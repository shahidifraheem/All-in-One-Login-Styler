<?php
// Prevent direct access to the file for security reasons.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class All_in_One_Login_Styler_Image_Upload
 *
 * Handles uploading and saving custom login page images
 * such as the login logo and background image through the admin interface.
 */
class All_in_One_Login_Styler_Image_Upload
{
    /**
     * Constructor: Hook the image upload handler to the 'admin_init' action.
     */
    public function __construct()
    {
        add_action('admin_init', array($this, 'handle_image_upload'));
    }

    /**
     * Processes the image uploads for login logo and background image.
     *
     * Validates nonce, sanitizes inputs, handles file upload using
     * WordPress functions, attaches uploaded files to the media library,
     * and updates plugin options with the uploaded image URLs.
     */
    public function handle_image_upload()
    {
        // Verify nonce to protect against CSRF attacks before processing the upload.
        if (
            !isset($_POST['cl_upload_nonce']) ||
            !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['cl_upload_nonce'])), 'cl_upload_action')
        ) {
            return; // Abort if nonce is missing or invalid.
        }

        /**
         * Handle Login Logo Upload
         * Check if a logo file was uploaded.
         */
        if (!empty($_FILES['cl_login_logo']['tmp_name'])) {

            // Check for upload errors and return if any.
            $upload_error = isset($_FILES['cl_login_logo']['error']) ? intval($_FILES['cl_login_logo']['error']) : 0;
            if ($upload_error !== UPLOAD_ERR_OK) {
                add_settings_error('cl_login_logo', 'cl_upload_error', 'File upload error: ' . esc_html($upload_error), 'error');
                return;
            }

            // Sanitize and prepare the $_FILES array keys for safe handling.
            $file = array(
                'name'     => isset($_FILES['cl_login_logo']['name']) ? sanitize_file_name($_FILES['cl_login_logo']['name']) : '',
                'type'     => isset($_FILES['cl_login_logo']['type']) ? sanitize_mime_type($_FILES['cl_login_logo']['type']) : '',
                'tmp_name' => isset($_FILES['cl_login_logo']['tmp_name']) ? $_FILES['cl_login_logo']['tmp_name'] : '',
                'error'    => isset($_FILES['cl_login_logo']['error']) ? intval($_FILES['cl_login_logo']['error']) : 0,
                'size'     => isset($_FILES['cl_login_logo']['size']) ? intval($_FILES['cl_login_logo']['size']) : 0,
            );

            // Use WordPress to handle the file upload securely.
            $uploaded = wp_handle_upload($file, array(
                'test_form' => false, // Disable form test because we are handling it ourselves.
                'mimes' => array(    // Allowed mime types.
                    'jpg|jpeg|jpe' => 'image/jpeg',
                    'gif' => 'image/gif',
                    'png' => 'image/png',
                    'webp' => 'image/webp',
                ),
            ));

            // If upload was successful, process the file further.
            if (isset($uploaded['file']) && isset($uploaded['url'])) {
                // Verify the file type is valid.
                $filetype = wp_check_filetype(basename($uploaded['file']), null);
                if (empty($filetype['type'])) {
                    add_settings_error('cl_login_logo', 'cl_upload_error', 'Invalid file type.', 'error');
                    return;
                }

                // Prepare attachment post data for the WordPress media library.
                $attachment = array(
                    'post_mime_type' => $filetype['type'],
                    'post_title'     => preg_replace('/\.[^.]+$/', '', basename($uploaded['file'])),
                    'post_content'   => '',
                    'post_status'    => 'inherit'
                );

                // Insert attachment into the media library.
                $attach_id = wp_insert_attachment($attachment, $uploaded['file']);

                // Include image functions required for generating metadata.
                require_once(ABSPATH . 'wp-admin/includes/image.php');

                // Generate and update attachment metadata.
                $attach_data = wp_generate_attachment_metadata($attach_id, $uploaded['file']);
                wp_update_attachment_metadata($attach_id, $attach_data);

                // Save the uploaded logo URL in the options table.
                $result = update_option('cl_login_logo', esc_url_raw($uploaded['url']));
                if ($result) {
                    add_settings_error('cl_login_logo', 'cl_upload_success', 'Logo uploaded successfully!', 'updated');
                } else {
                    add_settings_error('cl_login_logo', 'cl_upload_error', 'Failed to save logo URL.', 'error');
                }
            } else {
                // Handle failure to upload file.
                add_settings_error('cl_login_logo', 'cl_upload_error', 'Failed to upload file.', 'error');
            }
        }

        /**
         * Handle Background Image Upload
         * Check if a background image file was uploaded.
         */
        if (!empty($_FILES['cl_login_bg_img']['tmp_name'])) {
            // Check for upload errors.
            $upload_error = isset($_FILES['cl_login_bg_img']['error']) ? intval($_FILES['cl_login_bg_img']['error']) : 0;
            if ($upload_error !== UPLOAD_ERR_OK) {
                add_settings_error('cl_login_bg_img', 'cl_upload_error', 'File upload error: ' . esc_html($upload_error), 'error');
                return;
            }

            // Sanitize and prepare the $_FILES array keys.
            $file = array(
                'name'     => isset($_FILES['cl_login_bg_img']['name']) ? sanitize_file_name($_FILES['cl_login_bg_img']['name']) : '',
                'type'     => isset($_FILES['cl_login_bg_img']['type']) ? sanitize_mime_type($_FILES['cl_login_bg_img']['type']) : '',
                'tmp_name' => isset($_FILES['cl_login_bg_img']['tmp_name']) ? $_FILES['cl_login_bg_img']['tmp_name'] : '',
                'error'    => isset($_FILES['cl_login_bg_img']['error']) ? intval($_FILES['cl_login_bg_img']['error']) : 0,
                'size'     => isset($_FILES['cl_login_bg_img']['size']) ? intval($_FILES['cl_login_bg_img']['size']) : 0,
            );

            // Handle file upload via WordPress.
            $uploaded = wp_handle_upload($file, array(
                'test_form' => false,
                'mimes' => array(
                    'jpg|jpeg|jpe' => 'image/jpeg',
                    'gif' => 'image/gif',
                    'png' => 'image/png',
                    'webp' => 'image/webp',
                ),
            ));

            // If upload succeeds, process media attachment.
            if (isset($uploaded['file']) && isset($uploaded['url'])) {
                $filetype = wp_check_filetype(basename($uploaded['file']), null);
                if (empty($filetype['type'])) {
                    add_settings_error('cl_login_bg_img', 'cl_upload_error', 'Invalid file type.', 'error');
                    return;
                }

                // Prepare attachment post data.
                $attachment = array(
                    'post_mime_type' => $filetype['type'],
                    'post_title'     => preg_replace('/\.[^.]+$/', '', basename($uploaded['file'])),
                    'post_content'   => '',
                    'post_status'    => 'inherit'
                );

                // Insert attachment into media library.
                $attach_id = wp_insert_attachment($attachment, $uploaded['file']);

                // Generate and update attachment metadata.
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                $attach_data = wp_generate_attachment_metadata($attach_id, $uploaded['file']);
                wp_update_attachment_metadata($attach_id, $attach_data);

                // Update option with the uploaded background image URL.
                $result = update_option('cl_login_bg_img', esc_url_raw($uploaded['url']));
                if ($result) {
                    add_settings_error('cl_login_bg_img', 'cl_upload_success', 'Background image uploaded successfully!', 'updated');
                } else {
                    add_settings_error('cl_login_bg_img', 'cl_upload_error', 'Failed to save background image URL.', 'error');
                }
            } else {
                add_settings_error('cl_login_bg_img', 'cl_upload_error', 'Failed to upload file.', 'error');
            }
        }
    }
}
