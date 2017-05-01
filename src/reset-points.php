<?php

/**
 * Main file of the module.
 *
 * ---------------------------------------------------------------------------------|
 * Copyright 2013-16  J.D. Grimes  (email : jdg@codesymphony.co)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or later, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * ---------------------------------------------------------------------------------|
 *
 * @package WordPoints_Reset_Points
 * @version 1.2.1
 * @author  J.D. Grimes <jdg@codesymphony.co>
 * @license GPLv2+
 */

WordPoints_Modules::register(
	'
		Module Name: Reset Points
		Module URI:  https://wordpoints.org/modules/reset-points/
		Author:      J.D. Grimes
		Author URI:  https://codesymphony.co/
		Version:     1.2.1
		License:     GPLv2+
		Description: Reset your users points on demand or automatically on a scheduled date.
		Channel:     wordpoints.org
		ID:          540
		Text Domain: wordpoints-reset-points
		Domain Path: /languages
		Namespace:   Reset_Points
	'
	, __FILE__
);

/**
 * The module's deprecated functions.
 *
 * @since 1.3.0
 */
require_once( dirname( __FILE__ ) . '/includes/deprecated.php' );

if ( is_admin() ) {
	include dirname( __FILE__ ) . '/admin.php';
}

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
