import { registerBlockType } from '@wordpress/blocks';
import { useSelect } from '@wordpress/data';
import ServerSideRender from '@wordpress/server-side-render';
import { __ } from '@wordpress/i18n';
import {
    CheckboxControl,
    TextControl,
    Panel,
    PanelBody,
    PanelRow
} from '@wordpress/components'
import { 
    useBlockProps,
    InspectorControls,
 } from '@wordpress/block-editor';
 
registerBlockType( 'another-read/activity-block', {
    apiVersion: 2,
    title: 'AnotherRead activity',
    icon: 'book',
    category: 'common',

    attributes: { 
        numberOfPosts: {
            type: 'number',
            default: 1
        },
        tagsForPosts:{
            type: 'array',
            default: ''
        },
        jacketImage: {
            type: 'boolean',
            default: true
        },
        keynote: {
            type: 'boolean',
            default: true
        },
        authorLink: {
            type: 'boolean',
            default: true
        },
        bookLink: {
            type: 'boolean',
            default: true
        },

    
    },
    
    render_callback: 'activityBlockOutput',

    edit: function ( {attributes, setAttributes} ) {

        //console.log(attributes.numberOfPosts);

        const blockProps = useBlockProps();
        return (
            <div { ...blockProps }>
                <InspectorControls key="settings">
                    <Panel>
                        <PanelBody>
                            <PanelRow>
                                <TextControl label="Number of activity posts to show" type={('Input Type', 'number')} value={attributes.numberOfPosts} onChange={ ( event ) => setAttributes( {numberOfPosts: event})}></TextControl>
                            </PanelRow>
                            <PanelRow>
                                <TextControl label="Tags" type={('Input Type', 'text')} ></TextControl>
                            </PanelRow>
                            <PanelRow>
                                <CheckboxControl label="Display jacket image" checked={attributes.jacketImage} onChange={ ( event ) => setAttributes( {jacketImage: event})}></CheckboxControl>
                            </PanelRow>
                            <PanelRow>
                                <CheckboxControl label="Display keynote" checked={attributes.keynote} onChange={ ( event ) => setAttributes( {keynote: event})}></CheckboxControl>
                            </PanelRow>
                            <PanelRow>
                                <CheckboxControl label="Display author link" checked={attributes.authorLink} onChange={ ( event ) => setAttributes( {authorLink: event})}></CheckboxControl>
                            </PanelRow>
                            <PanelRow>
                                <CheckboxControl label="Display book link" checked={attributes.bookLink} onChange={ ( event ) => setAttributes( {bookLink: event})}></CheckboxControl>
                            </PanelRow>
                        </PanelBody>
                    </Panel>





 
                </InspectorControls>
                <ServerSideRender
                    block="another-read/activity-block"
                    attributes={ attributes }
                />
            </div>
        );
    },
    save: () => {return null},
} )