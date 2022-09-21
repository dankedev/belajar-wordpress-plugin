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
        'Membuat Halaman Pengaturan',
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

    if(!current_user_can('manage_options' )){
        return false;
    }
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


function create_admin_setting(){

    register_setting('belajar_plugin','belajar_plugin_option');
    // register_setting($option_group:string,$option_name:string,$args:array )

    //add_settings_section($id:string,$title:string,$callback:callable,$page:string )
    add_settings_section(
        'belajar_plugin_setting_section',
        'Pengaturan Plugin',
        'plugin_setting_section_call_back',
        'belajar_plugin');

        // add_settings_field($id:string,$title:string,$callback:callable,$page:string,$section:string,$args:array )

        add_settings_field(
            'setting_field_id_1',
            'Pilih Bahasa Pemograman',
            'setting_field_call_back',
            'belajar_plugin',
            'belajar_plugin_setting_section',
            array(
                'label_for'=>'bahasa_pemograman',
                'class'=> 'setting_field_row',
                'options'=>array(
        'javascript','php','html','css','go','kotlin'
    )
            )
        );

                add_settings_field(
            'setting_field_id_2',
            'Pilih Bahasa Pemograman',
            'setting_field_call_back',
            'belajar_plugin',
            'belajar_plugin_setting_section',
            array(
                'label_for'=>'operating_system',
                'class'=> 'setting_field_row',
                'options'=>array(
        'linux','macOs','windows'
    )
            )
        );

       
}

add_action('admin_init','create_admin_setting');


function plugin_setting_section_call_back(){
 echo 'Silahkan pilih bahasa pemograman yang Anda Suka';
}

function setting_field_call_back($args){
    $options = get_option('belajar_plugin_option');
    
    $id = $args['label_for'];
    $className = $args['class'];
    $value = isset($options[$id ]) ? esc_attr($options[$id ] ) : null;

    // var_dump($args['options']);

 
    ?>
    <select id="<?php echo $id;?>" name="belajar_plugin_option[<?php echo $id;?>]">
    <option value>Pilih Bahasa</option>
    <?php foreach ($args['options'] as $lang):
    $selected = selected($value,$lang,false );
echo '<option value="'.$lang.'" '.$selected.'>'.$lang.'</option>';
    endforeach;
    ?>
</select>
    <?php


}