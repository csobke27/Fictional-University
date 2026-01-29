import { registerBlockType } from '@wordpress/blocks';
import metadata from './block.json';
import Edit from './edit';
import { InnerBlocks } from '@wordpress/block-editor';

registerBlockType(metadata.name, {
    title: 'Our Banner',
    edit: Edit,
    save: () => {return <InnerBlocks.Content />;}
});