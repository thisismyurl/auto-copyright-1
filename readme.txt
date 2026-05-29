=== This Is My URL - Auto Copyright ===
Contributors: thisismyurl, phillcoxon
Donate link: https://github.com/sponsors/thisismyurl
Plugin URI: https://thisismyurl.com/downloads/auto-copyright-1/
Tags: copyright, footer, shortcode, widget, year
Requires at least: 5.6
Requires PHP: 7.4
Tested up to: 7.0
Stable tag: 1.6147
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Automatically generates a copyright notice based on the first and last post published in the WordPress database.

== Description ==

Automatically generates a copyright notice based on the first and last post published in the WordPress database. The notice can be placed anywhere in the web site template or included as a shortcode within a post itself.

The plugin includes the following theme functions:

* <code>thisismyurl_autocopyright_article( 'format=#c# #y# #sitename#. All Rights Reserved.' )</code> - Will display the copyright for a specific article, must be called from within the Loop.

* <code>thisismyurl_autocopyright( 'Copyright ( #c# ) #from# - #to#' )</code> - Will display the full copyright for the site

Format placeholders: <code>#c#</code> (©), <code>#from#</code> (the year of your earliest published post), <code>#to#</code> and <code>#y#</code> (the current year), and <code>#sitename#</code> (your site title). When the site has no published posts, or the earliest post is from the current year, the range collapses to a single year.

= Block themes (FSE) =

The bundled "Auto Copyright" widget is a classic <code>WP_Widget</code> and is therefore legacy: it does not appear in the Site Editor on block themes (the default in WordPress 6.x and 7.0). On a block theme, place the notice with the <code>[auto_copyright]</code> shortcode (via a Shortcode block) or the <code>thisismyurl_autocopyright()</code> template tag in a footer template part. The widget is retained for classic themes only.

This plugin was originally written by Christopher Ross in 2008, maintained by Phill Coxon during the WordPress 4.x era, and is now maintained again by Christopher at https://thisismyurl.com/.

== Installation ==

To install the plugin, please upload the folder to your plugins folder and activate the plugin.

== Frequently Asked Questions ==

= Where can I get more information, or technical support for this plugin? =

Technical support is available for free from the WordPress community on http://wordpress.org/.

= How do I display the results? =

Insert the following code into your WordPress theme files:

<code>echo thisismyurl_autocopyright();</code>

= Reformatting the results =

You can change how the plugin functions by adding the format option to the function as follows: The plugin recognizes #from# and #to# as valid placeholders.

<code>echo thisismyurl_autocopyright('format=Copyright (#c#) #from# - #to#');</code>

== Change Log ==

= 1.6148 =
* Fix the core promise: #to# and #y# now resolve to the current year (timezone-correct via wp_date), matching the documentation, instead of the newest published post's year.
* Empty/single-year sites now render a single year (e.g. "Copyright ( © ) 2026") instead of an empty or duplicated range.
* Escape the template-tag/shortcode output path with wp_kses_post() so echo do_shortcode() and the template tags are safe by default.
* Document the classic widget as legacy and recommend the [auto_copyright] shortcode or template tag on block themes (FSE).
* Reconcile Tested up to (7.0) and the License URI between the plugin header and readme; point the "Learn about format options" link at the plugin page instead of the raw readme.txt.

= 1.6143 =
* First full release (class 1). The 0.6xxx line was pre-release on the `x.Yddd` scheme.
* Standardized the donation link to GitHub Sponsors.

= 0.6123 (May 3, 2026) =

* Maintenance returns to Christopher Ross
* Fix PHP-fatal: drop double-parse of $arg in shortcode handler
* Fix PHP-fatal: replace PHP4 widget constructor with __construct() / parent::__construct()
* Fix widget output: $intance typo prevented user-chosen format from rendering
* Replace removed attribute_escape() with esc_attr()
* Escape widget output (esc_attr on field IDs/names, wp_kses_post on rendered notice)
* Replace extract($args) anti-pattern with explicit $args[*] reads
* Register clean [auto_copyright] shortcode (long thisismyurl_autocopyright_article tag kept as alias)
* Add ABSPATH guard, text domain loader, full plugin header, i18n on all strings
* Fix get_bloginfo('sitename') -> get_bloginfo('name')
* Cache earliest post year in a transient, invalidated on save_post
* Update readme.txt: contributors, tags trimmed to 5, Tested up to 6.8, Requires PHP 7.4

= 2.2.0 (March 31st, 2013) =

* Added title area and format to Widget
* Minor code cleanup

= 2.1.3 (September 18. 2012) =

* fixed display bug (thanks pgale2!)
* removed need for 'format=' on fetch
* cleaned up options code

= 2.1.2 (September 13. 2012) =

* minor code cleanup and documentation formatting

= 2.1 =

* added thisismyurl_autocopyright_article shortcode

= 2.0.0 =

* Added Widget support
* Removed excess code
* Optimized code for WordPress 3.2
* Replaced wpdb() calls with get_posts()
* added copyright symbol by default

= 1.2 =

* Updated admin
* Added #c# symbol for copyright symbol

= 1.1.8 =

* Removed versioning scripts
* Update links
* Added footer comment

= 1.1.7 =
* update to plugin updater

= 1.1.2 =
* updated website links

= 1.1.1 =
* added format option
* added admin menu

= 1.0.0 (2009-05-07) =
* official release
* Fixed a link in the readme.txt file

= 0.2.3 (2009-03-16) =
* Added the change log

= 0.2.3 (2009-03-26) =
* Happy Birthday to me
