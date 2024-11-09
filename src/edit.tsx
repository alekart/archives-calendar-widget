/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { useBlockProps } from '@wordpress/block-editor';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';
import { WidgetConfiguration } from './components';
import { Calendar } from './components/calendar/Calendar';
import { ArcwConfig } from './interfaces';


/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 */
export default function edit({attributes, setAttributes}: {
  attributes: ArcwConfig,
  setAttributes: (attrs: Partial<ArcwConfig>) => void
}) {
  function handleConfigUpdate(config: Partial<ArcwConfig>) {
    setAttributes({
      ...attributes,
      ...config,
    })
  }

  return (
    <>
      <WidgetConfiguration attributes={attributes} onChange={handleConfigUpdate}/>

      <div {...useBlockProps()}>
        <Calendar config={attributes}/>
      </div>
    </>
  );
}
