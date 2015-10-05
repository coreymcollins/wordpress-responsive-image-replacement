<?php
/**
 * Plugin Name: WordPress Responsive Images
 * Plugin URI:  http://coreymcollins.com
 * Description: Display beautiful responsive images at your own breakpoints.
 * Version:     0.1.0
 * Author:      Corey M Collins
 * Author URI:  http://coreymcollins.com
 * Donate link: http://coreymcollins.com
 * License:     GPLv2
 * Text Domain: wds-responsive-images
 * Domain Path: /languages
 */

/**
 * Copyright (c) 2015 Corey M Collins (email : coreymcollins@gmail.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 * Built using generator-plugin-wp
 */

/**
 * Bootstrap CMB2
 * No need to check versions or if CMB2 is already loaded... the init file does that already!
 *
 * Check to see if CMB2 exists, and either bootstrap it or add a notice that it is missing
 */
if ( file_exists( dirname( __FILE__ ) . '/includes/CMB2/init.php' ) ) {
	require_once 'includes/CMB2/init.php';
} else {
	add_action( 'admin_notices', 'cmb2_wp_responsive_images_missing_cmb2' );
}

/**
 * Add an error notice to the dashboard if CMB2 is missing from the plugin
 *
 * @return void
 */
function cmb2_wp_responsive_images_missing_cmb2() { ?>
<div class="error">
	<p><?php _e( 'CMB2 Example Plugin is missing CMB2!', 'wds-responsive-images' ); ?></p>
</div>
<?php }

/**
 * Autoloads files with classes when needed
 *
 * @since  0.1.0
 * @param  string $class_name Name of the class being requested
 * @return  null
 */
function wp_responsive_images_autoload_classes( $class_name ) {
	if ( 0 !== strpos( $class_name, 'WDSRI_' ) ) {
		return;
	}

	$filename = strtolower( str_ireplace(
		array( 'WDSRI_', '_' ),
		array( '', '-' ),
		$class_name
	) );

	WP_Responsive_Images::include_file( $filename );
}
spl_autoload_register( 'wp_responsive_images_autoload_classes' );


/**
 * Main initiation class
 *
 * @since  0.1.0
 * @var  string $version  Plugin version
 * @var  string $basename Plugin basename
 * @var  string $url      Plugin URL
 * @var  string $path     Plugin Path
 */
class WP_Responsive_Images {

	/**
	 * Current version
	 *
	 * @var  string
	 * @since  0.1.0
	 */
	const VERSION = '0.1.0';

	/**
	 * URL of plugin directory
	 *
	 * @var string
	 * @since  0.1.0
	 */
	protected $url = '';

	/**
	 * Path of plugin directory
	 *
	 * @var string
	 * @since  0.1.0
	 */
	protected $path = '';

	/**
	 * Plugin basename
	 *
	 * @var string
	 * @since  0.1.0
	 */
	protected $basename = '';

	/**
	 * Singleton instance of plugin
	 *
	 * @var WP_Responsive_Images
	 * @since  0.1.0
	 */
	protected static $single_instance = null;

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @since  0.1.0
	 * @return WP_Responsive_Images A single instance of this class.
	 */
	public static function get_instance() {
		if ( null === self::$single_instance ) {
			self::$single_instance = new self();
		}

		return self::$single_instance;
	}

	/**
	 * Sets up our plugin
	 *
	 * @since  0.1.0
	 */
	protected function __construct() {
		$this->basename = plugin_basename( __FILE__ );
		$this->url      = plugin_dir_url( __FILE__ );
		$this->path     = plugin_dir_path( __FILE__ );

		$this->plugin_classes();
		$this->hooks();

		// Include our settings page
		require_once 'wp-responsive-images-settings.php';
	}

	/**
	 * Attach other plugin classes to the base plugin class.
	 *
	 * @since 0.1.0
	 * @return  null
	 */
	function plugin_classes() {
		// Attach other plugin classes to the base plugin class.
		// $this->admin = new WDSRI_Admin( $this );
	}

	/**
	 * Add hooks and filters
	 *
	 * @since 0.1.0
	 * @return null
	 */
	public function hooks() {
		register_activation_hook( __FILE__, array( $this, '_activate' ) );
		register_deactivation_hook( __FILE__, array( $this, '_deactivate' ) );

		add_action( 'init', array( $this, 'init' ) );

		// Add our image attributes
		add_filter( 'wp_get_attachment_image_attributes', array( $this, 'add_img_attributes' ), 10, 2 );

		// Enqueue our scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );

		// Load our custom styles in the footer if they exist
		add_action( 'wp_footer', array( $this, 'custom_breakpoints' ) );
	}

	/**
	 * Activate the plugin
	 *
	 * @since  0.1.0
	 * @return null
	 */
	function _activate() {
		// Make sure any rewrite functionality has been loaded
		flush_rewrite_rules();
	}

	/**
	 * Deactivate the plugin
	 * Uninstall routines should be in uninstall.php
	 *
	 * @since  0.1.0
	 * @return null
	 */
	function _deactivate() {}

	/**
	 * Init hooks
	 *
	 * @since  0.1.0
	 * @return null
	 */
	public function init() {
		if ( $this->check_requirements() ) {
			load_plugin_textdomain( 'wds-responsive-images', false, dirname( $this->basename ) . '/languages/' );
		}
	}

	/**
	 * Check that all plugin requirements are met
	 *
	 * @since  0.1.0
	 * @return boolean
	 */
	public static function meets_requirements() {
		// Do checks for required classes / functions
		// function_exists('') & class_exists('')

		// We have met all requirements
		return true;
	}

