wp.blocks.registerBlockType('ourblocktheme/archive', {
    title: 'University Archive',
    edit: function(){
        return wp.element.createElement('div', {className: 'our-placeholder-block'}, 'Archive Block - Rendered on Frontend');
    },
    save: function(){
        return null;
    }
});