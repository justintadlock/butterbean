<?php
/**
 * Radio control class.
 *
 * @package    ButterBean
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2015-2016, Justin Tadlock
 * @link       https://github.com/justintadlock/butterbean
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Radio control class.
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
