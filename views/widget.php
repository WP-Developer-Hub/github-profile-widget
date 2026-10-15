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
    echo wp_kses_post($before_widget);
    if ( isset( $config["github_pw_title"] ) ) {
        echo wp_kses_post($before_title . apply_filters('widget_title', esc_html( $config["github_pw_title"] ) ) . $after_title);
    };
?>
<div class="github-pw<?php echo esc_attr($this->is_checked( $config, 'dark_theme' ) ? ' github-pw-dark' : ''); ?>" id="github-pw-<?php echo esc_attr($profile->id) . '-' . esc_attr($profile->node_id); ?>">
    <?php if ( $this->is_checked( $config, 'header' ) ) : ?>
        <div class="github-pw-header">
            <a href="https://github.com/" target="_blank" title="<?php esc_attr_e('GitHub', 'github-profile-widget'); ?>">
                <img loading="lazy" decoding="async" class="github-pw-company-logo"
                     src="<?php echo esc_url(plugins_url( '/img/github-mark' . ( $this->is_checked( $config, 'dark_theme' ) ? '-white' : '' ) . '.svg', dirname(__FILE__))); ?>"
                     alt="<?php esc_attr_e('GitHub logo', 'github-profile-widget'); ?>" />
            </a>

            <a class="github-pw-header-link" target="_blank" href="<?php echo esc_url($profile->html_url); ?>" title="<?php esc_attr_e('View profile', 'github-profile-widget'); ?>">
                <?php echo esc_html($profile->login); ?>
            </a>
        </div>
    <?php endif; ?>
    <div class="github-pw-body">
        <?php if ( $this->is_checked( $config, 'avatar_and_name' ) ) : ?>
            <div class="github-pw-block github-pw-profile">
                <!-- Profile Image Link -->
                <?php if ( $this->is_checked( $config, 'avatar_and_name' ) ) : ?>
                    <a target="_blank" href="<?php echo esc_url($profile->html_url); ?>" title="<?php esc_attr_e('View profile', 'github-profile-widget'); ?>">
                        <img class="github-pw-profile-avatar" src="<?php echo esc_url($profile->avatar_url); ?>" alt="<?php echo esc_attr($profile->name); ?> avatar">
                    </a>
                <?php endif; ?>
                <span class="github-pw-profile-names">
                    <!-- Profile Name as a Link -->
                    <a target="_blank" href="<?php echo esc_url($profile->html_url); ?>" title="<?php esc_attr_e('View profile', 'github-profile-widget'); ?>">
                        <span class="github-pw-profile-name"><?php echo esc_html($profile->name); ?></span>
                    </a>
                    <!-- Username as Plain Text -->
                    <span class="github-pw-profile-username"><?php echo esc_html($profile->login); ?></span>
                </span>
            </div>
        <?php endif; ?>

        <?php if ( $this->is_checked( $config, 'followers_and_following' ) ): ?>
            <div class="github-pw-divider"></div>
            <div class="github-pw-block github-pw-vcard-stats">
                <a class="github-pw-icons-block github-pw-vcard-stat" target='blank'
                   href="<?php echo esc_url($profile->html_url); ?>/?tab=followers">
                    <i class="octicon octicon-person-24"></i>
                    <strong class="github-pw-vcard-stat-count"><?php echo esc_html($profile->followers); ?></strong>
                    <span class="github-pw-text-muted">
                        <?php echo esc_html(_n('Follower', 'Followers', intval($profile->followers), 'github-profile-widget')); ?>
                    </span>
                </a>
                <span class="github-pw-text-small">&bull;</span>
                <a class="github-pw-vcard-stat" target='blank'
                   href="<?php echo esc_url($profile->html_url); ?>/?tab=following">
                    <strong class="github-pw-vcard-stat-count"><?php echo esc_html($profile->following); ?></strong>
                    <span class="github-pw-text-muted"><?php esc_html_e('Following', 'github-profile-widget'); ?></span>
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
                    <div title="<?php esc_attr_e('Company', 'github-profile-widget'); ?>" class="github-pw-icons-block">
                        <i class="octicon octicon-organization-24"></i><?php echo esc_html(ucfirst($profile->company)); ?>
                    </div>
                <?php endif; ?>

                <?php if ( $this->is_checked( $config, 'location' ) && ! empty( $profile->location ) ): ?>
                    <div title="<?php esc_attr_e('Location', 'github-profile-widget'); ?>" class="github-pw-icons-block">
                        <i class="octicon octicon-location-24"></i>
                        <?php echo esc_html($profile->location); ?>
                    </div>
                <?php endif; ?>

                <?php if ( $this->is_checked( $config, 'email' ) && ! empty( $profile->email ) ): ?>
                    <div title="<?php esc_attr_e('Email', 'github-profile-widget'); ?>" class="github-pw-icons-block">
                        <i class="octicon octicon-mail-24"></i>
                        <a href="mailto:<?php echo esc_attr($profile->email); ?>"><?php echo esc_html($profile->email); ?></a>
                    </div>
                <?php endif; ?>

                <?php if ( $this->is_checked( $config, 'blog' ) && ! empty( $profile->blog ) ): ?>
                    <div title="<?php esc_attr_e('Blog', 'github-profile-widget'); ?>" class="github-pw-icons-block">
                        <i class="octicon octicon-link-24"></i>
                        <a href="<?php echo esc_url($profile->blog); ?>" target="_blank"><?php echo esc_html($profile->blog); ?></a>
                    </div>
                <?php endif; ?>

                <?php if ( $this->is_checked( $config, 'joined_on' ) ): ?>
                    <div class="github-pw-icons-block">
                        <i class="octicon octicon-clock-24"></i>
                        <span>
                            <?php
                                echo sprintf(
                                    /* translators: %s: Date the user joined */
                                    esc_html__('Joined on %s', 'github-profile-widget'),
                                    esc_html($profile->created_at->format( 'M d, Y' ))
                                );
                            ?>
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
                    <a href="<?php echo esc_url($profile->html_url); ?>/?tab=projects" target="_blank">
                        <?php
                            echo sprintf(
                                /* translators: %s: Number of public projects */
                                esc_html__('%s Public Projects', 'github-profile-widget'),
                                esc_html($profile->public_repos)
                            );
                        ?>
                    </a>
                </div>
            <?php endif; ?>

            <?php if ( $this->is_checked( $config, 'public_contributions' ) && ! empty( $profile->public_gists ) ) : ?>
                <div class="github-pw-icons-block">
                    <i class="octicon octicon-code-square-24"></i>
                    <a href="https://gist.github.com/<?php echo esc_attr($profile->login); ?>" target="_blank">
                        <?php
                            echo sprintf(
                                /* translators: %s: Number of public gists */
                                esc_html__('%s Public Contributions', 'github-profile-widget'),
                                esc_html($profile->public_gists)
                            );
                        ?>
                    </a>
                </div>
            <?php endif; ?>

            <?php if ( $this->is_checked( $config, 'collaborating_organizations' ) && ! empty( $orgs ) ) : ?>
                <div class="github-pw-icons-block">
                    <i class="octicon octicon-organization-24"></i>
                    <?php
                        echo sprintf(
                            /* translators: %s: Number of collaborating organizations */
                            esc_html__('Member of %s Organizations', 'github-profile-widget'),
                            esc_html(count($orgs))
                        );
                    ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    </div>
    <?php if ( $this->is_checked( $config, 'organizations' ) && ! empty( $orgs ) ) : ?>
        <div class="github-pw-block github-pw-orgs">
            <?php foreach ( $orgs as $org ) { ?>
                <a target="_blank" href="https://github.com/<?php echo esc_attr($org->login); ?>"
                   title="<?php echo esc_attr($org->login); ?> & <?php echo esc_attr($org->description); ?>">
                    <img loading="lazy" decoding="async"  src='<?php echo esc_url($org->avatar_url); ?>' class="github-pw-org-avatar" alt="<?php echo esc_attr($org->login); ?> avatar"/>
                </a>
            <?php } ?>
        </div>
    <?php endif; ?>
</div>
<?php echo wp_kses_post($after_widget) ?>
