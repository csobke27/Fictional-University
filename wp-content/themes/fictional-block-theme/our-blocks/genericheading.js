import { ToolbarGroup, ToolbarButton } from "@wordpress/components";
import { RichText, BlockControls } from "@wordpress/block-editor";
import { registerBlockType } from '@wordpress/blocks';

registerBlockType('ourblocktheme/genericheading', {
    title: 'Generic Heading',
    attributes: {
        text: {type: 'string' },
        size: { type: 'string', default: 'large' }
    },
    edit: EditComponent,
    save: SaveComponent
});

function EditComponent(props) {
    function handleTextChange(newText) {
        props.setAttributes({ text: newText });
    }

    return (
        <>
        <BlockControls>
            <ToolbarGroup>
                <ToolbarButton isActive={props.attributes.size === 'large'} onClick={() => props.setAttributes({ size: 'large' })}>Large</ToolbarButton>
                <ToolbarButton isActive={props.attributes.size === 'medium'} onClick={() => props.setAttributes({ size: 'medium' })}>Medium</ToolbarButton>
                <ToolbarButton isActive={props.attributes.size === 'small'} onClick={() => props.setAttributes({ size: 'small' })}>Small</ToolbarButton>
            </ToolbarGroup>
        </BlockControls>
        <RichText allowedFormats={["core/bold", "core/italic"]} tagName="h1" className={`headline headline--${props.attributes.size}`} value={props.attributes.text} onChange={handleTextChange}/> 
        </>
    );
}

function SaveComponent(props) {
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