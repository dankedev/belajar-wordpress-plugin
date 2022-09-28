<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}


global $wpdb;
$option_name = 'wp_post_reaction_data';

delete_option($option_name);
delete_option('wp_post_reaction_db_version');

// for site options in Multisite
delete_site_option($option_name);
delete_site_option('wp_post_reaction_db_version');


require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

$table_name = $wpdb->prefix . 'post_reaction_reacted';
$wpdb->query("DROP TABLE IF EXISTS {$table_name}");
