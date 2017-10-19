<?php

/**
 * Points types settings uninstaller.
 *
 * @package WordPoints_Reset_Points
 * @since   1.3.2
 */

/**
 * Uninstaller for the extension's points types settings.
 *
 * @since 1.3.2
 */
class WordPoints_Reset_Points_Uninstaller_Points_Types_Settings
	implements WordPoints_RoutineI {

	/**
	 * Whether to uninstall the settings for network or regular points types.
	 *
	 * @since 1.3.2
	 *
	 * @var bool
	 */
	protected $network;

	/**
	 * @since 1.3.2
	 *
	 * @param bool $network Whether to uninstall the settings for network or regular
	 *                      points types.
	 */
	public function __construct( $network = false ) {

		$this->network = $network;
	}

	/**
	 * @since 1.3.2
	 */
	public function run() {

		$points_types = wordpoints_get_maybe_network_array_option(
			'wordpoints_points_types'
			, $this->network
		);

		foreach ( $points_types as $slug => $points_type ) {
			unset( $points_types[ $slug ]['reset_date'] );
		}

		wordpoints_update_maybe_network_option(
			'wordpoints_points_types'
			, $points_types
			, $this->network
		);
	}
}

// EOF
