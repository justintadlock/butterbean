<?php
/**
 * Date control class for the fields manager.
 *
 * @package    ButterBean
 * @subpackage Admin
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2015-2016, Justin Tadlock
 * @link       https://github.com/justintadlock/butterbean
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Date control class.
 *
 * @since  1.0.0
 * @access public
 */
class ButterBean_Control_Multi_Avatars extends ButterBean_Control {

	public $type = 'multi-avatars';

	public function to_json() {
		parent::to_json();

		$name = "butterbean_{$this->manager->name}_setting_{$this->setting}[]";

		$users = get_users( array( 'role__in' => $this->get_roles( get_post_type( $this->manager->post_id ) ) ) );

		$this->json['value'] = (array) $this->get_value( $this->manager->post_id );
		$this->json['choices'] = array();

		foreach ( $users as $user ) {
			$this->json['choices'][] = array(
				'id'     => $user->ID,
				'name'   => $user->display_name,
				'avatar' => get_avatar( $user->ID, 70 )
			);
		}
	}

	public function template() {
		butterbean_get_template( 'control', 'multi-avatars' );
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
	public function get_roles( $post_type ) {
		global $wp_roles;

		$roles = array();
		$type  = get_post_type_object( $post_type );

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
