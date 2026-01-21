<?php
/*
Plugin Name: Word Filter
Description: A plugin to filter specific words in content.
Version: 1.0
Author: Corey Sobke
Text Domain: wcpdomain
Domain Path: /languages
*/ 

if(!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class WordFilterPlugin {
    function __construct(){
        add_action('admin_menu', array($this, 'ourMenu'));
        if(get_option('plugin_words_to_filter', '')) {
            add_filter('the_content', array($this, 'filterLogic'));
        }
        add_action('init', array($this, 'languages'));
        add_action('admin_init', array($this, 'ourSettings'));
    }

    function ourSettings(){
        add_settings_section('replacement-text-section', null, null, 'word-filter-options');
        
        add_settings_field('replacement_text', 'Filtered Text', array($this, 'replacementFieldHTML'), 'word-filter-options', 'replacement-text-section');
        register_setting('replacementFields', 'replacementText');
    }

    function replacementFieldHTML(){
        $replacementText = get_option('replacementText', '****');
        ?>
        <input type="text" name="replacementText" value="<?php echo esc_attr($replacementText); ?>" />
        <p class="description">Enter the text that will replace filtered words. Default is "****".</p>
        <?php
    }

    function filterLogic($content){
        $wordList = explode(',', get_option('plugin_words_to_filter', ''));
        $wordsToFilter = array_map('trim', $wordList);
        if(!empty($wordsToFilter)) {
            $replacementText = get_option('replacementText', '****');
            $filteredContent = str_ireplace($wordsToFilter, esc_html($replacementText), $content);
            return $filteredContent;
        }
        return $content;
    }

    function languages(){
        load_plugin_textdomain('wcpdomain', false, dirname( plugin_basename( __FILE__ ) ) . '/languages');
    }

    function ourMenu(){
        $mainPageHook = add_menu_page('Words To Filter', 'Word Filter', 'manage_options', 'ourWordFilter', array($this, 'wordFilterPage'), 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHZpZXdCb3g9IjAgMCAyMCAyMCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik0xMCAyMEMxNS41MjI5IDIwIDIwIDE1LjUyMjkgMjAgMTBDMjAgNC40NzcxNCAxNS41MjI5IDAgMTAgMEM0LjQ3NzE0IDAgMCA0LjQ3NzE0IDAgMTBDMCAxNS41MjI5IDQuNDc3MTQgMjAgMTAgMjBaTTExLjk5IDcuNDQ2NjZMMTAuMDc4MSAxLjU2MjVMOC4xNjYyNiA3LjQ0NjY2SDEuOTc5MjhMNi45ODQ2NSAxMS4wODMzTDUuMDcyNzUgMTYuOTY3NEwxMC4wNzgxIDEzLjMzMDhMMTUuMDgzNSAxNi45Njc0TDEzLjE3MTYgMTEuMDgzM0wxOC4xNzcgNy40NDY2NkgxMS45OVoiIGZpbGw9IiNGRkRGOEQiLz4KPC9zdmc+', 100);
        // add_menu_page('Words To Filter', 'Word Filter', 'manage_options', 'ourWordFilter', array($this, 'wordFilterPage'), plugin_dir_url(__FILE__) . 'custom.svg', 100);
        // submenus
        add_submenu_page('ourWordFilter', 'Words To Filter', 'Word List', 'manage_options', 'ourWordFilter', array($this, 'wordFilterPage'));
        add_submenu_page('ourWordFilter', 'Word Filter Options', 'Options', 'manage_options', 'word-filter-options', array($this, 'optionsSubPage'));
        add_action("load-{$mainPageHook}", array($this, 'mainPageAssets'));
    }

    function mainPageAssets(){
        //enqueue scripts and styles
        wp_enqueue_style('wordFilterAdminCss', plugin_dir_url(__FILE__) . 'styles.css');
    }

    function handleForm(){
        if(isset($_POST['word_filter_nonce']) && wp_verify_nonce($_POST['word_filter_nonce'], 'word_filter_form') && current_user_can('manage_options')) {
            update_option('plugin_words_to_filter', sanitize_text_field($_POST['plugin_words_to_filter']));
            ?>
            <div class="updated notice is-dismissible">
                <p>Your filtered words have been saved.</p>
            </div>
            <?php
        } else {
            ?>
            <div class="error notice is-dismissible">
                <p>There was an error saving your filtered words. Please try again.</p>
            </div>
            <?php
            // wp_die('Sorry, your nonce did not verify or you do not have sufficient permissions.');
        }
    }

    function wordFilterPage(){
        ?>
        <div class="wrap">
            <h1>Word Filter</h1>
            <?php if(isset($_POST['just_submitted']) && $_POST['just_submitted'] == 'true') $this->handleForm() ?>
            <form method="post">
                <input type="hidden" name="just_submitted" value="true">
                <?php wp_nonce_field('word_filter_form', 'word_filter_nonce'); ?>
                <label for="plugin_words_to_filter">
                    <p>Enter a <strong>comma-separated</strong> list of words to filter from your site's content:</p>
                </label>
                <div class="word-filter__flex-container">
                    <textarea name="plugin_words_to_filter" id="plugin_words_to_filter" placeholder="bad,mean,awful..."><?php echo esc_textarea(get_option('plugin_words_to_filter', '')); ?></textarea>
                </div>
                <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
            </form>
        </div>
        <?php
    }

    function optionsSubPage(){
        ?>
        <div class="wrap">
            <h1>Word Filter Options</h1>
            <form method="post" action="options.php">
                <?php
                    settings_errors();
                    settings_fields('replacementFields');
                    do_settings_sections('word-filter-options');
                    submit_button();
                ?>
            </form>
        </div>
        <?php
    }
}

$wordFilterPlugin = new WordFilterPlugin();