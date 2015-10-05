# WordPress Responsive Image Replacement #
**Contributors:**      Corey M Collins
**Donate link:**       http://coreymcollins.com
**Tags:**
**Requires at least:** 4.3
**Tested up to:**      4.3
**Stable tag:**        0.1.0
**License:**           GPLv2
**License URI:**       http://www.gnu.org/licenses/gpl-2.0.html

## Description ##

Display beautiful responsive images at your own breakpoints.

## Installation ##

### Manual Installation ###

1. Upload the entire `/wordpress-responsive-image-replacement` directory to the `/wp-content/plugins/` directory.
2. Activate WordPress Responsive Image Replacement through the 'Plugins' menu in WordPress.

## Usage Instructions ##

There are default breakpoints included with the plugin if you do not wish to use your own.  The default breakpoints are:
<strong>large-desktop:</strong> 1680px
<strong>desktop:</strong> 1366px
<strong>tablet-landscape:</strong> 1024px
<strong>wp-admin-bar:</strong> 783px
<strong>tablet-portrait:</strong> 768px
<strong>phone-landscape:</strong> 640px
<strong>phone-portrait:</strong> 360px

If you wish to add your own custom breakpoints, they may be added on the plugin settings page.

<hr />

Once your breakpoints are in place, the JavaScript needs to be initalized.  You will find an example .js file in the main plugin directory (`wp-responsive-image-replacement-theme-example.js`).

Below is an example of how to adjust the image size for breakpoints:

`// Listen for a window resize
$(window).resize(function() {

	// Set our breakpoint value
	window.setBreakpoint();

	// Check to see if our breakpoint value is large-desktop
	// If it is, replace the image
	if ( 'large-desktop' == WPResponsiveImagesGetBreakpointSize() ) {

		// Set the image to a medium size at the large-desktop breakpoint
		WPResponsiveImagesReplace( '.attachment-some-other-size', 'img-size-medium' );
	} else if ( 'phone-landscape' == WPResponsiveImagesGetBreakpointSize() ) {

		// Return to the larger image size at the another-desktop breakpoint
		WPResponsiveImagesReplace( '.attachment-some-other-size', 'img-size-full' );
	}

}).resize();`

## Changelog ##

### 0.1.0 ###
* First release
