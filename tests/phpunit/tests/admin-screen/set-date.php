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
class WordPoints_Reset_Admin_Screen_Set_Date_Test
	extends WordPoints_Reset_Points_Admin_Screen_UnitTestCase {

	/**
	 * The Y-m-d for the date used in the tests.
	 *
	 * @since 1.2.0
	 *
	 * @var string
	 */
	protected $date;

	/**
	 * The timestamp for the date used in the tests.
	 *
	 * @since 1.2.0
	 *
	 * @var int
	 */
	protected $time;

	/**
	 * @since 1.2.0
	 */
	public function setUp() {

		parent::setUp();

		$this->date = date( 'Y-m-d', current_time( 'timestamp' ) + WEEK_IN_SECONDS );
		$this->time = strtotime( $this->date, current_time( 'timestamp' ) );

		$_POST['reset-points-type-date-set-points'] = 'Set Date';
		$_POST['reset-points-type-date-points'] = $this->date;
	}

	/**
	 * Test that it works when all values are supplied.
	 *
	 * @since 1.2.0
	 */
	public function test_set_date() {

		ob_start();
		wordpoints_reset_admin_screen_process();
		$notices = ob_get_clean();

		$this->assertWordPointsAdminNotice( $notices );

		$this->assertEquals(
			0
			, wordpoints_get_points_type_setting( 'points', 'reset_value' )
		);

		$this->assertEquals(
			$this->time
			, wordpoints_get_points_type_setting( 'points', 'reset_date' )
		);
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
	 * Test that it requires the set date button to have been pressed.
	 *
	 * @since 1.2.0
	 */
	public function test_requires_set_button() {

		unset( $_POST['reset-points-type-date-set-points'] );

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

	/**
	 * Test that it requires the date to be set.
	 *
	 * @since 1.2.0
	 */
	public function test_requires_date() {

		unset( $_POST['reset-points-type-date-points'] );

		$this->assert_nothing_happens();
	}

	/**
	 * Test that the date is unset if empty.
	 *
	 * @since 1.2.0
	 */
	public function test_unset_date() {

		$settings = wordpoints_get_points_type( 'points' );
		$settings['reset_date'] = $this->time + DAY_IN_SECONDS;
		wordpoints_update_points_type( 'points', $settings );

		$_POST['reset-points-type-date-points'] = '';

		ob_start();
		wordpoints_reset_admin_screen_process();
		$notices = ob_get_clean();

		$this->assertWordPointsAdminNotice( $notices );

		$this->assertEquals(
			0
			, wordpoints_get_points_type_setting( 'points', 'reset_value' )
		);

		$this->assertNull(
			wordpoints_get_points_type_setting( 'points', 'reset_date' )
		);
	}

	/**
	 * Test that it does nothing if the dates are equal
	 *
	 * @since 1.2.0
	 */
	public function test_same_date() {

		$settings = wordpoints_get_points_type( 'points' );
		$settings['reset_date'] = $this->time;
		wordpoints_update_points_type( 'points', $settings );

		$this->assert_nothing_happens();
	}

	/**
	 * Test that if fails if the date is not set and there is no current date.
	 *
	 * @since 1.2.0
	 */
	public function test_requires_date_if_no_date_set() {

		$_POST['reset-points-type-date-points'] = '';

		$this->assert_fails();
	}

	/**
	 * Test that it requires the date to be valid.
	 *
	 * @since 1.2.0
	 */
	public function test_requires_valid_date() {

		$_POST['reset-points-type-date-points'] = 'invalid';

		$this->assert_fails();
	}

	/**
	 * Test that it requires the date to be in the future.
	 *
	 * @since 1.2.0
	 */
	public function test_requires_future_date() {

		$_POST['reset-points-type-date-points'] = date( 'Y-m-d', current_time( 'timestamp' ) - WEEK_IN_SECONDS );

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

		$this->assertNull(
			wordpoints_get_points_type_setting( 'points', 'reset_value' )
		);

		$this->assertNull(
			wordpoints_get_points_type_setting( 'points', 'reset_date' )
		);
	}

	/**
	 * Assert that the form submission isn't processed.
	 *
	 * @since 1.2.0
	 */
	public function assert_nothing_happens() {

		$reset_value = wordpoints_get_points_type_setting( 'points', 'reset_value' );
		$reset_date = wordpoints_get_points_type_setting( 'points', 'reset_date' );

		ob_start();
		wordpoints_reset_admin_screen_process();
		$notices = ob_get_clean();

		$this->assertEmpty( $notices );

		$this->assertEquals(
			$reset_value
			, wordpoints_get_points_type_setting( 'points', 'reset_value' )
		);

		$this->assertEquals(
			$reset_date
			, wordpoints_get_points_type_setting( 'points', 'reset_date' )
		);
	}
}

// EOF
