<?php

/**
 * Plugin Name: Taplio Nofollow Checks
 * Plugin URI: https://github.com/valentin-grenier/taplio-nofollow-checks
 * Description: A WordPress plugin to check for nofollow links in posts, ensuring external competitors are not linked with dofollow attributes.
 * Version: 1.0
 * Author: Valentin Grenier • Studio Val
 * Author URI: https://studio-val.fr
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: taplio-nofollow-checks
 */

if (!defined('ABSPATH')) {
    exit; # Exit if accessed directly
}

# Plugin Directory
if (!defined('TAPLIO_DIR')) {
    define('TAPLIO_DIR', plugin_dir_path(__FILE__));
}

# Plugin URL
if (!defined('TAPLIO_URL')) {
    define('TAPLIO_URL', plugin_dir_url(__FILE__));
}

# JSON file path
if (!defined('JSON_FILE')) {
    define('JSON_FILE', TAPLIO_DIR . 'data/domains.json');
}

# Automatically require all PHP files in the includes directory
foreach (glob(TAPLIO_DIR . 'includes/*.php') as $file) {
    require_once($file);
}

# Load Dotenv if it exists
$autoload_path = TAPLIO_DIR . 'vendor/autoload.php';

if (file_exists($autoload_path)) {
    require_once($autoload_path);
} else {
    wp_die('Autoload file not found. Please run <code>composer install</code> to install dependencies.');
}

# Initialize the plugin
function taplio_init()
{
    # Init plugin assets
    new Taplio_Init();

    # Init hooks
    $domain_manager    = new Taplio_Domain_Manager(JSON_FILE);
    $content_processor = new Taplio_Content_Processor($domain_manager);

    # Hook into save_post for post_content updates
    add_action('save_post', [$content_processor, 'handle_post_save'], 10, 3);

    # Load admin page if in admin area
    if (is_admin()) {
        new Taplio_Admin();
    }
}

add_action('plugins_loaded', 'taplio_init');
