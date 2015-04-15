<?php

/**
 * Test case for the wordpoints_points_reset_on_date() function.
 *
 * @package WordPoints_Reset_Points
 * @since 1.2.0
 */

/**
 * Tests for wordpoints_points_reset_on_date().
 *
 * @since 1.2.0
 *
 * @covers ::wordpoints_points_reset_on_date
 */
class WordPoints_Points_Reset_On_Date_Test extends WordPoints_Points_UnitTestCase {

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

		$settings = wordpoints_get_points_type( 'points' );
		$settings['reset_value'] = 0;
		wordpoints_update_points_type( 'points', $settings );
	}

	/**
	 * Test with no points types created.
	 *
	 * @since 1.2.0
	 */
	public function test_no_points_types() {

		wordpoints_delete_points_type( 'points' );

		$this->assertEmpty( wordpoints_get_points_types() );

		wordpoints_points_reset_on_date();
	}

	/**
	 * Test that the points aren't reset if no date is specified.
	 *
	 * @since 1.2.0
	 */
	public function test_not_reset_if_date_not_set() {

		wordpoints_points_reset_on_date();

		$this->assert_points_not_reset();
	}

	/**
	 * Test that the points aren't reset if the date is in the future.
	 *
	 * @since 1.2.0
	 */
	public function test_not_reset_if_date_in_future() {

		$settings = wordpoints_get_points_type( 'points' );
		$settings['reset_date']  = current_time( 'timestamp' ) + 5;
		wordpoints_update_points_type( 'points', $settings );

		wordpoints_points_reset_on_date();

		$this->assert_points_not_reset();

		$this->assertEquals(
			$settings['reset_date']
			, wordpoints_get_points_type_setting( 'points', 'reset_date' )
		);
	}

	/**
	 * Test that the points aren't reset if the date is invalid.
	 *
	 * @since 1.2.0
	 */
	public function test_not_reset_if_date_invalid() {

		$settings = wordpoints_get_points_type( 'points' );
		$settings['reset_date']  = 'invalid';
		wordpoints_update_points_type( 'points', $settings );

		wordpoints_points_reset_on_date();

		$this->assert_points_not_reset();

		$this->assertEquals(
			'invalid'
			, wordpoints_get_points_type_setting( 'points', 'reset_date' )
		);
	}

	/**
	 * Test that the points are reset if the date has passed.
	 *
	 * @since 1.2.0
	 */
	public function test_points_reset_if_date_passed() {

		$settings = wordpoints_get_points_type( 'points' );
		$settings['reset_date']  = current_time( 'timestamp' ) - 5;
		wordpoints_update_points_type( 'points', $settings );

		wordpoints_points_reset_on_date();

		$this->assert_points_reset();

		$this->assertArrayNotHasKey(
			'reset_date'
			, wordpoints_get_points_type( 'points' )
		);
	}

	//
	// Helpers.
	//

	/**
	 * Assert that the points aren't reset.
	 *
	 * @since 1.2.0
	 */
	public function assert_points_not_reset() {

		$this->assertEquals( 100, wordpoints_get_points( $this->user_ids[0], 'points' ) );
		$this->assertEquals( 0, wordpoints_get_points( $this->user_ids[1], 'points' ) );
	}

	/**
	 * Assert that the points were reset.
	 *
	 * @since 1.2.0
	 */
	public function assert_points_reset() {

		$this->assertEquals( 0, wordpoints_get_points( $this->user_ids[0], 'points' ) );
		$this->assertEquals( 0, wordpoints_get_points( $this->user_ids[1], 'points' ) );
	}
}

// EOF
