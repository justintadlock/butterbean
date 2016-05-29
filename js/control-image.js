( function( $ ) {

	var control_class = '.butterbean-control-image';

	$( control_class + ' .butterbean-img' ).each( function() {

		if ( $( this ).attr( 'src' ) ) {
			butterbean_has_image( this );
		} else {
			butterbean_reset_image( this );
		}
	} );

	$( control_class + ' .butterbean-add-media, ' +
	   control_class + ' .butterbean-change-media' ).click( function() {

		butterbean_show_media_modal( this );
	} );

	$( control_class + ' .butterbean-remove-media' ).click( function() {

		butterbean_reset_image( this );
	} );

	function butterbean_get_control_id( element ) {

		return '#' + $( element ).parents( '.butterbean-control' ).attr( 'id' );
	}

	function butterbean_show_media_modal( element ) {

		var control_id = butterbean_get_control_id( element );
		var modal      = '';

		if ( 'undefined' === typeof butterbean_control_image_modal ) {

			butterbean_control_image_modal = [];

		} else if ( 'undefined' !== typeof butterbean_control_image_modal[ control_id ] ) {

			modal = butterbean_control_image_modal[ control_id ];
			modal.open();
			return;
		}

		modal = butterbean_control_image_modal[ control_id ] = wp.media.frames.butterbean_control_image_modal = wp.media( {

			frame : 'select',
			multiple : false,
			editing  : true,
			title    : butterbean_control_image.choose,
			library  : { type : 'image' },
			button   : { text:  butterbean_control_image.set }
		} );

		modal.on( 'select', function() {

			var media = modal.state().get( 'selection' ).first().toJSON();

			console.log( media );

			if ( media.sizes.large ) {
				$( control_id + ' .butterbean-img' ).attr( 'src', media.sizes.large.url );
			} else {
				$( control_id + ' .butterbean-img' ).attr( 'src', media.url ).show();
			}

			$( control_id + ' .butterbean-attachment-id' ).val( media.id );
			$( control_id + ' .butterbean-placeholder' ).hide();
			$( control_id + ' .butterbean-add-media' ).hide();
			$( control_id + ' .butterbean-change-media' ).show();
			$( control_id + ' .butterbean-remove-media' ).show();

		} );

		modal.open();
	}

	function butterbean_has_image( element ) {
		var control_id = butterbean_get_control_id( element );

			$( control_id + ' .butterbean-img' ).show();
			$( control_id + ' .butterbean-placeholder' ).hide();
			$( control_id + ' .butterbean-add-media' ).hide();
			$( control_id + ' .butterbean-change-media' ).show();
			$( control_id + ' .butterbean-remove-media' ).show();
	}

	function butterbean_reset_image( element ) {

		var control_id = butterbean_get_control_id( element );

		$( control_id + ' .butterbean-attachment-id' ).val( '' );
		$( control_id + ' .butterbean-img' ).attr( 'src', '' ).hide();
		$( control_id + ' .butterbean-placeholder' ).show();
		$( control_id + ' .butterbean-add-media' ).show();
		$( control_id + ' .butterbean-change-media' ).hide();
		$( control_id + ' .butterbean-remove-media' ).hide();
	}

}( jQuery ) );
