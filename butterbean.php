<?php
/**
 * Plugin Name: ButterBean
 * Plugin URI:  https://github.com/justintadlock/butterbean
 * Description: A little post meta framework.
 * Version:     1.0.0-dev
 * Author:      Justin Tadlock
 * Author URI:  http://themehybrid.com
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
		 * Array of managers.
		 *
		 * @since  1.0.0
		 * @access public
		 * @var    array
		 */
		public $managers = array();

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

			$this->dir_path = trailingslashit( plugin_dir_path( __FILE__ ) );
			$this->dir_uri  = trailingslashit( plugin_dir_url(  __FILE__ ) );
		}

		/**
		 * Loads include and admin files for the plugin.
		 *
		 * @since  1.0.0
		 * @access private
		 * @return void
		 */
		private function includes() {

			// Load base classes.
			require_once( $this->dir_path . 'inc/class-manager.php' );
			require_once( $this->dir_path . 'inc/class-section.php' );
			require_once( $this->dir_path . 'inc/class-control.php' );
			require_once( $this->dir_path . 'inc/class-setting.php' );

			// Load control sub-classes.
			require_once( $this->dir_path . 'inc/controls/class-control-checkbox.php'      );
			require_once( $this->dir_path . 'inc/controls/class-control-checkboxes.php'    );
			require_once( $this->dir_path . 'inc/controls/class-control-date.php'          );
			require_once( $this->dir_path . 'inc/controls/class-control-radio.php'         );
			require_once( $this->dir_path . 'inc/controls/class-control-select.php'        );
			require_once( $this->dir_path . 'inc/controls/class-control-text.php'          );
			require_once( $this->dir_path . 'inc/controls/class-control-textarea.php'      );

			require_once( $this->dir_path . 'inc/controls/class-control-excerpt.php'       );
			require_once( $this->dir_path . 'inc/controls/class-control-multi-avatars.php' );
			require_once( $this->dir_path . 'inc/controls/class-control-parent.php'        );

			// Load setting sub-classes.
			require_once( $this->dir_path . 'inc/settings/class-setting-date.php'  );
			require_once( $this->dir_path . 'inc/settings/class-setting-array.php' );

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

			// Get the current post type.
			$post_type = get_current_screen()->post_type;

			// Action hook for registering managers.
			do_action( 'butterbean_register', $this, $post_type );

			// If no managers registered, bail.
			if ( ! $this->managers )
				return;

			// Loop through the managers to see if we're using on on this screen.
			foreach ( $this->managers as $manager ) {

				// If we found a matching post type, add our actions/filters.
				if ( in_array( $post_type, (array) $manager->post_type ) ) {

					// Add meta boxes.
					add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );

					// Save settings.
					add_action( 'save_post', array( $this, 'update' ) );

					// Load scripts and styles.
					add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );

					// Localize scripts and Undescore templates.
					add_action( 'admin_footer', array( $this, 'localize_scripts' ) );
					add_action( 'admin_footer', array( $this, 'print_templates' ) );

					// Break out of the loop.
					break;
				}
			}
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

			if ( ! is_object( $manager ) )
				$manager = new ButterBean_Manager( $manager, $args );

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
		 * Loads scripts and styles.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		public function enqueue() {
			$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			wp_enqueue_script( 'butterbean', $this->dir_uri . "js/butterbean{$min}.js", array( 'backbone', 'wp-util' ), '', true );

			wp_enqueue_style( 'butterbean', $this->dir_uri . "css/butterbean{$min}.css" );
		}

		/**
		 * Callback function for adding meta boxes.  This function adds a meta box
		 * for each of the managers.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		public function add_meta_boxes() {

			foreach ( $this->managers as $manager ) {

				foreach ( (array) $manager->post_type as $type ) {

					add_meta_box(
						"butterbean-ui-{$manager->name}",
						$manager->label,
						array( $this, 'meta_box' ),
						$type,
						$manager->context,
						$manager->priority,
						array( 'manager' => $manager )
					);
				}
			}
		}

		/**
		 * Displays the meta box.
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
			wp_nonce_field( "butterbean_{$manager->name}_nonce", "butterbean_{$manager->name}" ); ?>

		<?php }

		/**
		 * Passes the appropriate section and control json data to the JS file.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		public function localize_scripts() {

			$json = array( 'managers' => array() );

			foreach ( $this->managers as $manager )
				$json['managers'][] = $manager->get_json();

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
				<?php butterbean_get_template( 'nav' ); ?>
			</script>

			<?php foreach ( $this->managers as $manager ) {

				if ( ! in_array( $manager->type, $m_templates ) ) {
					$m_templates[] = $manager->type; ?>

					<script type="text/html" id="tmpl-butterbean-manager-<?php echo esc_attr( $manager->type ); ?>">
						<?php $manager->print_template(); ?>
					</script>
				<?php }

				foreach ( $manager->sections as $section ) {

					if ( ! in_array( $section->type, $s_templates ) ) {
						$s_templates[] = $section->type; ?>

						<script type="text/html" id="tmpl-butterbean-section-<?php echo esc_attr( $section->type ); ?>">
							<?php $section->print_template(); ?>
						</script>
					<?php }
				}

				foreach ( $manager->controls as $control ) {

					if ( ! in_array( $control->type, $c_templates ) ) {
						$c_templates[] = $control->type; ?>

						<script type="text/html" id="tmpl-butterbean-control-<?php echo esc_attr( $control->type ); ?>">
							<?php $control->print_template(); ?>
						</script>
					<?php }
				}
			}
		}

		/**
		 * Saves the settings.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		public function update( $post_id ) {

			if ( ! $this->post_id )
				$this->post_id = $post_id;

			$do_autosave = defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE;
			$is_autosave = wp_is_post_autosave( $post_id );
			$is_revision = wp_is_post_revision( $post_id );

			if ( $do_autosave || $is_autosave || $is_revision )
				return;

			foreach ( $this->managers as $manager )
				$manager->save( $this->post_id );
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
