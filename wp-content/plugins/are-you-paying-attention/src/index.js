import { TextControl, Flex, FlexBlock, FlexItem, Button, Icon, PanelBody, PanelRow, ColorPicker } from '@wordpress/components';
import { InspectorControls, BlockControls, AlignmentToolbar, useBlockProps } from '@wordpress/block-editor';
import { ChromePicker } from 'react-color';
import './index.scss';

(function ourStartFunction() {

    let locked = false;
    wp.data.subscribe(function () {
        const results = wp.data.select('core/block-editor').getBlocks().filter(function (block) {
            return block.name === "ourplugin/are-you-paying-attention" && block.attributes.correctAnswer == undefined;
        });
        if (results.length && !locked) {
            locked = true;
            wp.data.dispatch('core/editor').lockPostSaving('noanswer');
        } else if (!results.length && locked) {
            locked = false;
            wp.data.dispatch('core/editor').unlockPostSaving('noanswer');
        }
    });
})();

var attributes = {
    question: { type: 'string' },
    answers: { type: 'array', default: [""] },
    correctAnswer: { type: 'number', default: undefined },
    bgColor: { type: 'string', default: '#ffffff' },
    theAlignment: { type: 'string', default: 'left' }
}

wp.blocks.registerBlockType('ourplugin/are-you-paying-attention', {
    title: 'Are You Paying Attention?',
    icon: 'smiley',
    category: 'common',
    attributes: attributes,
    edit: EditComponent,
    save: (props) => {
        return null; // Rendering in PHP
    },
    example: {
        attributes: {
            question: 'What is 2 + 2?',
            answers: ['3', '4', '5'],
            correctAnswer: 1,
            bgColor: '#31a0c2ff',
            theAlignment: 'center'
        }
    },
    description: 'Adds a quiz question to see if your readers are paying attention.'
});

function EditComponent(props) {
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