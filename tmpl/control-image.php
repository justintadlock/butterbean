<# if ( data.label ) { #>
	<span class="butterbean-label">{{ data.label }}</span>
	<br />
<# } #>

<# if ( data.description ) { #>
	<span class="butterbean-description">{{{ data.description }}}</span>
	<br />
<# } #>

<input type="hidden" class="butterbean-attachment-id" name="{{ data.field_name }}" value="{{ data.value }}" />

<img class="butterbean-img" src="{{ data.src }}" />
<div class="butterbean-placeholder">{{ data.l10n.placeholder }}</div>

<p>
	<button type="button" class="button button-secondary butterbean-add-media">{{ data.l10n.upload }}</button>
	<button type="button" class="button button-secondary butterbean-change-media">{{ data.l10n.change }}</button>
	<button type="button" class="button button-secondary butterbean-remove-media">{{ data.l10n.remove }}</button>
</p>
