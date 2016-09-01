<# if ( data.label ) { #>
	<span class="butterbean-label">{{ data.label }}</span>
<# } #>

<# if ( data.description ) { #>
	<span class="butterbean-description">{{{ data.description }}}</span>
<# } #>

<?php $month = '<label>
	<span class="screen-reader-text">{{ data.month.label }}</span>
	<select name="{{ data.month.name }}">
		<# _.each( data.month.choices, function( choice ) { #>
			<option value="{{ choice.num }}" <# if ( choice.num === data.month.value ) { #> selected="selected" <# } #>>{{ choice.label }}</option>
		<# } ) #>
	</select>
</label>';

$day = '<label>
	<span class="screen-reader-text">{{ data.day.label }}</span>
	<input type="text" name="{{ data.day.name }}" value="{{ data.day.value }}" {{{ data.day.attr }}} />
</label>';

$year = '<label>
	<span class="screen-reader-text">{{ data.year.label }}</span>
	<input type="text" name="{{ data.year.name }}" value="{{ data.year.value }}" {{{ data.year.attr }}} />
</label>';

$hour = '<label>
	<span class="screen-reader-text">{{ data.hour.label }}</span>
	<input type="text" name="{{ data.hour.name }}" value="{{ data.hour.value }}" {{{ data.hour.attr }}} />
</label>';

$minute = '<label>
	<span class="screen-reader-text">{{ data.minute.label }}</span>
	<input type="text" name="{{ data.minute.name }}" value="{{ data.minute.value }}" {{{ data.minute.attr }}} />
</label>';

$second = '<label>
	<span class="screen-reader-text">{{ data.second.label }}</span>
	<input type="text" name="{{ data.second.name }}" value="{{ data.second.value }}" {{{ data.second.attr }}} />
</label>'; ?>

<# if ( data.show_time ) { #>

	<?php // Translators: 1: month, 2: day, 3: year, 4: hour, 5: minute, 6: second.
	      printf( __( '%1$s %2$s, %3$s @ %4$s:%5$s:%6$s', 'butterbean' ), $month, $day, $year, $hour, $minute, $second );
	?>

<# } else { #>

	<?php // Translators: 1: month, 2: day, 3: year.
              printf( __( '%1$s %2$s, %3$s', 'butterbean' ), $month, $day, $year );
	?>

<# } #>
