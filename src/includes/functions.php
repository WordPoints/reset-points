<?php

/**
 * The extension's main functions.
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

	wordpoints_prevent_interruptions();

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

	$now = time();

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

/**
 * Creates a `DateTimeZone` object for the site's timezone.
 *
 * This function determines the site timezone as follows:
 *
 * - If the site uses a timezone identifier (i.e., 'timezone_string' option is set),
 *   that is used.
 * - If that's not set, we make up an identifier based on the 'gmt_offset'.
 * - If the GMT offset is 0, or the identifier is invalid, UTC is used.
 *
 * @see https://wordpress.stackexchange.com/a/198453/27757
 * @see https://us.php.net/manual/en/timezones.others.php
 *
 * @since 1.3.0
 *
 * @return DateTimeZone The site's timezone.
 */
function wordpoints_reset_points_get_site_timezone() {

	$timezone_string = get_option( 'timezone_string' );

	// A direct offset may be used instead of a timezone identifier.
	if ( empty( $timezone_string ) ) {
		$offset = get_option( 'gmt_offset' );

		if ( empty( $offset ) ) {
			$timezone_string = 'UTC';
		} else {
			$hours           = (int) $offset;
			$minutes         = ( $offset - floor( $offset ) ) * 60;
			$timezone_string = sprintf( '%+03d:%02d', $hours, $minutes );
		}
	}

	// The offsets in particular do not work prior to PHP 5.5.
	try {
		$timezone = new DateTimeZone( $timezone_string );
	} catch ( Exception $e ) {
		$timezone = new DateTimeZone( 'UTC' );
	}

	return $timezone;
}

/**
 * Validate a datetime string.
 *
 * @since 1.3.0
 *
 * @param string       $datetime The datetime string.
 * @param DateTimeZone $timezone The timezone to use.
 *
 * @return bool Whether the datetime is valid.
 */
function wordpoints_reset_points_validate_datetime( $datetime, $timezone ) {

	try {
		new DateTime( $datetime, $timezone );
	} catch ( Exception $e ) {
		return false;
	}

	// Requires PHP 5.3+.
	if ( ! function_exists( 'DateTime::getLastErrors' ) ) {
		return true;
	}

	$errors = DateTime::getLastErrors();

	if ( 0 !== $errors['error_count'] || 0 !== $errors['warning_count'] ) {
		return false;
	}

	return true;
}

// EOF
