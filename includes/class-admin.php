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
        add_action('admin_post_taplio_process_all', [$this, 'handle_process_all']);
    }

    # Register the admin page
    public function register_admin_page()
    {
        add_management_page(
            __('Nofollow Checks', 'taplio-nofollow-checks'),
            __('Nofollow Checks', 'taplio-nofollow-checks'),
            'manage_options',
            self::ADMIN_PAGE_SLUG,
            [$this, 'render_admin_page'],

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
            'bulk'        => isset($_GET['bulk']) && $_GET['bulk'] === '1',
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

    # Process all posts handler
    public function handle_process_all()
    {
        if (
            !current_user_can('manage_options') || !check_admin_referer('taplio_process_all')
        ) {
            wp_die(__('Unauthorized request', 'taplio-nofollow-checks'));
        }

        $domain_manager    = new Taplio_Domain_Manager(JSON_FILE);
        $content_processor = new Taplio_Content_Processor($domain_manager);

        if (empty($domains)) {
            wp_safe_redirect(admin_url('tools.php?page=' . self::ADMIN_PAGE_SLUG . '&updated=false&error=no_domains'));
        }

        $posts = get_posts([
            'post_type'   => 'post',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'fields'      => 'ids',
        ]);

        foreach ($posts as $post_id) {
            $post = get_post($post_id);
            $original = $post->post_content;
            $updated  = $content_processor->add_nofollow($original, $domain_manager->get_domains());

            if ($original !== $updated) {
                wp_update_post([
                    'ID'           => $post_id,
                    'post_content' => $updated,
                ]);
            }
        }

        wp_safe_redirect(admin_url('tools.php?page=' . self::ADMIN_PAGE_SLUG . '&bulk=1'));
        exit;
    }
}
