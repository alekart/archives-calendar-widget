import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import {
    Panel,
    PanelBody,
    TextControl,
} from '@wordpress/components';
import { Component } from '@wordpress/element';

import api from '@wordpress/api';


import './editor.scss';

class OptionsExample extends Component {
    constructor() {
        super( ...arguments );
        this.state = { exampleText: '',
        isAPILoaded: false,
     };
    }

    componentDidMount() {
        api.loadPromise.then( () => {
          this.settings = new api.models.Settings();
      
          const { isAPILoaded } = this.state;
      
          if ( isAPILoaded === false ) {
            this.settings.fetch().then( ( response ) => {
              this.setState( {
                exampleText: response[ 'wholesomecode_wholesome_plugin_example_text' ],
                isAPILoaded: true,
              } );
            } );
          }
        } );
      }

    render() {
        const { exampleText, isAPILoaded } = this.state;

        return (
            <Panel>
                <PanelBody
                    title={ __( 'Example Meta Box', 'wholesome-plugin' ) }
                    icon="admin-plugins"
                >
                    <TextControl
                        help={ __( 'This is an example text field.', 'wholesome-plugin' ) }
                        label={ __( 'Example Text', 'wholesome-plugin' ) }
                        onChange={ ( exampleText ) => this.setState( { exampleText } ) }
                        value={ exampleText }
                    />
                </PanelBody>
            </Panel>
        )
    }
}

export default function Edit( props ) {
    return (
        <div { ...useBlockProps() }>
            <OptionsExample { ...props }/>
        </div>
    );
}