<?php

/**
 * The reset points administration screen.
 *
 * @package WordPoints_Reset_Points
 * @since 1.0.0
 */

$points_types = wordpoints_get_points_types();

?>

<div class="wrap">
	<h2><?php _e( 'Reset Points', 'wordpoints-points-reset' ); ?></h2>

	<?php if ( ! $points_types ) : ?>
		<?php wordpoints_show_admin_error( __( 'You need to create a points type before you can reset points.', 'wordpoints-points-reset' ) ); ?>
	<?php else : ?>
		<?php

		if ( isset( $_POST['_wpnonce'] ) && wp_verify_nonce( $_POST['_wpnonce'], 'wordpoints-reset-points' ) ) {

			foreach ( $points_types as $slug => $points_type ) {

				if ( isset( $_POST[ "reset-points-type-{$slug}" ], $_POST[ "reset-points-type-value-{$slug}" ] ) ) {

					$points_type['reset_value'] = (int) $_POST[ "reset-points-type-value-{$slug}" ];

					wordpoints_update_points_type( $slug, $points_type );

					if ( wordpoints_points_reset_type( $slug ) ) {
						wordpoints_show_admin_message( sprintf( __( 'The points type &#8220;%s&#8221; was reset successfully.', 'wordpoints-points-reset' ), $points_type['name'] ) );
					} else {
						wordpoints_show_admin_error( sprintf( __( 'There was an error resetting the points type &#8220;%s&#8221;. Please try again.', 'wordpoints-points-reset' ), $points_type['name'] ) );
					}

					break;

				} elseif ( isset( $_POST[ "reset-points-type-date-set-{$slug}" ], $_POST[ "reset-points-type-date-{$slug}" ], $_POST[ "reset-points-type-value-{$slug}" ]  ) ) {

					$points_type['reset_value'] = (int) $_POST[ "reset-points-type-value-{$slug}" ];

					$raw_date = $_POST[ "reset-points-type-date-{$slug}" ];

					if ( empty( $raw_date ) && ! empty( $points_type ) ) {

						unset( $points_type['reset_date'] );

						if ( wordpoints_update_points_type( $slug, $points_type ) ) {
							wordpoints_show_admin_message( sprintf( __( 'The points type &#8220;%s&#8221; is no longer scheduled for an automatic reset.', 'wordpoints-points-reset' ), $points_type['name'] ) );
						} else {
							wordpoints_show_admin_error( sprintf( __( 'There was an error clearing the reset date for the points type &#8220;%s&#8221;. Please try again.', 'wordpoints-points-reset' ), $points_type['name'] ) );
						}

					} else {

						$now  = current_time( 'timestamp' );
						$date = strtotime( $raw_date, $now );

						// If the date hasn't changed, do nothing.
						if ( isset( $points_type['reset_date'] ) && $points_type['reset_date'] == $date ) {
							break;
						}

						if ( $date && $raw_date === date( 'Y-m-d', $date ) && $date > $now ) {

							$points_type['reset_date'] = $date;

							if ( wordpoints_update_points_type( $slug, $points_type ) ) {
								wordpoints_show_admin_message( sprintf( __( 'The points type &#8220;%s&#8221; will automatically be reset on %s.', 'wordpoints-points-reset' ), $points_type['name'], $raw_date ) );
							} else {
								wordpoints_show_admin_error( sprintf( __( 'There was an error setting the reset date for the points type &#8220;%s&#8221;. Please try again.', 'wordpoints-points-reset' ), $points_type['name'] ) );
							}

						} else {
							wordpoints_show_admin_error( __( 'Please enter a valid future date.', 'wordpoints-points-reset' ) );
						}
					}

					break;
				}
			}

			// Retrieve the up-to-date points types settings.
			$points_types = wordpoints_get_points_types();
		}

		?>
		<form id="reset-points-type" method="POST">
			<table class="widefat">
				<tbody>
					<?php foreach ( $points_types as $slug => $points_type ) : ?>
						<tr>
							<th><?php echo $points_type['name']; ?></th>
							<td>
								<label for="reset-points-type-value-<?php echo esc_attr( $slug ); ?>">
									<?php esc_html_e( 'Reset Value:', 'wordpoints-points-reset' ); ?>
								</label>
								<input
									type="number"
									id="reset-points-type-value-<?php echo esc_attr( $slug ); ?>"
									name="reset-points-type-value-<?php echo esc_attr( $slug ); ?>"
									value="<?php echo ( ! empty( $points_type['reset_value'] ) ) ? (int) $points_type['reset_value'] : 0; ?>"
								/>
							</td>
							<td><?php submit_button( __( 'Reset Now', 'wordpoints-points-reset' ), 'delete', "reset-points-type-{$slug}", false ); ?></td>
							<td>
								<label for="reset-points-type-date-<?php echo $slug; ?>">
									<?php _e( 'Reset on date:', 'wordpoints-points-reset' ); ?>
									<input
										type="date"
										id="reset-points-type-date-<?php echo $slug; ?>"
										name="reset-points-type-date-<?php echo $slug; ?>"
										value="<?php echo ( ! empty( $points_type['reset_date'] ) ) ? date( 'Y-m-d', $points_type['reset_date'] ) : ''; ?>"
									/>
								</label>
								<?php submit_button( __( 'Set Date', 'wordpoints-points-reset' ), 'secondary', "reset-points-type-date-set-{$slug}", false ); ?>
							</td>
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
						'<div title="<?php _e( 'Are you sure?', 'wordpoints-points-reset' ); ?>">'
							+ '<p><?php _e( 'Are you sure you want to reset this points type?', 'wordpoints-points-reset' ); ?></p>'
							+ '<p><strong>' + $currentDelete.closest( 'tr' ).find( 'th' ).text() + ': ' + $currentDelete.closest( 'tr' ).find( 'input[type=number]' ).val() + '</strong></p>'
							+ '<p><?php _e( 'This action cannot be undone.', 'wordpoints-points-reset' ); ?></p>'
						+ '</div>'

					).dialog({
						dialogClass: 'wp-dialog wordpoints-points-reset-dialog',
						resizable: false,
						draggable: false,
						height: 250,
						modal: true,
						buttons: [
							{
								text: '<?php _e( 'Reset', 'wordpoints-points-reset' ); ?>',
								class: 'button-primary',
								click: function() {
									$( this ).dialog( 'close' );
									$currentDelete.click();
									$currentDelete = false;
								}
							},
							{
								text: '<?php _e( 'Cancel', 'wordpoints-points-reset' ); ?>',
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