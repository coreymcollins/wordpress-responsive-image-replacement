window.WPResponsiveImageReplacement = window.WPResponsiveImageReplacement || {};

(function( window, document, $, app, undefined ) {

	var app = {};
	var breakpoint = {};

	breakpoint.refreshValue = function () {
		this.value = window.getComputedStyle( document.querySelector( 'body' ), ':before' ).getPropertyValue( 'content' ).replace( /['"]+/g, '' );
	};

	app.cache = function() {

		app.$ = {};
	};

	app.init = function() {
		app.cache();

		// Remove width and height attributes from images
		$( 'img' ).removeAttr( 'width' ).removeAttr( 'height' );
	};

	// Make this function globally available
	window.WPResponsiveImagesReplace = function( attachmentClass, newImageSize) {

		$( attachmentClass ).attr( 'src', $( attachmentClass ).data( newImageSize ) );
	}

	/**
	 * Get the breakpoint size on window resize
	 */
	window.WPResponsiveImagesGetBreakpointSize = function() {
		return breakpoint.value;
	}

	/**
	 * Set the breakpoint on resize
	 */
	window.setBreakpoint = function() {
		breakpoint.refreshValue();
	};

	jQuery(document).ready( app.init );

	return app;

})( window, document, jQuery, window.WPResponsiveImageReplacement );
