<?php
// No direct access, please
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WPKOI_ELEMENTS_LITE_PATH', plugin_dir_path( __FILE__ ) );
define( 'WPKOI_ELEMENTS_LITE_URL', plugins_url( '/', __FILE__ ) );
define( 'WPKOI_ELEMENTS_LITE_VERSION', '1.6.0' );

// Includes
require_once plugin_dir_path( __FILE__ ) . 'includes/elementor-helper.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpkoi-elements-integration.php';

// options for effects
$wtfe_element_effects 		= get_option( 'wtfe_element_effects', '' );
$wtfe_sticky_column			= get_option( 'wtfe_sticky_column', '' );
$wtfe_custom_css			= get_option( 'wtfe_custom_css', '' );

if ( $wtfe_element_effects  != true ) {
	require_once plugin_dir_path( __FILE__ ) . 'elements/effects/effects.php';
}
if ( $wtfe_sticky_column != true ) {
	require_once plugin_dir_path( __FILE__ ) . 'elements/sticky-container/sticky-container.php';
}
if ( $wtfe_custom_css != true ) {
	require_once plugin_dir_path( __FILE__ ) . 'elements/custom-css/custom-css.php';
}