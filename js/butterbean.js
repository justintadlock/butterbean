( function( $ ) {

	/* === Underscore Templates === */

	// Make sure we have the data passed in via `wp_localize_script()`, which is the
	// sections and controls JSON.
	if ( 'undefined' !== typeof butterbean_data ) {

		// Set up some objects for our templates.
		var section_templates = {};
		var control_templates = {};

		// Nav template.
		var nav_template = wp.template( 'butterbean-nav' );

		// Loop through each of the managers and add handle templates.
		_.each( butterbean_data.managers, function( manager ) {

			// Set the container ID for this manager.
			var container = '#' + manager.name;

			// Adds the `.butterbean-manager` class to the container (meta box).
			$( container ).addClass( 'butterbean-manager' );

			/* === Create templates. === */

			// Loop through the sections and create a template for each type.
			_.each( manager.sections, function( data ) {

				var type = data.type;

				// Only add a new section template if we have a different section type.
				if ( typeof section_templates.type === 'undefined' ) {
					section_templates[ type ] = wp.template( 'butterbean-section-' + type );
				}
			} );

			// Loop through the controls and create a template for each type.
			_.each( manager.controls, function( data ) {

				var type = data.type;

				// Only add a new control template if we have a different control type.
				if ( typeof control_templates.type === 'undefined' ) {
					control_templates[ type ] = wp.template( 'butterbean-control-' + type );
				}
			} );

			/* === Append the templates. === */

			// Loop through the sections and append the template for each.
			_.each( manager.sections, function( data ) {

				// Use the section type to get the corect template.
				var template = section_templates[ data.type ];

				// Pass the section data to the nav template and section template and append.
				$( container + ' .butterbean-nav'     ).append( nav_template( data ) );
				$( container + ' .butterbean-content' ).append( template( data     ) );
			} );

			// Loop through the controls and append the template for each.
			_.each( manager.controls, function( data ) {

				// Use the control type to get the correct template.
				var template = control_templates[ data.type ];

				// Pass the control data to the control template and append.
				$( container + ' #butterbean-' + data.manager + '-section-' + data.section ).append( template( data ) );
			} );
		} );
	}

	/* ====== Tabs ====== */

	// Looks for `.hndle` and adds the `.butterbean-title` class.
	$( '.butterbean-manager .hndle' ).addClass( 'butterbean-title' );

	// Adds the core WP `.description` class to any `.butterbean-description` elements.
	$( '.butterbean-ui .butterbean-description' ).addClass( 'description' );

	// Hides the tab content.
	$( '.butterbean-section' ).hide();

	// Shows the first tab's content.
	$( '.butterbean-section:first-of-type' ).show();

	// Makes the 'aria-selected' attribute true for the first tab nav item.
	$( '.butterbean-nav :first-child' ).attr( 'aria-selected', 'true' );

	// Copies the current tab item title to the box header.
	$( '.butterbean-title' ).append( ' <span class="butterbean-which-tab" />' );

	$( '.butterbean-which-tab' ).each( function() {

		var text = $( this ).parents( '.butterbean-manager' ).find( '.butterbean-nav :first-child a' ).text();

		$( this ).text( text );
	} );

	// When a tab nav item is clicked.
	$( '.butterbean-nav li a' ).click(
		function( j ) {

			// Prevent the default browser action when a link is clicked.
			j.preventDefault();

			// Get the manager.
			var manager = $( this ).parents( '.butterbean-manager' );

			// Hide all tab content.
			$( manager ).find( '.butterbean-section' ).hide();

			// Find the tab content that matches the tab nav item and show it.
			$( manager ).find( $( this ).attr( 'href' ) ).show();

			// Set the `aria-selected` attribute to false for all tab nav items.
			$( manager ).find( '.butterbean-nav li' ).attr( 'aria-selected', 'false' );

			// Set the `aria-selected` attribute to true for this tab nav item.
			$( this ).parent().attr( 'aria-selected', 'true' );

			// Copy the current tab item title to the box header.
			$( manager ).find( '.butterbean-which-tab' ).text( $( this ).text() );
		}
	); // click()

}( jQuery ) );
