<?php
/**
 * Plugin Name: Baobabstack Custom Assets
 * Description: Boilerplate plugin to enqueue custom JavaScript and CSS for the site.
 * Version: 1.0.0
 * Author: Baobabstack
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'BA_CA_VERSION', '1.0.0' );
define( 'BA_CA_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'BA_CA_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

function ba_ca_enqueue_public_assets() {
    $version = BA_CA_VERSION;

    wp_enqueue_style(
        'ba-ca-styles',
        BA_CA_PLUGIN_URL . 'assets/css/custom.css',
        array(),
        $version
    );

    wp_enqueue_script(
        'ba-ca-scripts',
        BA_CA_PLUGIN_URL . 'assets/js/custom.js',
        array( 'jquery' ),
        $version,
        true
    );
}
add_action( 'wp_enqueue_scripts', 'ba_ca_enqueue_public_assets' );

function ba_ca_enqueue_admin_assets() {
    $version = BA_CA_VERSION;

    wp_enqueue_style(
        'ba-ca-admin-styles',
        BA_CA_PLUGIN_URL . 'assets/css/admin.css',
        array(),
        $version
    );

    wp_enqueue_script(
        'ba-ca-admin-scripts',
        BA_CA_PLUGIN_URL . 'assets/js/admin.js',
        array( 'jquery' ),
        $version,
        true
    );
}
add_action( 'admin_enqueue_scripts', 'ba_ca_enqueue_admin_assets' );

function ba_ca_demo_shortcode() {
    $demo_path = BA_CA_PLUGIN_DIR . 'demo-snippet.html';

    if ( is_readable( $demo_path ) ) {
        $html = file_get_contents( $demo_path );
        return $html ?: '<p>Baobabstack demo snippet could not be loaded.</p>';
    }

    return '<p>Baobabstack demo snippet not found.</p>';
}
add_shortcode( 'baobabstack_demo', 'ba_ca_demo_shortcode' );
