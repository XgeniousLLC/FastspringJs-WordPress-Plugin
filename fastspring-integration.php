<?php
/*
Plugin Name: FastSpring Integration
Description: Integrate FastSpring payment with a button click to open the FastSpring payment popup.
Version: 1.3.0
Author: Sharifur Rahman
Author URI: https://xgenious.com
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
// Include the admin settings file
require_once plugin_dir_path(__FILE__) . 'includes/admin-settings.php';

// Enqueue the FastSpring script dynamically
function fastspring_enqueue_scripts() {
    $storefront_id = get_option('fastspring_storefront_id', '');
    $selected_pages = get_option('fastspring_load_pages', []);

    // Check if FastSpring should be loaded on the current page
    if (!empty($storefront_id) && is_page($selected_pages)) {
        wp_enqueue_script(
            'fastspring-store-library',
            'https://sbl.onfastspring.com/sbl/0.9.2/fastspring-builder.min.js',
            [],
            null,
            true
        );

        // Add custom attributes to the script tag
        add_filter('script_loader_tag', function ($tag, $handle) use ($storefront_id) {
            if ('fastspring-store-library' !== $handle) {
                return $tag;
            }

            return sprintf(
                '<script id="fsc-api" src="https://sbl.onfastspring.com/sbl/0.9.2/fastspring-builder.min.js" type="text/javascript" data-storefront="%s"></script>',
                esc_attr($storefront_id)
            );
        }, 10, 2);
    }
}
add_action('wp_enqueue_scripts', 'fastspring_enqueue_scripts');