wp.blocks.registerBlockType('ourblocktheme/pastevents', {
    title: 'Past Events',
    edit: function(){
        return wp.element.createElement('div', {className: 'our-placeholder-block'}, 'Past Events Block - Rendered on Frontend');
    },
    save: function(){
        return null;
    }
});