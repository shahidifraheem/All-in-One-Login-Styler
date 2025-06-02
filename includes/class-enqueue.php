<?php
if (!defined('ABSPATH')) {
    exit;
}

class Customize_Login_Enqueue
{
    public function __construct()
    {
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        add_action('admin_enqueue_scripts', function () {
            wp_enqueue_media(); // Enables the media uploader
        });
    }

    public function enqueue_admin_assets($hook)
    {
        // Only load scripts on the plugin's settings page
        if ($hook === 'toplevel_page_customize-login') {
            wp_enqueue_style(
                'customize-login-admin-styles',
                plugin_dir_url(__FILE__) . '../assets/css/admin-styles.css'
            );

            // Enqueue the script
            wp_enqueue_script('cl-admin-script', plugin_dir_url(__FILE__) . '../assets/js/admin-scripts.js', array('jquery'), null, true);

            // Localize the script with nonce
            wp_localize_script('cl-admin-script', 'cl_admin_vars', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'remove_bg_img_nonce' => wp_create_nonce('cl_remove_bg_img_nonce'),
                'remove_logo_nonce'   => wp_create_nonce('cl_remove_logo_nonce'),
            ));
        }
    }
}
