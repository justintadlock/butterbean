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
	}

	public function get_attr() {
		$attr = parent::get_attr();

		$setting = $this->get_setting();

		$attr['class']              = 'butterbean-color-picker';
		$attr['type']               = 'text';
		$attr['maxlength']          = 7;
		$attr['data-default-color'] = $setting ? $setting->default : '';

		return $attr;
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
	 * @param  string  $setting
	 * @return mixed
	 */
	public function get_value( $setting = 'default' ) {

		$value = parent::get_value( $setting );

		return ltrim( $value, '#' );
	}
}
