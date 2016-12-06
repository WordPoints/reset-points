<?php

/**
 * Test case for the wordpoints_reset_admin_screen_process() function.
 *
 * @package WordPoints_Reset_Points
 * @since 1.2.0
 */

/**
 * Tests for the wordpoints_reset_admin_screen_process() function.
 *
 * @since 1.2.0
 *
 * @covers ::wordpoints_reset_admin_screen_process
 */
class WordPoints_Reset_Admin_Screen_Reset_Test
	extends WordPoints_Reset_Points_Admin_Screen_UnitTestCase {

	/**
	 * The IDs of the users used in the tests.
	 *
	 * @since 1.2.0
	 *
	 * @var int[]
	 */
	protected $user_ids;

	/**
	 * @since 1.2.0
	 */
	public function setUp() {

		parent::setUp();

		$this->user_ids = $this->factory->user->create_many( 2 );
		wordpoints_set_points( $this->user_ids[0], 100, 'points', 'test' );

		$_POST['reset-points-type-points'] = 'Reset Now';
	}

	/**
	 * Test that it works when all values are supplied.
	 *
	 * @since 1.2.0
	 */
	public function test_points_reset() {

		ob_start();
		wordpoints_reset_admin_screen_process();
		$notices = ob_get_clean();

		$this->assertWordPointsAdminNotice( $notices, array( 'type' => 'success' ) );

		$this->assertEquals(
			0
			, wordpoints_get_points_type_setting( 'points', 'reset_value' )
		);

		$this->assertEquals( 0, wordpoints_get_points( $this->user_ids[0], 'points' ) );
		$this->assertEquals( 0, wordpoints_get_points( $this->user_ids[1], 'points' ) );
	}

	/**
	 * Test that it requires an nonce.
	 *
	 * @since 1.2.0
	 */
	public function test_requires_nonce() {

		unset( $_POST['_wpnonce'] );

		$this->assert_nothing_happens();
	}

	/**
	 * Test that it requires a valid nonce.
	 *
	 * @since 1.2.0
	 */
	public function test_requires_valid_nonce() {

		$_POST['_wpnonce'] = 'invalid';

		$this->assert_fails();
	}

	/**
	 * Test that it requires the reset now button to have been pressed.
	 *
	 * @since 1.2.0
	 */
	public function test_requires_reset_button() {

		unset( $_POST['reset-points-type-points'] );

		$this->assert_nothing_happens();
	}

	/**
	 * Test that it requires the points value to be set.
	 *
	 * @since 1.2.0
	 */
	public function test_requires_points_value() {

		unset( $_POST['reset-points-type-value-points'] );

		$this->assert_nothing_happens();
	}

	/**
	 * Test that it requires the points value to be valid.
	 *
	 * @since 1.2.0
	 */
	public function test_requires_valid_points_value() {

		$_POST['reset-points-type-value-points'] = 'invalid';

		$this->assert_fails();
	}

	//
	// Helpers.
	//

	/**
	 * Assert that the form submission fails.
	 *
	 * @since 1.2.0
	 */
	public function assert_fails() {

		ob_start();
		wordpoints_reset_admin_screen_process();
		$notices = ob_get_clean();

		$this->assertWordPointsAdminNotice( $notices, array( 'type' => 'error' ) );

		$this->assertEquals( 100, wordpoints_get_points( $this->user_ids[0], 'points' ) );
		$this->assertEquals( 0, wordpoints_get_points( $this->user_ids[1], 'points' ) );
	}

	/**
	 * Assert that the form submission isn't processed.
	 *
	 * @since 1.2.0
	 */
	public function assert_nothing_happens() {

		ob_start();
		wordpoints_reset_admin_screen_process();
		$notices = ob_get_clean();

		$this->assertEmpty( $notices );

		$this->assertEquals( 100, wordpoints_get_points( $this->user_ids[0], 'points' ) );
		$this->assertEquals( 0, wordpoints_get_points( $this->user_ids[1], 'points' ) );
	}
}

// EOF
