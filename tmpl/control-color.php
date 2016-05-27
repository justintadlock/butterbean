<label>
	<# if ( data.label ) { #>
		<span class="butterbean-label">{{ data.label }}</span>
		<br />
	<# } #>

	<# if ( data.description ) { #>
		<span class="butterbean-description">{{{ data.description }}}</span>
		<br />
	<# } #>

	<input {{{ data.attr }}} value="#{{ data.value }}" />
</label>
