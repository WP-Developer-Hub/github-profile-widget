<?php

/**
 * Plugin Name: GitHub Profile Widget
 * Description: This is a plugin that shows your GitHub profile with a simple widget.
 * Version: 1.1.1
 * Author: Henrique Dias and Luís Soares (Refactors)
 * Author URI: https://github.com/refactors
 * License: GPL2 or later
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
        "avatar_and_name",
        "meta_info",
        "followers_and_following",
        "repositories",
        "organizations",
        "dark_theme"
    );

    public function __construct() {
        parent::__construct(
            $this->widget_slug, 'GitHub Profile', $this->widget_slug, array(
                'classname'   => $this->widget_slug . '-class',
                'description' => 'A widget to show a small version of your GitHub profile',
                $this->widget_slug
            )
        );
        add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_styles' ) );
    }

    public function form( $config ) {
        $default = array(
            "avatar_and_name"     => "on",
            "meta_info"           => "on",
            "followers_following" => "on",
            "organizations"       => "on",
            "cache"               => "50",
            "dark_theme"          => "off"
        );

        $config = ! isset( $config['first_time'] ) ? $default : $config;

        foreach ( $config as $key => $value ) {
            ${$key} = esc_attr( $value );
        }

        ob_start( "refactors_HTMLCompressor" );
        require 'views/options.php';
        ob_end_flush();
    }

    public function update( $new_instance, $old_instance ) {
        $new_instance['first_time'] = false;
        return $new_instance;
    }

    public function widget( $args, $config ) {
        if (empty(get_option('github_token'))) {
            return;
        }

        $profile = $this->get_github_api_content( '', $config );

        if ( ! $profile ) {
            return;
        }

        $profile->created_at = new DateTime( $profile->created_at );
        $profile->events_url = str_replace( '{/privacy}', '', $profile->events_url );

        $optionsToUrls = array(
            'repositories' => $profile->{'repos_url'},
            'organizations' => empty($username) ? 'orgs' : $profile->{'organizations_url'},
            'feed' => $profile->{'events_url'}
        );

        foreach ( $optionsToUrls as $option => $url ) {
            if ( $this->is_checked( $config, $option ) ) {
                if (! empty( $url ) ) {
                	${$option} = $this->get_github_api_content( $url, $config );
                }
            }
        }

        extract( $args, EXTR_SKIP );
        ob_start( "refactors_HTMLCompressor" );
        $this->load_theme();
        require 'views/widget.php';
        ob_end_flush();
    }

    private function get_github_api_content($apiPath, $config) {
        $username = sanitize_text_field($config['username']); // Sanitize the username input

        // Derive API paths based on the username and apiPath
        if (empty($apiPath)) {
            if (!empty($username)) {
                $userApiPath = self::API_PATH . '/users/' . $username;
                $orgApiPath = self::API_PATH . '/orgs/' . $username;
            } else {
                $userApiPath = self::API_PATH . '/user';
                $orgApiPath = ''; // No organization path for authenticated user
            }
        } else {
            if (!empty($username)) {
                $userApiPath = self::API_PATH . '/users/' . $username . '/' . $apiPath;
                $orgApiPath = ''; // No organization lookup for sub-paths
            } else {
                $userApiPath = self::API_PATH . '/user/' . $apiPath;
                $orgApiPath = ''; // No organization path for sub-paths
            }
        }

        // Prepare headers for the request
        $headers = [
            'Accept' => 'application/vnd.github+json',
            'Authorization' => 'Bearer ' . get_option('github_token'),
            'X-GitHub-Api-Version' => self::API_VERSION,
        ];

        // Make the request using wp_remote_get
        $response = $this->make_github_request($userApiPath, $headers);

        // Check HTTP status and handle fallback to organization if needed
        if ($response['httpCode'] === 404 && !empty($orgApiPath)) {
            $response = $this->make_github_request($orgApiPath, $headers);

            if ($response['httpCode'] === 404) {
                error_log("GitHub API: User or organization '$username' not found.");
                return 'User or organization not found. Please check the username.';
            }
        }

        // Handle other HTTP errors
        if ($response['httpCode'] !== 200) {
            error_log("GitHub API Error: HTTP {$response['httpCode']}. Response: {$response['body']}");
            return "An error occurred while fetching the GitHub data. Please try again later.";
        }

        // Decode and return the response for successful requests
        return json_decode($response['body']);
    }

    // Helper function for making the GitHub request using wp_remote_get
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
        wp_enqueue_style( $this->widget_slug . '-octicons', plugins_url( 'css/octicons/octicons.css', __FILE__ ) );
    }

    public function register_admin_scripts() {
        wp_enqueue_script( $this->widget_slug . '-admin-script', plugins_url( 'js/admin.js', __FILE__ ), array( 'jquery' ) );
    }

    public function load_theme() {
        wp_enqueue_style( $this->widget_slug . '-widget-styles', plugins_url( "css/widget.css", __FILE__ ) );
    }
}

add_action( 'widgets_init', function() {
    register_widget( 'GitHub_Profile' );
} );

