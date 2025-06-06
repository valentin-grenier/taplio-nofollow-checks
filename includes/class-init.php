<?php

/**
 * Class: Init
 * 
 * This class initializes the plugin by loading the necessary classes and registering the custom post type and fields.
 */

class Taplio_Init
{
    public function __construct()
    {
        add_action('admin_enqueue_scripts', [$this, 'load_plugin_admin_assets']);
    }

    # Load admin scripts
    public function load_plugin_admin_assets()
    {
        # CSS
        wp_enqueue_style('taplio-nofollow-checks-admin-styles', TAPLIO_URL . 'assets/css/admin.css', array(), '1.0.0', 'all');

        # JS
        wp_enqueue_script('taplio-nofollow-checks-admin-scripts', TAPLIO_URL . 'assets/js/admin/app.js', array(), '1.0.0', true);
    }
}
