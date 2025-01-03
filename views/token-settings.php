<?php
/*
 * Github Profile Widget for WordPress
 *
 *     Copyright (C) 20204 DJABHipHop <djabhiphop-DJABHipHop@yahoo.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

// Prevent direct file access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class GitHub_Profile_widgat_API_Settings {

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
            __('GitHub PW API Settings', 'github_profile_widget'), // Page Title
            __('GitHub PW API Settings', 'github_profile_widget'), // Menu Title
            'manage_options', // Capability
            'github-pw-api-settings', // Menu Slug
            array($this, 'render_settings_page') // Callback function
        );
    }

    // Register the settings fields and sections
    public function register_settings() {
        // Register the setting for the GitHub token
        register_setting('github_pw_api_settings_group', 'github_pw_api_token');
        register_setting('github_pw_api_settings_group', 'github_pw_cache'); // Register the cache setting

        // Add a settings section
        add_settings_section(
            'github_pw_api_settings_section', // Section ID
            __('GitHub Profile Settings', 'github_profile_widget'), // Section Title
            array($this, 'section_callback'), // Callback function
            'github-pw-api-settings' // Page slug
        );

        // Add the GitHub token field
        add_settings_field(
            'github_pw_api_token_field', // Field ID
            __('GitHub API Token', 'github_profile_widget'), // Field Title
            array($this, 'github_pw_api_token_field_callback'), // Callback function
            'github-pw-api-settings', // Page slug
            'github_pw_api_settings_section' // Section ID
        );

        // Add the Cache duration field
        add_settings_field(
            'github_pw_cache_field', // Field ID
            __('Cache Duration (minutes)', 'github_profile_widget'), // Field Title
            array($this, 'github_pw_cache_field_callback'), // Callback function
            'github-pw-api-settings', // Page slug
            'github_pw_api_settings_section' // Section ID
        );
    }

    // Section description callback
    public function section_callback() {
        echo '<p>' . __('Enter your GitHub personal access token below to authenticate API requests and display your GitHub profile data on the site. You can also set the cache duration for API responses.', 'github_profile_widget') . '</p>';
    }

    // GitHub token field callback
    public function github_pw_api_token_field_callback() {
        // Get the current value of the GitHub token
        $github_pw_api_token = get_option('github_pw_api_token');
        ?>
        <input type="text" name="github_pw_api_token" value="<?php echo esc_attr($github_pw_api_token); ?>" class="regular-text" placeholder="<?php echo esc_attr(__('Enter the GitHub personal access token here.', 'github_profile_widget')); ?>"/>
        <p class="description">
            <?php
            echo sprintf(
                __('If you don\'t have a GitHub token, you can create one by visiting the <a href="%s" target="_blank" rel="noopener noreferrer">GitHub Personal Access Tokens page</a>.', 'github_profile_widget'),
                'https://github.com/settings/personal-access-tokens'
            );
            ?>
        </p>
        <?php
    }

    // Cache duration field callback
    public function github_pw_cache_field_callback() {
        // Get the current value of the cache duration
        $github_pw_cache = get_option('github_pw_cache', 60); // Default to 60 minutes if not set
        ?>
        <input type="number" name="github_pw_cache" value="<?php echo esc_attr($github_pw_cache); ?>" class="small-text" min="1" inputmode="numeric" pattern="\d*" />
        <p class="description"><?php echo __('Set the cache duration in minutes for GitHub profile data.', 'github_profile_widget'); ?></p>
        <?php
    }

    // Render the settings page
    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(__('GitHub Profile Settings', 'github_profile_widget')); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('github_pw_api_settings_group'); // Output settings fields
                do_settings_sections('github-pw-api-settings'); // Output settings sections
                submit_button();  // Output the save button
                ?>
            </form>
        </div>
        <?php
    }
}

// Instantiate the class to register and display the settings page
if (is_admin()) {
    new GitHub_Profile_widgat_API_Settings();
}
