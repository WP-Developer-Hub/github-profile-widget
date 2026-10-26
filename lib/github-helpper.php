<?php
/**
 * Provides helpper functions for the GitHub profile widget
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('GitHub_Helpper')) {
    class GitHub_Helpper {
        public static function roundCount($count, $e_not) {
            return ceil(($count / $e_not) * 2) / 2;
        }

        public static function formatViewCount($views) {
            if (!is_numeric($views) || $views < 0) {
                return 0;
            }

            $count = (float) $views;

            // 2. Short-abbreviate large numbers using floor to match your expectation
            switch (true) {
                case $count >= 1e33: // Decillion
                    return SELF::round_count($count, 1e33) . 'Dc';
                case $count >= 1e30: // Nonillion
                    return SELF::round_count($count, 1e30) . 'No';
                case $count >= 1e27: // Octillion
                    return SELF::round_count($count, 1e27) . 'Oc';
                case $count >= 1e24: // Septillion
                    return SELF::round_count($count, 1e24) . 'Sp';
                case $count >= 1e21: // Sextillion
                    return SELF::round_count($count, 1e21) . 'Sx';
                case $count >= 1e18: // Quintillion
                    return SELF::round_count($count, 1e18) . 'Qi';
                case $count >= 1e15: // Quadrillion
                    return SELF::round_count($count, 1e15) . 'Qa';
                case $count >= 1e12: // Trillion
                    return SELF::round_count($count, 1e12) . 'T';
                case $count >= 1e9: // Billion
                    return SELF::round_count($count, 1e9) . 'B';
                case $count >= 1e6: // Million
                    return SELF::round_count($count, 1e6) . 'M';
                case $count >= 1e3: // Thousand
                    return SELF::round_count($count, 1e3) . 'K';
                default:
                    return number_format_i18n($count);
            }
        }
    }
}
