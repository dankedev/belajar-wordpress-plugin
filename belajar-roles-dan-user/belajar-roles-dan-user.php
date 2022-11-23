<?php

/**
 * Plugin Name: Belajar Roles dan User
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


add_action('admin_menu', 'create_new_admin_menu_for_show');

function create_new_admin_menu_for_show()
{
    add_menu_page(
        'Belajar User dan Roles',
        'Belajar User',
        'manage_options', //capabilitie
        'belajar-user',
        'menu_for_show',
        'dashicons-editor-paste-word',
        71
    );
}



//https://developer.wordpress.org/reference/functions/wp_insert_user/
function menu_for_show()
{
    form_pendaftaran_user();




    // $user_id = 13;
    // $website = 'http://example.com';

    // $user_update = wp_update_user(array(
    //     'ID' => $user_id,
    //     'user_url' => esc_url($website)
    // ));

    // var_dump($user_update);

    //wp_delete_user($user_id);

    // roles
    // capability
    $user = new WP_User(get_current_user_id());


    $caps = [];
    foreach ($user->roles as $role) {
        $caps[] = get_role($role)->capabilities;
    }

    if (in_array('author', (array) $user->roles)) {
        echo 'ini untuk author';
    } else {
        echo  'hai bukan autho';
    }


    if (user_can($user, 'edit_posts')) {
        echo 'Bisa manage Order';
    } else {
        echo 'tidak bisa manage order';
    }




    if (isset($_POST['rahasia']) && sanitize_text_field($_POST['rahasia']) === 'rahasia') {
        $username = sanitize_text_field($_POST['username']);
        $email = sanitize_text_field($_POST['email']);
        $password = sanitize_text_field($_POST['password']);
        $website = sanitize_text_field($_POST['website']);
        $first_name = sanitize_text_field($_POST['first_name']);
        $last_name = sanitize_text_field($_POST['last_name']);

        $user_exist = username_exists($username);
        $email_exist = email_exists($email);

        if (!$user_exist && $email_exist === false) {
            // $user_id = wp_create_user(
            //     $username,
            //     $password,
            //     $email
            // );

            // if ($user_id) {
            //     $user = new WP_User($user_id);
            //     $user->set_role('editor');
            // }

            $user_data = [
                'user_login' => $username,
                'user_pass'  => $password,
                'user_email' => $email,
                'user_url'   => $website,
                'first_name'   => $first_name,
                'last_name' => $last_name,
                'role' => 'customer_service'

            ];
            $user_id = wp_insert_user($user_data);
            echo 'User berhasil dibuat ' . $user_id;
        } else {
            echo 'user sudah ada';
        }
    }
}

add_action('user_register', function ($user_id) {
    $user = get_user_by('ID', $user_id);
    $user->set_role('customer_service');
});


function form_pendaftaran_user()
{
    ?>

    <form method="post">
        <input type="hidden" name="rahasia" value="rahasia" />


        <fieldset>
            <label for="username">Username</label>
            <p> <input type="text" id="username" name="username" /></p>
        </fieldset>
        <fieldset>
            <label for="email">Email</label>
            <p> <input type="email" id="email" name="email" /></p>
        </fieldset>
        <fieldset>
            <label for="password">Password</label>
            <p> <input type="password" id="password" name="password" /></p>
        </fieldset>
        <fieldset>
            <label for="website">Website</label>
            <p> <input type="url" id="website" name="website" /></p>
        </fieldset>
        <fieldset>
            <label for="first_name">FirstName</label>
            <p> <input type="text" id="first_name" name="first_name" /></p>
        </fieldset>
        <fieldset>
            <label for="last_name">LastName</label>
            <p> <input type="text" id="last_name" name="last_name" /></p>
        </fieldset>
        <p><button class="button button-primary">Submit</button></p>
    </form>
<?php
}




function belajar_get_user_info()
{
    global $current_user;
    $user = wp_get_current_user();

    $user = get_user_by('slug', 'admin');

    $user = get_userdata(1);

    $user_info = get_user_meta($user->ID, 'show_admin_bar_front');
    var_dump($user_info);



    // echo 'halo ' . $user->display_name;
}









add_action('init', 'create_role_customer_service');

function create_role_customer_service()
{
    if (get_option('custom_roles_version') < 1) {
        add_role(
            'customer_service',
            'Customer Service',
            array(
                'read'         => true,
                'edit_posts'   => true,
                'upload_files' => true,
                'read_private_pages' => true,
                'read_private_posts' => true,
            )
        );
        update_option('custom_roles_version', 1);
    }
}

function create_order_capabilities()
{
    if (get_option('custom_capability_version') < 3) {
        global $wp_roles;

        if (!class_exists('WP_Roles')) {
            return;
        }

        $wp_roles->add_cap('manage_order', true);
        $wp_roles->add_cap('view_order', true);
        update_option('custom_capability_version', 3);
    }
}

add_action('init', 'create_order_capabilities', 11);


function add_administrator_to_manage_order_capability()
{
    if (get_option('custom_capability_to_roles_version') < 3) {
        // Gets the administrator role object.
        $role = get_role('administrator');

        // // Add a new capability.
        $role->add_cap('manage_order', true);
        $role->add_cap('view_order', true);

        $cs  = get_role('customer_service');

        $cs->add_cap('manage_order', true);
        $cs->add_cap('view_order', true);


        update_option('custom_capability_to_roles_version', 3);
    }
}

// Add simple_role capabilities, priority must be after the initial role definition.
add_action('init', 'add_administrator_to_manage_order_capability', 11);


function delete_customer_serivice_role()
{
    //remove_role('customer_service');
}
add_action('init', 'delete_customer_serivice_role');


function delete_view_order_cap()
{
    global $wp_roles;
    // $wp_roles->remove_cap('view_order');
    // $wp_roles->remove_cap('manage_order');
}
add_action('init', 'delete_view_order_cap');




function admin_view()
{
    global $wp_roles;
    $roles = $wp_roles->get_names();
    print_r($roles);

    $user = new WP_User(get_current_user_id());
    var_dump($user->roles);

    if (current_user_can('manage_order')) {
        //User ini bisa melihat
        echo 'hanya untuk user yang memiliki caustom capabality manage_order';
    }


    add_menu_page(
        '03. Membuat Menu My Menu Title',
        'XMenu',
        'manage_order', // hanya yang punya cap 'manage_order' yang bisa akses
        'my-menu-slug',
        'my_menu_callback',
        'dashicons-editor-paste-word',
        71
    );
}


// add_action('init', function () {
//     remove_role('customer_service');
//     remove_role('podcast_manager');
//     remove_role('podcast_editor');
// });
