<?php
/**
 * Plugin Name: Gravity Forms Webhook Integration
 * Description: Sends Gravity Forms submissions to a configurable webhook (e.g., Webhook.site) using gform_after_submission.
 * Version: 1.1.0
 * Author: Nyasha Ushewokunze
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Returns the default webhook URL. Override via the gfwi_webhook_url filter.
 *
 * @return string
 */
function gfwi_webhook_url_default() {
	return 'https://webhook.site/351579d8-5ee7-486f-9139-1342b3b4147e';
}

/**
 * Register runtime hooks only after Gravity Forms is available.
 */
function gfwi_init() {
	if ( ! class_exists( 'GFForms' ) ) {
		add_action( 'admin_notices', 'gfwi_missing_gf_notice' );
		return;
	}

	add_action( 'gform_after_submission_1, 'gfwi_send_to_webhook', 10, 2 );
}
add_action( 'plugins_loaded', 'gfwi_init' );

/**
 * Admin notice shown when Gravity Forms is missing.
 */
function gfwi_missing_gf_notice() {
	if ( ! current_user_can( 'activate_plugins' ) ) {
		return;
	}

	echo '<div class="notice notice-error"><p><strong>Gravity Forms Webhook Integration</strong> requires Gravity Forms to be installed and active.</p></div>';
}

/**
 * Sends Gravity Forms entry data to an external webhook URL.
 *
 * @param array $entry The form entry data.
 * @param array $form  The form object.
 */
function gfwi_send_to_webhook( $entry, $form ) {
	$webhook_url = apply_filters( 'gfwi_webhook_url', gfwi_webhook_url_default(), $entry, $form );

	if ( empty( $webhook_url ) ) {
		return;
	}

	$data = array();

	if ( isset( $form['fields'] ) && is_array( $form['fields'] ) ) {
		foreach ( $form['fields'] as $field ) {
			if ( ! is_object( $field ) || ! isset( $field->id ) ) {
				continue;
			}

			$field_id = (string) $field->id;

			if ( ! empty( $field->label ) ) {
				$label = $field->label;
			} elseif ( ! empty( $field->adminLabel ) ) {
				$label = $field->adminLabel;
			} else {
				$label = 'field_' . $field_id;
			}

			if ( function_exists( 'rgar' ) ) {
				$value = rgar( $entry, $field_id );
			} else {
				$value = isset( $entry[ $field_id ] ) ? $entry[ $field_id ] : '';
			}

			$data[ $label ] = $value;
		}
	}

	$response = wp_remote_post(
		$webhook_url,
		array(
			'method'  => 'POST',
			'headers' => array( 'Content-Type' => 'application/json' ),
			'body'    => wp_json_encode( $data ),
			'timeout' => 20,
		)
	);

	if ( is_wp_error( $response ) ) {
		error_log( 'GF Webhook Error: ' . $response->get_error_message() );
	}
}

/**
 *add_action( 'gform_after_submission', 'post_to_third_party', 10, 2 );
 */


function post_to_third_party( $entry, $form ) {
 
    $endpoint_url = 'https://webhook.site/351579d8-5ee7-486f-9139-1342b3b4147e';
    $body = array(
        'first_name' => rgar( $entry, '1.3' ),
        'last_name' => rgar( $entry, '1.6' ),
        'message' => rgar( $entry, '3' ),
        );
    GFCommon::log_debug( 'gform_after_submission: body => ' . print_r( $body, true ) );
 
    $response = wp_remote_post( $endpoint_url, array( 'body' => $body ) );
    GFCommon::log_debug( 'gform_after_submission: response => ' . print_r( $response, true ) );
}
