<label>

	<# if ( data.label ) { #>
		<span class="butterbean-label">{{ data.label }}</span>
	<# } #>

	<# if ( data.description ) { #>
		<span class="butterbean-description">{{{ data.description }}}</span>
	<# } #>

	<select {{{ data.attr }}}>

		<# _.each( data.choices, function( label, choice ) { #>

			<option value="{{ choice }}" <# if ( data.value === choice ) { #> selected="selected" <# } #>>{{ label }}</option>

		<# } ) #>

	</select>
</label>
