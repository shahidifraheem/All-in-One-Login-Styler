<?php
if (!defined('ABSPATH')) {
    exit;
}

class All_in_One_Login_Styler_Image_Upload
{
    public function __construct()
    {
        add_action('admin_init', array($this, 'handle_image_upload'));
    }

    public function handle_image_upload()
    {
        // Handling Logo Upload
        if (!empty($_FILES['cl_login_logo']['tmp_name'])) {
            // Check for upload errors
            if ($_FILES['cl_login_logo']['error'] !== UPLOAD_ERR_OK) {
                add_settings_error('cl_login_logo', 'cl_upload_error', 'File upload error: ' . $_FILES['cl_login_logo']['error'], 'error');
                return;
            }

            // Handle the upload
            $uploaded = wp_handle_upload($_FILES['cl_login_logo'], array(
                'test_form' => false,
                'mimes' => array(
                    'jpg|jpeg|jpe' => 'image/jpeg',
                    'gif' => 'image/gif',
                    'png' => 'image/png',
                    'webp' => 'image/webp',
                ),
            ));

            // Debugging: Log the uploaded array
            error_log('Uploaded Array: ' . print_r($uploaded, true));

            if (isset($uploaded['file']) && isset($uploaded['url'])) {
                // Check file type
                $filetype = wp_check_filetype(basename($uploaded['file']), null);
                if (empty($filetype['type'])) {
                    add_settings_error('cl_login_logo', 'cl_upload_error', 'Invalid file type.', 'error');
                    return;
                }

                // Add the file to the Media Library
                $attachment = array(
                    'post_mime_type' => $filetype['type'],
                    'post_title'     => preg_replace('/\.[^.]+$/', '', basename($uploaded['file'])),
                    'post_content'   => '',
                    'post_status'    => 'inherit'
                );
                $attach_id = wp_insert_attachment($attachment, $uploaded['file']);

                // Generate metadata for the attachment
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                $attach_data = wp_generate_attachment_metadata($attach_id, $uploaded['file']);
                wp_update_attachment_metadata($attach_id, $attach_data);

                // Debugging: Log the URL to save
                error_log('URL to Save: ' . $uploaded['url']);

                // Store the logo URL
                $result = update_option('cl_login_logo', $uploaded['url']);
                if ($result) {
                    error_log('Update Option Result: Success');
                    add_settings_error('cl_login_logo', 'cl_upload_success', 'Logo uploaded successfully!', 'updated');
                } else {
                    error_log('Update Option Result: Failure');
                    add_settings_error('cl_login_logo', 'cl_upload_error', 'Failed to save logo URL.', 'error');
                }
            } else {
                add_settings_error('cl_login_logo', 'cl_upload_error', 'Failed to upload file.', 'error');
            }
        }

        // Handling Background Image Upload
        if (!empty($_FILES['cl_login_bg_img']['tmp_name'])) {
            // Check for upload errors
            if ($_FILES['cl_login_bg_img']['error'] !== UPLOAD_ERR_OK) {
                add_settings_error('cl_login_bg_img', 'cl_upload_error', 'File upload error: ' . $_FILES['cl_login_bg_img']['error'], 'error');
                return;
            }

            // Handle the upload
            $uploaded = wp_handle_upload($_FILES['cl_login_bg_img'], array(
                'test_form' => false,
                'mimes' => array(
                    'jpg|jpeg|jpe' => 'image/jpeg',
                    'gif' => 'image/gif',
                    'png' => 'image/png',
                    'webp' => 'image/webp',
                ),
            ));

            // Debugging: Log the uploaded array
            error_log('Uploaded Array: ' . print_r($uploaded, true));

            if (isset($uploaded['file']) && isset($uploaded['url'])) {
                // Check file type
                $filetype = wp_check_filetype(basename($uploaded['file']), null);
                if (empty($filetype['type'])) {
                    add_settings_error('cl_login_bg_img', 'cl_upload_error', 'Invalid file type.', 'error');
                    return;
                }

                // Add the file to the Media Library
                $attachment = array(
                    'post_mime_type' => $filetype['type'],
                    'post_title'     => preg_replace('/\.[^.]+$/', '', basename($uploaded['file'])),
                    'post_content'   => '',
                    'post_status'    => 'inherit'
                );
                $attach_id = wp_insert_attachment($attachment, $uploaded['file']);

                // Generate metadata for the attachment
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                $attach_data = wp_generate_attachment_metadata($attach_id, $uploaded['file']);
                wp_update_attachment_metadata($attach_id, $attach_data);

                // Debugging: Log the URL to save
                error_log('URL to Save: ' . $uploaded['url']);

                // Store the bg_img URL
                $result = update_option('cl_login_bg_img', $uploaded['url']);
                if ($result) {
                    error_log('Update Option Result: Success');
                    add_settings_error('cl_login_bg_img', 'cl_upload_success', 'Background image uploaded successfully!', 'updated');
                } else {
                    error_log('Update Option Result: Failure');
                    add_settings_error('cl_login_bg_img', 'cl_upload_error', 'Failed to save background image URL.', 'error');
                }
            } else {
                add_settings_error('cl_login_bg_img', 'cl_upload_error', 'Failed to upload file.', 'error');
            }
        }
    }
}
