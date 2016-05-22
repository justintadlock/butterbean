<?php
/**
 * Radio control class for the fields manager.
 *
 * @package    ButterBean
 * @subpackage Admin
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2015-2016, Justin Tadlock
 * @link       https://github.com/justintadlock/butterbean
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

class ButterBean_Control_Radio extends ButterBean_Control {

	public $type = 'radio';

	public function template() {
		butterbean_get_template( 'control', 'radio' );
	}
}
