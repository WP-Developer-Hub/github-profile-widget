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
<?php echo $before_widget ?>
<?php if ( isset( $config["github_wp_title"] ) ) : ?>
    <?php echo $before_title . esc_html( $config["github_wp_title"] ) . $after_title; ?>
<?php endif; ?>
<div class="github-pw<?php if ( $this->is_checked( $config, 'github_wp_toggle_dark_theme' ) ) : ?> github-pw-dark<?php endif; ?>" id="github-pw-<?php echo $profile->id . '-' . $profile->node_id; ?>">
    <?php if ( $this->is_checked( $config, 'github_wp_toggle_header' ) ) : ?>
        <div class="github-pw-header">
            <a href="https://github.com/" target="_blank" title="<?php _e('GitHub', 'github_profile_widget'); ?>">
                <img loading="lazy" decoding="async" class="github-pw-company-logo"
                     src="<?php echo plugins_url( '/img/github-mark' . ( $this->is_checked( $config, 'github_wp_toggle_dark_theme' ) ? '-white' : '' ) . '.svg', dirname(__FILE__)); ?>"
                     alt="<?php _e('GitHub logo', 'github_profile_widget'); ?>" />
            </a>

            <a class="github-pw-header-link" target="_blank"
               href="<?php echo $profile->html_url; ?>" title="<?php _e('View profile', 'github_profile_widget'); ?>">
                <?php echo $profile->login; ?>
            </a>
        </div>
    <?php endif; ?>
    <div class="github-pw-body">
        <?php if ( $this->is_checked( $config, 'github_wp_toggle_avatar_and_name' ) ) : ?>
            <div class="github-pw-block github-pw-profile">
                <!-- Profile Image Link -->
                <a target="_blank" href="<?php echo $profile->html_url; ?>" title="<?php _e('View profile', 'github_profile_widget'); ?>">
                    <img class="github-pw-profile-avatar" src="<?php echo $profile->avatar_url; ?>" alt="<?php echo $profile->name; ?> avatar">
                </a>
                <span class="github-pw-profile-names">
                    <!-- Profile Name as a Link -->
                    <a target="_blank" href="<?php echo $profile->html_url; ?>" title="<?php _e('View profile', 'github_profile_widget'); ?>">
                        <span class="github-pw-profile-name"><?php echo $profile->name; ?></i>
                    </a>

                    <!-- Username as Plain Text -->
                    <span class="github-pw-profile-username"><?php echo $profile->login; ?></i>
                </span>
            </div>
        <?php endif; ?>

        <?php if ( $this->is_checked( $config, 'github_wp_toggle_followers_and_following' ) ): ?>
            <div class="github-pw-divider"></div>
            <div class="github-pw-block github-pw-vcard-stats">
                <a class="github-pw-icons-block github-pw-vcard-stat" target='_blank'
                   href="<?php echo $profile->html_url; ?>/?tab=followers">
                    <i class="octicon octicon-person-24"></i>
                    <strong class="github-pw-vcard-stat-count"><?php echo $profile->followers; ?></strong>
                    <span class="github-pw-text-muted"><?php _e('Followers', 'github_profile_widget'); ?></i>
                </a>
                <span>&bull;</span>
                <a class="github-pw-vcard-stat" target='_blank'
                   href="<?php echo $profile->html_url; ?>/?tab=following">
                    <strong class="github-pw-vcard-stat-count"><?php echo $profile->following; ?></strong>
                    <span class="github-pw-text-muted"><?php _e('Following', 'github_profile_widget'); ?></i>
                </a>
            </div>
        <?php endif; ?>

        <?php if ( $this->is_checked( $config, 'github_wp_toggle_meta_info' ) ) : ?>
            <div class="github-pw-divider"></div>
            <div class="github-pw-block">
                <?php if ( ! empty( $profile->company ) ): ?>
                    <div title="<?php _e('Company', 'github_profile_widget'); ?>" class="github-pw-icons-block">
                        <i class="octicon octicon-organization-24"></i><?php echo ucfirst($profile->company); ?>
                    </div>
                <?php endif; ?>
                <?php if ( ! empty( $profile->location ) ): ?>
                    <div title="<?php _e('Location', 'github_profile_widget'); ?>" class="github-pw-icons-block"><span
                            class="octicon octicon-location-24"></i><?php echo $profile->location; ?></div>
                <?php endif; ?>
                <?php if ( ! empty( $profile->email ) ): ?>
                    <div title="<?php _e('Email', 'github_profile_widget'); ?>" class="github-pw-icons-block">
                        <i class="octicon octicon-mail-24"></i>
                        <a href="mailto:<?php echo $profile->email; ?>"><?php echo $profile->email; ?></a>
                    </div>
                <?php endif; ?>
                <?php if ( ! empty( $profile->blog ) ): ?>
                    <div title="<?php _e('Blog', 'github_profile_widget'); ?>" class="github-pw-icons-block">
                        <i class="octicon octicon-link-24"></i>
                        <a href="<?php echo $profile->blog; ?>" target="_blank"><?php echo $profile->blog; ?></a>
                    </div>
                <?php endif; ?>
                <div class="github-pw-icons-block">
                    <i class="octicon octicon-clock-24"></i>
                    <?php _e('Joined on', 'github_profile_widget'); ?> <?php echo $profile->created_at->format( 'M d, Y' ); ?>
                </div>

            </div>

            <div class="github-pw-divider"></div>
            <div class="github-pw-block">
                <div class="github-pw-icons-block">
                    <i class="octicon octicon-repo-24"></i>
                    <a href="<?php echo $profile->html_url; ?>/?tab=repositories" target="_blank">
                        <?php echo $profile->public_repos; ?> <?php _e('Public Repositories', 'github_profile_widget'); ?>
                    </a>
                </div>
                <div class="github-pw-icons-block">
                    <i class="octicon octicon-code-24"></i>
                    <a href="https://gist.github.com/<?php echo $profile->login; ?>" target="_blank">
                        <?php echo $profile->public_gists; ?> <?php _e('Public Gists', 'github_profile_widget'); ?>
                    </a>
                </div>
                <?php if ( ! empty( $orgs ) ) : ?>
                    <div class="github-pw-icons-block">
                        <i class="octicon octicon-organization-24"></i>
                        <a href="https://gist.github.com/<?php echo $profile->login; ?>" target="_blank">
                            <?php echo count($orgs); ?> <?php _e('Organizations', 'github_profile_widget'); ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    <?php if ( $this->is_checked( $config, 'github_wp_toggle_organizations' ) && ! empty( $orgs ) ) : ?>
        <div class="github-pw-block github-pw-orgs">
            <?php foreach ( $orgs as $org ) { ?>
                <a target="_blank" href="https://github.com/<?php echo $org->login; ?>"
                   title="<?php echo $org->login; ?> & <?php echo $org->description; ?>">
                    <img loading="lazy" decoding="async"  src='<?php echo $org->avatar_url; ?>' class="github-pw-org-avatar" alt="<?php echo $org->login; ?> avatar"/>
                </a>
            <?php } ?>
        </div>
    <?php endif; ?>
</div>
<?php echo $after_widget ?>
