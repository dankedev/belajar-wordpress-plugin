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

/**
 * Mendaftarkan Halaman Admin
 *
 * @return void
 */
function create_new_admin_menu()
{
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

add_action('admin_menu', 'create_new_admin_menu', 9999999);

/**
 * Menampilkan halaman admin
 *
 * @return void
 */
function menu_options_page_html()
{
    //  {prefix}_options

?>
    <div class="wrap">
        <h1><?php echo get_admin_page_title(); ?></h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('belajar_plugin');

            do_settings_sections('belajar_plugin');

            submit_button('Simpan Kuy!!!');
            ?>

        </form>

        <button id="tombol-alert" class="btn btn-danger"> Add Alert</button>

    </div>
<?php
}

add_action('admin_init', 'create_admin_setting');


/**
 * Membuat Admin Setting
 *
 * @return void
 */
function create_admin_setting()
{

    // register_setting($option_group:string,$option_name:string,$args:array );
    register_setting('belajar_plugin', 'belajar_plugin_option_2');

    // add_settings_section($id:string,$title:string,$callback:callable,$page:string )

    add_settings_section(
        'belajar_plugin_setting_section',
        'Pengaturan Plugin',
        'plugin_setting_section_call_back',
        'belajar_plugin'
    );

    // add_settings_field($id:string,$title:string,$callback:callable,$page:string,$section:string,$args:array )
    add_settings_field(
        'setting_field_id_1',
        'Pilih Bahasa Pemograman',
        'setting_field_call_back',
        'belajar_plugin',
        'belajar_plugin_setting_section',
        array(
            'label_for' => 'bahasa_pemogragaman',
            'options' => array(
                'javascript', 'php', 'html', 'css', 'go', 'kotlin'
            )
        )
    );
}

function plugin_setting_section_call_back()
{
    echo sprintf('<p>%s</p>','Penjelasan apa yang harus dilakukan dengan pengaturan disini.');
}


function setting_field_call_back($args)
{
    $options = get_option('belajar_plugin_option_2');

    $id = $args['label_for'];
    $value = isset($options[$id]) ? esc_attr($options[$id]) : null;
    // var_dump($value)
?>

    <select id="<?php echo $id ?>" name="belajar_plugin_option_2[<?php echo $id; ?>]">
        <?php
        foreach ($args['options']  as $key) {
            // selected($selected:mixed,$current:mixed,$echo:boolean )
            $selected = selected($value, $key, false);
            echo '<option value="' . $key . '" ' . $selected . '>' . $key . '</option>';
        }
        ?>
    </select>
<?php
}


function register_external_style(){
    wp_register_style('font-roboto','//fonts.googleapis.com/css?family=Roboto',array(),'1.0','all');
    wp_register_style('bootstrap-roboto','//cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.1/css/bootstrap.min.css');
}

add_action('admin_enqueue_scripts','register_external_style');


/**
 * Add internal Plugin Style
 *
 * @return void
 */
function add_my_plugin_style(){
    wp_enqueue_style('my-plugin-style',plugin_dir_url(__FILE__).'/assets/css/plugin-style.css',array('bootstrap-roboto','font-roboto'));
}
add_action('admin_enqueue_scripts','add_my_plugin_style');

function add_my_plugin_script_js(){
    wp_enqueue_script('my-plugin-script',plugin_dir_url(__FILE__).'/assets/js/plugin-app.js',array('jquery'),'1.0',true);
}
add_action('admin_enqueue_scripts','add_my_plugin_script_js')

?>