<?php
// Prevent direct access to the file for security reasons.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class All_in_One_Login_Styler_Admin_Settings
 *
 * Handles the admin settings page for the "All in One Login Styler" plugin.
 * Responsible for adding the admin menu, registering plugin settings,
 * and rendering the settings form fields for customizing the WordPress login page.
 */
class All_in_One_Login_Styler_Admin_Settings
{
    /**
     * Constructor
     *
     * Hooks into WordPress admin actions to add the menu page and register settings.
     */
    public function __construct()
    {
        add_action('admin_menu', array($this, 'aiols_add_admin_page'));
        add_action('admin_init', array($this, 'aiols_register_settings'));
    }

    /**
     * Adds a top-level admin menu page for login customization.
     */
    public function aiols_add_admin_page()
    {
        add_menu_page(
            'Customize Login',                // Page title
            'Customize Login',                // Menu title
            'manage_options',                // Capability required to access
            'all-in-one-login-styler',       // Menu slug
            array($this, 'aiols_render_admin_page'), // Callback to render page content
            'dashicons-lock',                // Dashicon icon for the menu
            80                              // Position in menu order
        );
    }

    /**
     * Outputs the HTML for the admin settings page.
     * Displays setting errors and renders the settings form.
     */
    public function aiols_render_admin_page()
    {
?>
        <div class="wrap">
            <h1>Customize Login Page</h1>
            <?php settings_errors(); ?> <!-- Display any settings error messages -->
            <form method="post" action="options.php" enctype="multipart/form-data">
                <?php
                settings_fields('aiols_options_group');      // Security fields for the registered setting group
                do_settings_sections('all-in-one-login-styler'); // Output all sections and fields for this page
                submit_button();                          // Submit button for saving options
                ?>
            </form>
        </div>
    <?php
    }

    /**
     * Registers all plugin settings, sections, and fields.
     * Defines sanitization callbacks for each setting to ensure data integrity.
     */
    public function aiols_register_settings()
    {
        // Register each setting with proper sanitization callbacks
        register_setting('aiols_options_group', 'aiols_enable_customization', 'intval');
        register_setting('aiols_options_group', 'aiols_login_logo', 'intval');
        register_setting('aiols_options_group', 'aiols_login_bg_img', 'intval');
        register_setting('aiols_options_group', 'aiols_background_color', 'sanitize_hex_color');
        register_setting('aiols_options_group', 'aiols_button_color', 'sanitize_hex_color');
        register_setting('aiols_options_group', 'aiols_form_color', 'sanitize_hex_color');
        register_setting('aiols_options_group', 'aiols_fields_border_color', 'sanitize_hex_color');
        register_setting('aiols_options_group', 'aiols_form_radius', 'sanitize_text_field');
        register_setting('aiols_options_group', 'aiols_links_color', 'sanitize_hex_color');
        register_setting('aiols_options_group', 'aiols_form_width', 'sanitize_text_field');

        // Add main section to group settings on the page
        add_settings_section('aiols_main_section', 'Customization', null, 'all-in-one-login-styler');

        // Add individual fields to the settings page with callbacks to render inputs
        add_settings_field('aiols_enable_customization', 'Enable Customization', array($this, 'aiols_enable_customization_callback'), 'all-in-one-login-styler', 'aiols_main_section');
        add_settings_field('aiols_login_logo', 'Login Logo', array($this, 'aiols_login_logo_callback'), 'all-in-one-login-styler', 'aiols_main_section');
        add_settings_field('aiols_login_bg_img', 'Login Background Image', array($this, 'aiols_login_bg_img_callback'), 'all-in-one-login-styler', 'aiols_main_section');
        add_settings_field('aiols_background_color', 'Background Color', array($this, 'aiols_background_color_callback'), 'all-in-one-login-styler', 'aiols_main_section');
        add_settings_field('aiols_form_width', 'Form Width', array($this, 'aiols_form_width_callback'), 'all-in-one-login-styler', 'aiols_main_section');
        add_settings_field('aiols_button_color', 'Button Color', array($this, 'aiols_button_color_callback'), 'all-in-one-login-styler', 'aiols_main_section');
        add_settings_field('aiols_form_color', 'Form Background Color', array($this, 'aiols_form_color_callback'), 'all-in-one-login-styler', 'aiols_main_section');
        add_settings_field('aiols_fields_border_color', 'Fields Border Color', array($this, 'aiols_fields_border_color_callback'), 'all-in-one-login-styler', 'aiols_main_section');
        add_settings_field('aiols_form_radius', 'Form Radius', array($this, 'aiols_form_radius_callback'), 'all-in-one-login-styler', 'aiols_main_section');
        add_settings_field('aiols_links_color', 'Bottom Link Color', array($this, 'aiols_links_color_callback'), 'all-in-one-login-styler', 'aiols_main_section');
    }

