<?php
/**
 * Plugin Name: Gravity PDF - Event Ticket Template
 * Description: Provides a clean event ticket PDF template with attendee summary, add-ons, QR link to the entry, and branding space.
 * Version: 1.0.0
 * Author: Nyasha Ushewokunze
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register template when Gravity PDF is available.
 */
function gpet_init() {
	if ( ! class_exists( 'GFPDF_Core' ) ) {
		add_action( 'admin_notices', 'gpet_missing_gp_notice' );
		return;
	}

	add_filter( 'gfpdf_template_paths', 'gpet_add_template_path' );
	add_filter( 'gfpdf_custom_templates', 'gpet_register_template' );
}
add_action( 'plugins_loaded', 'gpet_init' );

/**
 * Add the plugin templates path so Gravity PDF can auto-discover the template.
 *
 * @param array $paths Template search paths.
 *
 * @return array
 */
function gpet_add_template_path( $paths ) {
	$paths[] = plugin_dir_path( __FILE__ ) . 'templates/';

	return $paths;
}

/**
 * Add the Event Ticket template to Gravity PDF.
 *
 * @param array $templates Existing templates.
 *
 * @return array
 */
function gpet_register_template( $templates ) {
	$templates['gpet-event-ticket'] = array(
		'name'        => __( 'Event Ticket', 'gpet' ),
		'description' => __( 'Clean event ticket layout with attendee summary, add-ons, QR code, and logo.', 'gpet' ),
		'type'        => 'universal',
		'group'       => __( 'SimplyBiz', 'gpet' ),
		'path'        => plugin_dir_path( __FILE__ ) . 'templates/event-ticket/template.php',
	);

	return $templates;
}

/**
 * Show an admin notice when Gravity PDF is missing.
 */
function gpet_missing_gp_notice() {
	if ( ! current_user_can( 'activate_plugins' ) ) {
		return;
	}

	echo '<div class="notice notice-warning"><p><strong>Gravity PDF - Event Ticket Template</strong> requires the Gravity PDF plugin to be installed and active.</p></div>';
}
