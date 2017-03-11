<?php

/**
 * Test case for the wordpoints_points_reset_type() function.
 *
 * @package WordPoints_Reset_Points
 * @since 1.2.0
 */

/**
 * Tests for the wordpoints_points_reset_type() function.
 *
 * @since 1.2.0
 *
 * @covers ::wordpoints_points_reset_type
 */
class WordPoints_Points_Reset_Type extends WordPoints_PHPUnit_TestCase_Points {

	/**
	 * Holds an arbitrary value to use with the return_() method.
	 *
	 * @since 1.2.0
	 *
	 * @var mixed
	 */
	protected $return_;

	/**
	 * Test resetting a nonexistent points type.
	 *
	 * @since 1.2.0
	 */
	public function test_reset_nonexistent_point_type() {
		$this->assertFalse( wordpoints_points_reset_type( 'nonexistent' ) );
	}

	/**
	 * Test resetting a points type with no reset value specified.
	 *
	 * @since 1.2.0
	 */
	public function test_no_reset_value() {
		$this->assertFalse( wordpoints_points_reset_type( 'points' ) );
	}

	/**
	 * Test resetting a points type to 0.
	 *
	 * @since 1.2.0
	 */
	public function test_reset_to_0() {

		$user_id = $this->factory->user->create();
		wordpoints_set_points( $user_id, 100, 'points', 'test' );

		$user_id_2 = $this->factory->user->create();

		$settings = wordpoints_get_points_type( 'points' );
		$settings['reset_value'] = 0;
		wordpoints_update_points_type( 'points', $settings );

		$this->assertTrue( wordpoints_points_reset_type( 'points' ) );

		$this->assertSame( 0, wordpoints_get_points( $user_id, 'points' ) );
		$this->assertSame( 0, wordpoints_get_points( $user_id_2, 'points' ) );
	}

	/**
	 * Test resetting a points type to a positive value.
	 *
	 * @since 1.2.0
	 */
	public function test_reset_to_positive() {

		$user_id = $this->factory->user->create();
		wordpoints_set_points( $user_id, 100, 'points', 'test' );

		$user_id_2 = $this->factory->user->create();

		$settings = wordpoints_get_points_type( 'points' );
		$settings['reset_value'] = 50;
		wordpoints_update_points_type( 'points', $settings );

		$this->assertTrue( wordpoints_points_reset_type( 'points' ) );

		$this->assertSame( 50, wordpoints_get_points( $user_id, 'points' ) );
		$this->assertSame( 50, wordpoints_get_points( $user_id_2, 'points' ) );
	}

	/**
	 * Test resetting a points type to a negative value.
	 *
	 * @since 1.2.0
	 */
	public function test_reset_to_negative() {

		$user_id = $this->factory->user->create();
		wordpoints_set_points( $user_id, 100, 'points', 'test' );

		$user_id_2 = $this->factory->user->create();

		$settings = wordpoints_get_points_type( 'points' );
		$settings['reset_value'] = -50;
		wordpoints_update_points_type( 'points', $settings );

		$this->return_ = -50;
		add_filter( 'wordpoints_points_minimum', array( $this, 'return_' ) );

		$this->assertTrue( wordpoints_points_reset_type( 'points' ) );

		$this->assertSame( -50, wordpoints_get_points( $user_id, 'points' ) );
		$this->assertSame( -50, wordpoints_get_points( $user_id_2, 'points' ) );
	}

	//
	// Helpers.
	//

	/**
	 * Returns the value of the return_ property.
	 *
	 * This is useful for attaching to filters to return an arbitrary value.
	 *
	 * @since 1.2.0
	 *
	 * @return mixed
	 */
	public function return_() {
		return $this->return_;
	}
}

// EOF