	/**
	 * Check if the plugin meets requirements and
	 * disable it if they are not present.
	 *
	 * @since  0.1.0
	 * @return boolean result of meets_requirements
	 */
	public function check_requirements() {
		if ( ! $this->meets_requirements() ) {

			// Add a dashboard notice
			add_action( 'all_admin_notices', array( $this, 'requirements_not_met_notice' ) );

			// Deactivate our plugin
			deactivate_plugins( $this->basename );

			return false;
		}

		return true;
	}

	/**
	 * Adds a notice to the dashboard if the plugin requirements are not met
	 *
	 * @since  0.1.0
	 * @return null
	 */
	public function requirements_not_met_notice() {
		// Output our error
		echo '<div id="message" class="error">';
		echo '<p>' . sprintf( __( 'WDS Responsive Images is missing requirements and has been <a href="%s">deactivated</a>. Please make sure all requirements are available.', 'wds-responsive-images' ), admin_url( 'plugins.php' ) ) . '</p>';
		echo '</div>';
	}

	/**
	 * Magic getter for our object.
	 *
	 * @since  0.1.0
	 * @param string $field
	 * @throws Exception Throws an exception if the field is invalid.
	 * @return mixed
	 */
	public function __get( $field ) {
		switch ( $field ) {
			case 'version':
				return self::VERSION;
			case 'basename':
			case 'url':
			case 'path':
				return $this->$field;
			default:
				throw new Exception( 'Invalid '. __CLASS__ .' property: ' . $field );
		}
	}

	/**
	 * Include a file from the includes directory
	 *
	 * @since  0.1.0
	 * @param  string  $filename Name of the file to be included
	 * @return bool    Result of include call.
	 */
	public static function include_file( $filename ) {
		$file = self::dir( 'includes/'. $filename .'.php' );
		if ( file_exists( $file ) ) {
			return include_once( $file );
		}
		return false;
	}

	/**
	 * This plugin's directory
	 *
	 * @since  0.1.0
	 * @param  string $path (optional) appended path
	 * @return string       Directory and path
	 */
	public static function dir( $path = '' ) {
		static $dir;
		$dir = $dir ? $dir : trailingslashit( dirname( __FILE__ ) );
		return $dir . $path;
	}

	/**
	 * This plugin's url
	 *
	 * @since  0.1.0
	 * @param  string $path (optional) appended path
	 * @return string       URL and path
	 */
	public static function url( $path = '' ) {
		static $url;
		$url = $url ? $url : trailingslashit( plugin_dir_url( __FILE__ ) );
		return $url . $path;
	}

	/**
	 * Filter attributes for the current gallery image tag.
	 *
	 * @param array   $atts       Gallery image tag attributes.
	 * @param WP_Post $attachment WP_Post object for the attachment.
	 */
	public function add_img_attributes( $atts, $attachment ) {

		// Grab our list of image sizes
		$image_sizes = get_intermediate_image_sizes();

		// Bail if we somehow have no image sizes
		if ( empty( $image_sizes ) ) {
			return;
		}

		// Add the full size to the image size array
		$image_sizes[] = 'full';

		// Loop through our image sizes and add each as a data attribute
		foreach ( $image_sizes as $size ) {

			// Grab the image source
			$image_src = wp_get_attachment_image_src( $attachment->ID, $size );

			// Output the size as a data attribute
			if ( ! empty ( $image_src[0] ) ) {
				$atts["data-img-size-$size"] = $image_src[0];
			}

			// Add the full-size URL to a data attribute
			$atts["data-img-full-size"] = wp_get_attachment_url( $attachment->ID );
		}

	    return $atts;
	}

	/**
	 * Enqueue our scripts
	 */
	public function scripts() {

		// Enqueue JS
		wp_enqueue_script( 'responsive-image-replacement-scripts', $this->url . 'assets/js/responsive-image-replacement.js', array( 'jquery' ), self::VERSION, true );

		// Grab our settings
		$wds_responsive_images_settings = cmb2_get_option( 'wp_responsive_images_options', 'wds-responsive-images-breakpoints' );

		// If there ARE settings, don't enqueue our homebaked styles/breakpoints
		if ( ! $wds_responsive_images_settings ) {
			wp_enqueue_style( 'responsive-images-replacement-styles', $this->url . 'style.css', array(), self::VERSION );
		}
	}

	/**
	 * Add our custom breakpoints to the footer
	 */
	public function custom_breakpoints() {

		// Grab our settings
		$wds_responsive_images_settings = cmb2_get_option( 'wp_responsive_images_options', 'wds-responsive-images-breakpoints' );

		// If we don't have settings, just stop
		if ( ! $wds_responsive_images_settings ) {
			return;
		}

		echo '<style type="text/css">'; ?>

			body:before {
				display: none;
			}

		<?php // Loop through each settings group and display it as a pseudo element
		foreach( $wds_responsive_images_settings as $breakpoint ) : ?>
			@media (min-width: <?php echo $breakpoint['breakpoint-dimensions']; ?>px ) {
				body:before {
					content: "<?php echo $breakpoint['breakpoint-name']; ?>";
				}
			}
		<?php endforeach;

		echo '</style>';
	}
}

/**
 * Grab the WP_Responsive_Images object and return it.
 * Wrapper for WP_Responsive_Images::get_instance()
 *
 * @since  0.1.0
 * @return WP_Responsive_Images  Singleton instance of plugin class.
 */
function wp_responsive_images() {
	return WP_Responsive_Images::get_instance();
}

// Kick it off
wp_responsive_images();
