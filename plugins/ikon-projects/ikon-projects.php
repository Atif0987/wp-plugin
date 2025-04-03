<?php
/**
 * Plugin Name: IKON Projects Manager
 * Description: A custom plugin to manage Projects post type and related functionalities.
 * Version: 1.0
 * Author: Atif Raza
 */

if (!defined('ABSPATH')) {
    exit; 
}
function ikon_redirect_based_on_ip() {
    if (!is_admin()) { 
        // $user_ip = '77.29.123.45'; 
        $user_ip = $_SERVER['REMOTE_ADDR'];
        if (strpos($user_ip, '77.29') === 0) {
            wp_redirect('https://www.google.com'); 
            exit;
        }
    }
}
add_action('init', 'ikon_redirect_based_on_ip');
function ikon_register_projects_post_type() {
    $labels = [
        'name'               => 'Projects',
        'singular_name'      => 'Project',
        'menu_name'          => 'Projects',
        'name_admin_bar'     => 'Project',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Project',
        'new_item'           => 'New Project',
        'edit_item'          => 'Edit Project',
        'view_item'          => 'View Project',
        'all_items'          => 'All Projects',
        'search_items'       => 'Search Projects',
        'not_found'          => 'No Projects found.',
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'show_in_rest'       => true, // Enables Gutenberg editor
        'supports'           => ['title', 'editor', 'thumbnail'],
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-portfolio',
        'rewrite'            => ['slug' => 'projects'],
    ];

    register_post_type('projects', $args);
}
add_action('init', 'ikon_register_projects_post_type');
function ikon_register_project_type_taxonomy() {
    $labels = [
        'name'          => 'Project Types',
        'singular_name' => 'Project Type',
        'search_items'  => 'Search Project Types',
        'all_items'     => 'All Project Types',
        'edit_item'     => 'Edit Project Type',
        'update_item'   => 'Update Project Type',
        'add_new_item'  => 'Add New Project Type',
        'new_item_name' => 'New Project Type Name',
        'menu_name'     => 'Project Types',
    ];

    $args = [
        'labels'            => $labels,
        'public'            => true,
        'hierarchical'      => true, // True for categories, false for tags
        'show_in_rest'      => true,
        'rewrite'           => ['slug' => 'project-type'],
    ];

    register_taxonomy('project_type', ['projects'], $args);
}
add_action('init', 'ikon_register_project_type_taxonomy');
