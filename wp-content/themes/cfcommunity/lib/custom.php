<?php
/**
 * Infinity Theme: base
 *
 * @author Bowe Frankema <bowe@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2013 Bowe Frankema
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity
 * @subpackage base
 * @since 1.1
 */

/**
 * Add Typekit
 *
 * @package cfcommunity
 */
function theme_typekit_inline() {
?>
    <script type="text/javascript" src="//use.typekit.net/nfj3xsx.js"></script>
    <script type="text/javascript">try{Typekit.load();}catch(e){}</script>
<?php
}
add_action( 'wp_head', 'theme_typekit_inline' );

/**
 * Add special "admin bar is showing" body class
 */
function cfc_base_admin_bar_class( $classes )
{
	if ( is_admin_bar_showing() ) {
		// *append* class to the array
		$classes[] = 'admin-bar-showing';
	}

	// return it!
	return $classes;
}
add_filter( 'body_class', 'cfc_base_admin_bar_class' );

// Hide Admin
add_filter('show_admin_bar', '__return_false');

//Automatically login user after registration
add_action("gform_user_registered", "autologin", 10, 4);
function autologin($user_id, $config, $entry, $password) {
        wp_set_auth_cookie($user_id, false, '');
}

?>