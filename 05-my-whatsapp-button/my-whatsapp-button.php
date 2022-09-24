<?php

/**
 * Plugin Name: 05. WhatsApp button
 * Plugin URI: https://dankedev.com/plugins/plugin-saya
 * Description: Membuat Tombol floating WhatsApp
 * Version: 1.0.10
 * Requires at least: 5.4
 * Requires PHP: 8.0
 * Author: Hadie Danker
 * Author URI: https://www.dankedev.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI: https://example.com/my-plugin/
 * Text Domain:whatsapp-button
 * Domain Path:/languages
 */

/**
 * @task
    1. Membuat halaman admin
    2. Membuat setting page 
        - Nomor WhatsApp
        - Pesan Pembuka
        - URL Icon
    3. Menampilkan tombol WhatsApp

    @target pembelajaran
    1. Membuat logical untuk membuat field type
    2. Membuat dan menambahkan stype di front end
    3. Membuat logical di front end
    4. Mengulang pembelajaran sebelumnya dengan cara praktek
 */


/**
 * Mendaftarkan Halaman Admin
 *
 * @return void
 */
function whatsapp_button_register_menu()
{
    add_menu_page(
        __('5. Tombol WhatsApp', 'whatsapp-button'),
        'My WhatsApp',
        'manage_options',
        'plugin-tombol-whatsapp',
        'whatsapp_button_setting_page',
        plugin_dir_url(__FILE__) . 'robot.svg',
        999999
    );
}

add_action('admin_menu', 'whatsapp_button_register_menu', 9999999);

function whatsapp_button_setting_page()
{
//    var_dump(get_option('my_whatsapp_option'));
?>
    <div class="wrap">
        <h1><?php echo get_admin_page_title(); ?></h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('whatsapp_plugin_setting');

            do_settings_sections('whatsapp_plugin_setting');

            submit_button('Simpan Kuy!!!');
            ?>

        </form>


    </div>
<?php
}

function whatsapp_button_register_setting()
{
    register_setting('whatsapp_plugin_setting', 'my_whatsapp_option');

    add_settings_section(
        'whasapp_button_plugin_setting_section',
        'Pengaturan Tombol WhatsApp',
        'whatsapp_button_section_call_back',
        'whatsapp_plugin_setting'
    );

    $settings = array(
        array(
            'id' => 'whatsapp_number',
            'title' => 'Nomor WhatsApp',
            'arguments' => array(
                'label_for' => 'whatsapp_number',
                'type' =>'input',
            )
        ),
        array(
            'id' => 'whatsapp_icon',
            'title' => 'Pilih Tombol',
            'arguments' => array(
                'label_for' => 'button',
                'type' =>'select',
                'options' => array(
                    'button-1' => 'Tombol 1',
                    'button-2' => 'Tombol 2',
                    'button-3' => 'Tombol 3',
                    'button-4' => 'Tombol 4',
                )
            )
        ),
        array(
            'id' => 'whatsapp_position',
            'title' => 'Pilih Tombol',
            'arguments' => array(
                'label_for' => 'position',
                'type' =>'select',
                'options' => array(
                    'left' => 'Kiri',
                    'right' => 'Kanan'
                )
            )
        ),
        array(
            'id' => 'activated_on_post',
            'title' => 'Aktifkan di Halaman Post',
            'arguments' => array(
                'label_for' => 'post',
                'type' =>'checkbox'
            )
        ),
        array(
            'id' => 'activated_on_page',
            'title' => 'Aktifkan di Halaman Page',
            'arguments' => array(
                'label_for' => 'page',
                'type' =>'checkbox'
            )
        ),
        array(
            'id' => 'activated_on_home_page',
            'title' => 'Aktifkan di Halaman Home',
            'arguments' => array(
                'label_for' => 'home',
                'type' =>'checkbox'
            )
        ),
    );

    foreach($settings as $setting){
        add_settings_field(
            $setting['id'],
            $setting['title'],
            'whatsapp_button_setting_field_callback',
            'whatsapp_plugin_setting',
            'whasapp_button_plugin_setting_section',
            $setting['arguments']
        );
    }
 
}

add_action('admin_init', 'whatsapp_button_register_setting');


function whatsapp_button_section_call_back()
{
    echo sprintf('<p>%s</p>', 'lengkapi data-data dibawah ini');
}

function whatsapp_button_setting_field_callback($args)
{
    $options = get_option('my_whatsapp_option');

    $id = $args['label_for'];
    $value = isset($options[$id]) ? esc_attr($options[$id]) : null;
    // var_dump($value)
    $type = isset($args['type']) ? esc_attr($args['type']):'input';


    if($type ==='select'){
        whatsapp_button_field_select($options,$args);
    }elseif($type ==='checkbox'){
        whatsapp_button_field_checkbox($options,$args);
    }else{
        whatsapp_button_field_input($options,$args);
    }
}

function whatsapp_button_field_select($options,$args){
    $id = $args['label_for'];
    $value = isset($options[$id]) ? esc_attr($options[$id]) : null;
    ?>

    <select id="<?php echo $id ?>" name="my_whatsapp_option[<?php echo $id; ?>]">
        <?php
        foreach ($args['options']  as $key=>$label) {
            // selected($selected:mixed,$current:mixed,$echo:boolean )
            $selected = selected($value, $key, false);
            echo '<option value="' . $key . '" ' . $selected . '>' . $label . '</option>';
        }
        ?>
    </select>
<?php
}

function whatsapp_button_field_input($options,$args){
    $id = $args['label_for'];
    $value = isset($options[$id]) ? esc_attr($options[$id]) : '';
    
    echo sprintf('<input type="text" id="%1$s" name="my_whatsapp_option[%1$s]" value="%2$s"/>',$id,$value);
}

function whatsapp_button_field_checkbox($options,$args){
    $id = $args['label_for'];
    $value = isset($options[$id]) ? esc_attr($options[$id]) : false;
    
    $checked = checked($value,'on',false);
    // var_dump($value);
    echo sprintf('<input type="checkbox" id="%1$s" name="my_whatsapp_option[%1$s]" %2$s/>',$id,$checked);
}


function whatsapp_button_enqueue_style(){
    wp_enqueue_style('whatsapp-button',plugin_dir_url(__FILE__).'/assets/whatsapp-button.css',array(),'1.0','all');
}

add_action('wp_enqueue_scripts','whatsapp_button_enqueue_style');


function whatsapp_button_add_button_to_page(){
    $options = get_option('my_whatsapp_option');
    $position = isset($options['position']) ? esc_attr($options['position']) :'right';
    $whatsapp_number = isset($options['whatsapp_number']) ? esc_attr($options['whatsapp_number']) :'';
    $whatsapp_button = isset($options['button']) ? esc_attr($options['button']) :'button-1';
    $on_home = isset($options['home']) ? esc_attr($options['home']) : false;
    $on_post = isset($options['post']) ? esc_attr($options['post']) : false;
    $on_page = isset($options['page']) ? esc_attr($options['page']) : false;

    if(is_single() && !$on_post){
        return'';
    }

    if(is_page() && !$on_page){
        return'';
    }

    if(is_home() && !$on_home){
        return '';
    }

 
    $url = 'https://wa.me/'.$whatsapp_number;
    $button = plugin_dir_url(__FILE__).'/assets/button/'. $whatsapp_button.'.png';

    echo sprintf('<div class="whatsapp-button whatsapp-button-%s"><a href="%s" target="_blank"><img src="%s" height="60"/></a></div>',
    $position,
    $url,
    $button
);
}

add_action('wp_footer','whatsapp_button_add_button_to_page');