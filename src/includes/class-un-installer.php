<?php

/**
 * Un/installer class.
 *
 * @package WordPoints_Reset_Points
 * @since   1.3.0
 */

/**
 * Uninstalls the module.
 *
 * @since 1.3.0
 */
class WordPoints_Reset_Points_Un_Installer extends WordPoints_Un_Installer_Base {

	/**
	 * @since 1.3.0
	 */
	protected $type = 'module';

	/**
	 * @since 1.8.0
	 */
	protected $updates = array(
		'1.3.0' => array( 'single' => true, 'site' => true, 'network' => true ),
	);

	/**
	 * @since 1.3.0
	 */
	protected function uninstall_network() {

		parent::uninstall_single();

		$this->uninstall_points_type_settings();
	}

	/**
	 * @since 1.3.0
	 */
	protected function uninstall_site() {

		parent::uninstall_single();

		$this->uninstall_points_type_settings();
	}

	/**
	 * @since 1.3.0
	 */
	protected function uninstall_single() {

		parent::uninstall_single();

		$this->uninstall_points_type_settings();
	}

	/**
	 * Uninstalls the points type settings added by the module.
	 *
	 * @since 1.3.0
	 */
	protected function uninstall_points_type_settings() {

		foreach ( wordpoints_get_points_types() as $slug => $settings ) {

			unset( $settings['reset_date'] );

			wordpoints_update_points_type( $slug, $settings );
		}
	}

	/**
	 * @since 1.3.0
	 */
	protected function before_update() {

		parent::before_update();

		if ( $this->network_wide ) {
			unset( $this->updates['1_3_0']['site'] );
		} else {
			unset( $this->updates['1_3_0']['network'] );
		}
	}

	/**
	 * Update a network to 1.3.0.
	 *
	 * @since 1.3.0
	 */
	protected function update_network_to_1_3_0() {

		$this->update_points_type_settings_to_1_3_0();
	}

	/**
	 * Update a site on the network to 1.3.0.
	 *
	 * @since 1.3.0
	 */
	protected function update_site_to_1_3_0() {

		$this->update_points_type_settings_to_1_3_0();
	}

	/**
	 * Update a single site to 1.3.0.
	 *
	 * @since 1.3.0
	 */
	protected function update_single_to_1_3_0() {

		$this->update_points_type_settings_to_1_3_0();
	}

	/**
	 * Updates the points types settings to 1.3.0.
	 *
	 * @since 1.3.0
	 */
	protected function update_points_type_settings_to_1_3_0() {

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

return 'WordPoints_Reset_Points_Un_Installer';

// EOF
