=== Locationews ===
Contributors: anttiluokkanen
Donate link: https://www.locationews.com
Plugin Name: Locationews
Plugin URI: https://www.locationews.com
Tags: location, local, map, news, publishing
Requires at least: 4.4
Tested up to: 4.8
Stable tag: 1.1.11
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Version: 1.1.11

Publish location based articles with Locationews API and Google Map API.

== Description ==

Locationews is a location based publishing channel that works both as a tool for journalists (as a plugin and template for the most widely used publishing platforms such as WordPress) and as an application that shows the content in a convenient map-based interface.

Go to locationews.com, register your free account and start publishing.

The plugin is made as simple as possible so that publishing on Locationews would be effortless for the publisher. Essentially you only need to install the plugin and enable it in one switch and you are ready to go.

Locationews plugin is WordPress multisite compatible.

The Plugin uses The WordPress Plugin Boilerplate (http://wppb.io/) as a starting point.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/locationews` directory or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Use the Locationews screen to configure the plugin.

== Changelog ==

= 1.1.11 =
* Bugfix:   Fixed a conflict when multiple plugins uses Google Maps API.

= 1.1.10 =
* Bugfix:   Removed conflicting jQuery tooltips.

= 1.1.9 =
* Updated:  Changed the order of admin js files.

= 1.1.8 =
* NEW:      Added PHP version check on activation.
* Fixed:    On plugin activation check if valid options already exists.

= 1.1.7 =
* Fixed:    Removed content validation. Let the API remove unwanted tags.
* Updated:  API response messages and translations.

= 1.1.6 =
* Fixed:    Urlencode featured image url.

= 1.1.5 =
* NEW:      Defined default actions that occur in the front end.
* Changed:  Updated info texts with links to registration.

= 1.1.4 =
* Fixed:	Plugin css should not affect to other plugins anymore. Added namespacing to Bootstrap styles.

= 1.1.3 =
* NEW:      Tooltips & help texts
* NEW:      Map styles from Locationews Dashboard.
* NEW:      Possibility to search location address
* NEW:      Set article's default coordinates to null.
* Removed:  Locationews icon from post types list view.

= 1.1.2 =
* NEW:      Added support for multisite install.
* NEW:      Added test settings for test users.
* NEW:      Validate and strip not allowed tags from content

= 1.1.1 =
* NEW:      Added Locationews -category option to settings
* Fixed:    Tested with minimum requirements: PHP 5.3.29, WordPress 4.4

= 1.1.0 =
* NEW:      Use publicationId instead of userId
* NEW:      First version to use new API version 1.1.0
* Fixed:    Massive code rewrite and optimization

= 1.0.7 =
* Changed:  First test version. Latest version to use API version 1.0.3

= 1.0.6 =
* Fixed:    Bug fix. Locationews icon went back to default position when saving news. Reason: wrong meta key.

= 1.0.5 =
* NEW:      Allow shortcodes in content

= 1.0.4 =
* Changed:  Publish only published posts
* Changed:  Remove post when status is not publish
* Changed:  Remove post when post is moved to trash
* Fixed:    Bugfix in CategoryId

= 1.0.3 =
* Fixed:    Fixed bug in default location

== Upgrade Notice ==

== Requirements ==
* PHP >= 5.3.29
* WordPress >= 4.4
* cURL support

==  Frequently Asked Questions ==
None yet.

== Screenshots ==

1. Locationews Plugin Settings
2. Locationews Meta Box
