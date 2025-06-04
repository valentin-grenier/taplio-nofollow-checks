<?php

/**
 * Class: Content Processor
 * 
 * This class processes the content of posts to check for nofollow links and ensure that external competitors are not linked with dofollow attributes.
 */

defined('ABSPATH') || exit;

class Taplio_Content_Processor
{
    private $domain_manager;

    public function __construct(Taplio_Domain_Manager $domain_manager)
    {
        $this->domain_manager = $domain_manager;
    }

    # Trigger the content processing when a post is saved
    public function handle_post_save($post_ID, $post, $update)
    {
        if ($post->post_type !== 'post') {
            return;
        }

        $domains = $this->domain_manager->get_domains();
        if (empty($domains)) {
            return;
        }

        $content  = $post->post_content;
        $modified = $this->add_nofollow($content, $domains);

        if ($modified !== $content) {
            remove_action('save_post', [$this, 'handle_post_save'], 10);

            wp_update_post([
                'ID'           => $post_ID,
                'post_content' => $modified,
            ]);

            add_action('save_post', [$this, 'handle_post_save'], 10, 3);
        }
    }

    # Add nofollow to links in the content
    private function add_nofollow($content, $domains)
    {
        return preg_replace_callback(
            '#<a\s[^>]*href=["\'](https?://[^"\']+)["\'][^>]*>#i',
            function ($matches) use ($domains) {
                $full_tag = $matches[0];
                $url      = $matches[1];

                foreach ($domains as $domain) {
                    if (strpos($url, $domain) !== false) {
                        // If rel attribute exists
                        if (preg_match('/rel=["\']([^"\']*)["\']/', $full_tag, $rel_match)) {
                            $rel_parts = preg_split('/\s+/', $rel_match[1]);
                            if (!in_array('nofollow', $rel_parts, true)) {
                                $rel_parts[] = 'nofollow';
                                $new_rel     = 'rel="' . esc_attr(trim(implode(' ', $rel_parts))) . '"';
                                $full_tag    = str_replace($rel_match[0], $new_rel, $full_tag);
                            }
                        } else {
                            // No rel attribute → add it
                            $full_tag = str_replace('<a ', '<a rel="nofollow" ', $full_tag);
                        }

                        return $full_tag;
                    }
                }

                return $full_tag;
            },
            $content
        );
    }
}
