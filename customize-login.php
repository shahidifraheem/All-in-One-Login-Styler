<?php
/*
* Plugin Name: Customize Login
* Description: A plugin to customize the WordPress login page with a own logo and styles.
* Version: 1.0
* Requires at least: 5.2
* Requires PHP:      7.2
* Author: Shahid Ifraheem
* Author URI: https://shahidifraheem.com/
* Text Domain: customize-login
*/

// Prevent direct access to the file
if (!defined('ABSPATH')) {
    exit;
}

// Load necessary files
require_once plugin_dir_path(__FILE__) . 'includes/functions.php';
require_once plugin_dir_path(__FILE__) . 'admin/class-admin-settings.php';
require_once plugin_dir_path(__FILE__) . 'admin/class-login-customizer.php';
require_once plugin_dir_path(__FILE__) . 'admin/class-image-upload.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-enqueue.php';

// Initialize classes
if (is_admin()) {
    new Customize_Login_Admin_Settings();
    new Customize_Login_Image_Upload();
}
new Customize_Login_Enqueue();
new Customize_Login_Customizer();
