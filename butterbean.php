<?php
/**
 * Plugin Name: ButterBean
 * Plugin URI:  https://github.com/justintadlock/butterbean
 * Description: A little post meta framework.
 * Version:     1.0.0
 * Author:      Justin Tadlock
 * Author URI:  http://themehybrid.com
 *
 * @package    ButterBean
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2015-2016, Justin Tadlock
 * @link       https://github.com/justintadlock/butterbean
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

// For each version release, the priority needs to decrement by 1. This is so that
// we can load newer versions earlier than older versions when there's a conflict.
add_action( 'init', 'butterbean_loader_100', 9999 );

if ( ! function_exists( 'butterbean_loader_100' ) ) {

	/**
	 * Loader function.  Note to change the name of this function to use the
	 * current version number of the plugin.  `1.0.0` is `100`, `1.3.4` = `134`.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	function butterbean_loader_100() {

		// If not in the admin, bail.
		if ( ! is_admin() )
			return;

		// If ButterBean hasn't been loaded, let's load it.
		if ( ! defined( 'BUTTERBEAN_LOADED' ) ) {
			define( 'BUTTERBEAN_LOADED', true );

			require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'class-butterbean.php' );
		}
	}
}
