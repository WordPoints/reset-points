<?php

/**
 * Installable class.
 *
 * @package WordPoints_Reset_Points
 * @since   1.3.2
 */

/**
 * The installable object for the extension.
 *
 * @since 1.3.2
 */
class WordPoints_Reset_Points_Installable extends WordPoints_Installable_Extension {

	/**
	 * @since 1.3.2
	 */
	public function get_update_routine_factories() {

		$factories = parent::get_update_routine_factories();

		// v1.3.0.
		$updater = array( 'WordPoints_Reset_Points_Updater_1_3_0_Points_Types_Settings' );
		$updates = array( 'single' => $updater );

		if ( is_wordpoints_network_active() ) {
			$updates['network'] = $updater;
		} else {
			$updates['site'] = $updater;
		}

		$factories[] = new WordPoints_Updater_Factory( '1.3.0', $updates );

		return $factories;
	}

	/**
	 * @since 1.3.2
	 */
	protected function get_uninstall_routine_factories() {

		$factories = parent::get_uninstall_routine_factories();

		// Skip this if WordPoints itself is being uninstalled, as then it is
		// unnecessary, since all points types will be deleted.
		if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {

			$factories[] = new WordPoints_Uninstaller_Factory(
				array(
					'local' => array(
						'WordPoints_Reset_Points_Uninstaller_Points_Types_Settings',
					),
					'network' => array(
						array(
							'class' => 'WordPoints_Reset_Points_Uninstaller_Points_Types_Settings',
							'args'  => array( true ),
						),
					),
				)
			);
		}

		return $factories;
	}
}

// EOF
