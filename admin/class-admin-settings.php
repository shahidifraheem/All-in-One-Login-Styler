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
        add_action('admin_menu', array($this, 'add_admin_page'));
        add_action('admin_init', array($this, 'register_settings'));
    }

    /**
     * Adds a top-level admin menu page for login customization.
     */
    public function add_admin_page()
    {
        add_menu_page(
            'Customize Login',                // Page title
            'Customize Login',                // Menu title
            'manage_options',                // Capability required to access
            'all-in-one-login-styler',       // Menu slug
            array($this, 'render_admin_page'), // Callback to render page content
            'dashicons-lock',                // Dashicon icon for the menu
            80                              // Position in menu order
        );
    }

    /**
     * Outputs the HTML for the admin settings page.
     * Displays setting errors and renders the settings form.
     */
    public function render_admin_page()
    {
?>
        <div class="wrap">
            <h1>Customize Login Page</h1>
            <?php settings_errors(); ?> <!-- Display any settings error messages -->
            <form method="post" action="options.php" enctype="multipart/form-data">
                <?php
                settings_fields('cl_options_group');      // Security fields for the registered setting group
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
    public function register_settings()
    {
        // Register each setting with proper sanitization callbacks
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

        // Add main section to group settings on the page
        add_settings_section('cl_main_section', 'Customization', null, 'all-in-one-login-styler');

        // Add individual fields to the settings page with callbacks to render inputs
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

    /**
     * Render callback for "Enable Customization" checkbox field.
     * Pre-selects the checkbox if customization is enabled.
     */
    public function enable_customization_callback()
    {
        $enable_customization = get_option('cl_enable_customization', 1);
    ?>
        <input type="checkbox" name="cl_enable_customization" id="cl_enable_customization" class="cl_checkbox" value="1" <?php checked(1, $enable_customization); ?>>
    <?php
    }

    /**
     * Render callback for the Login Logo upload section.
     * Displays upload/change buttons, remove button (if logo exists), and preview.
     */
    public function login_logo_callback()
    {
        $logo = get_option('cl_login_logo', '');
    ?>
        <div class="cl-logo-upload-container">
            <!-- Hidden input to store logo URL -->
            <input type="hidden" name="cl_login_logo" id="cl_login_logo" style="display: none;">

            <!-- Button to trigger upload/change -->
            <button type="button" id="cl_upload_logo_button" class="button">
                <?php echo $logo ? 'Change Logo' : 'Upload Logo'; ?>
            </button>

            <!-- Remove logo button only shown if logo exists -->
            <?php if ($logo) : ?>
                <button type="button" id="cl_remove_logo_button" class="button button-danger">Remove Logo</button>
            <?php endif; ?>

            <!-- Logo preview -->
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

    /**
     * Render callback for the Login Background Image upload section.
     * Displays upload/change buttons, remove button (if image exists), and preview.
     */
    public function login_bg_img_callback()
    {
        $bg_img = get_option('cl_login_bg_img', '');
    ?>
        <div class="cl-logo-upload-container">
            <!-- Hidden input to store background image URL -->
            <input type="hidden" name="cl_login_bg_img" id="cl_login_bg_img" style="display: none;">

            <!-- Button to trigger upload/change -->
            <button type="button" id="cl_upload_bg_img_button" class="button">
                <?php echo $bg_img ? 'Change Background Image' : 'Upload Background Image'; ?>
            </button>

            <!-- Remove background image button only shown if image exists -->
            <?php if ($bg_img) : ?>
                <button type="button" id="cl_remove_bg_img_button" class="button button-danger">Remove Background Image</button>
            <?php endif; ?>

            <!-- Background image preview -->
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

    /**
     * Render callback for Background Color picker.
     */
    public function background_color_callback()
    {
        $color = get_option('cl_background_color', '#ffffff');
        echo '<input type="color" name="cl_background_color" value="' . esc_attr($color) . '">';
    }

    /**
     * Render callback for Button Color picker.
     */
    public function button_color_callback()
    {
        $color = get_option('cl_button_color', '#2271b1');
        echo '<input type="color" name="cl_button_color" value="' . esc_attr($color) . '">';
    }

    /**
     * Render callback for Form Background Color picker.
     */
    public function form_color_callback()
    {
        $color = get_option('cl_form_color', '#ffffff');
        echo '<input type="color" name="cl_form_color" value="' . esc_attr($color) . '">';
    }

    /**
     * Render callback for Fields Border Color picker.
     */
    public function fields_border_color_callback()
    {
        $color = get_option('cl_fields_border_color', '#2271b1');
        echo '<input type="color" name="cl_fields_border_color" value="' . esc_attr($color) . '">';
    }

    /**
     * Render callback for Form Radius input.
     */
    public function form_radius_callback()
    {
        $radius = get_option('cl_form_radius', 0);
        echo '<input type="text" name="cl_form_radius" value="' . esc_attr($radius) . '">';
    }

    /**
     * Render callback for Form Width input.
     */
    public function form_width_callback()
    {
        $width = get_option('cl_form_width', 320);
        echo '<input type="text" name="cl_form_width" value="' . esc_attr($width) . '">';
    }

    /**
     * Render callback for Bottom Link Color picker.
     */
    public function links_color_callback()
    {
        $color = get_option('cl_links_color', '#50575e');
        echo '<input type="color" name="cl_links_color" value="' . esc_attr($color) . '">';
    }
}
