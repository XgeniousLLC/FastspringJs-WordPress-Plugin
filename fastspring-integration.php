<?php
/*
Plugin Name: FastSpring Integration
Description: Integrate FastSpring payment with a button click to open the FastSpring payment popup.
Version: 1.0
Author: Sharifur Rahman
Author URI: https://xgenious.com
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Enqueue FastSpring library and custom script
function fastspring_enqueue_scripts() {
    // Register FastSpring script
    wp_register_script('fastspring-store-library', 'https://cdn.fastspring.com/v1/storefront.min.js', [], null, true);

    // Enqueue the FastSpring script
    wp_enqueue_script('fastspring-store-library');

    // Add custom script for handling button clicks
    wp_enqueue_script('fastspring-integration-script', plugin_dir_url(__FILE__) . 'js/fastspring-integration.js', ['jquery'], null, true);

    // Pass FastSpring store ID to the script
    wp_localize_script('fastspring-integration-script', 'fastspringConfig', [
        'storeId' => 'your_fastspring_store_id_here', // Replace with your actual FastSpring Store ID
    ]);
}
add_action('wp_enqueue_scripts', 'fastspring_enqueue_scripts');
