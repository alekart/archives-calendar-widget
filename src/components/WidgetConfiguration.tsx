import { useEffect, useState } from 'react';
import { forkJoin } from 'rxjs';
import { PanelBody } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { Taxonomy } from '@wordpress/core-data';
import { InspectorControls } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch';
import { ArcwConfig, ArcwModeConfig, CheckboxOptionValue } from '../interfaces';
import { CheckBoxes } from './CheckBoxes';
import { ModeSelector } from './ModeSelector';
import { ThemeSelector } from './ThemeSelector';

function postTypeToCheckbox(type: { slug: string; name: string; [k: string]: unknown; }) {
  return ({
    value: type.slug,
    label: type.name,
  });
}

function taxonomyToCheckbox(tax: { id: number; name: string; [k: string]: unknown; }) {
  return ({
    value: tax.id,
    label: tax.name,
  });
}

interface Props {
  attributes: ArcwConfig;
  onChange: (config: Partial<ArcwConfig>) => void;
}

export function WidgetConfiguration({attributes, onChange}: Props) {
  // TODO: check why component is executed many times on editor load
  console.log('attributes', attributes);
  const [wpCategories, setWpCategories] = useState([]);
  const [postTypeCheckboxes, setPostTypeCheckboxes] = useState([]);
  // eslint-disable-next-line
  const wpTaxonomies: any[] = useSelect((select) =>
      // eslint-disable-next-line
      // @ts-expect-error
      select('core').getEntityRecords('root', 'taxonomy')
    , []);

  const wpPostTypes = useSelect((select) =>
      // eslint-disable-next-line
      // @ts-expect-error
      select('core').getEntityRecords('root', 'postType')
    , []);


  useEffect(() => {
    console.log('use effect type');

    const exclude = ['attachmendt', 'paged'];
    // eslint-disable-next-line
    const types = wpPostTypes?.filter((t: any) => t.viewable && !exclude.includes(t.slug)) || [];
    // eslint-disable-next-line
    const typeCheckboxes = types.map((type: any) => postTypeToCheckbox(type));

    setPostTypeCheckboxes(typeCheckboxes);
  }, [wpPostTypes]);

  useEffect(() => {
    console.log('use effect tax');
    // TODO: find better typing or better way to select data
    const tax: Taxonomy[] = wpTaxonomies?.filter((t) => t.visibility.public) || [];

    forkJoin(tax.map((taxonomy: Taxonomy) =>
        apiFetch({path: `wp/v2/${taxonomy.rest_base}`}),
      // eslint-disable-next-line
    )).subscribe((results: any[]) => {

      // eslint-disable-next-line
      const newCategories: any[] = tax.reduce((accum: any[], taxonomy: Taxonomy, index: number) => {
        if (!results[index]?.length) {
          return accum;
        }
        return [
          ...accum,
          {
            slug: taxonomy.slug,
            labels: taxonomy.labels,
            // eslint-disable-next-line
            items: results[index].map((item: any) => taxonomyToCheckbox(item)),
          },
        ];
      }, []);
      // eslint-disable-next-line
      setWpCategories(newCategories as any);
    });
  }, [wpTaxonomies]);

  /**
   *
   * @param partialConfig
   * @type {{[k: string]: any}}
   */
  function updateConfig(partialConfig: Partial<ArcwConfig>) {
    onChange(partialConfig);
  }

  function handleModeChange(config: ArcwModeConfig) {
    console.log('handle mode change');
    updateConfig({
      mode: config.mode,
      firstMonth: config.firstMonth,
    });
  }

  function handlePostTypeSelect(selected: CheckboxOptionValue[]) {
    console.log('handle post type change');
    updateConfig({
      postTypes: selected.map((v) => `${v}`),
    });
  }

  function handleTaxSelect(slug: string, values: string[]) {
    const updateData = {
      ...attributes.categories,
      [slug]: values,
    };
    if (!values?.length) {
      delete updateData[slug];
    }
    updateConfig({
      categories: updateData,
    });
  }

  function handleThemeSelect(theme: string) {
    updateConfig({theme});
  }

  function getSelectedValuesForPostTypes() {
    return attributes.postTypes || [];
  }

  function getSelectedValuesForTaxonomy(taxSlug: string) {
    return attributes.categories?.[taxSlug] || [];
  }

  return <InspectorControls>
    <PanelBody title={__('Mode', 'arcw')}>
      <ModeSelector config={attributes} onChange={handleModeChange}></ModeSelector>
    </PanelBody>
    <PanelBody title={__('Theme', 'arcw')}>
      <ThemeSelector currentTheme={attributes.theme} onChange={handleThemeSelect}></ThemeSelector>
    </PanelBody>

    {wpCategories && wpCategories.map(
      // eslint-disable-next-line
      (tax: any) => (
        <PanelBody title={tax.labels.name} key={tax.slug}>
          <CheckBoxes checkboxes={tax.items}
                      selected={getSelectedValuesForTaxonomy(tax.slug)}
                      onSelectChange={(values: CheckboxOptionValue[]) => handleTaxSelect(tax.slug, values as string[])}
                      displaySelectAll={true}></CheckBoxes>
        </PanelBody>
      ))}
    <PanelBody title={__('Post types', 'arcw')}>
      <CheckBoxes checkboxes={postTypeCheckboxes}
                  selected={getSelectedValuesForPostTypes()}
                  onSelectChange={handlePostTypeSelect}
                  autoSelectIfNone="post"
                  displaySelectAll={true}></CheckBoxes>
    </PanelBody>
  </InspectorControls>
}
