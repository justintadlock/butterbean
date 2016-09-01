<?php
/**
 * Multi-avatars control.  This control is for outputting multiple users who can create,
 * edit, or publish posts of the given post type.  Multiple users can be selected.  The
 * data is expected to be an array.  This control should be used with a setting type that
 * handles arrays, such as the built-in `array` or `multiple` types.
 *
 * @package    ButterBean
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2015-2016, Justin Tadlock
 * @link       https://github.com/justintadlock/butterbean
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Multi-avatars control class.
 *
 * @since  1.0.0
 * @access public
 */
class ButterBean_Control_Multi_Avatars extends ButterBean_Control {

	/**
	 * The type of control.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $type = 'multi-avatars';

	/**
	 * Adds custom data to the json array. This data is passed to the Underscore template.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function to_json() {
		parent::to_json();

		$this->json['value']   = is_array( $this->get_value() ) ? array_map( 'absint', $this->get_value() ) : array();
		$this->json['choices'] = array();

		$users = get_users( array( 'role__in' => $this->get_roles() ) );

		foreach ( $users as $user ) {
			$this->json['choices'][] = array(
				'id'     => $user->ID,
				'name'   => $user->display_name,
				'avatar' => get_avatar( $user->ID, 70 )
			);
		}
	}

	/**
	 * Returns an array of user roles that are allowed to edit, publish, or create
	 * posts of the given post type.
	 *
	 * @since  1.0.0
	 * @access public
	 * @global object  $wp_roles
	 * @return array
	 */
	public function get_roles() {
		global $wp_roles;

		$roles = array();
		$type  = get_post_type_object( get_post_type( $this->manager->post_id ) );

		// Get the post type object caps.
		$caps = array( $type->cap->edit_posts, $type->cap->publish_posts, $type->cap->create_posts );
		$caps = array_unique( $caps );

		// Loop through the available roles.
		foreach ( $wp_roles->roles as $name => $role ) {

			foreach ( $caps as $cap ) {

				// If the role is granted the cap, add it.
				if ( isset( $role['capabilities'][ $cap ] ) && true === $role['capabilities'][ $cap ] ) {
					$roles[] = $name;
					break;
				}
			}
		}

		return $roles;
	}
}
