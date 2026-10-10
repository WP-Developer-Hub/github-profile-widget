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
    <label for="<?php echo esc_attr( $this->get_field_name( 'github_pw_title' ) ); ?>"><?php echo esc_html ( 'Title:', 'github-profile-widget' ); ?></label>
    <input class="widefat"
           id="<?php echo esc_attr( $this->get_field_name( 'github_pw_title' ) ); ?>"
           name="<?php echo esc_attr( $this->get_field_name( 'github_pw_title' ) ); ?>"
           type="text"
           placeholder="<?php esc_attr_e( 'Title', 'github-profile-widget' ); ?>"
           value="<?php echo esc_attr( $github_pw_title ); ?>"/>
</p>

<p>
    <label for="<?php echo esc_attr($this->get_field_name( 'github_pw_org' ) ); ?>">
        <?php esc_html_e( 'Organizations:', 'github-profile-widget' ); ?>
    </label>
    <select class="widefat" style="max-width:100% !important"
            id="<?php echo esc_attr( $this->get_field_name( 'github_pw_org' ) ); ?>"
            name="<?php echo esc_attr( $this->get_field_name( 'github_pw_org' ) ); ?>">
        <option value="none" <?php selected( $github_pw_org, 'none' ); ?>>
            <?php echo esc_html( 'None', 'github-profile-widget' ); ?>
        </option>
        <?php foreach ( $orgs as $org_item ): ?>
            <option value="<?php echo esc_attr( $org_item->login ); ?>"
                <?php selected( $github_pw_org, $org_item->login ); ?>>
                <?php
                    echo sprintf(
                        /* translators: %s: GitHub login name */
                        esc_html__( '%s⠀', 'github-profile-widget' ),
                        esc_html( $org_item->login )
                    );
                ?>
            </option>
        <?php endforeach; ?>
    </select>
</p>

<div class="categorydiv">
    <ul id="product-mata-tabs" class="category-tabs" role="tablist">
        <li class="tabs">
            <a href="#sizes" role="tab" aria-controls="sizes">
                <?php echo esc_html( 'Apprence', 'github-profile-widget' ); ?>
            </a>
        </li>
    </ul>
    <div class="tabs-panel">
        <ul class="categorychecklist form-no-clear">
            <?php foreach ( $this->checkboxes as $option ): ?>
                <li>
                    <label for="<?php echo esc_attr( $this->get_field_id( $option ) ); ?>">
                        <input class="checkbox" type="checkbox"
                               <?php echo esc_attr( isset( ${$option} ) && ${$option} === 'on' ? 'checked' : '' ); ?>
                               id="<?php echo esc_attr( $this->get_field_id( $option ) ); ?>"
                               name="<?php echo esc_attr( $this->get_field_name( $option ) ); ?>"/>
                        <?php
                            echo sprintf( /* translators: %s: GitHub Display Options */
                                esc_html__( '%s.', 'github-profile-widget' ),
                                esc_html(ucwords(trim(str_replace(['github_pw_', '_'], [' ', ' '], $option))))
                            );
                        ?>
                    </label>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
<br>
