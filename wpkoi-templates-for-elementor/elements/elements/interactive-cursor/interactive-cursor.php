<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'WPKoi_Interactive_Cursor_Extension' ) ) {

	class WPKoi_Interactive_Cursor_Extension {

		private static $instance = null;

		public function init() {
						
			add_action( 'elementor/element/container/section_layout/after_section_end', [ $this, 'register_controls' ], 10, 2 );

			add_action( 'elementor/element/common/_section_style/after_section_end', [ $this, 'register_controls' ], 10, 2 );

			add_action( 'elementor/frontend/container/before_render', [ $this, 'before_render' ], 10, 3 );

			add_action( 'elementor/frontend/widget/before_render', [ $this, 'before_render' ], 10, 3 );
			
			add_action( 'elementor/frontend/before_enqueue_scripts', [ $this, 'enqueue_scripts' ], 9 );
			
		}

		public function register_controls( $obj, $args ) {

			$obj->start_controls_section(
				'wpkoi_container_interactive_cursor',
				array(
					'label' => esc_html__( 'Interactive Cursor (WPKoi)', 'wpkoi-elements' ),
					'tab'   => Elementor\Controls_Manager::TAB_ADVANCED,
				)
			);

			$obj->add_control(
				'interactive_cursor_show',
				[
					'label' => esc_html__( 'Use interactive cursor?', 'wpkoi-elements' ),
					'type'         => Elementor\Controls_Manager::SWITCHER,
					'default'      => '',
					'return_value' => 'yes',
				]
			);
			
			$obj->add_control(
				'interactive_cursor_editor_notice',
				[
					'type' => Elementor\Controls_Manager::RAW_HTML,
					'raw' => esc_html__(
						'The box displayed in the editor is only a preview. The live mouse interaction effect is active on the frontend.', 'wpkoi-elements' ),
					'content_classes' =>
						'elementor-panel-alert elementor-panel-alert-info',
					'condition' => [
						'interactive_cursor_show' => 'yes',
					],
				]
			);

			$obj->add_control(
				'interactive_cursor_editor_preview',
				[
					'label' => esc_html__( 'Show Editor Preview', 'wpkoi-elements' ),
					'type' => Elementor\Controls_Manager::SWITCHER,
					'default' => 'yes',
					'return_value' => 'yes',
					'condition' => [
						'interactive_cursor_show' => 'yes',
					],
					'render_type' => 'none',
				]
			);

			/*
			|--------------------------------------------------------------------------
			| Main Content
			|--------------------------------------------------------------------------
			*/

			$obj->add_control(
				'interactive_cursor_main_content_heading',
				[
					'label' => esc_html__( 'Main Content', 'wpkoi-elements' ),
					'type'  => Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
					'condition' => [
						'interactive_cursor_show' => 'yes',
					],
				]
			);

			$obj->add_control(
				'interactive_cursor_main_content_show',
				[
					'label' => esc_html__( 'Enable Main Content', 'wpkoi-elements' ),
					'type'         => Elementor\Controls_Manager::SWITCHER,
					'default'      => 'yes',
					'return_value' => 'yes',
					'condition' => [
						'interactive_cursor_show' => 'yes',
					],
				]
			);

			$obj->add_control(
				'interactive_cursor_main_content',
				[
					'label' => esc_html__( 'Content', 'wpkoi-elements' ),
					'type'      => Elementor\Controls_Manager::TEXTAREA,
					'default'   => '',
					'condition' => [
						'interactive_cursor_show' => 'yes',
						'interactive_cursor_main_content_show' => 'yes',
					],
					'render_type' => 'none',
				]
			);
			
			$obj->add_control(
				'interactive_cursor_content_mode',
				[
					'label' => esc_html__( 'Content Mode', 'wpkoi-elements' ),
					'type'    => Elementor\Controls_Manager::SELECT,
					'default' => 'normal',
					'options' => [

						'normal' => esc_html__(
							'Normal', 'wpkoi-elements' ),

						'circle' => esc_html__(
							'Circle Text', 'wpkoi-elements' ),
					],
					'condition' => [
						'interactive_cursor_show' => 'yes',
						'interactive_cursor_main_content_show' => 'yes',
					],
					'render_type' => 'none',
				]
			);
			
			$obj->add_control(
				'interactive_cursor_circle_radius',
				[
					'label' => esc_html__( 'Circle Radius', 'wpkoi-elements' ),
					'type' => Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px', 'vw', 'vh', 'em', 'rem', ],
					'range' => [
						'px' => [
							'min' => 20,
							'max' => 500,
						],
					],
					'default' => [
						'size' => 120,
					],
					'condition' => [
						'interactive_cursor_show' => 'yes',
						'interactive_cursor_main_content_show' => 'yes',
						'interactive_cursor_content_mode' => 'circle',
					],
					'render_type' => 'none',
				]
			);
			
			$obj->add_control(
				'interactive_cursor_circle_gap',
				[
					'label' => esc_html__( 'Character Spacing', 'wpkoi-elements' ),
					'type' => Elementor\Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 300,
							'step' => 1,
						],
					],
					'default' => [
						'size' => 0,
					],
					'condition' => [
						'interactive_cursor_show' => 'yes',
						'interactive_cursor_main_content_show' => 'yes',
						'interactive_cursor_content_mode' => 'circle',
					],
					'render_type' => 'none',
				]
			);
			
			$obj->add_control(
				'interactive_cursor_circle_start_angle',
				[
					'label' => esc_html__( 'Start Angle', 'wpkoi-elements' ),
					'type' => Elementor\Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => -360,
							'max' => 360,
							'step' => 1,
						],
					],
					'default' => [
						'size' => -90,
					],
					'condition' => [
						'interactive_cursor_show' => 'yes',
						'interactive_cursor_content_mode' => 'circle',
					],
					'render_type' => 'none',
				]
			);

			$obj->add_control(
				'interactive_cursor_circle_reverse',
				[
					'label' => esc_html__( 'Reverse Direction', 'wpkoi-elements' ),
					'type' => Elementor\Controls_Manager::SWITCHER,
					'default' => '',
					'return_value' => 'yes',
					'condition' => [
						'interactive_cursor_show' => 'yes',
						'interactive_cursor_main_content_show' => 'yes',
						'interactive_cursor_content_mode' => 'circle',
					],
					'render_type' => 'none',
				]
			);

			$obj->add_control(
				'interactive_cursor_circle_center_mode',
				[
					'label' => esc_html__( 'Center Content', 'wpkoi-elements' ),
					'type'    => Elementor\Controls_Manager::SELECT,
					'default' => 'none',
					'options' => [

						'none' => esc_html__(
							'None', 'wpkoi-elements' ),

						'icon' => esc_html__(
							'Icon', 'wpkoi-elements' ),

						'sub' => esc_html__(
							'Sub Content', 'wpkoi-elements' ),
					],
					'condition' => [
						'interactive_cursor_show' => 'yes',
						'interactive_cursor_main_content_show' => 'yes',
						'interactive_cursor_content_mode' => 'circle',
					],
					'render_type' => 'none',
				]
			);

			$obj->add_responsive_control(
				'interactive_cursor_main_content_alignment',
				[
					'label' => esc_html__( 'Alignment', 'wpkoi-elements' ),
					'type'    => Elementor\Controls_Manager::CHOOSE,
					'options' => [
						'left' => [
							'title' => esc_html__( 'Left', 'wpkoi-elements' ),
							'icon'  => 'eicon-text-align-left',
						],
						'center' => [
							'title' => esc_html__( 'Center', 'wpkoi-elements' ),
							'icon'  => 'eicon-text-align-center',
						],
						'right' => [
							'title' => esc_html__( 'Right', 'wpkoi-elements' ),
							'icon'  => 'eicon-text-align-right',
						],
					],
					'selectors' => [
						'{{WRAPPER}} .wpkoi-mi-main-content' => 'text-align: {{VALUE}};',
					],
					'condition' => [
						'interactive_cursor_show' => 'yes',
						'interactive_cursor_main_content_show' => 'yes',
						'interactive_cursor_content_mode!' => 'circle',
					],
				]
			);

			$obj->add_control(
				'interactive_cursor_main_content_color',
				[
					'label' => esc_html__( 'Color', 'wpkoi-elements' ),
					'type'  => Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .wpkoi-mi-main-content' => 'color: {{VALUE}};',
						'{{WRAPPER}} .wpkoi-mi-circle-char' => 'color: {{VALUE}};',
					],
					'condition' => [
						'interactive_cursor_show' => 'yes',
						'interactive_cursor_main_content_show' => 'yes',
					],
				]
			);

			$obj->add_group_control(
				Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'interactive_cursor_main_content_typography',
					'selector' => '{{WRAPPER}} .wpkoi-mi-main-content, {{WRAPPER}} .wpkoi-mi-circle-char',
					'condition' => [
						'interactive_cursor_show' => 'yes',
						'interactive_cursor_main_content_show' => 'yes',
					],
				]
			);

			$obj->add_group_control(
				Elementor\Group_Control_Text_Stroke::get_type(),
				[
					'name' => 'interactive_cursor_main_content_text_stroke',
					'selector' => '{{WRAPPER}} .wpkoi-mi-main-content, {{WRAPPER}} .wpkoi-mi-circle-char',
					'condition' => [
						'interactive_cursor_show' => 'yes',
						'interactive_cursor_main_content_show' => 'yes',
					],
				]
			);

			$obj->add_group_control(
				Elementor\Group_Control_Text_Shadow::get_type(),
				[
					'name' => 'interactive_cursor_main_content_text_shadow',
					'selector' => '{{WRAPPER}} .wpkoi-mi-main-content, {{WRAPPER}} .wpkoi-mi-circle-char',
					'condition' => [
						'interactive_cursor_show' => 'yes',
						'interactive_cursor_main_content_show' => 'yes',
					],
				]
			);

			$obj->add_responsive_control(
				'interactive_cursor_main_content_padding',
				[
					'label' => esc_html__( 'Padding', 'wpkoi-elements' ),
					'type'       => Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'vw', 'vh' ],
					'selectors'  => [
						'{{WRAPPER}} .wpkoi-mi-main-content' =>
							'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition' => [
						'interactive_cursor_show' => 'yes',
						'interactive_cursor_main_content_show' => 'yes',
						'interactive_cursor_content_mode!' => 'circle',
					],
				]
			);

			/*
			|--------------------------------------------------------------------------
			| Sub Content
			|--------------------------------------------------------------------------
			*/

			$obj->add_control(
				'interactive_cursor_sub_content_heading',
				[
					'label' => esc_html__( 'Sub Content', 'wpkoi-elements' ),
					'type'  => Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
					'conditions' => [
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'interactive_cursor_show',
								'operator' => '===',
								'value' => 'yes',
							],
							[
								'relation' => 'or',
								'terms' => [
									[
										'name' => 'interactive_cursor_content_mode',
										'operator' => '===',
										'value' => 'normal',
									],
									[
										'relation' => 'and',
										'terms' => [
											[
												'name' => 'interactive_cursor_content_mode',
												'operator' => '===',
												'value' => 'circle',
											],
											[
												'name' => 'interactive_cursor_circle_center_mode',
												'operator' => '===',
												'value' => 'sub',
											],
										],
									],
								],
							],
						],
					],
				]
			);

			$obj->add_control(
				'interactive_cursor_sub_content_show',
				[
					'label' => esc_html__( 'Enable Sub Content', 'wpkoi-elements' ),
					'type'         => Elementor\Controls_Manager::SWITCHER,
					'default'      => '',
					'return_value' => 'yes',
					'conditions' => [
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'interactive_cursor_show',
								'operator' => '===',
								'value' => 'yes',
							],
							[
								'relation' => 'or',
								'terms' => [
									[
										'name' => 'interactive_cursor_content_mode',
										'operator' => '===',
										'value' => 'normal',
									],
									[
										'relation' => 'and',
										'terms' => [
											[
												'name' => 'interactive_cursor_content_mode',
												'operator' => '===',
												'value' => 'circle',
											],
											[
												'name' => 'interactive_cursor_circle_center_mode',
												'operator' => '===',
												'value' => 'sub',
											],
										],
									],
								],
							],
						],
					],
				]
			);

			$obj->add_control(
				'interactive_cursor_sub_content',
				[
					'label' => esc_html__( 'Content', 'wpkoi-elements' ),
					'type'      => Elementor\Controls_Manager::TEXTAREA,
					'default'   => '',
					'conditions' => [
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'interactive_cursor_show',
								'operator' => '===',
								'value' => 'yes',
							],
							[
								'name' => 'interactive_cursor_sub_content_show',
								'operator' => '===',
								'value' => 'yes',
							],
							[
								'relation' => 'or',
								'terms' => [
									[
										'name' => 'interactive_cursor_content_mode',
										'operator' => '===',
										'value' => 'normal',
									],
									[
										'relation' => 'and',
										'terms' => [
											[
												'name' => 'interactive_cursor_content_mode',
												'operator' => '===',
												'value' => 'circle',
											],
											[
												'name' => 'interactive_cursor_circle_center_mode',
												'operator' => '===',
												'value' => 'sub',
											],
										],
									],
								],
							],
						],
					],
					'render_type' => 'none',
				]
			);

			$obj->add_responsive_control(
				'interactive_cursor_sub_content_alignment',
				[
					'label' => esc_html__( 'Alignment', 'wpkoi-elements' ),
					'type'    => Elementor\Controls_Manager::CHOOSE,
					'options' => [
						'left' => [
							'title' => esc_html__( 'Left', 'wpkoi-elements' ),
							'icon'  => 'eicon-text-align-left',
						],
						'center' => [
							'title' => esc_html__( 'Center', 'wpkoi-elements' ),
							'icon'  => 'eicon-text-align-center',
						],
						'right' => [
							'title' => esc_html__( 'Right', 'wpkoi-elements' ),
							'icon'  => 'eicon-text-align-right',
						],
					],
					'selectors' => [
						'{{WRAPPER}} .wpkoi-mi-sub-content' => 'text-align: {{VALUE}};',
					],
					'conditions' => [
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'interactive_cursor_show',
								'operator' => '===',
								'value' => 'yes',
							],
							[
								'name' => 'interactive_cursor_sub_content_show',
								'operator' => '===',
								'value' => 'yes',
							],
							[
								'relation' => 'or',
								'terms' => [
									[
										'name' => 'interactive_cursor_content_mode',
										'operator' => '===',
										'value' => 'normal',
									],
									[
										'relation' => 'and',
										'terms' => [
											[
												'name' => 'interactive_cursor_content_mode',
												'operator' => '===',
												'value' => 'circle',
											],
											[
												'name' => 'interactive_cursor_circle_center_mode',
												'operator' => '===',
												'value' => 'sub',
											],
										],
									],
								],
							],
						],
					],
				]
			);

			$obj->add_control(
				'interactive_cursor_sub_content_color',
				[
					'label' => esc_html__( 'Color', 'wpkoi-elements' ),
					'type'  => Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .wpkoi-mi-sub-content' => 'color: {{VALUE}};',
					],
					'conditions' => [
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'interactive_cursor_show',
								'operator' => '===',
								'value' => 'yes',
							],
							[
								'name' => 'interactive_cursor_sub_content_show',
								'operator' => '===',
								'value' => 'yes',
							],
							[
								'relation' => 'or',
								'terms' => [
									[
										'name' => 'interactive_cursor_content_mode',
										'operator' => '===',
										'value' => 'normal',
									],
									[
										'relation' => 'and',
										'terms' => [
											[
												'name' => 'interactive_cursor_content_mode',
												'operator' => '===',
												'value' => 'circle',
											],
											[
												'name' => 'interactive_cursor_circle_center_mode',
												'operator' => '===',
												'value' => 'sub',
											],
										],
									],
								],
							],
						],
					],
				]
			);

			$obj->add_group_control(
				Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'interactive_cursor_sub_content_typography',
					'selector' => '{{WRAPPER}} .wpkoi-mi-sub-content',
					'conditions' => [
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'interactive_cursor_show',
								'operator' => '===',
								'value' => 'yes',
							],
							[
								'name' => 'interactive_cursor_sub_content_show',
								'operator' => '===',
								'value' => 'yes',
							],
							[
								'relation' => 'or',
								'terms' => [
									[
										'name' => 'interactive_cursor_content_mode',
										'operator' => '===',
										'value' => 'normal',
									],
									[
										'relation' => 'and',
										'terms' => [
											[
												'name' => 'interactive_cursor_content_mode',
												'operator' => '===',
												'value' => 'circle',
											],
											[
												'name' => 'interactive_cursor_circle_center_mode',
												'operator' => '===',
												'value' => 'sub',
											],
										],
									],
								],
							],
						],
					],
				]
			);

			$obj->add_group_control(
				Elementor\Group_Control_Text_Stroke::get_type(),
				[
					'name' => 'interactive_cursor_sub_content_text_stroke',
					'selector' => '{{WRAPPER}} .wpkoi-mi-sub-content',
					'conditions' => [
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'interactive_cursor_show',
								'operator' => '===',
								'value' => 'yes',
							],
							[
								'name' => 'interactive_cursor_sub_content_show',
								'operator' => '===',
								'value' => 'yes',
							],
							[
								'relation' => 'or',
								'terms' => [
									[
										'name' => 'interactive_cursor_content_mode',
										'operator' => '===',
										'value' => 'normal',
									],
									[
										'relation' => 'and',
										'terms' => [
											[
												'name' => 'interactive_cursor_content_mode',
												'operator' => '===',
												'value' => 'circle',
											],
											[
												'name' => 'interactive_cursor_circle_center_mode',
												'operator' => '===',
												'value' => 'sub',
											],
										],
									],
								],
							],
						],
					],
				]
			);

			$obj->add_group_control(
				Elementor\Group_Control_Text_Shadow::get_type(),
				[
					'name' => 'interactive_cursor_sub_content_text_shadow',
					'selector' => '{{WRAPPER}} .wpkoi-mi-sub-content',
					'conditions' => [
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'interactive_cursor_show',
								'operator' => '===',
								'value' => 'yes',
							],
							[
								'name' => 'interactive_cursor_sub_content_show',
								'operator' => '===',
								'value' => 'yes',
							],
							[
								'relation' => 'or',
								'terms' => [
									[
										'name' => 'interactive_cursor_content_mode',
										'operator' => '===',
										'value' => 'normal',
									],
									[
										'relation' => 'and',
										'terms' => [
											[
												'name' => 'interactive_cursor_content_mode',
												'operator' => '===',
												'value' => 'circle',
											],
											[
												'name' => 'interactive_cursor_circle_center_mode',
												'operator' => '===',
												'value' => 'sub',
											],
										],
									],
								],
							],
						],
					],
				]
			);

			$obj->add_responsive_control(
				'interactive_cursor_sub_content_padding',
				[
					'label' => esc_html__( 'Padding', 'wpkoi-elements' ),
					'type'       => Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'vw', 'vh' ],
					'selectors'  => [
						'{{WRAPPER}} .wpkoi-mi-sub-content' =>
							'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'conditions' => [
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'interactive_cursor_show',
								'operator' => '===',
								'value' => 'yes',
							],
							[
								'name' => 'interactive_cursor_sub_content_show',
								'operator' => '===',
								'value' => 'yes',
							],
							[
								'relation' => 'or',
								'terms' => [
									[
										'name' => 'interactive_cursor_content_mode',
										'operator' => '===',
										'value' => 'normal',
									],
									[
										'relation' => 'and',
										'terms' => [
											[
												'name' => 'interactive_cursor_content_mode',
												'operator' => '===',
												'value' => 'circle',
											],
											[
												'name' => 'interactive_cursor_circle_center_mode',
												'operator' => '===',
												'value' => 'sub',
											],
										],
									],
								],
							],
						],
					],
				]
			);

			/*
			|--------------------------------------------------------------------------
			| Icon
			|--------------------------------------------------------------------------
			*/

			$obj->add_control(
				'interactive_cursor_icon_heading',
				[
					'label' => esc_html__( 'Icon', 'wpkoi-elements' ),
					'type'  => Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
					'conditions' => [
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'interactive_cursor_show',
								'operator' => '===',
								'value' => 'yes',
							],
							[
								'relation' => 'or',
								'terms' => [
									[
										'name' => 'interactive_cursor_content_mode',
										'operator' => '===',
										'value' => 'normal',
									],
									[
										'relation' => 'and',
										'terms' => [
											[
												'name' => 'interactive_cursor_content_mode',
												'operator' => '===',
												'value' => 'circle',
											],
											[
												'name' => 'interactive_cursor_circle_center_mode',
												'operator' => '===',
												'value' => 'icon',
											],
										],
									],
								],
							],
						],
					],
				]
			);

			$obj->add_control(
				'interactive_cursor_icon_show',
				[
					'label' => esc_html__( 'Enable Icon', 'wpkoi-elements' ),
					'type'         => Elementor\Controls_Manager::SWITCHER,
					'default'      => '',
					'return_value' => 'yes',
					'conditions' => [
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'interactive_cursor_show',
								'operator' => '===',
								'value' => 'yes',
							],
							[
								'relation' => 'or',
								'terms' => [
									[
										'name' => 'interactive_cursor_content_mode',
										'operator' => '===',
										'value' => 'normal',
									],
									[
										'relation' => 'and',
										'terms' => [
											[
												'name' => 'interactive_cursor_content_mode',
												'operator' => '===',
												'value' => 'circle',
											],
											[
												'name' => 'interactive_cursor_circle_center_mode',
												'operator' => '===',
												'value' => 'icon',
											],
										],
									],
								],
							],
						],
					],
				]
			);

			$obj->add_control(
				'interactive_cursor_icon',
				[
					'label' => esc_html__( 'Icon', 'wpkoi-elements' ),
					'type'  => Elementor\Controls_Manager::ICONS,
					'conditions' => [
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'interactive_cursor_show',
								'operator' => '===',
								'value' => 'yes',
							],
							[
								'name' => 'interactive_cursor_icon_show',
								'operator' => '===',
								'value' => 'yes',
							],
							[
								'relation' => 'or',
								'terms' => [
									[
										'name' => 'interactive_cursor_content_mode',
										'operator' => '===',
										'value' => 'normal',
									],
									[
										'relation' => 'and',
										'terms' => [
											[
												'name' => 'interactive_cursor_content_mode',
												'operator' => '===',
												'value' => 'circle',
											],
											[
												'name' => 'interactive_cursor_circle_center_mode',
												'operator' => '===',
												'value' => 'icon',
											],
										],
									],
								],
							],
						],
					],
				]
			);
			
			$obj->add_control(
				'interactive_cursor_icon_position',
				[
					'label' => esc_html__( 'Icon Position', 'wpkoi-elements' ),
					'type'    => Elementor\Controls_Manager::SELECT,
					'default' => 'top',
					'options' => [
						'top' => esc_html__(
							'Top', 'wpkoi-elements' ),

						'left' => esc_html__(
							'Left', 'wpkoi-elements' ),

						'right' => esc_html__(
							'Right', 'wpkoi-elements' ),

						'bottom' => esc_html__(
							'Bottom', 'wpkoi-elements' ),
					],
					'condition' => [
						'interactive_cursor_show' => 'yes',
						'interactive_cursor_icon_show' => 'yes',
						'interactive_cursor_content_mode!' => 'circle',
					],
				]
			);

			$obj->add_responsive_control(
				'interactive_cursor_icon_alignment',
				[
					'label' => esc_html__( 'Alignment', 'wpkoi-elements' ),
					'type'    => Elementor\Controls_Manager::CHOOSE,
					'options' => [
						'left' => [
							'title' => esc_html__( 'Left', 'wpkoi-elements' ),
							'icon'  => 'eicon-text-align-left',
						],
						'center' => [
							'title' => esc_html__( 'Center', 'wpkoi-elements' ),
							'icon'  => 'eicon-text-align-center',
						],
						'right' => [
							'title' => esc_html__( 'Right', 'wpkoi-elements' ),
							'icon'  => 'eicon-text-align-right',
						],
					],
					'selectors_dictionary' => [
						'left' =>
							'justify-content:flex-start;',

						'center' =>
							'justify-content:center;',

						'right' =>
							'justify-content:flex-end;',
					],
					'selectors' => [
						'{{WRAPPER}} .wpkoi-mi-icon-wrapper' =>
							'{{VALUE}}',
					],
					'condition' => [
						'interactive_cursor_show' => 'yes',
						'interactive_cursor_icon_show' => 'yes',
						'interactive_cursor_content_mode!' => 'circle',
					],
				]
			);

			$obj->add_control(
				'interactive_cursor_icon_color',
				[
					'label' => esc_html__( 'Color', 'wpkoi-elements' ),
					'type'  => Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .wpkoi-mi-icon' => 'color: {{VALUE}};',
						'{{WRAPPER}} .wpkoi-mi-icon svg' => 'fill: {{VALUE}};',
					],
					'conditions' => [
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'interactive_cursor_show',
								'operator' => '===',
								'value' => 'yes',
							],
							[
								'name' => 'interactive_cursor_icon_show',
								'operator' => '===',
								'value' => 'yes',
							],
							[
								'relation' => 'or',
								'terms' => [
									[
										'name' => 'interactive_cursor_content_mode',
										'operator' => '===',
										'value' => 'normal',
									],
									[
										'relation' => 'and',
										'terms' => [
											[
												'name' => 'interactive_cursor_content_mode',
												'operator' => '===',
												'value' => 'circle',
											],
											[
												'name' => 'interactive_cursor_circle_center_mode',
												'operator' => '===',
												'value' => 'icon',
											],
										],
									],
								],
							],
						],
					],
				]
			);

			$obj->add_responsive_control(
				'interactive_cursor_icon_size',
				[
					'label' => esc_html__( 'Size', 'wpkoi-elements' ),
					'type'  => Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px', 'em', 'rem', 'vw', 'vh' ],
					'range' => [
						'px' => [
							'min' => 1,
							'max' => 300,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .wpkoi-mi-icon' => 'font-size: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .wpkoi-mi-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					],
					'conditions' => [
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'interactive_cursor_show',
								'operator' => '===',
								'value' => 'yes',
							],
							[
								'name' => 'interactive_cursor_icon_show',
								'operator' => '===',
								'value' => 'yes',
							],
							[
								'relation' => 'or',
								'terms' => [
									[
										'name' => 'interactive_cursor_content_mode',
										'operator' => '===',
										'value' => 'normal',
									],
									[
										'relation' => 'and',
										'terms' => [
											[
												'name' => 'interactive_cursor_content_mode',
												'operator' => '===',
												'value' => 'circle',
											],
											[
												'name' => 'interactive_cursor_circle_center_mode',
												'operator' => '===',
												'value' => 'icon',
											],
										],
									],
								],
							],
						],
					],
				]
			);

			$obj->add_responsive_control(
				'interactive_cursor_icon_padding',
				[
					'label' => esc_html__( 'Padding', 'wpkoi-elements' ),
					'type'       => Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'vw', 'vh' ],
					'selectors'  => [
						'{{WRAPPER}} .wpkoi-mi-icon-wrapper' =>
							'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'conditions' => [
						'relation' => 'and',
						'terms' => [
							[
								'name' => 'interactive_cursor_show',
								'operator' => '===',
								'value' => 'yes',
							],
							[
								'name' => 'interactive_cursor_icon_show',
								'operator' => '===',
								'value' => 'yes',
							],
							[
								'relation' => 'or',
								'terms' => [
									[
										'name' => 'interactive_cursor_content_mode',
										'operator' => '===',
										'value' => 'normal',
									],
									[
										'relation' => 'and',
										'terms' => [
											[
												'name' => 'interactive_cursor_content_mode',
												'operator' => '===',
												'value' => 'circle',
											],
											[
												'name' => 'interactive_cursor_circle_center_mode',
												'operator' => '===',
												'value' => 'icon',
											],
										],
									],
								],
							],
						],
					],
				]
			);

			/*
			|--------------------------------------------------------------------------
			| Box Style
			|--------------------------------------------------------------------------
			*/

			$obj->add_control(
				'interactive_cursor_box_style_heading',
				[
					'label' => esc_html__( 'Box Style', 'wpkoi-elements' ),
					'type'  => Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
					'condition' => [
						'interactive_cursor_show' => 'yes',
					],
				]
			);

			$obj->add_control(
				'interactive_cursor_box_style_show',
				[
					'label' => esc_html__( 'Enable Box Style', 'wpkoi-elements' ),
					'type'         => Elementor\Controls_Manager::SWITCHER,
					'default'      => 'yes',
					'return_value' => 'yes',
					'condition' => [
						'interactive_cursor_show' => 'yes',
					],
				]
			);

			$obj->add_group_control(
				Elementor\Group_Control_Background::get_type(),
				[
					'name'     => 'interactive_cursor_background',
					'label' => esc_html__( 'Background', 'wpkoi-elements' ),
					'types'    => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} .wpkoi-mouse-anim',
					'condition' => [
						'interactive_cursor_show' => 'yes',
						'interactive_cursor_box_style_show' => 'yes',
					],
				]
			);

			$obj->add_responsive_control(
				'interactive_cursor_box_padding',
				[
					'label' => esc_html__( 'Padding', 'wpkoi-elements' ),
					'type'       => Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'vw', 'vh' ],
					'selectors'  => [
						'{{WRAPPER}} .wpkoi-mouse-anim' =>
							'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition' => [
						'interactive_cursor_show' => 'yes',
						'interactive_cursor_box_style_show' => 'yes',
					],
				]
			);

			$obj->add_group_control(
				Elementor\Group_Control_Border::get_type(),
				[
					'name'     => 'interactive_cursor_border',
					'selector' => '{{WRAPPER}} .wpkoi-mouse-anim',
					'condition' => [
						'interactive_cursor_show' => 'yes',
						'interactive_cursor_box_style_show' => 'yes',
					],
				]
			);

			$obj->add_responsive_control(
				'interactive_cursor_border_radius',
				[
					'label' => esc_html__( 'Border Radius', 'wpkoi-elements' ),
					'type'       => Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors'  => [
						'{{WRAPPER}} .wpkoi-mouse-anim' =>
							'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition' => [
						'interactive_cursor_show' => 'yes',
						'interactive_cursor_box_style_show' => 'yes',
					],
				]
			);

			$obj->add_group_control(
				Elementor\Group_Control_Box_Shadow::get_type(),
				[
					'name'     => 'interactive_cursor_box_shadow',
					'selector' => '{{WRAPPER}} .wpkoi-mouse-anim',
					'condition' => [
						'interactive_cursor_show' => 'yes',
						'interactive_cursor_box_style_show' => 'yes',
					],
				]
			);
			
			$obj->add_responsive_control(
				'interactive_cursor_box_max_width',
				[
					'label' => esc_html__( 'Max Width', 'wpkoi-elements' ),
					'type' => Elementor\Controls_Manager::SLIDER,
					'size_units' => [
						'px',
						'em',
						'rem',
						'vw',
						'vh',
					],
					'range' => [
						'px' => [
							'min' => 20,
							'max' => 1200,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .wpkoi-mouse-anim' =>
							'max-width: {{SIZE}}{{UNIT}};',
					],
					'condition' => [
						'interactive_cursor_show' => 'yes',
						'interactive_cursor_box_style_show' => 'yes',
					],
				]
			);

			/*
			|--------------------------------------------------------------------------
			| Position & Animation
			|--------------------------------------------------------------------------
			*/

			$obj->add_control(
				'interactive_cursor_position',
				[
					'label' => esc_html__( 'Box Position', 'wpkoi-elements' ),
					'type'    => Elementor\Controls_Manager::SELECT,
					'default' => 'top-center',
					'options' => [
						'top-left'      => esc_html__( 'Top Left', 'wpkoi-elements' ),
						'top-center'    => esc_html__( 'Top Center', 'wpkoi-elements' ),
						'top-right'     => esc_html__( 'Top Right', 'wpkoi-elements' ),

						'center-left'   => esc_html__( 'Center Left', 'wpkoi-elements' ),
						'center-center' => esc_html__( 'Center Center', 'wpkoi-elements' ),
						'center-right'  => esc_html__( 'Center Right', 'wpkoi-elements' ),

						'bottom-left'   => esc_html__( 'Bottom Left', 'wpkoi-elements' ),
						'bottom-center' => esc_html__( 'Bottom Center', 'wpkoi-elements' ),
						'bottom-right'  => esc_html__( 'Bottom Right', 'wpkoi-elements' ),
					],
					'condition' => [
						'interactive_cursor_show' => 'yes',
					],
					'render_type' => 'none',
				]
			);

			$obj->add_control(
				'interactive_cursor_animation',
				[
					'label' => esc_html__( 'Entrance Animation', 'wpkoi-elements' ),
					'type'    => Elementor\Controls_Manager::SELECT,
					'default' => 'default',
					'options' => [
						'default'      => esc_html__( 'Default', 'wpkoi-elements' ),
						'scale'      => esc_html__( 'Scale', 'wpkoi-elements' ),
						'blur'      => esc_html__( 'Blur', 'wpkoi-elements' ),
						'left-slide'   => esc_html__( 'Left Slide', 'wpkoi-elements' ),
						'right-slide'  => esc_html__( 'Right Slide', 'wpkoi-elements' ),
						'top-slide'    => esc_html__( 'Top Slide', 'wpkoi-elements' ),
						'bottom-slide' => esc_html__( 'Bottom Slide', 'wpkoi-elements' ),
						'perspective' => esc_html__( 'Perspective', 'wpkoi-elements' ),
						'clipscale' => esc_html__( 'Clip + Scale', 'wpkoi-elements' ),
					],
					'condition' => [
						'interactive_cursor_show' => 'yes',
					],
					'render_type' => 'none',
				]
			);

			$obj->add_control(
				'interactive_cursor_duration',
				[
					'label' => esc_html__( 'Animation Duration (ms)', 'wpkoi-elements' ),
					'type'       => Elementor\Controls_Manager::NUMBER,
					'default'    => 300,
					'min'        => 50,
					'max'        => 5000,
					'step'       => 50,
					'condition' => [
						'interactive_cursor_show' => 'yes',
					],
					'render_type' => 'none',
				]
			);
			
			$obj->add_control(
				'interactive_cursor_hide_cursor',
				[
					'label' => esc_html__( 'Hide Default Cursor', 'wpkoi-elements' ),
					'type'         => Elementor\Controls_Manager::SWITCHER,
					'default'      => '',
					'return_value' => 'yes',
					'condition' => [
						'interactive_cursor_show' => 'yes',
					],
					'render_type' => 'none',
				]
			);
			
			$obj->add_control(
				'interactive_cursor_disable_tablet',
				[
					'label' => esc_html__( 'Disable on Tablet', 'wpkoi-elements' ),
					'type' => Elementor\Controls_Manager::SWITCHER,
					'default' => '',
					'return_value' => 'yes',
					'condition' => [
						'interactive_cursor_show' => 'yes',
					],
					'render_type' => 'none',
				]
			);

			$obj->add_control(
				'interactive_cursor_disable_mobile',
				[
					'label' => esc_html__( 'Disable on Mobile', 'wpkoi-elements' ),
					'type' => Elementor\Controls_Manager::SWITCHER,
					'default' => '',
					'return_value' => 'yes',
					'condition' => [
						'interactive_cursor_show' => 'yes',
					],
					'render_type' => 'none',
				]
			);
			
			/*
			|--------------------------------------------------------------------------
			| Effects
			|--------------------------------------------------------------------------
			*/

			$obj->add_control(
				'interactive_cursor_effects_heading',
				[
					'label' => esc_html__( 'Effects', 'wpkoi-elements' ),
					'type' => Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
					'condition' => [
						'interactive_cursor_show' => 'yes',
					],
				]
			);

			/*
			|--------------------------------------------------------------------------
			| Pulse
			|--------------------------------------------------------------------------
			*/

			$obj->add_control(
				'interactive_cursor_pulse',
				[
					'label' => esc_html__( 'Pulse Animation', 'wpkoi-elements' ),
					'type' => Elementor\Controls_Manager::SWITCHER,
					'default' => '',
					'return_value' => 'yes',
					'condition' => [
						'interactive_cursor_show' => 'yes',
					],
					'render_type' => 'none',
				]
			);

			$obj->add_control(
				'interactive_cursor_pulse_min_scale',
				[
					'label' => esc_html__( 'Min Scale', 'wpkoi-elements' ),
					'type' => Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 2,
							'step' => 0.01,
						],
					],
					'default' => [
						'size' => 1,
					],
					'condition' => [
						'interactive_cursor_show' => 'yes',
						'interactive_cursor_pulse' => 'yes',
					],
					'render_type' => 'none',
				]
			);

			$obj->add_control(
				'interactive_cursor_pulse_max_scale',
				[
					'label' => esc_html__( 'Max Scale', 'wpkoi-elements' ),
					'type' => Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 2,
							'step' => 0.01,
						],
					],
					'default' => [
						'size' => 1.05,
					],
					'condition' => [
						'interactive_cursor_show' => 'yes',
						'interactive_cursor_pulse' => 'yes',
					],
					'render_type' => 'none',
				]
			);

			$obj->add_control(
				'interactive_cursor_pulse_speed',
				[
					'label' => esc_html__( 'Pulse Speed', 'wpkoi-elements' ),
					'type' => Elementor\Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 0.1,
							'max' => 10,
							'step' => 0.1,
						],
					],
					'default' => [
						'size' => 2,
					],
					'condition' => [
						'interactive_cursor_show' => 'yes',
						'interactive_cursor_pulse' => 'yes',
					],
					'render_type' => 'none',
				]
			);

			/*
			|--------------------------------------------------------------------------
			| Rotation
			|--------------------------------------------------------------------------
			*/

			$obj->add_control(
				'interactive_cursor_rotation',
				[
					'label' => esc_html__( 'Infinite Rotation', 'wpkoi-elements' ),
					'type' => Elementor\Controls_Manager::SWITCHER,
					'default' => '',
					'return_value' => 'yes',
					'condition' => [
						'interactive_cursor_show' => 'yes',
					],
					'render_type' => 'none',
				]
			);

			$obj->add_control(
				'interactive_cursor_rotation_speed',
				[
					'label' => esc_html__( 'Rotation Speed', 'wpkoi-elements' ),
					'type' => Elementor\Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 1,
							'max' => 60,
							'step' => 1,
						],
					],
					'default' => [
						'size' => 15,
					],
					'condition' => [
						'interactive_cursor_show' => 'yes',
						'interactive_cursor_rotation' => 'yes',
					],
					'render_type' => 'none',
				]
			);

			$obj->add_control(
				'interactive_cursor_rotation_reverse',
				[
					'label' => esc_html__( 'Reverse Direction', 'wpkoi-elements' ),
					'type' => Elementor\Controls_Manager::SWITCHER,
					'default' => '',
					'return_value' => 'yes',
					'condition' => [
						'interactive_cursor_show' => 'yes',
						'interactive_cursor_rotation' => 'yes',
					],
					'render_type' => 'none',
				]
			);

			/*
			|--------------------------------------------------------------------------
			| Blend Mode
			|--------------------------------------------------------------------------
			*/

			$obj->add_control(
				'interactive_cursor_blend_mode',

				[
					'label' => esc_html__( 'Blend Mode', 'wpkoi-elements' ),
					'type' => Elementor\Controls_Manager::SWITCHER,
					'default' => '',
					'return_value' => 'yes',
					'condition' => [
						'interactive_cursor_show' => 'yes',
					],
					'render_type' => 'none',
				]
			);

			$obj->add_control(
				'interactive_cursor_blend_mode_type',
				[
					'label' => esc_html__( 'Blend Type Mode', 'wpkoi-elements' ),
					'type' => Elementor\Controls_Manager::SELECT,
					'default' => 'difference',
					'options' => [

						'normal' => esc_html__(
							'Normal', 'wpkoi-elements' ),

						'multiply' => esc_html__(
							'Multiply', 'wpkoi-elements' ),

						'screen' => esc_html__(
							'Screen', 'wpkoi-elements' ),

						'overlay' => esc_html__(
							'Overlay', 'wpkoi-elements' ),

						'darken' => esc_html__(
							'Darken', 'wpkoi-elements' ),

						'lighten' => esc_html__(
							'Lighten', 'wpkoi-elements' ),

						'color-dodge' => esc_html__(
							'Color Dodge', 'wpkoi-elements' ),

						'color-burn' => esc_html__(
							'Color Burn', 'wpkoi-elements' ),

						'hard-light' => esc_html__(
							'Hard Light', 'wpkoi-elements' ),

						'soft-light' => esc_html__(
							'Soft Light', 'wpkoi-elements' ),

						'difference' => esc_html__(
							'Difference', 'wpkoi-elements' ),

						'exclusion' => esc_html__(
							'Exclusion', 'wpkoi-elements' ),
					],
					'condition' => [
						'interactive_cursor_show' => 'yes',
						'interactive_cursor_blend_mode' => 'yes',
					],
					'render_type' => 'none',
				]
			);
			
			$obj->add_control(
				'interactive_cursor_magnetic',
				[
					'label' => esc_html__( 'Magnetic Follow', 'wpkoi-elements' ),
					'type' => Elementor\Controls_Manager::SWITCHER,
					'default' => '',
					'return_value' => 'yes',
					'condition' => [
						'interactive_cursor_show' => 'yes',
					],
					'render_type' => 'none',
				]
			);
			
			$obj->add_control(
				'interactive_cursor_magnetic_speed',
				[
					'label' => esc_html__( 'Smoothness', 'wpkoi-elements' ),
					'type' => Elementor\Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 0.01,
							'max' => 0.5,
							'step' => 0.01,
						],
					],
					'default' => [
						'size' => 0.12,
					],
					'condition' => [
						'interactive_cursor_show' => 'yes',
						'interactive_cursor_magnetic' => 'yes',
					],
					'render_type' => 'none',
				]
			);
			
			$obj->add_control(
				'interactive_cursor_magnetic_offset',
				[
					'label' => esc_html__( 'Magnetic Offset', 'wpkoi-elements' ),
					'type' => Elementor\Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 1,
							'step' => 0.01,
						],
					],
					'default' => [
						'size' => 0.15,
					],
					'condition' => [
						'interactive_cursor_show' => 'yes',
						'interactive_cursor_magnetic' => 'yes',
					],
					'render_type' => 'none',
				]
			);

			$obj->end_controls_section();
		}

		public function before_render( $element ) {

			$settings = $element->get_settings_for_display();

			if ( empty( $settings['interactive_cursor_show'] ) ) {
				return;
			}
			
			$icon_html = '';

			if ( ! empty( $settings['interactive_cursor_icon']['value'] ) ) {

				ob_start();

				Elementor\Icons_Manager::render_icon(
					$settings['interactive_cursor_icon'],
					[
						'aria-hidden' => 'true',
					]
				);

				$icon_html = ob_get_clean();
			}
			
			$font_size      = $settings['interactive_cursor_main_content_typography_font_size'] ?? [];
			$letter_spacing = $settings['interactive_cursor_main_content_typography_letter_spacing'] ?? [];
			$line_height    = $settings['interactive_cursor_main_content_typography_line_height'] ?? [];
			
			$allowed_positions = [ 'top-left', 'top-center', 'top-right', 'center-left', 'center-center', 'center-right', 'bottom-left', 'bottom-center', 'bottom-right', ];

			$allowed_animations = [ 'default', 'scale', 'blur', 'left-slide', 'right-slide', 'top-slide', 'bottom-slide', 'perspective', 'clipscale', ];

			$allowed_content_modes = [ 'normal', 'circle', ];

			$allowed_center_modes = [ 'none', 'icon', 'sub', ];

			$allowed_icon_positions = [ 'top', 'left', 'right', 'bottom', ];

			$allowed_blend_modes = [ 'normal', 'multiply', 'screen', 'overlay', 'darken', 'lighten', 'color-dodge', 'color-burn', 'hard-light', 'soft-light', 'difference', 'exclusion', ];

			$allowed_units = [ 'px', 'em', 'rem', 'vw', 'vh', '%', ];

			$position = in_array( $settings['interactive_cursor_position'] ?? '', $allowed_positions, true ) ? $settings['interactive_cursor_position'] : 'top-center';

			$animation = in_array( $settings['interactive_cursor_animation'] ?? '', $allowed_animations, true ) ? $settings['interactive_cursor_animation'] : 'default';

			$content_mode = in_array( $settings['interactive_cursor_content_mode'] ?? '', $allowed_content_modes, true ) ? $settings['interactive_cursor_content_mode'] : 'normal';

			$center_mode = in_array( $settings['interactive_cursor_circle_center_mode'] ?? '', $allowed_center_modes, true ) ? $settings['interactive_cursor_circle_center_mode'] : 'none';

			$icon_position = in_array( $settings['interactive_cursor_icon_position'] ?? '', $allowed_icon_positions, true ) ? $settings['interactive_cursor_icon_position'] : 'top';

			$blend_mode = in_array( $settings['interactive_cursor_blend_mode_type'] ?? '', $allowed_blend_modes, true ) ? $settings['interactive_cursor_blend_mode_type'] : 'difference';

			$circle_radius_unit = in_array( $settings['interactive_cursor_circle_radius']['unit'] ?? '', $allowed_units, true ) ? $settings['interactive_cursor_circle_radius']['unit'] : 'px';

			$font_size_unit = in_array( $font_size['unit'] ?? '', $allowed_units, true ) ? $font_size['unit'] : 'px';

			$letter_spacing_unit = in_array( $letter_spacing['unit'] ?? '', $allowed_units, true ) ? $letter_spacing['unit'] : 'px';

			$line_height_unit = in_array( $line_height['unit'] ?? '', $allowed_units, true ) ? $line_height['unit'] : 'em';

			$element->add_render_attribute(
				'_wrapper',
				[
					'class' => 'wpkoi-interactive-cursor-element wpkoi-interactive-cursor-parent',

					'data-mi-position' => esc_attr( $position ),
					'data-mi-animation' => esc_attr( $animation ),
					'data-mi-duration' => absint( $settings['interactive_cursor_duration'] ?? 300 ),

					'data-mi-main-enabled' => esc_attr( $settings['interactive_cursor_main_content_show'] ?? '' ),
					'data-mi-content-mode' => esc_attr( $content_mode ),
					'data-mi-main-content' => esc_attr(
						wp_strip_all_tags(
							$settings['interactive_cursor_main_content'] ?? ''
						)
					),

					'data-mi-sub-enabled' => esc_attr( $settings['interactive_cursor_sub_content_show'] ?? '' ),
					'data-mi-sub-content' => esc_attr(
						wp_strip_all_tags(
							$settings['interactive_cursor_sub_content'] ?? ''
						)
					),

					'data-mi-icon-enabled' => esc_attr( $settings['interactive_cursor_icon_show'] ?? '' ),
					'data-mi-icon-html' => esc_attr( $icon_html ),
					'data-mi-icon-position' => esc_attr( $icon_position ),

					'data-mi-hide-cursor' => esc_attr( $settings['interactive_cursor_hide_cursor'] ?? '' ),

					'data-mi-disable-tablet' => esc_attr( $settings['interactive_cursor_disable_tablet'] ?? '' ),
					'data-mi-disable-mobile' => esc_attr( $settings['interactive_cursor_disable_mobile'] ?? '' ),

					'data-mi-pulse' => esc_attr( $settings['interactive_cursor_pulse'] ?? '' ),
					'data-mi-pulse-min-scale' => floatval( $settings['interactive_cursor_pulse_min_scale']['size'] ?? 1 ),
					'data-mi-pulse-max-scale' => floatval( $settings['interactive_cursor_pulse_max_scale']['size'] ?? 1.05 ),
					'data-mi-pulse-speed' => floatval( $settings['interactive_cursor_pulse_speed']['size'] ?? 2 ),

					'data-mi-rotation' => esc_attr( $settings['interactive_cursor_rotation'] ?? '' ),
					'data-mi-rotation-speed' => floatval( $settings['interactive_cursor_rotation_speed']['size'] ?? 15 ),
					'data-mi-rotation-reverse' => esc_attr( $settings['interactive_cursor_rotation_reverse'] ?? '' ),

					'data-mi-blend-mode' => esc_attr( $settings['interactive_cursor_blend_mode'] ?? '' ),
					'data-mi-blend-mode-type' => esc_attr( $blend_mode ),

					'data-mi-magnetic' => esc_attr( $settings['interactive_cursor_magnetic'] ?? '' ),
					'data-mi-magnetic-speed' => floatval( $settings['interactive_cursor_magnetic_speed']['size'] ?? 0.12 ),
					'data-mi-magnetic-offset' => floatval( $settings['interactive_cursor_magnetic_offset']['size'] ?? 0.15 ),

					'data-mi-circle-radius' => floatval( $settings['interactive_cursor_circle_radius']['size'] ?? 120 ),
					'data-mi-circle-radius-unit' => esc_attr( $circle_radius_unit ),
					'data-mi-circle-gap' => floatval( $settings['interactive_cursor_circle_gap']['size'] ?? 0 ),
					'data-mi-circle-start-angle' => floatval( $settings['interactive_cursor_circle_start_angle']['size'] ?? -90 ),
					'data-mi-circle-reverse' => esc_attr( $settings['interactive_cursor_circle_reverse'] ?? '' ),
					'data-mi-circle-center-mode' => esc_attr( $center_mode ),

					'data-mi-font-family' => esc_attr( $settings['interactive_cursor_main_content_typography_font_family'] ?? '' ),
					'data-mi-font-size' => floatval( $font_size['size'] ?? 0 ),
					'data-mi-font-size-unit' => esc_attr( $font_size_unit ),
					'data-mi-font-weight' => esc_attr( $settings['interactive_cursor_main_content_typography_font_weight'] ?? '' ),
					'data-mi-letter-spacing' => floatval( $letter_spacing['size'] ?? 0 ),
					'data-mi-letter-spacing-unit' => esc_attr( $letter_spacing_unit ),
					'data-mi-text-transform' => esc_attr( $settings['interactive_cursor_main_content_typography_text_transform'] ?? '' ),
					'data-mi-font-style' => esc_attr( $settings['interactive_cursor_main_content_typography_font_style'] ?? '' ),
					'data-mi-line-height' => floatval( $line_height['size'] ?? 0 ),
					'data-mi-line-height-unit' => esc_attr( $line_height_unit ),
				]
			);
			
		}
		
		public function enqueue_scripts() {

			wp_enqueue_script( 'wpkoi-interactive-cursor', WPKOI_ELEMENTS_LITE_URL.'elements/interactive-cursor/assets/interactive-cursor.min.js', ['jquery', 'elementor-frontend'], WPKOI_ELEMENTS_LITE_VERSION, true );

			wp_enqueue_style( 'wpkoi-interactive-cursor', WPKOI_ELEMENTS_LITE_URL.'elements/interactive-cursor/assets/interactive-cursor.css', [], WPKOI_ELEMENTS_LITE_VERSION );
			
		}

		public static function get_instance() {

			if ( null == self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}
	}
}

function wpkoi_interactive_cursor_lite_extension() {
	return WPKoi_Interactive_Cursor_Extension::get_instance();
}
wpkoi_interactive_cursor_lite_extension()->init();