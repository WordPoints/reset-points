<?php

/**
 * Points types settings 1.3.0 updater class.
 *
 * @package WordPoints_Reset_Points
 * @since   1.3.2
 */

/**
 * Updates the extension's points types settings to 1.3.0.
 *
 * @since 1.3.2
 */
class WordPoints_Reset_Points_Updater_1_3_0_Points_Types_Settings
	implements WordPoints_RoutineI {

	/**
	 * @since 1.3.2
	 */
	public function run() {

		foreach ( wordpoints_get_points_types() as $slug => $settings ) {

			if ( ! isset( $settings['reset_date'] ) ) {
				continue;
			}

			// Convert the reset timestamp back to GMT rather than "local" time.
			$settings['reset_date'] -= get_option( 'gmt_offset' ) * HOUR_IN_SECONDS;

			wordpoints_update_points_type( $slug, $settings );
		}
	}
}

// EOF
