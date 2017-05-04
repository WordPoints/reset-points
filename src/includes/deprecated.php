<?php

/**
 * The module's deprecated functions.
 *
 * @package WordPoints_Reset_Points
 * @since   1.3.0
 */

/**
 * Load the module's text domain.
 *
 * @since 1.2.0
 * @deprecated 1.3.0
 */
function wordpoints_points_reset_load_textdomain() {

	_deprecated_function( __FUNCTION__, '1.3.0' );

	wordpoints_load_module_textdomain(
		'wordpoints-reset-points'
		, wordpoints_module_basename( __FILE__ ) . '/languages'
	);
}

/**
 * Reset all users' points of a given type.
 *
 * @since 1.0.0
 * @deprecated 1.3.0 Use wordpoints_reset_points_type() instead.
 *
 * @param string $points_type The type of points that should be reset.
 *
 * @return bool Whether all users' points were reset successfully.
 */
function wordpoints_points_reset_type( $points_type ) {

	_deprecated_function(
		__FUNCTION__
		, '1.3.0'
		, 'wordpoints_reset_points_type'
	);

	return wordpoints_reset_points_type( $points_type );
}

/**
 * Perform an automatic scheduled reset of the points.
 *
 * @since 1.0.0
 * @deprecated 1.3.0 Use wordpoints_reset_points_on_date() instead.
 */
function wordpoints_points_reset_on_date() {

	_deprecated_function(
		__FUNCTION__
		, '1.3.0'
		, 'wordpoints_reset_points_on_date'
	);

	wordpoints_reset_points_on_date();
}

// EOF
