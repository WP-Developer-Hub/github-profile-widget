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
<?php if ( isset( $config["github_pw_title"] ) ) : ?>
    <?php echo $before_title . apply_filters('widget_title', esc_html( $config["github_pw_title"] ) ) . $after_title; ?>
<?php endif; ?>
<div class="github-pw<?php if ( $this->is_checked( $config, 'dark_theme' ) ) : ?> github-pw-dark<?php endif; ?>" id="github-pw-<?php echo $profile->id . '-' . $profile->node_id; ?>">
    <?php if ( $this->is_checked( $config, 'header' ) ) : ?>
        <div class="github-pw-header">
            <a href="https://github.com/" target="_blank" title="<?php _e('GitHub', 'github_profile_widget'); ?>">
                <img loading="lazy" decoding="async" class="github-pw-company-logo"
                     src="<?php echo plugins_url( '/img/github-mark' . ( $this->is_checked( $config, 'dark_theme' ) ? '-white' : '' ) . '.svg', dirname(__FILE__)); ?>"
                     alt="<?php _e('GitHub logo', 'github_profile_widget'); ?>" />
            </a>

            <a class="github-pw-header-link" target="_blank" href="<?php echo $profile->html_url; ?>" title="<?php _e('View profile', 'github_profile_widget'); ?>">
                <?php echo $profile->login; ?>
            </a>
        </div>
    <?php endif; ?>
    <div class="github-pw-body">
        <?php if ( $this->is_checked( $config, 'avatar_and_name' ) ) : ?>
            <div class="github-pw-block github-pw-profile">
                <!-- Profile Image Link -->
                <a target="_blank" href="<?php echo $profile->html_url; ?>" title="<?php _e('View profile', 'github_profile_widget'); ?>">
                    <img class="github-pw-profile-avatar" src="<?php echo $profile->avatar_url; ?>" alt="<?php echo $profile->name; ?> avatar">
                </a>
                <span class="github-pw-profile-names">
                    <!-- Profile Name as a Link -->
                    <a target="_blank" href="<?php echo $profile->html_url; ?>" title="<?php _e('View profile', 'github_profile_widget'); ?>">
                        <span class="github-pw-profile-name"><?php echo $profile->name; ?></span>
                    </a>

                    <!-- Username as Plain Text -->
                    <span class="github-pw-profile-username"><?php echo $profile->login; ?></span>
                </span>
            </div>
        <?php endif; ?>

        <?php if ( $this->is_checked( $config, 'followers_and_following' ) ): ?>
            <div class="github-pw-divider"></div>
            <div class="github-pw-block github-pw-vcard-stats">
                <a class="github-pw-icons-block github-pw-vcard-stat" target='blank'
                   href="<?php echo $profile->html_url; ?>/?tab=followers">
                    <i class="octicon octicon-person-24"></i>
                    <strong class="github-pw-vcard-stat-count"><?php echo $profile->followers; ?></strong>
                    <span class="github-pw-text-muted">
                        <?php echo esc_html(_n('Follower', 'Followers', intval($profile->followers), 'github_profile_widget')); ?>
                    </span>
                </a>
                <span class="github-pw-text-small">&bull;</span>
                <a class="github-pw-vcard-stat" target='blank'
                   href="<?php echo $profile->html_url; ?>/?tab=following">
                    <strong class="github-pw-vcard-stat-count"><?php echo $profile->following; ?></strong>
                    <span class="github-pw-text-muted"><?php _e('Following', 'github_profile_widget'); ?></span>
                </a>
            </div>
        <?php endif; ?>

        <?php if (
            ( $this->is_checked( $config, 'company' ) && ! empty( $profile->company ) ) ||
            ( $this->is_checked( $config, 'location' ) && ! empty( $profile->location ) ) ||
            ( $this->is_checked( $config, 'email' ) && ! empty( $profile->email ) ) ||
            ( $this->is_checked( $config, 'blog' ) && ! empty( $profile->blog ) ) ||
            ( $this->is_checked( $config, 'joined_on' ) )
        ) : ?>
            <div class="github-pw-divider"></div>
            <div class="github-pw-block">
                <?php if ( $this->is_checked( $config, 'company' ) && ! empty( $profile->company ) ): ?>
                    <div title="<?php _e('Company', 'github_profile_widget'); ?>" class="github-pw-icons-block">
                        <i class="octicon octicon-organization-24"></i><?php echo ucfirst($profile->company); ?>
                    </div>
                <?php endif; ?>

                <?php if ( $this->is_checked( $config, 'location' ) && ! empty( $profile->location ) ): ?>
                    <div title="<?php _e('Location', 'github_profile_widget'); ?>" class="github-pw-icons-block">
                        <i class="octicon octicon-location-24"></i>
                        <?php echo $profile->location; ?>
                    </div>
                <?php endif; ?>

                <?php if ( $this->is_checked( $config, 'email' ) && ! empty( $profile->email ) ): ?>
                    <div title="<?php _e('Email', 'github_profile_widget'); ?>" class="github-pw-icons-block">
                        <i class="octicon octicon-mail-24"></i>
                        <a href="mailto:<?php echo $profile->email; ?>"><?php echo $profile->email; ?></a>
                    </div>
                <?php endif; ?>

                <?php if ( $this->is_checked( $config, 'blog' ) && ! empty( $profile->blog ) ): ?>
                    <div title="<?php _e('Blog', 'github_profile_widget'); ?>" class="github-pw-icons-block">
                        <i class="octicon octicon-link-24"></i>
                        <a href="<?php echo $profile->blog; ?>" target="_blank"><?php echo $profile->blog; ?></a>
                    </div>
                <?php endif; ?>

                <?php if ( $this->is_checked( $config, 'joined_on' ) ): ?>
                    <div class="github-pw-icons-block">
                        <i class="octicon octicon-clock-24"></i>
                        <span>
                            <?php _e('Joined on', 'github_profile_widget'); ?>
                            <?php echo $profile->created_at->format( 'M d, Y' ); ?>
                        </span>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    <?php
        if (
            ( $this->is_checked( $config, 'public_projects' ) && ! empty( $profile->public_repos ) ) ||
            ( $this->is_checked( $config, 'public_contributions' ) && ! empty( $profile->public_gists ) ) ||
            ( $this->is_checked( $config, 'collaborating_organizations' ) && ! empty( $orgs ) )
        ) : ?>
        <div class="github-pw-divider"></div>
        <div class="github-pw-block">
            <?php if ( $this->is_checked( $config, 'public_projects' ) && ! empty( $profile->public_repos ) ) : ?>
                <div class="github-pw-icons-block">
                    <i class="octicon octicon-repo-24"></i>
                    <a href="<?php echo $profile->html_url; ?>/?tab=projects" target="_blank">
                        <?php echo $profile->public_repos; ?> <?php _e('Public Projects', 'github_profile_widget'); ?>
                    </a>
                </div>
            <?php endif; ?>

            <?php if ( $this->is_checked( $config, 'public_contributions' ) && ! empty( $profile->public_gists ) ) : ?>
                <div class="github-pw-icons-block">
                    <i class="octicon octicon-code-square-24"></i>
                    <a href="https://gist.github.com/<?php echo $profile->login; ?>" target="_blank">
                        <?php echo $profile->public_gists; ?> <?php _e('Public Contributions', 'github_profile_widget'); ?>
                    </a>
                </div>
            <?php endif; ?>

            <?php if ( $this->is_checked( $config, 'collaborating_organizations' ) && ! empty( $orgs ) ) : ?>
                <div class="github-pw-icons-block">
                    <i class="octicon octicon-organization-24"></i>
                    <a href="https://github.com/<?php echo $profile->login; ?>/organizations" target="_blank">
                        <?php echo count($orgs); ?> <?php _e('Collaborating Organizations', 'github_profile_widget'); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    </div>
    <?php if ( $this->is_checked( $config, 'organizations' ) && ! empty( $orgs ) ) : ?>
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
