( function( $ ) {

	// Set up our variables.
	var models      = { managers : {}, sections : {}, controls : {} },
	    collections = { managers : {}, sections : {}, controls : {} },
	    views       = { managers : {}, sections : {}, controls : {} },
	    templates   = { managers : {}, sections : {}, controls : {} };

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
	collections.managers = new Manager_Collection();

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
			if ( typeof templates.managers[ manager.type ] === 'undefined' ) {

				templates.managers[ manager.type ] = wp.template( 'butterbean-manager-' + manager.type );
			}

			// Add a new manager model.
			models.managers[ manager.name ] = new Manager_Model( manager );

			// Add manager model to collection.
			collections.managers.add( models.managers[ manager.name ] );

			// Add a new manager view.
			views.managers[ manager.name ] = new Manager_View( {
				model    : models.managers[ manager.name ],
				el       : container,
				template : templates.managers[ manager.type ]
			} );

			collections.sections[ manager.name ] = new Section_Collection();

			// Adds the `.butterbean-ui` class to the container (meta box).
			$( container ).addClass( 'butterbean-ui' );

			/* === Create templates. === */

			// Loop through the sections and create a template for each type.
			_.each( manager.sections, function( data ) {

				// Append manager nav item.
				$( container + ' .butterbean-nav' ).append( nav_template( data ) );

				// Only add a new section template if we have a different section type.
				if ( typeof templates.sections[ data.type ] === 'undefined' ) {
					templates.sections[ data.type ] = wp.template( 'butterbean-section-' + data.type );
				}

				// Add a new section model.
				models.sections[ data.name ] = new Section_Model( data );

				// Add section model to collection.
				collections.sections[ manager.name ].add( models.sections[ data.name ] );

				// Add a new manager view.
				views.sections[ data.name ] = new Section_View( {
					model    : models.sections[ data.name ],
					el       : container + ' .butterbean-content',
					template : templates.sections[ data.type ]
				} );

				// Add a control collection for this section.
				collections.controls[ manager.name + '-' + data.name ] = new Control_Collection();
			} );

			// Loop through the controls and create a template for each type.
			_.each( manager.controls, function( data ) {

				// Only add a new control template if we have a different control type.
				if ( typeof templates.controls[ data.type ] === 'undefined' ) {
					templates.controls[ data.type ] = wp.template( 'butterbean-control-' + data.type );
				}

				// Add a new control model.
				models.controls[ data.name ] = new Control_Model( data );

				// Add control model to collection.
				collections.controls[ data.manager + '-' + data.section ].add( models.controls[ data.name ] );

				// Add a new manager view.
				views.controls[ data.name ] = new Control_View( {
					model    : models.controls[ data.name ],
					el       : container + ' #butterbean-' + data.manager + '-section-' + data.section,
					template : templates.controls[ data.type ]
				} );
			} );
		} );
	}

	// Dev.
	//var butterbean = { models : models, collections : collections, views : views, templates : templates };
	//console.log( butterbean );

	/* ====== Tabs ====== */

	// Looks for `.hndle` and adds the `.butterbean-title` class.
	$( '.butterbean-ui .hndle' ).addClass( 'butterbean-title' );

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

		var text = $( this ).parents( '.butterbean-ui' ).find( '.butterbean-nav :first-child a' ).text();

		$( this ).text( text );
	} );

	// When a tab nav item is clicked.
	$( '.butterbean-nav li a' ).click(
		function( j ) {

			// Prevent the default browser action when a link is clicked.
			j.preventDefault();

			// Get the manager.
			var manager = $( this ).parents( '.butterbean-ui' );

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
