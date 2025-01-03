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
<?php
$github_pw_title = isset($config['github_pw_title']) ? $config['github_pw_title'] : '';
$github_pw_org = isset($config['github_pw_org']) ? $config['github_pw_org'] : 'none';
$github_pw_cache = isset($config['github_pw_cache']) ? $config['github_pw_cache'] : '';
?>

<p>
    <label for="<?php echo $this->get_field_name( 'github_pw_title' ); ?>"><?php _e( 'Title:', 'github_profile_widget' ); ?></label>
    <input class="widefat"
           id="<?php echo $this->get_field_name( 'github_pw_title' ); ?>"
           name="<?php echo $this->get_field_name( 'github_pw_title' ); ?>"
           type="text"
           placeholder="<?php _e( 'Title', 'github_profile_widget' ); ?>"
           value="<?php echo esc_attr( $github_pw_title ); ?>"/>
</p>

<p>
    <label for="<?php echo $this->get_field_name( 'github_pw_org' ); ?>">
        <?php _e( 'Organizations:', 'github_profile_widget' ); ?>
    </label>
    <select class="widefat"
            id="<?php echo $this->get_field_name( 'github_pw_org' ); ?>"
            name="<?php echo $this->get_field_name( 'github_pw_org' ); ?>">
        <option value="none" <?php selected( $github_pw_org, 'none' ); ?>>
            <?php _e( 'None', 'github_profile_widget' ); ?>
        </option>
        <?php foreach ( $orgs as $org_item ): ?>
            <option value="<?php echo esc_attr( $org_item->login ); ?>"
                <?php selected( $github_pw_org, $org_item->login ); ?>>
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
                    <?php echo ucfirst(str_replace(['github_pw_', '_'], [' ', ' '], $option)); ?>
                </label>
            </li>
        <?php endforeach; ?>
    </ul>
</details>
