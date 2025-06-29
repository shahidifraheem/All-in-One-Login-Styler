<?php
// If uninstall not called from WordPress, then exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Option keys used in the plugin
$option_keys = [
    'aiols_enable_customization',
    'aiols_login_logo',
    'aiols_login_bg_img',
    'aiols_background_color',
    'aiols_button_color',
    'aiols_form_color',
    'aiols_fields_border_color',
    'aiols_form_radius',
    'aiols_links_color',
    'aiols_form_width',
];

// Loop through and delete each option
foreach ($option_keys as $key) {
    delete_option($key);
}
