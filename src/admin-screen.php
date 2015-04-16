<?php

/**
 * The reset points administration screen.
 *
 * @package WordPoints_Reset_Points
 * @since 1.0.0
 */

?>

<div class="wrap">
	<h2><?php esc_html_e( 'Reset Points', 'wordpoints-points-reset' ); ?></h2>

	<?php if ( ! wordpoints_get_points_types() ) : ?>
		<?php wordpoints_show_admin_error( __( 'You need to create a points type before you can reset points.', 'wordpoints-points-reset' ) ); ?>
	<?php else : ?>
		<?php

		wordpoints_reset_admin_screen_process();

		?>

		<p>
			<?php esc_html_e( 'Reset all users&#8217; points to a certain value, either right now or on a future date.', 'wordpoints-reset-points' ); ?>
		</p>

		<form id="reset-points-type" method="POST">
			<table class="widefat">
				<tbody>
					<?php foreach ( wordpoints_get_points_types() as $slug => $points_type ) : ?>
						<tr>
							<th><?php echo esc_html( $points_type['name'] ); ?></th>
							<td>
								<label for="reset-points-type-value-<?php echo esc_attr( $slug ); ?>">
									<?php esc_html_e( 'Reset to value:', 'wordpoints-points-reset' ); ?>
								</label>
								<input
									type="number"
									id="reset-points-type-value-<?php echo esc_attr( $slug ); ?>"
									name="reset-points-type-value-<?php echo esc_attr( $slug ); ?>"
									value="<?php echo ( ! empty( $points_type['reset_value'] ) ) ? (int) $points_type['reset_value'] : 0; ?>"
								/>
							</td>
							<td>
								<label for="reset-points-type-date-<?php echo esc_attr( $slug ); ?>">
									<?php esc_html_e( 'Reset on date:', 'wordpoints-points-reset' ); ?>
									<input
										type="date"
										id="reset-points-type-date-<?php echo esc_attr( $slug ); ?>"
										name="reset-points-type-date-<?php echo esc_attr( $slug ); ?>"
										value="<?php echo ( ! empty( $points_type['reset_date'] ) ) ? esc_html( date( 'Y-m-d', $points_type['reset_date'] ) ) : ''; ?>"
									/>
								</label>
								<?php submit_button( __( 'Set Date', 'wordpoints-points-reset' ), 'secondary', "reset-points-type-date-set-{$slug}", false ); ?>
							</td>
							<td><?php submit_button( __( 'Reset Now', 'wordpoints-points-reset' ), 'delete', "reset-points-type-{$slug}", false ); ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<?php wp_nonce_field( 'wordpoints-reset-points' ); ?>
		</form>
		<script>
		jQuery( document ).ready( function( $ ) {
			var $currentDelete = false;

			$( '#reset-points-type input[type=date]' ).datepicker( { dateFormat: 'yy-mm-dd' } );
			$( '#reset-points-type .delete' ).click( function( event ) {
				if ( $currentDelete === false ) {

					$currentDelete = $( this );

					event.preventDefault();

					$(
						'<div title="<?php esc_attr_e( 'Are you sure?', 'wordpoints-points-reset' ); ?>">'
							+ '<p><?php esc_html_e( 'Are you sure you want to reset this points type?', 'wordpoints-points-reset' ); ?></p>'
							+ '<p><strong>' + $currentDelete.closest( 'tr' ).find( 'th' ).text() + ': ' + $currentDelete.closest( 'tr' ).find( 'input[type=number]' ).val() + '</strong></p>'
							+ '<p><?php esc_html_e( 'This action cannot be undone.', 'wordpoints-points-reset' ); ?></p>'
						+ '</div>'

					).dialog({
						dialogClass: 'wp-dialog wordpoints-points-reset-dialog',
						resizable: false,
						draggable: false,
						height: 250,
						modal: true,
						buttons: [
							{
								text: '<?php echo esc_js( __( 'Reset', 'wordpoints-points-reset' ) ); ?>',
								class: 'button-primary',
								click: function() {
									$( this ).dialog( 'close' );
									$currentDelete.click();
									$currentDelete = false;
								}
							},
							{
								text: '<?php  echo esc_js( __( 'Cancel', 'wordpoints-points-reset' ) ); ?>',
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
		</script>
	<?php endif; ?>
</div>