wp.blocks.registerBlockType('ourblocktheme/campusarchive', {
    title: 'University Campus Archive',
    edit: function(){
        return wp.element.createElement('div', {className: 'our-placeholder-block'}, 'Campus Archive Block - Rendered on Frontend');
    },
    save: function(){
        return null;
    }
});