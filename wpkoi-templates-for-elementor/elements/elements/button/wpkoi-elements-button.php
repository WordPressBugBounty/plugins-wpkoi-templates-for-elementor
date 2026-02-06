<?php
/**
 * Class: WPKoi_Elements_Button
 * Name: Button
 * Slug: wpkoi-button
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WPKoi_Elements_Button extends Widget_Base {

	public function get_name() {
		return 'wpkoi-button';
	}

	public function get_title() {
		return esc_html__( 'Button', 'wpkoi-elements' );
	}

	public function get_icon() {
		return 'eicon-dual-button';
	}

	public function get_categories() {
		return [ 'wpkoi-addons-for-elementor' ];
	}
	
	public function get_help_url() {
		return 'https://wpkoi.com/wpkoi-elementor-templates-demo/elements/button/';
	}

	protected function register_controls() {

		$css_scheme = apply_filters(
			'wpkoi-elements/button/css-scheme',
			array(
				'container'    => '.wpkoi-button__container',
				'button'       => '.wpkoi-button__instance',
				'plane_normal' => '.wpkoi-button__plane-normal',
				'plane_hover'  => '.wpkoi-button__plane-hover',
				'state_normal' => '.wpkoi-button__state-normal',
				'state_hover'  => '.wpkoi-button__state-hover',
				'icon_normal'  => '.wpkoi-button__state-normal .wpkoi-button__icon',
				'label_normal' => '.wpkoi-button__state-normal .wpkoi-button__label',
				'icon_hover'   => '.wpkoi-button__state-hover .wpkoi-button__icon',
				'label_hover'  => '.wpkoi-button__state-hover .wpkoi-button__label',
			)
		);

		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Button settings', 'wpkoi-elements' ),
			)
		);

		$this->start_controls_tabs( 'tabs_button_content' );

		$this->start_controls_tab(
			'tab_button_content_normal',
			array(
				'label' => esc_html__( 'Normal', 'wpkoi-elements' ),
			)
		);
		
		$this->add_control(
			'button_icon_normal_new',
			[
				'label' => esc_html__( 'Button Icon', 'wpkoi-elements' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'button_icon_normal',
				'default' => [
					'value' => 'far fa-circle',
					'library' => 'fa-regular',
				]
			]
		);

		$this->add_control(
			'button_label_normal',
			array(
				'label'       => esc_html__( 'Button Label Text', 'wpkoi-elements' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Click Me', 'wpkoi-elements' ),
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_content_hover',
			array(
				'label' => esc_html__( 'Hover', 'wpkoi-elements' ),
			)
		);
		
		$this->add_control(
			'button_icon_hover_new',
			[
				'label' => esc_html__( 'Button Icon', 'wpkoi-elements' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'button_icon_hover',
				'default' => [
					'value' => 'far fa-circle',
					'library' => 'fa-regular',
				]
			]
		);

		$this->add_control(
			'button_label_hover',
			array(
				'label'       => esc_html__( 'Button Label Text', 'wpkoi-elements' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Go', 'wpkoi-elements' ),
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'use_button_icon',
			array(
				'label'        => esc_html__( 'Use Icon?', 'wpkoi-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'wpkoi-elements' ),
				'label_off'    => esc_html__( 'No', 'wpkoi-elements' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'button_icon_position',
			array(
				'label'   => esc_html__( 'Icon Position', 'wpkoi-elements' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'left'   => esc_html__( 'Left', 'wpkoi-elements' ),
					'top'    => esc_html__( 'Top', 'wpkoi-elements' ),
					'right'  => esc_html__( 'Right', 'wpkoi-elements' ),
					'bottom' => esc_html__( 'Bottom', 'wpkoi-elements' ),
				),
				'default'     => 'left',
				'render_type' => 'template',
				'condition' => array(
					'use_button_icon' => 'yes',
				),
			)
		);

		$this->add_control(
			'button_url',
			array(
				'label' => esc_html__( 'Button Link', 'wpkoi-elements' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'http://your-link.com',
				'default' => array(
					'url' => '#',
				),
				'separator' => 'before',
				'dynamic' => array( 'active' => true ),
			)
		);

		$effects = apply_filters(
			'wpkoi-elements/button/effects',
			array(
				'effect-0'  => esc_html__( 'None', 'wpkoi-elements' ),
				'effect-1'  => esc_html__( 'Fade', 'wpkoi-elements' ),
				'effect-2'  => esc_html__( 'Up Slide', 'wpkoi-elements' ),
				'effect-3'  => esc_html__( 'Down Slide', 'wpkoi-elements' ),
				'effect-4'  => esc_html__( 'Right Slide', 'wpkoi-elements' ),
				'effect-5'  => esc_html__( 'Left Slide', 'wpkoi-elements' ),
				'effect-6'  => esc_html__( 'Up Scale', 'wpkoi-elements' ),
				'effect-7'  => esc_html__( 'Down Scale', 'wpkoi-elements' ),
				'effect-8'  => esc_html__( 'Top Diagonal Slide', 'wpkoi-elements' ),
				'effect-9'  => esc_html__( 'Bottom Diagonal Slide', 'wpkoi-elements' ),
				'effect-10' => esc_html__( 'Right Rayen', 'wpkoi-elements' ),
				'effect-11' => esc_html__( 'Left Rayen', 'wpkoi-elements' ),
			)
		);

		$this->add_control(
			'hover_effect',
			array(
				'label'   => esc_html__( 'Hover Effect', 'wpkoi-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'effect-0',
				'options' => $effects,
			)
		);

		$this->end_controls_section();

		/**
		 * General Style Section
		 */
		$this->start_controls_section(
			'section_button_general_style',
			array(
				'label'      => esc_html__( 'Button', 'wpkoi-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'custom_size',
			array(
				'label'        => esc_html__( 'Custom Size', 'wpkoi-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'wpkoi-elements' ),
				'label_off'    => esc_html__( 'No', 'wpkoi-elements' ),
				'return_value' => 'yes',
				'default'      => 'false',
			)
		);

		$this->add_responsive_control(
			'button_custom_width',
			array(
				'label'      => esc_html__( 'Custom Width', 'wpkoi-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', 'em', 'rem', '%', 'vw'
				),
				'range'      => array(
					'px' => array(
						'min' => 40,
						'max' => 1000,
					),
					'%' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['button'] => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'custom_size' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'button_custom_height',
			array(
				'label'      => esc_html__( 'Custom Height', 'wpkoi-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', 'em', 'rem', '%', 'vw'
				),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 1000,
					),
					'%' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['button'] => 'height: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'custom_size' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'button_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'wpkoi-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'flex-start',
				'options' => array(
					'flex-start'    => array(
						'title' => esc_html__( 'Left', 'wpkoi-elements' ),
						'icon'  => 'fas fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'wpkoi-elements' ),
						'icon'  => 'fas fa-align-center',
					),
					'flex-end' => array(
						'title' => esc_html__( 'Right', 'wpkoi-elements' ),
						'icon'  => 'fas fa-align-right',
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['container'] => 'justify-content: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'button_margin',
			array(
				'label'      => __( 'Margin', 'wpkoi-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', 'rem', '%', 'vw' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['button'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_general_styles' );

		$this->start_controls_tab(
			'tab_general_normal',
			array(
				'label' => esc_html__( 'Normal', 'wpkoi-elements' ),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'normal_button_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['button'],
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'normal_border',
				'label'       => esc_html__( 'Border', 'wpkoi-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'  => '{{WRAPPER}} ' . $css_scheme['button'],
			)
		);

		$this->add_responsive_control(
			'normal_border_radius',
			array(
				'label'      => __( 'Border Radius', 'wpkoi-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['button'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'button_padding',
			array(
				'label'      => __( 'Padding', 'wpkoi-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', 'rem', '%', 'vw' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['state_normal'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'normal_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['button'],
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_general_hover',
			array(
				'label' => esc_html__( 'Hover', 'wpkoi-elements' ),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'hover_button_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'hover_border',
				'label'       => esc_html__( 'Border', 'wpkoi-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'  => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover',
			)
		);

		$this->add_responsive_control(
			'hover_border_radius',
			array(
				'label'      => __( 'Border Radius', 'wpkoi-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['button'] . ':hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'hover_button_padding',
			array(
				'label'      => __( 'Padding', 'wpkoi-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', 'rem', '%', 'vw' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['state_hover'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'hover_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		/**
		 * Plane Style Section
		 */
		$this->start_controls_section(
			'section_button_plane_style',
			array(
				'label'      => esc_html__( 'Background', 'wpkoi-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->start_controls_tabs( 'tabs_plane_styles' );

		$this->start_controls_tab(
			'tab_plane_normal',
			array(
				'label' => esc_html__( 'Normal', 'wpkoi-elements' ),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'normal_plane_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['plane_normal'],
				'fields_options' => array(
					'color' => array(
					),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'normal_plane_border',
				'label'       => esc_html__( 'Border', 'wpkoi-elements' ),
				'placeholder' => '1px',
				'selector'  => '{{WRAPPER}} ' . $css_scheme['plane_normal'],
			)
		);

		$this->add_responsive_control(
			'normal_plane_border_radius',
			array(
				'label'      => __( 'Border Radius', 'wpkoi-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['plane_normal'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'normal_plane_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['plane_normal'],
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_plane_hover',
			array(
				'label' => esc_html__( 'Hover', 'wpkoi-elements' ),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'plane_hover_background',
				'selector' => '{{WRAPPER}} ' . $css_scheme['plane_hover'],
				'fields_options' => array(
					'color' => array(
					),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'plane_hover_border',
				'label'       => esc_html__( 'Border', 'wpkoi-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['plane_hover'],
			)
		);

		$this->add_responsive_control(
			'plane_hover_border_radius',
			array(
				'label'      => __( 'Border Radius', 'wpkoi-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['plane_hover'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'hover_plane_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_scheme['plane_hover'],
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		/**
		 * Icon Style Section
		 */
		$this->start_controls_section(
			'section_button_icon_style',
			array(
				'label'      => esc_html__( 'Icon', 'wpkoi-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->start_controls_tabs( 'tabs_icon_styles' );

		$this->start_controls_tab(
			'tab_icon_normal',
			array(
				'label' => esc_html__( 'Normal', 'wpkoi-elements' ),
			)
		);

		$this->add_control(
			'normal_icon_color',
			array(
				'label' => esc_html__( 'Color', 'wpkoi-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['icon_normal'] . ' i' => 'color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['icon_normal'] . ' svg' => 'fill: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'normal_icon_font_size',
			array(
				'label'      => esc_html__( 'Font Size', 'wpkoi-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', 'em', 'rem', '%', 'vw'
				),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['icon_normal'] . ' i' => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} ' . $css_scheme['icon_normal'] . ' svg' => 'width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'normal_icon_box_width',
			array(
				'label'      => esc_html__( 'Icon Box Width', 'wpkoi-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', 'em', 'rem', '%', 'vw'
				),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 200,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['icon_normal'] => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'normal_icon_box_height',
			array(
				'label'      => esc_html__( 'Icon Box Height', 'wpkoi-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', 'em', 'rem', '%', 'vw'
				),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 200,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['icon_normal'] => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'normal_icon_box_color',
			array(
				'label' => esc_html__( 'Icon Box Color', 'wpkoi-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['icon_normal'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'normal_icon_box_border',
				'label'       => esc_html__( 'Border', 'wpkoi-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['icon_normal'],
			)
		);

		$this->add_control(
			'normal_icon_box_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'wpkoi-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['icon_normal'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'normal_icon_margin',
			array(
				'label'      => __( 'Margin', 'wpkoi-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', 'rem', '%', 'vw' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['icon_normal'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_icon_hover',
			array(
				'label' => esc_html__( 'Hover', 'wpkoi-elements' ),
			)
		);

		$this->add_control(
			'hover_icon_color',
			array(
				'label' => esc_html__( 'Color', 'wpkoi-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['icon_hover'] . ' i' => 'color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_scheme['icon_hover'] . ' svg' => 'fill: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'hover_icon_font_size',
			array(
				'label'      => esc_html__( 'Font Size', 'wpkoi-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', 'em', 'rem', '%', 'vw'
				),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['icon_hover'] . ' i' => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} ' . $css_scheme['icon_hover'] . ' svg' => 'width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'hover_icon_box_width',
			array(
				'label'      => esc_html__( 'Icon Box Width', 'wpkoi-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', 'em', 'rem', '%', 'vw'
				),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 200,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['icon_hover'] => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'hover_icon_box_height',
			array(
				'label'      => esc_html__( 'Icon Box Height', 'wpkoi-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px', 'em', 'rem', '%', 'vw'
				),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 200,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['icon_hover'] => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'hover_icon_box_color',
			array(
				'label' => esc_html__( 'Icon Box Color', 'wpkoi-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['icon_hover'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'hover_icon_box_border',
				'label'       => esc_html__( 'Border', 'wpkoi-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_scheme['icon_hover'],
			)
		);

		$this->add_control(
			'hover_icon_box_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'wpkoi-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['icon_hover'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'hover_icon_margin',
			array(
				'label'      => __( 'Margin', 'wpkoi-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', 'rem', '%', 'vw' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['icon_hover'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		/**
		 * Label Style Section
		 */
		$this->start_controls_section(
			'section_button_label_style',
			array(
				'label'      => esc_html__( 'Text', 'wpkoi-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->start_controls_tabs( 'tabs_label_styles' );

		$this->start_controls_tab(
			'tab_label_normal',
			array(
				'label' => esc_html__( 'Normal', 'wpkoi-elements' ),
			)
		);

		$this->add_control(
			'normal_label_color',
			array(
				'label' => esc_html__( 'Color', 'wpkoi-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['label_normal'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'normal_label_typography',
				'selector' => '{{WRAPPER}}  ' . $css_scheme['label_normal'],
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'normal_label_typography_shadow',
				'selector' => '{{WRAPPER}}  ' . $css_scheme['label_normal'],
			)
		);

		$this->add_group_control(
			Group_Control_Text_Stroke::get_type(),
			array(
				'name'     => 'normal_label_typography_stroke',
				'selector' => '{{WRAPPER}}  ' . $css_scheme['label_normal'],
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_label_hover',
			array(
				'label' => esc_html__( 'Hover', 'wpkoi-elements' ),
			)
		);

		$this->add_control(
			'hover_label_color',
			array(
				'label' => esc_html__( 'Color', 'wpkoi-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['label_hover'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'hover_label_typography',
				'selector' => '{{WRAPPER}}  ' . $css_scheme['label_hover'],
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'hover_label_typography_shadow',
				'selector' => '{{WRAPPER}}  ' . $css_scheme['label_hover'],
			)
		);

		$this->add_group_control(
			Group_Control_Text_Stroke::get_type(),
			array(
				'name'     => 'hover_label_typography_stroke',
				'selector' => '{{WRAPPER}}  ' . $css_scheme['label_hover'],
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'label_margin',
			array(
				'label'      => __( 'Margin', 'wpkoi-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', 'rem', '%', 'vw' ),
				'separator'  => 'before',
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['label_normal'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} ' . $css_scheme['label_hover'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'label_text_alignment',
			array(
				'label'   => esc_html__( 'Text Alignment', 'wpkoi-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'left',
				'options' => array(
					'left'    => array(
						'title' => esc_html__( 'Left', 'wpkoi-elements' ),
						'icon'  => 'fas fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'wpkoi-elements' ),
						'icon'  => 'fas fa-align-center',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'wpkoi-elements' ),
						'icon'  => 'fas fa-align-right',
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['label_normal'] => 'text-align: {{VALUE}};',
					'{{WRAPPER}} ' . $css_scheme['label_hover'] => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

	}

	protected function render() {

		$settings = $this->get_settings_for_display();

		$position = $this->get_settings_for_display( 'button_icon_position' );
		$use_icon = $this->get_settings_for_display( 'use_button_icon' );
		$hover_effect = $this->get_settings_for_display( 'hover_effect' );
		
		$this->add_render_attribute( 'wpkoi-button', 'class', 'wpkoi-button__instance' );
		$this->add_render_attribute( 'wpkoi-button', 'class', 'wpkoi-button__instance--icon-' . esc_attr( $position ) );
		$this->add_render_attribute( 'wpkoi-button', 'class', 'hover-' . esc_attr( $hover_effect ) );
		
		$tag = 'div';
		
		if ( ! empty( $settings['button_url']['url'] ) ) {
			$this->add_render_attribute( 'wpkoi-button', 'href', esc_url( $settings['button_url']['url'] ) );
		
			if ( $settings['button_url']['is_external'] ) {
				$this->add_render_attribute( 'wpkoi-button', 'target', '_blank' );
			}
		
			if ( $settings['button_url']['nofollow'] ) {
				$this->add_render_attribute( 'wpkoi-button', 'rel', 'nofollow' );
			}
		
			$tag = 'a';
		}
		
		?>
		<div class="wpkoi-button__container">
			<<?php echo esc_attr($tag); ?> <?php echo $this->get_render_attribute_string( 'wpkoi-button' ); ?>>
				<div class="wpkoi-button__plane wpkoi-button__plane-normal"></div>
				<div class="wpkoi-button__plane wpkoi-button__plane-hover"></div>
				<div class="wpkoi-button__state wpkoi-button__state-normal">
					<?php
						if ( filter_var( $use_icon, FILTER_VALIDATE_BOOLEAN ) ) {
							echo '<span class="wpkoi-button__icon">';
							$migrated = isset( $settings['__fa4_migrated']['button_icon_normal_new'] );
							$is_new = empty( $settings['button_icon_normal'] );
							if ( $is_new || $migrated ) :
								Icons_Manager::render_icon( $settings['button_icon_normal_new'], [ 'aria-hidden' => 'true' ] );
							else : 
								echo '<i class="' . esc_attr( $settings['button_icon_normal'] ) . '" aria-hidden="true"></i>';
							endif;
							echo '</span>';
						}
						echo '<span class="wpkoi-button__label">' . esc_html( $settings['button_label_normal'] ) . '</span>';
					?>
				</div>
				<div class="wpkoi-button__state wpkoi-button__state-hover">
					<?php
						if ( filter_var( $use_icon, FILTER_VALIDATE_BOOLEAN ) ) {
							echo '<span class="wpkoi-button__icon">';
							$migrated = isset( $settings['__fa4_migrated']['button_icon_hover_new'] );
							$is_new = empty( $settings['button_icon_hover'] );
							if ( $is_new || $migrated ) :
								Icons_Manager::render_icon( $settings['button_icon_hover_new'], [ 'aria-hidden' => 'true' ] );
							else : 
								echo '<i class="' . esc_attr( $settings['button_icon_hover'] ) . '" aria-hidden="true"></i>';
							endif;
							echo '</span>';
						}
						echo '<span class="wpkoi-button__label">' . esc_html( $settings['button_label_hover'] ) . '</span>';
					?>
				</div>
			</<?php echo esc_attr( $tag ); ?>>
		</div>
		<?php
	}
	
	public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);

		wp_register_style('wpkoi-button',WPKOI_ELEMENTS_LITE_URL . 'elements/button/assets/button.css',false,WPKOI_ELEMENTS_LITE_VERSION);
	}

	public function get_style_depends() {
		return [ 'wpkoi-button' ];
	}
}

Plugin::instance()->widgets_manager->register( new WPKoi_Elements_Button() );