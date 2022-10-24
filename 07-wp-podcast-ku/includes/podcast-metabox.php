<?php

if (is_admin()) {
    add_action('load-post.php',     'wp_podcast_ku_init_metabox');
    add_action('load-post-new.php', 'wp_podcast_ku_init_metabox');
}

function wp_podcast_ku_init_metabox()
{
    add_action('add_meta_boxes_podcast', 'wp_podcast_ku_create_meta_box', 10, 1);
    add_action('save_post',      'wp_podcast_ku_save_meta_box', 10, 1);
}

function wp_podcast_ku_create_meta_box(\WP_Post $post)
{

    $title = 'Podcast Episode Details';
    add_meta_box(
        sanitize_title($title),
        $title,
        'wp_podcast_ku_create_meta_box_callback',
        'podcast',
        'normal', //'normal', 'side', 'advanced'
        'high' //'high', 'core', 'default', or 'low'. Default 'default'
    );
}

function wp_podcast_ku_create_meta_box_callback(\WP_Post $post)
{
    $key_type = 'wp_podcast_ku';
    $post_id = $post ? absint($post->ID) : null;

    $data = get_post_meta($post_id, $key_type, true);
    $data = $data ?? array();


    $types = array(
        array(
            'label' => 'Audio URL',
            'key' => 'audio'
        ),
        array(
            'label' => 'Embed',
            'key' => 'embed'
        ),
    );
    $podcast_url = isset($data['podcast_url']) ? esc_url($data['podcast_url']) : null;
    $podcast_type = isset($data['podcast_type']) ? esc_attr($data['podcast_type']) : null;



    wp_nonce_field('wp_podcast_ku_metabox_action', 'wp_podcast_ku_metabox_token');
    $output = '<div>';
    $output .= wp_sprintf('<strong>%s</strong>', 'Episode type');

    $output .= '<div class="d-input-radio-wrap">';
    foreach ($types as $type) {

        $output .= wp_sprintf(
            '<label for="%1$s"><input id="%1$s-post-type" type="radio"  name="%1$s[podcast_type]" value="%2$s" %3$s/><span>%4$s</span></label>',
            $key_type,
            esc_attr($type['key']),
            checked($podcast_type, $type['key'], false),
            $type['label']
        );
    }
    $output .= '</div>';

    $output .= '<div style="margin:15px 0;">';
    $output .= wp_sprintf('<strong>%s</strong>', 'File URL');
    $output .= wp_sprintf(
        '<div class="wp-podcast-file-upload-input"><input id="wp-podcast-file-upload-input" type="url" name="%1$s[podcast_url]" value="%2$s"/><button data-action="wp-podcast-upload-button" type="button" class="button button-secondary">Upload file</button></div>',
        $key_type,
        esc_url($podcast_url)
    );
    $output .= wp_sprintf('<div>%s</div>', esc_attr('Upload audio episode files as MP3 or M4A, video episodes as MP4, or paste the accessible file URL.'));
    $output .= '</div>';

    $output .= '<div id="wp-podcast-preview"></div>';

    $output .= '</div>';
    echo $output;
}


function wp_podcast_ku_preview()
{
?>
    <audio controls>
        <source src="horse.ogg" type="audio/ogg">
        <source src="horse.mp3" type="audio/mpeg">
        Your browser does not support the audio element.
    </audio>
<?php
}

function wp_podcast_ku_save_meta_box($post_id)
{
    // update_post_meta($post_id, 'wp_podcast_ku_test', $_POST);
    if (isset($_POST['wp_podcast_ku'])) {
        $is_valid_request = check_ajax_referer('wp_podcast_ku_metabox_action', 'wp_podcast_ku_metabox_token', false);

        // update_post_meta($post_id, 'wp_podcast_ku_test', $is_valid_request);

        if ($is_valid_request) {
            $data = $_POST['wp_podcast_ku'];
            $podcast_url = isset($data['podcast_url']) ? esc_url($data['podcast_url']) : null;
            $podcast_type = isset($data['podcast_type']) ? esc_attr($data['podcast_type']) : null;

            update_post_meta($post_id, 'wp_podcast_ku', array(
                'podcast_url' => $podcast_url,
                'podcast_type' => $podcast_type
            ));
        }
    }
}
