<?php
/**
 * Color control class.  This class uses the core WordPress color picker.  Expected 
 * values are hex colors.  This class also attempts to strip `#` from the hex color.
 * By design, it's recommended to add the `#` on output.
 *
 * @package    ButterBean
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2015-2016, Justin Tadlock
 * @link       https://github.com/justintadlock/butterbean
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Color control class.
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

	/**
	 * Enqueue scripts/styles for the control.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enqueue() {

		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_style(  'wp-color-picker' );
	}

	/**
	 * Gets the attributes for the control.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return array
	 */
	public function get_attr() {
		$attr = parent::get_attr();

		$setting = $this->get_setting();

		$attr['class']              = 'butterbean-color-picker';
		$attr['type']               = 'text';
		$attr['maxlength']          = 7;
		$attr['data-default-color'] = $setting ? $setting->default : '';

		return $attr;
	}

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
