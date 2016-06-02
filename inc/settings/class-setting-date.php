<?php
/**
 * Date setting class.  This is meant to be used in conjunction with the built-in 
 * `ButterBean_Date_Control` or a sub-class that passes the appropriate values.
 *
 * @package    ButterBean
 * @subpackage Admin
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2015-2016, Justin Tadlock
 * @link       https://github.com/justintadlock/butterbean
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Date setting class.
 *
 * @since  1.0.0
 * @access public
 */
class ButterBean_Setting_Date extends ButterBean_Setting {

	/**
	 * Gets the posted value of the setting.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed
	 */
	public function get_posted_value() {

		$field_name = $this->get_field_name();

		// Get the posted year, month, and day.
		$year  = ! empty( $_POST[ "{$field_name}_year" ] )  ? zeroise( absint( $_POST[ "{$field_name}_year" ]  ), 4 ) : '';
		$month = ! empty( $_POST[ "{$field_name}_month" ] ) ? zeroise( absint( $_POST[ "{$field_name}_month" ] ), 2 ) : '';
		$day   = ! empty( $_POST[ "{$field_name}_day" ] )   ? zeroise( absint( $_POST[ "{$field_name}_day" ]   ), 2 ) : '';

		$new_date = $year && $month && $day ? "{$year}-{$month}-{$day} 00:00:00" : '';

		return $new_date;
	}
}
