<?php
/**
 * Builds our admin page.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'WPKOI_DOCUMENTATION','https://wpkoi.com/docs/');
define( 'WPKOI_AUTHOR_URL','https://wpkoi.com/');
define( 'WPKOI_SOCIAL_URL','https://www.facebook.com/wpkoithemes/');
define( 'WPKOI_WPORG_URL','https://wordpress.org/themes/author/wpkoithemes/');
define( 'WPKOI_DRIBBLE_URL','https://dribbble.com/wpkoi');
define( 'WPKOI_BEHANCE_URL','https://www.behance.net/wpkoi');
define( 'WPKOI_WORDPRESS_REVIEW','https://wordpress.org/support/theme/' . WPKOI_PARENT_THEME_SLUG . '/reviews/?filter=5');
define( 'WPKOI_IMPORT','https://wpkoi.com/docs/import-free-content/');
define( 'WPKOI_ELEMENTOR_DOCS','https://elementor.com/help/');
define( 'WPKOI_SHOWCASE','https://wpkoi.com/docs/explore-our-wpkoi-wordpress-themes-with-template-parts/');

if ( ! function_exists( 'wpkoi_theme_uri_link' ) ) {
	function wpkoi_theme_uri_link() {
		return 'https://wpkoi.com/' . WPKOI_PARENT_THEME_SLUG . '-wpkoi-wordpress-theme/';
	}
}

// Theme functions
require WPKOI_TEMPLATES_FOR_ELEMENTOR_DIRECTORY . 'theme/inc/theme-functions.php';
require WPKOI_TEMPLATES_FOR_ELEMENTOR_DIRECTORY . 'theme/inc/css-output.php';
require WPKOI_TEMPLATES_FOR_ELEMENTOR_DIRECTORY . 'theme/inc/customizer.php';
require WPKOI_TEMPLATES_FOR_ELEMENTOR_DIRECTORY . 'theme/inc/markup.php';

if ( is_admin() ) {
	// Metaboxes
	require WPKOI_TEMPLATES_FOR_ELEMENTOR_DIRECTORY . 'theme/inc/meta-box.php';
	// Admin dashboard for themes
	require WPKOI_TEMPLATES_FOR_ELEMENTOR_DIRECTORY . 'theme/inc/dashboard.php';
}

// Load our theme structure
require WPKOI_TEMPLATES_FOR_ELEMENTOR_DIRECTORY . 'theme/inc/structures.php';

add_action( WPKOI_PARENT_THEME_SLUG . '_body_schema_init', 'wpkoi_body_schema' );
add_action( WPKOI_PARENT_THEME_SLUG . '_content_class_init', 'wpkoi_content_class' );
add_action( WPKOI_PARENT_THEME_SLUG . '_main_class_init', 'wpkoi_main_class' );
add_action( WPKOI_PARENT_THEME_SLUG . '_footer_class_init', 'wpkoi_footer_class' );


