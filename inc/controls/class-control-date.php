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
class ButterBean_Control_Date extends ButterBean_Control {

	public $type = 'date';

	public function to_json() {

		parent::to_json();

		// Get project start/end dates.
		$date = $this->get_value( $this->manager->post_id );

		// Get the individual years, months, and days.
		$year  = $date ? mysql2date( 'Y', $date, false ) : '';
		$month = $date ? mysql2date( 'm', $date, false ) : '';
		$day   = $date ? mysql2date( 'd', $date, false ) : '';

		global $wp_locale;

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

	public function template() { ?>

		<# if ( data.label ) { #>
			<span class="butterbean-label">{{ data.label }}</span>
			<br />
		<# } #>

		<label>
			<span class="screen-reader-text">{{ data.month.label }}</span>
			<select name="{{ data.month.name }}">
				<# _.each( data.month.choices, function( choice ) { #>
					<option value="{{ choice.num }}" <# if ( choice.num === data.month.value ) { #> selected="selected" <# } #>>{{ choice.label }}</option>
				<# } ) #>
			</select>
		</label>

		<label>
			<span class="screen-reader-text">{{ data.day.label }}</span>
			<input type="text" name="{{ data.day.name }}" value="{{ data.day.value }}" {{{ data.day.attr }}} />
		</label>

		<label>
			<span class="screen-reader-text">{{ data.year.label }}</span>
			<input type="text" name="{{ data.year.name }}" value="{{ data.year.value }}" {{{ data.year.attr }}} />
		</label>

		<# if ( data.description ) { #>
			<br />
			<span class="butterbean-description">{{{ data.description }}}</span>
		<# } #>
	<?php }
}
