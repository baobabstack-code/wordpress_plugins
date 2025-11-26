<?php
/**
 * Plugin Name: Simple Frontend Plugin
 * Plugin URI: https://github.com/baobabstack-code/wordpress_plugin
 * Description: A simple working frontend plugin with shortcodes and admin settings
 * Version: 1.0.0
 * Author: Developer
 * License: GPL v2 or later
 * Text Domain: simple-frontend-plugin
 */

if (!defined('WPINC')) {
    die;
}

// Define constants
define('SFP_VERSION', '1.0.0');
define('SFP_PATH', plugin_dir_path(__FILE__));
define('SFP_URL', plugin_dir_url(__FILE__));
define('SFP_OPTION_NAME', 'sfp_options');

// Include admin settings when in admin
if (is_admin()) {
    if (file_exists(SFP_PATH . 'includes/admin-settings.php')) {
        require_once SFP_PATH . 'includes/admin-settings.php';
    }
}

// Register styles and scripts
function sfp_enqueue_assets() {
    wp_enqueue_style(
        'sfp-style',
        SFP_URL . 'assets/css/simple-frontend.css',
        array(),
        SFP_VERSION
    );
    
    wp_enqueue_script(
        'sfp-script',
        SFP_URL . 'assets/js/simple-frontend.js',
        array('jquery'),
        SFP_VERSION,
        true
    );
    
    // Localize script for AJAX
    wp_localize_script('sfp-script', 'sfpData', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('sfp-nonce')
    ));
}
add_action('wp_enqueue_scripts', 'sfp_enqueue_assets');

// Add settings link on plugins page
function sfp_plugin_action_links($links) {
    $settings_link = '<a href="options-general.php?page=sfp-settings">Settings</a>';
    array_unshift($links, $settings_link);
    return $links;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'sfp_plugin_action_links');

// Shortcode: Display a contact form
function sfp_contact_form_shortcode() {
    ob_start();
    ?>
    <div class="sfp-contact-form">
        <h3>Contact Us</h3>
        <form id="sfp-contact-form" method="POST">
            <div class="form-group">
                <label for="sfp-name">Name:</label>
                <input type="text" id="sfp-name" name="name" required>
            </div>
            <div class="form-group">
                <label for="sfp-email">Email:</label>
                <input type="email" id="sfp-email" name="email" required>
            </div>
            <div class="form-group">
                <label for="sfp-message">Message:</label>
                <textarea id="sfp-message" name="message" rows="5" required></textarea>
            </div>
            <button type="submit" class="sfp-btn">Send Message</button>
        </form>
        <div id="sfp-response"></div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('sfp_contact', 'sfp_contact_form_shortcode');

// Handle form submission via AJAX
function sfp_handle_contact_form() {
    check_ajax_referer('sfp-nonce', 'nonce');
    
    $name = sanitize_text_field($_POST['name'] ?? '');
    $email = sanitize_email($_POST['email'] ?? '');
    $message = sanitize_textarea_field($_POST['message'] ?? '');
    
    if (!$name || !$email || !$message) {
        wp_send_json_error('All fields are required');
    }
    
    // Use configured recipient if set, otherwise admin email
    $options = get_option(SFP_OPTION_NAME, array());
    $recipient = !empty($options['recipient_email']) ? sanitize_email($options['recipient_email']) : get_option('admin_email');
    
    // Prepare email
    $subject = 'New Contact Form Submission: ' . $name;
    $body = "Name: $name\n";
    $body .= "Email: $email\n";
    $body .= "Message:\n$message";
    
    // Send email
    $sent = wp_mail($recipient, $subject, $body);
    
    if ($sent) {
        wp_send_json_success('Message sent successfully!');
    } else {
        wp_send_json_error('Failed to send message');
    }
}
add_action('wp_ajax_sfp_submit_contact', 'sfp_handle_contact_form');
add_action('wp_ajax_nopriv_sfp_submit_contact', 'sfp_handle_contact_form');

// Shortcode: Display testimonials
function sfp_testimonials_shortcode($atts) {
    $atts = shortcode_atts(array('count' => 3), $atts);
    
    ob_start();
    ?>
    <div class="sfp-testimonials">
        <h3>What Our Customers Say</h3>
        <div class="testimonials-grid">
            <div class="testimonial-card">
                <p class="testimonial-text">"This plugin is amazing!"</p>
                <p class="testimonial-author">- John Doe</p>
            </div>
            <div class="testimonial-card">
                <p class="testimonial-text">"Easy to use and powerful."</p>
                <p class="testimonial-author">- Jane Smith</p>
            </div>
            <div class="testimonial-card">
                <p class="testimonial-text">"Highly recommended!"</p>
                <p class="testimonial-author">- Mike Johnson</p>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('sfp_testimonials', 'sfp_testimonials_shortcode');

// Shortcode: Display counter
function sfp_counter_shortcode($atts) {
    $atts = shortcode_atts(array('number' => 100), $atts);
    
    ob_start();
    ?>
    <div class="sfp-counter">
        <div class="counter-display">
            <p class="counter-number" data-number="<?php echo intval($atts['number']); ?>">0</p>
            <p class="counter-label">Happy Users</p>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('sfp_counter', 'sfp_counter_shortcode');

// Plugin activation
function sfp_activate_plugin() {
    // Set default options if not set
    $defaults = array('recipient_email' => get_option('admin_email'));
    if (get_option(SFP_OPTION_NAME) === false) {
        add_option(SFP_OPTION_NAME, $defaults);
    }
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'sfp_activate_plugin');

// Plugin deactivation
function sfp_deactivate_plugin() {
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'sfp_deactivate_plugin');
