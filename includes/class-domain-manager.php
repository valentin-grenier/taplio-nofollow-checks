<?php

/**
 * Class: Domain Manager
 * 
 * This class manages the domains used in the plugin, including adding, removing, and checking domains.
 */

defined('ABSPATH') || exit;

class Taplio_Domain_Manager
{
    private $file_path;

    public function __construct($file_path)
    {
        $this->file_path = $file_path;
    }

    # Get domains from the JSON file
    public function get_domains()
    {
        if (!file_exists($this->file_path)) {
            return [];
        }

        $json = json_decode(file_get_contents($this->file_path), true);

        return is_array($json['nofollow_domains'] ?? null) ? $json['nofollow_domains'] : [];
    }

    # Save domains to the JSON file
    public function save_domains(array $domains)
    {
        $data = [
            'nofollow_domains' => array_values(array_unique(array_filter(array_map('trim', $domains)))),
        ];

        file_put_contents($this->file_path, wp_json_encode($data, JSON_PRETTY_PRINT));
    }

    # Check if JSON file exists and is writable
    public function is_writable()
    {
        return file_exists($this->file_path) && is_writable($this->file_path);
    }
}
