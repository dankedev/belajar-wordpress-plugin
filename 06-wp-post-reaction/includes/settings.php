<?php

function wp_post_reaction_register_setting()
{
    register_setting('wp_post_reaction_setting', 'wp_post_reaction_data');


    add_settings_section(
        'wp_post_reaction_setting_section',
        null,
        null,
        'wp_post_reaction_setting'
    );



    $settings = array(
        array(
            'id' => 'display_on',
            'label' => __('WordPress Display On', 'wp-post-reaction'),
            'arguments' => array(
                'type' => 'display'
            )
        ),
        array(
            'id' => 'position',
            'label' => __('Position', 'wp-post-reaction'),
            'arguments' => array(
                'type' => 'position'
            )
        ),
        array(
            'id' => 'alignment',
            'label' => __('Alignment', 'wp-post-reaction'),
            'arguments' => array(
                'type' => 'alignment'
            )
        ),
        array(
            'id' => 'reaction_buttons',
            'label' => __('Reaction Icons', 'wp-post-reaction'),
            'arguments' => array(
                'type' => 'reaction_buttons'
            )
        ),
        array(
            'id' => 'button_size',
            'label' => __('Icons size', 'wp-post-reaction'),
            'arguments' => array(
                'type' => 'button_size'
            )
        ),

    );

    foreach ($settings as $setting) {
        $setting_id = esc_attr($setting['id']);
        add_settings_field(
            'wp_post_reaction_' . $setting_id . 'setting_field',
            esc_attr($setting['label']),
            'wp_post_reaction_setting_callback',
            'wp_post_reaction_setting',
            'wp_post_reaction_setting_section',
            $setting['arguments']
        );
    }
}

add_action('admin_init', 'wp_post_reaction_register_setting');


function wp_post_reaction_setting_callback($args)
{
    $type = $args['type'];
    $data = get_option('wp_post_reaction_data');


    $alignments = array(
        'left' => __('Align Left'),
        'center' => __('Align Center'),
        'right' => __('Align Right'),
    );

    $button_sizes = array(
        'small' => __('Small'),
        'medium' => __('Medium'),
        'large' => __('Large'),
    );

    $positions = array(
        'top' => __('Top of post', 'wp-post-reaction'),
        'bottom' => __('Bottom of post', 'wp-post-reaction'),
    );

    switch ($type) {
        case 'display':
            echo  wp_post_reaction_setting_display_on($data);
            break;
        case 'position':
            echo wp_post_reaction_setting_radio($data, $type, $positions, 'bottom');
            break;
        case 'alignment':
            echo wp_post_reaction_setting_radio($data, $type, $alignments, 'center');
            break;
        case 'button_size':
            echo wp_post_reaction_setting_radio($data, $type, $button_sizes, 'small');
            break;
        case 'reaction_buttons':
            echo wp_post_reaction_setting_reaction_buttons($data);
            break;
        default:
            echo '';
            break;
    }
}


function wp_post_reaction_setting_display_on($options)
{

    $id = 'display_on';

    $values = isset($options[$id]) ? $options[$id] :  array();

    $displays = apply_filters('wp_post_reaction_post_type', array(
        'post' => __('Singe Post Page', 'wp-post-reaction'),
        'page' => __('Static Page', 'wp-post-reaction'),
    ));

    $output = '';
    foreach ($displays as $key => $label) {

        $checked = in_array($key, $values) ? 'checked' : '';

        $output .= wp_sprintf('<div>
        <input id="display_on_%2$s" value="%3$s" name="wp_post_reaction_data[%1$s][]" type="checkbox" %4$s/>
        <label for="display_on_%2$s">%2$s</label>
        </p>', $id, $label, $key, $checked);
    }

    return $output;
}


function wp_post_reaction_setting_radio($data, $for = null, $options = array(), $default = '')
{
    if (is_null($for) || empty($options)) {
        return '';
    }



    $value = isset($data[$for]) ? esc_attr($data[$for]) : $default;



    $output = '';
    foreach ($options as $key => $label) {
        $checked = checked($value, $key, false);
        $output .= wp_sprintf('<div>
        <input type="radio" id="select_item_%1$s" name="wp_post_reaction_data[%4$s]"  value="%1$s" %2$s/>
        <label for="select_item_%1$s">%3$s</label>
    </div>', $key, $checked, $label, $for);
    }

    return $output;
}


function wp_post_reaction_setting_reaction_buttons($options)
{
    $reactions = wp_post_reaction_get_all_reaction_button();
    $values = isset($options['reaction_buttons']) ? $options['reaction_buttons'] : array();

    $output = '<div class="select-reaction-button">';
    foreach ($reactions as $reaction) {
        $emoji = wp_sprintf('%s/assets/emoji/%s.svg', WP_POST_REACTION_PLUGIN_URL, $reaction);
        $checked = in_array($reaction, $values) ? 'checked' : '';

        $output .= wp_sprintf(
            '<div>
            <label>
                <span>%s</span>
               <div>
               <img src="%s" width="60" height="60"/>
               </div>
                <input type="checkbox" name="wp_post_reaction_data[reaction_buttons][]" value="%s" %s/>
            </label>
        </div>',
            ucfirst($reaction),
            esc_url($emoji),
            esc_attr($reaction),
            $checked
        );
    }
    $output .= '</div>';
    return $output;
}
