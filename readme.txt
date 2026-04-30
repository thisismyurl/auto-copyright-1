=== Auto Copyright ===
Contributors: phillcoxon
Plugin URI: http://phillcoxon.com/wp
Tags: blog, administration, copyright, links, footer, plugin, admin, syndication, aggregation, full text, theft, protection, splogs, theme, footer
Donate link:  http://phillcoxon.com/wp
Requires at least: 3.2.0
Tested up to: 4.1.0
Stable tag: 14.11

Automatically generates a copyright notice based on the first and last post published in the WordPress database.

== Description ==

Automatically generates a copyright notice based on the first and last post published in the WordPress database. The notice can be placed anywhere in the web site template or included as a short code within a post itself.

The plugin includes the following theme functions:

* <code>thisismyurl_autocopyright_article( 'format=#c# #y# #sitename#. All Rights Reserved.' )</code> - Will display the copyright for a specific article, must be called from within the Loop.

* <code>thisismyurl_autocopyright( 'Copyright ( #c# ) #from# - #to#' )</code> - Will display the full copyright for the site

This plugin is maintained by Phill Coxon (http://phillcoxon.com) or you can find him on Twitter at http://twitter.com/phillcoxon/

== Installation ==

To install the plugin, please upload the folder to your plugins folder and active the plugin.

== Screenshots ==

== Updates ==


== Frequently Asked Questions ==

= Where can I get more information, or technical support for this plugin? =

Technical support is available for free from the WordPress community on http://wordpress.org/.

= How do I display the results? =

Insert the following code into your WordPress theme files:

<code>echo thisismyurl_autocopyright();</code>

= Reformatting the results =

You can change how the plugin functions by adding the format option to the function as follows: The plugin recognizes #from# and #to# as valid placeholders.

<code>echo thisismyurl_autocopyright('format=Copyright (#c#) #from# - #to#');</code>

== Donations ==
If you would like to donate to help support future development of this tool, please visit http://phillcoxon.com/wp/

== Original Developer ==

This plugin was originally created by Christopher Ross before being taken over by Phill Coxon in Jan 2016


== Change Log ==

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


== Screenshots ==

Empty
