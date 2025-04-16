<?php
    function pageBanner($args = NULL){
        if(!isset($args['title'])) {
            $args['title'] = get_the_title();
        }
        if(!isset($args['subtitle'])) {
            $args['subtitle'] = get_field('page_banner_subtitle');
        }
        if(!isset($args['photo'])) {
            if(get_field('page_banner_background_image') && !is_archive() && !is_home()) {
                $args['photo'] = get_field('page_banner_background_image')['sizes']['pageBanner'];
            } else {
                $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
            }
        }?>
        <div class="page-banner">
            <div class="page-banner__bg-image" style="background-image: url(
                <?php echo $args['photo'];?>)">
            </div>
            <div class="page-banner__content container container--narrow">
                <h1 class="page-banner__title"><?php echo $args['title'];?></h1>
                <div class="page-banner__intro">
                <p><?php echo $args["subtitle"]; ?></p>
                </div>
            </div>
        </div>
        <?php
    }

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
        // Add support for custom menus
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
        add_image_size('professorLandscape', 400, 260, true);
        add_image_size('professorPortrait', 480, 650, true);
        add_image_size('pageBanner', 1500, 350, true);
    }

    add_action('after_setup_theme', 'fictional_university_features');

    // Add custom post query for events and if the query is the main query
    // and if the query is not in the admin area
    function fictional_university_adjust_queries($query) {
        if(!is_admin() && is_post_type_archive('campus') && $query->is_main_query()) {
            $query->set('orderby', 'title');
            $query->set('order', 'ASC');
            $query->set('posts_per_page', -1);
        }
        if(!is_admin() && is_post_type_archive('program') && $query->is_main_query()) {
            $query->set('orderby', 'title');
            $query->set('order', 'ASC');
            $query->set('posts_per_page', -1);
        }
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


    function universityMapKey($api){
        $api['key'] = 'YOUR_GOOGLE_MAPS_API_KEY_HERE'; // Replace with your actual Google Maps API key
        return $api;
    }

    add_filter('acf/fields/google_map/api', 'universityMapKey');
?>