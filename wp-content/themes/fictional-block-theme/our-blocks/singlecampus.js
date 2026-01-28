wp.blocks.registerBlockType('ourblocktheme/singlecampus', {
    title: 'Single Campus',
    edit: function(){
        return wp.element.createElement('div', {className: 'our-placeholder-block'}, 'Single Campus Block - Rendered on Frontend');
    },
    save: function(){
        return null;
    }
});