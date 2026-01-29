import { registerBlockType } from '@wordpress/blocks';
import metadata from './block.json';
import Edit from './edit';

registerBlockType(metadata.name, {
    title: 'Our Button',
    edit: Edit,
    save: Save
});

function Save(props) {
    return <a href={props.attributes.linkObject ? props.attributes.linkObject.url : '#'} className={`btn btn--${props.attributes.size} btn--${props.attributes.colorName}`}>{props.attributes.text}</a>
}