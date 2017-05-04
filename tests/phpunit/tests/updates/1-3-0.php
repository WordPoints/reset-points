<?php

/**
 * Test case for updating to 1.3.0.
 *
 * @package WordPoints_Reset_Points
 * @since 1.3.0
 */

/**
 * Tests updating to 1.3.0.
 *
 * @since 1.3.0
 *
 * @covers WordPoints_Reset_Points_Un_Installer::update_single_to_1_3_0()
 * @covers WordPoints_Reset_Points_Un_Installer::update_site_to_1_3_0()
 * @covers WordPoints_Reset_Points_Un_Installer::update_network_to_1_3_0()
 * @covers WordPoints_Reset_Points_Un_Installer::update_points_type_settings_to_1_3_0()
 */
class WordPoints_Reset_Points_Update_1_3_0_Test
	extends WordPoints_PHPUnit_TestCase_Points {

	/**
	 * @since 1.3.0
	 */
	protected $wordpoints_module = 'reset-points';

	/**
	 * @since 1.3.0
	 */
	protected $previous_version = '1.2.1';

	/**
	 * Test resetting a nonexistent points type.
	 *
	 * @since 1.3.0
	 */
	public function test_reset_nonexistent_point_type() {

		update_option( 'gmt_offset', 5 );

		$now = current_time( 'timestamp' );
		$now_gmt = time();

		$points_type = wordpoints_get_points_type( 'points' );

		$points_type['reset_date'] = $now;

		wordpoints_update_points_type( 'points', $points_type );

		$this->update_module();

		$points_type = wordpoints_get_points_type( 'points' );

		$this->assertSame( $now_gmt, $points_type['reset_date'] );
	}
}

// EOF
