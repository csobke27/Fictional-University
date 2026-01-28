wp.blocks.registerBlockType('ourblocktheme/header', {
    title: 'Our Header',
    edit: function(){
        return wp.element.createElement('div', {className: 'our-placeholder-block'}, 'Header Block - Rendered on Frontend');
    },
    save: function(){
        return null;
    }
});