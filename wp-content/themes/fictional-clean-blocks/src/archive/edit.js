import { useBlockProps } from '@wordpress/block-editor';

export default function Edit() {
    const blockProps = useBlockProps();
    return (
    <div {...blockProps}>
        <div className='our-placeholder-block'>Archive block - Rendered on Frontend</div>
    </div>
    )
}