<?php
/*
 * Github Profile Widget for WordPress
 *
 *     Copyright (C) 2015 Henrique Dias <hacdias@gmail.com>
 *     Copyright (C) 2015 Luís Soares <lsoares@gmail.com>
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
?>

<p>
    <label for="<?php echo $this->get_field_name( 'github_wp_title' ); ?>"><?php _e( 'Title:', 'github_profile_widget' ); ?></label>
    <input class="widefat"
           id="<?php echo $this->get_field_name( 'github_wp_title' ); ?>"
           name="<?php echo $this->get_field_name( 'github_wp_title' ); ?>"
           type="text"
           placeholder="<?php _e( 'Title', 'github_profile_widget' ); ?>"
           value="<?php echo esc_attr( $github_wp_title ); ?>"/>
</p>

<p>
    <label for="<?php echo $this->get_field_name( 'github_wp_org' ); ?>"><?php _e( 'Organizations:', 'github_profile_widget' ); ?></label>
    <select class="widefat"
            id="<?php echo $this->get_field_name( 'github_wp_org' ); ?>"
            name="<?php echo $this->get_field_name( 'github_wp_org' ); ?>">
        <option value="none" <?php echo empty( $org ) ? 'selected' : ''; ?>><?php _e( 'User', 'github_profile_widget' ); ?></option>
        <?php foreach ( $orgs as $org_item ): ?>
            <option value="<?php echo esc_attr( $org_item->login ); ?>"
                <?php echo ( $org == $org_item->login ) ? 'selected' : ''; ?>>
                <?php echo esc_html( $org_item->login ); ?>
            </option>
        <?php endforeach; ?>
    </select>
</p>

<details>
    <summary><?php _e( 'Show', 'github_profile_widget' ); ?></summary>
    <ul>
        <?php foreach ( $this->checkboxes as $option ): ?>
            <li>
                <label for="<?php echo $this->get_field_id( $option ); ?>">
                    <input class="checkbox" type="checkbox"
                           <?php checked( ${$option}, 'on' ); ?>
                           id="<?php echo $this->get_field_id( $option ); ?>"
                           name="<?php echo $this->get_field_name( $option ); ?>"/>
                    <?php echo ucfirst( str_replace( '_', ' ', $option ) ); ?>
                </label>
            </li>
        <?php endforeach; ?>
    </ul>
</details>

<details>
    <summary><?php _e( 'Advance', 'github_profile_widget' ); ?></summary>
    <ul>
        <li>
        <label for="<?php echo $this->get_field_name( 'github_wp_cache' ); ?>"><?php _e( 'Minutes of cache:', 'github_profile_widget' ); ?></label>
        <input class="widefat" title="<?php _e( 'Value 0 disables cache', 'github_profile_widget' ); ?>"
               id="<?php echo $this->get_field_name( 'github_wp_cache' ); ?>"
               name="<?php echo $this->get_field_name( 'github_wp_cache' ); ?>"
               type="number"
               placeholder="<?php _e( 'Cache expiration time in minutes', 'github_profile_widget' ); ?>"
               value="<?php echo esc_attr( $cache ); ?>"/>
        </li>
    </ul>
</details>
