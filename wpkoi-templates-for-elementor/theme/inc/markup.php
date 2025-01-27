<?php
/**
 * Adds HTML markup.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Figure out which schema tags to apply to the <body> element.
if ( ! function_exists( 'wpkoi_body_schema' ) ) {
	function wpkoi_body_schema() {
		
		$blog = ( is_home() || is_archive() || is_attachment() || is_tax() || is_single() ) ? true : false;

		$itemtype = 'WebPage';

		$itemtype = ( $blog ) ? 'Blog' : $itemtype;

		$itemtype = ( is_search() ) ? 'SearchResultsPage' : $itemtype;

		$result = $itemtype;

		echo "itemtype='https://schema.org/" . esc_html( $result ) . "' itemscope='itemscope'"; 
	}
}

// Adds custom classes to the array of body classes
if ( ! function_exists( 'wpkoi_body_classes' ) ) {
	add_filter( 'body_class', 'wpkoi_body_classes' );
	function wpkoi_body_classes( $classes ) {
		// Get Customizer settings
		$wpkoi_settings = wp_parse_args(
			get_option( 'wpkoi_settings', array() ),
			wpkoi_get_defaults()
		);
		
		$classes[] = 'has-inline-mobile-toggle';
		
		if ( $wpkoi_settings[ 'top_bar_mobile' ] == 'desktop' ) {
			$classes[] = 'desktop-mobile-top-bar';
		}
		
		if ( $wpkoi_settings[ 'enable_content_width' ] == 'enable' ) {
			$classes[] = 'wpkoi-content-container';
		}
		
		if ( $wpkoi_settings[ 'stylish_scrollbar' ] == 'enable' ) {
			$classes[] = 'wpkoi-scrollbar';
		}
		
		if ( $wpkoi_settings[ 'nav_border' ] == 'enable' ) {
			$classes[] = 'wpkoi-nav-border';
		}
		
		if ( $wpkoi_settings[ 'nav_effect' ] != 'none' ) {
			$classes[] = 'navigation-effect-' . esc_attr( $wpkoi_settings[ 'nav_effect' ] );
		}

		if ( $wpkoi_settings['fixed_side_display_mobile'] == true ) {
			$classes[] = 'fixed-side-mobile';
		}
		
		if ( ( $wpkoi_settings[ 'image_cursor' ] == 'enable' ) && ( $wpkoi_settings[ 'def_cursor_image' ] != '' ) && ( $wpkoi_settings[ 'pointer_cursor_image' ] != '' ) ) {
			$classes[] = 'wpkoi-image-cursor';
		}
		
		if ( ( $wpkoi_settings[ 'side_top' ] != 0 ) || ( $wpkoi_settings[ 'side_right' ] != 0 ) || ( $wpkoi_settings[ 'side_bottom' ] != 0 ) || ( $wpkoi_settings[ 'side_left' ] != 0 ) ) {
			$classes[] = 'wpkoi-bodypadding';
		}
		
		if ( 'click' === $wpkoi_settings['nav_dropdown_type'] ) {
			$classes[] = 'dropdown-click';
			$classes[] = 'dropdown-click-menu-item';
		} elseif ( 'click-arrow' === $wpkoi_settings['nav_dropdown_type'] ) {
			$classes[] = 'dropdown-click-arrow';
			$classes[] = 'dropdown-click';
		} else {
			$classes[] = 'dropdown-hover';
		}

		if ( 'enable' === $wpkoi_settings['nav_search'] ) {
			$classes[] = 'nav-search-enabled';
		}

		if ( 'enable' === $wpkoi_settings['wpkoi_cart'] ) {
			$classes[] = 'nav-wpkoi-cart';
		}
		
		$content_link_dec = 'wpkoi-cld-enable'; 
		if ( isset( $wpkoi_settings['content_link_dec'] ) && ( $wpkoi_settings[ 'content_link_dec' ] == 'onhover' ) ) {
			$content_link_dec = 'wpkoi-cld-onhover';
		}
		if ( isset( $wpkoi_settings['content_link_dec'] ) && ( $wpkoi_settings[ 'content_link_dec' ] == 'disable' ) ) {
			$content_link_dec = 'wpkoi-cld-disable';
		}
		
		$classes[] = $content_link_dec;
		
		$classes[] = 'header-aligned-' . $wpkoi_settings['header_alignment_setting'];
		
		if ( $wpkoi_settings['nav_position_setting'] == 'nav-above-header' || $wpkoi_settings['nav_position_setting'] == 'nav-below-header' ) {
			$classes[] = 'nav-aligned-' . $wpkoi_settings['nav_alignment_setting'];
		}
		
		$classes[] = $wpkoi_settings['nav_position_setting'];

		$layout = "no-sidebar";

		$full_width = get_post_meta( get_the_ID(), '_wpkoi-full-width-content', true );
		$classes[] = ( '' !== $full_width && false !== $full_width && is_singular() && 'true' == $full_width ) ? 'full-width-content' : '';

		$classes[] = ( '' !== $full_width && false !== $full_width && is_singular() && 'contained' == $full_width ) ? 'contained-content' : '';
	
		$transparent_header = get_post_meta( get_the_ID(), '_wpkoi-transparent-header', true );
		if ( $transparent_header == true ) {
			$classes[] = 'transparent-header';
		}

		return $classes;
	}
}

// Adds custom classes to the header.
if ( ! function_exists( 'wpkoi_header_classes' ) ) {
	add_filter( 'wpkoi_header_class', 'wpkoi_header_classes' );
	function wpkoi_header_classes( $classes ) {
		$classes[] = 'site-header';

		return $classes;
	}
}

// Adds custom classes to the footer.
if ( ! function_exists( 'wpkoi_footer_classes' ) ) {
	add_filter( 'wpkoi_footer_class', 'wpkoi_footer_classes' );
	function wpkoi_footer_classes( $classes ) {
		$classes[] = 'site-footer';

		return $classes;
	}
}

// Adds custom classes to the <main> element
if ( ! function_exists( 'wpkoi_main_classes' ) ) {
	add_filter( 'wpkoi_main_class', 'wpkoi_main_classes' );
	function wpkoi_main_classes( $classes ) {
		$classes[] = 'site-main';
		return $classes;
	}
}

if ( ! function_exists( 'wpkoi_post_classes' ) ) {
	add_filter( 'post_class', 'wpkoi_post_classes' );
	function wpkoi_post_classes( $classes ) {
		if ( 'page' == get_post_type() ) {
			$classes = array_diff( $classes, array( 'hentry' ) );
		}

		return $classes;
	}
}

// Display the classes for the content.
if ( ! function_exists( 'wpkoi_content_class' ) ) {
	function wpkoi_content_class( $class = '' ) {
		// Separates classes with a single space, collates classes for post DIV
		echo 'class="' . esc_attr( join( ' ', wpkoi_get_content_class( $class ) ) ) . '"'; 
	}
}

// Retrieve the classes for the content.
if ( ! function_exists( 'wpkoi_get_content_class' ) ) {
	function wpkoi_get_content_class( $class = '' ) {

		$classes = array();

		if ( !empty($class) ) {
			if ( !is_array( $class ) )
				$class = preg_split('#\s+#', $class);
			$classes = array_merge($classes, $class);
		}

		$classes = array_map('esc_attr', $classes);

		return apply_filters('wpkoi_content_class', $classes, $class);
	}
}

// Display the classes for the header.
if ( ! function_exists( 'wpkoi_header_class' ) ) {
	function wpkoi_header_class( $class = '' ) {
		// Separates classes with a single space, collates classes for post DIV
		echo 'class="' . esc_attr( join( ' ', wpkoi_get_header_class( $class ) ) ) . '"'; 
	}
}

// Retrieve the classes for the content.
if ( ! function_exists( 'wpkoi_get_header_class' ) ) {
	function wpkoi_get_header_class( $class = '' ) {

		$classes = array();

		if ( !empty($class) ) {
			if ( !is_array( $class ) )
				$class = preg_split('#\s+#', $class);
			$classes = array_merge($classes, $class);
		}

		$classes = array_map('esc_attr', $classes);

		return apply_filters('wpkoi_header_class', $classes, $class);
	}
}

// Display the classes for the container.
if ( ! function_exists( 'wpkoi_container_class' ) ) {
	function wpkoi_container_class( $class = '' ) {
		// Separates classes with a single space, collates classes for post DIV
		echo 'class="' . esc_attr( join( ' ', wpkoi_get_container_class( $class ) ) ) . '"'; 
	}
}

// Retrieve the classes for the content.
if ( ! function_exists( 'wpkoi_get_container_class' ) ) {
	function wpkoi_get_container_class( $class = '' ) {

		$classes = array();

		if ( !empty($class) ) {
			if ( !is_array( $class ) )
				$class = preg_split('#\s+#', $class);
			$classes = array_merge($classes, $class);
		}

		$classes = array_map('esc_attr', $classes);

		return apply_filters('wpkoi_container_class', $classes, $class);
	}
}

// Display the classes for the <main> container.
if ( ! function_exists( 'wpkoi_main_class' ) ) {
	function wpkoi_main_class( $class = '' ) {
		// Separates classes with a single space, collates classes for post DIV
		echo 'class="' . esc_attr( join( ' ', wpkoi_get_main_class( $class ) ) ) . '"'; 
	}
}

// Retrieve the classes for the footer.
if ( ! function_exists( 'wpkoi_get_main_class' ) ) {
	function wpkoi_get_main_class( $class = '' ) {

		$classes = array();

		if ( !empty($class) ) {
			if ( !is_array( $class ) )
				$class = preg_split('#\s+#', $class);
			$classes = array_merge($classes, $class);
		}

		$classes = array_map('esc_attr', $classes);

		return apply_filters('wpkoi_main_class', $classes, $class);
	}
}

// Display the classes for the footer.
if ( ! function_exists( 'wpkoi_footer_class' ) ) {
	function wpkoi_footer_class( $class = '' ) {
		// Separates classes with a single space, collates classes for post DIV
		echo 'class="' . esc_attr( join( ' ', wpkoi_get_footer_class( $class ) ) ) . '"'; 
	}
}

// Retrieve the classes for the footer.
if ( ! function_exists( 'wpkoi_get_footer_class' ) ) {
	function wpkoi_get_footer_class( $class = '' ) {

		$classes = array();

		if ( !empty($class) ) {
			if ( !is_array( $class ) )
				$class = preg_split('#\s+#', $class);
			$classes = array_merge($classes, $class);
		}

		$classes = array_map('esc_attr', $classes);

		return apply_filters('wpkoi_footer_class', $classes, $class);
	}
}
