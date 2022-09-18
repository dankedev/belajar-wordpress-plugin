<?php
/*
Plugin Name: 01. Belajar WordPress Hooks
Plugin URI: https://dankedev.com
Description: Belajar dan Mengenal WordPress Hooks
Version: 1.0.0.0
Author: Hadie Danker
Author URI: https://dankedev.com
License: dankedev
Text Domain: dankedev
Domain Path: /languages
*/

// Contoh Action Hooks 	do_action( 'save_post', $post_ID, $post, $update );

function send_email_when_create_new_post($post_ID){
        $title = get_the_title($post_ID );
    wp_mail('hadie@dankedev.com', $title, 'Halo, ini adalah kiriman baru'.time());
}

add_action('save_post', 'send_email_when_create_new_post');





// Contoh Filter Hooks

function modify_post_content($content){
    $gambar = '<img src="http://belajarwp.test/wp-content/uploads/2022/09/undraw_Performance_overview_re_mqrq.png"/>';
    $content = str_replace('[tampilkan gambar]',$gambar,$content);
    return $content;
}

add_filter('the_content', 'modify_post_content',9999999);
