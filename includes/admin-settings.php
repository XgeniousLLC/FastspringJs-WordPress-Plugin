<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Add a FastSpring submenu under Settings
function fastspring_add_settings_submenu() {
    add_options_page(
        __('FastSpring Settings', 'fastspring-integration'), // Page title
        __('FastSpring', 'fastspring-integration'), // Menu title
        'manage_options', // Capability
        'fastspring-settings', // Menu slug
        'fastspring_settings_page_callback' // Callback function
    );
}
add_action('admin_menu', 'fastspring_add_settings_submenu');

// Register the FastSpring settings
function fastspring_register_settings() {
    register_setting('fastspring-settings-group', 'fastspring_storefront_id', [
        'type' => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'default' => '',
    ]);

    register_setting('fastspring-settings-group', 'fastspring_load_pages', [
        'type' => 'array',
        'sanitize_callback' => 'fastspring_sanitize_pages',
        'default' => [],
    ]);
}
add_action('admin_init', 'fastspring_register_settings');

// Sanitize selected pages
function fastspring_sanitize_pages($input) {
    return is_array($input) ? array_map('intval', $input) : [];
}

// Render the FastSpring settings page
function fastspring_settings_page_callback() {
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('FastSpring Settings', 'fastspring-integration'); ?></h1>
        <form method="post" action="options.php">
            <?php
            // Output security fields for the registered settings
            settings_fields('fastspring-settings-group');
            // Output setting sections and fields
            do_settings_sections('fastspring-settings-group');
            ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Storefront ID', 'fastspring-integration'); ?></th>
                    <td>
                        <input type="text" name="fastspring_storefront_id" value="<?php echo esc_attr(get_option('fastspring_storefront_id', '')); ?>" class="regular-text">
                        <p class="description"><?php esc_html_e('Enter your FastSpring Storefront ID (e.g., yourstore.onfastspring.com/popup-yourstore)', 'fastspring-integration'); ?></p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Load on Pages', 'fastspring-integration'); ?></th>
                    <td>
                        <?php
                        // Fetch all published pages
                        $pages = get_pages();
                        $selected_pages = get_option('fastspring_load_pages', []);
                        ?>
                        <select name="fastspring_load_pages[]" multiple style="width: 100%; height: 150px;">
                            <?php foreach ($pages as $page): ?>
                                <option value="<?php echo esc_attr($page->ID); ?>" <?php echo in_array($page->ID, $selected_pages) ? 'selected' : ''; ?>>
                                    <?php echo esc_html($page->post_title); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <p class="description"><?php esc_html_e('Select the pages where the FastSpring JavaScript file should be loaded.', 'fastspring-integration'); ?></p>
                    </td>
                </tr>
            </table>
            <?php
            // Output save settings button
            submit_button();
            ?>
        </form>
    </div>
    <?php
}
