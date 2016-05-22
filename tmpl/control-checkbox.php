<label>
	<input type="checkbox" value="{{ data.value }}" {{{ data.attr }}} <# if ( data.value ) { #> checked="checked" <# } #> />

	<# if ( data.label ) { #>
		<span class="butterbean-label">{{ data.label }}</span>
	<# } #>

	<# if ( data.description ) { #>
		<br />
		<span class="butterbean-description">{{{ data.description }}}</span>
	<# } #>
</label>
