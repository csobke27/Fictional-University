<?php
    function fictional_university_post_types() {
        // Register a custom post type for events
        register_post_type('event', array(
            'show_in_rest' => true,
            'supports' => array('title', 'editor', 'excerpt'),
            'rewrite' => array('slug' => 'events'),
            'has_archive' => true,
            'public' => true,
            'labels' => array(
                'name' => 'Events',
                'add_new_item' => 'Add New Event',
                'edit_item' => 'Edit Event',
                'all_items' => 'All Events',
                'singular_name' => 'Event',
            ),
            'menu_icon' => 'dashicons-calendar'
        ));

        // Register a custom post type for programs
        register_post_type('program', array(
            'show_in_rest' => true,
            'supports' => array('title', 'editor'),
            'rewrite' => array('slug' => 'programs'),
            'has_archive' => true,
            'public' => true,
            'labels' => array(
                'name' => 'Programs',
                'add_new_item' => 'Add New Program',
                'edit_item' => 'Edit Program',
                'all_items' => 'All Programs',
                'singular_name' => 'Program',
            ),
            'menu_icon' => 'dashicons-book-alt'
        ));
    }

    add_action('init', 'fictional_university_post_types');
?>