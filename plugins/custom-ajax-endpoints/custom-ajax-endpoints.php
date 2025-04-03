<?php
/**
 * Plugin Name: Custom AJAX Endpoints
 * Description: Provides a custom AJAX endpoint to fetch "Architecture" projects.
 * Version: 1.0
 * Author: Atif Raza
 */

if (!defined('ABSPATH')) {
    exit; 
}

function fetch_architecture_projects() {
    $is_logged_in = is_user_logged_in();

    $post_count = $is_logged_in ? 6 : 3;

    
    $paged = $is_logged_in ? (isset($_GET['page']) ? intval($_GET['page']) : 1) : 1;

    $args = [
        'post_type'      => 'projects',
        'posts_per_page' => $post_count,
        'paged'          => $paged,
        'tax_query'      => [
            [
                'taxonomy' => 'project_type',
                'field'    => 'slug',
                'terms'    => 'architecture',
            ]
        ],
    ];

    $query = new WP_Query($args);
    $projects = [];

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $projects[] = [
                'id'    => get_the_ID(),
                'title' => get_the_title(),
                'link'  => get_permalink(),
            ];
        }
        wp_reset_postdata();
    }

    
    $total_pages = $is_logged_in ? $query->max_num_pages : 1;

    // JSON response
    wp_send_json([
        'success'     => true,
        'data'        => $projects,
        'total_pages' => $total_pages,
        'current_page'=> $paged
    ]);
    wp_die();
}

// Register AJAX
add_action('wp_ajax_fetch_architecture_projects', 'fetch_architecture_projects');
add_action('wp_ajax_nopriv_fetch_architecture_projects', 'fetch_architecture_projects');


// coffee image using the API
function hs_give_me_coffee() {
    $response = wp_remote_get('https://coffee.alexflipnote.dev/random.json');

    if (is_wp_error($response)) {
        return 'Error fetching coffee image';
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    return $data['file'] ?? 'No coffee image found';
    
}
