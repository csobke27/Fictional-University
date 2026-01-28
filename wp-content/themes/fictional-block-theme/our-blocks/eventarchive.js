wp.blocks.registerBlockType('ourblocktheme/eventarchive', {
    title: 'Event Archive',
    edit: function(){
        return wp.element.createElement('div', {className: 'our-placeholder-block'}, 'Event Archive Block - Rendered on Frontend');
    },
    save: function(){
        return null;
    }
});