=== Responsive Menu ===
Contributors: ResponsiveMenu
Donate link: https://responsive.menu/donate
Tags: responsive, menu, responsive menu, mobile menu, wordpress responsive menu, wp responsive menu, tablet menu, mobile, tablet, 3 lines, 3 line, three line, three lines
Requires at least: 3.5.0
Tested up to: 4.6
Stable tag: 3.0.9
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This is a Highly Customisable Responsive Menu Plugin for WordPress

== Description ==

This is a Highly Customisable Responsive Menu Plugin for WordPress, with over 120 customisable options giving you a combination of 14,000 options!
<br /><br />
**Requires PHP 5.4+**, please ensure you have this installed before upgrading.
<br /><br />
With this plugin, you can edit the following and more:
<ul>
<li>Menu Title</li>
<li>Menu Title Image</li>
<li>Button Title</li>
<li>Button Title Image</li>
<li>Menu To Responsify</li>
<li>Media Query Breakpoint Width</li>
<li>CSS Options For Hiding Specific Elements</li>
<li>Menu Depth To Display</li>
<li>Top Location</li>
<li>Right Percentage Location</li>
<li>Line & Text Colour</li>
<li>Menu Button Background Colour</li>
<li>Absolute and Fixed Positioning</li>
<li>Menu Font</li>
<li>Menu Title Colour</li>
<li>Menu Title Hover Colour</li>
<li>Menu Title Background Colour</li>
<li>Menu Text Colour</li>
<li>Menu Text Hover Colour</li>
<li>Menu Background Colour</li>
<li>Menu Link Background Hover Colour</li>
<li>Menu Text Size</li>
<li>Menu Button Text Size</li>
<li>Menu Links Text Size</li>
<li>Choose Overlay or Push Animations</li>
<li>Slide Animation Speed</li>
<li>Ability to auto expand/hide sub-menus</li>
<li>Inclusion/Exclusion of Search Box</li>
<li>Choice of Positioning of Search Box</li>
<li>Transition speed</li>
<li>Slide Animation Speed</li>
<li>Menu Link Heights</li>
<li>Text Alignment</li>
<li>Choice of side to slide in from (left, right, top, bottom)</li>
<li>Choice to use inline/external stylesheets and scripts</li>
<li>Option to include JavaScript in footer</li>
<li>Option to remove CSS !important tags</li>
<li>Choice to Minify created CSS and JS files (saves up to 50% file space)</li>
<li>Choice to auto-close menu items on click (for single page sites)</li>
<li>Choice to replace 3 lines with an x on click</li>
<li>Minimum width of menu</li>
<li>Maximum width of menu</li>
<li>Choice to Auto Expand Parent Links</li>
<li>Choice to Ignore Clicks on Ancestor Links</li>
<li>Choice to Close Menu Automatically on Page Clicks</li>
<li>Choice to Specify Title Menu Link</li>
<li>Choice to Specify Title Menu Link Location</li>
<li>Ability to add custom HTML snippet inside the menu</li>
<li>Choice of location for custom HTML snippet inside the menu</li>
<li>Choice of using shortode or not</li>
<li>Ability to change the 3 lines height</li>
<li>Ability to change the 3 lines width</li>
<li>Ability to Export Options</li>
<li>Ability to Import Options</li>
<li>Ability to set sub menu arrow shape/image Options</li>
<li>Ability to set custom click trigger</li>
<li>Ability to push menu button with animation</li>
<li>Ability to change Current Page background hover colour</li>
<li>Ability to change Current Page Link hover colour</li>
<li>Ability to provide a custom walker option</li>
<li>Ability to choose to use transient caching or not</li>
<li>Ability to choose if menu is shown on left or right of screen</li>
<li>Ability to set theme location menu</li>
<li>Ability to reset to default</li>
<li>Ability to set menu text location</li>
<li>Ability to set menu text line height</li>
<li>Plus more...</li>
</ul>
The plugin creates a nice three-lined mobile menu button (or custom image if you choose) that users can click on to bring a slide out menu (from the left, right, top or bottom - again your choice), which is easily navigated.
<br /><br />
It is fully responsive if you have the viewport meta tag on your site, using media queries with the widths defined by you. It can be used as a responsive menu, mobile menu, tablet menu or full dedicated menu for your main site.
<br /><br />
It requires no shortcodes (although you can use them) or fancy php code to be inserted by yourself making it very easy to install and you can design it to look exactly as you want to or leave it with its default values to have it looking amazing in just a matter of seconds.
<br /><br />
You have the choice to include the stylesheets and scripts inline to avoid adding any extra HTTP requests to your site or through external stylesheets created by the plug-in. Either way, the code added is extremely small (only a little jQuery and CSS) and there is even an option to minify the output if you wish saving a further 50% on file space.
<br /><br />
It also includes the following functionality:
<ul>
<li>WPML/Polylang Support</li>
</ul>
If you decide to go Pro then you will also get the following functionality:
<ul>
<li>FontIcon Support for individual menu items</li>
<li>Button Animation Effects</li>
<li>Colour Opacity option</li>
<li>Header Bar</li>
<li>Single Menu Option</li>
</ul>
For more reasons to go Pro, please visit <a target="_blank" href="https://responsive.menu/why-go-pro/">this page</a>.
If you would like to see any other options added to the plugin or would like to help with translating the plugin into various languages then please email me or place them in a support ticket.
<br />

