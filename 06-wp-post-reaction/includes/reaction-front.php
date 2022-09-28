<?php

function wp_post_reaction_insert_to_post($content)
{
    global $post;
    $options = get_option('wp_post_reaction_data');

    $position =  isset($options['position']) ? esc_attr($options['position']) : 'bottom';
    if (is_singular()) {
        if ('top' === $position) {
            return wp_post_reaction_html() . $content;
        } else {
            return $content . wp_post_reaction_html();
        }
    }
    return $content;
}

add_filter('the_content', 'wp_post_reaction_insert_to_post', 10000);


function wp_post_reaction_html()
{
    $reactions = wp_post_reaction_get_all_reaction_button();

    $options = get_option('wp_post_reaction_data');
    $values = isset($options['reaction_buttons']) ? $options['reaction_buttons'] : $reactions;



    $size  =  wp_post_reaction_get_size($options);
    $post_id = get_the_ID();
    $alignment = isset($options['alignment']) ? esc_attr($options['alignment']) : 'center';
    $selected  = wp_post_reaction_get_current_user_reaction($post_id);
    $html  = apply_filters(
        'wp_post_reaction_html_container',
        sprintf(
            '<div class="wp-post-reaction-container wp-post-reaction-%s" data-nonce="%s" data-post-id="%d" data-reacted="%s">',
            $alignment,
            wp_create_nonce('wp_post_reaction_none'),
            $post_id,
            $selected
        )
    );

    $total = 0;


    foreach ($values as $reaction) {
        $emoji = wp_sprintf('%s/assets/emoji/%s.svg', WP_POST_REACTION_PLUGIN_URL, $reaction);
        $is_selected =   $selected === $reaction ? 'has-clicked' : '';
        $html .= wp_sprintf('<div class="wp-post-reaction-button %s" data-reaction="%s">', $is_selected, $reaction,);
        $html .= wp_sprintf('<img src="%s" alt="%s" width="%s" height=""/>', $emoji, $reaction, $size);
        $html .= wp_sprintf('<span class="wp-post-reaction-count">%d</span>', $total);
        $html .= wp_sprintf('<span class="wp-post-reaction-label">%s</span>', $reaction);
        $html .= '</div>';
    }

    $html  .= apply_filters('wp_post_reaction_html_end_wrap', '</div>');

    return $html;
}

function wp_post_reaction_get_size($options)
{
    $size  =  isset($options['button_size']) ? esc_attr($options['button_size']) : 'medium';

    switch ($size) {
        case 'small':
            $size_number = 30;
            break;
        case 'large':
            $size_number = 60;
            break;
        default:
            $size_number = 40;
            break;
    }

    return apply_filters('wp_post_reaction_size', $size_number, $size);
}



function wp_post_reaction_front_end_style()
{
    if (!function_exists('get_plugin_data')) {
        require_once(ABSPATH . 'wp-admin/includes/plugin.php');
    }


    $data = get_plugin_data(WP_POST_REACTION_PLUGIN_FILE, false, false);

    $plugin_version = isset($data['Version']) ? esc_attr($data['Version']) : false;
    $version = defined('WP_DEBUG') ? time() : $plugin_version;

    $options = get_option('wp_post_reaction_data');
    $display_on = isset($options['display_on']) ? $options['display_on'] : array('post', 'page');
    if (is_singular($display_on)) {
        wp_enqueue_style(
            'wp-post-reaction-front',
            WP_POST_REACTION_PLUGIN_URL . '/assets/css/front.css',
            array(),
            $version,
            'all'
        );

        wp_enqueue_script(
            'wp-post-reaction-js',
            WP_POST_REACTION_PLUGIN_URL . '/assets/js/front.js',
            array('jquery'),
            $version,
            true
        );
        wp_localize_script(
            'wp-post-reaction-js',
            'wp_post_reaction',
            array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'version' => $version,
                'post_id' => null,
                'selected' => 'like'
            )
        );
    }
}

add_action('wp_enqueue_scripts', 'wp_post_reaction_front_end_style');


function wp_post_reaction_make_reaction()
{
    global $wpdb;

    //$token = isset($_POST['_token']) ? sanitize_text_field($_POST['_token']) : null;
    $is_valid_request = check_ajax_referer('wp_post_reaction_none', '_token', false);

    if (!$is_valid_request) {
        wp_send_json_error(array('message' => 'not allowed'));
        wp_die();
    }

    $post_id    = isset($_POST['post_id']) ? absint($_POST['post_id']) : null;
    $reaction   = isset($_POST['reaction']) ? sanitize_text_field($_POST['reaction']) : null;
    $user_id    = get_current_user_id();
    $table_name = $wpdb->prefix . 'post_reaction_reacted';

    $reactor_id           =  isset($_COOKIE['reactor_id']) ? sanitize_text_field($_COOKIE['reactor_id']) : null;
    $response = array();
    if ($reactor_id) {
        // $reactor_id           = $_COOKIE['reactor_id'];
        $is_already_reacted = $wpdb->get_var(
            $wpdb->prepare("SELECT count(*) FROM $table_name WHERE post_id = %d and reactor_id = %s", $post_id, $reactor_id)
        );
    }



    $data_to_input = array(
        'post_id' => $post_id,
        'user_id' => absint($user_id),
        'reactor_id' => esc_attr($reactor_id),
        'reaction' => esc_attr($reaction),
    );


    if (!is_null($reactor_id) && $is_already_reacted != 0) {

        $response['action'] = 'update';
        $response['status'] = $wpdb->update($table_name, $data_to_input, ['reactor_id' => $reactor_id, 'post_id' => $post_id]) > 0
            ? 'success'
            : 'error';
    } else {
        if (is_null($reactor_id)) {
            $data_to_input['reactor_id'] = uniqid();
            setcookie('reactor_id', $data_to_input['reactor_id'], time() + (86400 * 365), "/"); // 86400 = 1 day
        }

        $response['action'] = 'insert';
        $response['status'] = $wpdb->insert($table_name, $data_to_input) > 0
            ? 'success'
            : 'error';
    }

    if ($response['status'] == 'error') {
        $response['error_message'] = $wpdb->last_error;
        wp_send_json_error($response);
    }
    $response['reaction'] = esc_attr($reaction);
    wp_send_json_success($response);
    wp_die();
}

add_action('wp_ajax_nopriv_wp_post_reaction_make_reaction', 'wp_post_reaction_make_reaction');
add_action('wp_ajax_wp_post_reaction_make_reaction', 'wp_post_reaction_make_reaction');


function wp_post_reaction_get_current_user_reaction($post_id = null)
{
    global $wpdb;
    if (is_null($post_id)) {
        return '';
    }

    $table_name = $wpdb->prefix . 'post_reaction_reacted';
    $reactor_id           =  isset($_COOKIE['reactor_id']) ? sanitize_text_field($_COOKIE['reactor_id']) : null;

    if (is_null($reactor_id)) {
        return '';
    }

    return $wpdb->get_var(
        $wpdb->prepare("SELECT reaction FROM $table_name WHERE post_id = %d and reactor_id = %s", $post_id, $reactor_id)
    );
}
