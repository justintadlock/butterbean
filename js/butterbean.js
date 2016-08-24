window.butterbean = window.butterbean || {};

( function() {

	// Bail if we don't have the JSON, which is passed in via `wp_localize_script()`.
	if ( _.isUndefined( butterbean_data ) ) {
		return;
	}

	/**
	 * Our global object.  The `butterbean` object is just a wrapper to house everything
	 * in a single namespace.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    object
	 */
	var api = butterbean = {

		/**
		 * Houses the manager, section, and control views based on the `type`.
		 *
		 * @since  1.0.0
		 * @access public
		 * @var    object
		 */
		views : { managers : {}, sections : {}, controls : {} }
	};

	/**
	 * Creates a new manager view.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $type
	 * @param  object  $args
	 * @return void
	 */
	api.views.add_manager = function( type, args ) {

		if ( 'default' !== type )
			this.managers[ type ] = this.managers.default.extend( args );
	};

	/**
	 * Returns a manager view.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $type
	 * @return object
	 */
	api.views.get_manager = function( type ) {

		if ( this.manager_exists( type ) )
			return this.managers[ type ];

		return this.managers.default;
	};

	/**
	 * Removes a manager view.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $type
	 * @return void
	 */
	api.views.remove_manager = function( type ) {

		if ( 'default' !== type && this.manager_exists( type ) )
			delete this.managers[ type ];
	};

	/**
	 * Checks if a manager view exists.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $type
	 * @return bool
	 */
	api.views.manager_exists = function( type ) {

		return this.managers.hasOwnProperty( type );
	};

	/**
	 * Creates a new section view.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $type
	 * @param  object  $args
	 * @return void
	 */
	api.views.add_section = function( type, args ) {

		if ( 'default' !== type )
			this.sections[ type ] = this.sections.default.extend( args );
	};

	/**
	 * Returns a section view.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $type
	 * @return object
	 */
	api.views.get_section = function( type ) {

		if ( this.section_exists( type ) )
			return this.sections[ type ];

		return this.sections.default;
	};

	/**
	 * Removes a section view.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $type
	 * @return void
	 */
	api.views.remove_section = function( type ) {

		if ( 'default' !== type && this.section_exists( type ) )
			delete this.sections[ type ];
	};

	/**
	 * Checks if a section view exists.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $type
	 * @return bool
	 */
	api.views.section_exists = function( type ) {

		return this.sections.hasOwnProperty( type );
	};

	/**
	 * Creates a new control view.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $type
	 * @param  object  $args
	 * @return void
	 */
	api.views.add_control = function( type, args ) {

		if ( 'default' !== type )
			this.controls[ type ] = this.controls.default.extend( args );
	};

	/**
	 * Returns a control view.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $type
	 * @return object
	 */
	api.views.get_control = function( type ) {

		if ( this.control_exists( type ) )
			return this.controls[ type ];

		return this.controls.default;
	};

	/**
	 * Removes a control view.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $type
	 * @return void
	 */
	api.views.remove_control = function( type ) {

		if ( 'default' !== type && this.control_exists( type ) )
			delete this.controls[ type ];
	};

	/**
	 * Checks if a control view exists.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $type
	 * @return bool
	 */
	api.views.control_exists = function( type ) {

		return this.controls.hasOwnProperty( type );
	};

	/**
	 * Renders our managers, sections, and controls.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	api.render = function() {

		// Loop through each of the managers and render their api.views.
		_.each( butterbean_data.managers, function( data ) {

			// Create a new manager model.
			var manager = new Manager( data );

			// Get the manager view callback.
			var callback = api.views.get_manager( data.type );

			// Create a new manager view.
			var view = new callback( { model : manager } );

			// Add the `.butterbean-ui` class to the meta box.
			document.getElementById( 'butterbean-ui-' + manager.get( 'name' ) ).className += ' butterbean-ui';

			// Render the manager view.
			document.querySelector( '#butterbean-ui-' + manager.get( 'name' ) + ' .inside' ).appendChild( view.render().el );

			// Render the manager subapi.views.
			view.subview_render();
		} );
	};

	/* === Templates === */

	// Templates.
	var templates = { managers : {}, sections : {}, controls : {} };

	// Nav template.
	var nav_template = wp.template( 'butterbean-nav' );

	/* === Models === */

	// Manager model (each manager is housed within a meta box).
	var Manager = Backbone.Model.extend( {
		defaults : {
			name     : '',
			type     : '',
			sections : {},
			controls : {}
		}
	} );

	// Section model (each section belongs to a manager).
	var Section = Backbone.Model.extend( {
		defaults : {
			name        : '',
			type        : '',
			label       : '',
			description : '',
			icon        : '',
			manager     : '',
			active      : '',
			selected    : false
		}
	} );

	// Control model (each control belongs to a manager and section).
	var Control = Backbone.Model.extend( {
		defaults : {
			name        : '',
			type        : '',
			label       : '',
			description : '',
			icon        : '',
			value       : '',
			choices     : {},
			attr        : '',
			active      : '',
			manager     : '',
			section     : '',
			setting     : ''
		}
	} );

	/* === Collections === */

	/**
	 * Stores our collection of section models.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    object
	 */
	var Sections = Backbone.Collection.extend( {
		model : Section
	} );

	/* === Views === */

	/**
	 * The default manager view.  Other views can extend this using the
	 * `butterbean.views.add_manager()` function.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    object
	 */
	api.views.managers[ 'default' ] = Backbone.View.extend( {

		// Wrapper element for the manager view.
		tagName : 'div',

		// Adds some custom attributes to the wrapper.
		attributes : function() {
			return {
				'id'    : 'butterbean-manager-' + this.model.get( 'name' ),
				'class' : 'butterbean-manager butterbean-manager-' + this.model.get( 'type' )
			};
		},

		// Initializes the view.
		initialize : function() {

			var type = this.model.get( 'type' );

			// If there's not yet a template for this manager type, create it.
			if ( _.isUndefined( templates.managers[ type ] ) )
				templates.managers[ type ] = wp.template( 'butterbean-manager-' + type );

			// Get the manager template.
			this.template = templates.managers[ type ];
		},

		// Renders the manager.
		render : function() {
			this.el.innerHTML = this.template( this.model.toJSON() );
			return this;
		},

		// Renders the manager's sections and controls.
		// Important! This may change drastically in the future, possibly even
		// taken out of the manager view altogether.  It's for this reason that
		// it's not recommended to create custom views for managers right now.
		subview_render : function() {

			// Create a new section collection.
			var sections = new Sections();

			// Loop through each section and add it to the collection.
			_.each( this.model.get( 'sections' ), function( data ) {

				sections.add( new Section( data ) );
			} );

			// Loop through each section in the collection and render its view.
			sections.forEach( function( section, i ) {

				// Create a new nav item view for the section.
				var nav_view = new Nav_View( { model : section } );

				// Render the nav item view.
				document.querySelector( '#butterbean-ui-' + section.get( 'manager' ) + ' .butterbean-nav'     ).appendChild( nav_view.render().el     );

				// Get the section view callback.
				var callback = api.views.get_section( section.attributes.type );

				// Create a new section view.
				var view = new callback( { model : section } );

				// Render the section view.
				document.querySelector( '#butterbean-ui-' + section.get( 'manager' ) + ' .butterbean-content' ).appendChild( view.render().el );

				// Call the section view's ready method.
				view.ready();

				// If the first model, set it to selected.
				section.set( 'selected', 0 === i );
			}, this );

			// Loop through each control for the manager and render its view.
			_.each( this.model.get( 'controls' ), function( data ) {

				// Create a new control model.
				var control = new Control( data );

				// Get the control view callback.
				var callback = api.views.get_control( data.type );

				// Create a new control view.
				var view = new callback( { model : control } );

				// Render the view.
				document.getElementById( 'butterbean-' + control.get( 'manager' ) + '-section-' + control.get( 'section' ) ).appendChild( view.render().el );

				// Call the view's ready method.
				view.ready();
			} );

			return this;
		}
	} );

	/**
	 * The default section view.  Other views can extend this using the
	 * `butterbean.views.add_section()` function.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    object
	 */
	api.views.sections[ 'default' ] = Backbone.View.extend( {

		// Wrapper element for the section.
		tagName : 'div',

		// Adds custom attributes for the section wrapper.
		attributes : function() {
			return {
				'id'          : 'butterbean-' + this.model.get( 'manager' ) + '-section-' + this.model.get( 'name' ),
				'class'       : 'butterbean-section butterbean-section-' + this.model.get( 'type' ),
				'aria-hidden' : ! this.model.get( 'selected' )
			};
		},

		// Initializes the view.
		initialize : function() {

			// Add an event for when the model changes.
			this.model.on( 'change', this.onchange, this );

			// Get the section type.
			var type = this.model.get( 'type' );

			// If there's no template for this section type, create it.
			if ( _.isUndefined( templates.sections[ type ] ) )
				templates.sections[ type ] = wp.template( 'butterbean-section-' + type );

			// Gets the section template.
			this.template = templates.sections[ type ];
		},

		// Renders the section.
		render : function() {

			// Only render template if model is active.
			if ( this.model.get( 'active' ) )
				this.el.innerHTML = this.template( this.model.toJSON() );

			return this;
		},

		// Executed when the model changes.
		onchange : function() {

			// Set the view's `aria-hidden` attribute based on whether the model is selected.
			this.el.setAttribute( 'aria-hidden', ! this.model.get( 'selected' ) );
		},

		// Function that is executed *after* the view has been rendered.
		// This is meant to be overwritten in sub-views.
		ready : function() {}
	} );

	/**
	 * The nav item view for each section.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    object
	 */
	var Nav_View = Backbone.View.extend( {

		// Sets the template used.
		template : nav_template,

		// Wrapper element for the nav item.
		tagName : 'li',

		// Sets some custom attributes for the nav item wrapper.
		attributes : function() {
			return {
				'aria-selected' : this.model.get( 'selected' )
			};
		},

		// Initializes the nav item view.
		initialize : function() {
			this.model.on('change', this.render, this);
			this.model.on('change', this.onchange, this);
		},

		// Renders the nav item.
		render : function() {

			// Only render template if model is active.
			if ( this.model.get( 'active' ) )
				this.el.innerHTML = this.template( this.model.toJSON() );

			return this;
		},

		// Custom events.
		events : {
			'click a' : 'onselect'
		},

		// Executed when the section model changes.
		onchange : function() {

			// Set the `aria-selected` attibute based on the model selected state.
			this.el.setAttribute( 'aria-selected', this.model.get( 'selected' ) );
		},

		// Executed when the link for the nav item is clicked.
		onselect : function( event ) {
			event.preventDefault();

			// Loop through each of the models in the collection and set them to inactive.
			_.each( this.model.collection.models, function( m ) {

				m.set( 'selected', false );
			}, this );

			// Set this view's model to selected.
			this.model.set( 'selected', true );
		}
	} );

	/**
	 * The default control view.  Other views can extend this using the
	 * `butterbean.views.add_control()` function.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    object
	 */
	api.views.controls[ 'default' ] = Backbone.View.extend( {

		// Wrapper element for the control.
		tagName : 'div',

		// Custom attributes for the control wrapper.
		attributes : function() {
			return {
				'id'    : 'butterbean-control-' + this.model.get( 'name' ),
				'class' : 'butterbean-control butterbean-control-' + this.model.get( 'type' )
			};
		},

		// Initiazlies the control view.
		initialize : function() {
			var type = this.model.get( 'type' );

			// Only add a new control template if we have a different control type.
			if ( _.isUndefined( templates.controls[ type ] ) )
				templates.controls[ type ] = wp.template( 'butterbean-control-' + type );

			// Get the control template.
			this.template = templates.controls[ type ];


			// Bind changes so that the view is re-rendered when the model changes.
			_.bindAll( this, 'render' );
			this.model.bind( 'change', this.render );
		},

		// Renders the control template.
		render : function() {

			// Only render template if model is active.
			if ( this.model.get( 'active' ) )
				this.el.innerHTML = this.template( this.model.toJSON() );

			return this;
		},

		// Function that is executed *after* the view has been rendered.
		// This is meant to be overwritten in sub-views.
		ready : function() {}
	} );

	/**
	 * Adds the color control view.
	 *
	 * @since  1.0.0
	 */
	api.views.add_control( 'color', {

		// Calls the core WP color picker for the control's input.
		ready : function() {

			jQuery( '#' + this.el.id + ' .butterbean-color-picker' ).wpColorPicker();
		}
	} );

	/**
	 * Adds the color palette view.
	 *
	 * @since  1.0.0
	 */
	api.views.add_control( 'palette', {

		// Adds custom events.
		events : {
			'change input' : 'onselect'
		},

		// Executed when one of the color palette's value has changed.
		// These are radio inputs.
		onselect : function() {

			// Get the value of the input.
			var value = document.querySelector( '#' + this.el.id + ' input:checked' ).getAttribute( 'value' );

			// Get all choices.
			var choices = this.model.get( 'choices' );

			// Loop through choices and change the selected value.
			_.each( choices, function( choice, key ) {
				choice.selected = key === value;
			} );

			// Because `choices` is an array, it's not recognized as a change.  So, we
			// have to manually trigger a change here so that the view gets re-rendered.
			this.model.set( 'choices', choices ).trigger( 'change', this.model );
		}
	} );

	/**
	 * Adds the image control view.
	 *
	 * @since  1.0.0
	 */
	api.views.add_control( 'image', {

		// Adds custom events.
		events : {
			'click .butterbean-add-media'    : 'showmodal',
			'click .butterbean-change-media' : 'showmodal',
			'click .butterbean-remove-media' : 'removemedia'
		},

		// Executed when the show modal button is clicked.
		showmodal : function() {


			// If we already have a media modal, open it.
			if ( ! _.isUndefined( this.media_modal ) ) {

				this.media_modal.open();
				return;
			}

			// Create a new media modal.
			this.media_modal = wp.media( {
				frame    : 'select',
				multiple : false,
				editing  : true,
				title    : this.model.get( 'l10n' ).choose,
				library  : { type : 'image' },
				button   : { text:  this.model.get( 'l10n' ).set }
			} );

			// Runs when an image is selected in the media modal.
			this.media_modal.on( 'select', function() {

				// Gets the JSON data for the first selection.
				var media = this.media_modal.state().get( 'selection' ).first().toJSON();

				// Updates the model for the view.
				this.model.set( {
					src   : media.sizes.large ? media.sizes.large.url : media.url,
					alt   : media.alt,
					value : media.id
				} );
			}, this );

			// Opens the media modal.
			this.media_modal.open();
		},

		// Executed when the remove media button is clicked.
		removemedia : function() {

			// Updates the model for the view.
			this.model.set( { src : '', alt : '', value : '' } );
		}
	} );

}() );
