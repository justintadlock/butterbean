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
class ButterBean_Control_Parent extends ButterBean_Control {

	public $type = 'parent';

	public $post_type = '';

	public function __construct( $manager, $name, $args = array() ) {

		parent::__construct( $manager, $name, $args );

		$this->post_type = ! $this->post_type ? get_post_type( $this->manager->post_id ) : $this->post_type;
	}

	public function to_json() {

		parent::to_json();

		$_post = get_post( $this->manager->post_id );

		$posts = get_posts(
			array(
				'post_type'      => $this->post_type,
				'post_status'    => 'any',
				'post__not_in'   => array( $post_id ),
				'posts_per_page' => -1,
				'post_parent'    => 0,
				'orderby'        => 'title',
				'order'          => 'ASC',
				'fields'         => array( 'ID', 'post_title' )
			)
		);

		$this->json['choices'] = array( array( 'value' => 0, 'label' => '' ) );

		foreach ( $posts as $post )
			$this->json['choices'][] = array( 'value' => $post->ID, 'label' => $post->post_title );
	}

	public function template() {
		butterbean_get_template( 'control', 'parent' );
	}
}
