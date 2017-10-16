<?php

/**
 * Main file of the module.
 *
 * ---------------------------------------------------------------------------------|
 * Copyright 2013-17  J.D. Grimes  (email : jdg@codesymphony.co)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or later, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * ---------------------------------------------------------------------------------|
 *
 * @package WordPoints_Reset_Points
 * @version 1.3.1
 * @author  J.D. Grimes <jdg@codesymphony.co>
 * @license GPLv2+
 */

wordpoints_register_extension(
	'
		Extension Name: Reset Points
		Extension URI:  https://wordpoints.org/extensions/reset-points/
		Author:         J.D. Grimes
		Author URI:     https://codesymphony.co/
		Version:        1.3.1
		License:        GPLv2+
		Description:    Reset your users points on demand or automatically on a scheduled date.
		Server:         wordpoints.org
		ID:             540
		Text Domain:    wordpoints-reset-points
		Domain Path:    /languages
		Namespace:      Reset_Points
	'
	, __FILE__
);

/**
 * The module's main functions.
 *
 * @since 1.3.0
 */
require_once dirname( __FILE__ ) . '/includes/functions.php';

/**
 * The module's deprecated functions.
 *
 * @since 1.3.0
 */
require_once dirname( __FILE__ ) . '/includes/deprecated.php';

if ( is_admin() ) {
	/**
	 * The extension's admin-side code.
	 *
	 * @since 1.0.0
	 */
	require_once dirname( __FILE__ ) . '/admin.php';
}

// EOF
