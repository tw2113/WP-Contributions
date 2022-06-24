# WP Contributions
- **Contributors**: webdevstudios, dustyf, colorful-tones
- **Tags**: contributions, core, plugins, themes, codex, widget
- **Donate link**: http://webdevstudios.com
- **Requires at least**: 3.8.0
- **Tested up to**: 4.5.3
- **Stable tag**: 1.0.2
- **License**: GPLv2 or later
- **License URI**: http://www.gnu.org/licenses/gpl-2.0.html

Provides an easy way to display your WordPress.org Themes, Plugins, Core tickets, and Codex contributions with handy widgets and template tags.

## Description

Provides an easy way to display your WordPress.org Themes, Plugins, Core tickets, and Codex contributions with handy widgets and template tags.

WordPress wouldn't be as amazing as it is without all of the contributors to the project. The community is proud of each and every contributor. Display some of your contributions to the project using handy widgets or template tags in your custom theme.  Currently, you can display your contributions to WordPress core, the WordPress Codex, your WordPress Plugins, or your WordPress themes.

There are four handy widgets available for you that are easily configured and added to your sidebar.  You just need to add your theme or plugin slug to display a theme or plugin or enter your WordPress.org username to display core or codex contributions.  You can also display these in a more custon fashion in your custom theme using template tags.  More info on template tags is available under the FAQ.

We want to give a big thanks to the great plugin, [Core Contributions Widget](https://wordpress.org/plugins/wp-core-contributions-widget/) by Eric Mann, Michael Fields, John P. Bloch, Mike Bijon, and Konstantin Obenland. We forked part of this plugin to include Core and Codex Contributions. If you would just like widgets to display Core and Codex Contributions, we recommend downloading their plugin.

## Installation

1. Upload the `wp-contributions` folder to the `/wp-content/plugins/` directory.
2. Activate the WP Contributions plugin through the 'Plugins' menu in WordPress.
3. Add widgets to your sidebars or place template tags in your templates.

## Frequently Asked Questions

### How do I [shortcode](https://codex.wordpress.org/Shortcode)?
* Show your plugin with: `[wp_contributions_plugin_card slug="your-plugin-slug"]`
* Show your theme with: `[wp_contributions_theme_card slug="your-theme-slug"]`

### How do I add a widget?

1. Visit Appearance -> Widgets in your WordPress Admin.
2. Drag any of the WP Contributions widgets to the sidebar where you want them to appear.
3. For the plugin and them widgets, enter a widget title and enter the slug of the plugin you would like to display. The slug of a plugin can be found by looking at the URL of the plugin page.  For instance, Jetpack is found at `https://wordpress.org/plugins/jetpack/` which makes the plugin slug `jetpack`.
4. For Core and Codex contributions, enter a title, your WordPress.org username, and the number of contributions you would like to display. It will display the most recent contributions. There will be a link to display more contributions so people can view any after the number you input.

### How do I integrate directly in a theme?

* Option 1: [Copy over template files and modify](#template-files)
* Option 2: [Utilize the available template tags](#template-tags)

#### <a name="template-files"></a>Option 1: Copy over template files and modify
Copy either the individual template file: e.g. `/wp-content/wp-contributions-theme-card-template.php`, or the plugin's entire template folder into your theme's folder (`/wp-content/plugins/wp-contributions/templates/`), and override anything you desire.

#### <a name="template-tags"></a>Option 2: Utilize the available template tags

`<?php wp_contributions_plugin_card( $plugin_slug ); ?>`

Displays a the plugin information for a plugin. Just pass the slug of the plugin as `$plugin_slug` to display the plugin information card.  This function will echo your results to your template.

`<?php wp_contributions_theme_card( $theme_slug ); ?>`

Displays a the theme information for a theme. Just pass the slug of the theme as `$theme_slug` to display the theme information card.  This function will echo your results to your template.

`<?php wp_contributions_author_plugin_cards( $username ); ?>`

Displays all plugins for a plugin author.  Just pass the WordPress.org username as `$username` to display all plugin cards for that user.  This function will echo your results to your template.

`<?php wp_contributions_author_theme_cards( $username ); ?>`

Displays all plugins for a theme author.  Just pass the WordPress.org username as `$username` to display all theme cards for that user.  This function will echo your results to your template.

`<?php wp_contributions_core_contributions_card( $username, $count ); ?>`

Displays Core contributions for a WordPress.org user.  Just pass the WordPress.org username as `$username` to display the contributions for that user. Optionally, you can also pass in `$count` to control the number of contributions to display. Default count is set at 5. This function will echo your results to your template.

`<?php wp_contributions_codex_contributions_card( $username, $count ); ?>`

Displays Codex contributions for a WordPress.org user.  Just pass the WordPress.org username as `$username` to display the contributions for that user. Optionally, you can also pass in `$count` to control the number of contributions to display. Default count is set at 5. This function will echo your results to your template.

## Changelog

1.0.1 - minor edits, updates to Grunt build, and some shortcodes

1.0.0 - Initial Release

## Upgrade Notice

No upgrades yet.
