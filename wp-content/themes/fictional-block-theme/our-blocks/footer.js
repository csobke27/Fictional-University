wp.blocks.registerBlockType('ourblocktheme/footer', {
    title: 'Our Footer',
    edit: function(){
        return wp.element.createElement('div', {className: 'our-placeholder-block'}, 'Footer Block - Rendered on Frontend');
    },
    save: function(){
        return null;
    }
});