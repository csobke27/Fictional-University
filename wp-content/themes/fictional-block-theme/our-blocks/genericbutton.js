import { link } from '@wordpress/icons';
import { ToolbarGroup, ToolbarButton, Popover, Button, PanelBody, PanelRow, ColorPalette } from "@wordpress/components";
import { RichText, InspectorControls, BlockControls, __experimentalLinkControl as LinkControl, getColorObjectByColorValue } from "@wordpress/block-editor";
import { registerBlockType } from '@wordpress/blocks';
import { useState } from '@wordpress/element';
import ourColors from '../includes/ourColors';

registerBlockType('ourblocktheme/genericbutton', {
    title: 'Generic Button',
    attributes: {
        text: {type: 'string' },
        size: { type: 'string', default: 'large' },
        colorName: { type: 'string', default: 'blue' },
        linkObject: { type: 'object' }
    },
    edit: EditComponent,
    save: SaveComponent
});

function EditComponent(props) {
    const [isLinkPickerVisible, setIsLinkPickerVisible] = useState(false);
    function handleTextChange(newText) {
        props.setAttributes({ text: newText });
    }

    function buttonHandler() {
        setIsLinkPickerVisible(!isLinkPickerVisible);
    }

    function handleLinkChange(newLink) {
        props.setAttributes({ linkObject: newLink });
    }

    const currentColorValue = ourColors.find(color => color.name === props.attributes.colorName).color;

    function handleColorChange(colorCode) {
        const newColor = getColorObjectByColorValue(ourColors, colorCode);
        props.setAttributes({ colorName: newColor.name });
    }

    return (
        <>
        <BlockControls>
            <ToolbarGroup>
                <ToolbarButton onClick={buttonHandler} icon={link}/>
            </ToolbarGroup>
            <ToolbarGroup>
                <ToolbarButton isActive={props.attributes.size === 'large'} onClick={() => props.setAttributes({ size: 'large' })}>Large</ToolbarButton>
                <ToolbarButton isActive={props.attributes.size === 'medium'} onClick={() => props.setAttributes({ size: 'medium' })}>Medium</ToolbarButton>
                <ToolbarButton isActive={props.attributes.size === 'small'} onClick={() => props.setAttributes({ size: 'small' })}>Small</ToolbarButton>
            </ToolbarGroup>
        </BlockControls>
        <InspectorControls>
            <PanelBody title="Color" initialOpen={true}>
                <PanelRow>
                    <ColorPalette disableCustomColors={true} clearable={false} colors={ourColors} value={currentColorValue} onChange={handleColorChange} />
                </PanelRow>
            </PanelBody>
        </InspectorControls>
        <RichText allowedFormats={[]}  tagName="a" className={`btn btn--${props.attributes.size} btn--${props.attributes.colorName}`} value={props.attributes.text} onChange={handleTextChange}/> 
        {isLinkPickerVisible && (
            <Popover position="middle center" onFocusOutside={() => setIsLinkPickerVisible(false)}>
                <LinkControl settings={[]} value={props.attributes.linkObject} onChange={handleLinkChange} />
                <Button variant="primary" onClick={() => setIsLinkPickerVisible(false)} style={{display: 'block', width: '100%'}}>Confirm Link</Button>
            </Popover>
        )}
        </>
    );
}

function SaveComponent(props) {
    return (
        <a href={props.attributes.linkObject ? props.attributes.linkObject.url : '#'} className={`btn btn--${props.attributes.size} btn--${props.attributes.colorName}`}>{props.attributes.text}</a>
    )
}