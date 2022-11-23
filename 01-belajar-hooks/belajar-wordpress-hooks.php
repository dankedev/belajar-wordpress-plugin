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

function send_email_when_create_new_post($post_ID)
{
    $title = get_the_title($post_ID);
    wp_mail('hadie@dankedev.com', $title, 'Halo, ini adalah kiriman baru' . time());
}

add_action('save_post', 'send_email_when_create_new_post');





// Contoh Filter Hooks

function modify_post_content($content)
{
    $gambar = '<img src="http://belajarwp.test/wp-content/uploads/2022/09/undraw_Performance_overview_re_mqrq.png"/>';
    $content = str_replace('[tampilkan gambar]', $gambar, $content);
    return $content;
}

add_filter('the_content', 'modify_post_content', 9999999);


// Menyimpan

add_option('key_option', 'contoh value');

//Update
update_option('key_option', 'contoh value baru');

// Read
$data = get_option('key_option', 'default data');

// Delete
delete_option('key_option');



//VALUE

$data  = array('title' => 'Misalnya sebuah judul', 'type' => 'artikel');
update_option('key_option', $data);

//a:7:{s:6:"drafts";s:4:"true";s:5:"spams";s:4:"true";s:9:"transient";s:5:"false";s:10:"unapproved";s:5:"false";s:9:"revisions";s:4:"true";s:8:"optimize";s:5:"false";s:5:"trash";s:4:"true";}

$data = get_option('key_option', null);


if (is_array($data)) {
    echo $data['title'];
} else {
    echo $data;
}
