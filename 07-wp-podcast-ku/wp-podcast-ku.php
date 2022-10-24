<?php

/**
 * Plugin Name: 07. WP Podcast KU
 * Plugin URI: https://dankedev.com/plugins/wp-portfolios-ku
 * Description: Increase user engagement with emoji reactions
 * Version: 1.0.0
 * Requires at least: 6.0
 * Requires PHP: 8.0
 * Author: Hadie Danker
 * Author URI: https://www.dankedev.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI: https://example.com/my-plugin/
 * Text Domain: wp-podcast
 * Domain Path:/languages
 */


include_once(plugin_dir_path(__FILE__) . '/includes/podcast-post-type.php');
include_once(plugin_dir_path(__FILE__) . '/includes/podcast-metabox.php');
include_once(plugin_dir_path(__FILE__) . '/includes/podcast-content.php');


function wp_podcast_ku_admin_script()
{
    global $post_type;
    if ('podcast' == $post_type) {
        wp_enqueue_style('wp-podcast-style', plugin_dir_url(__FILE__) . 'assets/css/admin.css', array('wp-mediaelement'), time(), 'all');

        wp_enqueue_script('wp-podcast-js', plugin_dir_url(__FILE__) . 'assets/js/admin.js', array('jquery', 'media-upload', 'thickbox', 'wp-mediaelement'), time(), true);
    }
}

// add_action('')
add_action('admin_print_scripts-post-new.php', 'wp_podcast_ku_admin_script', 11);
add_action('admin_print_scripts-post.php', 'wp_podcast_ku_admin_script', 11);


function wp_podcast_ku_front_end_script()
{
    // global $post_type;
    wp_enqueue_style('wp-podcast-front', plugin_dir_url(__FILE__) . 'assets/css/front.css', array('wp-mediaelement'), time(), 'all');

    wp_enqueue_script('wp-podcast-front', plugin_dir_url(__FILE__) . 'assets/js/front.js', array('jquery',  'wp-mediaelement'), time(), true);
}
add_action('wp_enqueue_scripts', 'wp_podcast_ku_front_end_script');

// // add_filter('single_template', 'wp_podcast_ku_template');

// function wp_podcast_ku_template($single)
// {
//     global $post;
//     if ($post->post_type == 'podcast') {
//         if (file_exists(plugin_dir_path(__FILE__) . '/includes/single-podcast.php')) {
//             return plugin_dir_path(__FILE__) . '/includes/single-podcast.php';
//         }
//     }

//     return $single;
// }
