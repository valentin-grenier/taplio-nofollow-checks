<?php

/**
 * Class: Admin
 * 
 * This class handles the admin functionality of the plugin, including registering custom post types and fields.
 */

class Taplio_Admin
{
    const ADMIN_PAGE_SLUG = 'taplio-nofollow-checks';

    public function __construct()
    {
        add_action('admin_menu', [$this, 'register_admin_page']);
        add_action('admin_post_taplio_update_domains', [$this, 'handle_form_submit']);
    }

    # Register the admin page
    public function register_admin_page()
    {
        add_menu_page(
            __('Taplio Nofollow Checks', 'wp-plugin-boilerplate'),
            __('Taplio Nofollow Checks', 'wp-plugin-boilerplate'),
            'manage_options',
            'taplio-nofollow-checks',
            [$this, 'render_admin_page'],
            'dashicons-admin-generic',
            20
        );
    }

    # Render the admin page
    public function render_admin_page()
    {
        $view_file = TAPLIO_DIR . 'admin/views/page-nofollow-domains.php';

        $domains_manager = new Taplio_Domain_Manager(JSON_FILE);

        $args = [
            'domains'     => $domains_manager->get_domains(),
            'is_writable' => $domains_manager->is_writable(),
            'updated'     => isset($_GET['updated']) && $_GET['updated'] === 'true',
        ];

        if (file_exists($view_file)) {
            include $view_file;
        } else {
            wp_die(__('Admin file not found.', 'taplio-nofollow-checks'));
        }
    }

    # Form submission handler
    public function handle_form_submit()
    {
        if (
            !current_user_can('manage_options') || !check_admin_referer('taplio_update_domains')
        ) {
            wp_die(__('Unauthorized request', 'taplio-nofollow-checks'));
        }

        # Validate and sanitize the input
        $raw = $_POST['domains'] ?? '';
        $lines = explode("\n", $raw);
        $domains = [];

        foreach ($lines as $line) {
            $domain = trim($line);
            $domain = str_replace(['http://', 'https://'], '', $domain);
            $domain = sanitize_text_field($domain);
            $domain = strtolower($domain);

            if (! empty($domain)) {
                $domains[] = $domain;
            }
        }

        $domains_manager = new Taplio_Domain_Manager(JSON_FILE);
        $domains_manager->save_domains($domains);

        wp_safe_redirect(admin_url('admin.php?page=' . self::ADMIN_PAGE_SLUG . '&updated=true'));
        exit;
    }
}
