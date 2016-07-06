<?php
/**
 * Base class for handling sections. Sections house groups of controls.  Multiple sections can
 * be added to a manager.
 *
 * @package    ButterBean
 * @subpackage Admin
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2015-2016, Justin Tadlock
 * @link       https://github.com/justintadlock/butterbean
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Base section class.
 *
 * @since  1.0.0
 * @access public
 */
class ButterBean_Section {

	/**
	 * Stores the project details manager object.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    object
	 */
	public $manager;

	/**
	 * Name/ID of the section.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $name = '';

	/**
	 * The type of section.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $type = 'default';

	/**
	 * Dashicons icon for the section.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $icon = 'dashicons-admin-generic';

	/**
	 * Label for the section.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $label = '';

	/**
	 * Description for the section.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $description = '';

	/**
	 * Priority (order) the section should be output.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    int
	 */
	public $priority = 10;

	/**
	 * The number of instances created.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    int
	 */
	protected static $instance_count = 0;

	/**
	 * The instance of the current section.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    int
	 */
	public $instance_number;

	/**
	 * A callback function for deciding if a section is active.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    callable
	 */
	public $active_callback = '';

	/**
	 * A user role capability required to show the section.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string|array
	 */
	public $capability = '';

	/**
	 * A feature that the current post type must support to show the section.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $post_type_supports = '';

	/**
	 * A feature that the current theme must support to show the section.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string|array
	 */
	public $theme_supports = '';

	/**
	 * Stores the JSON data for the manager.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    array()
	 */
	public $json = array();

	/**
	 * Creates a new section object.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  object  $manager
	 * @param  string  $section
	 * @param  array   $args
	 * @return void
	 */
	public function __construct( $manager, $name, $args = array() ) {

		foreach ( array_keys( get_object_vars( $this ) ) as $key ) {

			if ( isset( $args[ $key ] ) )
				$this->$key = $args[ $key ];
		}

		$this->manager = $manager;
		$this->name    = $name;

		// Increment the instance count and set the instance number.
		self::$instance_count += 1;
		$this->instance_number = self::$instance_count;

		// Set the active callback function if not set.
		if ( ! $this->active_callback )
			$this->active_callback = array( $this, 'active_callback' );
	}

	/**
	 * Enqueue scripts/styles for the section.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enqueue() {}

	/**
	 * Returns the json array.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return array
	 */
	public function get_json() {
		$this->to_json();

		return $this->json;
	}

	/**
	 * Adds custom data to the json array. This data is passed to the Underscore template.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function to_json() {

		$this->json['manager']     = $this->manager->name;
		$this->json['name']        = $this->name;
		$this->json['type']        = $this->type;
		$this->json['icon']        = preg_match( '/dashicons-/', $this->icon ) ? sprintf( 'dashicons %s', sanitize_html_class( $this->icon ) ) : esc_attr( $this->icon );
		$this->json['label']       = $this->label;
		$this->json['description'] = $this->description;
		$this->json['active']      = $this->is_active();
	}

	/**
	 * Returns whether the section is active.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return bool
	 */
	public function is_active() {

		$is_active = call_user_func( $this->active_callback, $this );

		if ( $is_active )
			$is_active = $this->check_capabilities();

		return apply_filters( 'butterbean_is_section_active', $is_active, $this );
	}

	/**
	 * Default active callback.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return bool
	 */
	public function active_callback() {
		return true;
	}

	/**
	 * Checks if the section should be allowed at all.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return bool
	 */
	public function check_capabilities() {

		if ( $this->capability && ! call_user_func_array( 'current_user_can', (array) $this->capability ) )
			return false;

		if ( $this->post_type_supports && ! call_user_func_array( 'post_type_supports', array( get_post_type( $this->manager->post_id ), $this->post_type_supports ) ) )
			return false;

		if ( $this->theme_supports && ! call_user_func_array( 'theme_supports', (array) $this->theme_supports ) )
			return false;

		return true;
	}

	/**
	 * Prints Underscore.js template.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function print_template() { ?>

		<script type="text/html" id="tmpl-butterbean-section-<?php echo esc_attr( $this->type ); ?>">
			<?php $this->get_template(); ?>
		</script>
	<?php }

	/**
	 * Gets the Underscore.js template.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function get_template() {
		butterbean_get_section_template( $this->type );
	}
}
