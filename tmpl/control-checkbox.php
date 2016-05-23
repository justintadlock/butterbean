<label>
	<input type="checkbox" value="true" {{{ data.attr }}} <# if ( data.value ) { #> checked="checked" <# } #> />

<# console.log( data ); #>
	<# if ( data.label ) { #>
		<span class="butterbean-label">{{ data.label }}</span>
	<# } #>

	<# if ( data.description ) { #>
		<br />
		<span class="butterbean-description">{{{ data.description }}}</span>
	<# } #>
</label>
