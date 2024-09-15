/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import {__} from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import {InspectorControls, useBlockProps} from '@wordpress/block-editor';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';
import {CheckboxControl, PanelBody, SelectControl} from '@wordpress/components';
import {useSelect} from '@wordpress/data';
import {useEffect, useState} from 'react';
import {forkJoin} from 'rxjs';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {Element} Element to render.
 */
export default function edit({attributes, setAttributes}) {
  /**
   * @type {any[]}
   */
  const wpTaxonomies = useSelect((select) =>
      select('core').getEntityRecords('root', 'taxonomy')
    , []);

  let wpPostTypes = useSelect((select) =>
      select('core').getEntityRecords('root', 'postType')
    , []);

  const [wpCategories, setWpCategories] = useState([]);
  const [arcwCategories, setArcwCategories] = useState(attributes.categories || {});

  const [wpTypes, setWpTypes] = useState([]);
  const [arcwPostTypes, setArcwPostTypes] = useState(attributes.postTypes);

  useEffect(() => {
    const types = wpPostTypes?.filter((t) => t.viewable) || [];
    setWpTypes(types);
  }, [wpPostTypes]);

  useEffect(() => {
    const tax = wpTaxonomies?.filter((t) => t.visibility.public) || [];

    forkJoin(tax.map(taxonomy =>
      apiFetch({path: `wp/v2/${taxonomy.rest_base}`}),
    )).subscribe((results) => {
      let catIds = [];
      let newCategories = tax.reduce((accum, taxonomy, index) => {
        if (!results[index]?.length) {
          return accum;
        }
        return [
          ...accum,
          {
            slug: taxonomy.slug,
            labels: taxonomy.labels,
            items: results[index],
          },
        ];
      }, []);
      setWpCategories(newCategories);
    });
  }, [wpTaxonomies]);

  const handleOnChange = (checked, id) => {
    const checks = {
      ...arcwCategories,
      [`${id}`]: checked,
    };
    setArcwCategories(checks);
    setAttributes({categories: checks});
  };

  const handleTypeOnChange = (checked, id) => {
    const checks = {
      ...arcwPostTypes,
      [`${id}`]: checked,
    };
    if(Object.values(checks).every((v) => v === false)){
      checks['post'] = true;
    }
    setArcwPostTypes(checks);
    setAttributes({postTypes: checks});
  };

  const selectMonth = (enabled) => {
    if (enabled) {
      return <SelectControl
        label="fist month"
        value={attributes.firstMonth}
        help="Select the first month to display"
        options={[
          {label: __('Current month', 'arcw'), value: 'current'},
          {label: __('Latest month', 'arcw'), value: 'latest'},
          {label: __('Next month', 'arcw'), value: 'next'},
          {label: __('Previous month', 'arcw'), value: 'previous'},
        ]}
        selected={attributes.firstMonth}
        onChange={(value) => setAttributes({firstMonth: value})}
      />;
    }
    return null;
  };

  return (
    <>
      <InspectorControls>
        <PanelBody title={__('Mode', 'arcw')}>
          <SelectControl
            label="Display mode"
            value={attributes.mode}
            options={[
              {label: __('Month', 'arcw'), value: 'month'},
              {label: __('Year', 'arcw'), value: 'year'},
            ]}
            selected={attributes.mode}
            onChange={(value) => setAttributes({mode: value})}
          />
          {selectMonth(attributes.mode === 'month')}
        </PanelBody>
        {wpCategories && wpCategories.map(tax => (
          <PanelBody title={tax.labels.name} key={tax.slug}>
            {tax.items && tax.items.map(category => (
              <CheckboxControl
                key={category.id}
                label={category.name}
                checked={arcwCategories[`${category.id}`] === true}
                onChange={(checked) => handleOnChange(checked, category.id)}
              />
            ))}
          </PanelBody>
        ))}
        <PanelBody title={__('Post types', 'arcw')}>
          {wpTypes && wpTypes.map(type => (
            <CheckboxControl
              parent={'allTypes'}
              key={type.slug}
              label={type.labels.name}
              checked={arcwPostTypes[`${type.slug}`] === true}
              onChange={(checked) => handleTypeOnChange(checked, type.slug)}
            />
          ))}
        </PanelBody>
      </InspectorControls>

      <div {...useBlockProps()}>
        Calendar will be showed here
      </div>
    </>
  );
}
