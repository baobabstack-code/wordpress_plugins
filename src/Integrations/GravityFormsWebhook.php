<?php

namespace MyCustomPlugin\Integrations;

/**
 * Gravity Forms Webhook Integration
 * 
 * Sends form submission data to an external webhook endpoint
 */
class GravityFormsWebhook {

    /**
     * Initialize the integration
     */
    public function __construct() {
        // Constructor can be used for any initialization if needed
    }

    /**
     * Handle form submission and send to webhook
     * 
     * @param array $entry The entry that was just created
     * @param array $form The form object
     */
    public function handle_submission($entry, $form) {
        // Get webhook URL - can be filtered for easy configuration
        $webhook_url = apply_filters('my_custom_plugin_webhook_url', 'https://webhook.site/6d297428-e170-4881-859f-5c8c63537efd');

        // Prepare the payload
        $payload = $this->prepare_payload($entry, $form);

        // Send to webhook
        $response = wp_remote_post($webhook_url, array(
            'method'      => 'POST',
            'timeout'     => 30,
            'headers'     => array(
                'Content-Type' => 'application/json',
            ),
            'body'        => json_encode($payload),
            'data_format' => 'body',
        ));

        // Log the response for debugging
        if (is_wp_error($response)) {
            error_log('My Custom Plugin Webhook Error: ' . $response->get_error_message());
        } else {
            $response_code = wp_remote_retrieve_response_code($response);
            $response_body = wp_remote_retrieve_body($response);
            
            if ($response_code >= 200 && $response_code < 300) {
                error_log('My Custom Plugin: Webhook sent successfully. Response code: ' . $response_code);
            } else {
                error_log('My Custom Plugin: Webhook failed. Response code: ' . $response_code . ', Body: ' . $response_body);
            }
        }
    }

    /**
     * Prepare the payload data from entry and form
     * 
     * @param array $entry The entry data
     * @param array $form The form object
     * @return array The formatted payload
     */
    private function prepare_payload($entry, $form) {
        // Basic entry information
        $payload = array(
            'entry_id'     => rgar($entry, 'id'),
            'form_id'      => rgar($entry, 'form_id'),
            'form_title'   => rgar($form, 'title'),
            'date_created' => rgar($entry, 'date_created'),
            'ip'           => rgar($entry, 'ip'),
            'source_url'   => rgar($entry, 'source_url'),
            'user_agent'   => rgar($entry, 'user_agent'),
            'fields'       => array(),
        );

        // Extract all field values
        if (isset($form['fields']) && is_array($form['fields'])) {
            foreach ($form['fields'] as $field) {
                $field_id = $field->id;
                $field_label = $field->label;
                $field_value = rgar($entry, $field_id);

                // Handle different field types
                $payload['fields'][] = array(
                    'id'    => $field_id,
                    'label' => $field_label,
                    'type'  => $field->type,
                    'value' => $field_value,
                );
            }
        }

        // Allow filtering of the payload
        return apply_filters('my_custom_plugin_webhook_payload', $payload, $entry, $form);
    }
}
