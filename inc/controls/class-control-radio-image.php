<?php
/**
 * Radio image control class extends the built-in radio control.  This control is 
 * meant for displaying an image instead of the radio fields.
 *
 * @package    ButterBean
 * @subpackage Admin
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2015-2016, Justin Tadlock
 * @link       https://github.com/justintadlock/butterbean
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Radio image control class.
 *
 * @since  1.0.0
 * @access public
 */
class ButterBean_Control_Radio_Image extends ButterBean_Control_Radio {

	/**
	 * The type of control.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $type = 'radio-image';

	/**
	 * Adds custom data to the json array. This data is passed to the Underscore template.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function to_json() {
		parent::to_json();

		foreach ( $this->choices as $value => $args )
			$this->choices[ $value ]['url'] = esc_url( sprintf( $args['url'], get_template_directory_uri(), get_stylesheet_directory_uri() ) );

		$this->json['choices'] = $this->choices;
	}
}
