<?php

/**
 * Plugin Name: GitHub Profile Widget
 * Description: This is a plugin that shows your GitHub profile with a simple widget.
 * Version: 4.0.0
 * Author: Henrique Dias and Luís Soares (Refactors) and DJABHipHop
 * Author URI: https://github.com/WP-Developer-Hub
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

define('GH_PW_PLUGIN_ORG', 'WP-Developer-Hub');
define('GH_PW_PLUGIN_SLUG', 'github-profile-widget');
define('GH_PW_API_PATH', 'https://api.github.com');
define('GH_PW_PLUGIN_URL', plugin_dir_url(__FILE__));
define('GH_PW_PLUGIN_BASENAME', plugin_basename(__FILE__));

require_once( 'lib/htmlcompressor.php' );
require_once( 'views/token-settings.php' );

if (!defined('WP_DEBUG') && !WP_DEBUG) {
    require_once( 'lib/github-profile-widget-updates.php' );
    $api_endpoint = GH_PW_API_PATH . '/repos/' . GH_PW_PLUGIN_ORG . '/' . GH_PW_PLUGIN_SLUG . '/releases/latest';
    new GitHub_Profile_Plugin_Auto_Updates($api_endpoint, GH_PW_PLUGIN_URL, GH_PW_PLUGIN_SLUG, GH_PW_PLUGIN_BASENAME);
}

if (!class_exists('GitHub_Profile')) {
    class GitHub_Profile extends WP_Widget {

        const API_PATH = GH_PW_API_PATH;
        const API_VERSION = "2022-11-28";

        protected $widget_slug = 'github-profile';
        protected $checkboxes = array(
            "github_pw_toggle_header",
            "github_pw_toggle_avatar_and_name",
            "github_pw_toggle_followers_and_following",
            "github_pw_toggle_company",
            "github_pw_toggle_location",
            "github_pw_toggle_email",
            "github_pw_toggle_blog",
            "github_pw_toggle_joined_on",
            "github_pw_toggle_public_projects",
            "github_pw_toggle_public_contributions",
            "github_pw_toggle_collaborating_organizations",
            "github_pw_toggle_organizations",
            "github_pw_toggle_dark_theme"
        );

        public function __construct() {
            parent::__construct(
                $this->widget_slug,
                __('GitHub Profile', 'github_profile_widget'),
                array(
                    'classname' => $this->widget_slug . '-class',
                    'description' => __('A widget to show a small version of your GitHub profile', 'github_profile_widget'),
                    'panels_title' => 'github_pw_title', // Disable Widget description override.
                    'panels_icon' => 'dashicons dashicons-editor-code',
                    'panels_groups' => array('djabhiphop'),
                )
            );
        }

        public function form( $config ) {
            if (empty(get_option('github_pw_api_token'))) {
                echo '<p>' . sprintf(__('Go to Settings to configure the GitHub API token. %s', 'github_profile_widget'), '<a href="admin.php?page=github-wp-api-settings">' . __('Go to Settings', 'github_profile_widget') . '</a>') . '</p>';
            } else {

                $default = array(
                    "github_pw_org" => "none",
                    "github_pw_toggle_header" => "on",
                    "github_pw_toggle_avatar_and_name" => "on",
                    "github_pw_toggle_followers_and_following" => "on",
                    "github_pw_toggle_company" => "on",
                    "github_pw_toggle_location" => "on",
                    "github_pw_toggle_email" => "on",
                    "github_pw_toggle_blog" => "on",
                    "github_pw_toggle_joined_on" => "on",
                    "github_pw_toggle_public_projects" => "on",
                    "github_pw_toggle_public_contributions" => "on",
                    "github_pw_toggle_collaborating_organizations" => "on",
                    "github_pw_toggle_organizations" => "on",
                    "github_pw_toggle_dark_theme" => "off",
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

            if ( $this->is_checked( $config, 'organizations' ) || $this->is_checked( $config, 'collaborating_organizations' ) ) {
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
            // Safely check for the 'github_pw_org' index
            $github_pw_org = isset($config['github_pw_org']) ? sanitize_text_field($config['github_pw_org']) : '';

            // Derive API paths based on whether org is provided or not
            if (empty($apiPath)) {
                // If no sub-path is given, use org or user
                if (empty($github_pw_org) || $github_pw_org === 'none') {
                    $apiPath = self::API_PATH . '/user';
                } else {
                    $apiPath = self::API_PATH . '/orgs/' . $github_pw_org;
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
                if (defined('WP_DEBUG') && WP_DEBUG) {
                    error_log("GitHub API: User or organization '$github_pw_org' not found.");
                }
                return __('User or organization not found. Please check the username.', 'github_profile_widget');
            }

            // Handle other HTTP errors
            if ($response['httpCode'] !== 200) {
                if (defined('WP_DEBUG') && WP_DEBUG) {
                    error_log("GitHub API Error: HTTP {$response['httpCode']}. Response: {$response['body']}");
                }
                return __('An error occurred while fetching the GitHub data. Please try again later.', 'github_profile_widget');
            }

            // Decode and return the response for successful requests
            return json_decode($response['body']);
        }

        private function make_github_request($url, $headers) {
            if (empty($url)) {
                return ['httpCode' => 404, 'body' => ''];
            }

            // 10 seconds timeout (you can adjust this value)
            $timeout = 10;

            // Cache the response for a defined duration (in seconds)
            $cache_duration = get_option('github_pw_cache', 60) * 60;
            
            // Create a unique cache key based on the URL
            $cache_key = 'github_request_' . md5($url);
            
            // Check if cached data exists
            $cached_response = get_transient($cache_key);

            if ($cached_response) {
                // If cached data exists, return it
                return $cached_response;
            }

            // Define the timeout (in seconds)

            // If no cached data, make the request with a timeout
            $response = wp_remote_get($url, [
                'headers' => $headers, // Pass the headers with the request
                'timeout' => $timeout,  // Timeout after 10 seconds
            ]);

            // Check for errors in the response
            if (is_wp_error($response)) {
                // Log the error for debugging purposes
                error_log('GitHub API Error: ' . $response->get_error_message());
                return [
                    'httpCode' => 500,
                    'body' => $response->get_error_message(),
                ];
            }

            // Get the HTTP status code and body from the response
            $httpCode = wp_remote_retrieve_response_code($response);
            $body = wp_remote_retrieve_body($response);

            // Log the status code for debugging purposes (optional)
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log("GitHub API Request to {$url} returned HTTP code {$httpCode}");
            }

            set_transient($cache_key, [
                'httpCode' => $httpCode,
                'body' => $body,
            ], $cache_duration);

            return [
                'httpCode' => $httpCode,
                'body' => $body,
            ];
        }

        public function is_checked( $conf, $name ) {
            $prefix = 'github_pw_toggle_';
            return isset( $conf[ $prefix.$name ] ) && $conf[ $prefix.$name ] == 'on';
        }

        public function load_theme() {
            wp_enqueue_style( $this->widget_slug . '-octicons', plugins_url( 'css/octicons/octicons.min.css', __FILE__ ) );
            wp_enqueue_style( $this->widget_slug . '-widget-styles', plugins_url( "css/widget.css", __FILE__ ) );
        }
    }

    add_action( 'widgets_init', function() {
        register_widget( 'GitHub_Profile' );
    } );

    add_filter('siteorigin_panels_widget_dialog_tabs', function($tabs) {
        $tabs[] = array(
            'title' => __('DJABHipHop', 'pt'),
            'filter' => array(
                'groups' => array('djabhiphop'),
            ),
        );
        return $tabs;
    }, 20);

    add_filter('siteorigin_panels_widgets', function($widgets){
       $widgets['GitHub_Profile']['groups'] = array('djabhiphop');
       return $widgets;
    });

    add_filter( 'siteorigin_panels_widgets', function($widgets) {
       $widgets['GitHub_Profile']['groups'][] = 'recommended';
       return $widgets;
    }, 12 );
}
