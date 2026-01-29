import { InnerBlocks, InspectorControls, MediaUpload, MediaUploadCheck, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, PanelRow, Button } from '@wordpress/components';
import apiFetch from '@wordpress/api-fetch';
import { useEffect } from '@wordpress/element';

export default function Edit(props) {
    const blockProps = useBlockProps();

    useEffect(function() {
        if(props.attributes.themeimage) {
            props.setAttributes({ imgUrl:  `${ourThemeData.themePath}/images/${props.attributes.themeimage}`});
        }
        if(!props.attributes.themeimage && !props.attributes.imgUrl){
            props.setAttributes({ imgUrl: `${ourThemeData.themePath}/images/library-hero.jpg` });
        }
    }, []);

    useEffect(function() {
        if(props.attributes.imgId) {
            async function fetchImageUrl() {
                const response = await apiFetch({ path: `/wp/v2/media/${props.attributes.imgId}`, method: 'GET' });
                props.setAttributes({ themeimage: "", imgUrl: response.media_details.sizes.pageBanner.source_url });
            }
            fetchImageUrl();
        }
    }, [props.attributes.imgId]);

    

    function onFileSelect(media) {
        props.setAttributes({imgId: media.id});
    }
    return (
        <>
        <InspectorControls>
            <PanelBody title="Background" initialOpen={true}>
                <PanelRow>
                    <MediaUploadCheck>
                        <MediaUpload 
                            onSelect={onFileSelect}
                            value={props.attributes.imgId}
                            render={({ open }) => (
                                <Button onClick={open} variant="secondary">Choose Image</Button>
                            )}
                        />
                    </MediaUploadCheck>
                </PanelRow>
            </PanelBody>
        </InspectorControls>
        <div {...blockProps}>
            <div className="hero-slider__slide" style={{backgroundImage: `url(${props.attributes.imgUrl})`}}>
                <div className="hero-slider__interior container">
                    <div className="hero-slider__overlay t-center">
                        <InnerBlocks allowedBlocks={['core/heading', 'core/paragraph', 'core/list', 'ourblocktheme/genericheading', 'ourblocktheme/genericbutton']} />
                    </div>
                </div>
            </div>
        </div>
        </>
    )
}