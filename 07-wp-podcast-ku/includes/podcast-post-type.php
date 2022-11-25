<?php

add_action('init', 'wp_podcast_ku_post_type');

function wp_podcast_ku_post_type()
{
    $args = [
        'label'  => esc_html__('podcasts', 'text-domain'),
        'labels' => [
            'menu_name'          => esc_html__('Podcasting', 'wp-podcasts-ku'),
            'name_admin_bar'     => esc_html__('Podcast', 'wp-podcasts-ku'),
            'add_new'            => esc_html__('Add New Episode', 'wp-podcasts-ku'),
            'add_new_item'       => esc_html__('Add new Episode', 'wp-podcasts-ku'),
            'new_item'           => esc_html__('New Podcast', 'wp-podcasts-ku'),
            'edit_item'          => esc_html__('Edit Podcast', 'wp-podcasts-ku'),
            'view_item'          => esc_html__('View Podcast', 'wp-podcasts-ku'),
            'update_item'        => esc_html__('View Podcast', 'wp-podcasts-ku'),
            'all_items'          => esc_html__('All Episodes', 'wp-podcasts-ku'),
            'search_items'       => esc_html__('Search episodes', 'wp-podcasts-ku'),
            'parent_item_colon'  => esc_html__('Parent Podcast', 'wp-podcasts-ku'),
            'not_found'          => esc_html__('No Episodes found', 'wp-podcasts-ku'),
            'not_found_in_trash' => esc_html__('No Episodes found in Trash', 'wp-podcasts-ku'),
            'name'               => esc_html__('Podcast', 'wp-podcasts-ku'),
            'singular_name'      => esc_html__('Podcast', 'wp-podcasts-ku'),
        ],
        'public'              => true,
        'exclude_from_search' => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'show_in_rest'        => true,
        'capability_type'     => 'post', //page/post
        'hierarchical'        => false,
        'has_archive'         => true,
        'query_var'           => true,
        'can_export'          => true,
        'rewrite_no_front'    => true,
        'show_in_menu'        => true,
        'menu_position'       => 20,
        'rest_base'           => 'wp-podcasts',
        'menu_icon'           => 'dashicons-megaphone',
        'supports' => [
            'title',
            'editor',
            'excerpt',
            'comments',
            'custom-fields',
            'author',
            'thumbnail',
        ],
        'taxonomies' => [
            'podcast-categories'
        ],
        'rewrite' => true
    ];

    register_post_type('podcast', $args);
}



add_filter('use_block_editor_for_post_type', 'wp_podcasts_ku_disable_gutenberg', 10, 2);

function wp_podcasts_ku_disable_gutenberg($current_status, $post_type)
{
    if ($post_type === 'podcast') {
        return false;
    }

    return $current_status;
}

add_action('init', 'wp_podcast_ku_taxonomy');

