<?php

/**
 * Plugin Name: 06. WP Post Reaction
 * Plugin URI: https://dankedev.com/plugins/plugin-saya
 * Description: Increase user engagement with emoji reactions
 * Version: 1.0.0
 * Requires at least: 6.0
 * Requires PHP: 8.0
 * Author: Hadie Danker
 * Author URI: https://www.dankedev.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI: https://example.com/my-plugin/
 * Text Domain: wp-post-reaction
 * Domain Path:/languages
 */

define('WP_POST_REACTION_VERSION', '1.0.0');
define('WP_POST_REACTION_DB_VERSION', 1);

define('WP_POST_REACTION_PLUGIN_FILE', __FILE__);
define('WP_POST_REACTION_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('WP_POST_REACTION_PLUGIN_URL', plugin_dir_url(__FILE__));


include_once(WP_POST_REACTION_PLUGIN_PATH . '/install.php');
include_once(WP_POST_REACTION_PLUGIN_PATH . '/includes/settings.php');
include_once(WP_POST_REACTION_PLUGIN_PATH . '/includes/reaction-front.php');


function wp_post_reaction_admin_menu()
{
    add_menu_page(
        '06. WP Post Reaction',
        'Post Reaction',
        'manage_options',
        'wp-post-reaction',
        'wp_post_reaction_page_html',
        'dashicons-smiley',
        999999
    );
}
add_action('admin_menu', 'wp_post_reaction_admin_menu', 9999999);


function wp_post_reaction_page_html()
{
    global $current_user;

    $data = get_plugin_data(WP_POST_REACTION_PLUGIN_FILE);
    // $options = get_option('wp_post_reaction_data');

    // var_dump($options);
?>
    <div>
        <h1>
            <?php echo esc_html(get_admin_page_title()); ?>
        </h1>
        <p><?php echo $data['Description']; ?></p>
        <?php
        // translators: User display name.
        printf(esc_html__('Welcome aboard, %1$s! Use the settings panels below for complete control over where and how reaction buttons appear on your site. ', 'wp-post-reaction'), esc_html($current_user->display_name));
        ?>

        <form action="options.php" method="post">
            <?php
            settings_fields('wp_post_reaction_setting');
            do_settings_sections('wp_post_reaction_setting');
            submit_button(esc_html__('Update', 'wp-post-reaction'));
            ?>
        </form>
    </div>
<?php
}


function wp_post_reaction_admin_script($hook)
{

    if ($hook !== 'toplevel_page_wp-post-reaction') {
        return;
    }

    wp_enqueue_style('wp-post-reaction', WP_POST_REACTION_PLUGIN_URL . '/assets/css/admin.css', array(), time(), 'all');
}

add_action('admin_enqueue_scripts', 'wp_post_reaction_admin_script');


function wp_post_reaction_get_all_reaction_button()
{

    return apply_filters('wp_post_reaction_all_buttons', array('like', 'love', 'lol', 'wow', 'sad', 'angry'));
}

// if (function_exists('wp_post_reaction_on_activated')) {
// }

register_activation_hook(__FILE__, 'wp_post_reaction_on_activated');


function wp_post_reaction_uninstall()
{
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
}

// register_uninstall_hook(__FILE__, 'wp_post_reaction_uninstall');
//register_deactivation_hook(__FILE__, 'wp_post_reaction_uninstall');
