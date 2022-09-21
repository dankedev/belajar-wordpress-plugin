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


function create_new_admin_menu(){

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

add_action('admin_menu','create_new_admin_menu',9999999);


function my_menu_callback(){
   ?>
<div class="wrap">
<h1>Ini Adalah Menu</h1>
</div>
   <?php
}


function my_sub_menu(){
      ?>
<div class="wrap">
<h1>Ini Adalah Sub Menu</h1>
</div>
   <?php
}

