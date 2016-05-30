<?php
/**
 * Helper functions.
 *
 * @package    ButterBean
 * @subpackage Admin
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2015-2016, Justin Tadlock
 * @link       https://github.com/justintadlock/butterbean
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Function for validating booleans before saving them as metadata. If the value is
 * `true`, we'll return a `1` to be stored as the meta value.  Else, we return `false`.
 *
 * @since  1.0.0
 * @access public
 * @param  mixed
 * @return bool|int
 */
function butterbean_validate_boolean( $value ) {

	return wp_validate_boolean( $value ) ? 1 : false;
}

/**
 * Helper function for getting Underscore.js templates.
 *
 * @since  1.0.0
 * @param  string  $name
 * @param  string  $slug
 * @return void
 */
function butterbean_get_template( $name, $slug = '' ) {

	$templates = array();

	if ( $slug )
		$templates[] = "{$name}-{$slug}.php";

	$templates[] = "{$name}.php";

	foreach ( $templates as $template ) {

		if ( file_exists( butterbean()->dir_path . "tmpl/{$template}" ) ) {
			require( butterbean()->dir_path . "tmpl/{$template}" );
			break;
		}
	}
}
