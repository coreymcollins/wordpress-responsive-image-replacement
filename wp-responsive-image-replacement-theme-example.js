/**
 * A sample file to demonstrate how to use the
 * WP Responsive Image Replacement functionality
 *
 * WPResponsiveImagesReplace( '.attachment-full', 'img-size-medium' );
 * This function takes two values: the attachment class and the image size
 * which you wish to display at your specificed breakpoints
 *
 * '.attachment-full' is the designated WP class given to images using the "full" image size
 * WP automatically creates and adds a class for your custom image sizes.
 * So, if you create an image size called "large-hero", you would want to target the class ".attachment-large-hero"
 *
 * 'img-size-medium' is a custom data attribute added by the WP Responsive Image Replacement plugin
 * which contains the URL for the medium-size WP image
 * So if you create an image size called "large-hero", your data-attribute name would be "img-size-large-hero"
 */

( function($) {

	// Listen for a window resize
	$(window).resize(function() {

		// Set our breakpoint value
		window.setBreakpoint();

		// Check to see if our breakpoint value is tablet-landscape
		// If it is, replace the image
		if ( 'tablet-landscape' == WPResponsiveImagesGetBreakpointSize() ) {

			// Set the image to a medium size at the tablet-landscape breakpoint
			WPResponsiveImagesReplace( '.attachment-full', 'img-size-medium' );
		} else if ( 'desktop' == WPResponsiveImagesGetBreakpointSize() ) {

			// Return to the larger image size at the desktop breakpoint
			WPResponsiveImagesReplace( '.attachment-full', 'img-size-full' );
		}

	}).resize();

})( jQuery );
