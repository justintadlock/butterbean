<?php
/**
 * Primary plugin class.  This sets up and runs the show.
 *
 * @package    ButterBean
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2015-2016, Justin Tadlock
 * @link       https://github.com/justintadlock/butterbean
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

if ( ! class_exists( 'ButterBean' ) ) {

	/**
	 * Main ButterBean class.  Runs the show.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	final class ButterBean {

		/**
		 * Directory path to the plugin folder.
		 *
		 * @since  1.0.0
		 * @access public
		 * @var    string
		 */
		public $dir_path = '';

		/**
		 * Directory URI to the plugin folder.
		 *
		 * @since  1.0.0
		 * @access public
		 * @var    string
		 */
		public $dir_uri = '';

		/**
		 * Directory path to the template folder.
		 *
		 * @since  1.0.0
		 * @access public
		 * @var    string
		 */
		public $tmpl_path = '';

		/**
		 * Array of managers.
		 *
		 * @since  1.0.0
		 * @access public
		 * @var    array
		 */
		public $managers = array();

		/**
		 * Array of manager types.
		 *
		 * @since  1.0.0
		 * @access public
		 * @var    array
		 */
		public $manager_types = array();

		/**
		 * Array of section types.
		 *
		 * @since  1.0.0
		 * @access public
		 * @var    array
		 */
		public $section_types = array();

		/**
		 * Array of control types.
		 *
		 * @since  1.0.0
		 * @access public
		 * @var    array
		 */
		public $control_types = array();

		/**
		 * Array of setting types.
		 *
		 * @since  1.0.0
		 * @access public
		 * @var    array
		 */
		public $setting_types = array();

		/**
		 * Whether this is a new post.  Once the post is saved and we're
		 * no longer on the `post-new.php` screen, this is going to be
		 * `false`.
		 *
		 * @since  1.0.0
		 * @access public
		 * @var    bool
		 */
		public $is_new_post = false;

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return object
		 */
		public static function get_instance() {

			static $instance = null;

			if ( is_null( $instance ) ) {
				$instance = new self;
				$instance->setup();
				$instance->includes();
				$instance->setup_actions();
			}

			return $instance;
		}

		/**
		 * Constructor method.
		 *
		 * @since  1.0.0
		 * @access private
		 * @return void
		 */
		private function __construct() {}

		/**
		 * Initial plugin setup.
		 *
		 * @since  1.0.0
		 * @access private
		 * @return void
		 */
		private function setup() {

			$this->dir_path  = apply_filters( 'butterbean_dir_path', trailingslashit( plugin_dir_path( __FILE__ ) ) );
			$this->dir_uri   = apply_filters( 'butterbean_dir_uri',  trailingslashit( plugin_dir_url(  __FILE__ ) ) );

			$this->tmpl_path = trailingslashit( $this->dir_path . 'tmpl' );
		}

		/**
		 * Loads include and admin files for the plugin.
		 *
		 * @since  1.0.0
		 * @access private
		 * @return void
		 */
		private function includes() {

			// If not in the admin, bail.
			if ( ! is_admin() )
				return;

			// Load base classes.
			require_once( $this->dir_path . 'inc/class-manager.php' );
			require_once( $this->dir_path . 'inc/class-section.php' );
			require_once( $this->dir_path . 'inc/class-control.php' );
			require_once( $this->dir_path . 'inc/class-setting.php' );

			// Load control sub-classes.
			require_once( $this->dir_path . 'inc/controls/class-control-checkboxes.php'    );
			require_once( $this->dir_path . 'inc/controls/class-control-color.php'         );
			require_once( $this->dir_path . 'inc/controls/class-control-datetime.php'      );
			require_once( $this->dir_path . 'inc/controls/class-control-image.php'         );
			require_once( $this->dir_path . 'inc/controls/class-control-palette.php'       );
			require_once( $this->dir_path . 'inc/controls/class-control-radio.php'         );
			require_once( $this->dir_path . 'inc/controls/class-control-radio-image.php'   );
			require_once( $this->dir_path . 'inc/controls/class-control-select-group.php'  );
			require_once( $this->dir_path . 'inc/controls/class-control-textarea.php'      );

			require_once( $this->dir_path . 'inc/controls/class-control-excerpt.php'       );
			require_once( $this->dir_path . 'inc/controls/class-control-multi-avatars.php' );
			require_once( $this->dir_path . 'inc/controls/class-control-parent.php'        );

			// Load setting sub-classes.
			require_once( $this->dir_path . 'inc/settings/class-setting-multiple.php' );
			require_once( $this->dir_path . 'inc/settings/class-setting-datetime.php' );
			require_once( $this->dir_path . 'inc/settings/class-setting-array.php'    );

			// Load functions.
			require_once( $this->dir_path . 'inc/functions-core.php' );
		}

		/**
		 * Sets up initial actions.
		 *
		 * @since  1.0.0
		 * @access private
		 * @return void
		 */
		private function setup_actions() {

			// Call the register function.
			add_action( 'load-post.php',     array( $this, 'register' ), 95 );
			add_action( 'load-post-new.php', array( $this, 'register' ), 95 );

			// Register default types.
			add_action( 'butterbean_register', array( $this, 'register_manager_types' ), -95 );
			add_action( 'butterbean_register', array( $this, 'register_section_types' ), -95 );
			add_action( 'butterbean_register', array( $this, 'register_control_types' ), -95 );
			add_action( 'butterbean_register', array( $this, 'register_setting_types' ), -95 );
		}

		/**
		 * Registration callback. Fires the `butterbean_register` action hook to
		 * allow plugins to register their managers.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		public function register() {

			// If this is a new post, set the new post boolean.
			if ( 'load-post-new.php' === current_action() )
				$this->is_new_post = true;

			// Get the current post type.
			$post_type = get_current_screen()->post_type;

			// Action hook for registering managers.
			do_action( 'butterbean_register', $this, $post_type );

			// Loop through the managers to see if we're using on on this screen.
			foreach ( $this->managers as $manager ) {

				// If we found a matching post type, add our actions/filters.
				if ( ! in_array( $post_type, (array) $manager->post_type ) ) {
					$this->unregister_manager( $manager->name );
					continue;
				}

				// Sort controls and sections by priority.
				uasort( $manager->controls, array( $this, 'priority_sort' ) );
				uasort( $manager->sections, array( $this, 'priority_sort' ) );
			}

			// If no managers registered, bail.
			if ( ! $this->managers )
				return;

			// Add meta boxes.
			add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 5 );

			// Save settings.
			add_action( 'save_post', array( $this, 'update' ) );

			// Load scripts and styles.
			add_action( 'admin_enqueue_scripts',      array( $this, 'enqueue_scripts' ) );
			add_action( 'butterbean_enqueue_scripts', array( $this, 'enqueue'         ) );

			// Localize scripts and Undescore templates.
			add_action( 'admin_footer', array( $this, 'localize_scripts' ) );
			add_action( 'admin_footer', array( $this, 'print_templates'  ) );

			// Renders our Backbone views.
			add_action( 'admin_print_footer_scripts', array( $this, 'render_views' ), 95 );
		}

		/**
		 * Register a manager.
		 *
		 * @since  1.0.0
		 * @access public
		 * @param  object|string  $manager
		 * @param  array          $args
		 * @return void
		 */
		public function register_manager( $manager, $args = array() ) {

			if ( ! is_object( $manager ) ) {

				$type = isset( $args['type'] ) ? $this->get_manager_type( $args['type'] ) : $this->get_manager_type( 'default' );

				$manager = new $type( $manager, $args );
			}

			if ( ! $this->manager_exists( $manager->name ) )
				$this->managers[ $manager->name ] = $manager;

			return $manager;
		}

		/**
		 * Unregisters a manager object.
		 *
		 * @since  1.0.0
		 * @access public
		 * @param  string  $name
		 * @return void
		 */
		public function unregister_manager( $name ) {

			if ( $this->manager_exists( $name ) )
				unset( $this->managers[ $name ] );
		}

		/**
		 * Returns a manager object.
		 *
		 * @since  1.0.0
		 * @access public
		 * @param  string  $name
		 * @return object|bool
		 */
		public function get_manager( $name ) {

			return $this->manager_exists( $name ) ? $this->managers[ $name ] : false;
		}

		/**
		 * Checks if a manager exists.
		 *
		 * @since  1.0.0
		 * @access public
		 * @param  string  $name
		 * @return bool
		 */
		public function manager_exists( $name ) {

			return isset( $this->managers[ $name ] );
		}

		/**
		 * Registers a manager type.  This is just a method of telling ButterBean
		 * the class of your custom manager type.  It allows the manager to be
		 * called without having to pass an object to `register_manager()`.
		 *
		 * @since  1.0.0
		 * @access public
		 * @param  string  $type
		 * @param  string  $class
		 * @return void
		 */
		public function register_manager_type( $type, $class ) {

			if ( ! $this->manager_type_exists( $type ) )
				$this->manager_types[ $type ] = $class;
		}

		/**
		 * Unregisters a manager type.
		 *
		 * @since  1.0.0
		 * @access public
		 * @param  string  $type
		 * @return void
		 */
		public function unregister_manager_type( $type ) {

			if ( $this->manager_type_exists( $type ) )
				unset( $this->manager_types[ $type ] );
		}

		/**
		 * Returns the class name for the manager type.
		 *
		 * @since  1.0.0
		 * @access public
		 * @param  string  $type
		 * @return string
		 */
		public function get_manager_type( $type ) {

			return $this->manager_type_exists( $type ) ? $this->manager_types[ $type ] : $this->manager_types[ 'default' ];
		}

		/**
		 * Checks if a manager type exists.
		 *
		 * @since  1.0.0
		 * @access public
		 * @param  string  $type
		 * @return bool
		 */
		public function manager_type_exists( $type ) {

			return isset( $this->manager_types[ $type ] );
		}

		/**
		 * Registers a section type.  This is just a method of telling ButterBean
		 * the class of your custom section type.  It allows the section to be
		 * called without having to pass an object to `register_section()`.
		 *
		 * @since  1.0.0
		 * @access public
		 * @param  string  $type
		 * @param  string  $class
		 * @return void
		 */
		public function register_section_type( $type, $class ) {

			if ( ! $this->section_type_exists( $type ) )
				$this->section_types[ $type ] = $class;
		}

		/**
		 * Unregisters a section type.
		 *
		 * @since  1.0.0
		 * @access public
		 * @param  string  $type
		 * @return void
		 */
		public function unregister_section_type( $type ) {

			if ( $this->section_type_exists( $type ) )
				unset( $this->section_types[ $type ] );
		}

		/**
		 * Returns the class name for the section type.
		 *
		 * @since  1.0.0
		 * @access public
		 * @param  string  $type
		 * @return string
		 */
		public function get_section_type( $type ) {

			return $this->section_type_exists( $type ) ? $this->section_types[ $type ] : $this->section_types[ 'default' ];
		}

		/**
		 * Checks if a section type exists.
		 *
		 * @since  1.0.0
		 * @access public
		 * @param  string  $type
		 * @return bool
		 */
		public function section_type_exists( $type ) {

			return isset( $this->section_types[ $type ] );
		}

		/**
		 * Registers a control type.  This is just a method of telling ButterBean
		 * the class of your custom control type.  It allows the control to be
		 * called without having to pass an object to `register_control()`.
		 *
		 * @since  1.0.0
		 * @access public
		 * @param  string  $type
		 * @param  string  $class
		 * @return void
		 */
		public function register_control_type( $type, $class ) {

			if ( ! $this->control_type_exists( $type ) )
				$this->control_types[ $type ] = $class;
		}

		/**
		 * Unregisters a control type.
		 *
		 * @since  1.0.0
		 * @access public
		 * @param  string  $type
		 * @return void
		 */
		public function unregister_control_type( $type ) {

			if ( $this->control_type_exists( $type ) )
				unset( $this->control_types[ $type ] );
		}

		/**
		 * Returns the class name for the control type.
		 *
		 * @since  1.0.0
		 * @access public
		 * @param  string  $type
		 * @return string
		 */
		public function get_control_type( $type ) {

			return $this->control_type_exists( $type ) ? $this->control_types[ $type ] : $this->control_types[ 'default' ];
		}

		/**
		 * Checks if a control type exists.
		 *
		 * @since  1.0.0
		 * @access public
		 * @param  string  $type
		 * @return bool
		 */
		public function control_type_exists( $type ) {

			return isset( $this->control_types[ $type ] );
		}

		/**
		 * Registers a setting type.  This is just a method of telling ButterBean
		 * the class of your custom setting type.  It allows the setting to be
		 * called without having to pass an object to `register_setting()`.
		 *
		 * @since  1.0.0
		 * @access public
		 * @param  string  $type
		 * @param  string  $class
		 * @return void
		 */
		public function register_setting_type( $type, $class ) {

			if ( ! $this->setting_type_exists( $type ) )
				$this->setting_types[ $type ] = $class;
		}

		/**
		 * Unregisters a setting type.
		 *
		 * @since  1.0.0
		 * @access public
		 * @param  string  $type
		 * @return void
		 */
		public function unregister_setting_type( $type ) {

			if ( $this->setting_type_exists( $type ) )
				unset( $this->setting_types[ $type ] );
		}

		/**
		 * Returns the class name for the setting type.
		 *
		 * @since  1.0.0
		 * @access public
		 * @param  string  $type
		 * @return string
		 */
		public function get_setting_type( $type ) {

			return $this->setting_type_exists( $type ) ? $this->setting_types[ $type ] : $this->setting_types[ 'default' ];
		}

		/**
		 * Checks if a setting type exists.
		 *
		 * @since  1.0.0
		 * @access public
		 * @param  string  $type
		 * @return bool
		 */
		public function setting_type_exists( $type ) {

			return isset( $this->setting_types[ $type ] );
		}

		/**
		 * Registers our manager types so that devs don't have to directly instantiate
		 * the class each time they register a manager.  Instead, they can use the
		 * `type` argument.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		public function register_manager_types() {

			$this->register_manager_type( 'default', 'ButterBean_Manager' );
		}

		/**
		 * Registers our section types so that devs don't have to directly instantiate
		 * the class each time they register a section.  Instead, they can use the
		 * `type` argument.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		public function register_section_types() {

			$this->register_section_type( 'default', 'ButterBean_Section' );
		}

		/**
		 * Registers our control types so that devs don't have to directly instantiate
		 * the class each time they register a control.  Instead, they can use the
		 * `type` argument.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		public function register_control_types() {

			$this->register_control_type( 'default',       'ButterBean_Control'               );
			$this->register_control_type( 'checkboxes',    'ButterBean_Control_Checkboxes'    );
			$this->register_control_type( 'color',         'ButterBean_Control_Color'         );
			$this->register_control_type( 'datetime',      'ButterBean_Control_Datetime'      );
			$this->register_control_type( 'excerpt',       'ButterBean_Control_Excerpt'       );
			$this->register_control_type( 'image',         'ButterBean_Control_Image'         );
			$this->register_control_type( 'palette',       'ButterBean_Control_Palette'       );
			$this->register_control_type( 'radio',         'ButterBean_Control_Radio'         );
			$this->register_control_type( 'radio-image',   'ButterBean_Control_Radio_Image'   );
			$this->register_control_type( 'select-group',  'ButterBean_Control_Select_Group'  );
			$this->register_control_type( 'textarea',      'ButterBean_Control_Textarea'      );
			$this->register_control_type( 'multi-avatars', 'ButterBean_Control_Multi_Avatars' );
			$this->register_control_type( 'parent',        'ButterBean_Control_Parent'        );
		}

		/**
		 * Registers our setting types so that devs don't have to directly instantiate
		 * the class each time they register a setting.  Instead, they can use the
		 * `type` argument.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		public function register_setting_types() {

			$this->register_setting_type( 'default',  'ButterBean_Setting'          );
			$this->register_setting_type( 'single',   'ButterBean_Setting'          );
			$this->register_setting_type( 'multiple', 'ButterBean_Setting_Multiple' );
			$this->register_setting_type( 'array',    'ButterBean_Setting_Array'    );
			$this->register_setting_type( 'datetime', 'ButterBean_Setting_Datetime' );
		}

		/**
		 * Fires an action hook to register/enqueue scripts/styles.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		public function enqueue_scripts() {

			do_action( 'butterbean_enqueue_scripts' );
		}

		/**
		 * Loads scripts and styles.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		public function enqueue() {
			$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			// Enqueue the main plugin script.
			wp_enqueue_script( 'butterbean', $this->dir_uri . "js/butterbean{$min}.js", array( 'backbone', 'wp-util' ), '', true );

			// Enqueue the main plugin style.
			wp_enqueue_style( 'butterbean', $this->dir_uri . "css/butterbean{$min}.css" );

			// Loop through the manager and its controls and call each control's `enqueue()` method.
			foreach ( $this->managers as $manager ) {

				$manager->enqueue();

				foreach ( $manager->sections as $section )
					$section->enqueue();

				foreach ( $manager->controls as $control )
					$control->enqueue();
			}
		}

		/**
		 * Callback function for adding meta boxes.  This function adds a meta box
		 * for each of the managers.
		 *
		 * @since  1.0.0
		 * @access public
		 * @param  string  $post_type
		 * @return void
		 */
		public function add_meta_boxes( $post_type ) {

			foreach ( $this->managers as $manager ) {

				// If the manager is registered for the current post type, add a meta box.
				if ( in_array( $post_type, (array) $manager->post_type ) && $manager->check_capabilities() ) {

					add_meta_box(
						"butterbean-ui-{$manager->name}",
						$manager->label,
						array( $this, 'meta_box' ),
						$post_type,
						$manager->context,
						$manager->priority,
						array( 'manager' => $manager )
					);
				}
			}
		}

		/**
		 * Displays the meta box.  Note that the actual content of the meta box is
		 * handled via Underscore.js templates.  The only thing we're outputting here
		 * is the nonce field.
		 *
		 * @since  1.0.0
		 * @access public
		 * @param  object  $post
		 * @param  array   $metabox
		 * @return void
		 */
		public function meta_box( $post, $metabox ) {

			$manager = $metabox['args']['manager'];

			$manager->post_id = $this->post_id = $post->ID;

			// Nonce field to validate on save.
			wp_nonce_field( "butterbean_{$manager->name}_nonce", "butterbean_{$manager->name}" );
		}

		/**
		 * Passes the appropriate section and control json data to the JS file.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		public function localize_scripts() {

			$json = array( 'managers' => array() );

			foreach ( $this->managers as $manager ) {

				if ( $manager->check_capabilities() )
					$json['managers'][] = $manager->get_json();
			}

			wp_localize_script( 'butterbean', 'butterbean_data', $json );
		}

		/**
		 * Prints the Underscore.js templates.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		public function print_templates() {

			$m_templates = array();
			$s_templates = array();
			$c_templates = array(); ?>

			<script type="text/html" id="tmpl-butterbean-nav">
				<?php butterbean_get_nav_template(); ?>
			</script>

			<?php foreach ( $this->managers as $manager ) {

				if ( ! $manager->check_capabilities() )
					continue;

				if ( ! in_array( $manager->type, $m_templates ) ) {
					$m_templates[] = $manager->type;

					$manager->print_template();
				}

				foreach ( $manager->sections as $section ) {

					if ( ! in_array( $section->type, $s_templates ) ) {
						$s_templates[] = $section->type;

						$section->print_template();
					}
				}

				foreach ( $manager->controls as $control ) {

					if ( ! in_array( $control->type, $c_templates ) ) {
						$c_templates[] = $control->type;

						$control->print_template();
					}
				}
			}
		}

		/**
		 * Renders our Backbone views.  We're calling this late in the page load so
		 * that other scripts have an opportunity to extend with their own, custom
		 * views for custom controls and such.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		public function render_views() { ?>

			<script type="text/javascript">
				( function( api ) {
					if ( _.isObject( api ) && _.isFunction( api.render ) ) {
						api.render();
					}
				}( butterbean ) );
			</script>
		<?php }

		/**
		 * Saves the settings.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		public function update( $post_id ) {

			$do_autosave = defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE;
			$is_autosave = wp_is_post_autosave( $post_id );
			$is_revision = wp_is_post_revision( $post_id );

			if ( $do_autosave || $is_autosave || $is_revision )
				return;

			foreach ( $this->managers as $manager ) {

				if ( $manager->check_capabilities() )
					$manager->save( $post_id );
			}
		}

		/**
		 * Helper method for sorting sections and controls by priority.
		 *
		 * @since  1.0.0
		 * @access protected
		 * @param  object     $a
		 * @param  object     $b
		 * @return int
		 */
		protected function priority_sort( $a, $b ) {

			if ( $a->priority === $b->priority )
				return $a->instance_number - $b->instance_number;

			return $a->priority - $b->priority;
		}
	}

	/**
	 * Gets the instance of the `ButterBean` class.  This function is useful for quickly grabbing data
	 * used throughout the plugin.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object
	 */
	function butterbean() {
		return ButterBean::get_instance();
	}

	// Let's do this thang!
	butterbean();
}
