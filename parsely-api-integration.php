<?php
/*
Plugin Name: Parsely API Integration
Description: This plugin is used to get data from Parsely API, for Recommendation Posts [personalized_sidebar_widget], Top Trending Posts "[parsely_trending_posts offset=0 limit=8]", and Trending Tags [parsely_trending_tags].
Version: 1.0
Author: Umar Khtab
*/


//Umar Khtab 
// die;
// Add CSS file for parsely widget
function enqueue_parsely_widget_style() {
    // Check if it's a single post page
    if (is_single()) {
        // Enqueue the stylesheet
        wp_enqueue_style('parsely-style', plugins_url('/css/parsely-style.css', __FILE__));
    }
    wp_enqueue_style('parsely-tag', plugins_url('/css/parsely-tag.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'enqueue_parsely_widget_style');

// Include the Parsely Recomendation Api Shortcode file
require_once(plugin_dir_path(__FILE__) . 'parsely-recomendation-posts.php');

// Include the Parsely Top Posts Api Shortcode file
require_once(plugin_dir_path(__FILE__) . 'parsely-top-trending-posts.php');

// Include the Parsely Trending Tags Api Shortcode file
require_once(plugin_dir_path(__FILE__) . 'parsely-trending-tags.php');
