<?php
/**
 * Select group control class.  This works just like a normal select.  However, it
 * allows for `<optgroup>` to be added.
 *
 * @package    ButterBean
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2015-2016, Justin Tadlock
 * @link       https://github.com/justintadlock/butterbean
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Select group control class.
 *
 * @since  1.0.0
 * @access public
 */
class ButterBean_Control_Select_Group extends ButterBean_Control {

	/**
	 * The type of control.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $type = 'select-group';

	/**
	 * Adds custom data to the json array.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function to_json() {
		parent::to_json();

		$choices = $group = array();

		foreach ( $this->choices as $choice => $maybe_group ) {

			if ( is_array( $maybe_group ) )
				$group[ $choice ] = $maybe_group;
			else
				$choices[ $choice ] = $maybe_group;
		}

		$this->json['choices'] = $choices;
		$this->json['group']   = $group;
	}
}
