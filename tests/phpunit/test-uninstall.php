<?php

/**
 * Uninstall test case.
 *
 * @package WordPoints_Reset_Points
 * @since 1.3.0
 */

/**
 * Tests uninstalling the extension.
 *
 * @since 1.3.0
 *
 * @covers WordPoints_Reset_Points_Un_Installer
 */
class WordPoints_Reset_Points_Uninstall_Test
	extends WordPoints_PHPUnit_TestCase_Extension_Uninstall {

	/**
	 * Test installation and uninstallation.
	 *
	 * @since 1.3.0
	 */
	public function test_uninstall() {

		$this->uninstall();

		// Check that everything with this extension's prefix has been uninstalled.
		$this->assertUninstalledPrefix( 'wordpoints_reset_points' );

		$this->assertArrayNotHasKey(
			'reset_date'
			, wordpoints_get_points_type( 'points' )
		);
	}
}

// EOF
