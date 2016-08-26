<?php
/**
 * Setting class for storing multiple post meta values for a single key.
 *
 * @package    ButterBean
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2015-2016, Justin Tadlock
 * @link       https://github.com/justintadlock/butterbean
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Multiple setting class.
 *
 * @since  1.0.0
 * @access public
 */
class ButterBean_Setting_Multiple extends ButterBean_Setting {

	/**
	 * The type of setting.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $type = 'multiple';

	/**
	 * Gets the value of the setting.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed
	 */
	public function get_value() {

		return get_post_meta( $this->manager->post_id, $this->name );
	}

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
		if ( is_array( $new_values ) )
			$this->set_values( $new_values, $old_values );

		// If no array of posted values but we have old values, delete them.
		else if ( $old_values )
			$this->delete_values();
	}

	/**
	 * Loops through new and old meta values and updates.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array   $new_values
	 * @param  array   $old_values
	 * @return void
	 */
	public function set_values( $new_values, $old_values ) {

		foreach ( $new_values as $new ) {

			if ( ! in_array( $new, $old_values ) )
				$this->add_value( $new );
		}

		foreach ( $old_values as $old ) {

			if ( ! in_array( $old, $new_values ) )
				$this->remove_value( $old );
		}
	}

	/**
	 * Deletes old meta values.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function delete_values() {

		return delete_post_meta( $this->manager->post_id, $this->name );
	}

	/**
	 * Adds a single meta value.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  mixed   $value
	 * @return bool
	 */
	public function add_value( $value ) {

		return add_post_meta( $this->manager->post_id, $this->name, $value, false );
	}

	/**
	 * Deletes a single meta value.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  mixed   $value
	 * @return bool
	 */
	public function remove_value( $value ) {

		return delete_post_meta( $this->manager->post_id, $this->name, $value );
	}
}
