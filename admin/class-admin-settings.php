<?php
if (!defined('ABSPATH')) {
    exit;
}

class All_in_One_Login_Styler_Admin_Settings
{
    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_admin_page'));
        add_action('admin_init', array($this, 'register_settings'));
    }

    public function add_admin_page()
    {
        add_menu_page(
            'Customize Login', // Page title
            'Customize Login', // Menu title
            'manage_options',   // Capability
            'all-in-one-login-styler', // Menu slug
            array($this, 'render_admin_page'), // Callback function
            'dashicons-lock',  // Icon URL
            80                  // Position
        );
    }

    public function render_admin_page()
    {
?>
        <div class="wrap">
            <h1>Customize Login Page</h1>
            <?php settings_errors(); ?> <!-- Display errors here -->
            <form method="post" action="options.php" enctype="multipart/form-data">
                <?php
                settings_fields('cl_options_group');
                do_settings_sections('all-in-one-login-styler');
                submit_button();
                ?>
            </form>
        </div>
    <?php
    }

    public function register_settings()
    {
        register_setting('cl_options_group', 'cl_enable_customization', 'intval');
        register_setting('cl_options_group', 'cl_login_logo', 'cl_sanitize_logo_url');
        register_setting('cl_options_group', 'cl_login_bg_img', 'cl_sanitize_bg_img_url');
        register_setting('cl_options_group', 'cl_background_color', 'sanitize_hex_color');
        register_setting('cl_options_group', 'cl_button_color', 'sanitize_hex_color');
        register_setting('cl_options_group', 'cl_form_color', 'sanitize_hex_color');
        register_setting('cl_options_group', 'cl_fields_border_color', 'sanitize_hex_color');
        register_setting('cl_options_group', 'cl_form_radius', 'sanitize_text_field');
        register_setting('cl_options_group', 'cl_links_color', 'sanitize_hex_color');
        register_setting('cl_options_group', 'cl_form_width', 'sanitize_text_field');

        add_settings_section('cl_main_section', 'Customization', null, 'all-in-one-login-styler');

        add_settings_field('cl_enable_customization', 'Enable Customization', array($this, 'enable_customization_callback'), 'all-in-one-login-styler', 'cl_main_section');
        add_settings_field('cl_login_logo', 'Login Logo', array($this, 'login_logo_callback'), 'all-in-one-login-styler', 'cl_main_section');
        add_settings_field('cl_login_bg_img', 'Login Background Image', array($this, 'login_bg_img_callback'), 'all-in-one-login-styler', 'cl_main_section');
        add_settings_field('cl_background_color', 'Background Color', array($this, 'background_color_callback'), 'all-in-one-login-styler', 'cl_main_section');
        add_settings_field('cl_form_width', 'Form Width', array($this, 'form_width_callback'), 'all-in-one-login-styler', 'cl_main_section');
        add_settings_field('cl_button_color', 'Button Color', array($this, 'button_color_callback'), 'all-in-one-login-styler', 'cl_main_section');
        add_settings_field('cl_form_color', 'Form Background Color', array($this, 'form_color_callback'), 'all-in-one-login-styler', 'cl_main_section');
        add_settings_field('cl_fields_border_color', 'Fields Border Color', array($this, 'fields_border_color_callback'), 'all-in-one-login-styler', 'cl_main_section');
        add_settings_field('cl_form_radius', 'Form Radius', array($this, 'form_radius_callback'), 'all-in-one-login-styler', 'cl_main_section');
        add_settings_field('cl_links_color', 'Bottom Link Color', array($this, 'links_color_callback'), 'all-in-one-login-styler', 'cl_main_section');
    }

    public function enable_customization_callback()
    {
        $enable_customization = get_option('cl_enable_customization', 1);
        if ($enable_customization == 1) {
            echo '<input type="checkbox" name="cl_enable_customization" id="cl_enable_customization" class="cl_checkbox" value="' . esc_attr($enable_customization) . '" checked>';
        } else {
            echo '<input type="checkbox" name="cl_enable_customization" id="cl_enable_customization" class="cl_checkbox" value="' . esc_attr($enable_customization) . '">';
        }
    }

    public function login_logo_callback()
    {
        $logo = get_option('cl_login_logo', '');
    ?>
        <div class="cl-logo-upload-container">
            <!-- File Input (Hidden by Default) -->
            <input type="hidden" name="cl_login_logo" id="cl_login_logo" style="display: none;">

            <!-- Upload/Change Logo Button -->
            <button type="button" id="cl_upload_logo_button" class="button">
                <?php echo $logo ? 'Change Logo' : 'Upload Logo'; ?>
            </button>

            <!-- Remove Logo Button (Only Show if Logo Exists) -->
            <?php if ($logo) : ?>
                <button type="button" id="cl_remove_logo_button" class="button button-danger">
                    Remove Logo
                </button>
            <?php endif; ?>

            <!-- Logo Preview -->
            <div id="cl_logo_preview">
                <?php if ($logo) : ?>
                    <p><img src="<?php echo esc_url($logo); ?>" style="max-width: 300px; margin-top: 10px;"></p>
                <?php else : ?>
                    <p>No logo uploaded.</p>
                <?php endif; ?>
            </div>
        </div>
    <?php
    }

    public function login_bg_img_callback()
    {
        $bg_img = get_option('cl_login_bg_img', '');
    ?>
        <div class="cl-logo-upload-container">
            <!-- File Input (Hidden by Default) -->
            <input type="hidden" name="cl_login_bg_img" id="cl_login_bg_img" style="display: none;">

            <!-- Upload/Change Background Image Button -->
            <button type="button" id="cl_upload_bg_img_button" class="button">
                <?php echo $bg_img ? 'Change Background Image' : 'Upload Background Image'; ?>
            </button>

            <!-- Remove Background Image Button (Only Show if Background Image Exists) -->
            <?php if ($bg_img) : ?>
                <button type="button" id="cl_remove_bg_img_button" class="button button-danger">
                    Remove Background Image
                </button>
            <?php endif; ?>

            <!-- Background Image Preview -->
            <div id="cl_bg_img_preview">
                <?php if ($bg_img) : ?>
                    <p><img src="<?php echo esc_url($bg_img); ?>" style="max-width: 300px; margin-top: 10px;"></p>
                <?php else : ?>
                    <p>No Background Image uploaded.</p>
                <?php endif; ?>
            </div>
        </div>
<?php
    }

    public function background_color_callback()
    {
        $color = get_option('cl_background_color', '#ffffff');
        echo '<input type="color" name="cl_background_color" value="' . esc_attr($color) . '">';
    }

    public function button_color_callback()
    {
        $color = get_option('cl_button_color', '#2271b1');
        echo '<input type="color" name="cl_button_color" value="' . esc_attr($color) . '">';
    }

    public function form_color_callback()
    {
        $color = get_option('cl_form_color', '#ffffff');
        echo '<input type="color" name="cl_form_color" value="' . esc_attr($color) . '">';
    }

    public function fields_border_color_callback()
    {
        $color = get_option('cl_fields_border_color', '#2271b1');
        echo '<input type="color" name="cl_fields_border_color" value="' . esc_attr($color) . '">';
    }

    public function form_radius_callback()
    {
        $color = get_option('cl_form_radius', 0);
        echo '<input type="text" name="cl_form_radius" value="' . esc_attr($color) . '">';
    }

    public function form_width_callback()
    {
        $color = get_option('cl_form_width', 320);
        echo '<input type="text" name="cl_form_width" value="' . esc_attr($color) . '">';
    }

    public function links_color_callback()
    {
        $color = get_option('cl_links_color', '#50575e');
        echo '<input type="color" name="cl_links_color" value="' . esc_attr($color) . '">';
    }
}
