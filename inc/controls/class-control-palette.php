<?php
/**
 * Color palette control class.  The purpose of this class is to give users a choice 
 * of color palettes.  The actual data that is stored is a key of your choosing.
 *
 * @package    ButterBean
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2015-2016, Justin Tadlock
 * @link       https://github.com/justintadlock/butterbean
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Color palette control class.
 *
 * @since  1.0.0
 * @access public
 */
class ButterBean_Control_Palette extends ButterBean_Control {

	/**
	 * The type of control.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $type = 'palette';

	/**
	 * Adds custom data to the json array. This data is passed to the Underscore template.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function to_json() {
		parent::to_json();

		$value = $this->get_value();

		// Make sure the colors have a hash.
		foreach ( $this->choices as $choice => $palette ) {
			$this->choices[ $choice ]['colors'] = array_map( 'butterbean_maybe_hash_hex_color', $palette['colors'] );

			$this->choices[ $choice ]['selected'] = $value && $choice === $value;
		}

		$this->json['choices'] = $this->choices;
	}
}
