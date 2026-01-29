import { TextControl, Flex, FlexBlock, FlexItem, Button, Icon, PanelBody, PanelRow, ColorPicker } from '@wordpress/components';
import { InspectorControls, BlockControls, AlignmentToolbar } from '@wordpress/block-editor';
import { ChromePicker } from 'react-color';

/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { useBlockProps } from '@wordpress/block-editor';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @param {Object}   props               Properties passed to the function.
 * @param {Object}   props.attributes    Available block attributes.
 * @param {Function} props.setAttributes Function that updates individual attributes.
 *
 * @return {Element} Element to render.
 */
export default function Edit( props ) {
	const blockProps = useBlockProps({className:"paying-attention-edit-block", style: { backgroundColor: props.attributes.bgColor }});

	function updateQuestion(value) {
        props.setAttributes({ question: value });
    }

    function updateAnswer(value, index) {
        const newAnswers = [...props.attributes.answers];
        newAnswers[index] = value;
        props.setAttributes({ answers: newAnswers });
    }

    function deleteAnswer(index) {
        if (props.attributes.correctAnswer === index) {
            props.setAttributes({ correctAnswer: undefined });
        }
        if (props.attributes.correctAnswer > index) {
            props.setAttributes({ correctAnswer: props.attributes.correctAnswer - 1 });
        }
        const newAnswers = [...props.attributes.answers];
        newAnswers.splice(index, 1);
        props.setAttributes({ answers: newAnswers });
    }

    function addAnswer() {
        const newAnswers = [...props.attributes.answers];
        newAnswers.push(undefined);
        props.setAttributes({ answers: newAnswers });
    }

    function markAsCorrect(index) {
        props.setAttributes({ correctAnswer: index });
    }

	return (
        <div {...blockProps}>
            <BlockControls>
                <AlignmentToolbar value={props.attributes.theAlignment} onChange={(newAlign) => {
                    props.setAttributes({ theAlignment: newAlign });
                }} />
            </BlockControls>
            <InspectorControls>
                <PanelBody title="Background Color" initialOpen={true}>
                    <PanelRow>
                        <ChromePicker color={props.attributes.bgColor} onChangeComplete={(value) => props.setAttributes({ bgColor: value.hex })} disableAlpha={true} />
                    </PanelRow>
                </PanelBody>
            </InspectorControls>
            <TextControl style={{ fontSize: '20px' }} label="Question:" value={props.attributes.question} onChange={updateQuestion} />
            <p style={{ fontSize: '13px', margin: '20px 0 8px 0' }}>Answers:</p>
            {/* Each answer would be mapped here in a real implementation */}
            {(props.attributes.answers || []).map((answer, index) => (
                <Flex key={index}>
                    <FlexBlock>
                        <TextControl autoFocus={answer === undefined} value={answer} onChange={(value) => {
                            updateAnswer(value, index);
                        }} />
                    </FlexBlock>
                    <FlexItem>
                        <Button onClick={() => {
                            markAsCorrect(index);
                        }}>
                            <Icon className="mark-as-correct" icon={props.attributes.correctAnswer === index ? "star-filled" : "star-empty"} />
                        </Button>
                    </FlexItem>
                    <FlexItem>
                        <Button isLink className="attention-delete" onClick={() => {
                            deleteAnswer(index);
                        }}>Delete</Button>
                    </FlexItem>
                </Flex>
            ))}
            <Button isPrimary onClick={() => {
                addAnswer();
            }}>Add another answer</Button>
        </div>
    )
}
