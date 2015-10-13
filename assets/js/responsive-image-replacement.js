window.WPResponsiveImageReplace = window.WPResponsiveImageReplace || {};

(function( window, document, $, app, undefined ) {

	var breakpoint = {};

	breakpoint.refreshValue = function () {
		this.value = window.getComputedStyle( document.querySelector( 'body' ), ':before' ).getPropertyValue( 'content' ).replace( /['"]+/g, '' );
	};

	app.cache = function() {

		app.$ = {};
	};

	app.init = function() {
		app.cache();

		// Set our initial breakpoint
		app.setBreakpoint();
	};

	// Make this function globally available
	app.doReplacement = function( attachmentClass, newImageSize) {

		// Remove width and height attributes from images
		$( attachmentClass ).removeAttr( 'width' ).removeAttr( 'height' ).attr( 'src', $( attachmentClass ).data( newImageSize ) );
	};

	/**
	 * Get the breakpoint size on window resize
	 */
	app.getGetBreakpointSize = function() {
		return breakpoint.value;
	};

	/**
	 * Set the breakpoint on resize
	 */
	app.setBreakpoint = function() {
		breakpoint.refreshValue();
		return breakpoint.value;
	};

	$( app.init );

})( window, document, jQuery, window.WPResponsiveImageReplace );
