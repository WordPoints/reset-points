<?php

/**
 * Simulates using the plugin.
 *
 * @package WordPoints_Reset_Points
 * @since   1.3.0
 */

wordpoints_add_points_type(
	array( 'name' => 'points', 'reset_date' => time() + WEEK_IN_SECONDS )
);

// EOF
