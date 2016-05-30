<?php
/**
 * Date control class.
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
class ButterBean_Control_Date extends ButterBean_Control {

	/**
	 * The type of control.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $type = 'date';

	/**
	 * Adds custom data to the json array. This data is passed to the Underscore template.
	 *
	 * @since  1.0.0
	 * @access public
	 * @globl  object  $wp_locale
	 * @return void
	 */
	public function to_json() {
		global $wp_locale;

		parent::to_json();

		// Get project start/end dates.
		$date = $this->get_value( $this->manager->post_id );

		// Get the individual years, months, and days.
		$year  = $date ? mysql2date( 'Y', $date, false ) : '';
		$month = $date ? mysql2date( 'm', $date, false ) : '';
		$day   = $date ? mysql2date( 'd', $date, false ) : '';

		// Year
		$this->json['year'] = array(
			'value' => esc_attr( $year ),
			'label' => esc_html__( 'Year', 'butterbean' ),
			'name'  => esc_attr( "butterbean_{$this->manager->name}_setting_{$this->setting}_year" ),
			'attr'  => sprintf( 'placeholder="%s" size="4" maxlength="4" autocomplete="off"', esc_attr( date_i18n( 'Y' ) ) )
		);

		// Month
		$this->json['month'] = array(
			'value'   => esc_attr( $month ),
			'name'    => esc_attr( "butterbean_{$this->manager->name}_setting_{$this->setting}_month" ),
			'label'   => esc_html__( 'Month', 'butterbean' ),
			'choices' => array(
				array(
					'num'   => '',
					'label' => ''
				)
			)
		);

		for ( $i = 1; $i < 13; $i = $i +1 ) {

			$monthnum  = zeroise( $i, 2 );
			$monthtext = $wp_locale->get_month_abbrev( $wp_locale->get_month( $i ) );

			$this->json['month']['choices'][] = array(
				'num'   => $monthnum,
				'label' => $monthtext
			);
		}

		// Day
		$this->json['day'] = array(
			'value' => esc_attr( $day ),
			'name'  => esc_attr( "butterbean_{$this->manager->name}_setting_{$this->setting}_day" ),
			'label' => esc_html__( 'Day', 'butterbean' ),
			'attr'  => sprintf( 'placeholder="%s" size="2" maxlength="2" autocomplete="off"', esc_attr( date_i18n( 'd' ) ) )
		);
	}
}
