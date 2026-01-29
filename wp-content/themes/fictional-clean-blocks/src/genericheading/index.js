import { registerBlockType } from '@wordpress/blocks';
import { RichText } from "@wordpress/block-editor";
import metadata from './block.json';
import Edit from './edit';

registerBlockType(metadata.name, {
    title: 'Our Heading',
    edit: Edit,
    save: Save
});

function Save(props) {
    function getTagName(){
        if(props.attributes.size === 'large') {
            return 'h1';
        } else if(props.attributes.size === 'medium') {
            return 'h2';
        } else {
            return 'h3';
        }
    }
    return (
        <RichText.Content tagName={getTagName()} className={`headline headline--${props.attributes.size}`} value={props.attributes.text} />
    )
}