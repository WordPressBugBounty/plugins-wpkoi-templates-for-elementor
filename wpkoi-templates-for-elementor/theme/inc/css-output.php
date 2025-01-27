<?php
/**
 * Output all of our dynamic CSS.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Add font families
if ( ! function_exists( 'wpkoi_font_family_css' ) ) {
	function wpkoi_font_family_css() {
		
		$wpkoi_settings = wp_parse_args(
			get_option( 'wpkoi_settings', array() ),
			wpkoi_get_defaults()
		);

		$css = new wpkoi_css;
		
		$og_defaults = wpkoi_get_defaults( false );
		
		$wpkoi_df = wpkoi_typography_inherit_fonts();
		
		$bodyclass = 'body';
		if ( is_admin() ) {
			$bodyclass = '.editor-styles-wrapper';
		}
		
		$bodyfont = $wpkoi_settings[ 'font_body' ];
		if ( $bodyfont == 'inherit' ) { $bodyfont = $wpkoi_df['bodyfont']; }
		
		$font_site_title = $wpkoi_settings[ 'font_site_title' ];
		if ( $font_site_title == 'inherit' ) { $font_site_title = $wpkoi_df['font_site_title']; }
		$font_navigation = $wpkoi_settings[ 'font_navigation' ];
		if ( $font_navigation == 'inherit' ) { $font_navigation = $wpkoi_df['font_navigation']; }
		$font_buttons = $wpkoi_settings[ 'font_buttons' ];
		if ( $font_buttons == 'inherit' ) { $font_buttons = $wpkoi_df['font_buttons']; }
		$font_heading_1 = $wpkoi_settings[ 'font_heading_1' ];
		if ( $font_heading_1 == 'inherit' ) { $font_heading_1 = $wpkoi_df['font_heading_1']; }
		$font_heading_2 = $wpkoi_settings[ 'font_heading_2' ];
		if ( $font_heading_2 == 'inherit' ) { $font_heading_2 = $wpkoi_df['font_heading_2']; }
		$font_heading_3 = $wpkoi_settings[ 'font_heading_3' ];
		if ( $font_heading_3 == 'inherit' ) { $font_heading_3 = $wpkoi_df['font_heading_3']; }
		$font_heading_4 = $wpkoi_settings[ 'font_heading_4' ];
		if ( $font_heading_4 == 'inherit' ) { $font_heading_4 = $wpkoi_df['font_heading_4']; }
		$font_heading_5 = $wpkoi_settings[ 'font_heading_5' ];
		if ( $font_heading_5 == 'inherit' ) { $font_heading_5 = $wpkoi_df['font_heading_5']; }
		$font_heading_6 = $wpkoi_settings[ 'font_heading_6' ];
		if ( $font_heading_6 == 'inherit' ) { $font_heading_6 = $wpkoi_df['font_heading_6']; }
		$font_footer = $wpkoi_settings[ 'font_footer' ];
		if ( $font_footer == 'inherit' ) { $font_footer = $wpkoi_df['font_footer']; }
		$font_fixed_side = $wpkoi_settings[ 'font_fixed_side' ];
		if ( $font_fixed_side == 'inherit' ) { $font_fixed_side = $wpkoi_df['font_fixed_side']; }
		
		$css->set_selector( $bodyclass );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--font-body', esc_attr( $bodyfont ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--font-site-title', esc_attr( $font_site_title ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--font-navigation', esc_attr( $font_navigation ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--font-buttons', esc_attr( $font_buttons ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--font-heading-1', esc_attr( $font_heading_1 ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--font-heading-2', esc_attr( $font_heading_2 ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--font-heading-3', esc_attr( $font_heading_3 ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--font-heading-4', esc_attr( $font_heading_4 ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--font-heading-5', esc_attr( $font_heading_5 ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--font-heading-6', esc_attr( $font_heading_6 ) );
		$css->add_property( '--wpkoi--font-footer', esc_attr( $font_footer ) );
		$css->add_property( '--wpkoi--font-fixed-side', esc_attr( $font_fixed_side ) );
		
		// Allow us to hook CSS into our output
		do_action( 'wpkoi_font_family_css', $css );

		return apply_filters( 'wpkoi_font_family_css_output', $css->css_output() );
	}
}

// Generate the CSS in the <head> section using the Theme Customizer.
if ( ! function_exists( 'wpkoi_base_css' ) ) {
	function wpkoi_base_css() {
		
		$wpkoi_settings = wp_parse_args(
			get_option( 'wpkoi_settings', array() ),
			wpkoi_get_defaults()
		);
		$body_background = '#' . get_background_color();

		$css = new wpkoi_css;
		
		$og_defaults = wpkoi_get_defaults( false );
		
		$bodyclass = 'body';
		if ( is_admin() ) {
			$bodyclass = '.editor-styles-wrapper';
		}
		
		$desktop_logo_width_unit = isset( $wpkoi_settings['desktop_logo_width_unit'] ) ? $wpkoi_settings['desktop_logo_width_unit'] : $og_defaults[ 'desktop_logo_width_unit' ];
		$mobile_logo_width_unit = isset( $wpkoi_settings['mobile_logo_width_unit'] ) ? $wpkoi_settings['mobile_logo_width_unit'] : $og_defaults[ 'mobile_logo_width_unit' ];
		
		$navigation_items_spacing_unit = isset( $wpkoi_settings['desktop_navigation_items_spacing_unit'] ) ? $wpkoi_settings['desktop_navigation_items_spacing_unit'] : $og_defaults[ 'desktop_navigation_items_spacing_unit' ];
		$navigation_items_width_unit = isset( $wpkoi_settings['desktop_navigation_items_width_unit'] ) ? $wpkoi_settings['desktop_navigation_items_width_unit'] : $og_defaults[ 'desktop_navigation_items_width_unit' ];
		$navigation_items_height_unit = isset( $wpkoi_settings['desktop_navigation_items_height_unit'] ) ? $wpkoi_settings['desktop_navigation_items_height_unit'] : $og_defaults[ 'desktop_navigation_items_height_unit' ];
		$subnavigation_width_unit = isset( $wpkoi_settings['desktop_subnavigation_width_unit'] ) ? $wpkoi_settings['desktop_subnavigation_width_unit'] : $og_defaults[ 'desktop_subnavigation_width_unit' ];
		
		$desktop_body_font_size_unit = isset( $wpkoi_settings['desktop_body_font_size_unit'] ) ? $wpkoi_settings['desktop_body_font_size_unit'] : $og_defaults[ 'desktop_body_font_size_unit' ];
		$mobile_body_font_size_unit = isset( $wpkoi_settings['mobile_body_font_size_unit'] ) ? $wpkoi_settings['mobile_body_font_size_unit'] : $og_defaults[ 'mobile_body_font_size_unit' ];
		$desktop_site_title_font_size_unit = isset( $wpkoi_settings['desktop_site_title_font_size_unit'] ) ? $wpkoi_settings['desktop_site_title_font_size_unit'] : $og_defaults[ 'desktop_site_title_font_size_unit' ];
		$mobile_site_title_font_size_unit = isset( $wpkoi_settings['mobile_site_title_font_size_unit'] ) ? $wpkoi_settings['mobile_site_title_font_size_unit'] : $og_defaults[ 'mobile_site_title_font_size_unit' ];
		$desktop_navigation_font_size_unit = isset( $wpkoi_settings['desktop_navigation_font_size_unit'] ) ? $wpkoi_settings['desktop_navigation_font_size_unit'] : $og_defaults[ 'desktop_navigation_font_size_unit' ];
		$tablet_navigation_font_size_unit = isset( $wpkoi_settings['tablet_navigation_font_size_unit'] ) ? $wpkoi_settings['tablet_navigation_font_size_unit'] : $og_defaults[ 'tablet_navigation_font_size_unit' ];
		$mobile_navigation_font_size_unit = isset( $wpkoi_settings['mobile_navigation_font_size_unit'] ) ? $wpkoi_settings['mobile_navigation_font_size_unit'] : $og_defaults[ 'mobile_navigation_font_size_unit' ];
		$desktop_buttons_font_size_unit = isset( $wpkoi_settings['desktop_buttons_font_size_unit'] ) ? $wpkoi_settings['desktop_buttons_font_size_unit'] : $og_defaults[ 'desktop_buttons_font_size_unit' ];
		$mobile_buttons_font_size_unit = isset( $wpkoi_settings['mobile_buttons_font_size_unit'] ) ? $wpkoi_settings['mobile_buttons_font_size_unit'] : $og_defaults[ 'mobile_buttons_font_size_unit' ];
		$desktop_heading_1_font_size_unit = isset( $wpkoi_settings['desktop_heading_1_font_size_unit'] ) ? $wpkoi_settings['desktop_heading_1_font_size_unit'] : $og_defaults[ 'desktop_heading_1_font_size_unit' ];
		$mobile_heading_1_font_size_unit = isset( $wpkoi_settings['mobile_heading_1_font_size_unit'] ) ? $wpkoi_settings['mobile_heading_1_font_size_unit'] : $og_defaults[ 'mobile_heading_1_font_size_unit' ];
		$desktop_heading_2_font_size_unit = isset( $wpkoi_settings['desktop_heading_2_font_size_unit'] ) ? $wpkoi_settings['desktop_heading_2_font_size_unit'] : $og_defaults[ 'desktop_heading_2_font_size_unit' ];
		$mobile_heading_2_font_size_unit = isset( $wpkoi_settings['mobile_heading_2_font_size_unit'] ) ? $wpkoi_settings['mobile_heading_2_font_size_unit'] : $og_defaults[ 'mobile_heading_2_font_size_unit' ];
		$desktop_heading_3_font_size_unit = isset( $wpkoi_settings['desktop_heading_3_font_size_unit'] ) ? $wpkoi_settings['desktop_heading_3_font_size_unit'] : $og_defaults[ 'desktop_heading_3_font_size_unit' ];
		$mobile_heading_3_font_size_unit = isset( $wpkoi_settings['mobile_heading_3_font_size_unit'] ) ? $wpkoi_settings['mobile_heading_3_font_size_unit'] : $og_defaults[ 'mobile_heading_3_font_size_unit' ];
		$desktop_heading_4_font_size_unit = isset( $wpkoi_settings['desktop_heading_4_font_size_unit'] ) ? $wpkoi_settings['desktop_heading_4_font_size_unit'] : $og_defaults[ 'desktop_heading_4_font_size_unit' ];
		$mobile_heading_4_font_size_unit = isset( $wpkoi_settings['mobile_heading_4_font_size_unit'] ) ? $wpkoi_settings['mobile_heading_4_font_size_unit'] : $og_defaults[ 'mobile_heading_4_font_size_unit' ];
		$desktop_heading_5_font_size_unit = isset( $wpkoi_settings['desktop_heading_5_font_size_unit'] ) ? $wpkoi_settings['desktop_heading_5_font_size_unit'] : $og_defaults[ 'desktop_heading_5_font_size_unit' ];
		$mobile_heading_5_font_size_unit = isset( $wpkoi_settings['mobile_heading_5_font_size_unit'] ) ? $wpkoi_settings['mobile_heading_5_font_size_unit'] : $og_defaults[ 'mobile_heading_5_font_size_unit' ];
		$desktop_heading_6_font_size_unit = isset( $wpkoi_settings['desktop_heading_6_font_size_unit'] ) ? $wpkoi_settings['desktop_heading_6_font_size_unit'] : $og_defaults[ 'desktop_heading_6_font_size_unit' ];
		$mobile_heading_6_font_size_unit = isset( $wpkoi_settings['mobile_heading_6_font_size_unit'] ) ? $wpkoi_settings['mobile_heading_6_font_size_unit'] : $og_defaults[ 'mobile_heading_6_font_size_unit' ];
		$desktop_footer_font_size_unit = isset( $wpkoi_settings['desktop_footer_font_size_unit'] ) ? $wpkoi_settings['desktop_footer_font_size_unit'] : $og_defaults[ 'desktop_footer_font_size_unit' ];
		$mobile_footer_font_size_unit = isset( $wpkoi_settings['mobile_footer_font_size_unit'] ) ? $wpkoi_settings['mobile_footer_font_size_unit'] : $og_defaults[ 'mobile_footer_font_size_unit' ];
		$desktop_fixed_side_font_size_unit = isset( $wpkoi_settings['desktop_fixed_side_font_size_unit'] ) ? $wpkoi_settings['desktop_fixed_side_font_size_unit'] : $og_defaults[ 'desktop_fixed_side_font_size_unit' ];
		$mobile_fixed_side_font_size_unit = isset( $wpkoi_settings['mobile_fixed_side_font_size_unit'] ) ? $wpkoi_settings['mobile_fixed_side_font_size_unit'] : $og_defaults[ 'mobile_fixed_side_font_size_unit' ];
		
		$css->set_selector( $bodyclass );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--body-background', esc_attr( $body_background ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--text-color', esc_attr( $wpkoi_settings[ 'text_color' ] ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--link-color', esc_attr( $wpkoi_settings[ 'link_color' ] ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--link-color-hover', esc_attr( $wpkoi_settings[ 'link_color_hover' ] ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--side-inside-color', esc_attr( $wpkoi_settings[ 'side_inside_color' ] ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--header-background-color', esc_attr( $wpkoi_settings[ 'header_background_color' ] ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--header-text-color', esc_attr( $wpkoi_settings[ 'header_text_color' ] ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--header-link-color', esc_attr( $wpkoi_settings[ 'header_link_color' ] ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--header-link-hover-color', esc_attr( $wpkoi_settings[ 'header_link_hover_color' ] ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--sticky-header-background-color', esc_attr( $wpkoi_settings[ 'sticky_header_background_color' ] ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--site-title-color', esc_attr( $wpkoi_settings[ 'site_title_color' ] ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--site-title-bg-color', esc_attr( $wpkoi_settings[ 'site_title_bg_color' ] ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--navigation-background-color', esc_attr( $wpkoi_settings[ 'navigation_background_color' ] ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--navigation-text-color', esc_attr( $wpkoi_settings[ 'navigation_text_color' ] ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--navigation-background-hover-color', esc_attr( $wpkoi_settings[ 'navigation_background_hover_color' ] ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--navigation-text-hover-color', esc_attr( $wpkoi_settings[ 'navigation_text_hover_color' ] ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--navigation-text-current_color', esc_attr( $wpkoi_settings[ 'navigation_text_current_color' ] ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--navigation-background-current-color', esc_attr( $wpkoi_settings[ 'navigation_background_current_color' ] ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--navigation-border-color', esc_attr( $wpkoi_settings[ 'navigation_border_color' ] ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--subnavigation-background-color', esc_attr( $wpkoi_settings[ 'subnavigation_background_color' ] ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--subnavigation-text-color', esc_attr( $wpkoi_settings[ 'subnavigation_text_color' ] ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--subnavigation-background-hover-color', esc_attr( $wpkoi_settings[ 'subnavigation_background_hover_color' ] ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--subnavigation-text-hover-color', esc_attr( $wpkoi_settings[ 'subnavigation_text_hover_color' ] ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--subnavigation-text-current-color', esc_attr( $wpkoi_settings[ 'subnavigation_text_current_color' ] ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--subnavigation-background-current-color', esc_attr( $wpkoi_settings[ 'subnavigation_background_current_color' ] ) );		
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--form-button-background-color', esc_attr( $wpkoi_settings[ 'form_button_background_color' ] ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--form-button-background-color-hover', esc_attr( $wpkoi_settings[ 'form_button_background_color_hover' ] ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--form-button-text-color', esc_attr( $wpkoi_settings[ 'form_button_text_color' ] ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--form-button-text-color-hover', esc_attr( $wpkoi_settings[ 'form_button_text_color_hover' ] ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--form-button-border-color', esc_attr( $wpkoi_settings[ 'form_button_border_color' ] ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--form-button-border-color-hover', esc_attr( $wpkoi_settings[ 'form_button_border_color_hover' ] ) );
		$css->add_property( '--wpkoi--fixed-side-content-background-color', esc_attr( $wpkoi_settings[ 'fixed_side_content_background_color' ] ) );
		$css->add_property( '--wpkoi--fixed-side-content-text-color', esc_attr( $wpkoi_settings[ 'fixed_side_content_text_color' ] ) );
		$css->add_property( '--wpkoi--fixed-side-content-link-color', esc_attr( $wpkoi_settings[ 'fixed_side_content_link_color' ] ) );
		$css->add_property( '--wpkoi--fixed-side-content-link-hover-color', esc_attr( $wpkoi_settings[ 'fixed_side_content_link_hover_color' ] ) );
		$css->add_property( '--wpkoi--back-to-top-background-color', esc_attr( $wpkoi_settings[ 'back_to_top_background_color' ] ) );
		$css->add_property( '--wpkoi--back-to-top-text-color', esc_attr( $wpkoi_settings[ 'back_to_top_text_color' ] ) );
		$css->add_property( '--wpkoi--back-to-top-background-color-hover', esc_attr( $wpkoi_settings[ 'back_to_top_background_color_hover' ] ) );
		$css->add_property( '--wpkoi--back-to-top-text-color-hover', esc_attr( $wpkoi_settings[ 'back_to_top_text_color_hover' ] ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--form-text-color', esc_attr( $wpkoi_settings[ 'form_text_color' ] ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--form-background-color', esc_attr( $wpkoi_settings[ 'form_background_color' ] ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--form-border-color', esc_attr( $wpkoi_settings[ 'form_border_color' ] ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--form-background-color-focus', esc_attr( $wpkoi_settings[ 'form_background_color_focus' ] ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--form-text-color-focus', esc_attr( $wpkoi_settings[ 'form_text_color_focus' ] ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--form-border-color-focus', esc_attr( $wpkoi_settings[ 'form_border_color_focus' ] ) );
		$css->add_property( '--wpkoi--footer-text-color', esc_attr( $wpkoi_settings[ 'footer_text_color' ] ) );
		$css->add_property( '--wpkoi--footer-background-color', esc_attr( $wpkoi_settings[ 'footer_background_color' ] ) );
		$css->add_property( '--wpkoi--footer-link-color', esc_attr( $wpkoi_settings[ 'footer_link_color' ] ) );
		$css->add_property( '--wpkoi--footer-link-hover-color', esc_attr( $wpkoi_settings[ 'footer_link_hover_color' ] ) );
		$css->add_property( '--wpkoi--scrollbar-track-color', esc_attr( $wpkoi_settings[ 'scrollbar_track_color' ] ) );
		$css->add_property( '--wpkoi--scrollbar-thumb-color', esc_attr( $wpkoi_settings[ 'scrollbar_thumb_color' ] ) );
		$css->add_property( '--wpkoi--scrollbar-thumb-hover-color', esc_attr( $wpkoi_settings[ 'scrollbar_thumb_hover_color' ] ) );		
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--wc-sale-sticker-background', esc_attr( $wpkoi_settings[ 'wc_sale_sticker_background' ] ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--wc-sale-sticker-text', esc_attr( $wpkoi_settings[ 'wc_sale_sticker_text' ] ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--wc-price-color', esc_attr( $wpkoi_settings[ 'wc_price_color' ] ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--wc-product-tab', esc_attr( $wpkoi_settings[ 'wc_product_tab' ] ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--wc-product-tab-highlight', esc_attr( $wpkoi_settings[ 'wc_product_tab_highlight' ] ) );		
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--desktop-logo-width', floatval( $wpkoi_settings[ 'logo_width' ] ), $og_defaults[ 'logo_width' ], esc_attr( $desktop_logo_width_unit ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--mobile-logo-width', floatval( $wpkoi_settings[ 'mobile_logo_width' ] ), $og_defaults[ 'mobile_logo_width' ], esc_attr( $mobile_logo_width_unit ) );		
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--navigation-items-spacing', floatval( $wpkoi_settings[ 'navigation_items_spacing' ] ), $og_defaults[ 'navigation_items_spacing' ], esc_attr( $navigation_items_spacing_unit ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--navigation-items-width', floatval( $wpkoi_settings[ 'navigation_items_width' ] ), $og_defaults[ 'navigation_items_width' ], esc_attr( $navigation_items_width_unit ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--navigation-items-height', floatval( $wpkoi_settings[ 'navigation_items_height' ] ), $og_defaults[ 'navigation_items_height' ], esc_attr( $navigation_items_height_unit ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--subnavigation-width', floatval( $wpkoi_settings[ 'subnavigation_width' ] ), $og_defaults[ 'subnavigation_width' ], esc_attr( $subnavigation_width_unit ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--button-border-style', esc_attr( $wpkoi_settings[ 'button_border_style' ] ), $og_defaults[ 'button_border_style' ] );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--button-border', esc_attr( $wpkoi_settings[ 'button_border' ] ), $og_defaults[ 'button_border' ], 'px' );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--button-radius', esc_attr( $wpkoi_settings[ 'button_radius' ] ), $og_defaults[ 'button_radius' ], 'px' );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--button-rotate', 'rotate(' . esc_attr( $wpkoi_settings[ 'button_rotate' ] ), 'rotate(' . esc_attr( $og_defaults[ 'button_rotate' ] ), 'deg)' );
		$css->add_property( '--wpkoi--fixed-side-margin-top', absint( $wpkoi_settings[ 'fixed_side_margin_top' ] ), $og_defaults[ 'fixed_side_margin_top' ], 'px' );
		$css->add_property( '--wpkoi--fixed-side-margin-right', absint( $wpkoi_settings[ 'fixed_side_margin_right' ] ), $og_defaults[ 'fixed_side_margin_right' ], 'px' );
		$css->add_property( '--wpkoi--fixed-side-margin-bottom', absint( $wpkoi_settings[ 'fixed_side_margin_bottom' ] ), $og_defaults[ 'fixed_side_margin_bottom' ], 'px' );
		$css->add_property( '--wpkoi--fixed-side-margin-left', absint( $wpkoi_settings[ 'fixed_side_margin_left' ] ), $og_defaults[ 'fixed_side_margin_left' ], 'px' );
		$css->add_property( '--wpkoi--fixed-side-top', absint( $wpkoi_settings[ 'fixed_side_top' ] ), $og_defaults[ 'fixed_side_top' ], 'px' );
		$css->add_property( '--wpkoi--fixed-side-right', absint( $wpkoi_settings[ 'fixed_side_right' ] ), $og_defaults[ 'fixed_side_right' ], 'px' );
		$css->add_property( '--wpkoi--fixed-side-bottom', absint( $wpkoi_settings[ 'fixed_side_bottom' ] ), $og_defaults[ 'fixed_side_bottom' ], 'px' );
		$css->add_property( '--wpkoi--fixed-side-left', absint( $wpkoi_settings[ 'fixed_side_left' ] ), $og_defaults[ 'fixed_side_left' ], 'px' );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--button-top', absint( $wpkoi_settings[ 'button_top' ] ), $og_defaults[ 'button_top' ], 'px' );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--button-right', absint( $wpkoi_settings[ 'button_right' ] ), $og_defaults[ 'button_right' ], 'px' );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--button-bottom', absint( $wpkoi_settings[ 'button_bottom' ] ), $og_defaults[ 'button_bottom' ], 'px' );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--button-left', absint( $wpkoi_settings[ 'button_left' ] ), $og_defaults[ 'button_left' ], 'px' );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--container-width', absint( $wpkoi_settings[ 'container_width' ] ), $og_defaults[ 'container_width' ], 'px' );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--header-top', esc_attr( $wpkoi_settings[ 'header_top' ] ), $og_defaults[ 'header_top' ], 'vw' );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--header-right', esc_attr( $wpkoi_settings[ 'header_right' ] ), $og_defaults[ 'header_right' ], 'vw' );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--header-bottom', esc_attr( $wpkoi_settings[ 'header_bottom' ] ), $og_defaults[ 'header_bottom' ], 'vw' );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--header-left', esc_attr( $wpkoi_settings[ 'header_left' ] ), $og_defaults[ 'header_left' ], 'vw' );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--mobile-header-top', esc_attr( $wpkoi_settings[ 'mobile_header_top' ] ), $og_defaults[ 'mobile_header_top' ], 'vw' );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--mobile-header-right', esc_attr( $wpkoi_settings[ 'mobile_header_right' ] ), $og_defaults[ 'mobile_header_right' ], 'vw' );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--mobile-header-bottom', esc_attr( $wpkoi_settings[ 'mobile_header_bottom' ] ), $og_defaults[ 'mobile_header_bottom' ], 'vw' );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--mobile-header-left', esc_attr( $wpkoi_settings[ 'mobile_header_left' ] ), $og_defaults[ 'mobile_header_left' ], 'vw' );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--site-title-top', esc_attr( $wpkoi_settings[ 'site_title_top' ] ), $og_defaults[ 'site_title_top' ], 'vw' );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--site-title-right', esc_attr( $wpkoi_settings[ 'site_title_right' ] ), $og_defaults[ 'site_title_right' ], 'vw' );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--site-title-bottom', esc_attr( $wpkoi_settings[ 'site_title_bottom' ] ), $og_defaults[ 'site_title_bottom' ], 'vw' );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--site-title-left', esc_attr( $wpkoi_settings[ 'site_title_left' ] ), $og_defaults[ 'site_title_left' ], 'vw' );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--mobile-site-title-top', esc_attr( $wpkoi_settings[ 'mobile_site_title_top' ] ), $og_defaults[ 'mobile_site_title_top' ], 'vw' );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--mobile-site-title-right', esc_attr( $wpkoi_settings[ 'mobile_site_title_right' ] ), $og_defaults[ 'mobile_site_title_right' ], 'vw' );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--mobile-site-title-bottom', esc_attr( $wpkoi_settings[ 'mobile_site_title_bottom' ] ), $og_defaults[ 'mobile_site_title_bottom' ], 'vw' );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--mobile-site-title-left', esc_attr( $wpkoi_settings[ 'mobile_site_title_left' ] ), $og_defaults[ 'mobile_site_title_left' ], 'vw' );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--content-top', absint( $wpkoi_settings[ 'content_top' ] ), $og_defaults[ 'content_top' ], 'vw' );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--content-right', absint( $wpkoi_settings[ 'content_right' ] ), $og_defaults[ 'content_right' ], 'vw' );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--content-bottom', absint( $wpkoi_settings[ 'content_bottom' ] ), $og_defaults[ 'content_bottom' ], 'vw' );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--content-left', absint( $wpkoi_settings[ 'content_left' ] ), $og_defaults[ 'content_left' ], 'vw' );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--mobile-content-top', absint( $wpkoi_settings[ 'mobile_content_top' ] ), $og_defaults[ 'mobile_content_top' ], 'vw' );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--mobile-content-right', absint( $wpkoi_settings[ 'mobile_content_right' ] ), $og_defaults[ 'mobile_content_right' ], 'vw' );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--mobile-content-bottom', absint( $wpkoi_settings[ 'mobile_content_bottom' ] ), $og_defaults[ 'mobile_content_bottom' ], 'vw' );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--mobile-content-left', absint( $wpkoi_settings[ 'mobile_content_left' ] ), $og_defaults[ 'mobile_content_left' ], 'vw' );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--side-top', absint( $wpkoi_settings[ 'side_top' ] ), $og_defaults[ 'side_top' ], 'px' );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--side-right', absint( $wpkoi_settings[ 'side_right' ] ), $og_defaults[ 'side_right' ], 'px' );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--side-bottom', absint( $wpkoi_settings[ 'side_bottom' ] ), $og_defaults[ 'side_bottom' ], 'px' );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--side-left', absint( $wpkoi_settings[ 'side_left' ] ), $og_defaults[ 'side_left' ], 'px' );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--mobile-side-top', absint( $wpkoi_settings[ 'mobile_side_top' ] ), $og_defaults[ 'mobile_side_top' ], 'px' );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--mobile-side-right', absint( $wpkoi_settings[ 'mobile_side_right' ] ), $og_defaults[ 'mobile_side_right' ], 'px' );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--mobile-side-bottom', absint( $wpkoi_settings[ 'mobile_side_bottom' ] ), $og_defaults[ 'mobile_side_bottom' ], 'px' );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--mobile-side-left', absint( $wpkoi_settings[ 'mobile_side_left' ] ), $og_defaults[ 'mobile_side_left' ], 'px' );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--side-padding-radius', absint( $wpkoi_settings[ 'side_padding_radius' ] ), $og_defaults[ 'side_padding_radius' ], 'px' );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--body-font-weight', esc_attr( $wpkoi_settings[ 'body_font_weight' ], $og_defaults[ 'body_font_weight' ] ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--body-font-transform', esc_attr( $wpkoi_settings[ 'body_font_transform' ], $og_defaults[ 'body_font_transform' ] ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--body-font-size', floatval( $wpkoi_settings[ 'body_font_size' ] ), $og_defaults[ 'body_font_size' ], esc_attr( $desktop_body_font_size_unit ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--mobile-body-font-size', floatval( $wpkoi_settings[ 'mobile_body_font_size' ] ), $og_defaults[ 'mobile_body_font_size' ], esc_attr( $mobile_body_font_size_unit ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--body-line-height', floatval( $wpkoi_settings['body_line_height'] ), $og_defaults['body_line_height'] );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--site-title-font-weight', esc_attr( $wpkoi_settings[ 'site_title_font_weight' ] ), $og_defaults[ 'site_title_font_weight' ] );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--site-title-font-transform', esc_attr( $wpkoi_settings[ 'site_title_font_transform' ] ), $og_defaults[ 'site_title_font_transform' ] );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--site-title-font-size', floatval( $wpkoi_settings[ 'site_title_font_size' ] ), $og_defaults[ 'site_title_font_size' ], esc_attr( $desktop_site_title_font_size_unit ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--mobile-site-title-font-size', floatval( $wpkoi_settings[ 'mobile_site_title_font_size' ] ), $og_defaults[ 'mobile_site_title_font_size' ], esc_attr( $mobile_site_title_font_size_unit ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--navigation-font-weight', esc_attr( $wpkoi_settings[ 'navigation_font_weight' ] ), $og_defaults[ 'navigation_font_weight' ] );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--navigation-font-transform', esc_attr( $wpkoi_settings[ 'navigation_font_transform' ] ), $og_defaults[ 'navigation_font_transform' ] );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--navigation-font-size', floatval( $wpkoi_settings[ 'navigation_font_size' ] ), $og_defaults[ 'navigation_font_size' ], esc_attr( $desktop_navigation_font_size_unit ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--tablet-navigation-font-size', floatval( $wpkoi_settings[ 'tablet_navigation_font_size' ] ), $og_defaults[ 'tablet_navigation_font_size' ], esc_attr( $tablet_navigation_font_size_unit ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--mobile-navigation-font-size', floatval( $wpkoi_settings[ 'mobile_navigation_font_size' ] ), $og_defaults[ 'mobile_navigation_font_size' ], esc_attr( $mobile_navigation_font_size_unit ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--buttons-font-weight', esc_attr( $wpkoi_settings[ 'buttons_font_weight' ] ), $og_defaults[ 'buttons_font_weight' ] );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--buttons-font-transform', esc_attr( $wpkoi_settings[ 'buttons_font_transform' ] ), $og_defaults[ 'buttons_font_transform' ] );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--buttons-font-size', floatval( $wpkoi_settings[ 'buttons_font_size' ] ), $og_defaults[ 'buttons_font_size' ], esc_attr( $desktop_buttons_font_size_unit ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--mobile-buttons-font-size', floatval( $wpkoi_settings[ 'mobile_buttons_font_size' ] ), $og_defaults[ 'mobile_buttons_font_size' ], esc_attr( $mobile_buttons_font_size_unit ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--heading-1-weight', esc_attr( $wpkoi_settings[ 'heading_1_weight' ] ), $og_defaults[ 'heading_1_weight' ] );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--heading-1-transform', esc_attr( $wpkoi_settings[ 'heading_1_transform' ] ), $og_defaults[ 'heading_1_transform' ] );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--heading-1-font-size', floatval( $wpkoi_settings[ 'heading_1_font_size' ] ), $og_defaults[ 'heading_1_font_size' ], esc_attr( $desktop_heading_1_font_size_unit ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--mobile-heading-1-font-size', floatval( $wpkoi_settings[ 'mobile_heading_1_font_size' ] ), $og_defaults[ 'mobile_heading_1_font_size' ], esc_attr( $mobile_heading_1_font_size_unit ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--heading-1-line-height', floatval( $wpkoi_settings['heading_1_line_height'] ), $og_defaults['heading_1_line_height'], 'em' );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--heading-2-weight', esc_attr( $wpkoi_settings[ 'heading_2_weight' ] ), $og_defaults[ 'heading_2_weight' ] );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--heading-2-transform', esc_attr( $wpkoi_settings[ 'heading_2_transform' ] ), $og_defaults[ 'heading_2_transform' ] );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--heading-2-font-size', floatval( $wpkoi_settings[ 'heading_2_font_size' ] ), $og_defaults[ 'heading_2_font_size' ], esc_attr( $desktop_heading_2_font_size_unit ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--mobile-heading-2-font-size', floatval( $wpkoi_settings[ 'mobile_heading_2_font_size' ] ), $og_defaults[ 'mobile_heading_2_font_size' ], esc_attr( $mobile_heading_2_font_size_unit ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--heading-2-line-height', floatval( $wpkoi_settings['heading_2_line_height'] ), $og_defaults['heading_2_line_height'], 'em' );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--heading-3-weight', esc_attr( $wpkoi_settings[ 'heading_3_weight' ] ), $og_defaults[ 'heading_3_weight' ] );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--heading-3-transform', esc_attr( $wpkoi_settings[ 'heading_3_transform' ] ), $og_defaults[ 'heading_3_transform' ] );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--heading-3-font-size', floatval( $wpkoi_settings[ 'heading_3_font_size' ] ), $og_defaults[ 'heading_3_font_size' ], esc_attr( $desktop_heading_3_font_size_unit ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--mobile-heading-3-font-size', floatval( $wpkoi_settings[ 'mobile_heading_3_font_size' ] ), $og_defaults[ 'mobile_heading_3_font_size' ], esc_attr( $mobile_heading_3_font_size_unit ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--heading-3-line-height', floatval( $wpkoi_settings['heading_3_line_height'] ), $og_defaults['heading_3_line_height'], 'em' );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--heading-4-weight', esc_attr( $wpkoi_settings[ 'heading_4_weight' ] ), $og_defaults[ 'heading_4_weight' ] );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--heading-4-transform', esc_attr( $wpkoi_settings[ 'heading_4_transform' ] ), $og_defaults[ 'heading_4_transform' ] );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--heading-4-font-size', floatval( $wpkoi_settings[ 'heading_4_font_size' ] ), $og_defaults[ 'heading_4_font_size' ], esc_attr( $desktop_heading_4_font_size_unit ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--mobile-heading-4-font-size', floatval( $wpkoi_settings[ 'mobile_heading_4_font_size' ] ), $og_defaults[ 'mobile_heading_4_font_size' ], esc_attr( $mobile_heading_4_font_size_unit ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--heading-4-line-height', floatval( $wpkoi_settings['heading_4_line_height'] ), $og_defaults['heading_4_line_height'], 'em' );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--heading-5-weight', esc_attr( $wpkoi_settings[ 'heading_5_weight' ] ), $og_defaults[ 'heading_5_weight' ] );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--heading-5-transform', esc_attr( $wpkoi_settings[ 'heading_5_transform' ] ), $og_defaults[ 'heading_5_transform' ] );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--heading-5-font-size', floatval( $wpkoi_settings[ 'heading_5_font_size' ] ), $og_defaults[ 'heading_5_font_size' ], esc_attr( $desktop_heading_5_font_size_unit ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--mobile-heading-5-font-size', floatval( $wpkoi_settings[ 'mobile_heading_5_font_size' ] ), $og_defaults[ 'mobile_heading_5_font_size' ], esc_attr( $mobile_heading_5_font_size_unit ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--heading-5-line-height', floatval( $wpkoi_settings['heading_5_line_height'] ), $og_defaults['heading_5_line_height'], 'em' );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--heading-6-weight', esc_attr( $wpkoi_settings[ 'heading_6_weight' ] ), $og_defaults[ 'heading_6_weight' ] );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--heading-6-transform', esc_attr( $wpkoi_settings[ 'heading_6_transform' ] ), $og_defaults[ 'heading_6_transform' ] );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--heading-6-font-size', floatval( $wpkoi_settings[ 'heading_6_font_size' ] ), $og_defaults[ 'heading_6_font_size' ], esc_attr( $desktop_heading_6_font_size_unit ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--mobile-heading-6-font-size', floatval( $wpkoi_settings[ 'mobile_heading_6_font_size' ] ), $og_defaults[ 'mobile_heading_6_font_size' ], esc_attr( $mobile_heading_6_font_size_unit ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--heading-6-line-height', floatval( $wpkoi_settings['heading_6_line_height'] ), $og_defaults['heading_6_line_height'], 'em' );
		$css->add_property( '--wpkoi--footer-weight', esc_attr( $wpkoi_settings[ 'footer_weight' ] ), $og_defaults[ 'footer_weight' ] );
		$css->add_property( '--wpkoi--footer-transform', esc_attr( $wpkoi_settings[ 'footer_transform' ] ), $og_defaults[ 'footer_transform' ] );
		$css->add_property( '--wpkoi--footer-font-size', floatval( $wpkoi_settings['footer_font_size'] ), $og_defaults['footer_font_size'], esc_attr( $desktop_footer_font_size_unit ) );
		$css->add_property( '--wpkoi--mobile-footer-font-size', floatval( $wpkoi_settings[ 'mobile_footer_font_size' ] ), $og_defaults[ 'mobile_footer_font_size' ], esc_attr( $mobile_footer_font_size_unit ) );
		$css->add_property( '--wpkoi--fixed-side-font-weight', esc_attr( $wpkoi_settings[ 'fixed_side_font_weight' ] ), $og_defaults[ 'fixed_side_font_weight' ] );
		$css->add_property( '--wpkoi--fixed-side-font-transform', esc_attr( $wpkoi_settings[ 'fixed_side_font_transform' ] ), $og_defaults[ 'fixed_side_font_transform' ] );
		$css->add_property( '--wpkoi--fixed-side-font-size', floatval( $wpkoi_settings['fixed_side_font_size'] ), $og_defaults['fixed_side_font_size'], esc_attr( $desktop_fixed_side_font_size_unit ) );
		$css->add_property( '--wpkoi--mobile-fixed-side-font-size', floatval( $wpkoi_settings[ 'mobile_fixed_side_font_size' ] ), $og_defaults[ 'mobile_fixed_side_font_size' ], esc_attr( $mobile_fixed_side_font_size_unit ) );
		$css->add_property( '--wpkoi--def-cursor-image', 'url(' . esc_url( $wpkoi_settings['def_cursor_image'] ) . '), auto' );
		$css->add_property( '--wpkoi--pointer-cursor-image', 'url(' . esc_url( $wpkoi_settings['pointer_cursor_image'] ) . '), auto' );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--form-padding-top', esc_attr( $og_defaults['form_padding_top'] ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--form-padding-right', esc_attr( $og_defaults['form_padding_right'] ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--form-padding-bottom', esc_attr( $og_defaults['form_padding_bottom'] ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--form-padding-left', esc_attr( $og_defaults['form_padding_left'] ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--form-border-radius', esc_attr( $og_defaults['form_border_radius'] ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--form-border-width', esc_attr( $og_defaults['form_border_width'] ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--form-border-style', esc_attr( $og_defaults['form_border_style'] ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--form-checkbox-size', esc_attr( $og_defaults['form_checkbox_size'] ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--form-checkbox-innersize', esc_attr( $og_defaults['form_checkbox_innersize'] ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--form-checkbox-padding', esc_attr( $og_defaults['form_checkbox_padding'] ) );
		$css->add_property( '--' . WPKOI_PARENT_THEME_SLUG . '--form-checkbox-bordersize', esc_attr( $og_defaults['form_checkbox_bordersize'] ) );
		$css->add_property( '--wpkoi--nav-border-width', esc_attr( $og_defaults['nav_border_width'] ) );
		$css->add_property( '--wpkoi--scrollbar-width', esc_attr( $og_defaults['scrollbar_width'] ) );
		$css->add_property( '--wpkoi--scrollbar-radius', esc_attr( $og_defaults['scrollbar_radius'] ) );

		if ( 'enable' === $wpkoi_settings[ 'nav_search' ] ) {
			$css->set_selector( '.navigation-search' );
			$css->add_property( 'position', 'absolute' );
			$css->add_property( 'left', '-99999px' );
			$css->add_property( 'pointer-events', 'none' );
			$css->add_property( 'visibility', 'hidden' );
			$css->add_property( 'z-index', '20' );
			$css->add_property( 'width', '100%' );
			$css->add_property( 'top', '0' );
			$css->add_property( 'transition', 'opacity 100ms ease-in-out' );
			$css->add_property( 'opacity', '0' );

			$css->set_selector( '.navigation-search.nav-search-active' );
			$css->add_property( 'left', '0' );
			$css->add_property( 'right', '0' );
			$css->add_property( 'pointer-events', 'auto' );
			$css->add_property( 'visibility', 'visible' );
			$css->add_property( 'opacity', '1' );

			$css->set_selector( '.navigation-search input[type="search"]' );
			$css->add_property( 'outline', '0' );
			$css->add_property( 'border', '0' );
			$css->add_property( 'vertical-align', 'bottom' );
			$css->add_property( 'line-height', '1' );
			$css->add_property( 'opacity', '0.9' );
			$css->add_property( 'width', '100%' );
			$css->add_property( 'z-index', '20' );
			$css->add_property( 'border-radius', '0' );
			$css->add_property( '-webkit-appearance', 'none' );
			$css->add_property( 'height', '60px' );

			$css->set_selector( '.navigation-search input::-ms-clear' );
			$css->add_property( 'display', 'none' );
			$css->add_property( 'width', '0' );
			$css->add_property( 'height', '0' );

			$css->set_selector( '.navigation-search input::-ms-reveal' );
			$css->add_property( 'display', 'none' );
			$css->add_property( 'width', '0' );
			$css->add_property( 'height', '0' );

			$css->set_selector( '.navigation-search input::-webkit-search-decoration, .navigation-search input::-webkit-search-cancel-button, .navigation-search input::-webkit-search-results-button, .navigation-search input::-webkit-search-results-decoration' );
			$css->add_property( 'display', 'none' );

			$css->set_selector( '.main-navigation li.search-item' );
			$css->add_property( 'z-index', '21' );

			$css->set_selector( 'li.search-item.active' );
			$css->add_property( 'transition', 'opacity 100ms ease-in-out' );

			$css->set_selector( '.nav-left-sidebar .main-navigation li.search-item.active,.nav-right-sidebar .main-navigation li.search-item.active' );
			$css->add_property( 'width', 'auto' );
			$css->add_property( 'display', 'inline-block' );
			$css->add_property( 'float', 'right' );
			
			$css->set_selector( '.gen-sidebar-nav .navigation-search' );
			$css->add_property( 'top', 'auto' );
			$css->add_property( 'bottom', '0' );
		}
		
		if ( 'click' === $wpkoi_settings[ 'nav_dropdown_type' ] || 'click-arrow' === $wpkoi_settings[ 'nav_dropdown_type' ] ) {
			$css->set_selector( '.dropdown-click .main-navigation ul ul' );
			$css->add_property( 'display', 'none' );
			$css->add_property( 'visibility', 'hidden' );

			$css->set_selector( '.dropdown-click .main-navigation ul ul ul.toggled-on' );
			$css->add_property( 'left', '0' );
			$css->add_property( 'top', 'auto' );
			$css->add_property( 'position', 'relative' );
			$css->add_property( 'box-shadow', 'none' );
			$css->add_property( 'border-bottom', '1px solid rgba(0,0,0,0.05)' );

			$css->set_selector( '.dropdown-click .main-navigation ul ul li:last-child > ul.toggled-on' );
			$css->add_property( 'border-bottom', '0' );

			$css->set_selector( '.dropdown-click .main-navigation ul.toggled-on, .dropdown-click .main-navigation ul li.sfHover > ul.toggled-on' );
			$css->add_property( 'display', 'block' );
			$css->add_property( 'opacity', '1' );
			$css->add_property( 'visibility', 'visible' );
			$css->add_property( 'pointer-events', 'auto' );
			$css->add_property( 'height', 'auto' );
			$css->add_property( 'overflow', 'visible' );
			$css->add_property( 'float', 'none' );

			$css->set_selector( '.dropdown-click .main-navigation.sub-menu-left .sub-menu.toggled-on, .dropdown-click .main-navigation.sub-menu-left ul li.sfHover > ul.toggled-on' );
			$css->add_property( 'right', '0' );

			$css->set_selector( '.dropdown-click nav ul ul ul' );
			$css->add_property( 'background-color', 'transparent' );

			$css->set_selector( '.dropdown-click .widget-area .main-navigation ul ul' );
			$css->add_property( 'top', 'auto' );
			$css->add_property( 'position', 'absolute' );
			$css->add_property( 'float', 'none' );
			$css->add_property( 'width', '100%' );
			$css->add_property( 'left', '-99999px' );

			$css->set_selector( '.dropdown-click .widget-area .main-navigation ul ul.toggled-on' );
			$css->add_property( 'position', 'relative' );
			$css->add_property( 'left', '0' );
			$css->add_property( 'right', '0' );

			$css->set_selector( '.dropdown-click .widget-area.sidebar .main-navigation ul li.sfHover ul, .dropdown-click .widget-area.sidebar .main-navigation ul li:hover ul' );
			$css->add_property( 'right', '0' );
			$css->add_property( 'left', '0' );

			$css->set_selector( '.dropdown-click .sfHover > a > .dropdown-menu-toggle > .gp-icon svg' );
			$css->add_property( 'transform', 'rotate(180deg)' );

			if ( 'click' === $wpkoi_settings[ 'nav_dropdown_type' ] ) {
				$css->set_selector( '.menu-item-has-children  .dropdown-menu-toggle[role="presentation"]' );
				$css->add_property( 'pointer-events', 'none' );
			}
		}
		
		if ( ( $wpkoi_settings[ 'side_top' ] != 0 ) || ( $wpkoi_settings[ 'side_right' ] != 0 ) || ( $wpkoi_settings[ 'side_bottom' ] != 0 ) || ( $wpkoi_settings[ 'side_left' ] != 0 ) ) {
		if ( get_background_image() ) {
			$css->set_selector( 'body.custom-background.wpkoi-bodypadding' );
			$css->add_property( 'background-image', 'none' );
			$css->set_selector( '.' . WPKOI_PARENT_THEME_SLUG . '-body-padding-content' );
			$css->add_property( 'background-image', 'url("' . esc_url( get_background_image() ) . '")' );
			$css->add_property( 'background-repeat', esc_attr( get_theme_mod( 'background_repeat', get_theme_support( 'custom-background', 'default-repeat' ) ) ) );
			$css->add_property( 'background-position', esc_attr( get_theme_mod( 'background_position_y', get_theme_support( 'custom-background', 'default-position-y' ) ) ) . ' ' . esc_attr( get_theme_mod( 'background_position_x', get_theme_support( 'custom-background', 'default-position-x' ) ) ) );
			$css->add_property( 'background-size', esc_attr( get_theme_mod( 'background_size', get_theme_support( 'custom-background', 'default-size' ) ) ) );
			$css->add_property( 'background-attachment', esc_attr( get_theme_mod( 'background_attachment', get_theme_support( 'custom-background', 'default-attachment' ) ) ) );
		}
		}

		// Allow us to hook CSS into our output
		do_action( 'wpkoi_base_css', $css );

		return apply_filters( 'wpkoi_base_css_output', $css->css_output() );
	}
}

// Enqueue our dynamic CSS.
if ( ! function_exists( 'wpkoi_enqueue_dynamic_css' ) ) {
	function wpkoi_enqueue_dynamic_css() {
		$css = wpkoi_font_family_css() . wpkoi_base_css();
	
		wp_add_inline_style( WPKOI_PARENT_THEME_SLUG . '-style', $css );
	}
}
add_action( 'wp_enqueue_scripts', 'wpkoi_enqueue_dynamic_css', 50 );

// Enqueue editor styles.
if ( ! function_exists( 'wpkoi_enqueue_dynamic_editor_styles' ) ) {
	function wpkoi_enqueue_dynamic_editor_styles() {
		// Add styles inline.
		$css = wpkoi_font_family_css() . wpkoi_base_css();
		wp_add_inline_style( 'wp-block-library', $css );
	}
}
add_action( 'admin_init', 'wpkoi_enqueue_dynamic_editor_styles', 10 );