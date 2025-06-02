<?php

// Logo Handler Start
function cl_sanitize_logo_url($url)
{
    // Debugging: Log the input value
    error_log('Sanitization Input: ' . print_r($url, true));

    // If the URL is empty, return the existing value
    if (empty($url)) {
        $existing_url = get_option('cl_login_logo', '');
        error_log('Sanitization Output: (Empty Input, Returning Existing Value) ' . $existing_url);
        return $existing_url;
    }

    // Ensure $url is a string (convert null to an empty string)
    $url = is_string($url) ? $url : '';

    // Trim the URL to remove any extra spaces
    $url = trim($url);

    // Check if the URL is valid
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        $sanitized_url = esc_url_raw($url);
        error_log('Sanitization Output: ' . $sanitized_url);
        return $sanitized_url;
    }

    // If the URL is invalid, return the existing value
    $existing_url = get_option('cl_login_logo', '');
    error_log('Sanitization Output: (Invalid URL, Returning Existing Value) ' . $existing_url);
    return $existing_url;
}

function cl_handle_remove_logo()
{
    // Verify nonce for security
    error_log('Nonce Received: ' . $_POST['_ajax_nonce']);
    check_ajax_referer('cl_remove_logo_nonce', '_ajax_nonce');

    // Delete the logo option
    delete_option('cl_login_logo');

    // Send a success response
    wp_send_json_success();
}
add_action('wp_ajax_cl_remove_logo', 'cl_handle_remove_logo');
// Logo Handler End

// Background Image Handler Start
function cl_sanitize_bg_img_url($url)
{
    // Debugging: Log the input value
    error_log('Sanitization Input: ' . print_r($url, true));

    // If the URL is empty, return the existing value
    if (empty($url)) {
        $existing_url = get_option('cl_login_bg_img', '');
        error_log('Sanitization Output: (Empty Input, Returning Existing Value) ' . $existing_url);
        return $existing_url;
    }

    // Ensure $url is a string (convert null to an empty string)
    $url = is_string($url) ? $url : '';

    // Trim the URL to remove any extra spaces
    $url = trim($url);

    // Check if the URL is valid
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        $sanitized_url = esc_url_raw($url);
        error_log('Sanitization Output: ' . $sanitized_url);
        return $sanitized_url;
    }

    // If the URL is invalid, return the existing value
    $existing_url = get_option('cl_login_bg_img', '');
    error_log('Sanitization Output: (Invalid URL, Returning Existing Value) ' . $existing_url);
    return $existing_url;
}

function cl_handle_remove_bg_img()
{
    // Verify nonce for security
    error_log('Nonce Received: ' . $_POST['_ajax_nonce']);
    check_ajax_referer('cl_remove_bg_img_nonce', '_ajax_nonce');

    // Delete the bg_img option
    delete_option('cl_login_bg_img');

    // Send a success response
    wp_send_json_success();
}
add_action('wp_ajax_cl_remove_bg_img', 'cl_handle_remove_bg_img');
// Background Image Handler End
