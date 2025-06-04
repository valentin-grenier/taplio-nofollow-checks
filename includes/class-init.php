<?php

/**
 * Class: Init
 * 
 * This class initializes the plugin by loading the necessary classes and registering the custom post type and fields.
 */

class Plugin_Init
{
    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'load_plugin_public_assets']);
        add_action('admin_enqueue_scripts', [$this, 'load_plugin_admin_assets']);
    }

    # Initialize plugin assets
    public function load_plugin_public_assets()
    {
        # CSS
        wp_enqueue_style('wp-plugin-boilerplate-public-styles', PLUGIN_URL . 'assets/css/public.css', array(), '1.0.0', 'all');

        # JS
        wp_enqueue_script('wp-plugin-boilerplate-public-scripts', PLUGIN_URL . 'assets/js/public/app.js', array(), '1.0.0', true);
    }

    # Load admin scripts
    public function load_plugin_admin_assets()
    {
        # CSS
        wp_enqueue_style('wp-plugin-boilerplate-admin-styles', PLUGIN_URL . 'assets/css/admin.css', array(), '1.0.0', 'all');

        # JS
        wp_enqueue_script('wp-plugin-boilerplate-admin-scripts', PLUGIN_URL . 'assets/js/admin/app.js', array(), '1.0.0', true);
    }
}
