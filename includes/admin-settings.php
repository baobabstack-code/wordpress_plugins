<?php

if (!defined('WPINC')) {
    die;
}

// Register settings and add settings page
function sfp_register_settings() {
    register_setting('sfp_options_group', SFP_OPTION_NAME, 'sfp_sanitize_options');
    add_settings_section('sfp_main_section', 'Simple Frontend Plugin Settings', null, 'sfp-settings');
    add_settings_field('recipient_email', 'Recipient Email', 'sfp_recipient_email_field_cb', 'sfp-settings', 'sfp_main_section');
}
add_action('admin_init', 'sfp_register_settings');

function sfp_sanitize_options($input) {
    $output = array();
    if (isset($input['recipient_email'])) {
        $output['recipient_email'] = sanitize_email($input['recipient_email']);
    }
    return $output;
}

function sfp_recipient_email_field_cb() {
    $options = get_option(SFP_OPTION_NAME, array('recipient_email' => get_option('admin_email')));
    $value = isset($options['recipient_email']) ? esc_attr($options['recipient_email']) : '';
    echo "<input type=\"email\" name=\"" . SFP_OPTION_NAME . "[recipient_email]\" value=\"$value\" class=\"regular-text\" />";
}

// Add settings page under Settings menu
function sfp_add_settings_page() {
    add_options_page('Simple Frontend Settings', 'Simple Frontend', 'manage_options', 'sfp-settings', 'sfp_render_settings_page');
}
add_action('admin_menu', 'sfp_add_settings_page');

function sfp_render_settings_page() {
    if (!current_user_can('manage_options')) {
        return;
    }
    ?>
    <div class="wrap">
        <h1>Simple Frontend Plugin Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('sfp_options_group');
            do_settings_sections('sfp-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}
