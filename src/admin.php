<?php

/**
 * Administration screen code for the extension.
 *
 * @package WordPoints_Reset_Points
 * @since 1.0.0
 */

/**
 * Add admin screen to the administration menu.
 *
 * @since 1.0.0
 *
 * @action admin_menu
 * @action network_admin_menu
 */
function wordpoints_reset_points_admin_menu() {

	add_submenu_page(
		'wordpoints_configure'
		, __( 'WordPoints — Reset Points', 'wordpoints-reset-points' )
		, __( 'Reset Points', 'wordpoints-reset-points' )
		, 'set_wordpoints_points'
		, 'wordpoints_reset_points'
		, 'wordpoints_reset_points_admin_screen'
	);
}
add_action( 'admin_menu', 'wordpoints_reset_points_admin_menu' );
add_action( 'network_admin_menu', 'wordpoints_reset_points_admin_menu' );

/**
 * Register admin scripts and styles for the extension.
 *
 * @since 1.0.0
 *
 * @WordPress\action admin_enqueue_scripts 5 So they are ready on enqueue (10).
 */
function wordpoints_reset_points_admin_register_scripts() {

	$assets_url = wordpoints_extensions_url( '/assets', __FILE__ );
	$suffix     = SCRIPT_DEBUG ? '' : '.min';

	$version = wordpoints_get_extension_version( __FILE__ );

	// JS.
	wp_register_script(
		'wordpoints-reset-points-admin-screen'
		, "{$assets_url}/js/admin-screen{$suffix}.js"
		, array( 'jquery-ui-dialog', 'jquery-ui-datepicker' )
		, $version
		, true
	);

	// CSS.
	wp_register_style(
		'wordpoints-reset-points-jquery-ui-datepicker'
		, "{$assets_url}/css/jquery-ui-datepicker{$suffix}.css"
		, null
		, $version
	);

	wp_register_style(
		'wordpoints-reset-points-admin-screen'
		, "{$assets_url}/css/admin-screen{$suffix}.css"
		, array(
			'wp-jquery-ui-dialog',
			'wordpoints-reset-points-jquery-ui-datepicker',
		)
		, $version
	);
}
add_action( 'admin_enqueue_scripts', 'wordpoints_reset_points_admin_register_scripts', 5 );

/**
 * Display the extension's administration screen.
 *
 * @since 1.0.0
 */
function wordpoints_reset_points_admin_screen() {

	wp_enqueue_style( 'wordpoints-reset-points-admin-screen' );
	wp_enqueue_script( 'wordpoints-reset-points-admin-screen' );
	wp_localize_script(
		'wordpoints-reset-points-admin-screen'
		, 'WordPointsResetPointsAdminScreenL10n'
		, array(
			'resetButton'      => __( 'Reset', 'wordpoints-reset-points' ),
			'cancelButton'     => __( 'Cancel', 'wordpoints-reset-points' ),
			'dialogTitle'      => __( 'Are you sure?', 'wordpoints-reset-points' ),
			'dialogTextTop'    => __( 'Are you sure you want to reset this points type?', 'wordpoints-reset-points' ),
			'dialogTextBottom' => __( 'This action cannot be undone.', 'wordpoints-reset-points' ),
		)
	);

	include dirname( __FILE__ ) . '/admin-screen.php';
}

/**
 * Process the reset points settings form when it is submitted.
 *
 * @since 1.4.0
 */
