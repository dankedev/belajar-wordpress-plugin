<?php
/**
 * Plugin Name: 04. Membuat Halaman Pengaturan
 * Plugin URI: https://dankedev.com/plugins/plugin-saya
 * Description: Membuat Halaman Pengaturan - Belajar membuat plugin bersama hadie dankedev.com
 * Version: 1.0.10
 * Requires at least: 5.4
 * Requires PHP: 8.0
 * Author: Hadie Danker
 * Author URI: https://www.dankedev.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI: https://example.com/my-plugin/
 * Text Domain:dankedev
 * Domain Path:/languages
 */

 function create_new_admin_menu(){
  add_menu_page(
        '4. Membuat Halaman Pengaturan',
        'XMenu',
        'manage_options',
        'my-first-plugin',
        'menu_options_page_html',
        plugin_dir_url(__FILE__) . 'robot.svg',
        999999
    );

 }

 add_action('admin_menu','create_new_admin_menu',9999999);


 function menu_options_page_html(){
    ?>
<div class="wrap">
<h1><?php echo get_admin_page_title( );?></h1>

<form method="post" action="options.php">
        <?php
        settings_fields('belajar_plugin' );

        do_settings_sections('belajar_plugin' );

        submit_button('Simpan Kuy!!!')
        ?>

        </form>

</div>
    <?php
 }


 function create_my_plugin_setting(){
    register_setting(
        'belajar_plugin',
        'belajar_plugin_option'
    );

    //add_settings_section($id:string,$title:string,$callback:callable,$page:string )
    add_settings_section(
        'setting_section_1',
        'Pilih Bahasa Pemograman',
        'callback_plugin_setting_section',
        'belajar_plugin'
    );

    // add_settings_field($id:string,$title:string,$callback:callable,$page:string,$section:string,$args:array )
    
    add_settings_field(
        'setting_field_1',
        'Pilih Salah Satu',
        'callback_setting_fields',
        'belajar_plugin',
        'setting_section_1',
        array(
            'label_for'=>'bahasa_pemograman'
        )
    );

 }


 add_action('admin_init','create_my_plugin_setting');

 function callback_plugin_setting_section(){
    echo 'Ayo belajar pemograman';
 }


 function callback_setting_fields($args){
    $options = get_option('belajar_plugin_option');
    
    $id = $args['label_for'];
    $value = isset($options[$id ]) ? esc_attr($options[$id ] ) : null;

    // var_dump($args['options']);

 $languages = array('javascript','php','html','css','go','kotlin');
    ?>
    <select id="<?php echo $id;?>" name="belajar_plugin_option[<?php echo $id;?>]">
    <option value>Pilih Bahasa</option>
    <?php foreach ($languages as $lang):
    $selected = selected($value,$lang,false );
echo '<option value="'.$lang.'" '.$selected.'>'.$lang.'</option>';
    endforeach;
    ?>
</select>
    <?php


}