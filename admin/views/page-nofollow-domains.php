<?php
defined('ABSPATH') || exit;

$domains = $args['domains'] ?? [];
$is_writable = $args['is_writable'] ?? false;
$updated = $args['updated'] ?? false;
$bulk = $args['bulk'] ?? false;
$textarea_text = implode("\n", $domains);
?>

<div class="wrap">
    <h1><?php esc_html_e('Nofollow Domains', 'taplio-nofollow-checks'); ?></h1>

    <?php if (! $is_writable): ?>
        <div class="notice notice-error">
            <p><strong><?php esc_html_e('Warning:', 'taplio-nofollow-checks'); ?></strong> <?php esc_html_e('The JSON file is not writable.', 'taplio-nofollow-checks'); ?></p>
        </div>
    <?php endif; ?>

    <?php if (! empty($bulk)): ?>
        <div class="notice notice-success is-dismissible">
            <p><?php esc_html_e('All posts have been processed.', 'taplio-nofollow-checks'); ?></p>
        </div>
    <?php endif; ?>

    <?php if ($updated): ?>
        <div class="notice notice-success is-dismissible">
            <p><?php esc_html_e('Domains updated successfully.', 'taplio-nofollow-checks'); ?></p>
        </div>
    <?php endif; ?>

    <p><?php _e('Add domains to the list below. All links to these domains will have the <code>nofollow</code> attribute added automatically.', 'taplio-nofollow-checks'); ?></p>

    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <?php wp_nonce_field('taplio_update_domains'); ?>
        <input type="hidden" name="action" value="taplio_update_domains" />

        <textarea name="domains" rows="20" style="width: 100%; font-family: monospace;" placeholder="example.com"><?php echo esc_textarea($textarea_text); ?></textarea>

        <p>
            <button type="submit" class="button button-primary">
                <?php esc_html_e('Save list', 'taplio-nofollow-checks'); ?>
            </button>
        </p>
    </form>

    <br />

    <h2><?php _e('Add <code>nofollow</code> attribute to all existing posts.', 'taplio-nofollow-checks'); ?></h2>
    <p><?php _e('Processing all posts can be harmful for your web server. Handle this feature with care.', ''); ?></p>
    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <?php wp_nonce_field('taplio_process_all'); ?>
        <input type="hidden" name="action" value="taplio_process_all" />
        <p>
            <button type="submit" class="button button-secondary">
                <?php esc_html_e('Process all posts', 'taplio-nofollow-checks'); ?>
            </button>
        </p>
    </form>
</div>