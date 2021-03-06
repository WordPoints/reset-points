<?php

/**
 * The reset points administration screen.
 *
 * @package WordPoints_Reset_Points
 * @since 1.0.0
 */

?>

<div class="wrap">
	<h2><?php esc_html_e( 'Reset Points', 'wordpoints-reset-points' ); ?></h2>

	<?php if ( ! wordpoints_get_points_types() ) : ?>
		<?php wordpoints_show_admin_error( __( 'You need to create a points type before you can reset points.', 'wordpoints-reset-points' ) ); ?>
	<?php else : ?>
		<?php

		wordpoints_reset_points_admin_screen_process();

		?>

		<p>
			<?php esc_html_e( 'Reset all users&#8217; points to a certain value, either right now or on a future date.', 'wordpoints-reset-points' ); ?>
		</p>

		<form id="reset-points-type" method="POST">
			<table class="widefat striped">
				<tbody>
					<?php foreach ( wordpoints_get_points_types() as $slug => $points_type ) : // WPCS: prefix OK. ?>
						<tr>
							<th><?php echo esc_html( $points_type['name'] ); ?></th>
							<td>
								<label for="reset-points-type-value-<?php echo esc_attr( $slug ); ?>">
									<?php esc_html_e( 'Reset to value:', 'wordpoints-reset-points' ); ?>
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
									<?php esc_html_e( 'Reset on date:', 'wordpoints-reset-points' ); ?>
									<input
										type="date"
										id="reset-points-type-date-<?php echo esc_attr( $slug ); ?>"
										name="reset-points-type-date-<?php echo esc_attr( $slug ); ?>"
										value="<?php echo ( ! empty( $points_type['reset_date'] ) ) ? esc_html( date( 'Y-m-d', $points_type['reset_date'] ) ) : ''; ?>"
									/>
								</label>
								<label for="reset-points-type-time-<?php echo esc_attr( $slug ); ?>">
									<span class="screen-reader-text">
										<?php esc_html_e( 'Time:', 'wordpoints-reset-points' ); ?>
									</span>
									<input
										type="time"
										placeholder="<?php esc_html_e( 'Time', 'wordpoints-reset-points' ); ?>"
										id="reset-points-type-time-<?php echo esc_attr( $slug ); ?>"
										name="reset-points-type-time-<?php echo esc_attr( $slug ); ?>"
										value="<?php echo ( ! empty( $points_type['reset_date'] ) ) ? esc_html( date( 'H:i', $points_type['reset_date'] ) ) : '00:00'; ?>"
									/>
								</label>
								<?php submit_button( __( 'Set Date', 'wordpoints-reset-points' ), '', "reset-points-type-date-set-{$slug}", false ); ?>
							</td>
							<td><?php submit_button( __( 'Reset Now', 'wordpoints-reset-points' ), 'delete', "reset-points-type-{$slug}", false ); ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<?php wp_nonce_field( 'wordpoints-reset-points' ); ?>
		</form>
	<?php endif; ?>
</div>
