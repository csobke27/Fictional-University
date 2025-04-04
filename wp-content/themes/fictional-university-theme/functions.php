<?php
    function fictional_university_files() {
        // Enqueue the main stylesheet
        wp_enqueue_script('main-university.js', get_theme_file_uri('/build/index.js'), array('jquery'), '1.0', true);
        wp_enqueue_style('custom-google-fonts', 'https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
        wp_enqueue_style('font_awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
        wp_enqueue_style('fictional_university_main_styles', get_theme_file_uri('/build/style-index.css'));
        wp_enqueue_style('fictional_university_extra_styles', get_theme_file_uri('/build/index.css'));
        // Enqueue a custom script
        // wp_enqueue_script('fictional_university_custom_script', get_template_directory_uri() . '/js/custom.js', array(), '1.0', true);
    }
    add_action('wp_enqueue_scripts', 'fictional_university_files');

    function fictional_university_features() {
        // register_nav_menu('headerMenuLocation', 'Header Menu Location');
        // register_nav_menu('footerLocationOne', 'Footer Location One');
        // register_nav_menu('footerLocationTwo', 'Footer Location Two');
        // add support for title tag
        add_theme_support('title-tag');
    }

    add_action('after_setup_theme', 'fictional_university_features');

    // Add custom post query for events and if the query is the main query
    // and if the query is not in the admin area
    function fictional_university_adjust_queries($query) {
        if(!is_admin() && is_post_type_archive('event') && $query->is_main_query()) {
            $today = date('Ymd');
            $query->set('meta_key', 'event_date');
            $query->set('orderby', 'meta_value_num');
            $query->set('order', 'ASC');
            $query->set('meta_query', array(
                array(
                    'key' => 'event_date',
                    'compare' => '>=',
                    'value' => $today,
                    'type' => 'numeric'
                )
            ));
        }
    }

    add_action('pre_get_posts', 'fictional_university_adjust_queries');
?>