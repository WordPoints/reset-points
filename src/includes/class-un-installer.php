<?php

/**
 * Un/installer class.
 *
 * @package WordPoints_Points_Reset
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
}

return 'WordPoints_Reset_Points_Un_Installer';

// EOF
