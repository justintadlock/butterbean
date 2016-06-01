<# if ( data.label ) { #>
	<span class="butterbean-label">{{ data.label }}</span>
<# } #>

<# if ( data.description ) { #>
	<span class="butterbean-description">{{{ data.description }}}</span>
<# } #>

<div class="butterbean-multi-avatars-wrap">

	<# _.each( data.choices, function( user ) { #>

		<label>
			<input type="checkbox" value="{{ user.id }}" name="{{ data.field_name }}[]" <# if ( -1 !== _.indexOf( data.value, user.id ) ) { #> checked="checked" <# } #> />

			<span class="screen-reader-text">{{ user.name }}</span>

			{{{ user.avatar }}}
		</label>

	<# } ) #>

</div><!-- .butterbean-multi-avatars-wrap -->
