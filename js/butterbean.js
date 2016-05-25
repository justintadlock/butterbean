( function( $ ) {

	// Set up our variables.
	var manager_models      = {},
	    manager_collection  = {},
	    manager_views       = {},
	    manager_models      = {},
	    manager_collection  = {},
	    manager_views       = {},
	    manager_templates   = {},
	    section_models      = {},
	    section_collections = {},
	    section_views       = {},
	    section_templates   = {},
	    control_models      = {},
	    control_collections = {},
	    control_views       = {},
	    control_templates   = {};

	// Models.
	var ButterBean_Model = Backbone.Model.extend( {} );
	var Manager_Model    = ButterBean_Model.extend( {} );
	var Section_Model    = ButterBean_Model.extend( {} );
	var Control_Model    = ButterBean_Model.extend( {} );

	// Collections.
	var Manager_Collection = Backbone.Collection.extend( { model : Manager_Model } );
	var Section_Collection = Backbone.Collection.extend( { model : Section_Model } );
	var Control_Collection = Backbone.Collection.extend( { model : Control_Model } );

	// Views
	var ButterBean_View = Backbone.View.extend( {
		initialize : function( options ) {
			this.template = options.template;
			this.render();
		},
		render: function() {
			this.$el.append( this.template( this.model.attributes ) );
			return this;
	      }
	} );

	var Manager_View = ButterBean_View.extend( {} );
	var Section_View = ButterBean_View.extend( {} );
	var Control_View = ButterBean_View.extend( {} );

	// Create new manager collection.
	manager_collection = new Manager_Collection();

	/* === Underscore Templates === */

	// Make sure we have the data passed in via `wp_localize_script()`, which is the
	// sections and controls JSON.
	if ( 'undefined' !== typeof butterbean_data ) {

		// Nav template.
		var nav_template = wp.template( 'butterbean-nav' );

		// Loop through each of the managers and handle templates.
		_.each( butterbean_data.managers, function( manager ) {

			// Set the container ID for this manager.
			var container = '#' + manager.name;

			// Only add a new manager template if we have a different manager type.
			if ( typeof manager_templates[ manager.type ] === 'undefined' ) {

				manager_templates[ manager.type ] = wp.template( 'butterbean-manager-' + manager.type );
			}

			// Add a new manager model.
			manager_models[ manager.name ] = new Manager_Model( manager );

			// Add manager model to collection.
			manager_collection.add( manager_models[ manager.name ] );

			// Add a new manager view.
			manager_views[ manager.name ] = new Manager_View( {
				model    : manager_models[ manager.name ],
				el       : container,
				template : manager_templates[ manager.type ]
			} );

			section_collections[ manager.name ] = new Section_Collection();

			// Adds the `.butterbean-manager` class to the container (meta box).
			$( container ).addClass( 'butterbean-manager' );

			/* === Create templates. === */

			// Loop through the sections and create a template for each type.
			_.each( manager.sections, function( data ) {

				// Append manager nav item.
				$( container + ' .butterbean-nav' ).append( nav_template( data ) );

				// Only add a new section template if we have a different section type.
				if ( typeof section_templates[ data.type ] === 'undefined' ) {
					section_templates[ data.type ] = wp.template( 'butterbean-section-' + data.type );
				}

				// Add a new manager model.
				section_models[ data.name ] = new Section_Model( data );

				// Add section model to collection.
				section_collections[ manager.name ].add( section_models[ data.name ] );

				// Add a new manager view.
				section_views[ data.name ] = new Section_View( {
					model    : section_models[ data.name ],
					el       : container + ' .butterbean-content',
					template : section_templates[ data.type ]
				} );

				// Add a control collection for this section.
				control_collections[ manager.name + '-' + data.name ] = new Control_Collection();
			} );

			// Loop through the controls and create a template for each type.
			_.each( manager.controls, function( data ) {

				// Only add a new control template if we have a different control type.
				if ( typeof control_templates[ data.type ] === 'undefined' ) {
					control_templates[ data.type ] = wp.template( 'butterbean-control-' + data.type );
				}

				// Add a new manager model.
				control_models[ data.name ] = new Section_Model( data );

				// Add control model to collection.
				control_collections[ data.manager + '-' + data.section ].add( control_models[ data.name ] );

				// Add a new manager view.
				control_views[ data.name ] = new Section_View( {
					model    : control_models[ data.name ],
					el       : container + ' #butterbean-' + data.manager + '-section-' + data.section,
					template : control_templates[ data.type ]
				} );
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
