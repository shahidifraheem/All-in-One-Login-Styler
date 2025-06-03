<?php
/*
* Plugin Name:        Customize Login
* Description:        A plugin to customize the WordPress login page with a own logo and styles.
* Version:            1.0
* Requires at least:  6.0
* Requires PHP:       7.4
* Author:             Shahid Ifraheem
* Author URI:         https://shahidifraheem.com/
* License:            GPL v2 or later
* License URI:        https://www.gnu.org/licenses/gpl-2.0.html
* Text Domain:        customize-login-page
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
