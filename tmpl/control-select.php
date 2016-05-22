<label>

	<# if ( data.label ) { #>
		<span class="butterbean-label">{{ data.label }}</span>
		<br />
	<# } #>

	<# if ( data.description ) { #>
		<span class="butterbean-description">{{{ data.description }}}</span>
		<br />
	<# } #>

	<select {{{ data.attr }}}>

		<# _.each( data.choices, function( choice, label ) { #>

			<option value="{{ choice }}" <# if ( data.value === choice ) { #> selected="selected" <# } #>>{{ label }}</option>

		<# } ) #>

	</select>
</label>
