/**
 * Date picker and dialog code for the admin screen.
 *
 * @since 1.2.0
 */

/* global WordPointsResetPointsAdminScreenL10n, jQuery */

jQuery( document ).ready( function( $ ) {
	var $currentDelete = false,
		$resetForm = $( '#reset-points-type'),
		$dialogTemplate;

	$resetForm.find( 'input[type=date]' ).datepicker(
		{ dateFormat: 'yy-mm-dd', minDate: 1 }
	);

	$resetForm.find( '.delete' ).click( function( event ) {
		if ( $currentDelete === false ) {

			$currentDelete = $( this );

			event.preventDefault();

			if ( ! $dialogTemplate ) {
				$dialogTemplate = $( '<div />' )
					.attr( 'title', WordPointsResetPointsAdminScreenL10n.dialogTitle );
				$( '<p />' )
					.text( WordPointsResetPointsAdminScreenL10n.dialogTextTop )
					.appendTo( $dialogTemplate );
				$( '<p />' )
					.append( $( '<strong />' ) )
					.appendTo( $dialogTemplate );
				$( '<p />' )
					.text( WordPointsResetPointsAdminScreenL10n.dialogTextBottom )
					.appendTo( $dialogTemplate );
			}

			$dialogTemplate.find( 'p strong' ).text(
				$currentDelete.closest( 'tr' ).find( 'th' ).text() + ': ' + $currentDelete.closest( 'tr' ).find( 'input[type=number]' ).val()
			);

			$dialogTemplate.dialog({
				dialogClass: 'wp-dialog wordpoints-points-reset-dialog',
				resizable: false,
				draggable: false,
				height: 250,
				modal: true,
				buttons: [
					{
						text: WordPointsResetPointsAdminScreenL10n.resetButton,
						class: 'button-primary',
						click: function() {
							$( this ).dialog( 'close' );
							$currentDelete.click();
							$currentDelete = false;
						}
					},
					{
						text: WordPointsResetPointsAdminScreenL10n.cancelButton,
						class: 'button-secondary',
						click: function() {
							$( this ).dialog( 'close' );
							$currentDelete = false;
						}
					}
				]
			});
		}
	});
});