<?php
/**
 * CMB2 Theme Options
 * @version 0.1.0
 */
class Myprefix_Admin {
	/**
 	 * Option key, and option page slug
 	 * @var string
 	 */
	private $key = 'wp_responsive_images_options';
	/**
 	 * Options page metabox id
 	 * @var string
 	 */
	private $metabox_id = 'wp_responsive_images_option_metabox';
	/**
	 * Options Page title
	 * @var string
	 */
	protected $title = '';
	/**
	 * Options Page hook
	 * @var string
	 */
	protected $options_page = '';
	/**
	 * Constructor
	 * @since 0.1.0
	 */
	public function __construct() {
		// Set our title
		$this->title = __( 'WP Responsive Images', 'wp_responsive_images' );
	}
	/**
	 * Initiate our hooks
	 * @since 0.1.0
	 */
	public function hooks() {
		add_action( 'admin_init', array( $this, 'init' ) );
		add_action( 'admin_menu', array( $this, 'add_options_page' ) );
		add_action( 'cmb2_admin_init', array( $this, 'add_options_page_metabox' ) );
	}
	/**
	 * Register our setting to WP
	 * @since  0.1.0
	 */
	public function init() {
		register_setting( $this->key, $this->key );
	}
	/**
	 * Add menu options page
	 * @since 0.1.0
	 */
	public function add_options_page() {
		$this->options_page = add_submenu_page( 'options-general.php', $this->title, $this->title, 'manage_options', $this->key, array( $this, 'admin_page_display' ) );
		// Include CMB CSS in the head to avoid FOUT
		add_action( "admin_print_styles-{$this->options_page}", array( 'CMB2_hookup', 'enqueue_cmb_css' ) );
	}
	/**
	 * Admin page markup. Mostly handled by CMB2
	 * @since  0.1.0
	 */
	public function admin_page_display() {
		?>
		<div class="wrap cmb2-options-page <?php echo $this->key; ?>">
			<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
			<?php cmb2_metabox_form( $this->metabox_id, $this->key ); ?>
		</div>
		<?php
	}
	/**
	 * Add the options metabox to the array of metaboxes
	 * @since  0.1.0
	 */
	function add_options_page_metabox() {
		$cmb = new_cmb2_box( array(
			'id'         => $this->metabox_id,
			'hookup'     => false,
			'cmb_styles' => false,
			'show_on'    => array(
				// These are important, don't remove
				'key'    => 'options-page',
				'value'  => array( $this->key, )
			),
		) );

		$cmb->add_field( array(
		    'name' => __( 'Default Breakpoints', 'wds-responsive-images' ),
		    'desc' => __( 'The following are the default breakpoints used with WP Responsive Images:<br />
		    	large-desktop: 1680px<br />
				desktop: 1366px<br />
				tablet-landscape: 1024px<br />
				wp-admin-bar: 783px<br />
				tablet-portrait: 768px<br />
				phone-landscape: 640px<br />
				phone-portrait: 360px', 'wds-responsive-images' ),
		    'type' => 'title',
		    'id'   => 'breakpoint-defaults'
		) );

		// Set our CMB2 fields
		$group_field_id = $cmb->add_field( array(
		    'id'          => 'wds-responsive-images-breakpoints',
		    'type'        => 'group',
		    'description' => __( 'Set a custom set of breakpoint names and dimensions.  Order from largest to smallest.', 'wds-responsive-images' ),
		    'options'     => array(
		        'group_title'   => __( 'Custom Breakpoint {#}', 'wds-responsive-images' ), // since version 1.1.4, {#} gets replaced by row number
		        'add_button'    => __( 'Add Custom Breakpoint', 'wds-responsive-images' ),
		        'remove_button' => __( 'Remove Custom Breakpoint', 'wds-responsive-images' ),
		        'sortable'      => true, // beta
		    ),
		) );

		// Id's for group's fields only need to be unique for the group. Prefix is not needed.
		$cmb->add_group_field( $group_field_id, array(
		    'name' => __( 'Breakpoint Name', 'wds-responsive-images' ),
		    'id'   => 'breakpoint-name',
		    'type' => 'text_medium',
		) );

		$cmb->add_group_field( $group_field_id, array(
		    'name'        => __( 'Breakpoint Dimensions (pixel width)', 'wds-responsive-images' ),
		    'description' => 'px',
		    'id'          => 'breakpoint-dimensions',
		    'type'        => 'text_small',
		) );
	}
	/**
	 * Public getter method for retrieving protected/private variables
	 * @since  0.1.0
	 * @param  string  $field Field to retrieve
	 * @return mixed          Field value or exception is thrown
	 */
	public function __get( $field ) {
		// Allowed fields to retrieve
		if ( in_array( $field, array( 'key', 'metabox_id', 'title', 'options_page' ), true ) ) {
			return $this->{$field};
		}
		throw new Exception( 'Invalid property: ' . $field );
	}
}
/**
 * Helper function to get/return the Myprefix_Admin object
 * @since  0.1.0
 * @return Myprefix_Admin object
 */
function wp_responsive_images_admin() {
	static $object = null;
	if ( is_null( $object ) ) {
		$object = new Myprefix_Admin();
		$object->hooks();
	}
	return $object;
}
/**
 * Wrapper function around cmb2_get_option
 * @since  0.1.0
 * @param  string  $key Options array key
 * @return mixed        Option value
 */
function wp_responsive_images_get_option( $key = '' ) {
	return cmb2_get_option( wp_responsive_images_admin()->key, $key );
}
// Get it started
wp_responsive_images_admin();