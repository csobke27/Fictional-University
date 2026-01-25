<?php

/*
  Plugin Name: Featured Professor Block Type
  Version: 1.0
  Author: Your Name Here
  Author URI: https://www.udemy.com/user/bradschiff/
*/

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require_once plugin_dir_path(__FILE__) . 'inc/generateProfessorHTML.php';
require_once plugin_dir_path(__FILE__) . 'inc/relatedPostsHTML.php';

class FeaturedProfessor {
  function __construct() {
    add_action('init', [$this, 'onInit']);
    add_action('rest_api_init', [$this, 'profHTML']);
    add_filter('the_content', [$this, 'addRelatedPosts']);
  }

  function addRelatedPosts($content){
    if(is_singular('professor') && in_the_loop() && is_main_query()) {
      return $content . relatedPostsHTML(get_the_ID());
    }
    return $content;
  }

  function profHTML(){
    register_rest_route('featuredProfessor/v1', '/getHTML', array(
      'methods' => WP_REST_Server::READABLE,
      'callback' => [$this, 'getProfHTML']
      // 'callback' => function($data) {
      //   return generateProfessorHTML($data['id']);
      // }
    ));
  }

  function getProfHTML($data) {
    return generateProfessorHTML($data['profId']);
  }

  function onInit() {
    register_meta('post', 'featuredprofessor', array(
      'show_in_rest' => true,
      'single' => false,
      'type' => 'number',
    ));
    // wp_register_script('featuredProfessorScript', plugin_dir_url(__FILE__) . 'build/index.js', array('wp-blocks', 'wp-i18n', 'wp-editor'));
    // wp_register_style('featuredProfessorStyle', plugin_dir_url(__FILE__) . 'build/index.css');

    register_block_type(__DIR__, array(
      'render_callback' => [$this, 'renderCallback'],
      // 'editor_script' => 'featuredProfessorScript',
      // 'editor_style' => 'featuredProfessorStyle'
    ));
  }

  function renderCallback($attributes) {
    if( $attributes['profId']) {
      wp_enqueue_style('featuredProfessorStyle', plugin_dir_url(__FILE__) . 'build/index.css');
      return generateProfessorHTML($attributes['profId']);
    } else {
      return "<p>Please select a professor in the block settings.</p>";
    }
  }

}

$featuredProfessor = new FeaturedProfessor();