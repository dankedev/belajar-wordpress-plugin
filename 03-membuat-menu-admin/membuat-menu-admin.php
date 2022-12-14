<?php

/**
 * Plugin Name: 03. Membuat Menu Admin
 * Plugin URI : https://dankedev.com/plugins/plugin-saya
 * Description: Belajar membuat plugin bersama hadie dankedev.com
 * Version: 1.0.10
 * Requires at least: 5.4
 * Requires PHP: 8.0
 * Author: Hadie Danker
 * Author URI: https://www.dankedev.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       dankedev
 * Domain Path:       /languages
 */

//  function create_my_plugin_menu(){

//     add_menu_page(
//         'Title Page Menu',
//         'My Menu',
//         'manage_options',
//         'my-menu-slug',
//         'tampilan_isi_menu_utama',
//         'dashicons-welcome-learn-more',
//         2
//     );

//  };

//  add_action('admin_menu','create_my_plugin_menu' );


//  add_menu_page(
//     $page_title:string,
//     $menu_title:string,
//     $capability:string,
//     $menu_slug:string,
//     $callback:callable,
//     $icon_url:string,
//     $position:integer|float|null )


function create_new_admin_menu()
{
    add_menu_page(
        '03. Membuat Menu My Menu Title',
        'XMenu',
        'manage_options',
        'my-menu-slug',
        'my_menu_callback',
        'dashicons-editor-paste-word',
        71
    );




    add_submenu_page(
        'my-menu-slug',
        'Menu Tambahan 1',
        'subMenu',
        'manage_options',
        'my-sub-menu',
        'my_sub_menu',
        1
    );

    // add_dashboard_page($page_title:string,$menu_title:string,$capability:string,$menu_slug:string,$callback:callable,$position:integer|null )
    add_media_page(
        'Menu Tambahan 1',
        'subMenu',
        'manage_options',
        'my-sub-menu2',
        'my_sub_menu',
    );
}

add_action('admin_menu', 'create_new_admin_menu', 9999999);


function my_menu_callback()
{
    ?>
    <div class="wrap">
        <h1>Ini Adalah Menu</h1>
    </div>
<?php

}


function my_sub_menu()
{
    ?>
    <div class="wrap">
        <h1>Ini Adalah Sub Menu</h1>
    </div>
<?php
}

//

function mendaftarkan_book_custom_post_type()
{
    $args = array(
        'label' => 'Buku',
        'labels' => array(
            'name'  => 'Books',
            'name_admin_bar' => 'Bukuku',
            'not_found' => 'Tidak ada buku'
        ),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'publicly_queryable' => true

    );

    register_post_type('dankedev-book', $args);
}
add_action('init', 'mendaftarkan_book_custom_post_type');


function mendaftarkan_genre_book_taxonomy()
{
    $args = array(
        'labels' => array(
            'name' => 'Book Genre',
            'singular_name' => 'Book Genre',
            'plural_name' => 'Book Genres'
        ),
        'hierarchical'      => true,
        'rewrite'           => array('slug' => 'xxxx'),

    );
    register_taxonomy('book-genre', array('post', 'dankedev-book'), $args);
}
//https://belajarwp.test/genre/religion
//https://belajarwp.test/xxxx/fiction

add_action('init', 'mendaftarkan_genre_book_taxonomy');



function function_simple_callback_shortcodenya()
{
    return 'disinixxx' . get_the_title() . get_the_ID();
}

add_shortcode('nama-shortcodenya', 'function_simple_callback_shortcodenya');




add_shortcode(
    'alert-danger',
    'function_alert_danger_callback_shortcodenya'
);

function function_alert_danger_callback_shortcodenya($atts = array(), $content = null)
{
    $title = get_the_title();

    return wp_sprintf('<div class="alert alert-danger" style="color:red;padding:10px;border:1px solid #999">%s - %s</div>', esc_html($content), $title);
}


add_shortcode(
    'notifikasi',
    'function_notifikasi_callback_shortcodenya'
);

function function_notifikasi_callback_shortcodenya($atts = array(), $content = null)
{
    $atts = array_change_key_case((array) $atts, CASE_LOWER);
    //[notifikasi Title=""]

    $attributes = shortcode_atts(
        array(
            'title' => 'Notifikasi',
            'type' => '', //danger,success,warning
        ),
        $atts
    );


    if (empty($content)) {
        return;
    }

    return wp_sprintf('<div class="notifikasi notifikasi-%s" style="color:green;padding:10px;border:1px solid #333">
    <h3>%s</h3><p>%s</p>
    </div>', sanitize_key($attributes['type']), esc_attr($attributes['title']), esc_html($content));
}


add_action('init', 'handle_remove_shortcode');



function handle_remove_shortcode()
{
    remove_shortcode(
        'audio'
    );

    remove_shortcode(
        'caption'
    );
}
