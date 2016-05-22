<?php
/**
 * Excerpt control class for the fields manager.
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

	public function get_value( $post_id ) {
		return get_post( $post_id )->post_excerpt;
	}
}