function wp_podcast_ku_taxonomy()
{
    $args = [
        'label'  => esc_html__('Podcast Category', 'wp-podcasts-ku'),
        'labels' => [
            'menu_name'                  => esc_html__('Podcast Category', 'wp-podcasts-ku'),
            'all_items'                  => esc_html__('All Podcast Category', 'wp-podcasts-ku'),
            'edit_item'                  => esc_html__('Edit Podcast Categories', 'wp-podcasts-ku'),
            'view_item'                  => esc_html__('View Podcast Categories', 'wp-podcasts-ku'),
            'update_item'                => esc_html__('Update Podcast Categories', 'wp-podcasts-ku'),
            'add_new_item'               => esc_html__('Add new Podcast Categories', 'wp-podcasts-ku'),
            'new_item'                   => esc_html__('New Podcast Categories', 'wp-podcasts-ku'),
            'parent_item'                => esc_html__('Parent Podcast Categories', 'wp-podcasts-ku'),
            'parent_item_colon'          => esc_html__('Parent Podcast Categories', 'wp-podcasts-ku'),
            'search_items'               => esc_html__('Search Podcast Category', 'wp-podcasts-ku'),
            'popular_items'              => esc_html__('Popular Podcast Category', 'wp-podcasts-ku'),
            'separate_items_with_commas' => esc_html__('Separate Podcast Category with commas', 'wp-podcasts-ku'),
            'add_or_remove_items'        => esc_html__('Add or remove Podcast Category', 'wp-podcasts-ku'),
            'choose_from_most_used'      => esc_html__('Choose most used Podcast Category', 'wp-podcasts-ku'),
            'not_found'                  => esc_html__('No Podcast Category found', 'wp-podcasts-ku'),
            'name'                       => esc_html__('Podcast Category', 'wp-podcasts-ku'),
            'singular_name'              => esc_html__('Podcast Categories', 'wp-podcasts-ku'),
        ],
        'public'               => true,
        'show_ui'              => true,
        'show_in_menu'         => true,
        'show_in_nav_menus'    => true,
        'show_tagcloud'        => true,
        'show_in_quick_edit'   => true,
        'show_admin_column'    => true,
        'show_in_rest'         => true,
        'hierarchical'         => true,
        'query_var'            => true,
        'sort'                 => true,
        'rewrite_no_front'     => true,
        'rewrite_hierarchical' => true,
        'rewrite' => true
    ];

    register_taxonomy('podcast-categories', 'podcast', $args);
}

//showcase/web
//showcase/mobile-apps

// add_filter('manage_podcast_posts_columns', 'wp_podcast_ku_add_new_column', 10, 1);

// function wp_podcast_ku_add_new_column($columns)
// {
//     // hapus kolom tanggal podcast
//     unset($columns['date']);

//     //sementara hapus comments, akan kita pindah susunanya
//     unset($columns['comments']);

//     // hapus podcast category
//     unset($columns['taxonomy-podcast-categories']);
//     $columns['title'] = __('Episode title', 'wp-podcasts-ku');

//     // ganti table label untuk "title"
//     $columns['title'] = __('Episode title', 'wp-podcasts-ku');


//     // daftarkan column baru untuk shortcode dan memasang kembali podcast category
//     $columns['podcast_shortcode'] = __('Shortcode', 'your_text_domainwp-podcasts-ku');
//     $columns['taxonomy-podcast-categories'] = __('Categories', 'your_text_domainwp-podcasts-ku');


//     //pasang kembali column comments
//     $columns['comments'] = '<span class="vers comment-grey-bubble" title="Comments"><span class="screen-reader-text">Comments</span></span>';


//     //jangan lupa return semua columns
//     return $columns;
// }


// add_action('manage_podcast_posts_custom_column', 'wp_podcast_ku_custom_column', 10, 2);


// function wp_podcast_ku_custom_column($column, $post_id)
// {
//     $shortcode = wp_sprintf('[wp-podcast id="%d"]', $post_id);

//     if ($column === 'podcast_shortcode') {
//         echo wp_sprintf('<input  readonly type="text" value="%s"/>', esc_html($shortcode));
//     }


//     return $column;
// }


add_filter('manage_podcast_posts_columns', 'wp_podcast_ku_add_new_column', 10, 1);


function wp_podcast_ku_add_new_column($columns)
{
    unset($columns['date']);
    unset($columns['comments']);
    unset($columns['taxonomy-podcast-categories']);

    $columns['title'] = __('Episode title', 'wp-podcasts-ku');

    $columns['podcast_shortcode'] = __('Shortcode', 'your_text_domainwp-podcasts-ku');

    $columns['taxonomy-podcast-categories'] = __('Categories', 'your_text_domainwp-podcasts-ku');


    return $columns;
}


add_action('manage_podcast_posts_custom_column', 'wp_podcast_ku_custom_column', 10, 2);

function wp_podcast_ku_custom_column($column, $post_id)
{
    $shortcode = wp_sprintf('[wp-podcast id="%d"]', $post_id);
    if ($column === 'podcast_shortcode') {
        echo wp_sprintf('<input  readonly type="text" value="%s"/>', esc_html($shortcode));
    }

    return $column;
}
