<?php

/**
 * Plugin Name: WP Plugin Boilerplate
 * Plugin URI: https://github.com/valentin-grenier/wp-plugin-boilerplate
 * Description: A boilerplate for WordPress plugins to help you get started quickly.
 * Version: 1.0
 * Author: Valentin Grenier • Studio Val
 * Author URI: https://studio-val.fr
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wp-plugin-boilerplate
 */

if (!defined('ABSPATH')) {
    exit; # Exit if accessed directly
}

# Plugin Directory
if (!defined('PLUGIN_DIR')) {
    define('PLUGIN_DIR', plugin_dir_path(__FILE__));
}

# Plugin URL
if (!defined('PLUGIN_URL')) {
    define('PLUGIN_URL', plugin_dir_url(__FILE__));
}

# Automatically require all PHP files in the includes directory
foreach (glob(PLUGIN_DIR . 'includes/*.php') as $file) {
    require_once($file);
}

# Load Dotenv if it exists
$autoload_path = PLUGIN_DIR . 'vendor/autoload.php';

if (file_exists($autoload_path)) {
    require_once($autoload_path);
} else {
    wp_die('Autoload file not found. Please run <code>composer install</code> to install dependencies.');
}

use Dotenv\Dotenv;

$env_path = dirname(__DIR__, 3);

if (file_exists($env_path . '/.env')) {
    $dotenv = Dotenv::createImmutable($env_path);
    $dotenv->safeLoad();
} else {
    error_log('[WP Plugin Boilerplate] .env file not found at ' . $env_path);
}

# Initialize the plugin
function plugin_gifts_init()
{
    new Plugin_Init();
    new Plugin_Admin();
}

add_action('plugins_loaded', 'plugin_gifts_init');
