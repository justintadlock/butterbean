( function( $ ) {

	// Bail if we don't have the JSON, which is passed in via `wp_localize_script()`.
	if ( 'undefined' === typeof butterbean_data ) {
		return;
	}

	/* === Backbone + Underscore === */

	// Set up a variable to house our templates.
	var templates = { managers : {}, sections : {}, controls : {} };

	// Nav template.
	var nav_template = wp.template( 'butterbean-nav' );

	/* === Models === */

	// Manager model (each manager is housed within a meta box).
	var Manager_Model = Backbone.Model.extend( {
		defaults: {
			name     : '',
			type     : '',
			sections : {},
			controls : {}
		}
	} );

	// Section model (each section belongs to a manager).
	var Section_Model = Backbone.Model.extend( {
		defaults: {
			name        : '',
			type        : '',
			label       : '',
			description : '',
			icon        : '',
			manager     : ''
		}
	} );

	// Control model (each control belongs to a manager and section).
	var Control_Model = Backbone.Model.extend( {
		defaults: {
			name        : '',
			type        : '',
			label       : '',
			description : '',
			icon        : '',
			value       : '',
			choices     : {},
			attr        : '',
			manager     : '',
			section     : '',
			setting     : ''
		}
	} );

	/* === Collections === */

	// Collection of managers.
	var Manager_Collection = Backbone.Collection.extend( {
		model : Manager_Model
	} );

	/* === Views === */

	// Manager collection view. Handles the output for all managers.
	var Manager_Collection_View = Backbone.View.extend( {
		collection : null,
		render     : function() {

			jQuery( this.el ).empty();

			// Loop through each manager in the collection and render its view.
			this.collection.forEach( function( manager ) {

				var view = new Manager_View( {
					model : manager,
					el    : '#butterbean-ui-' + manager.attributes.name + ' .inside'
				} );

				view.render();
			} );

			return this;
		}
	} );

	// Manager view.  Handles the output of a manager.
	var Manager_View = Backbone.View.extend( {
		initialize : function( options ) {

			var type = this.model.attributes.type;

			if ( 'undefined' === typeof templates.managers[ type ] ) {
				templates.managers[ type ] = wp.template( 'butterbean-manager-' + type );
			}

			this.template = templates.managers[ type ];
		},
		render : function() {
			this.$el.append( this.template( this.model.toJSON() ) );

			// Loop through each section for the manager and render its view.
			_.each( this.model.attributes.sections, function( data ) {

				var section = new Section_Model( data );

				$( '#butterbean-ui-' + section.attributes.manager + ' .butterbean-nav' ).append( nav_template( section.attributes ) );

				var view = new Section_View( {
					model : section,
					el    : '#butterbean-ui-' + section.attributes.manager + ' .butterbean-content'
				} );

				view.render();
			} );

			// Loop through each control for the manager and render its view.
			_.each( this.model.attributes.controls, function( data ) {

				var control = new Control_Model( data );

				var view = new Control_View( {
					model : control,
					el    : '#butterbean-' + control.attributes.manager + '-section-' + control.attributes.section
				} );

				view.render();
			} );

			return this;
		}
	} );

	// Section view.  Handles the output of a section.
	var Section_View = Backbone.View.extend( {
		initialize: function( options ) {

			var type = this.model.attributes.type;

			if ( 'undefined' === typeof templates.sections[ type ] ) {
				templates.sections[ type ] = wp.template( 'butterbean-section-' + type );
			}

			this.template = templates.sections[ type ];
		},
		render: function() {
			this.$el.append( this.template( this.model.toJSON() ) );
		}
	} );

	// Control view. Handles the output of a control.
	var Control_View = Backbone.View.extend( {
		initialize: function( options ) {
			var type = this.model.attributes.type;

			// Only add a new control template if we have a different control type.
			if ( 'undefined' === typeof templates.controls[ type ] ) {
				templates.controls[ type ] = wp.template( 'butterbean-control-' + type );
			}

			this.template = templates.controls[ type ];
		},
		render: function(){
			this.$el.append( this.template( this.model.toJSON() ) );
		}
	} );

	// Create a new manager collection.
	var managers = new Manager_Collection();

	// Loop through each of the managers and add it to the collection.
	_.each( butterbean_data.managers, function( manager ) {

		// Add a new manager model to the managers collection.
		managers.add( new Manager_Model( manager ) );

		// Adds the `.butterbean-ui` class to the container (meta box).
		$( '#butterbean-ui-' + manager.name ).addClass( 'butterbean-ui' );
	} );

	// Create a new view for the manager collection.
	var view = new Manager_Collection_View( {
		collection : managers
	} );

	// Render the managers.
	view.render();

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
