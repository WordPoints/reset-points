<?php

/**
 * The module's main functions.
 *
 * @package WordPoints_Reset_Points
 * @since   1.3.0
 */

/**
 * Reset all users' points of a given type.
 *
 * @since 1.0.0 As wordpoints_points_reset_type().
 * @since 1.3.0
 *
 * @param string $points_type The type of points that should be reset.
 *
 * @return bool Whether all users' points were reset successfully.
 */
function wordpoints_reset_points_type( $points_type ) {

	$meta_key = wordpoints_get_points_user_meta_key( $points_type );

	if ( ! $meta_key ) {
		return false;
	}

	$reset_value = wordpoints_get_points_type_setting( $points_type, 'reset_value' );

	if ( false === wordpoints_int( $reset_value ) ) {
		return false;
	}

	/**
	 * Fires before all users' points are reset.
	 *
	 * @since 1.3.0
	 *
	 * @param string $points_type The type of points being reset.
	 * @param int    $reset_value The value that the user points are being reset to.
	 */
	do_action( 'wordpoints_reset_points_before', $points_type, $reset_value );

	$user_ids = get_users( array( 'fields' => 'ids' ) );

	foreach ( $user_ids as $user_id ) {
		update_user_meta( $user_id, $meta_key, $reset_value );
	}

	/**
	 * Fires after all users' points are reset.
	 *
	 * @since 1.3.0
	 *
	 * @param string $points_type The type of points being reset.
	 * @param int    $reset_value The value that the user points are being reset to.
	 */
	do_action( 'wordpoints_reset_points', $points_type, $reset_value );

	return true;
}

/**
 * Perform an automatic scheduled reset of the points.
 *
 * @since 1.0.0 As wordpoints_points_reset_on_date().
 * @since 1.3.0
 *
 * @WordPress\action init
 */
function wordpoints_reset_points_on_date() {

	$points_types = wordpoints_get_points_types();

	if ( ! $points_types ) {
		return;
	}

	$now = current_time( 'timestamp' );

	foreach ( $points_types as $slug => $points_type ) {

		if (
			! empty( $points_type['reset_date'] )
			&& wordpoints_posint( $points_type['reset_date'] )
			&& $points_type['reset_date'] <= $now
		) {

			if ( wordpoints_reset_points_type( $slug ) ) {
				unset( $points_type['reset_date'] );
				wordpoints_update_points_type( $slug, $points_type );
			}
		}
	}
}
add_action( 'init', 'wordpoints_reset_points_on_date' );

// EOF
