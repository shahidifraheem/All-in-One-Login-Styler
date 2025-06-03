<?php
// If uninstall not called from WordPress, then exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Option keys used in the plugin
$option_keys = [
    'cl_enable_customization',
    'cl_login_logo',
    'cl_login_bg_img',
    'cl_background_color',
    'cl_button_color',
    'cl_form_color',
    'cl_fields_border_color',
    'cl_form_radius',
    'cl_links_color',
    'cl_form_width',
];

// Loop through and delete each option
foreach ($option_keys as $key) {
    delete_option($key);
}
