wp.blocks.registerBlockType('ourblocktheme/singleevent', {
    title: 'Single Event',
    edit: function(){
        return wp.element.createElement('div', {className: 'our-placeholder-block'}, 'Single Event Block - Rendered on Frontend');
    },
    save: function(){
        return null;
    }
});