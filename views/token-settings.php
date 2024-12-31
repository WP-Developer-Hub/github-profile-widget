<?php
// Prevent direct access to the file
if (!defined('ABSPATH')) {
    exit;
}

class GitHub_Profile_Settings {

    // Constructor: Hooks to WordPress actions
    public function __construct() {
        // Register the admin menu for GitHub Profile settings
        add_action('admin_menu', array($this, 'add_settings_menu'));

        // Register the settings for the GitHub Profile widget
        add_action('admin_init', array($this, 'register_settings'));
    }

    // Add the settings page to the WordPress admin menu
    public function add_settings_menu() {
        add_options_page(
            'GitHub Profile Settings',        // Page Title
            'GitHub Profile Settings',        // Menu Title
            'manage_options',                 // Capability
            'github-profile-settings',        // Menu Slug
            array($this, 'render_settings_page') // Callback function
        );
    }

    // Register the settings fields and sections
    public function register_settings() {
        // Register the setting for the GitHub token
        register_setting('github_profile_settings_group', 'github_token');

        // Add a settings section
        add_settings_section(
            'github_profile_settings_section',  // Section ID
            'GitHub Profile Settings',          // Section Title
            array($this, 'section_callback'),   // Callback function
            'github-profile-settings'           // Page slug
        );

        // Add the GitHub token field
        add_settings_field(
            'github_token_field',               // Field ID
            'GitHub Token',                     // Field Title
            array($this, 'github_token_field_callback'), // Callback function
            'github-profile-settings',          // Page slug
            'github_profile_settings_section'   // Section ID
        );
    }

    // Section description callback
    public function section_callback() {
        echo '<p>Enter your GitHub personal access token below to authenticate API requests and display your GitHub profile data on the site.</p>';
    }

    // GitHub token field callback
    public function github_token_field_callback() {
        // Get the current value of the GitHub token
        $github_token = get_option('github_token');
        ?>
        <input type="text" name="github_token" value="<?php echo esc_attr($github_token); ?>" class="regular-text" />
        <p class="description">Enter the GitHub personal access token here. This will be used to authenticate requests to GitHub API.</p>
        <?php
    }

    // Render the settings page
    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1>GitHub Profile Settings</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('github_profile_settings_group');  // Output settings fields
                do_settings_sections('github-profile-settings');   // Output settings sections
                submit_button();  // Output the save button
                ?>
            </form>
        </div>
        <?php
    }
}

// Instantiate the class to register and display the settings page
if (is_admin()) {
    new GitHub_Profile_Settings();
}
