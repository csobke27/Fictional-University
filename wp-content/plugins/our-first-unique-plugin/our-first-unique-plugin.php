<?php
/*
Plugin Name: Our First Unique Plugin
Description: A plugin to demonstrate unique functionality.
Version: 1.0
Author: Corey Sobke
Text Domain: wcpdomain
Domain Path: /languages
*/  

class WordCountAndTimePlugin {
    function __construct(){
        add_action('admin_menu', array($this, 'adminPage'));
        add_action('admin_init', array($this, 'settings'));
        add_filter('the_content', array($this, 'ifWrap'));
        add_action('init', array($this, 'languages'));
    }

    function languages(){
        load_plugin_textdomain('wcpdomain', false, dirname( plugin_basename( __FILE__ ) ) . '/languages');
    }

    function ifWrap($content){
        if(is_singular('post') && is_main_query() && (get_option('wcp_wordcount', '1') || get_option('wcp_charcount', '1') || get_option('wcp_readtime', '1'))) {
            return $this->wordCountAndTime($content);
        } else {
            return $content;
        }
    }

    function wordCountAndTime($content){
        // return $content . ' hello';
        $location = get_option('wcp_location', '0');
        $wordCount = get_option('wcp_wordcount', '1');
        $charCount = get_option('wcp_charcount', '1');
        $readTime = get_option('wcp_readtime', '1');
        $headline = get_option('wcp_headline', 'Post Statistics');

        $statsHTML = '<h3>' . esc_html($headline) . '</h3><p>';
        if($wordCount) {
            $statsHTML .= esc_html__('Word Count:', 'wcpdomain') . ' ' . strval(str_word_count(strip_tags(get_the_content()))) . '<br>';
        }
        if($charCount) {
            $statsHTML .= esc_html__('Character Count:', 'wcpdomain') . ' ' . strval(strlen(strip_tags(get_the_content()))) . '<br>';
        }
        if($readTime) {
            $wordsPerMinute = 200;
            $totalWords = str_word_count(strip_tags(get_the_content()));
            $minutes = ceil($totalWords / $wordsPerMinute);
            $statsHTML .= esc_html__('Estimated Read Time:', 'wcpdomain') . ' ' . strval($minutes) . ' ' . esc_html__('minute(s)', 'wcpdomain') . '<br>';
        }
        $statsHTML .= '</p>';

        if($location == '1') {
            return $statsHTML . $content;
        } else {
            return $content . $statsHTML;
        }
    }

    function adminPage() {
        add_options_page('Word Count Settings', __('Word Count', 'wcpdomain'), 'manage_options', 'word-count-settings-page', array($this, 'ourHTML'));
    }

    function ourHTML() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html__('Word Count Settings', 'wcpdomain'); ?></h1>
            <form method="post" action="options.php">
                <?php
                    settings_fields('wordcountplugin');
                    do_settings_sections('word-count-settings-page');
                    submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    function settings() {
        add_settings_section('wcp_first_section', null, null, 'word-count-settings-page');

        add_settings_field('wcp_location', 'Display Location', array($this, 'locationHTML'), 'word-count-settings-page', 'wcp_first_section');
        register_setting('wordcountplugin', 'wcp_location', array(
            'sanitize_callback' => array($this, 'sanitizeLocation'),
            'default' => '0'
        ));

        add_settings_field('wcp_headline', 'Headline Text', array($this, 'headlineHTML'), 'word-count-settings-page', 'wcp_first_section');
        register_setting('wordcountplugin', 'wcp_headline', array(
            'sanitize_callback' => 'sanitize_text_field',
            'default' => 'Post Statistics'
        ));

        add_settings_field('wcp_wordcount', 'Word Count', array($this, 'checkboxHTML'), 'word-count-settings-page', 'wcp_first_section', array('theName' => 'wcp_wordcount'));
        register_setting('wordcountplugin', 'wcp_wordcount', array(
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '1'
        ));

        add_settings_field('wcp_charcount', 'Character Count', array($this, 'checkboxHTML'), 'word-count-settings-page', 'wcp_first_section', array('theName' => 'wcp_charcount'));
        register_setting('wordcountplugin', 'wcp_charcount', array(
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '1'
        ));

        add_settings_field('wcp_readtime', 'Read Time', array($this, 'checkboxHTML'), 'word-count-settings-page', 'wcp_first_section', array('theName' => 'wcp_readtime'));
        register_setting('wordcountplugin', 'wcp_readtime', array(
            'sanitize_callback' => 'sanitize_text_field',
            'default' => '1'
        ));
    }

    function sanitizeLocation($input){
        if($input != '0' && $input != '1'){
            add_settings_error('wcp_location', 'wcp_location_error', 'Display location must be either Above Content or Below Content');
            return get_option('wcp_location');
        }
        return $input;
    }

    function locationHTML(){
        $location = get_option('wcp_location');
        ?>
        <select name="wcp_location" id="wcp_location">
            <option value="0" <?php selected($location, '0'); ?>>Below Content</option>
            <option value="1" <?php selected($location, '1'); ?>>Above Content</option>
        </select>
        <?php
    }

    function headlineHTML(){
        $headline = get_option('wcp_headline');
        ?>
        <input type="text" name="wcp_headline" value="<?php echo esc_attr($headline); ?>">
        <?php
    }

    function checkboxHTML($args){
        $option = get_option($args['theName']);
        ?>
        <input type="checkbox" name="<?php echo $args['theName']; ?>" value="1" <?php checked($option, '1'); ?>>
        <?php
    }
}

$wordCountAndTimePlugin =   new WordCountAndTimePlugin();