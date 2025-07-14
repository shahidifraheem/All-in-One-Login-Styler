<?php
// Prevent direct access to the file for security reasons.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class AIOLS_Enqueue
 *
 * Handles the enqueueing of admin styles and scripts for the "Customize Login" plugin.
 * This includes loading CSS, JavaScript, and enabling the WordPress media uploader
 * on the plugin's settings page in the WordPress admin dashboard.
 */
class AIOLS_Enqueue
{
    /**
     * Constructor hooks the enqueue methods to appropriate WordPress actions.
     * - Enqueues plugin assets on admin pages.
     * - Loads the media uploader scripts globally on admin pages.
     */
    public function __construct()
    {
        add_action('admin_enqueue_scripts', array($this, 'aiols_enqueue_admin_assets'));

        // Enqueue WordPress media uploader scripts on all admin pages
        add_action('admin_enqueue_scripts', function () {
            wp_enqueue_media();
        });
    }

    /**
     * Enqueues CSS and JavaScript files only on the plugin's settings admin page.
     *
     * @param string $hook The current admin page hook suffix.
     */
    public function aiols_enqueue_admin_assets($hook)
    {
        // Only enqueue assets on the plugin's top-level admin menu page
        if ($hook === 'toplevel_page_all-in-one-login-styler') {

            // Enqueue admin styles with versioning based on file modification time for cache busting
            wp_enqueue_style(
                'all-in-one-login-styler-admin-styles',
                plugin_dir_url(__FILE__) . '../assets/css/admin-styles.css',
                array(),
                filemtime(plugin_dir_path(__FILE__) . '../assets/css/admin-styles.css')
            );

            // Enqueue admin scripts, dependent on jQuery, loaded in the footer with versioning
            wp_enqueue_script(
                'aiols-admin-script',
                plugin_dir_url(__FILE__) . '../assets/js/admin-scripts.js',
                array('jquery'),
                filemtime(plugin_dir_path(__FILE__) . '../assets/js/admin-scripts.js'),
                true
            );

            // Localize script with AJAX URL and nonces for secure AJAX requests
            wp_localize_script('aiols-admin-script', 'aiols_admin_vars', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'remove_bg_img_nonce' => wp_create_nonce('aiols_remove_bg_img_nonce'),
                'remove_logo_nonce'   => wp_create_nonce('aiols_remove_logo_nonce'),
            ));
        }
    }
}
