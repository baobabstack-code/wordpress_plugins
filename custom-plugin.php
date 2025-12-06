<?php

/*
Plugin Name: Custom Plugin
Description: Loads custom CSS and JavaScript on Baoabab Tech
Version: 1.0
Author: Nyasha Ushewokunze
*/

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

/**
 * Enqueue custom CSS and JS
 */
function custom_plugin_enqueue_scripts()
{
    // Enqueue CSS
    wp_enqueue_style(
        'custom-plugin-css',
        plugin_dir_url(__FILE__) . 'assets/css/custom.css',
        array(),
        '1.0'
    );

    // Enqueue JS
    wp_enqueue_script(
        'custom-plugin-js',
        plugin_dir_url(__FILE__) . 'assets/js/custom.js',
        array('jquery'),
        '1.0',
        true // Load in footer
    );
}

add_action('wp_enqueue_scripts', 'custom_plugin_enqueue_scripts');
