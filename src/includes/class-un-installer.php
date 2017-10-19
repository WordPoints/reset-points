<?php

/**
 * Un/installer class.
 *
 * @package WordPoints_Reset_Points
 * @since 1.3.0
 * @deprecated 1.3.2
 */

_deprecated_file( __FILE__, '1.3.2' );

/**
 * Uninstalls the extension.
 *
 * @since 1.3.0
 * @deprecated 1.3.2
 */
class WordPoints_Reset_Points_Un_Installer extends WordPoints_Un_Installer_Base {

	/**
	 * @since 1.3.0
	 */
	protected $type = 'module';

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
	 * Uninstalls the points type settings added by the extension.
	 *
	 * @since 1.3.0
	 */
	protected function uninstall_points_type_settings() {

		$routine = new WordPoints_Reset_Points_Uninstaller_Points_Types_Settings();
		$routine->run();
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

		$routine = new WordPoints_Reset_Points_Updater_1_3_0_Points_Types_Settings();
		$routine->run();
	}
}

return 'WordPoints_Reset_Points_Un_Installer';

// EOF
