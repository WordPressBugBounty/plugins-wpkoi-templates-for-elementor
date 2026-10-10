<?php
/**
 * Main functions for WPKoi themes.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Get the default values from the WPKoi theme that is loaded right now.
// It can differ from WPKOI_PARENT_THEME_SLUG, for example in the Live Preview of an other WPKoi theme.
if ( ! function_exists( 'wpkoi_theme_defaults_filter' ) ) {
	function wpkoi_theme_defaults_filter( $value ) {
		$theme_functions = array(
			'wpkoi_option_defaults'          => '_get_defaults',
			'wpkoi_default_color_palettes'   => '_get_default_color_palettes',
			'wpkoi_typography_default_fonts' => '_get_default_fonts',
			'wpkoi_typography_inherit_fonts' => '_get_inherit_fonts',
		);

		$filter = current_filter();
		if ( ! isset( $theme_functions[ $filter ] ) ) {
			return $value;
		}

		foreach ( array_unique( array( WPKOI_PARENT_THEME_SLUG, get_template() ) ) as $theme_slug ) {
			$theme_function = $theme_slug . $theme_functions[ $filter ];

			if ( in_array( $theme_slug, WPKOI_ALLOWED_THEMES, true ) && function_exists( $theme_function ) ) {
				return call_user_func( $theme_function );
			}
		}

		return $value;
	}
}

// Get defaults from the theme
add_filter( 'wpkoi_option_defaults', 'wpkoi_theme_defaults_filter', 10 );
if ( ! function_exists( 'wpkoi_get_defaults' ) ) {
	function wpkoi_get_defaults() {
		$wpkoi_defaults = array();

		return apply_filters( 'wpkoi_option_defaults', $wpkoi_defaults );
	}
}

// Get colors from the theme
add_filter( 'wpkoi_default_color_palettes', 'wpkoi_theme_defaults_filter', 10 );
if ( ! function_exists( 'wpkoi_get_default_color_palettes' ) ) {
	function wpkoi_get_default_color_palettes() {
		$wpkoi_palettes = array();

		return apply_filters( 'wpkoi_default_color_palettes', $wpkoi_palettes );
	}
}

// Get font defaults from the theme
add_filter( 'wpkoi_typography_default_fonts', 'wpkoi_theme_defaults_filter', 5 );
if ( ! function_exists( 'wpkoi_typography_default_fonts' ) ) {
	function wpkoi_typography_default_fonts() {
		$fonts = array();

		return apply_filters( 'wpkoi_typography_default_fonts', $fonts );
	}
}

// Set the inherit system fonts from the theme
add_filter( 'wpkoi_typography_inherit_fonts', 'wpkoi_theme_defaults_filter', 10 );
if ( ! function_exists( 'wpkoi_typography_inherit_fonts' ) ) {
	function wpkoi_typography_inherit_fonts() {
		$fonts = array();

		return apply_filters( 'wpkoi_typography_inherit_fonts', $fonts );
	}
}

// A wrapper function to get our settings.
if ( ! function_exists( 'wpkoi_get_setting' ) ) {
	function wpkoi_get_setting( $setting ) {
		$wpkoi_settings = wp_parse_args(
			get_option( 'wpkoi_settings', array() ),
			wpkoi_get_defaults()
		);

		return $wpkoi_settings[ $setting ];
	}
}

// Dequeue the default css from the theme to replace it with dynamic values
if ( ! function_exists( 'wpkoi_dequeue_scripts' ) ) {
	add_action( 'wp_enqueue_scripts', 'wpkoi_dequeue_scripts', 100 );
	function wpkoi_dequeue_scripts() {
		wp_dequeue_style( WPKOI_PARENT_THEME_SLUG . '-defaults' );
	}
}


// Enqueue scripts and styles
if ( ! function_exists( 'wpkoi_scripts' ) ) {
	add_action( 'wp_enqueue_scripts', 'wpkoi_scripts' );
	function wpkoi_scripts() {
		$wpkoi_settings = wp_parse_args(
			get_option( 'wpkoi_settings', array() ),
			wpkoi_get_defaults()
		);

		$dir_uri = WPKOI_TEMPLATES_FOR_ELEMENTOR_URL . 'theme';
		
		wp_enqueue_style( 'wpkoi-theme-style', esc_url( $dir_uri ) . "/css/style.min.css", array(), WPKOI_TEMPLATES_FOR_ELEMENTOR_VERSION, 'all' );
		
		wp_enqueue_script( 'wpkoi-menu', esc_url( $dir_uri ) . "/js/menu.min.js", array(), WPKOI_TEMPLATES_FOR_ELEMENTOR_VERSION, true );
		
		wp_localize_script(
			'wpkoi-menu',
			'wpkoiMenu',
			apply_filters(
				'wpkoi_localize_js_args',
				array(
					'toggleOpenedSubMenus' => true,
					'openSubMenuLabel' => esc_attr__( 'Open Sub-Menu', 'wpkoi-templates-for-elementor' ),
					'closeSubMenuLabel' => esc_attr__( 'Close Sub-Menu', 'wpkoi-templates-for-elementor' ),
				)
			)
		);

		if ( 'click' === $wpkoi_settings['nav_dropdown_type'] || 'click-arrow' === $wpkoi_settings['nav_dropdown_type'] ) {
			wp_enqueue_script( 'wpkoi-dropdown-click', esc_url( $dir_uri ) . "/js/dropdown-click.min.js", array(), WPKOI_TEMPLATES_FOR_ELEMENTOR_VERSION, true );

			wp_localize_script(
				'wpkoi-dropdown-click',
				'wpkoiDropdownClick',
				array(
					'openSubMenuLabel' => esc_attr__( 'Open Sub-Menu', 'wpkoi-templates-for-elementor' ),
					'closeSubMenuLabel' => esc_attr__( 'Close Sub-Menu', 'wpkoi-templates-for-elementor' ),
				)
			);
		}

		if ( 'enable' === $wpkoi_settings['nav_search'] ) {
			wp_enqueue_script( 'wpkoi-navigation-search', esc_url( $dir_uri ) . "/js/navigation-search.min.js", array(), WPKOI_TEMPLATES_FOR_ELEMENTOR_VERSION, true );

			wp_localize_script(
				'wpkoi-navigation-search',
				'wpkoiNavSearch',
				array(
					'open' => esc_attr__( 'Open Search Bar', 'wpkoi-templates-for-elementor' ),
					'close' => esc_attr__( 'Close Search Bar', 'wpkoi-templates-for-elementor' ),
				)
			);
		}

		if ( 'enable' == $wpkoi_settings['back_to_top'] ) {
			wp_enqueue_script( 'wpkoi-back-to-top', esc_url( $dir_uri ) . "/js/back-to-top.min.js", array(), WPKOI_TEMPLATES_FOR_ELEMENTOR_VERSION, true );
		}
	}
}

// Main class to work with dynamic CSS
if ( ! class_exists( 'wpkoi_css' ) ) {
	class wpkoi_css {

		protected $_selector = '';

		protected $_selector_output = '';

		protected $_css = '';

		protected $_output = '';

		protected $_media_query = null;

		protected $_media_query_output = '';

		public function set_selector( $selector = '' ) {
			// Render the css in the output string everytime the selector changes.
			if ( $this->_selector !== '' ) {
				$this->add_selector_rules_to_output();
			}

			$this->_selector = $selector;
			return $this;
		}

		public function add_property( $property, $value, $og_default = false, $unit = false ) {
			// Add our unit to our value if it exists.
			if ( $unit && '' !== $unit ) {
				$value = $value . $unit;
				if ( '' !== $og_default ) {
					$og_default = $og_default . $unit;
				}
			}

			// If we don't have a value or our value is the same as our og default, bail.
			if ( empty( $value ) ) {
				return false;
			}

			$this->_css .= $property . ':' . $value . ';';
			return $this;
		}

		public function start_media_query( $value ) {
			// Add the current rules to the output.
			$this->add_selector_rules_to_output();

			// Add any previous media queries to the output.
			if ( ! empty( $this->_media_query ) ) {
				$this->add_media_query_rules_to_output();
			}

			// Set the new media query.
			$this->_media_query = $value;
			return $this;
		}

		public function stop_media_query() {
			return $this->start_media_query( null );
		}

		private function add_media_query_rules_to_output() {
			if ( ! empty( $this->_media_query_output ) ) {
				$this->_output .= sprintf( '@media %1$s{%2$s}', $this->_media_query, $this->_media_query_output );

				// Reset the media query output string.
				$this->_media_query_output = '';
			}

			return $this;
		}

		private function add_selector_rules_to_output() {
			if ( ! empty( $this->_css ) ) {
				$this->_selector_output = $this->_selector;
				$selector_output = sprintf( '%1$s{%2$s}', $this->_selector_output, $this->_css );

				// Add our CSS to the output.
				if ( ! empty( $this->_media_query ) ) {
					$this->_media_query_output .= $selector_output;
					$this->_css = '';
				} else {
					$this->_output .= $selector_output;
				}

				// Reset the css.
				$this->_css = '';
			}

			return $this;
		}

		public function css_output() {
			// Add current selector's rules to output.
			$this->add_selector_rules_to_output();

			// Output minified css.
			return $this->_output;
		}

	}
}
