<?php
/**
 * Multiple checkbox control class.  This is for array-type settings, so you'll need 
 * to utilize a setting type that handles arrays.  Both the `array` and `multiple` 
 * setting types will do this.
 *
 * @package    ButterBean
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2015-2016, Justin Tadlock
 * @link       https://github.com/justintadlock/butterbean
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Multiple checkboxes control class.
 *
 * @since  1.0.0
 * @access public
 */
class ButterBean_Control_CheckBoxes extends ButterBean_Control {

	/**
	 * The type of control.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $type = 'checkboxes';

	/**
	 * Adds custom data to the json array. This data is passed to the Underscore template.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function to_json() {
		parent::to_json();

		$this->json['value'] = (array) $this->get_value();
	}
}
