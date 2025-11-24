<?php
/**
 * Plugin Name: Gravity Forms Webhook Integration
 * Description: Sends Gravity Forms submissions to an external API using the gform_after_submission hook.
 * Version: 1.0
 * Author: Nyasha Ushewokunze
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Hook into Gravity Forms after a form is submitted
add_action('gform_after_submission', 'gf_send_to_webhook', 10, 2);

/**
 * Sends GF entry data to an external webhook URL.
 *
 * @param array $entry  The form entry data.
 * @param array $form   The form object.
 */
function gf_send_to_webhook($entry, $form) {

    // 1. Add your Webhook URL here (Webhook.site gives you a unique URL)
    $webhook_url = 'https://webhook.site/https://webhook.site/6d297428-e170-4881-859f-5c8c63537efd';

    // 2. Prepare data to send
    $data = [];

    foreach ($form['fields'] as $field) {
        $field_id = $field->id;
        $label = $field->label;
        $value = rgar($entry, $field_id);

        $data[$label] = $value;
    }

    // 3. Send using wp_remote_post()
    $response = wp_remote_post($webhook_url, [
        'method'  => 'POST',
        'headers' => ['Content-Type' => 'application/json'],
        'body'    => json_encode($data),
        'timeout' => 20,
    ]);

    // Optional: Log errors to debug log
    if (is_wp_error($response)) {
        error_log('GF Webhook Error: ' . $response->get_error_message());
    }
}
