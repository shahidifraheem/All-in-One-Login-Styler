<?php
if (!defined('ABSPATH')) {
    exit;
}

class Customize_Login_Customizer
{
    public function __construct()
    {
        add_action('login_enqueue_scripts', array($this, 'customize_login_page'));
    }

    public function customize_login_page()
    {
        // Check if customization is enabled
        $enable_customization = get_option('cl_enable_customization', false);
        if (!$enable_customization) {
            return; // Exit if customization is disabled
        }

        $logo = get_option('cl_login_logo', 'No logo uploaded yet.');
        $bg_img = get_option('cl_login_bg_img', 'No Background Image uploaded yet.');
        $background_color = get_option('cl_background_color', '#ffffff');
        $button_color = get_option('cl_button_color', '#2271b1');
        $form_color = get_option('cl_form_color', '#ffffff');
        $fields_border_color = get_option('cl_fields_border_color', '#2271b1');
        $form_radius = get_option('cl_form_radius', 0);
        $links_color = get_option('cl_links_color', '#50575e');
        $form_width = get_option('cl_form_width', 320);
?>
        <style type="text/css">
            #login {
                width: <?php echo esc_attr($form_width); ?>px !important;
            }

            body.login {
                background-color: <?php echo esc_attr($background_color); ?> !important;
                <?php if ($bg_img) : ?>background: url(<?php echo esc_url($bg_img); ?>) center no-repeat !important;
                background-size: cover !important;
                <?php endif; ?>
            }

            .login form {
                background-color: <?php echo esc_attr($form_color); ?> !important;
            }

            a:focus {
                box-shadow: none !important;
            }

            <?php if ($logo) : ?>.login h1 a {
                background-image: url(<?php echo esc_url($logo); ?>) !important;
                background-size: contain !important;
                width: 100% !important;
                height: 100px !important;
            }

            <?php endif; ?><?php if ($bg_img) : ?>body.login {
                background: url(<?php echo esc_url($bg_img); ?>) center no-repeat !important;
                background-size: cover !important;
            }

            <?php endif; ?>#wp-submit {
                background-color: <?php echo esc_attr($button_color); ?> !important;
                border-color: <?php echo esc_attr($button_color); ?> !important;
            }

            .wp-core-ui .button:not(#wp-submit) {
                color: <?php echo esc_attr($button_color); ?> !important;
            }

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

            #nav {
                text-align: center;
            }

            #nav a {
                color: <?php echo esc_attr($links_color); ?> !important;
                text-decoration: underline !important;
            }

            input[type=checkbox]:checked::before {
                filter: saturate(0%);
            }


            #backtoblog a {
                visibility: hidden;
            }
        </style>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                document.querySelector("#backtoblog").remove();
                const link = document.querySelector(".wp-login-logo a");
                if (link) {
                    link.href = "<?= home_url() ?>";
                }
            });
        </script>

<?php
    }
}
