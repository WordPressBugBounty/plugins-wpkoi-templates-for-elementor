<?php
/**
 * Builds our Customizer controls.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_action( 'customize_register', 'wpkoi_set_customizer_helpers', 1 );
function wpkoi_set_customizer_helpers( $wp_customize ) {
	// Load helpers
	include( WPKOI_TEMPLATES_FOR_ELEMENTOR_DIRECTORY . 'theme/inc/customizer-helpers.php' );
}

if ( ! function_exists( 'wpkoi_customize_register' ) ) {
	add_action( 'customize_register', 'wpkoi_customize_register' );
	function wpkoi_customize_register( $wp_customize ) {
		// Get our default values
		$defaults = wpkoi_get_defaults();

		// Get our palettes
		$palettes = wpkoi_get_default_color_palettes();
		
		$textdomain = wp_get_theme()->get( 'TextDomain' );
		if ($textdomain == '') {
			$textdomain = strtolower( str_replace( ' ', '-', wp_get_theme()->get( 'Name' ) ) );
		}

		if ( $wp_customize->get_control( 'blogdescription' ) ) {
			$wp_customize->get_control('blogdescription')->priority = 3;
			$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
		}

		if ( $wp_customize->get_control( 'blogname' ) ) {
			$wp_customize->get_control('blogname')->priority = 1;
			$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
		}

		if ( method_exists( $wp_customize, 'register_control_type' ) ) {
			$wp_customize->register_control_type( 'WPKoi_Customize_Misc_Control' );
			$wp_customize->register_control_type( 'WPKoi_Customize_Help_Control' );
			$wp_customize->register_control_type( 'WPKoi_Template_Parts_Section' );
			$wp_customize->register_section_type( 'WPKoi_Upsell_Section' );
			$wp_customize->register_control_type( 'WPKoi_Alpha_Color_Customize_Control' );
			$wp_customize->register_control_type( 'WPKoi_Title_Customize_Control' );
			$wp_customize->register_control_type( 'WPKoi_Pro_Range_Slider_Control' );
		}

		// Add selective refresh to site title and description
		if ( isset( $wp_customize->selective_refresh ) ) {
			$wp_customize->selective_refresh->add_partial( 'blogname', array(
				'selector' => '.site-header .wp-block-site-title a',
				'render_callback' => 'wpkoi_customize_partial_blogname',
			) );

			$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
				'selector' => '.site-description',
				'render_callback' => 'wpkoi_customize_partial_blogdescription',
			) );
		}
		
		// Functions for Site Identity section
		$wp_customize->add_setting(
			'wpkoi_settings[hide_title]',
			array(
				'default' => $defaults['hide_title'],
				'type' => 'option',
				'sanitize_callback' => 'wpkoi_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			'wpkoi_settings[hide_title]',
			array(
				'type' => 'checkbox',
				'label' => __( 'Hide site title', 'wpkoi-templates-for-elementor' ),
				'section' => 'title_tagline',
				'priority' => 11,
			)
		);

		$wp_customize->add_setting(
			'wpkoi_settings[hide_tagline]',
			array(
				'default' => $defaults['hide_tagline'],
				'type' => 'option',
				'sanitize_callback' => 'wpkoi_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			'wpkoi_settings[hide_tagline]',
			array(
				'type' => 'checkbox',
				'label' => __( 'Hide site tagline', 'wpkoi-templates-for-elementor' ),
				'section' => 'title_tagline',
				'priority' => 12,
			)
		);

		$wp_customize->add_setting(
			'wpkoi_settings[custom_logo]',
			array(
				'default' => $defaults['custom_logo'],
				'type' => 'option',
				'sanitize_callback' => 'esc_url_raw',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'wpkoi_settings[custom_logo]',
				array(
					'label' => __( 'Header Logo', 'wpkoi-templates-for-elementor' ),
					'section' => 'title_tagline',
					'settings' => 'wpkoi_settings[custom_logo]',
					'priority' => 21,
				)
			)
		);

		$wp_customize->add_setting(
			'wpkoi_settings[retina_logo]',
			array(
				'default' => $defaults['retina_logo'],
				'type' => 'option',
				'sanitize_callback' => 'esc_url_raw',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'wpkoi_settings[retina_logo]',
				array(
					'label' => __( 'Retina Logo', 'wpkoi-templates-for-elementor' ),
					'section' => 'title_tagline',
					'settings' => 'wpkoi_settings[retina_logo]',
					'priority' => 22,
				)
			)
		);

		$wp_customize->add_setting(
			'wpkoi_settings[logo_width]',
			array(
				'default' => $defaults['logo_width'],
				'type' => 'option',
				'sanitize_callback' => 'wpkoi_sanitize_empty_absint',
			)
		);

		$wp_customize->add_setting(
			'wpkoi_settings[mobile_logo_width]',
			array(
				'default' => $defaults['mobile_logo_width'],
				'type' => 'option',
				'sanitize_callback' => 'wpkoi_sanitize_decimal_integer'
			)
		);
		
		$wp_customize->add_setting( 
			'wpkoi_settings[desktop_logo_width_unit]', 
			array(
				'default' => $defaults['desktop_logo_width_unit'],
				'type' => 'option',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_setting( 
			'wpkoi_settings[mobile_logo_width_unit]', 
			array(
				'default' => $defaults['mobile_logo_width_unit'],
				'type' => 'option',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new WPKoi_Pro_Range_Slider_Control(
				$wp_customize,
				'wpkoi_settings[logo_width]',
				array(
					'label' => __( 'Logo Width', 'wpkoi-templates-for-elementor' ),
					'section' => 'title_tagline',
					'settings' => array(
						'desktop' => 'wpkoi_settings[logo_width]',
						'mobile' => 'wpkoi_settings[mobile_logo_width]',
						'desktop_unit' => 'wpkoi_settings[desktop_logo_width_unit]',
    					'mobile_unit'  => 'wpkoi_settings[mobile_logo_width_unit]',
					),
					'choices' => array(
						'desktop' => array(
							'min' => 1,
							'max' => 500,
							'step' => 1,
							'edit' => true,
							'unit' => 'px',
							'units' => array( 'px', 'em', 'rem', 'vw', 'vh', '%' ),
						),
						'mobile' => array(
							'min' => 1,
							'max' => 500,
							'step' => 1,
							'edit' => true,
							'unit' => 'px',
							'units' => array( 'px', 'em', 'rem', 'vw', 'vh', '%' )
						),
					),
					'priority' => 24,
				)
			)
		);

		// Functions for Header section
		$wp_customize->add_section(
			'wpkoi_header_section',
			array(
				'title' => __( 'Header', 'wpkoi-templates-for-elementor' ),
				'priority' => 25
			)
		);
		
		$wp_customize->add_setting(
			'wpkoi_settings[top_bar_mobile]',
			array(
				'default' => $defaults['top_bar_mobile'],
				'type' => 'option',
				'sanitize_callback' => 'wpkoi_sanitize_choices'
			)
		);

		$wp_customize->add_control(
			'wpkoi_settings[top_bar_mobile]',
			array(
				'type' => 'select',
				'label' => __( 'Top Bar', 'wpkoi-templates-for-elementor' ),
				'section' => 'wpkoi_header_section',
				'choices' => array(
					'show' => __( 'Show', 'wpkoi-templates-for-elementor' ),
					'desktop' => __( 'Only on desktop', 'wpkoi-templates-for-elementor' ),
					'hide' => __( 'Hide', 'wpkoi-templates-for-elementor' )
				),
				'settings' => 'wpkoi_settings[top_bar_mobile]',
				'description'=> sprintf( 
					'%1$s<a href="%2$s" target="_blank">%3$s</a>%4$s',
					esc_html__( 'You can edit the top bar template part ', 'wpkoi-templates-for-elementor' ),
					esc_url( admin_url( 'site-editor.php?postType=wp_template_part&postId=' . esc_attr( $textdomain ) .'%2F%2Ftopbar&canvas=edit' ) ),
					esc_html__( 'here,', 'wpkoi-templates-for-elementor' ),
					esc_html__( ' at the block editor.', 'wpkoi-templates-for-elementor' )
				),
				'priority' => 2
			)
		);

		$wp_customize->add_setting(
			'wpkoi_settings[header_layout_setting]',
			array(
				'default' => $defaults['header_layout_setting'],
				'type' => 'option',
				'sanitize_callback' => 'wpkoi_sanitize_choices',
			)
		);

		$wp_customize->add_control(
			'wpkoi_settings[header_layout_setting]',
			array(
				'type' => 'select',
				'label' => __( 'Header Width', 'wpkoi-templates-for-elementor' ),
				'section' => 'wpkoi_header_section',
				'choices' => array(
					'fluid-header' => __( 'Full', 'wpkoi-templates-for-elementor' ),
					'contained-header' => __( 'Contained', 'wpkoi-templates-for-elementor' ),
				),
				'settings' => 'wpkoi_settings[header_layout_setting]',
				'priority' => 5,
			)
		);

		$wp_customize->add_setting(
			'wpkoi_settings[header_inner_width]',
			array(
				'default' => $defaults['header_inner_width'],
				'type' => 'option',
				'sanitize_callback' => 'wpkoi_sanitize_choices',
			)
		);

		$wp_customize->add_control(
			'wpkoi_settings[header_inner_width]',
			array(
				'type' => 'select',
				'label' => __( 'Inner Header Width', 'wpkoi-templates-for-elementor' ),
				'section' => 'wpkoi_header_section',
				'choices' => array(
					'contained' => __( 'Contained', 'wpkoi-templates-for-elementor' ),
					'full-width' => __( 'Full', 'wpkoi-templates-for-elementor' ),
				),
				'settings' => 'wpkoi_settings[header_inner_width]',
				'priority' => 6,
			)
		);

		$wp_customize->add_setting(
			'wpkoi_settings[header_alignment_setting]',
			array(
				'default' => $defaults['header_alignment_setting'],
				'type' => 'option',
				'sanitize_callback' => 'wpkoi_sanitize_choices',
			)
		);

		$wp_customize->add_control(
			'wpkoi_settings[header_alignment_setting]',
			array(
				'type' => 'select',
				'label' => __( 'Header Alignment', 'wpkoi-templates-for-elementor' ),
				'section' => 'wpkoi_header_section',
				'choices' => array(
					'left' => __( 'Left', 'wpkoi-templates-for-elementor' ),
					'center' => __( 'Center', 'wpkoi-templates-for-elementor' ),
					'right' => __( 'Right', 'wpkoi-templates-for-elementor' ),
				),
				'description'=> __( 'Only if the navigation is above or below the header', 'wpkoi-templates-for-elementor' ),
				'settings' => 'wpkoi_settings[header_alignment_setting]',
				'priority' => 10,
			)
		);

		$wp_customize->add_control(
			new WPKoi_Title_Customize_Control(
				$wp_customize,
				'wpkoi_primary_navigation_title',
				array(
					'section'     => 'wpkoi_header_section',
					'type'        => 'wpkoi-customizer-title',
					'title'			=> __( 'Primary Navigation', 'wpkoi-templates-for-elementor' ),
					'settings' => ( isset( $wp_customize->selective_refresh ) ) ? array() : 'blogname',
					'priority'	=> 20,
				)
			)
		);

		$wp_customize->add_setting(
			'wpkoi_settings[nav_inner_width]',
			array(
				'default' => $defaults['nav_inner_width'],
				'type' => 'option',
				'sanitize_callback' => 'wpkoi_sanitize_choices',
			)
		);

		$wp_customize->add_control(
			'wpkoi_settings[nav_inner_width]',
			array(
				'type' => 'select',
				'label' => __( 'Navigation Width', 'wpkoi-templates-for-elementor' ),
				'section' => 'wpkoi_header_section',
				'choices' => array(
					'contained' => __( 'Contained', 'wpkoi-templates-for-elementor' ),
					'full-width' => __( 'Full', 'wpkoi-templates-for-elementor' ),
				),
				'settings' => 'wpkoi_settings[nav_inner_width]',
				'priority' => 22,
			)
		);

		$wp_customize->add_setting(
			'wpkoi_settings[nav_alignment_setting]',
			array(
				'default' => $defaults['nav_alignment_setting'],
				'type' => 'option',
				'sanitize_callback' => 'wpkoi_sanitize_choices',
			)
		);

		$wp_customize->add_control(
			'wpkoi_settings[nav_alignment_setting]',
			array(
				'type' => 'select',
				'label' => __( 'Navigation Alignment', 'wpkoi-templates-for-elementor' ),
				'section' => 'wpkoi_header_section',
				'choices' => array(
					'left' => __( 'Left', 'wpkoi-templates-for-elementor' ),
					'center' => __( 'Center', 'wpkoi-templates-for-elementor' ),
					'right' => __( 'Right', 'wpkoi-templates-for-elementor' ),
				),
				'description'=> __( 'Only if the navigation is above or below the header', 'wpkoi-templates-for-elementor' ),
				'settings' => 'wpkoi_settings[nav_alignment_setting]',
				'priority' => 23,
			)
		);

		$wp_customize->add_setting(
			'wpkoi_settings[nav_position_setting]',
			array(
				'default' => $defaults['nav_position_setting'],
				'type' => 'option',
				'sanitize_callback' => 'wpkoi_sanitize_choices',
				'transport' => 'refresh',
			)
		);

		$wp_customize->add_control(
			'wpkoi_settings[nav_position_setting]',
			array(
				'type' => 'select',
				'label' => __( 'Navigation Location', 'wpkoi-templates-for-elementor' ),
				'section' => 'wpkoi_header_section',
				'choices' => array(
					'nav-below-header' => __( 'Below Header', 'wpkoi-templates-for-elementor' ),
					'nav-above-header' => __( 'Above Header', 'wpkoi-templates-for-elementor' ),
					'nav-float-right' => __( 'Float Right', 'wpkoi-templates-for-elementor' ),
					'nav-float-left' => __( 'Float Left', 'wpkoi-templates-for-elementor' ),
					'' => __( 'No Navigation', 'wpkoi-templates-for-elementor' ),
				),
				'settings' => 'wpkoi_settings[nav_position_setting]',
				'priority' => 25,
			)
		);

		$wp_customize->add_setting(
			'wpkoi_settings[nav_border]',
			array(
				'default' => $defaults['nav_border'],
				'type' => 'option',
				'sanitize_callback' => 'wpkoi_sanitize_choices'
			)
		);

		$wp_customize->add_control(
			'wpkoi_settings[nav_border]',
			array(
				'type' => 'select',
				'label' => __( 'Navigation Border', 'wpkoi-templates-for-elementor' ),
				'section' => 'wpkoi_header_section',
				'choices' => array(
					'enable' => __( 'Enable', 'wpkoi-templates-for-elementor' ),
					'' => __( 'Disable', 'wpkoi-templates-for-elementor' )
				),
				'settings' => 'wpkoi_settings[nav_border]',
				'priority' => 26
			)
		);

		$wp_customize->add_setting(
			'wpkoi_settings[nav_effect]',
			array(
				'default' => $defaults['nav_effect'],
				'type' => 'option',
				'sanitize_callback' => 'wpkoi_sanitize_choices'
			)
		);

		$wp_customize->add_control(
			'wpkoi_settings[nav_effect]',
			array(
				'type' => 'select',
				'label' => __( 'Navigation Border', 'wpkoi-templates-for-elementor' ),
				'section' => 'wpkoi_header_section',
				'choices' => apply_filters( 'wpkoi_nav_effect_choices', array(
					'none'    => __( 'None', 'wpkoi-templates-for-elementor' ),
					'stylea'  => __( 'Scale', 'wpkoi-templates-for-elementor' ),
					'styleb'  => __( 'Rotate', 'wpkoi-templates-for-elementor' ),
					'stylec'  => __( 'Pulse', 'wpkoi-templates-for-elementor' ),
				) ),
				'settings' => 'wpkoi_settings[nav_effect]',
				'priority' => 26
			)
		);
		
		$wp_customize->add_setting(
			'wpkoi_settings[navigation_items_spacing]',
			array(
				'default' => $defaults['navigation_items_spacing'],
				'type' => 'option',
				'sanitize_callback' => 'wpkoi_sanitize_decimal_integer'
			)
		);
		
		$wp_customize->add_setting( 
			'wpkoi_settings[desktop_navigation_items_spacing_unit]', 
			array(
				'default' => $defaults['desktop_navigation_items_spacing_unit'],
				'type' => 'option',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new WPKoi_Pro_Range_Slider_Control(
				$wp_customize,
				'wpkoi_settings[navigation_items_spacing]',
				array(
					'label' => __( 'Navigation Item Spacing', 'wpkoi-templates-for-elementor' ),
					'section' => 'wpkoi_header_section',
					'priority' => 28,
					'settings' => array(
						'desktop' => 'wpkoi_settings[navigation_items_spacing]',
						'desktop_unit' => 'wpkoi_settings[desktop_navigation_items_spacing_unit]',
					),
					'choices' => array(
						'desktop' => array(
							'min' => 1,
							'max' => 50,
							'step' => 1,
							'edit' => true,
							'unit' => 'px',
							'units' => array( 'px', 'em', 'rem', 'vw', 'vh', '%' )
						)
					),
				)
			)
		);
		
		$wp_customize->add_setting(
			'wpkoi_settings[navigation_items_width]',
			array(
				'default' => $defaults['navigation_items_width'],
				'type' => 'option',
				'sanitize_callback' => 'wpkoi_sanitize_decimal_integer'
			)
		);
		
		$wp_customize->add_setting( 
			'wpkoi_settings[desktop_navigation_items_width_unit]', 
			array(
				'default' => $defaults['desktop_navigation_items_width_unit'],
				'type' => 'option',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new WPKoi_Pro_Range_Slider_Control(
				$wp_customize,
				'wpkoi_settings[navigation_items_width]',
				array(
					'label' => __( 'Navigation Item Width', 'wpkoi-templates-for-elementor' ),
					'section' => 'wpkoi_header_section',
					'priority' => 29,
					'settings' => array(
						'desktop' => 'wpkoi_settings[navigation_items_width]',
						'desktop_unit' => 'wpkoi_settings[desktop_navigation_items_width_unit]',
					),
					'choices' => array(
						'desktop' => array(
							'min' => 1,
							'max' => 50,
							'step' => 1,
							'edit' => true,
							'unit' => 'px',
							'units' => array( 'px', 'em', 'rem', 'vw', 'vh', '%' )
						)
					),
				)
			)
		);
		
		$wp_customize->add_setting(
			'wpkoi_settings[navigation_items_height]',
			array(
				'default' => $defaults['navigation_items_height'],
				'type' => 'option',
				'sanitize_callback' => 'wpkoi_sanitize_decimal_integer'
			)
		);
		
		$wp_customize->add_setting( 
			'wpkoi_settings[desktop_navigation_items_height_unit]', 
			array(
				'default' => $defaults['desktop_navigation_items_height_unit'],
				'type' => 'option',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new WPKoi_Pro_Range_Slider_Control(
				$wp_customize,
				'wpkoi_settings[navigation_items_height]',
				array(
					'label' => __( 'Navigation Item Height', 'wpkoi-templates-for-elementor' ),
					'section' => 'wpkoi_header_section',
					'priority' => 29,
					'settings' => array(
						'desktop' => 'wpkoi_settings[navigation_items_height]',
						'desktop_unit' => 'wpkoi_settings[desktop_navigation_items_height_unit]',
					),
					'choices' => array(
						'desktop' => array(
							'min' => 1,
							'max' => 50,
							'step' => 1,
							'edit' => true,
							'unit' => 'px',
							'units' => array( 'px', 'em', 'rem', 'vw', 'vh', '%' )
						)
					),
				)
			)
		);
		
		$wp_customize->add_setting(
			'wpkoi_settings[subnavigation_width]',
			array(
				'default' => $defaults['subnavigation_width'],
				'type' => 'option',
				'sanitize_callback' => 'wpkoi_sanitize_decimal_integer'
			)
		);
		
		$wp_customize->add_setting( 
			'wpkoi_settings[desktop_subnavigation_width_unit]', 
			array(
				'default' => $defaults['desktop_subnavigation_width_unit'],
				'type' => 'option',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new WPKoi_Pro_Range_Slider_Control(
				$wp_customize,
				'wpkoi_settings[subnavigation_width]',
				array(
					'label' => __( 'Subnavigation width', 'wpkoi-templates-for-elementor' ),
					'section' => 'wpkoi_header_section',
					'priority' => 30,
					'settings' => array(
						'desktop' => 'wpkoi_settings[subnavigation_width]',
						'desktop_unit' => 'wpkoi_settings[desktop_subnavigation_width_unit]',
					),
					'choices' => array(
						'desktop' => array(
							'min' => 1,
							'max' => 300,
							'step' => 1,
							'edit' => true,
							'unit' => 'px',
							'units' => array( 'px', 'em', 'rem', 'vw', 'vh', '%' )
						)
					),
				)
			)
		);

		$wp_customize->add_setting(
			'wpkoi_settings[nav_dropdown_type]',
			array(
				'default' => $defaults['nav_dropdown_type'],
				'type' => 'option',
				'sanitize_callback' => 'wpkoi_sanitize_choices',
			)
		);

		$wp_customize->add_control(
			'wpkoi_settings[nav_dropdown_type]',
			array(
				'type' => 'select',
				'label' => __( 'Navigation Dropdown', 'wpkoi-templates-for-elementor' ),
				'section' => 'wpkoi_header_section',
				'choices' => array(
					'hover' => __( 'Hover', 'wpkoi-templates-for-elementor' ),
					'click' => __( 'Click - Menu Item', 'wpkoi-templates-for-elementor' ),
					'click-arrow' => __( 'Click - Arrow', 'wpkoi-templates-for-elementor' ),
				),
				'settings' => 'wpkoi_settings[nav_dropdown_type]',
				'priority' => 38,
			)
		);

		$wp_customize->add_setting(
			'wpkoi_settings[nav_dropdown_direction]',
			array(
				'default' => $defaults['nav_dropdown_direction'],
				'type' => 'option',
				'sanitize_callback' => 'wpkoi_sanitize_choices',
			)
		);

		$wp_customize->add_control(
			'wpkoi_settings[nav_dropdown_direction]',
			array(
				'type' => 'select',
				'label' => __( 'Dropdown Direction', 'wpkoi-templates-for-elementor' ),
				'section' => 'wpkoi_header_section',
				'choices' => array(
					'right' => __( 'Right', 'wpkoi-templates-for-elementor' ),
					'left' => __( 'Left', 'wpkoi-templates-for-elementor' ),
				),
				'settings' => 'wpkoi_settings[nav_dropdown_direction]',
				'priority' => 39,
			)
		);

		$wp_customize->add_setting(
			'wpkoi_settings[nav_search]',
			array(
				'default' => $defaults['nav_search'],
				'type' => 'option',
				'sanitize_callback' => 'wpkoi_sanitize_choices',
			)
		);

		$wp_customize->add_control(
			'wpkoi_settings[nav_search]',
			array(
				'type' => 'select',
				'label' => __( 'Navigation Search', 'wpkoi-templates-for-elementor' ),
				'section' => 'wpkoi_header_section',
				'choices' => array(
					'enable' => __( 'Enable', 'wpkoi-templates-for-elementor' ),
					'disable' => __( 'Disable', 'wpkoi-templates-for-elementor' ),
				),
				'settings' => 'wpkoi_settings[nav_search]',
				'priority' => 42,
			)
		);

		// Functions for Typography section
		if ( class_exists( 'WP_Customize_Panel' ) ) {
			$wp_customize->add_panel( 'wpkoi_typography_panel', array(
				'priority'       => 30,
				'capability'     => 'edit_theme_options',
				'theme_supports' => '',
				'title'          => __( 'Typography', 'wpkoi-templates-for-elementor' ),
				'description'    => '',
			) );
		}

		$wp_customize->add_section(
			'font_section',
			array(
				'title' => __( 'Body', 'wpkoi-templates-for-elementor' ),
				'capability' => 'edit_theme_options',
				'description' => '',
				'priority' => 30,
				'panel' => 'wpkoi_typography_panel'
			)
		);

		$wp_customize->add_setting(
			'wpkoi_settings[body_font_size]',
			array(
				'default' => $defaults['body_font_size'],
				'type' => 'option',
				'sanitize_callback' => 'wpkoi_sanitize_decimal_integer'
			)
		);

		$wp_customize->add_setting(
			'wpkoi_settings[mobile_body_font_size]',
			array(
				'default' => $defaults['mobile_body_font_size'],
				'type' => 'option',
				'sanitize_callback' => 'wpkoi_sanitize_decimal_integer'
			)
		);
		
		$wp_customize->add_setting( 
			'wpkoi_settings[desktop_body_font_size_unit]', 
			array(
				'default' => $defaults['desktop_body_font_size_unit'],
				'type' => 'option',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_setting( 
			'wpkoi_settings[mobile_body_font_size_unit]', 
			array(
				'default' => $defaults['mobile_body_font_size_unit'],
				'type' => 'option',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new WPKoi_Pro_Range_Slider_Control(
				$wp_customize,
				'wpkoi_settings[body_font_size]',
				array(
					'label' => __( 'Font size', 'wpkoi-templates-for-elementor' ),
					'section' => 'font_section',
					'priority' => 40,
					'settings' => array(
						'desktop' => 'wpkoi_settings[body_font_size]',
						'mobile' => 'wpkoi_settings[mobile_body_font_size]',
						'desktop_unit' => 'wpkoi_settings[desktop_body_font_size_unit]',
    					'mobile_unit'  => 'wpkoi_settings[mobile_body_font_size_unit]',
					),
					'choices' => array(
						'desktop' => array(
							'min' => 1,
							'max' => 50,
							'step' => 1,
							'edit' => true,
							'unit' => 'px',
							'units' => array( 'px', 'em', 'rem', 'vw', 'vh', '%' ),
						),
						'mobile' => array(
							'min' => 1,
							'max' => 50,
							'step' => 1,
							'edit' => true,
							'unit' => 'px',
							'units' => array( 'px', 'em', 'rem', 'vw', 'vh', '%' )
						),
					),
				)
			)
		);

		$wp_customize->add_setting(
			'wpkoi_settings[body_line_height]',
			array(
				'default' => $defaults['body_line_height'],
				'type' => 'option',
				'sanitize_callback' => 'wpkoi_sanitize_decimal_integer'
			)
		);

		$wp_customize->add_control(
			new WPKoi_Pro_Range_Slider_Control(
				$wp_customize,
				'wpkoi_settings[body_line_height]',
				array(
					'label' => __( 'Line height', 'wpkoi-templates-for-elementor' ),
					'section' => 'font_section',
					'priority' => 45,
					'settings' => array(
						'desktop' => 'wpkoi_settings[body_line_height]',
					),
					'choices' => array(
						'desktop' => array(
							'min' => 1,
							'max' => 5,
							'step' => .1,
							'edit' => true,
							'unit' => '',
						),
					),
				)
			)
		);

		$wp_customize->add_section(
			'font_header_section',
			array(
				'title' => __( 'Header', 'wpkoi-templates-for-elementor' ),
				'capability' => 'edit_theme_options',
				'description' => '',
				'priority' => 40,
				'panel' => 'wpkoi_typography_panel'
			)
		);

		$wp_customize->add_setting(
			'wpkoi_settings[site_title_font_size]',
			array(
				'default' => $defaults['site_title_font_size'],
				'type' => 'option',
				'sanitize_callback' => 'wpkoi_sanitize_decimal_integer'
			)
		);

		$wp_customize->add_setting(
			'wpkoi_settings[mobile_site_title_font_size]',
			array(
				'default' => $defaults['mobile_site_title_font_size'],
				'type' => 'option',
				'sanitize_callback' => 'wpkoi_sanitize_decimal_integer'
			)
		);
		
		$wp_customize->add_setting( 
			'wpkoi_settings[desktop_site_title_font_size_unit]', 
			array(
				'default' =>$defaults['desktop_site_title_font_size_unit'],
				'type' => 'option',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_setting( 
			'wpkoi_settings[mobile_site_title_font_size_unit]', 
			array(
				'default' => $defaults['mobile_site_title_font_size_unit'],
				'type' => 'option',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new WPKoi_Pro_Range_Slider_Control(
				$wp_customize,
				'wpkoi_settings[site_title_font_size]',
				array(
					'label' => __( 'Site Title Font Size', 'wpkoi-templates-for-elementor' ),
					'section' => 'font_header_section',
					'priority' => 75,
					'settings' => array(
						'desktop' => 'wpkoi_settings[site_title_font_size]',
						'mobile' => 'wpkoi_settings[mobile_site_title_font_size]',
						'desktop_unit' => 'wpkoi_settings[desktop_site_title_font_size_unit]',
    					'mobile_unit'  => 'wpkoi_settings[mobile_site_title_font_size_unit]',
					),
					'choices' => array(
						'desktop' => array(
							'min' => 1,
							'max' => 100,
							'step' => 1,
							'edit' => true,
							'unit' => 'px',
							'units' => array( 'px', 'em', 'rem', 'vw', 'vh', '%' )
						),
						'mobile' => array(
							'min' => 1,
							'max' => 100,
							'step' => 1,
							'edit' => true,
							'unit' => 'px',
							'units' => array( 'px', 'em', 'rem', 'vw', 'vh', '%' )
						),
					),
				)
			)
		);

		$wp_customize->add_setting(
			'wpkoi_settings[navigation_font_size]',
			array(
				'default' => $defaults['navigation_font_size'],
				'type' => 'option',
				'sanitize_callback' => 'wpkoi_sanitize_decimal_integer'
			)
		);

		$wp_customize->add_setting(
			'wpkoi_settings[tablet_navigation_font_size]',
			array(
				'default' => $defaults['tablet_navigation_font_size'],
				'type' => 'option',
				'sanitize_callback' => 'wpkoi_sanitize_decimal_integer'
			)
		);

		$wp_customize->add_setting(
			'wpkoi_settings[mobile_navigation_font_size]',
			array(
				'default' => $defaults['mobile_navigation_font_size'],
				'type' => 'option',
				'sanitize_callback' => 'wpkoi_sanitize_decimal_integer'
			)
		);
		
		$wp_customize->add_setting( 
			'wpkoi_settings[desktop_navigation_font_size_unit]', 
			array(
				'default' => $defaults['desktop_navigation_font_size_unit'],
				'type' => 'option',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_setting( 
			'wpkoi_settings[tablet_navigation_font_size_unit]', 
			array(
				'default' => $defaults['tablet_navigation_font_size_unit'],
				'type' => 'option',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_setting( 
			'wpkoi_settings[mobile_navigation_font_size_unit]', 
			array(
				'default' => $defaults['mobile_navigation_font_size_unit'],
				'type' => 'option',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new WPKoi_Pro_Range_Slider_Control(
				$wp_customize,
				'wpkoi_settings[navigation_font_size]',
				array(
					'label' => __( 'Navigation Font Size', 'wpkoi-templates-for-elementor' ),
					'section' => 'font_header_section',
					'priority' => 165,
					'settings' => array(
						'desktop' => 'wpkoi_settings[navigation_font_size]',
						'tablet' => 'wpkoi_settings[tablet_navigation_font_size]',
						'mobile' => 'wpkoi_settings[mobile_navigation_font_size]',
						'desktop_unit' => 'wpkoi_settings[desktop_navigation_font_size_unit]',
    					'tablet_unit'  => 'wpkoi_settings[tablet_navigation_font_size_unit]',
    					'mobile_unit'  => 'wpkoi_settings[mobile_navigation_font_size_unit]',
					),
					'choices' => array(
						'desktop' => array(
							'min' => 1,
							'max' => 50,
							'step' => 1,
							'edit' => true,
							'unit' => 'px',
							'units' => array( 'px', 'em', 'rem', 'vw', 'vh', '%' )
						),
						'tablet' => array(
							'min' => 1,
							'max' => 50,
							'step' => 1,
							'edit' => true,
							'unit' => 'px',
							'units' => array( 'px', 'em', 'rem', 'vw', 'vh', '%' )
						),
						'mobile' => array(
							'min' => 1,
							'max' => 30,
							'step' => 1,
							'edit' => true,
							'unit' => 'px',
							'units' => array( 'px', 'em', 'rem', 'vw', 'vh', '%' )
						),
					),
				)
			)
		);
		
		$wp_customize->add_section(
			'fixed_side_font_section',
			array(
				'title' => __( 'Fixed Side Content', 'wpkoi-templates-for-elementor' ),
				'capability' => 'edit_theme_options',
				'description' => '',
				'priority' => 51,
				'panel' => 'wpkoi_typography_panel'
			)
		);

		$wp_customize->add_setting(
			'wpkoi_settings[fixed_side_font_size]',
			array(
				'default' => $defaults['fixed_side_font_size'],
				'type' => 'option',
				'sanitize_callback' => 'wpkoi_sanitize_decimal_integer',
			)
		);

		$wp_customize->add_setting(
			'wpkoi_settings[mobile_fixed_side_font_size]',
			array(
				'default' => $defaults['mobile_fixed_side_font_size'],
				'type' => 'option',
				'sanitize_callback' => 'wpkoi_sanitize_decimal_integer',
			)
		);
		
		$wp_customize->add_setting( 
			'wpkoi_settings[desktop_fixed_side_font_size_unit]', 
			array(
				'default' =>$defaults['desktop_fixed_side_font_size_unit'],
				'type' => 'option',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_setting( 
			'wpkoi_settings[mobile_fixed_side_font_size_unit]', 
			array(
				'default' => $defaults['mobile_fixed_side_font_size_unit'],
				'type' => 'option',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new WPKoi_Pro_Range_Slider_Control(
				$wp_customize,
				'wpkoi_settings[fixed_side_font_size]',
				array(
					'label' => __( 'Font size', 'wpkoi-templates-for-elementor' ),
					'section' => 'fixed_side_font_section',
					'priority' => 165,
					'settings' => array(
						'desktop' => 'wpkoi_settings[fixed_side_font_size]',
						'mobile' => 'wpkoi_settings[mobile_fixed_side_font_size]',
						'desktop_unit' => 'wpkoi_settings[desktop_fixed_side_font_size_unit]',
    					'mobile_unit'  => 'wpkoi_settings[mobile_fixed_side_font_size_unit]',
					),
					'choices' => array(
						'desktop' => array(
							'min' => 1,
							'max' => 30,
							'step' => 1,
							'edit' => true,
							'unit' => 'px',
							'units' => array( 'px', 'em', 'rem', 'vw', 'vh', '%' )
						),
						'mobile' => array(
							'min' => 1,
							'max' => 20,
							'step' => 1,
							'edit' => true,
							'unit' => 'px',
							'units' => array( 'px', 'em', 'rem', 'vw', 'vh', '%' )
						),
					),
				)
			)
		);

		// Functions for Colors section
		$wp_customize->add_setting(
			'wpkoi_settings[text_color]', array(
				'default' => $defaults['text_color'],
				'type' => 'option',
				'sanitize_callback' => 'wpkoi_sanitize_hex_color'
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'wpkoi_settings[text_color]',
				array(
					'label' => __( 'Text Color', 'wpkoi-templates-for-elementor' ),
					'section' => 'colors',
					'settings' => 'wpkoi_settings[text_color]'
				)
			)
		);

		$wp_customize->add_setting(
			'wpkoi_settings[link_color]', array(
				'default' => $defaults['link_color'],
				'type' => 'option',
				'sanitize_callback' => 'wpkoi_sanitize_hex_color'
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'wpkoi_settings[link_color]',
				array(
					'label' => __( 'Link Color', 'wpkoi-templates-for-elementor' ),
					'section' => 'colors',
					'settings' => 'wpkoi_settings[link_color]'
				)
			)
		);

		$wp_customize->add_setting(
			'wpkoi_settings[link_color_hover]', array(
				'default' => $defaults['link_color_hover'],
				'type' => 'option',
				'sanitize_callback' => 'wpkoi_sanitize_hex_color'
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'wpkoi_settings[link_color_hover]',
				array(
					'label' => __( 'Link Color Hover', 'wpkoi-templates-for-elementor' ),
					'section' => 'colors',
					'settings' => 'wpkoi_settings[link_color_hover]'
				)
			)
		);

		$wp_customize->add_setting(
			'wpkoi_settings[side_inside_color]', array(
				'default' => $defaults['side_inside_color'],
				'type' => 'option',
				'capability'  => 'edit_theme_options',
				'sanitize_callback' => 'wpkoi_sanitize_hex_color'
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'wpkoi_settings[side_inside_color]',
				array(
					'label' => __( 'Body Padding Content', 'wpkoi-templates-for-elementor' ),
					'section' => 'colors',
					'settings' => 'wpkoi_settings[side_inside_color]'
				)
			)
		);

		$wp_customize->add_control(
			new WPKoi_Title_Customize_Control(
				$wp_customize,
				'wpkoi_header_colors_title',
				array(
					'section'     => 'colors',
					'type'        => 'wpkoi-customizer-title',
					'title'			=> __( 'Header colors', 'wpkoi-templates-for-elementor' ),
					'settings' => ( isset( $wp_customize->selective_refresh ) ) ? array() : 'blogname',
					'priority'	=> 20,
				)
			)
		);

		$wp_customize->add_setting(
			'wpkoi_settings[header_background_color]',
			array(
				'default'     => $defaults['header_background_color'],
				'type'        => 'option',
				'capability'  => 'edit_theme_options',
				'sanitize_callback' => 'wpkoi_sanitize_rgba',
			)
		);

		$wp_customize->add_control(
			new WPKoi_Alpha_Color_Customize_Control(
				$wp_customize,
				'wpkoi_settings[header_background_color]',
				array(
					'label'     => __( 'Background', 'wpkoi-templates-for-elementor' ),
					'section'   => 'colors',
					'settings'  => 'wpkoi_settings[header_background_color]',
					'palette'   => $palettes,
					'show_opacity'  => true,
					'priority' => 21,
				)
			)
		);

		$header_colors = array();
		$header_colors[] = array(
			'slug' => 'header_text_color',
			'default' => $defaults['header_text_color'],
			'label' => __( 'Text', 'wpkoi-templates-for-elementor' ),
			'priority' => 22,
		);
		$header_colors[] = array(
			'slug' => 'header_link_color',
			'default' => $defaults['header_link_color'],
			'label' => __( 'Link', 'wpkoi-templates-for-elementor' ),
			'priority' => 23,
		);
		$header_colors[] = array(
			'slug' => 'header_link_hover_color',
			'default' => $defaults['header_link_hover_color'],
			'label' => __( 'Link Hover', 'wpkoi-templates-for-elementor' ),
			'priority' => 24,
		);
		$header_colors[] = array(
			'slug' => 'site_title_color',
			'default' => $defaults['site_title_color'],
			'label' => __( 'Site Title', 'wpkoi-templates-for-elementor' ),
			'priority' => 25,
		);
		$header_colors[] = array(
			'slug' => 'site_title_bg_color',
			'default' => $defaults['site_title_bg_color'],
			'label' => __( 'Site Title Background', 'wpkoi-templates-for-elementor' ),
			'priority' => 25,
		);

		foreach( $header_colors as $color ) {
			$wp_customize->add_setting(
				'wpkoi_settings[' . $color['slug'] . ']', array(
					'default' => $color['default'],
					'type' => 'option',
					'capability' => 'edit_theme_options',
					'sanitize_callback' => 'wpkoi_sanitize_hex_color'
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					$color['slug'],
					array(
						'label' => $color['label'],
						'section' => 'colors',
						'settings' => 'wpkoi_settings[' . $color['slug'] . ']',
						'priority' => $color['priority'],
						'palette'   => $palettes,
					)
				)
			);
		}

		$wp_customize->add_control(
			new WPKoi_Title_Customize_Control(
				$wp_customize,
				'wpkoi_primary_navigation_parent_items',
				array(
					'section'     => 'colors',
					'type'        => 'wpkoi-customizer-title',
					'title'			=> __( 'Primary Navigation', 'wpkoi-templates-for-elementor' ),
					'settings' => ( isset( $wp_customize->selective_refresh ) ) ? array() : 'blogname',
					'priority'	=> 30,
				)
			)
		);

		$wp_customize->add_setting(
			'wpkoi_settings[navigation_background_color]',
			array(
				'default'     => $defaults['navigation_background_color'],
				'type'        => 'option',
				'capability'  => 'edit_theme_options',
				'sanitize_callback' => 'wpkoi_sanitize_rgba',
			)
		);

		$wp_customize->add_control(
			new WPKoi_Alpha_Color_Customize_Control(
				$wp_customize,
				'wpkoi_settings[navigation_background_color]',
				array(
					'label'     => __( 'Background', 'wpkoi-templates-for-elementor' ),
					'section'   => 'colors',
					'settings'  => 'wpkoi_settings[navigation_background_color]',
					'palette'   => $palettes,
					'priority' => 31,
				)
			)
		);

		$wp_customize->add_setting(
			'wpkoi_settings[navigation_background_hover_color]',
			array(
				'default'     => $defaults['navigation_background_hover_color'],
				'type'        => 'option',
				'capability'  => 'edit_theme_options',
				'sanitize_callback' => 'wpkoi_sanitize_rgba',
			)
		);

		$wp_customize->add_control(
			new WPKoi_Alpha_Color_Customize_Control(
				$wp_customize,
				'wpkoi_settings[navigation_background_hover_color]',
				array(
					'label'     => __( 'Background Hover', 'wpkoi-templates-for-elementor' ),
					'section'   => 'colors',
					'settings'  => 'wpkoi_settings[navigation_background_hover_color]',
					'palette'   => $palettes,
					'priority' => 33,
				)
			)
		);

		$wp_customize->add_setting(
			'wpkoi_settings[navigation_background_current_color]',
			array(
				'default'     => $defaults['navigation_background_current_color'],
				'type'        => 'option',
				'capability'  => 'edit_theme_options',
				'sanitize_callback' => 'wpkoi_sanitize_rgba',
			)
		);

		$wp_customize->add_control(
			new WPKoi_Alpha_Color_Customize_Control(
				$wp_customize,
				'wpkoi_settings[navigation_background_current_color]',
				array(
					'label'     => __( 'Background Current', 'wpkoi-templates-for-elementor' ),
					'section'   => 'colors',
					'settings'  => 'wpkoi_settings[navigation_background_current_color]',
					'palette'   => $palettes,
					'priority' => 35,
				)
			)
		);

		$navigation_colors = array();
		$navigation_colors[] = array(
			'slug'=>'navigation_text_color',
			'default' => $defaults['navigation_text_color'],
			'label' => __( 'Text', 'wpkoi-templates-for-elementor' ),
			'priority' => 32,
		);
		$navigation_colors[] = array(
			'slug'=>'navigation_text_hover_color',
			'default' => $defaults['navigation_text_hover_color'],
			'label' => __( 'Text Hover', 'wpkoi-templates-for-elementor' ),
			'priority' => 34,
		);
		$navigation_colors[] = array(
			'slug'=>'navigation_text_current_color',
			'default' => $defaults['navigation_text_current_color'],
			'label' => __( 'Text Current', 'wpkoi-templates-for-elementor' ),
			'priority' => 36,
		);
		$navigation_colors[] = array(
			'slug'=>'navigation_border_color',
			'default' => $defaults['navigation_border_color'],
			'label' => __( 'Navigation Border Color', 'wpkoi-templates-for-elementor' ),
			'priority' => 36,
		);

		foreach( $navigation_colors as $color ) {
			$wp_customize->add_setting(
				'wpkoi_settings[' . $color['slug'] . ']', array(
					'default' => $color['default'],
					'type' => 'option',
					'capability' => 'edit_theme_options',
					'sanitize_callback' => 'wpkoi_sanitize_hex_color'
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					$color['slug'],
					array(
						'label' => $color['label'],
						'section' => 'colors',
						'settings' => 'wpkoi_settings[' . $color['slug'] . ']',
						'priority' => $color['priority']
					)
				)
			);
		}

		$wp_customize->add_control(
			new WPKoi_Title_Customize_Control(
				$wp_customize,
				'wpkoi_primary_navigation_sub_menu_items',
				array(
					'section'     => 'colors',
					'type'        => 'wpkoi-customizer-title',
					'title'			=> __( 'Navigation Sub-Menu', 'wpkoi-templates-for-elementor' ),
					'settings' => ( isset( $wp_customize->selective_refresh ) ) ? array() : 'blogname',
					'priority' => 37,
				)
			)
		);

		$wp_customize->add_setting(
			'wpkoi_settings[subnavigation_background_color]',
			array(
				'default'     => $defaults['subnavigation_background_color'],
				'type'        => 'option',
				'capability'  => 'edit_theme_options',
				'sanitize_callback' => 'wpkoi_sanitize_rgba',
			)
		);

		$wp_customize->add_control(
			new WPKoi_Alpha_Color_Customize_Control(
				$wp_customize,
				'wpkoi_settings[subnavigation_background_color]',
				array(
					'label'     => __( 'Background', 'wpkoi-templates-for-elementor' ),
					'section'   => 'colors',
					'settings'  => 'wpkoi_settings[subnavigation_background_color]',
					'palette'   => $palettes,
					'priority' => 38,
				)
			)
		);

		$wp_customize->add_setting(
			'wpkoi_settings[subnavigation_background_hover_color]',
			array(
				'default'     => $defaults['subnavigation_background_hover_color'],
				'type'        => 'option',
				'capability'  => 'edit_theme_options',
				'sanitize_callback' => 'wpkoi_sanitize_rgba',
			)
		);

		$wp_customize->add_control(
			new WPKoi_Alpha_Color_Customize_Control(
				$wp_customize,
				'wpkoi_settings[subnavigation_background_hover_color]',
				array(
					'label'     => __( 'Background Hover', 'wpkoi-templates-for-elementor' ),
					'section'   => 'colors',
					'settings'  => 'wpkoi_settings[subnavigation_background_hover_color]',
					'palette'   => $palettes,
					'priority' => 40,
				)
			)
		);

		$wp_customize->add_setting(
			'wpkoi_settings[subnavigation_background_current_color]',
			array(
				'default'     => $defaults['subnavigation_background_current_color'],
				'type'        => 'option',
				'capability'  => 'edit_theme_options',
				'sanitize_callback' => 'wpkoi_sanitize_rgba',
			)
		);

		$wp_customize->add_control(
			new WPKoi_Alpha_Color_Customize_Control(
				$wp_customize,
				'wpkoi_settings[subnavigation_background_current_color]',
				array(
					'label'     => __( 'Background Current', 'wpkoi-templates-for-elementor' ),
					'section'   => 'colors',
					'settings'  => 'wpkoi_settings[subnavigation_background_current_color]',
					'palette'   => $palettes,
					'priority' => 42,
				)
			)
		);

		$subnavigation_colors = array();
		$subnavigation_colors[] = array(
			'slug'=>'subnavigation_text_color',
			'default' => $defaults['subnavigation_text_color'],
			'label' => __( 'Text', 'wpkoi-templates-for-elementor' ),
			'priority' => 39,
		);
		$subnavigation_colors[] = array(
			'slug'=>'subnavigation_text_hover_color',
			'default' => $defaults['subnavigation_text_hover_color'],
			'label' => __( 'Text Hover', 'wpkoi-templates-for-elementor' ),
			'priority' => 41,
		);
		$subnavigation_colors[] = array(
			'slug'=>'subnavigation_text_current_color',
			'default' => $defaults['subnavigation_text_current_color'],
			'label' => __( 'Text Current', 'wpkoi-templates-for-elementor' ),
			'priority' => 43,
		);
		foreach( $subnavigation_colors as $color ) {
			$wp_customize->add_setting(
				'wpkoi_settings[' . $color['slug'] . ']', array(
					'default' => $color['default'],
					'type' => 'option',
					'capability' => 'edit_theme_options',
					'sanitize_callback' => 'wpkoi_sanitize_hex_color'
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					$color['slug'],
					array(
						'label' => $color['label'],
						'section' => 'colors',
						'settings' => 'wpkoi_settings[' . $color['slug'] . ']',
						'priority' => $color['priority'],
					)
				)
			);
		}
		
		$wp_customize->add_control(
			new WPKoi_Title_Customize_Control(
				$wp_customize,
				'wpkoi_copyright_colors_title',
				array(
					'section' => 'colors',
					'type' => 'wpkoi-customizer-title',
					'title' => __( 'Copyright area', 'wpkoi-templates-for-elementor' ),
					'settings' => ( isset( $wp_customize->selective_refresh ) ) ? array() : 'blogname',
					'priority' => 80
				)
			)
		);

		$wp_customize->add_setting(
			'wpkoi_settings[footer_background_color]',
			array(
				'default'     => $defaults['footer_background_color'],
				'type'        => 'option',
				'capability'  => 'edit_theme_options',
				'sanitize_callback' => 'wpkoi_sanitize_rgba',
			)
		);

		$wp_customize->add_control(
			new WPKoi_Alpha_Color_Customize_Control(
				$wp_customize,
				'wpkoi_settings[footer_background_color]',
				array(
					'label'     => __( 'Background', 'wpkoi-templates-for-elementor' ),
					'section'   => 'colors',
					'settings'  => 'wpkoi_settings[footer_background_color]',
					'palette'   => $palettes,
					'priority' => 81,
				)
			)
		);

		$footer_colors = array();
		$footer_colors[] = array(
			'slug' => 'footer_text_color',
			'default' => $defaults['footer_text_color'],
			'label' => __( 'Text', 'wpkoi-templates-for-elementor' ),
			'priority' => 82,
		);
		$footer_colors[] = array(
			'slug' => 'footer_link_color',
			'default' => $defaults['footer_link_color'],
			'label' => __( 'Link', 'wpkoi-templates-for-elementor' ),
			'priority' => 83,
		);
		$footer_colors[] = array(
			'slug' => 'footer_link_hover_color',
			'default' => $defaults['footer_link_hover_color'],
			'label' => __( 'Link Hover', 'wpkoi-templates-for-elementor' ),
			'priority' => 84,
		);

		foreach( $footer_colors as $color ) {
			$wp_customize->add_setting(
				'wpkoi_settings[' . $color['slug'] . ']', array(
					'default' => $color['default'],
					'type' => 'option',
					'capability' => 'edit_theme_options',
					'sanitize_callback' => 'wpkoi_sanitize_hex_color'
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					$color['slug'],
					array(
						'label' => $color['label'],
						'section' => 'colors',
						'settings' => 'wpkoi_settings[' . $color['slug'] . ']',
						'priority' => $color['priority'],
					)
				)
			);
		}

		if ( ! defined( 'WPKOI_PREMIUM_VERSION' ) ) {
			$wp_customize->add_control(
				new WPKoi_Customize_Misc_Control(
					$wp_customize,
					'colors_get_addon_desc',
					array(
						'section' => 'colors',
						'type' => 'addon',
						'label' => __( 'Get ', 'wpkoi-templates-for-elementor' ) . esc_html( ucfirst( WPKOI_PARENT_THEME_SLUG) ) . __( ' Premium', 'wpkoi-templates-for-elementor' ),
						'description' => __( 'More colors are available in the premium version. Visit wpkoi.com for more info.', 'wpkoi-templates-for-elementor' ),
						'url' => esc_url( wpkoi_theme_uri_link() ),
						'priority' => 130,
						'settings' => ( isset( $wp_customize->selective_refresh ) ) ? array() : 'blogname'
					)
				)
			);
		}
		
		// Functions for Fixed Side Content section
		$wp_customize->add_section(
			'wpkoi_layout_sidecontent',
			array(
				'title' => __( 'Fixed Side Content', 'wpkoi-templates-for-elementor' ),
				'priority' => 55
			)
		);
		
		$wp_customize->add_setting(
			'wpkoi_settings[fixed_side_content]',
			array(
				'default' => $defaults['fixed_side_content'],
				'type' => 'option',
				'sanitize_callback' => 'wp_kses_post',
			)
		);

		$wp_customize->add_control(
			'wpkoi_settings[fixed_side_content]',
			array(
				'type' 		 => 'textarea',
				'label'      => __( 'Fixed Side Content', 'wpkoi-templates-for-elementor' ),
				'description'=> __( 'Content that You want to display fixed on the left.', 'wpkoi-templates-for-elementor' ),
				'section'    => 'wpkoi_layout_sidecontent',
				'settings'   => 'wpkoi_settings[fixed_side_content]',
				'priority' 	 => 1
			)
		);
		
		$wp_customize->add_setting(
			'wpkoi_settings[fixed_side_display_mobile]',
			array(
				'default' => $defaults['fixed_side_display_mobile'],
				'type' => 'option',
				'sanitize_callback' => 'wpkoi_sanitize_checkbox'
			)
		);

		$wp_customize->add_control(
			'wpkoi_settings[fixed_side_display_mobile]',
			array(
				'type' => 'checkbox',
				'label' => esc_html__( 'Display on mobile', 'wpkoi-templates-for-elementor' ),
				'section' => 'wpkoi_layout_sidecontent',
				'settings' => 'wpkoi_settings[fixed_side_display_mobile]',
				'priority' => 5
			)
		);
		
		$wp_customize->add_setting(
			'wpkoi_settings[socials_facebook_url]',
			array(
				'default' => $defaults['socials_facebook_url'],
				'type' => 'option',
				'sanitize_callback' => 'esc_url',
			)
		);

		$wp_customize->add_control(
			'wpkoi_settings[socials_facebook_url]',
			array(
				'type' 		 => 'text',
				'label'      => __( 'Facebook url', 'wpkoi-templates-for-elementor' ),
				'section'    => 'wpkoi_layout_sidecontent',
				'settings'   => 'wpkoi_settings[socials_facebook_url]',
				'priority' 	 => 10
			)
		);
		
		$wp_customize->add_setting(
			'wpkoi_settings[socials_twitter_url]',
			array(
				'default' => $defaults['socials_twitter_url'],
				'type' => 'option',
				'sanitize_callback' => 'esc_url',
			)
		);

		$wp_customize->add_control(
			'wpkoi_settings[socials_twitter_url]',
			array(
				'type' 		 => 'text',
				'label'      => __( 'X (Twitter) url', 'wpkoi-templates-for-elementor' ),
				'section'    => 'wpkoi_layout_sidecontent',
				'settings'   => 'wpkoi_settings[socials_twitter_url]',
				'priority' 	 => 11
			)
		);
		
		$wp_customize->add_setting(
			'wpkoi_settings[socials_instagram_url]',
			array(
				'default' => $defaults['socials_instagram_url'],
				'type' => 'option',
				'sanitize_callback' => 'esc_url',
			)
		);

		$wp_customize->add_control(
			'wpkoi_settings[socials_instagram_url]',
			array(
				'type' 		 => 'text',
				'label'      => __( 'Instagram url', 'wpkoi-templates-for-elementor' ),
				'section'    => 'wpkoi_layout_sidecontent',
				'settings'   => 'wpkoi_settings[socials_instagram_url]',
				'priority' 	 => 12
			)
		);
		
		$wp_customize->add_setting(
			'wpkoi_settings[socials_youtube_url]',
			array(
				'default' => $defaults['socials_youtube_url'],
				'type' => 'option',
				'sanitize_callback' => 'esc_url',
			)
		);

		$wp_customize->add_control(
			'wpkoi_settings[socials_youtube_url]',
			array(
				'type' 		 => 'text',
				'label'      => __( 'Youtube url', 'wpkoi-templates-for-elementor' ),
				'section'    => 'wpkoi_layout_sidecontent',
				'settings'   => 'wpkoi_settings[socials_youtube_url]',
				'priority' 	 => 13
			)
		);
		
		$wp_customize->add_setting(
			'wpkoi_settings[socials_tiktok_url]',
			array(
				'default' => $defaults['socials_tiktok_url'],
				'type' => 'option',
				'sanitize_callback' => 'esc_url',
			)
		);

		$wp_customize->add_control(
			'wpkoi_settings[socials_tiktok_url]',
			array(
				'type' 		 => 'text',
				'label'      => __( 'Tiktok url', 'wpkoi-templates-for-elementor' ),
				'section'    => 'wpkoi_layout_sidecontent',
				'settings'   => 'wpkoi_settings[socials_tiktok_url]',
				'priority' 	 => 14
			)
		);
		
		$wp_customize->add_setting(
			'wpkoi_settings[socials_twitch_url]',
			array(
				'default' => $defaults['socials_twitch_url'],
				'type' => 'option',
				'sanitize_callback' => 'esc_url',
			)
		);

		$wp_customize->add_control(
			'wpkoi_settings[socials_twitch_url]',
			array(
				'type' 		 => 'text',
				'label'      => __( 'Twitch url', 'wpkoi-templates-for-elementor' ),
				'section'    => 'wpkoi_layout_sidecontent',
				'settings'   => 'wpkoi_settings[socials_twitch_url]',
				'priority' 	 => 15
			)
		);
		
		$wp_customize->add_setting(
			'wpkoi_settings[socials_tumblr_url]',
			array(
				'default' => $defaults['socials_tumblr_url'],
				'type' => 'option',
				'sanitize_callback' => 'esc_url',
			)
		);

		$wp_customize->add_control(
			'wpkoi_settings[socials_tumblr_url]',
			array(
				'type' 		 => 'text',
				'label'      => __( 'Tumblr url', 'wpkoi-templates-for-elementor' ),
				'section'    => 'wpkoi_layout_sidecontent',
				'settings'   => 'wpkoi_settings[socials_tumblr_url]',
				'priority' 	 => 16
			)
		);
		
		$wp_customize->add_setting(
			'wpkoi_settings[socials_pinterest_url]',
			array(
				'default' => $defaults['socials_pinterest_url'],
				'type' => 'option',
				'sanitize_callback' => 'esc_url',
			)
		);

		$wp_customize->add_control(
			'wpkoi_settings[socials_pinterest_url]',
			array(
				'type' 		 => 'text',
				'label'      => __( 'Pinterest url', 'wpkoi-templates-for-elementor' ),
				'section'    => 'wpkoi_layout_sidecontent',
				'settings'   => 'wpkoi_settings[socials_pinterest_url]',
				'priority' 	 => 17
			)
		);
		
		$wp_customize->add_setting(
			'wpkoi_settings[socials_linkedin_url]',
			array(
				'default' => $defaults['socials_linkedin_url'],
				'type' => 'option',
				'sanitize_callback' => 'esc_url',
			)
		);

		$wp_customize->add_control(
			'wpkoi_settings[socials_linkedin_url]',
			array(
				'type' 		 => 'text',
				'label'      => __( 'Linkedin url', 'wpkoi-templates-for-elementor' ),
				'section'    => 'wpkoi_layout_sidecontent',
				'settings'   => 'wpkoi_settings[socials_linkedin_url]',
				'priority' 	 => 18
			)
		);
		
		$wp_customize->add_setting(
			'wpkoi_settings[socials_linkedin_url]',
			array(
				'default' => $defaults['socials_linkedin_url'],
				'type' => 'option',
				'sanitize_callback' => 'esc_url',
			)
		);

		$wp_customize->add_control(
			'wpkoi_settings[socials_linkedin_url]',
			array(
				'type' 		 => 'text',
				'label'      => __( 'Linkedin url', 'wpkoi-templates-for-elementor' ),
				'section'    => 'wpkoi_layout_sidecontent',
				'settings'   => 'wpkoi_settings[socials_linkedin_url]',
				'priority' 	 => 19
			)
		);
		
		$wp_customize->add_setting(
			'wpkoi_settings[socials_mail_url]',
			array(
				'default' => $defaults['socials_mail_url'],
				'type' => 'option',
				'sanitize_callback' => 'esc_attr',
			)
		);

		$wp_customize->add_control(
			'wpkoi_settings[socials_mail_url]',
			array(
				'type' 		 => 'text',
				'label'      => __( 'E-mail url', 'wpkoi-templates-for-elementor' ),
				'section'    => 'wpkoi_layout_sidecontent',
				'settings'   => 'wpkoi_settings[socials_mail_url]',
				'priority' 	 => 20
			)
		);

		// Functions for General section
		$wp_customize->add_section(
			'wpkoi_general_section',
			array(
				'title' => __( 'General', 'wpkoi-templates-for-elementor' ),
				'priority' => 50
			)
		);

		$wp_customize->add_setting(
			'wpkoi_settings[back_to_top]',
			array(
				'default' => $defaults['back_to_top'],
				'type' => 'option',
				'sanitize_callback' => 'wpkoi_sanitize_choices'
			)
		);

		$wp_customize->add_control(
			'wpkoi_settings[back_to_top]',
			array(
				'type' => 'select',
				'label' => __( 'Back to Top Button', 'wpkoi-templates-for-elementor' ),
				'section' => 'wpkoi_general_section',
				'choices' => array(
					'enable' => __( 'Enable', 'wpkoi-templates-for-elementor' ),
					'' => __( 'Disable', 'wpkoi-templates-for-elementor' )
				),
				'settings' => 'wpkoi_settings[back_to_top]',
				'priority' => 50
			)
		);

		$wp_customize->add_setting(
			'wpkoi_settings[content_link_dec]',
			array(
				'default' => $defaults['content_link_dec'],
				'type' => 'option',
				'sanitize_callback' => 'wpkoi_sanitize_choices'
			)
		);

		$wp_customize->add_control(
			'wpkoi_settings[content_link_dec]',
			array(
				'type' => 'select',
				'label' => __( 'Content Link Underline', 'wpkoi-templates-for-elementor' ),
				'section' => 'wpkoi_general_section',
				'choices' => array(
					'enable' => __( 'Always', 'wpkoi-templates-for-elementor' ),
					'onhover' => __( 'On Hover', 'wpkoi-templates-for-elementor' ),
					'disable' => __( 'Never', 'wpkoi-templates-for-elementor' )
				),
				'settings' => 'wpkoi_settings[content_link_dec]',
				'priority' => 51
			)
		);
		
		$wp_customize->add_setting(
			'wpkoi_settings[enable_noise]',
			array(
				'default' => $defaults['enable_noise'],
				'type' => 'option',
				'sanitize_callback' => 'wpkoi_sanitize_choices'
			)
		);

		$wp_customize->add_control(
			'wpkoi_settings[enable_noise]',
			array(
				'type' => 'select',
				'label' => __( 'Enable noise', 'wpkoi-templates-for-elementor' ),
				'choices' => array(
					'enable' => __( 'Enable', 'wpkoi-templates-for-elementor' ),
					'disable' => __( 'Disable', 'wpkoi-templates-for-elementor' )
				),
				'settings' => 'wpkoi_settings[enable_noise]',
				'section' => 'wpkoi_general_section',
				'description' => __( 'Adds a white noise effect to the website.', 'wpkoi-templates-for-elementor' ),
				'priority' => 110
			)
		);
		
		$wp_customize->add_setting(
			'wpkoi_settings[noise_image]',
			array(
				'default' => $defaults['noise_image'],
				'type' => 'option',
				'sanitize_callback' => 'esc_url_raw'
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'wpkoi_settings[noise_image]',
				array(
					'label' => __( 'Background noise image', 'wpkoi-templates-for-elementor' ),
					'section' => 'wpkoi_general_section',
					'priority' => 111,
					'settings' => 'wpkoi_settings[noise_image]',
					'description' => __( 'Add a transparent image with some dirt. Recommended size: 100*100px.', 'wpkoi-templates-for-elementor' ),
					'active_callback' => function () {
						return get_option( 'wpkoi_settings' )['enable_noise'] === 'enable';
					},
				)
			)
		);
		
		$wp_customize->add_setting(
			'wpkoi_settings[image_cursor]',
			array(
				'default' => $defaults['image_cursor'],
				'type' => 'option',
				'sanitize_callback' => 'wpkoi_sanitize_choices'
			)
		);

		$wp_customize->add_control(
			'wpkoi_settings[image_cursor]',
			array(
				'type' => 'select',
				'label' => __( 'Image cursor', 'wpkoi-templates-for-elementor' ),
				'section' => 'wpkoi_general_section',
				'choices' => array(
					'enable' => __( 'Enable', 'wpkoi-templates-for-elementor' ),
					'disable' => __( 'Disable', 'wpkoi-templates-for-elementor' )
				),
				'settings' => 'wpkoi_settings[image_cursor]',
				'priority' => 120,
				'description' => __( 'Replace the cursor with images.', 'wpkoi-templates-for-elementor' ),
			)
		);
		
		$wp_customize->add_setting(
			'wpkoi_settings[def_cursor_image]',
			array(
				'default' => $defaults['def_cursor_image'],
				'type' => 'option',
				'sanitize_callback' => 'esc_url_raw'
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'wpkoi_settings[def_cursor_image]',
				array(
					'label' => __( 'Default cursor image', 'wpkoi-templates-for-elementor' ),
					'section' => 'wpkoi_general_section',
					'priority' => 121,
					'settings' => 'wpkoi_settings[def_cursor_image]',
					'description' => __( 'Recommended size: 32*32px. Big image won`t work.', 'wpkoi-templates-for-elementor' ),
					'active_callback' => function () {
						return get_option( 'wpkoi_settings' )['image_cursor'] === 'enable';
					},
				)
			)
		);
		
		$wp_customize->add_setting(
			'wpkoi_settings[pointer_cursor_image]',
			array(
				'default' => $defaults['pointer_cursor_image'],
				'type' => 'option',
				'sanitize_callback' => 'esc_url_raw'
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'wpkoi_settings[pointer_cursor_image]',
				array(
					'label' => __( 'Pointer cursor image', 'wpkoi-templates-for-elementor' ),
					'section' => 'wpkoi_general_section',
					'priority' => 122,
					'settings' => 'wpkoi_settings[pointer_cursor_image]',
					'description' => __( 'Recommended size: 32*32px. Big image won`t work.', 'wpkoi-templates-for-elementor' ),
					'active_callback' => function () {
						return get_option( 'wpkoi_settings' )['image_cursor'] === 'enable';
					},
				)
			)
		);
		
		$wp_customize->add_setting(
			'wpkoi_settings[stylish_scrollbar]',
			array(
				'default' => $defaults['stylish_scrollbar'],
				'type' => 'option',
				'sanitize_callback' => 'wpkoi_sanitize_choices'
			)
		);

		$wp_customize->add_control(
			'wpkoi_settings[stylish_scrollbar]',
			array(
				'type' => 'select',
				'label' => __( 'Stylish Scrollbar', 'wpkoi-templates-for-elementor' ),
				'section' => 'wpkoi_general_section',
				'choices' => array(
					'enable' => __( 'Enable', 'wpkoi-templates-for-elementor' ),
					'disable' => __( 'Disable', 'wpkoi-templates-for-elementor' )
				),
				'settings' => 'wpkoi_settings[stylish_scrollbar]',
				'priority' => 135,
				'description' => __( 'It only works on Chrome and some specific browsers', 'wpkoi-templates-for-elementor' )
			)
		);

		if ( ! defined( 'WPKOI_PREMIUM_VERSION' ) ) {
			$wp_customize->add_control(
				new WPKoi_Customize_Misc_Control(
					$wp_customize,
					'general_get_addon_desc',
					array(
						'section' => 'wpkoi_general_section',
						'type' => 'addon',
						'label' => __( 'Get ', 'wpkoi-templates-for-elementor' ) . esc_html( ucfirst( WPKOI_PARENT_THEME_SLUG) ) . __( ' Premium', 'wpkoi-templates-for-elementor' ),
						'description' => __( 'More options are available in the premium version. Visit wpkoi.com for more info.', 'wpkoi-templates-for-elementor' ),
						'url' => esc_url( wpkoi_theme_uri_link() ),
						'priority' => 170,
						'settings' => ( isset( $wp_customize->selective_refresh ) ) ? array() : 'blogname'
					)
				)
			);
		}
		
		// Check if the WordPress version is newer than defined
        if ( version_compare( get_bloginfo('version'), '6.6', '>=' ) ) {

			// Fast links to template parts
			$wp_customize->add_section(
				'wpkoi_template_parts_section',
				array(
					'title' => __( 'Template Parts', 'wpkoi-templates-for-elementor' ),
					'priority' => 60
				)
			);

			$wp_customize->add_control(
				new WPKoi_Template_Parts_Section( $wp_customize, 'wpkoi_tp_top_bar_section',
					array(
						'section' => 'wpkoi_template_parts_section',
						'type' => 'wpkoi-template-parts-section',
						'tp_text' => __( 'Top Bar', 'wpkoi-templates-for-elementor' ),
						'tp_url' => esc_url( admin_url( 'site-editor.php?postType=wp_template_part&postId=' . esc_attr( $textdomain ) .'%2F%2Ftopbar&canvas=edit' ) ),
						'priority' => 1,
						'settings' => ( isset( $wp_customize->selective_refresh ) ) ? array() : 'blogname'
					)
				)
			);

			$wp_customize->add_control(
				new WPKoi_Template_Parts_Section( $wp_customize, 'wpkoi_tp_footer_section',
					array(
						'section' => 'wpkoi_template_parts_section',
						'type' => 'wpkoi-template-parts-section',
						'tp_text' => __( 'Footer', 'wpkoi-templates-for-elementor' ),
						'tp_url' => esc_url( admin_url( 'site-editor.php?postType=wp_template_part&postId=' . esc_attr( $textdomain ) .'%2F%2Ffooter&canvas=edit' ) ),
						'priority' => 5,
						'settings' => ( isset( $wp_customize->selective_refresh ) ) ? array() : 'blogname'
					)
				)
			);

			$wp_customize->add_control(
				new WPKoi_Template_Parts_Section( $wp_customize, 'wpkoi_tp_page_section',
					array(
						'section' => 'wpkoi_template_parts_section',
						'type' => 'wpkoi-template-parts-section',
						'tp_text' => __( 'Default Page', 'wpkoi-templates-for-elementor' ),
						'tp_url' => esc_url( admin_url( 'site-editor.php?postType=wp_template_part&postId=' . esc_attr( $textdomain ) .'%2F%2Fpage&canvas=edit' ) ),
						'priority' => 7,
						'settings' => ( isset( $wp_customize->selective_refresh ) ) ? array() : 'blogname'
					)
				)
			);

			$wp_customize->add_control(
				new WPKoi_Template_Parts_Section( $wp_customize, 'wpkoi_tp_single_section',
					array(
						'section' => 'wpkoi_template_parts_section',
						'type' => 'wpkoi-template-parts-section',
						'tp_text' => __( 'Single Post', 'wpkoi-templates-for-elementor' ),
						'tp_url' => esc_url( admin_url( 'site-editor.php?postType=wp_template_part&postId=' . esc_attr( $textdomain ) .'%2F%2Fsingle&canvas=edit' ) ),
						'priority' => 9,
						'settings' => ( isset( $wp_customize->selective_refresh ) ) ? array() : 'blogname'
					)
				)
			);

			$wp_customize->add_control(
				new WPKoi_Template_Parts_Section( $wp_customize, 'wpkoi_tp_index_section',
					array(
						'section' => 'wpkoi_template_parts_section',
						'type' => 'wpkoi-template-parts-section',
						'tp_text' => __( 'Index', 'wpkoi-templates-for-elementor' ),
						'tp_url' => esc_url( admin_url( 'site-editor.php?postType=wp_template_part&postId=' . esc_attr( $textdomain ) .'%2F%2Findex&canvas=edit' ) ),
						'priority' => 11,
						'settings' => ( isset( $wp_customize->selective_refresh ) ) ? array() : 'blogname'
					)
				)
			);

			$wp_customize->add_control(
				new WPKoi_Template_Parts_Section( $wp_customize, 'wpkoi_tp_archive_section',
					array(
						'section' => 'wpkoi_template_parts_section',
						'type' => 'wpkoi-template-parts-section',
						'tp_text' => __( 'Archive', 'wpkoi-templates-for-elementor' ),
						'tp_url' => esc_url( admin_url( 'site-editor.php?postType=wp_template_part&postId=' . esc_attr( $textdomain ) .'%2F%2Farchive&canvas=edit' ) ),
						'priority' => 13,
						'settings' => ( isset( $wp_customize->selective_refresh ) ) ? array() : 'blogname'
					)
				)
			);

			$wp_customize->add_control(
				new WPKoi_Template_Parts_Section( $wp_customize, 'wpkoi_tp_404_section',
					array(
						'section' => 'wpkoi_template_parts_section',
						'type' => 'wpkoi-template-parts-section',
						'tp_text' => __( '404 Page', 'wpkoi-templates-for-elementor' ),
						'tp_url' => esc_url( admin_url( 'site-editor.php?postType=wp_template_part&postId=' . esc_attr( $textdomain ) .'%2F%2F404&canvas=edit' ) ),
						'priority' => 15,
						'settings' => ( isset( $wp_customize->selective_refresh ) ) ? array() : 'blogname'
					)
				)
			);

			$wp_customize->add_control(
				new WPKoi_Template_Parts_Section( $wp_customize, 'wpkoi_tp_search_section',
					array(
						'section' => 'wpkoi_template_parts_section',
						'type' => 'wpkoi-template-parts-section',
						'tp_text' => __( 'Search Result', 'wpkoi-templates-for-elementor' ),
						'tp_url' => esc_url( admin_url( 'site-editor.php?postType=wp_template_part&postId=' . esc_attr( $textdomain ) .'%2F%2Fsearch&canvas=edit' ) ),
						'priority' => 17,
						'settings' => ( isset( $wp_customize->selective_refresh ) ) ? array() : 'blogname'
					)
				)
			);
		}
		
		// Add WPKoi Premium section
		if ( ! defined( 'WPKOI_PREMIUM_VERSION' ) ) {
			$wp_customize->add_section(
				new WPKoi_Upsell_Section( $wp_customize, 'wpkoi_upsell_section',
					array(
						'pro_text' => __( 'Get ', 'wpkoi-templates-for-elementor' ) . esc_html( ucfirst( WPKOI_PARENT_THEME_SLUG) ) . __( ' Premium for more!', 'wpkoi-templates-for-elementor' ),
						'pro_url' => esc_url( wpkoi_theme_uri_link() ),
						'capability' => 'edit_theme_options',
						'priority' => 1,
						'type' => 'wpkoi-upsell-section',
					)
				)
			);
		}
	}
}