<?php
/*
 * Plugin Name:        All in One Login Styler
 * Description:        A plugin to customize the WordPress login page with a custom logo and styles.
 * Version:            1.0
 * Requires at least:  6.0
 * Requires PHP:       7.4
 * Author:             Shahid Ifraheem
 * Author URI:         https://shahidifraheem.com/
 * License:            GPL v2 or later
 * License URI:        https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:        all-in-one-login-styler
 */

// Prevent direct access to this file for security reasons.
if (!defined('ABSPATH')) {
    exit;
}

// Load plugin core and admin files to provide functionality and admin interface.
require_once plugin_dir_path(__FILE__) . 'includes/functions.php';
require_once plugin_dir_path(__FILE__) . 'admin/class-admin-settings.php';
require_once plugin_dir_path(__FILE__) . 'admin/class-login-customizer.php';
require_once plugin_dir_path(__FILE__) . 'admin/class-image-upload.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-enqueue.php';

// Instantiate admin-only classes on admin pages to handle settings and image uploads.
if (is_admin()) {
    new All_in_One_Login_Styler_Admin_Settings();
    new All_in_One_Login_Styler_Image_Upload();
}

// Instantiate the enqueuing class to load styles and scripts as needed.
new All_in_One_Login_Styler_Enqueue();

// Instantiate the main login styler class to apply login page customizations.
new All_in_One_Login_Styler();
