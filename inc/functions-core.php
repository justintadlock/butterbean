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
 * Pre-WP 4.6 function for sanitizing hex colors.
 *
 * @since  1.0.0
 * @access public
 * @param  string  $color
 * @return string
 */
function butterbean_sanitize_hex_color( $color ) {

	if ( function_exists( 'sanitize_hex_color' ) )
		return sanitize_hex_color( $color );

	return $color && preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) ? $color : '';
}

/**
 * Pre-WP 4.6 function for sanitizing hex colors without a hash.
 *
 * @since  1.0.0
 * @access public
 * @param  string  $color
 * @return string
 */
function butterbean_sanitize_hex_color_no_hash( $color ) {

	if ( function_exists( 'sanitize_hex_color_no_hash' ) )
		return sanitize_hex_color_no_hash( $color );

	$color = ltrim( $color, '#' );

	if ( '' === $color )
		return '';

	return butterbean_sanitize_hex_color( '#' . $color ) ? $color : null;
}

/**
 * Pre-WP 4.6 function for sanitizing a color and adding a hash.
 *
 * @since  1.0.0
 * @access public
 * @param  string  $color
 * @return string
 */
function butterbean_maybe_hash_hex_color( $color ) {

	if ( function_exists( 'maybe_has_hex_color' ) )
		return maybe_has_hex_color( $color );

	if ( $unhashed = butterbean_sanitize_hex_color_no_hash( $color ) )
		return '#' . $unhashed;

	return $color;
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
