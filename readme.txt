=== Plugin Name ===
Contributors: kiranantony
Donate link: https://www.paypal.me/kiranantony
Tags: slideshow, images, jquery cycle2, wp-cycle,wp cycle2,responsive slider, wp cycle
Requires at least: 3.0
Tested up to: 4.6
Stable tag: 1.2.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin creates an image slideshow in your theme, using the jQuery Cycle2 plugin. You can upload/delete images via the administration panel.
== Description ==

The Cycle Responsive Slider plugin allows you to upload images from your computer, which will then be used to generate a jQuery Cycle2 Plugin slideshow of the images.

Each image can also be given a URL which, when the image is active in the slideshow, will be used as an anchor wrapper around the image, turning the image into a link to the URL you specified.  The slideshow is set to pause when the user hovers over the slideshow images, giving them ample time to click the link.

Images can also be deleted via the plugins Administration page.

== Installation ==

1. Upload the entire `cycle-responsive-slider` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Configure the plugin, and upload/edit/delete images via the "WP-Cycle" menu within the "Media" tab
1. Place `<?php wp_cycle(); ?>` in your theme where you want the slideshow to appear
1. Alternatively, you can use the shortcode [wp_cycle] or [wp-cycle] in a post or page to display the slideshow.

== Frequently Asked Questions ==

= My images won't upload. What should I do? =

The plugin uses built in WordPress functions to handle image uploading. Therefore, you need to have [correct permissions](http://codex.wordpress.org/Changing_File_Permissions "Changing File Permissions") set for your uploads directory.

Also, a file that is not an image, or an image that does not meet the minimum height/width requirements, will not upload. Images larger than the dimensions set in the Settings of this plugin will be scaled down to fit, but images smaller than the dimensions set in the Settings will NOT be scaled up. The upload will fail and you will be asked to try again with another image.

You need to verify that your upload directory is properly set. Some hosts screw this up, so you'll need to check. Go to "Settings" -> "Miscellaneous" and find the input box labeled "Store uploads in this folder". Unless you are absolutely sure this needs to be something else, this value should be exactly this (without the quotes) "wp-content/uploads". If it says "/wp-content/uploads" then the plugin will not function correctly. No matter what, the value of this field should never start with a slash "/". It expects a path relative to the root of the WordPress installation.
Finally, you have to check the folder "resized" inside uploads. you need to have [correct permissions].
= I'm getting an error message that I don't understand. What should I do? =

Please wordpress Support Forum. This plugin is now relatively stable, so if you are experiencing problems that you would like me to diagnose and fix, please use my support form.

As much as I would like to, in most cases, I cannot provide free support.

= How can I style the slideshow further? =
In the settings of the plugin, you're able to set a custom DIV ID for the slideshow. Use that DIV ID to style the slideshow however you want using CSS.

= In what order are the images shown during the slideshow? =

Chronologically, from the time of upload. For instance, the first image you upload will be the first image in the slideshow. The last image will be the last, etc.

= Can I reorder the images? =

Yes, You can drag and sort the image from the admin section. Optionally A random Order Also Available.

= Can I rotate anything other than images with this plugin? =

No. This version is an image slideshow. Enjoy it for what it is. 



== Changelog ==

= 1.0 =
* Initial Release
= 1.1 =
* Added support to `<?php wp_cycle(); ?>` function
* Added Widget Support
== 1.2 =
* Improved Code Base (Object Oriented) 
* Solved Issues with loading large number of images
* Solved Issue With Multiple Instance of the slider in a sinlge page
== 1.2.1 =
* Some Bug Fixes