== Installation ==

1. Upload `responsive-menu` to the `/wp-content/plugins/` directory
2. Activate the plugin through the `Plugins` menu in WordPress
3. Set your options from the Responsive Menu admin area

Alternatively:

1. Login to your WordPress admin area
2. Search for `Responsive Menu`
3. Click install `Responsive Menu`
4. Activate through the `Plugins` menu in WordPress or when asked during installation
5. Set your options from the Responsive Menu admin area

== Frequently Asked Questions ==

1. Why do I get the error `Parse error: syntax error, unexpected '[' in /home/..../wp-content/plugins/responsive-menu/src/app/Routing/WpRouting.php on line 19`?

This is due to your PHP version not being high enough, you need at least PHP 5.4 for the plugin to work.

2. I am getting a message similar to `Fatal error: Uncaught exception 'Exception' with message 'parse error: failed at $hamburger-layer-color: ;`, why?

All you need to do is login to your WordPress admin, go to my plugin page and hit 'Update Options' to fix this.

To view our whole FAQ, please go to https://responsive.menu/faq/

== Screenshots ==

1. Fully customisable Admin Screen

2. Example Front End Menu Button

3. Example Front End Expanded Menu

== Changelog ==

= 3.0.9 (2nd August 2016) =
* **Requires PHP 5.4**
* Added current item border colour option
* Added current item border hover colour option
* Apply title link to title image
* Fixed transition bug with iPhone Safari iOS on links
* Fixed Import option bug
* Import native jquery-ui-core instead of externally
* Minor bug fixes

= 3.0.8 (25th July 2016) =
* **Requires PHP 5.4**
* Added placeholder search text colour option
* Improved update process (no longer need to login to admin to trigger)
* Bug fixes

= 3.0.7 (22nd July 2016) =
* **Requires PHP 5.4**
* Added preview option - Pro only
* Added search box text colour option
* Added search box background colour option
* Added search box border colour option
* Fixed header bar bug with disabled scrolling - PRO
* Fixed smooth scrolling issue on iOS with disabled scrolling - PRO
* Added string translation to Search text

= 3.0.6 (13th July 2016) =
* **Requires PHP 5.4**
* Improved Database Migration Scripts
* Changed sub-arrows to only show border on left edge
* Improved button title text spacing
* Improved PHP version checking process

= 3.0.5 (13th July 2016) =
* **Requires PHP 5.4**
* Fixed bug with push animation
* Improved PHP version check functionality
* Fixed issue where custom menu classes weren't being added

= 3.0.4 (9th July 2016) =
* **Requires PHP 5.4**
* Fixed bug with overwriting values with shortcode

= 3.0.3 (9th July 2016) =
* **Requires PHP 5.4**
* Fixed bug with custom trigger
* Updated Polylang Support
* Removed extend() function from Pimple Container as it was throwing security notices in VaultPress - thanks to Brin @ WinningWp.com

