<?php

function wp_post_reaction_on_activated()
{
    if ('yes' === get_transient('wp_post_reaction_installing')) {
        return;
    }
    set_transient('wp_post_reaction_installing', 'yes', MINUTE_IN_SECONDS * 10);

    wp_post_reaction_default_options();
    wp_post_reaction_create_table();

    delete_transient('wp_post_reaction_installing');
}


function wp_post_reaction_default_options()
{
    $option_name = 'wp_post_reaction_data';
    if (!get_option('wp_post_reaction_data')) {
        $options = array(
            'display_on' => array(
                'post',
                'page'
            ),
            'position' => 'bottom',
            'alignment' => 'center',
            'reaction_buttons' => array(
                "like",
                "love",
                "lol",
                "wow",
                "sad",
                "angry"
            ),
            'button_size' => 'medium'
        );
        add_option($option_name, $options);
    }
}


function wp_post_reaction_create_table()
{
    global $wpdb;

    $wpdb->hide_errors();
    $version = get_option('wp_post_reaction_db_version');

    if ($version && version_compare($version, WP_POST_REACTION_DB_VERSION, '>=')) {
        update_option('install_check_db', WP_POST_REACTION_DB_VERSION);
        return;
    }

    $charset_collate = '';

    if ($wpdb->has_cap('collation')) {
        $charset_collate = $wpdb->get_charset_collate();
    }

    $table_name = $wpdb->prefix . 'post_reaction_reacted';

    $sql = "CREATE TABLE IF NOT EXISTS " . $table_name . " ( 
            id bigint unsigned NOT NULL AUTO_INCREMENT,
            post_id bigint NOT NULL,
            reactor_id varchar(30) NOT NULL,
            reacted_date DATETIME ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            reaction varchar(30) NOT NULL,
            user_id BIGINT(20) NOT NULL,
            PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    dbDelta($sql);
    update_option('wp_post_reaction_db_version', WP_POST_REACTION_DB_VERSION);
}
