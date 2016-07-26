<?php

/**
 * Parent test case for the reset points admin screen.
 *
 * @package WordPoints_Reset_Points
 * @since 1.2.0
 */

/**
 * Base for the reset points admin screen tests.
 *
 * @since 1.2.0
 */
abstract class WordPoints_Reset_Points_Admin_Screen_UnitTestCase
	extends WordPoints_Points_UnitTestCase {

	/**
	 * @since 1.2.0
	 */
	public static function setUpBeforeClass() {

		parent::setUpBeforeClass();

		/**
		 * @since 1.2.0
		 */
		include_once( WORDPOINTS_DIR . '/admin/admin.php' );

		/**
		 * @since 1.2.0
		 */
		include_once( dirname( __FILE__ ) . '/../../../../src/admin.php' );
	}

	/**
	 * @since 1.2.0
	 */
	public function setUp() {

		parent::setUp();

		$_POST['_wpnonce'] = wp_create_nonce( 'wordpoints-reset-points' );
		$_POST['reset-points-type-value-points'] = 0;
	}
}

// EOF
