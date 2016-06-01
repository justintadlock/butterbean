<# if ( data.label ) { #>
	<span class="butterbean-label">{{ data.label }}</span>
<# } #>

<# if ( data.description ) { #>
	<span class="butterbean-description">{{{ data.description }}}</span>
<# } #>

<# _.each( data.choices, function( palette, choice ) { #>
	<label aria-selected="{{ palette.selected }}">
		<input type="radio" value="{{ choice }}" name="{{ data.field_name }}" <# if ( palette.selected ) { #> checked="checked" <# } #> />

		<span class="butterbean-palette-label">{{ palette.label }}</span>

		<div class="butterbean-palette-block">

			<# _.each( palette.colors, function( color ) { #>
				<span class="butterbean-palette-color" style="background-color: {{ color }}">&nbsp;</span>
			<# } ) #>

		</div>
	</label>
<# } ) #>
