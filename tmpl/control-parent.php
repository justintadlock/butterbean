<label>
	<# if ( data.label ) { #>
		<span class="butterbean-label">{{ data.label }}</span>
	<# } #>

	<select name="{{ data.field_name }}" id="{{ data.field_name }}">

		<# _.each( data.choices, function( choice ) { #>
			<option value="{{ choice.value }}" <# if ( choice.value === data.value ) { #> selected="selected" <# } #>>{{ choice.label }}</option>
		<# } ) #>

	</select>

	<# if ( data.description ) { #>
		<span class="butterbean-description">{{{ data.description }}}</span>
	<# } #>
</label>
