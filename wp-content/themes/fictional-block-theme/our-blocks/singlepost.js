wp.blocks.registerBlockType('ourblocktheme/singlepost', {
    title: 'University Single Post',
    edit: function(){
        return wp.element.createElement('div', {className: 'our-placeholder-block'}, 'Single Post Block - Rendered on Frontend');
    },
    save: function(){
        return null;
    }
});