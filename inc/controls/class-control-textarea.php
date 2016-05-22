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
class ButterBean_Control_Textarea extends ButterBean_Control {

	public $type = 'textarea';

	public function to_json() {
		parent::to_json();

		$this->json['value'] = esc_textarea( $this->get_value( $this->manager->post_id ) );
	}

	public function template() {
		butterbean_get_template( 'control', 'textarea' ); 
	}
}
