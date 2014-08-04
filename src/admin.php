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

// EOF
