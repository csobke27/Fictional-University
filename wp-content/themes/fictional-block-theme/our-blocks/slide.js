import { InnerBlocks, InspectorControls, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { registerBlockType } from '@wordpress/blocks';
import { PanelBody, PanelRow, Button } from '@wordpress/components';
import apiFetch from '@wordpress/api-fetch';
import { useEffect } from '@wordpress/element';

registerBlockType('ourblocktheme/slide', {
    title: 'Slide',
    supports: {
        align: ['full']
    },
    attributes: {
        align: { type: 'string', default: 'full' },
        imgId: { type: 'number' },
        imgUrl: { type: 'string', default: bannerData.fallbackimage }
    },
    edit: EditComponent,
    save: SaveComponent
});

function EditComponent(props) {
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
                <div className="hero-slider__slide" style={{backgroundImage: `url(${props.attributes.imgUrl})`}}>
                    <div className="hero-slider__interior container">
                        <div class="hero-slider__overlay t-center">
                            <InnerBlocks allowedBlocks={['core/heading', 'core/paragraph', 'core/list', 'ourblocktheme/genericheading', 'ourblocktheme/genericbutton']} />
                        </div>
                    </div>
                </div>
        </>
    );
            
}

function SaveComponent(props) {
    return <InnerBlocks.Content />;
}