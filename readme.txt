=== WP Contributions ===
Contributors: webdevstudios, dustyf, colorful-tones
Tags: contributions, core, plugins, themes, codex, widget
Donate link: http://webdevstudios.com
Requires at least: 3.8.0
Tested up to: 4.7
Stable tag: 1.1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Provides an easy way to display your WordPress.org Themes, Plugins, Core tickets, and Codex contributions with handy widgets and template tags.

== Description ==

Provides an easy way to display your WordPress.org Themes, Plugins, Core tickets, and Codex contributions with handy widgets and template tags.

WordPress wouldn't be as amazing as it is without all of the contributors to the project. The community is proud of each and every contributor. Display some of your contributions to the project using handy widgets or template tags in your custom theme.  Currently, you can display your contributions to WordPress core, the WordPress Codex, your WordPress Plugins, or your WordPress themes.

There are four handy widgets available for you that are easily configured and added to your sidebar.  You just need to add your theme or plugin slug to display a theme or plugin or enter your WordPress.org username to display core or codex contributions.

There are two shortcodes available, and some handy template tags if you desire to add them in your custom theme.  More info on shortcodes and template tags are available under the [FAQ](https://wordpress.org/plugins/wp-contributions/faq/).

We want to give a big thanks to the great plugin, [Core Contributions Widget](https://wordpress.org/plugins/wp-core-contributions-widget/) by Eric Mann, Michael Fields, John P. Bloch, Mike Bijon, and Konstantin Obenland. We forked part of this plugin to include Core and Codex Contributions. If you would just like widgets to display Core and Codex Contributions, we recommend downloading their plugin.

== Installation ==

1. Upload the `wp-contributions` folder to the `/wp-content/plugins/` directory.
2. Activate the WP Contributions plugin through the 'Plugins' menu in WordPress.
3. Add widgets to your sidebars or place template tags in your templates.

== Frequently Asked Questions ==

= Available shortcodes =

Show a Plugin Card

`[wp_contributions_plugin_card slug="your-plugin-slug"]`

Show a Theme Card

`[wp_contributions_theme_card slug="your-theme-slug"]`

Not sure what shortcodes are? [Learn more here](https://codex.wordpress.org/Shortcode).

= How do I add a widget? =

1. Visit Appearance -> Widgets in your WordPress Admin.
2. Drag any of the WP Contributions widgets to the sidebar where you want them to appear.
3. For the plugin and them widgets, enter a widget title and enter the slug of the plugin you would like to display. The slug of a plugin can be found by looking at the URL of the plugin page.  For instance, Jetpack is found at `https://wordpress.org/plugins/jetpack/` which makes the plugin slug `jetpack`.
4. For Core and Codex contributions, enter a title, your WordPress.org username, and the number of contributions you would like to display. It will display the most recent contributions. There will be a link to display more contributions so people can view any after the number you input.

= What template tags are available? =

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

= How do I integrate directly in a theme? =

Copy either the individual template file: e.g. `/wp-content/wp-contributions-theme-card-template.php`, or the plugin's entire template folder into your theme's folder (`/wp-content/plugins/wp-contributions/templates/`), and override anything you desire.

== Screenshots ==

1. Plugin Card View (Shown in Twenty Fourteen Theme)
2. Theme Card View (Shown in in Twenty Fourteen Theme)
3. Codex Contributions Card View (Shown in Twenty Fourteen Theme)
4. Core Contributions Card View (Shown in Twenty Fourteen Theme)
5. Widget Management

== Changelog ==

= 1.1.0 =
* Shortcodes for:
 * Plugin Card `[wp_contributions_plugin_card slug="your-plugin-slug"]`, and
 * Theme Card `[wp_contributions_theme_card slug="your-theme-slug"]`
* Add descriptions to Core widget

= 1.0.1 =
* minor edits and updates to Grunt build

= 1.0.0 =
* Initial Release

== Upgrade Notice ==

= 1.1.0 =
* Shortcodes for:
 * Plugin Card `[wp_contributions_plugin_card slug="your-plugin-slug"]`, and
 * Theme Card `[wp_contributions_theme_card slug="your-theme-slug"]`
* Add descriptions to Core widget
