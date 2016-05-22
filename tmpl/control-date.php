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
