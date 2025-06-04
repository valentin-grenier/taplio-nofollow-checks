<?php

/**
 * Class: Admin
 * 
 * This class handles the admin functionality of the plugin, including registering custom post types and fields.
 */

class Plugin_Admin
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'register_admin_page']);
    }

    # Register the admin page
    public function register_admin_page()
    {
        add_menu_page(
            __('WP Plugin Boilerplate', 'wp-plugin-boilerplate'),
            __('WP Plugin Boilerplate', 'wp-plugin-boilerplate'),
            'manage_options',
            'plugin-admin',
            [$this, 'render_admin_page'],
            'dashicons-admin-generic',
            20
        );
    }

    # Render the admin page
    public function render_admin_page()
    {
        echo '<div class="wrap">';
        echo '<h1>' . esc_html__('WP Plugin Boilerplate Admin Page', 'wp-plugin-boilerplate') . '</h1>';
        echo '<p>' . esc_html__('Welcome to the WP Plugin Boilerplate admin page!', 'wp-plugin-boilerplate') . '</p>';
        // Add your admin page content here
        echo '</div>';
    }
}
