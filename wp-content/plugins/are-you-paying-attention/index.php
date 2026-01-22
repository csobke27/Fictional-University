<?php
/*
Plugin Name: Are You Paying Attention Quiz
Description: Give your readers a multiple choice quiz
Version: 1.0
Author: Corey Sobke
Text Domain: wcpdomain
Domain Path: /languages
*/ 

if(!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class AreYouPayingAttentionPlugin {
    function __construct(){
        // add_action('admin_menu', array($this, 'ourMenu'));
        // add_action('init', array($this, 'languages'));
        // add_action('admin_init', array($this, 'ourSettings'));
        add_action('init', array($this, 'adminAssets'));
    }

    function languages(){
        load_plugin_textdomain('wcpdomain', false, dirname( plugin_basename( __FILE__ ) ) . '/languages');
    }

    function adminAssets(){
        register_block_type(__DIR__, array(
            'render_callback' => array($this, 'theHTML')
        ));
    }

    function theHTML($attributes){
        // if(!is_admin()){
        //     // wp_enqueue_script('attentionFrontend', plugin_dir_url(__FILE__) . 'build/frontend.js', array('wp-element', 'wp-blocks'));
        //     // wp_enqueue_style('attentionFrontendCSS', plugin_dir_url(__FILE__) . 'build/frontend.css');
        // }
        ob_start(); ?>
            <div class="paying-attention-update-me"><pre style="display:none;"><?php echo wp_json_encode($attributes); ?></pre></div>
        <?php
        return ob_get_clean();
    }
}

$areYouPayingAttentionPlugin = new AreYouPayingAttentionPlugin();