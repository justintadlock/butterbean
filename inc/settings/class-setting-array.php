<?php
/**
 * Setting class for storing a single meta value as an array.
 *
 * @package    ButterBean
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2015-2016, Justin Tadlock
 * @link       https://github.com/justintadlock/butterbean
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Array setting class.
 *
 * @since  1.0.0
 * @access public
 */
class ButterBean_Setting_Array extends ButterBean_Setting {

	/**
	 * The type of setting.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $type = 'array';

	/**
	 * Sanitizes the value of the setting.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array   $value
	 * @return array
	 */
	public function sanitize( $values ) {

		$multi_values = $values && ! is_array( $values ) ? explode( ',', $values ) : $values;

		return $multi_values ? array_map( array( $this, 'map' ), $multi_values ) : array();
	}

	/**
	 * Helper function for sanitizing each value of the array.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  mixed  $value
	 * @return mixed
	 */
	public function map( $value ) {

		return apply_filters( "butterbean_{$this->manager->name}_sanitize_{$this->name}", $value, $this );
	}

	/**
	 * Saves the value of the setting.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function save() {

		if ( ! $this->check_capabilities() )
			return;

		$old_values = $this->get_value();
		$new_values = $this->get_posted_value();

		// If there's an array of posted values, set them.
		if ( $new_values && is_array( $new_values ) && $new_values !== $old_values )
			return update_post_meta( $this->manager->post_id, $this->name, $new_values );

		// If no array of posted values but we have old values, delete them.
		else if ( $old_values && ! $new_values )
			return delete_post_meta( $this->manager->post_id, $this->name );
	}
}