function wordpoints_reset_points_admin_screen_process() {

	$points_types = wordpoints_get_points_types();

	if ( ! isset( $_POST['_wpnonce'] ) ) {
		return;
	}

	if ( ! wordpoints_verify_nonce( '_wpnonce', 'wordpoints-reset-points', null, 'post' ) ) {
		wordpoints_show_admin_error( __( 'Are you sure you want to do this? Please try again.', 'wordpoints-reset-points' ) );
		return;
	}

	foreach ( $points_types as $slug => $points_type ) {

		if ( ! isset( $_POST[ "reset-points-type-value-{$slug}" ] ) ) {
			continue;
		}

		if ( false === wordpoints_int( $_POST[ "reset-points-type-value-{$slug}" ] ) ) {

			// translators: Points type name.
			wordpoints_show_admin_error( sprintf( __( 'There was an error resetting the points type &#8220;%s&#8221;. Please try again.', 'wordpoints-reset-points' ), $points_type['name'] ) );
			return;
		}

		$points_type['reset_value'] = (int) $_POST[ "reset-points-type-value-{$slug}" ];

		if ( isset( $_POST[ "reset-points-type-{$slug}" ] ) ) {

			wordpoints_update_points_type( $slug, $points_type );

			if ( wordpoints_reset_points_type( $slug ) ) {
				// translators: Points type name.
				wordpoints_show_admin_message( sprintf( __( 'The points type &#8220;%s&#8221; was reset successfully.', 'wordpoints-reset-points' ), $points_type['name'] ) );
			} else {
				// translators: Points type name.
				wordpoints_show_admin_error( sprintf( __( 'There was an error resetting the points type &#8220;%s&#8221;. Please try again.', 'wordpoints-reset-points' ), $points_type['name'] ) );
			}

			break;

		} elseif (
			isset(
				$_POST[ "reset-points-type-date-set-{$slug}" ]
				, $_POST[ "reset-points-type-date-{$slug}" ]
				, $_POST[ "reset-points-type-time-{$slug}" ]
			)
		) {

			$raw_date = sanitize_text_field( wp_unslash( $_POST[ "reset-points-type-date-{$slug}" ] ) );
			$raw_time = sanitize_text_field( wp_unslash( $_POST[ "reset-points-type-time-{$slug}" ] ) );

			if ( empty( $raw_date ) && ! empty( $points_type['reset_date'] ) ) {

				unset( $points_type['reset_date'] );

				if ( wordpoints_update_points_type( $slug, $points_type ) ) {
					// translators: Points type name.
					wordpoints_show_admin_message( sprintf( __( 'The points type &#8220;%s&#8221; is no longer scheduled for an automatic reset.', 'wordpoints-reset-points' ), $points_type['name'] ) );
				} else {
					// translators: Points type name.
					wordpoints_show_admin_error( sprintf( __( 'There was an error clearing the reset date for the points type &#8220;%s&#8221;. Please try again.', 'wordpoints-reset-points' ), $points_type['name'] ) );
				}

			} else {

				$raw_datetime = "{$raw_date} {$raw_time}:00";
				$timezone     = wordpoints_reset_points_get_site_timezone();

				if ( empty( $raw_date ) || ! wordpoints_reset_points_validate_datetime( $raw_datetime, $timezone ) ) {
					wordpoints_show_admin_error( __( 'Please enter a valid future date.', 'wordpoints-reset-points' ) );
					break;
				}

				$date = new DateTime( $raw_datetime, $timezone );

				$timestamp = (int) $date->format( 'U' );

				// If the date hasn't changed, do nothing.
				if ( isset( $points_type['reset_date'] ) && $points_type['reset_date'] === $timestamp ) {
					break;
				}

				if ( $timestamp > time() ) {

					$points_type['reset_date'] = $timestamp;

					if ( wordpoints_update_points_type( $slug, $points_type ) ) {
						// translators: 1. Points type name; 2. Date.
						wordpoints_show_admin_message( sprintf( __( 'The points type &#8220;%1$s&#8221; will automatically be reset on %2$s.', 'wordpoints-reset-points' ), $points_type['name'], $date->format( 'Y-m-d\TH:i' ) ) );
					} else {
						// translators: Points type name.
						wordpoints_show_admin_error( sprintf( __( 'There was an error setting the reset date for the points type &#8220;%s&#8221;. Please try again.', 'wordpoints-reset-points' ), $points_type['name'] ) );
					}

				} else {
					wordpoints_show_admin_error( __( 'Please enter a valid future date.', 'wordpoints-reset-points' ) );
				}

			} // End if ( date being deleted ).

			break;

		} // End if ( immediate reset ) elseif ( set date ).

	} // End foreach ( $points_type ).
}

// EOF
