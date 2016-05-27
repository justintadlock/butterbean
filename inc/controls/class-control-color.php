<?php
/**
 * Text control class.
 *
 * @package    ButterBean
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2015-2016, Justin Tadlock
 * @link       https://github.com/justintadlock/butterbean
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Text control class.
 *
 * @since  1.0.0
 * @access public
 */
class ButterBean_Control_Color extends ButterBean_Control {

	/**
	 * The type of control.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $type = 'color';

	public function __construct( $manager, $name, $args = array() ) {
		parent::__construct( $manager, $name, $args );

		$this->attr['class'] = 'butterbean-color-picker';
		$this->attr['type']  = 'text';
		$this->attr['maxlength'] = 7;
		$this->attr['data-default-color'] = '';
	}

	public function enqueue() {
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_style(  'wp-color-picker' );

		add_action( 'admin_footer', array( $this, 'print_scripts' ) );
	}

	public function print_scripts() { ?>
		<script type="text/javascript">
			jQuery( document ).ready( function( $ ) {
				$( '.butterbean-color-picker' ).wpColorPicker();
			} );
		</script>
	<?php }
	/**
	 * Get the value for the setting.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed
	 */
	public function get_value() {

		$value = parent::get_value();

		return ltrim( $value, '#' );
	}

	/**
	 * Gets the Underscore.js template.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function template() {
		butterbean_get_template( 'control', 'color' );
	}
}
