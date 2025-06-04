<?php
defined('ABSPATH') || exit;

$domains       = $args['domains'] ?? [];
$is_writable   = $args['is_writable'] ?? false;
$updated       = $args['updated'] ?? false;
$textarea_text = implode("\n", $domains);
?>

<div class="wrap">
    <h1><?php esc_html_e('Nofollow Domains', 'taplio-nofollow-checks'); ?></h1>

    <?php if (! $is_writable): ?>
        <div class="notice notice-error">
            <p><strong><?php esc_html_e('Warning:', 'taplio-nofollow-checks'); ?></strong> <?php esc_html_e('The JSON file is not writable.', 'taplio-nofollow-checks'); ?></p>
        </div>
    <?php endif; ?>

    <?php if ($updated): ?>
        <div class="notice notice-success is-dismissible">
            <p><?php esc_html_e('Domains updated successfully.', 'taplio-nofollow-checks'); ?></p>
        </div>
    <?php endif; ?>

    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <?php wp_nonce_field('taplio_update_domains'); ?>
        <input type="hidden" name="action" value="taplio_update_domains" />

        <textarea name="domains" rows="20" style="width: 100%; font-family: monospace;" placeholder="example.com"><?php echo esc_textarea($textarea_text); ?></textarea>

        <p>
            <button type="submit" class="button button-primary">
                <?php esc_html_e('Save', 'taplio-nofollow-checks'); ?>
            </button>
        </p>
    </form>
</div>