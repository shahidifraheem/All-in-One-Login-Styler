<?php

/**
 * AIOLS Login Customizer
 * 
 * Handles customization of the WordPress login page including styles,
 * scripts, and dynamic CSS generation based on user settings.
 * 
 * @package All_in_One_Login_Styler
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Main class for customizing the WordPress login page
 */
class All_in_One_Login_Styler
{
    /**
     * Class constructor
     * 
     * Initializes the login customizer by hooking into WordPress actions
     */
    public function __construct()
    {
        // Hook into login page to enqueue custom assets
        add_action('login_enqueue_scripts', array($this, 'enqueue_login_assets'));
    }

    /**
     * Enqueues all necessary login page assets (CSS/JS)
     * 
     * Only loads assets if customization is enabled in settings
     * 
     * @hook login_enqueue_scripts
     * @return void
     */
    public function enqueue_login_assets()
    {
        // Bail if customization is disabled in settings
        if (!get_option('aiols_enable_customization', false)) {
            return;
        }

        // Register empty style just to attach our dynamic CSS
        wp_register_style(
            'aiols-login',
            false, // No actual file
            array() // No dependencies
        );
        wp_enqueue_style('aiols-login');

        wp_add_inline_script('jquery', '
            jQuery(document).ready(function($) {
                $("#backtoblog").remove();
                $(".wp-login-logo a").attr("href", "' . home_url() . '");
            });
        ');

        // Generate and add dynamic CSS based on settings
        $this->generate_dynamic_css();
    }

    /**
     * Generates dynamic CSS based on plugin settings
     * 
     * Compiles CSS variables and custom rules from stored options
     * and adds them as inline styles to the login page
     * 
     * @access private
     * @return void
     */
    private function generate_dynamic_css()
    {
        // Get all customization options with defaults
        $options = array(
            'form_width' => get_option('aiols_form_width', 320),
            'background_color' => get_option('aiols_background_color', '#ffffff'),
            'button_color' => get_option('aiols_button_color', '#2271b1'),
            'form_color' => get_option('aiols_form_color', '#ffffff'),
            'fields_border_color' => get_option('aiols_fields_border_color', '#2271b1'),
            'form_radius' => get_option('aiols_form_radius', 0),
            'links_color' => get_option('aiols_links_color', '#50575e'),
            'logo_url' => wp_get_attachment_url(get_option('aiols_login_logo', '')),
            'bg_img_url' => wp_get_attachment_url(get_option('aiols_login_bg_img', ''))
        );

        // Base CSS variables for all customizations
        $css = ":root {
            --aiols-form-width: {$options['form_width']}px;
            --aiols-bg-color: {$options['background_color']};
            --aiols-button-color: {$options['button_color']};
            --aiols-form-color: {$options['form_color']};
            --aiols-border-color: {$options['fields_border_color']};
            --aiols-form-radius: {$options['form_radius']}px;
            --aiols-links-color: {$options['links_color']};
        }";

        // Add custom logo CSS if logo URL exists
        if ($options['logo_url']) {
            $css .= ".login h1 a {
                background-image: url('{$options['logo_url']}') !important;
                background-size: contain !important;
                width: 100% !important;
                height: 100px !important;
            }";
        }

        // Add background image CSS if BG image URL exists
        if ($options['bg_img_url']) {
            $css .= "body.login {
                background: {$options['background_color']} url('{$options['bg_img_url']}') center/cover no-repeat !important;
            }";
        }

        if (!$options['bg_img_url']) {
            $css .= "body.login {
                background-color: {$options['background_color']} !important;
            }";
        }

        // Add custom form styling
        $css .= "
            #login {
                width: {$options['form_width']}px !important;
            }

            .login form {
                background-color: {$options['form_color']} !important;
                border-radius: {$options['form_radius']}px !important;
            }

            #wp-submit {
                background-color: {$options['button_color']} !important;
                border-color: {$options['button_color']} !important;
            }

            .wp-core-ui .button:not(#wp-submit) {
                color: {$options['button_color']} !important;
            }

            .login .message,
            .login .notice,
            .login .success {
                border-color: {$options['button_color']} !important;
            }

            .login .message a,
            .login .notice a,
            .login .success a {
                color: {$options['button_color']} !important;
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
                border-color: {$options['fields_border_color']} !important;
                box-shadow: 0 0 0 1px {$options['fields_border_color']} !important;
            }

            #nav a {
                color: {$options['links_color']} !important;
            }

            #nav {
                text-align: center;
            }

            #nav a {
                text-decoration: underline !important;
            }

            input[type='checkbox']:checked::before {
                filter: saturate(0%);
            }

            #backtoblog a {
                visibility: hidden;
            }

            a:focus {
                box-shadow: none !important;
            }

        ";

        // Add the compiled CSS as inline style
        wp_add_inline_style('aiols-login', $css);
    }
}
