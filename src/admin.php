<?php

/**
 * Administration screen code for the module.
 *
 * @package WordPoints_Points_Reset
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
		,__( 'WordPoints - Reset Points', 'wordpoints' )
		,__( 'Reset Points', 'wordpoints' )
		,'set_wordpoints_points'
		,'wordpoints_reset_points'
		,'wordpoints_reset_points_admin_screen'
	);
}
add_action( 'admin_menu', 'wordpoints_reset_points_admin_menu' );
add_action( 'network_admin_menu', 'wordpoints_reset_points_admin_menu' );

/**
 * Display the module's administration screen.
 *
 * @since 1.0.0
 */
function wordpoints_reset_points_admin_screen() {

	wp_enqueue_style( 'wp-jquery-ui-dialog' );
	wp_enqueue_style(
		'wordpoints-points-reset-jquery-ui-datepicker'
		, wordpoints_modules_url( 'assets/css/jquery-ui-1.10.4.custom.css', __FILE__ )
	);

	wp_enqueue_script( 'jquery-ui-datepicker' );
	wp_enqueue_script( 'jquery-ui-dialog' );

	include dirname( __FILE__ ) . '/admin-screen.php';
}

/**
 * Process the reset points settings form when it is submitted.
 *
 * @since 1.2.0
 */
function wordpoints_reset_admin_screen_process() {

	$points_types = wordpoints_get_points_types();

	if ( ! isset( $_POST['_wpnonce'] ) ) {
		return;
	}

	if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'wordpoints-reset-points' ) ) {
		wordpoints_show_admin_error( __( 'Are you sure you want to do this? Please try again.', 'wordpoints-points-reset' ) );
		return;
	}

	foreach ( $points_types as $slug => $points_type ) {

		if ( isset( $_POST["reset-points-type-{$slug}"], $_POST["reset-points-type-value-{$slug}"] ) ) {

			$points_type['reset_value'] = (int) $_POST["reset-points-type-value-{$slug}"];

			wordpoints_update_points_type( $slug, $points_type );

			if ( wordpoints_points_reset_type( $slug ) ) {
				wordpoints_show_admin_message( sprintf( __( 'The points type &#8220;%s&#8221; was reset successfully.', 'wordpoints-points-reset' ), $points_type['name'] ) );
			} else {
				wordpoints_show_admin_error( sprintf( __( 'There was an error resetting the points type &#8220;%s&#8221;. Please try again.', 'wordpoints-points-reset' ), $points_type['name'] ) );
			}

			break;

		} elseif ( isset( $_POST["reset-points-type-date-set-{$slug}"], $_POST["reset-points-type-date-{$slug}"], $_POST["reset-points-type-value-{$slug}"] ) ) {

			$points_type['reset_value'] = (int) $_POST["reset-points-type-value-{$slug}"];

			$raw_date = $_POST["reset-points-type-date-{$slug}"];

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
}

// EOF
