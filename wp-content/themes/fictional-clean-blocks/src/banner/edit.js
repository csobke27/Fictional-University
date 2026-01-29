import { InnerBlocks, InspectorControls, MediaUpload, MediaUploadCheck, useBlockProps } from '@wordpress/block-editor';
import { registerBlockType } from '@wordpress/blocks';
import { PanelBody, PanelRow, Button } from '@wordpress/components';
import apiFetch from '@wordpress/api-fetch';
import { useEffect } from '@wordpress/element';

export default function Edit(props) {
    const blockProps = useBlockProps();
    useEffect(function() {
        if(!props.attributes.imgUrl) {
            props.setAttributes({ imgUrl: ourThemeData.themePath + '/images/library-hero.jpg' });
        }
    }, []);
    useEffect(function() {
        if(props.attributes.imgId) {
            async function fetchImageUrl() {
                const response = await apiFetch({ path: `/wp/v2/media/${props.attributes.imgId}`, method: 'GET' });
                props.setAttributes({ imgUrl: response.media_details.sizes.pageBanner.source_url });
            }
            fetchImageUrl();
        }
    }, [props.attributes.imgId]);

    function onFileSelect(media) {
        props.setAttributes({imgId: media.id});
    }
    return (
    <div {...blockProps}>
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
        <div className="page-banner">
            <div className="page-banner__bg-image" style={{backgroundImage: `url(${props.attributes.imgUrl})`}}></div>
            <div className="page-banner__content container t-center c-white">
                <InnerBlocks allowedBlocks={['core/heading', 'core/paragraph', 'core/list', 'ourblocktheme/genericheading', 'ourblocktheme/genericbutton']} />
            </div>
        </div>
    </div>
    )
}