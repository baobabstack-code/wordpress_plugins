<?php

namespace MyCustomPlugin;

/**
 * Main Plugin Class
 */
class Plugin {

    /**
     * The loader that's responsible for maintaining and registering all hooks
     */
    protected $loader;

    /**
     * Initialize the plugin
     */
    public function __construct() {
        $this->loader = new Loader();
        $this->load_dependencies();
        $this->define_admin_hooks();
        $this->define_public_hooks();
        $this->define_integration_hooks();
    }

    /**
     * Load required dependencies
     */
    private function load_dependencies() {
        // Add any additional dependencies here
    }

    /**
     * Register admin-specific hooks
     */
    private function define_admin_hooks() {
        $admin = new Admin\Admin();

        $this->loader->add_action('admin_enqueue_scripts', $admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $admin, 'enqueue_scripts');
        $this->loader->add_action('admin_menu', $admin, 'add_admin_menu');
    }

    /**
     * Register public-facing hooks
     */
    private function define_public_hooks() {
        $public = new PublicFacing\PublicFacing();

        $this->loader->add_action('wp_enqueue_scripts', $public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $public, 'enqueue_scripts');
    }

    /**
     * Register integration hooks
     */
    private function define_integration_hooks() {
        // Gravity Forms Webhook Integration
        if (class_exists('GFForms')) {
            $gravity_webhook = new Integrations\GravityFormsWebhook();
            $this->loader->add_action('gform_after_submission', $gravity_webhook, 'handle_submission', 10, 2);
        }
    }

    /**
     * Run the plugin
     */
    public function run() {
        $this->loader->run();
    }
}
