import { link } from '@wordpress/icons';
import { ToolbarGroup, ToolbarButton, Popover, Button, PanelBody, PanelRow, ColorPalette } from "@wordpress/components";
import { RichText, InspectorControls, BlockControls, __experimentalLinkControl as LinkControl, getColorObjectByColorValue, useBlockProps } from "@wordpress/block-editor";
import { useState } from '@wordpress/element';
import ourColors from '../../includes/ourColors';

export default function Edit(props) {
    const blockProps = useBlockProps();

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
    <div {...blockProps}>
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
    </div>
    )
}