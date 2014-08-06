<?php

/**
 * Module Name: Reset Points
 * Author:      J.D. Grimes
 * Author URI:  http://codesymphony.co/
 * Version:     1.1.0
 * License:     GPLv2+
 * Description: Reset your users points on demand or automatically on a scheduled date.
 *
 * ---------------------------------------------------------------------------------|
 * Copyright 2013-2014  J.D. Grimes  (email : jdg@codesymphony.co)
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
 * @package WordPoints_Points_Reset
 * @version 1.1.0
 * @author  J.D. Grimes <jdg@codesymphony.co>
 * @license GPLv2+
 */

if ( is_admin() ) {
	include dirname( __FILE__ ) . '/admin.php';
}

/**
 * Reset all users' points of a given type.
 *
 * @since 1.0.0
 *
 * @param string $points_type The type of points that should be reset.
 *
 * @return bool Whether all users' points were reset successfully.
 */
function wordpoints_points_reset_type( $points_type ) {

	$meta_key = wordpoints_get_points_user_meta_key( $points_type );
	$reset_value = (int) wordpoints_get_points_type_setting( $points_type, 'reset_value' );

	$user_ids = get_users( array( 'fields' => 'ids' ) );

	foreach ( $user_ids as $user_id ) {
		update_user_meta( $user_id, $meta_key, $reset_value );
	}

	return true;
}

/**
 * Perform an automatic scheduled reset of the points.
 *
 * @since 1.0.0
 *
 * @action init
 */
function wordpoints_points_reset_on_date() {

	$points_types = wordpoints_get_points_types();

	if ( ! $points_types ) {
		return;
	}

	$now = current_time( 'timestamp' );

	foreach ( $points_types as $slug => $points_type ) {

		if (
			! empty( $points_type['reset_date'] )
			&& $points_type['reset_date'] <= $now
		) {

			if ( wordpoints_points_reset_type( $slug ) ) {
				unset( $points_type['reset_date'] );
				wordpoints_update_points_type( $slug, $points_type );
			}
		}
	}
}
add_action( 'init', 'wordpoints_points_reset_on_date' );

// EOF
