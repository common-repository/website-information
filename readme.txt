=== Website Information ===
Contributors: giuse
Donate link:
Tags: website information, quotation
Requires at least: 4.6
Tested up to: 5.9
Stable tag: 0.0.6
Requires PHP: 5.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

It collects some useful information about the website (number of posts, server environment, active plugins...).


== Description ==

It collects some useful information about the website (number of posts, server environment, active plugins, plugins and theme that need updates...).

If you need for example to give a quotation to restyle or optimise a website, without the need to log in to the backend, you can ask to install this plugin and give you the collected information.

The actual version collects the following data:


Home URL

Wordpress Version

Wordpress Multisite: Yes/No

Wordpress Debug Mode: Yes/No

PHP Version

MySQL Version

Server software (e.g. Apache)

Must Use Plugins

Active Plugins

Current Theme

Theme Version

Child Theme: Yes/No

Number of Post Types

Number of queryable Post Types

Total Number of posts (any type)

Number of posts for each post type

Site Health summary

User Agent used by the user who give you the collected information


As you probably know the Site Health already gives a lot of information, but it includes too sensitive information as database name and database username.

And most of all, it misses information like number of pages, number of posts and custom posts.

However if you think the information included in the Site Health page are ok for your purpose, of course, you don't need to install this plugin.



== Installation ==

1. Upload the entire `website-information` folder to the `/wp-content/plugins/` directory or install it using the usual installation button in the Plugins administration page.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. After successful activation you will find the Website Information menu item in the admin menu.
4. All done. Good job!


== Changelog ==


= 0.0.6 =
* Added: database size

= 0.0.5 =
* Added: list of plugins and themes that need updates

= 0.0.4 =
* Added: PHP extensions
* Fixed: fatal error if WP < 5.2

= 0.0.3 =
* Added: number of posts for each post type

= 0.0.2 =
* Fix: PHP notices

= 0.0.1 =
* First release
