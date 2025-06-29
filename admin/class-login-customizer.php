<?php
// Prevent direct access to the file for security reasons.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class All_in_One_Login_Styler
 *
 * Handles applying custom styles and scripts to the WordPress login page.
 * It uses options saved in the database to customize elements such as logo,
 * background, form colors, button colors, border colors, form width, and more.
 */
class All_in_One_Login_Styler
{
    /**
     * Constructor hooks the styling method into the login page enqueue scripts action.
     */
    public function __construct()
    {
        add_action('login_enqueue_scripts', array($this, 'all_in_one_login_styler'));
    }

    /**
     * Outputs inline CSS and JavaScript to customize the login page based on
     * plugin settings saved as options in the database.
     *
     * Checks if customization is enabled and applies styles accordingly:
     * - Login form width and background
     * - Custom logo image
     * - Background image and color
     * - Button colors and form styling (radius, borders)
     * - Link colors and hiding the back to blog link
     * - Adjust login logo link to homepage URL
     */
    public function all_in_one_login_styler()
    {
        // Retrieve customization enable flag; exit if disabled
        $enable_customization = get_option('aiols_enable_customization', false);
        if (!$enable_customization) {
            return; // Customization not enabled, do nothing
        }

        // Get all customization options, providing sensible defaults
        $logo_id = get_option('aiols_login_logo', '');
        $bg_img_id = get_option('aiols_login_bg_img', '');
        $background_color = get_option('aiols_background_color', '#ffffff');
        $button_color = get_option('aiols_button_color', '#2271b1');
        $form_color = get_option('aiols_form_color', '#ffffff');
        $fields_border_color = get_option('aiols_fields_border_color', '#2271b1');
        $form_radius = get_option('aiols_form_radius', 0);
        $links_color = get_option('aiols_links_color', '#50575e');
        $form_width = get_option('aiols_form_width', 320);
?>
        <style type="text/css">
            /* Customize login form width */
            #login {
                width: <?php echo esc_attr($form_width); ?>px !important;
            }

            /* Customize body background color and optionally background image */
            body.login {
                background-color: <?php echo esc_attr($background_color); ?> !important;
                <?php if ($bg_img_id) : ?>background: url(<?php echo esc_url(wp_get_attachment_url($bg_img_id)); ?>) center no-repeat !important;
                background-size: cover !important;
                <?php endif; ?>
            }

            /* Style the login form background color and border radius */
            .login form {
                background-color: <?php echo esc_attr($form_color); ?> !important;
                border-radius: <?php echo esc_attr($form_radius); ?>px !important;
            }

            /* Remove focus box-shadow from all links */
            a:focus {
                box-shadow: none !important;
            }

            /* Customize the login logo background image and sizing if a logo is set */
            <?php if ($logo_id) : ?>.login h1 a {
                background-image: url(<?php echo esc_url(wp_get_attachment_url($logo_id)); ?>) !important;
                background-size: contain !important;
                width: 100% !important;
                height: 100px !important;
            }

            <?php endif; ?>

            /* Background image is repeated here to ensure it applies properly */
            <?php if ($bg_img) : ?>body.login {
                background: url(<?php echo esc_url($bg_img); ?>) center no-repeat !important;
                background-size: cover !important;
            }

            <?php endif; ?>

            /* Style the login button colors */
            #wp-submit {
                background-color: <?php echo esc_attr($button_color); ?> !important;
                border-color: <?php echo esc_attr($button_color); ?> !important;
            }

            /* Style other buttons with the same color */
            .wp-core-ui .button:not(#wp-submit) {
                color: <?php echo esc_attr($button_color); ?> !important;
            }

            /* Style messages and notices border colors */
            .login .message,
            .login .notice,
            .login .success {
                border-color: <?php echo esc_attr($button_color); ?> !important;
            }

            /* Style links inside messages and notices */
            .login .message a,
            .login .notice a,
            .login .success a {
                color: <?php echo esc_attr($button_color); ?> !important;
            }

            /* Style focus state of various input fields */
            input[type=checkbox]:focus,
            input[type=color]:focus,
            input[type=date]:focus,
            input[type=datetime-local]:focus,
            input[type=datetime]:focus,
            input[type=email]:focus,
            input[type=month]:focus,
            input[type=number]:focus,
            input[type=password]:focus,
            input[type=radio]:focus,
            input[type=search]:focus,
            input[type=tel]:focus,
            input[type=text]:focus,
            input[type=time]:focus,
            input[type=url]:focus,
            input[type=week]:focus,
            select:focus,
            textarea:focus {
                border-color: <?php echo esc_attr($fields_border_color); ?> !important;
                box-shadow: 0 0 0 1px <?php echo esc_attr($fields_border_color); ?> !important;
            }

            /* Center the navigation links below the login form */
            #nav {
                text-align: center;
            }

            /* Style navigation links */
            #nav a {
                color: <?php echo esc_attr($links_color); ?> !important;
                text-decoration: underline !important;
            }

            /* Style checked checkboxes (grayscale effect) */
            input[type=checkbox]:checked::before {
                filter: saturate(0%);
            }

            /* Hide the "Back to blog" link */
            #backtoblog a {
                visibility: hidden;
            }
        </style>

        <script>
            // DOMContentLoaded ensures the script runs after the page is fully loaded
            document.addEventListener("DOMContentLoaded", function() {
                // Remove the #backtoblog element completely from DOM
                const backToBlog = document.querySelector("#backtoblog");
                if (backToBlog) {
                    backToBlog.remove();
                }

                // Change the login logo link URL to the site's home URL
                const loginLogoLink = document.querySelector(".wp-login-logo a");
                if (loginLogoLink) {
                    loginLogoLink.href = "<?php echo esc_attr(home_url()); ?>";
                }
            });
        </script>
<?php
    }
}