= 3.0.2 (8th July 2016) =
* **Requires PHP 5.4**
* Fixed issue with close on link click option
* Improved database migration scripts
* Updated default button size
* Improved License Checks (Pro)
* Removed tab memory (too resource intensive)
* Fixed Query Monitor "IF" error - thanks to KTS915
* Bug fixes

= 3.0.1 (7th July 2016) =
* **Requires PHP 5.4**
* Initial Bug Fixes
* Catch non-updated option errors
* Those using under PHP 5.4 will not crash their site but deactivate
* License Key Checking Fixed (Pro)

= 3.0.0 (5th July 2016) =
* **Requires PHP 5.4** - Please ensure you have it installed to work
* Version 3 released!
* Please login to your admin and hit 'Update Options' upon installing on all sites
* Awesome FontIcon integration
* Much Smoother Animations
* Button Animations
* And much, much more
* Many bug fixes
* Completely re-written from the ground up

= 2.8.9 (17th June 2016) =
* Last Version 2 release with beta notice

= 2.8.8 (12th May 2016) =
* Added class to body when menu is opened

= 2.8.7 =
* Added absolute paths to file inclusions
* Added ability to set menu title text location (top, bottom, left, right)
* Added ability to set menu title text line height (useful for above)
* Fixed width on custom menu images for consistency

= 2.8.6 (28th January 2016) =
* Added Menu Title Link to WPML
* Fixed issue with fixed menu widths and push animation

= 2.8.5 (11th Sept 2015) =
* Fixed menu not opening on first click bug
* Added empty check to additional html content

= 2.8.4 (19th Aug 2015) =
* Fixed middle mouse scroll button bug

= 2.8.3 (13th Aug 2015) =
* Fixed bug with accordion sub arrows not re-setting correctly

= 2.8.2 (13th Aug 2015) =
* Fixed bug with push animation from right side

= 2.8.1 (27th July 2015) =
* Import/Export issue with geometric shapes fixed
* Default export.xml file added
* Other bugfixes

= 2.8 (26th July 2015) =
* Launched Pro Version 1.0 with:
	- Ability to use as the only menu on the site (responsive and desktop)
	- Option to only show on mobile using wp_is_mobile()
	- Various colour themes
	- Header bar creator
	- Menu Auto-Height option
	- Background scrolling disabled option
* Added "Reset to default" option
* Removed Metatag check
* Improved transient caching
* Bugfixes

= 2.7 (14th June 2015) =
* Added Navigation Tabs for Admin Pages -thanks to mkdgs for this!
* Bugfixes

= 2.6 (26th May 2015) =
* Added Theme Location options - useful for Polylang - Thanks to mkdgs for this
* Fixed Issue with Auto-Expanding Links
* Fixed Issue with Data Folders Incorrect Location

= 2.5 (20th May 2015) =
* Fixed issue with duplicate sub menu arrows
* Added option to set if menu is shown on left or right
* Fixed issue with custom sub menu classes
* Button now compatible with Apple Voiceover
* Changed "X" close icon to the math symbol "x" - Thanks to patlog for the idea!
* Added many more shortcode options as below:

    - "title"
    - "title_link"
    - "title_open"
    - "title_img"
    - "html"
    - "html_loc"
    - "search_loc"
    - "btn_img"
    - "btn_img_clicked"
    - "btn_title"

= 2.4 (18th Feb 2015) =

* Added option to turn transient caching off as it causes issues with active link colours etc.
* Made Menu clear the transient caches and rebuild menu on Menu/Page/Post updates.

= 2.3 (16th Feb 2015) =

* Added ability to set custom click menu trigger
* Added ability to set custom walker function - Thanks to Mickael Desgranges for this
* Added option to push menu button with animation
* Added option to change Current Page background hover colour
* Added option to change Current Page Link hover colour
* Fixed issue with accordion menu not retracting
* Enabled 0 values for animation speed to in affect remove animation
* Added easy view of Shortcode options in admin
* Added ability to place shortcodes in extra html content - Thanks to Mickael Desgranges for this
* Leveraged WordPress persistent transient caching to significantly increase load speed

= 2.2 (29th Oct 2014) =