    /**
     * Render callback for "Enable Customization" checkbox field.
     * Pre-selects the checkbox if customization is enabled.
     */
    public function aiols_enable_customization_callback()
    {
        $enable_customization = get_option('aiols_enable_customization', 1);
    ?>
        <input type="checkbox" name="aiols_enable_customization" id="aiols_enable_customization" class="aiols_checkbox" value="1" <?php checked(1, $enable_customization); ?>>
    <?php
    }

    /**
     * Render callback for the Login Logo upload section.
     * Displays upload/change buttons, remove button (if logo exists), and preview.
     */
    public function aiols_login_logo_callback()
    {
        $logo_id = get_option('aiols_login_logo', '');
    ?>
        <div class="aiols-logo-upload-container">
            <!-- Hidden input to store logo URL -->
            <input type="hidden" name="aiols_login_logo" id="aiols_login_logo" style="display: none;" value="<?php echo ($logo_id) ? esc_attr($logo_id) : '' ?>">

            <!-- Button to trigger upload/change -->
            <button type="button" id="aiols_upload_logo_button" class="button">
                <?php echo $logo_id ? 'Change Logo' : 'Upload Logo'; ?>
            </button>

            <!-- Remove logo button only shown if logo exists -->
            <?php if ($logo_id) : ?>
                <button type="button" id="aiols_remove_logo_button" class="button button-danger">Remove Logo</button>
            <?php endif; ?>

            <!-- Logo preview -->
            <div id="aiols_logo_preview">
                <?php if ($logo_id) :
                    echo wp_get_attachment_image((int) $logo_id, 'medium', false, ['style' => 'max-width:300px;margin-top:10px;']);
                else : ?>
                    <p>No logo uploaded.</p>
                <?php endif; ?>
            </div>
        </div>
    <?php
    }

    /**
     * Render callback for the Login Background Image upload section.
     * Displays upload/change buttons, remove button (if image exists), and preview.
     */
    public function aiols_login_bg_img_callback()
    {
        $bg_img_id = get_option('aiols_login_bg_img', '');
    ?>
        <div class="aiols-logo-upload-container">
            <!-- Hidden input to store background image URL -->
            <input type="hidden" name="aiols_login_bg_img" id="aiols_login_bg_img" style="display: none;" value="<?php echo ($bg_img_id) ? esc_attr($bg_img_id) : '' ?>">

            <!-- Button to trigger upload/change -->
            <button type="button" id="aiols_upload_bg_img_button" class="button">
                <?php echo $bg_img_id ? 'Change Background Image' : 'Upload Background Image'; ?>
            </button>

            <!-- Remove background image button only shown if image exists -->
            <?php if ($bg_img_id) : ?>
                <button type="button" id="aiols_remove_bg_img_button" class="button button-danger">Remove Background Image</button>
            <?php endif; ?>

            <!-- Background image preview -->
            <div id="aiols_bg_img_preview">
                <?php if ($bg_img_id) :
                    echo wp_get_attachment_image((int) $bg_img_id, 'medium', false, ['style' => 'max-width:300px;margin-top:10px;']);
                else : ?>
                    <p>No Background Image uploaded.</p>
                <?php endif; ?>
            </div>
        </div>
<?php
    }

    /**
     * Render callback for Background Color picker.
     */
    public function aiols_background_color_callback()
    {
        $color = get_option('aiols_background_color', '#ffffff');
        echo '<input type="color" name="aiols_background_color" value="' . esc_attr($color) . '">';
    }

    /**
     * Render callback for Button Color picker.
     */
    public function aiols_button_color_callback()
    {
        $color = get_option('aiols_button_color', '#2271b1');
        echo '<input type="color" name="aiols_button_color" value="' . esc_attr($color) . '">';
    }

    /**
     * Render callback for Form Background Color picker.
     */
    public function aiols_form_color_callback()
    {
        $color = get_option('aiols_form_color', '#ffffff');
        echo '<input type="color" name="aiols_form_color" value="' . esc_attr($color) . '">';
    }

    /**
     * Render callback for Fields Border Color picker.
     */
    public function aiols_fields_border_color_callback()
    {
        $color = get_option('aiols_fields_border_color', '#2271b1');
        echo '<input type="color" name="aiols_fields_border_color" value="' . esc_attr($color) . '">';
    }

    /**
     * Render callback for Form Radius input.
     */
    public function aiols_form_radius_callback()
    {
        $radius = get_option('aiols_form_radius', 0);
        echo '<input type="text" name="aiols_form_radius" value="' . esc_attr($radius) . '">';
    }

    /**
     * Render callback for Form Width input.
     */
    public function aiols_form_width_callback()
    {
        $width = get_option('aiols_form_width', 320);
        echo '<input type="text" name="aiols_form_width" value="' . esc_attr($width) . '">';
    }

    /**
     * Render callback for Bottom Link Color picker.
     */
    public function aiols_links_color_callback()
    {
        $color = get_option('aiols_links_color', '#50575e');
        echo '<input type="color" name="aiols_links_color" value="' . esc_attr($color) . '">';
    }
}
