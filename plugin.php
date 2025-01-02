<?php

/**
 * Plugin Name: GitHub Profile Widget
 * Description: This is a plugin that shows your GitHub profile with a simple widget.
 * Version: 3.1.0
 * Author: Henrique Dias and Luís Soares (Refactors) and DJABHipHop
 * Author URI: https://github.com/refactors
 * Requires PHP: 7.2
 * Requires at least: 6.0
 * License: GPL2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: github_profile_widget
 * Domain Path: /languages
 */

// Prevent direct file access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once( 'lib/htmlcompressor.php' );
require_once( 'views/token-settings.php' );

class GitHub_Profile extends WP_Widget {

    const API_PATH = "https://api.github.com";
    const API_VERSION = "2022-11-28";

    protected $widget_slug = 'github-profile';
    protected $checkboxes = array(
        "github_wp_toggle_header",
        "github_wp_toggle_avatar_and_name",
        "github_wp_toggle_meta_info",
        "github_wp_toggle_followers_and_following",
        "github_wp_toggle_organizations",
        "github_wp_toggle_dark_theme"
    );

    public function __construct() {
        parent::__construct(
            $this->widget_slug, 'GitHub Profile', $this->widget_slug, array(
                'classname' => $this->widget_slug . '-class',
                'description' => 'A widget to show a small version of your GitHub profile',
                $this->widget_slug
            )
        );
        add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_styles' ) );
    }

    public function form( $config ) {
        if (empty(get_option('github_pw_api_token'))) {
            echo '<p>' . sprintf(__('Go to Settings to configure the GitHub API token. %s', 'github_profile_widget'), '<a href="admin.php?page=github-wp-api-settings">' . __('Go to Settings', 'github_profile_widget') . '</a>') . '</p>';
        } else {

            $default = array(
                "github_wp_toggle_org" => "none",
                "github_wp_toggle_avatar_and_name" => "on",
                "github_wp_toggle_followers_following" => "on",
                "github_wp_toggle_meta_info" => "on",
                "github_wp_toggle_organizations" => "on",
                "github_wp_toggle_cache" => "50",
                "github_wp_toggle_dark_theme" => "off"
            );

            $config = ! isset( $config['first_time'] ) ? $default : $config;

            foreach ( $config as $key => $value ) {
                ${$key} = esc_attr( $value );
            }

            $orgs = $this->get_github_api_content( "orgs", $config );

            ob_start( "refactors_HTMLCompressor" );
            require 'views/options.php';
            ob_end_flush();
        }
    }

    public function update( $new_instance, $old_instance ) {
        $new_instance['first_time'] = false;
        return $new_instance;
    }

    public function widget( $args, $config ) {
        if (empty(get_option('github_pw_api_token'))) {
            return;
        }

        $profile = $this->get_github_api_content( '', $config );

        if ( ! $profile ) {
            return;
        }

        $profile->created_at = new DateTime( $profile->created_at );
        $profile->events_url = str_replace( '{/privacy}', '', $profile->events_url );

        if ( $this->is_checked( $config, 'github_wp_toggle_organizations' ) ) {
            if ( $profile->type == 'User' ) {
                $orgs = $this->get_github_api_content( "orgs", $config );
            }
        }

        extract( $args, EXTR_SKIP );
        ob_start( "refactors_HTMLCompressor" );
        $this->load_theme();
        require 'views/widget.php';
        ob_end_flush();
    }

    private function get_github_api_content($apiPath, $config) {
        // Safely check for the 'github_wp_org' index
        $github_wp_org = isset($config['github_wp_org']) ? sanitize_text_field($config['github_wp_org']) : '';

        // Derive API paths based on whether org is provided or not
        if (empty($apiPath)) {
            // If no sub-path is given, use org or user
            if (empty($github_wp_org) || $github_wp_org === 'none') {
                $apiPath = self::API_PATH . '/user';
            } else {
                $apiPath = self::API_PATH . '/orgs/' . $github_wp_org;
            }
        } else {
            $apiPath = self::API_PATH . '/user/' . $apiPath;
        }

        // Prepare headers for the request
        $headers = [
            'Accept' => 'application/vnd.github+json',
            'Authorization' => 'Bearer ' . get_option('github_pw_api_token'),
            'X-GitHub-Api-Version' => self::API_VERSION,
        ];

        // Make the request using wp_remote_get
        $response = $this->make_github_request($apiPath, $headers);

        if ($response['httpCode'] === 404) {
            error_log("GitHub API: User or organization '$github_wp_org' not found.");
            return __('User or organization not found. Please check the username.', 'github_profile_widget');
        }

        // Handle other HTTP errors
        if ($response['httpCode'] !== 200) {
            error_log("GitHub API Error: HTTP {$response['httpCode']}. Response: {$response['body']}");
            return __('An error occurred while fetching the GitHub data. Please try again later.', 'github_profile_widget');
        }

        // Decode and return the response for successful requests
        return json_decode($response['body']);
    }

    private function make_github_request($url, $headers) {
        if (empty($url)) {
            return ['httpCode' => 404, 'body' => ''];
        }

        // Using wp_remote_get to send the request
        $response = wp_remote_get($url, [
            'headers' => $headers,
            'timeout' => 15, // Optional timeout setting
        ]);

        // Check for errors
        if (is_wp_error($response)) {
            return [
                'httpCode' => 500,
                'body' => $response->get_error_message(),
            ];
        }

        // Get the HTTP status code and body from the response
        $httpCode = wp_remote_retrieve_response_code($response);
        $body = wp_remote_retrieve_body($response);

        return [
            'httpCode' => $httpCode,
            'body' => $body,
        ];
    }

    public function is_checked( $conf, $name ) {
        return isset( $conf[ $name ] ) && $conf[ $name ] == 'on';
    }

    public function register_widget_styles() {
        wp_enqueue_style( $this->widget_slug . '-octicons', plugins_url( 'css/octicons/octicons.min.css', __FILE__ ) );
    }

    public function load_theme() {
        wp_enqueue_style( $this->widget_slug . '-widget-styles', plugins_url( "css/widget.css", __FILE__ ) );
    }
}

add_action( 'widgets_init', function() {
    register_widget( 'GitHub_Profile' );
} );

