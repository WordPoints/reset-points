/**
 * Date picker and dialog code for the admin screen.
 *
 * @since 1.2.0
 */

/* global WordPointsResetPointsAdminScreenL10n, jQuery */

jQuery( document ).ready( function( $ ) {
	var $currentDelete = false;

	$( '#reset-points-type input[type=date]' ).datepicker( { dateFormat: 'yy-mm-dd' } );
	$( '#reset-points-type .delete' ).click( function( event ) {
		if ( $currentDelete === false ) {

			$currentDelete = $( this );

			event.preventDefault();

			$(
				'<div title="' + WordPointsResetPointsAdminScreenL10n.dialogTitle + '">'
				+ '<p>' + WordPointsResetPointsAdminScreenL10n.dialogTextTop + '</p>'
				+ '<p><strong>' + $currentDelete.closest( 'tr' ).find( 'th' ).text() + ': ' + $currentDelete.closest( 'tr' ).find( 'input[type=number]' ).val() + '</strong></p>'
					+ '<p>' + WordPointsResetPointsAdminScreenL10n.dialogTextBottom + '</p>'
				+ '</div>'

			).dialog({
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