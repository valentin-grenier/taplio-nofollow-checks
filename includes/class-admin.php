<?php

/**
 * Class: Admin
 * 
 * This class handles the admin functionality of the plugin, including registering custom post types and fields.
 */

class Taplio_Admin
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'register_admin_page']);
    }

    # Register the admin page
    public function register_admin_page()
    {
        add_menu_page(
            __('Taplio Nofollow Checks', 'wp-plugin-boilerplate'),
            __('Taplio Nofollow Checks', 'wp-plugin-boilerplate'),
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
        echo '<h1>' . esc_html__('Taplio Nofollow Checks Admin Page', 'taplio-nofollow-checks') . '</h1>';
        echo '<p>' . esc_html__('Welcome to the Taplio Nofollow Checks admin page!', 'taplio-nofollow-checks') . '</p>';
        echo '</div>';
    }
}
