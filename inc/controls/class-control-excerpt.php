<?php
/**
 * Excerpt control class.  Note that this control isn't meant to be tied to a setting.  Core
 * WP will save the excerpt.  Also, make sure to disable the core excerpt metabox if using
 * this control.
 *
 * @package    ButterBean
 * @subpackage Admin
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2015-2016, Justin Tadlock
 * @link       https://github.com/justintadlock/butterbean
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Excerpt control class.
 *
 * @since  1.0.0
 * @access public
 */
class ButterBean_Control_Excerpt extends ButterBean_Control_Textarea {

	/**
	 * The type of control.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $type = 'excerpt';

	/**
	 * Gets the attributes for the control.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return array
	 */
	public function get_attr() {
		$attr = parent::get_attr();

		$attr['id'] = 'post_excerpt';

		return $attr;
	}

	/**
	 * Returns the HTML field name for the control.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $setting
	 * @return string
	 */
	public function get_field_name( $setting = 'default' ) {
		return 'post_excerpt';
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

		return get_post( $this->manager->post_id )->post_excerpt;
	}

	/**
	 * Gets the Underscore.js template.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function get_template() {
		butterbean_get_control_template( 'textarea' );
	}
}
