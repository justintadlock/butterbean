<?php
/**
 * Base class for handling controls.  Controls are the form fields for the manager.  Each
 * control should be tied to a section.
 *
 * @package    ButterBean
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2015-2016, Justin Tadlock
 * @link       https://github.com/justintadlock/butterbean
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Base control class.
 *
 * @since  1.0.0
 * @access public
 */
class ButterBean_Control {

	/**
	 * Stores the manager object.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    object
	 */
	public $manager;

	/**
	 * Name/ID of the control.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $name = '';

	/**
	 * Label for the control.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $label = '';

	/**
	 * Description for the control.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $description = '';

	/**
	 * ID of the section the control is for.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $section = '';

	/**
	 * ID of the setting the control is for.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $setting = '';

	/**
	 * The type of control.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $type = 'text';

	/**
	 * Form field attributes.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    array
	 */
	public $attr = '';

	/**
	 * Choices for fields with multiple choices.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    array
	 */
	public $choices = array();

	/**
	 * Stores the JSON data for the control.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    array()
	 */
	public $json = array();

	/**
	 * Creates a new control object.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  object  $manager
	 * @param  string  $name
	 * @param  array   $args
	 * @return void
	 */
	public function __construct( $manager, $name, $args = array() ) {

		foreach ( array_keys( get_object_vars( $this ) ) as $key ) {

			if ( isset( $args[ $key ] ) )
				$this->$key = $args[ $key ];
		}

		$this->manager = $manager;
		$this->name    = $name;

		if ( ! isset( $args['setting'] ) )
			$this->setting = $name;
	}

	/**
	 * Get the value for the setting.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return mixed
	 */
	public function get_value() {

		$setting = $this->manager->get_setting( $this->setting );

		return $setting ? $setting->get_value( $this->manager->post_id ) : false;
	}

	/**
	 * Gets the attributes for the control.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return array
	 */
	public function get_attr() {

		$defaults = array( 'name' => "butterbean_{$this->manager->name}_setting_{$this->setting}" );

		return wp_parse_args( $this->attr, $defaults );
	}

	/**
	 * Prints the attributes for the control.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function attr() {

		foreach ( $this->get_attr() as $attr => $value )
			printf( '%s="%s" ', esc_html( $attr ), esc_attr( $value ) );
	}

	/**
	 * Returns the json array.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return array
	 */
	public function get_json() {
		$this->to_json();

		return $this->json;
	}

	/**
	 * Adds custom data to the json array. This data is passed to the Underscore template.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function to_json() {

		$this->json['manager']     = $this->manager->name;
		$this->json['section']     = $this->section;
		$this->json['setting']     = $this->setting;
		$this->json['name']        = $this->name;
		$this->json['label']       = $this->label;
		$this->json['type']        = $this->type;
		$this->json['description'] = $this->description;
		$this->json['value']       = $this->get_value();
		$this->json['choices']     = $this->choices;

		$this->json['attr'] = '';

		foreach ( $this->get_attr() as $attr => $value ) {
			$this->json['attr'] .= sprintf( '%s="%s" ', esc_html( $attr ), esc_attr( $value ) );
		}
	}

	/**
	 * Prints Underscore.js template.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function print_template() { ?>

		<div id="butterbean-control-{{ data.name }}" class="butterbean-control butterbean-control-{{ data.type }}">
			<?php $this->template(); ?>
		</div>
	<?php }

	/**
	 * Gets the Underscore.js template.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function template() {
		butterbean_get_template( 'control' );
	}
}