* Added Ability to Export Options
* Added Ability to Import Options
* Added option to change 3 lines width
* Added option to change 3 lines height
* Added option to change 3 lines margin
* Added option to change sub arrows using HTML shape or Image
* Added option to change the click menu image once clicked
* Sub Menu Animation added to improve smoothness
* Accordion sub menu animation option
* Added shortcode option to change menu (use "RM" argument) eg. [responsive-menu RM="footer-menu"]
* Minor bug fixes

= 2.1 (17th Aug 2014) =

* Basic shortcode support added - To use, tick the option in the admin and use the shortcode [responsive-menu]
* Multiple bug fixes

= 2.0 (13th Aug 2014) =

*** WE HAVE REACHED OVER 50 CUSTOMISABLE OPTIONS! ***

* Complete overhaul of the codebase
* Increased menu depth to 5
* Added Spanish Translation - Massive thanks to Andrew @ <a href="http://www.webhostinghub.com">WebHostingHub</a> for this!
* 'Double Click' menu bug fixed
* Various bug fixes
* Added Choice to Auto Expand Parent Links
* Added Choice to Ignore Clicks on Ancestor Links
* Added Choice to Close Menu Automatically on Page Clicks
* Added Top and Bottom Slide Options
* Added Option to set a Maximum Width for the menu
* Added Choice of Positioning of Search Box
* Added Choice to Specify Title Menu Link
* Added Choice to Specify Title Menu Link Location
* Added Ability to add custom HTML snippet inside the menu
* Added Choice of location for custom HTML snippet inside the menu
* Detect if WP Login bar is active and adjust accordingly

= 1.9 (5th Apr 2014) =

* Changed where custom CSS/JS files are stored. If you have issues when upgrading, please just click "Update Options" from admin and this should return back to normal.
* Added option to include scripts in footer
* Added ability to upload custom menu button image to replace the 3 lines
* Added ability to close menu on each link click (good for single page sites)
* Added ability to minify output (saving 50% on file size)
* Remove title section if title and image are empty
* Added ability to change 3 lines to an x when clicked
* Added ability to set the minimum width of the menu
* Added Croatian Translation - Massive thanks to <a href="https://www.facebook.com/pages/Neverone-design/490262371018076">Neverone Design</a> for this!

= 1.8 (26th Mar 2014) =

* Added option to include styles/scripts externally/internally
* Added WPML Support
* Added internationalisation functionality, awaiting translations
* Added ability to choose which side the menu slides in from
* Minor Code Improvements

= 1.7 (13th Mar 2014) =

* Ability to auto expand/hide sub-menus
* Inclusion/Exclusion of Search Box
* Transition speed
* Slide Animation Speed
* Menu Link Heights
* Text Alignment
* Removed potential jQuery conflicts
* Minor Code Improvements

= 1.6 (6th Mar 2014) =

* Added Animation Options Section
* Added Animation Speed Customisation Option
* Added Choice Of Slide Animation (Overlay or Push)
* Added Menu Title Background Colour Customisation Option
* Added Menu Title Font Size Customisation Option
* Added Click Menu Title Font Size Customisation Option
* Added Menu Links Font Size Customisation Option
* Removed Image Size Restriction
* Minor Code Improvements

= 1.5 (4th Mar 2014) =

* Added Menu Title Colour Customisation Option
* Added Menu Title Hover Colour Customisation Option
* Added Menu Text Colour Customisation Option
* Added Menu Text Hover Colour Customisation Option
* Added Menu Background Colour Customisation Option
* Added Menu Link Background Hover Colour Customisation Option
* Minor Code Improvements

= 1.4 (2nd Mar 2014) =

* Improved menu sliding animation.
* Fixed bug where the menu wouldn't retract on re-size.
* Removed the use of namespaces to support pre PHP 5.3 systems.

= 1.3 (1st Mar 2014) =

* Added ability to upload image for menu title.
* Added ability to switch between fixed and non-fixed positioning.
* Added ability to change menu font.
* Minor updates.

= 1.2 (28th Feb 2014) =

* Added support to include all site menus.

= 1.1 (25th Feb 2014) =

* Added transparent menu background option.

= 1.0 (22nd Feb 2014) =

* Initial Version Released.

== Upgrade Notice ==

= 2.8.9 =
Requires PHP 5.4  - DO NOT upgrade if you do not have this installed.

= 2.8.8 =
Requires PHP 5.4  - DO NOT upgrade if you do not have this installed.
