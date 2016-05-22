<label>
	<# if ( data.label ) { #>
		<span class="butterbean-label">{{ data.label }}</span>
		<br />
	<# } #>

	<input type="{{ data.type }}" value="{{ data.value }}" {{{ data.attr }}} />

	<# if ( data.description ) { #>
		<br />
		<span class="butterbean-description">{{{ data.description }}}</span>
	<# } #>
</label>
