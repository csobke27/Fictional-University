wp.blocks.registerBlockType('ourblocktheme/page', {
    title: 'University Page',
    edit: function(){
        return wp.element.createElement('div', {className: 'our-placeholder-block'}, 'Page Block - Rendered on Frontend');
    },
    save: function(){
        return null;
    }
});