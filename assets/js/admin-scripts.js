jQuery(document).ready(function ($) {
    let logoMediaUploader;
    let bgMediaUploader;

    $(".aiols_checkbox").change(function () {
        if ($(this).is(":checked")) {
            $(this).val(1);
        } else {
            $(this).val(0);
        }
    });

    // Start Logo Scripts
    // Trigger file input when "Upload/Change Logo" button is clicked
    $('#aiols_upload_logo_button').on('click', function () {
        $('#aiols_login_logo').click();
    });

    $('#aiols_login_logo').on('click', function (e) {
        e.preventDefault();

        // If the uploader already exists, reopen it
        if (logoMediaUploader) {
            logoMediaUploader.open();
            return;
        }

        // Create the media uploader
        logoMediaUploader = wp.media({
            title: 'Select Logo',
            button: {
                text: 'Use this image'
            },
            multiple: false
        });

        // When an image is selected
        logoMediaUploader.on('select', function () {
            const attachment = logoMediaUploader.state().get('selection').first().toJSON();

            // Update preview
            $('#aiols_logo_preview').html('<p><img src="' + attachment.url + '" style="max-width:300px;margin-top:10px;"></p>');

            // Store image ID in a hidden input (optional)
            $('#aiols_login_logo').val(attachment.id);
        });

        logoMediaUploader.open();
    });

    // Handle "Remove Logo" button click
    $('#aiols_remove_logo_button').on('click', function () {
        if (confirm('Are you sure you want to remove the logo?')) {
            // Send an AJAX request to remove the logo
            $.post(ajaxurl, {
                action: 'aiols_remove_logo',
                _ajax_nonce: aiols_admin_vars.remove_logo_nonce // Pass the nonce from localized script
            }, function (response) {
                console.log(response);
                if (response.success) {
                    // Reload the page to reflect changes
                    location.reload();
                } else {
                    alert('Failed to remove logo.');
                }
            });
        }
    });
    // End Logo Scripts


    // Start Background Image Scripts
    // Trigger file input when "Upload/Change background image" button is clicked
    $('#aiols_upload_bg_img_button').on('click', function () {
        $('#aiols_login_bg_img').click();
    });

    // Handle file input change
    $('#aiols_login_bg_img').on('click', function (e) {
        e.preventDefault();

        // If the uploader already exists, reopen it
        if (bgMediaUploader) {
            bgMediaUploader.open();
            return;
        }

        // Create the media uploader
        bgMediaUploader = wp.media({
            title: 'Select Logo',
            button: {
                text: 'Use this image'
            },
            multiple: false
        });

        // When an image is selected
        bgMediaUploader.on('select', function () {
            const attachment = bgMediaUploader.state().get('selection').first().toJSON();

            // Update preview
            $('#aiols_bg_img_preview').html('<p><img src="' + attachment.url + '" style="max-width:300px;margin-top:10px;"></p>');

            // Store image ID in a hidden input (optional)
            $('#aiols_login_bg_img').val(attachment.id);
        });

        bgMediaUploader.open();
    });

    // Handle "Remove background image" button click
    $('#aiols_remove_bg_img_button').on('click', function () {
        if (confirm('Are you sure you want to remove the background image?')) {
            // Send an AJAX request to remove the background image
            $.post(ajaxurl, {
                action: 'aiols_remove_bg_img',
                _ajax_nonce: aiols_admin_vars.remove_bg_img_nonce // Pass the nonce from localized script
            }, function (response) {
                if (response.success) {
                    // Reload the page to reflect changes
                    location.reload();
                } else {
                    alert('Failed to remove background image.');
                }
            });
        }
    });
    // Start Background Image Scripts
});