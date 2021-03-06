# WordPress Responsive Image Replacement #
**Contributors:**      Corey M Collins<br />
**Donate link:**       http://coreymcollins.com<br />
**Tags:**              responsive design, responsive, responsive images, images, image replacement<br />
**Requires at least:** 4.3<br />
**Tested up to:**      4.3<br />
**Stable tag:**        0.1.0<br />
**License:**           GPLv2<br />
**License URI:**       http://www.gnu.org/licenses/gpl-2.0.html

## Description ##

Display beautiful responsive images at your own breakpoints.

## Installation ##

### Manual Installation ###

1. Upload the entire `/wordpress-responsive-image-replacement` directory to the `/wp-content/plugins/` directory.
2. Activate WordPress Responsive Image Replacement through the 'Plugins' menu in WordPress.

## Usage Instructions ##

This plugin utilizes the built-in classes applied to images by WordPress.  For instance, if you are displaying an image at thumbnail size, WordPress adds a class of "attachment-thumbnail" to the image.  The snippet below looks for specific classes applied to images and replaces their `img src` values at the specified breakpoints.

There are default breakpoints included with the plugin if you do not wish to use your own.  The default breakpoints are:<br />
<strong>phone-portrait:</strong> 360px<br />
<strong>phone-landscape:</strong> 640px<br />
<strong>tablet-portrait:</strong> 768px<br />
<strong>wp-admin-bar:</strong> 783px<br />
<strong>tablet-landscape:</strong> 1024px<br />
<strong>desktop:</strong> 1366px<br />
<strong>large-desktop:</strong> 1680px

If you wish to add your own custom breakpoints, they may be added on the plugin settings page.

<hr />

Once your breakpoints are in place, the JavaScript needs to be initalized.  You will find an example .js file in the main plugin directory (`wp-responsive-image-replacement-theme-example.js`).

Below is an example of how to adjust the image size for breakpoints:

<pre>
// Listen for a window resize
$(window).resize(function() {

	// Set our breakpoint value
	window.setBreakpoint();

	// Check to see if our breakpoint value is large-desktop
	// If it is, replace the image
	if ( 'large-desktop' == WPResponsiveImagesGetBreakpointSize() ) {

		// Set the image to a medium size at the large-desktop breakpoint
		WPResponsiveImagesReplace( '.attachment-some-other-size', 'img-size-medium' );
	} else if ( 'phone-landscape' == WPResponsiveImagesGetBreakpointSize() ) {

		// Return to the larger image size at the phone-landscape breakpoint
		WPResponsiveImagesReplace( '.attachment-some-other-size', 'img-size-full' );
	}

}).resize();
</pre>

## Changelog ##

### 0.1.0 ###
* First release
