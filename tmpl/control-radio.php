<# if ( data.label ) { #>
	<span class="butterbean-label">{{ data.label }}</span>
	<br />
<# } #>

<# if ( data.description ) { #>
	<span class="butterbean-description">{{{ data.description }}}</span>
	<br />
<# } #>

<ul class="butterbean-radio-list">

	<# _.each( data.choices, function( choice, label ) { #>

		<li>
			<label>
				<input type="radio" value="{{ choice }}" name="butterbean_{{ data.manager }}_setting_{{ data.setting }}" <# if ( data.value === choice ) { #> checked="checked" <# } #> />
				{{ label }}
			</label>
		</li>

	<# } ) #>

</ul>
