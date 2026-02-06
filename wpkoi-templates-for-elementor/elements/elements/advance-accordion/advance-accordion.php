<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Widget_Lite_WPKoi_Elements_Adv_Accordion extends Widget_Base {

	public function get_name() {
		return 'wpkoi-elements-adv-accordion';
	}

	public function get_title() {
		return esc_html__( 'Advanced Accordion', 'wpkoi-elements' );
	}

	public function get_icon() {
		return 'eicon-accordion';
	}

    public function get_categories() {
		return [ 'wpkoi-addons-for-elementor' ];
	}
	
	public function get_help_url() {
		return 'https://wpkoi.com/wpkoi-elementor-templates-demo/elements/advanced-accordion/';
	}

	protected function register_controls() {
		/**
  		 * Advance Accordion Content
  		 */
  		$this->start_controls_section(
  			'wpkoi_elements_section_adv_accordion_content_settings',
  			[
  				'label' => esc_html__( 'Accordion Settings', 'wpkoi-elements' )
  			]
  		);
		
		$repeater = new Repeater();

		$repeater->add_control(
			'wpkoi_elements_adv_accordion_tab_default_active',
			array(
				'label' => esc_html__( 'Active as Default', 'wpkoi-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'return_value' => 'yes',
			)
		);

		$repeater->add_control(
			'wpkoi_elements_adv_accordion_tab_icon_show',
			array(
				'label' => esc_html__( 'Tab Icon', 'wpkoi-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
			)
		);

		$repeater->add_control(
			'wpkoi_elements_adv_accordion_tab_title_icon_new',
			array(
				'label' => esc_html__( 'Icon', 'wpkoi-elements' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'wpkoi_elements_adv_accordion_tab_title_icon',
				'default' => [
					'value' => 'fas fa-plus',
					'library' => 'fa-solid',
				],
				'condition' => [
					'wpkoi_elements_adv_accordion_tab_icon_show' => 'yes'
				]
			)
		);

		$repeater->add_control(
			'wpkoi_elements_adv_accordion_tab_title',
			array(
				'label' => esc_html__( 'Tab Title', 'wpkoi-elements' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Tab Title', 'wpkoi-elements' ),
				'dynamic' => [ 'active' => true ]
			)
		);

		$repeater->add_control(
			'wpkoi_elements_adv_accordion_text_type',
			array(
				'label'                 => __( 'Content Type', 'wpkoi-elements' ),
				'type'                  => Controls_Manager::SELECT,
				'options'               => [
					'content'       => __( 'Content', 'wpkoi-elements' ),
					'template'      => __( 'Saved Templates', 'wpkoi-elements' ),
				],
				'default'               => 'content',
			)
		);

		$repeater->add_control(
			'wpkoi_elements_primary_templates',
			array(
				'label'                 => __( 'Choose Template', 'wpkoi-elements' ),
				'type'                  => Controls_Manager::SELECT,
				'options'               => wpkoi_elements_lite_get_page_templates(),
				'condition'             => [
					'wpkoi_elements_adv_accordion_text_type'      => 'template',
				],
			)
		);

		$repeater->add_control(
			'wpkoi_elements_adv_accordion_tab_content',
			array(
				'label' => esc_html__( 'Tab Content', 'wpkoi-elements' ),
				'type' => Controls_Manager::WYSIWYG,
				'default' => esc_html__( 'Add Your content here', 'wpkoi-elements' ),
				'dynamic' => [ 'active' => true ],
				'condition'             => [
					'wpkoi_elements_adv_accordion_text_type'      => 'content',
				],
			)
		);
		
		$this->add_control(
			'wpkoi_elements_adv_accordion_tab',
			array(
				'type'    => Controls_Manager::REPEATER,
				'label'   => esc_html__( 'Accordion content', 'wpkoi-elements' ),
				'fields'  => $repeater->get_controls(),
				'seperator' => 'before',
				'default' => [
					[ 'wpkoi_elements_adv_accordion_tab_title' => esc_html__( 'Tab Title 1', 'wpkoi-elements' ) ],
					[ 'wpkoi_elements_adv_accordion_tab_title' => esc_html__( 'Tab Title 2', 'wpkoi-elements' ) ],
				],
				'title_field' => '{{wpkoi_elements_adv_accordion_tab_title}}',
			)
		);
		
  		$this->add_control(
		  'wpkoi_elements_adv_accordion_type',
		  	[
		   	'label'       	=> esc_html__( 'Behavior', 'wpkoi-elements' ),
		     	'type' 			=> Controls_Manager::SELECT,
		     	'default' 		=> 'accordion',
		     	'label_block' 	=> false,
		     	'options' 		=> [
		     		'accordion' 	=> esc_html__( 'Accordion', 'wpkoi-elements' ),
		     		'toggle' 		=> esc_html__( 'Toggle', 'wpkoi-elements' ),
		     	],
		  	]
		);
		
  		$this->add_control(
		  'wpkoi_elements_adv_accordion_hover_effect',
		  	[
		   	'label'       	=> esc_html__( 'Hover Effect', 'wpkoi-elements' ),
		     	'type' 			=> Controls_Manager::SELECT,
		     	'default' 		=> 'default',
		     	'label_block' 	=> false,
		     	'options' 		=> [
		     		'default' 	=> esc_html__( 'Default', 'wpkoi-elements' ),
		     		'right' 	=> esc_html__( 'Right Slide', 'wpkoi-elements' ),
		     		'left' 		=> esc_html__( 'Left Slide', 'wpkoi-elements' ),
		     		'top' 		=> esc_html__( 'Top Slide', 'wpkoi-elements' ),
		     		'bottom' 	=> esc_html__( 'Bottom Slide', 'wpkoi-elements' ),
		     	],
		  	]
		);
		
		$this->add_control(
			'wpkoi_elements_adv_accordion_icon_show',
			[
				'label' => esc_html__( 'Toggle Icon', 'wpkoi-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
			]
		);
		$this->add_control(
			'wpkoi_elements_adv_accordion_icon_new',
			[
				'label' => esc_html__( 'Toggle Icon', 'wpkoi-elements' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'wpkoi_elements_adv_accordion_icon',
				'default' => [
					'value' => 'fas fa-angle-right',
					'library' => 'fa-solid',
				],
				'condition' => [
					'wpkoi_elements_adv_accordion_icon_show' => 'yes'
				]
			]
		);
		$this->add_control(
			'wpkoi_elements_adv_accordion_toggle_speed',
			[
				'label' => esc_html__( 'Speed of animation', 'wpkoi-elements' ),
				'type' => Controls_Manager::NUMBER,
				'label_block' => false,
				'default' => 300,
			]
		);
  		$this->end_controls_section();
  		
  		/**
		 * Tab Style Advance Accordion Generel Style
		 */
		$this->start_controls_section(
			'wpkoi_elements_section_adv_accordion_style_settings',
			[
				'label' => esc_html__( 'General Style', 'wpkoi-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'wpkoi_elements_adv_accordion_padding',
			[
				'label' => esc_html__( 'Padding', 'wpkoi-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem', '%', 'vw' ],
				'selectors' => [
	 					'{{WRAPPER}} .wpkoi-elements-adv-accordion' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);
		$this->add_responsive_control(
			'wpkoi_elements_adv_accordion_margin',
			[
				'label' => esc_html__( 'Margin', 'wpkoi-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem', '%', 'vw' ],
				'selectors' => [
	 					'{{WRAPPER}} .wpkoi-elements-adv-accordion' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'wpkoi_elements_adv_accordion_border',
				'label' => esc_html__( 'Border', 'wpkoi-elements' ),
				'selector' => '{{WRAPPER}} .wpkoi-elements-adv-accordion',
			]
		);
		$this->add_responsive_control(
			'wpkoi_elements_adv_accordion_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpkoi-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .wpkoi-elements-adv-accordion' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'wpkoi_elements_adv_accordion_box_shadow',
				'selector' => '{{WRAPPER}} .wpkoi-elements-adv-accordion',
			]
		);
  		$this->end_controls_section();

  		
		$this->start_controls_section(
			'wpkoi_elements_section_adv_accordions_tab_style_settings',
			[
				'label' => esc_html__( 'Tab Style', 'wpkoi-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
            	'name' => 'wpkoi_elements_adv_accordion_tab_title_typography',
				'selector' => '{{WRAPPER}} .wpkoi-elements-adv-accordion .wpkoi-elements-accordion-list .wpkoi-elements-accordion-header',
			]
		);
		$this->add_responsive_control(
			'wpkoi_elements_adv_accordion_tab_icon_size',
			[
				'label' => __( 'Icon Size', 'wpkoi-elements' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 18,
					'unit' => 'px',
				],
				'size_units' => [ 'px', 'em', 'rem', '%', 'vw' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
					'em' => [
						'min' => 0,
						'max' => 20,
						'step' => 1,
					],
					'rem' => [
						'min' => 0,
						'max' => 20,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
					'vw' => [
						'min' => 0,
						'max' => 20,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpkoi-elements-adv-accordion .wpkoi-elements-accordion-list .wpkoi-elements-accordion-header i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpkoi-elements-adv-accordion .wpkoi-elements-accordion-list .wpkoi-elements-accordion-header svg' => 'width: {{SIZE}}{{UNIT}};',
				]
			]
		);
		$this->add_responsive_control(
			'wpkoi_elements_adv_accordion_tab_icon_gap',
			[
				'label' => __( 'Icon Gap', 'wpkoi-elements' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 12,
					'unit' => 'px',
				],
				'size_units' => [ 'px', 'em', 'rem', '%', 'vw' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
					'em' => [
						'min' => 0,
						'max' => 20,
						'step' => 1,
					],
					'rem' => [
						'min' => 0,
						'max' => 20,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
					'vw' => [
						'min' => 0,
						'max' => 20,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpkoi-elements-adv-accordion .wpkoi-elements-accordion-list .wpkoi-elements-accordion-header i' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpkoi-elements-adv-accordion .wpkoi-elements-accordion-list .wpkoi-elements-accordion-header svg' => 'margin-right: {{SIZE}}{{UNIT}};',
				]
			]
		);
		$this->add_responsive_control(
			'wpkoi_elements_adv_accordion_tab_padding',
			[
				'label' => esc_html__( 'Padding', 'wpkoi-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem', '%', 'vw' ],
				'selectors' => [
	 					'{{WRAPPER}} .wpkoi-elements-adv-accordion .wpkoi-elements-accordion-list .wpkoi-elements-accordion-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);
		$this->add_responsive_control(
			'wpkoi_elements_adv_accordion_tab_margin',
			[
				'label' => esc_html__( 'Margin', 'wpkoi-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem', '%', 'vw' ],
				'selectors' => [
	 					'{{WRAPPER}} .wpkoi-elements-adv-accordion .wpkoi-elements-accordion-list .wpkoi-elements-accordion-header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);
		$this->start_controls_tabs( 'wpkoi_elements_adv_accordion_header_tabs' );
			// Normal State Tab
			$this->start_controls_tab( 'wpkoi_elements_adv_accordion_header_normal', [ 'label' => esc_html__( 'Normal', 'wpkoi-elements' ) ] );
				$this->add_control(
					'wpkoi_elements_adv_accordion_tab_color',
					[
						'label' => esc_html__( 'Tab Background Color', 'wpkoi-elements' ),
						'type' => Controls_Manager::COLOR,
						'default' => '#ffffff',
						'selectors' => [
							'{{WRAPPER}} .wpkoi-elements-adv-accordion .wpkoi-elements-accordion-list .wpkoi-elements-accordion-header' => 'background-color: {{VALUE}};',
							'{{WRAPPER}} .wpkoi-elements-adv-accordion.wpkoi-elements-aa-hover-right .wpkoi-elements-accordion-list .wpkoi-elements-accordion-header:hover' => 'background-color: {{VALUE}};',
							'{{WRAPPER}} .wpkoi-elements-adv-accordion.wpkoi-elements-aa-hover-left .wpkoi-elements-accordion-list .wpkoi-elements-accordion-header:hover' => 'background-color: {{VALUE}};',
							'{{WRAPPER}} .wpkoi-elements-adv-accordion.wpkoi-elements-aa-hover-top .wpkoi-elements-accordion-list .wpkoi-elements-accordion-header:hover' => 'background-color: {{VALUE}};',
							'{{WRAPPER}} .wpkoi-elements-adv-accordion.wpkoi-elements-aa-hover-bottom .wpkoi-elements-accordion-list .wpkoi-elements-accordion-header:hover' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'wpkoi_elements_adv_accordion_tab_text_color',
					[
						'label' => esc_html__( 'Text Color', 'wpkoi-elements' ),
						'type' => Controls_Manager::COLOR,
						'default' => '#333333',
						'selectors' => [
							'{{WRAPPER}} .wpkoi-elements-adv-accordion .wpkoi-elements-accordion-list .wpkoi-elements-accordion-header' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'wpkoi_elements_adv_accordion_tab_icon_color',
					[
						'label' => esc_html__( 'Icon Color', 'wpkoi-elements' ),
						'type' => Controls_Manager::COLOR,
						'default' => '#333333',
						'selectors' => [
							'{{WRAPPER}} .wpkoi-elements-adv-accordion .wpkoi-elements-accordion-list .wpkoi-elements-accordion-header i' => 'color: {{VALUE}};',
							'{{WRAPPER}} .wpkoi-elements-adv-accordion .wpkoi-elements-accordion-list .wpkoi-elements-accordion-header svg' => 'fill: {{VALUE}};',
						]
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'wpkoi_elements_adv_accordion_tab_border',
						'label' => esc_html__( 'Border', 'wpkoi-elements' ),
						'selector' => '{{WRAPPER}} .wpkoi-elements-adv-accordion .wpkoi-elements-accordion-list .wpkoi-elements-accordion-header',
					]
				);
				$this->add_responsive_control(
					'wpkoi_elements_adv_accordion_tab_border_radius',
					[
						'label' => esc_html__( 'Border Radius', 'wpkoi-elements' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', 'em', 'rem', '%' ],
						'selectors' => [
			 					'{{WRAPPER}} .wpkoi-elements-adv-accordion .wpkoi-elements-accordion-list .wpkoi-elements-accordion-header' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			 			],
					]
				);
			$this->end_controls_tab();
			// Hover State Tab
			$this->start_controls_tab( 'wpkoi_elements_adv_accordion_header_hover', [ 'label' => esc_html__( 'Hover', 'wpkoi-elements' ) ] );
				$this->add_control(
					'wpkoi_elements_adv_accordion_tab_color_hover',
					[
						'label' => esc_html__( 'Tab Background Color', 'wpkoi-elements' ),
						'type' => Controls_Manager::COLOR,
						'default' => '#414141',
						'selectors' => [
							'{{WRAPPER}} .wpkoi-elements-adv-accordion .wpkoi-elements-accordion-list .wpkoi-elements-accordion-header:hover' => 'background-color: {{VALUE}};',
							'{{WRAPPER}} .wpkoi-elements-adv-accordion.wpkoi-elements-aa-hover-right .wpkoi-elements-accordion-list .wpkoi-elements-accordion-header:before' => 'background-color: {{VALUE}};',
							'{{WRAPPER}} .wpkoi-elements-adv-accordion.wpkoi-elements-aa-hover-left .wpkoi-elements-accordion-list .wpkoi-elements-accordion-header:before' => 'background-color: {{VALUE}};',
							'{{WRAPPER}} .wpkoi-elements-adv-accordion.wpkoi-elements-aa-hover-top .wpkoi-elements-accordion-list .wpkoi-elements-accordion-header:before' => 'background-color: {{VALUE}};',
							'{{WRAPPER}} .wpkoi-elements-adv-accordion.wpkoi-elements-aa-hover-bottom .wpkoi-elements-accordion-list .wpkoi-elements-accordion-header:before' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'wpkoi_elements_adv_accordion_tab_text_color_hover',
					[
						'label' => esc_html__( 'Text Color', 'wpkoi-elements' ),
						'type' => Controls_Manager::COLOR,
						'default' => '#ffffff',
						'selectors' => [
							'{{WRAPPER}} .wpkoi-elements-adv-accordion .wpkoi-elements-accordion-list .wpkoi-elements-accordion-header:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'wpkoi_elements_adv_accordion_tab_icon_color_hover',
					[
						'label' => esc_html__( 'Icon Color', 'wpkoi-elements' ),
						'type' => Controls_Manager::COLOR,
						'default' => '#ffffff',
						'selectors' => [
							'{{WRAPPER}} .wpkoi-elements-adv-accordion .wpkoi-elements-accordion-list .wpkoi-elements-accordion-header:hover i' => 'color: {{VALUE}};',
							'{{WRAPPER}} .wpkoi-elements-adv-accordion .wpkoi-elements-accordion-list .wpkoi-elements-accordion-header:hover svg' => 'fill: {{VALUE}};',
						]
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'wpkoi_elements_adv_accordion_tab_border_hover',
						'label' => esc_html__( 'Border', 'wpkoi-elements' ),
						'selector' => '{{WRAPPER}} .wpkoi-elements-adv-accordion .wpkoi-elements-accordion-list .wpkoi-elements-accordion-header:hover',
					]
				);
				$this->add_responsive_control(
					'wpkoi_elements_adv_accordion_tab_border_radius_hover',
					[
						'label' => esc_html__( 'Border Radius', 'wpkoi-elements' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', 'em', 'rem', '%' ],
						'selectors' => [
			 					'{{WRAPPER}} .wpkoi-elements-adv-accordion .wpkoi-elements-accordion-list .wpkoi-elements-accordion-header:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			 			],
					]
				);
			$this->end_controls_tab();
			// Active State Tab
			$this->start_controls_tab( 'wpkoi_elements_adv_accordion_header_active', [ 'label' => esc_html__( 'Active', 'wpkoi-elements' ) ] );
				$this->add_control(
					'wpkoi_elements_adv_accordion_tab_color_active',
					[
						'label' => esc_html__( 'Tab Background Color', 'wpkoi-elements' ),
						'type' => Controls_Manager::COLOR,
						'default' => '#444444',
						'selectors' => [
							'{{WRAPPER}} .wpkoi-elements-adv-accordion .wpkoi-elements-accordion-list .wpkoi-elements-accordion-header.active' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'wpkoi_elements_adv_accordion_tab_text_color_active',
					[
						'label' => esc_html__( 'Text Color', 'wpkoi-elements' ),
						'type' => Controls_Manager::COLOR,
						'default' => '#ffffff',
						'selectors' => [
							'{{WRAPPER}} .wpkoi-elements-adv-accordion .wpkoi-elements-accordion-list .wpkoi-elements-accordion-header.active' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'wpkoi_elements_adv_accordion_tab_icon_color_active',
					[
						'label' => esc_html__( 'Icon Color', 'wpkoi-elements' ),
						'type' => Controls_Manager::COLOR,
						'default' => '#ffffff',
						'selectors' => [
							'{{WRAPPER}} .wpkoi-elements-adv-accordion .wpkoi-elements-accordion-list .wpkoi-elements-accordion-header.active i' => 'color: {{VALUE}};',
							'{{WRAPPER}} .wpkoi-elements-adv-accordion .wpkoi-elements-accordion-list .wpkoi-elements-accordion-header.active svg' => 'fill: {{VALUE}};',
						]
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'wpkoi_elements_adv_accordion_tab_border_active',
						'label' => esc_html__( 'Border', 'wpkoi-elements' ),
						'selector' => '{{WRAPPER}} .wpkoi-elements-adv-accordion .wpkoi-elements-accordion-list .wpkoi-elements-accordion-header.active',
					]
				);
				$this->add_responsive_control(
					'wpkoi_elements_adv_accordion_tab_border_radius_active',
					[
						'label' => esc_html__( 'Border Radius', 'wpkoi-elements' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', 'em', 'rem', '%' ],
						'selectors' => [
			 					'{{WRAPPER}} .wpkoi-elements-adv-accordion .wpkoi-elements-accordion-list .wpkoi-elements-accordion-header.active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			 			],
					]
				);
			$this->end_controls_tab();
		$this->end_controls_tabs();
  		$this->end_controls_section();
  		/**
		 * -------------------------------------------
		 * Tab Style Advance Accordion Content Style
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'wpkoi_elements_section_adv_accordion_tab_content_style_settings',
			[
				'label' => esc_html__( 'Content Style', 'wpkoi-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'adv_accordion_content_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpkoi-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .wpkoi-elements-adv-accordion .wpkoi-elements-accordion-list .wpkoi-elements-accordion-content' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'adv_accordion_content_text_color',
			[
				'label' => esc_html__( 'Text Color', 'wpkoi-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wpkoi-elements-adv-accordion .wpkoi-elements-accordion-list .wpkoi-elements-accordion-content' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
            	'name' => 'wpkoi_elements_adv_accordion_content_typography',
				'selector' => '{{WRAPPER}} .wpkoi-elements-adv-accordion .wpkoi-elements-accordion-list .wpkoi-elements-accordion-content',
			]
		);
		$this->add_responsive_control(
			'wpkoi_elements_adv_accordion_content_padding',
			[
				'label' => esc_html__( 'Padding', 'wpkoi-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem', '%', 'vw' ],
				'selectors' => [
	 					'{{WRAPPER}} .wpkoi-elements-adv-accordion .wpkoi-elements-accordion-list .wpkoi-elements-accordion-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);
		$this->add_responsive_control(
			'wpkoi_elements_adv_accordion_content_margin',
			[
				'label' => esc_html__( 'Margin', 'wpkoi-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem', '%', 'vw' ],
				'selectors' => [
	 					'{{WRAPPER}} .wpkoi-elements-adv-accordion .wpkoi-elements-accordion-list .wpkoi-elements-accordion-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'wpkoi_elements_adv_accordion_content_border',
				'label' => esc_html__( 'Border', 'wpkoi-elements' ),
				'selector' => '{{WRAPPER}} .wpkoi-elements-adv-accordion .wpkoi-elements-accordion-list .wpkoi-elements-accordion-content',
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'wpkoi_elements_adv_accordion_content_shadow',
				'selector' => '{{WRAPPER}} .wpkoi-elements-adv-accordion .wpkoi-elements-accordion-list .wpkoi-elements-accordion-content',
				'separator' => 'before'
			]
		);
  		$this->end_controls_section();

	}

	protected function render() {

   		$settings = $this->get_settings_for_display();
		$hover_effect = isset( $settings['wpkoi_elements_adv_accordion_hover_effect'] ) ? $settings['wpkoi_elements_adv_accordion_hover_effect'] : "default";
	?>
	<div class="wpkoi-elements-adv-accordion wpkoi-elements-aa-hover-<?php echo esc_attr( $hover_effect ); ?>" id="wpkoi-elements-adv-accordion-<?php echo esc_attr( $this->get_id() ); ?>">
		<?php foreach( $settings['wpkoi_elements_adv_accordion_tab'] as $tab ) : ?>
		<div class="wpkoi-elements-accordion-list">
			<div class="wpkoi-elements-accordion-header<?php if( $tab['wpkoi_elements_adv_accordion_tab_default_active'] == 'yes' ) : echo ' active-default'; endif; ?>">
				<span><?php if( $tab['wpkoi_elements_adv_accordion_tab_icon_show'] === 'yes' ) :
				$migrated = isset( $tab['__fa4_migrated']['wpkoi_elements_adv_accordion_tab_title_icon_new'] );
				$is_new = empty( $tab['wpkoi_elements_adv_accordion_tab_title_icon'] );
				if ( $is_new || $migrated ) :
					Icons_Manager::render_icon( $tab['wpkoi_elements_adv_accordion_tab_title_icon_new'], [ 'aria-hidden' => 'true' ] );
				else : ?><i class="<?php echo esc_attr( $tab['wpkoi_elements_adv_accordion_tab_title_icon'] ); ?> fa-accordion-icon" aria-hidden="true"></i> <?php endif;
				endif; 
				echo esc_html( $tab['wpkoi_elements_adv_accordion_tab_title'] ); ?></span> 
				<?php if( $settings['wpkoi_elements_adv_accordion_icon_show'] === 'yes' ) : 
				echo '<span class="fa-toggle">' ; 
				$migrated = isset( $settings['__fa4_migrated']['wpkoi_elements_adv_accordion_icon_new'] );
				$is_new = empty( $settings['wpkoi_elements_adv_accordion_icon'] );
				if ( $is_new || $migrated ) :
					Icons_Manager::render_icon( $settings['wpkoi_elements_adv_accordion_icon_new'], [ 'aria-hidden' => 'true' ] );
				else : ?>
					<i class="<?php echo esc_attr( $settings['wpkoi_elements_adv_accordion_icon'] ); ?>" aria-hidden="true"></i>
				<?php endif; 
				echo '</span>' ;
				endif; ?>
			</div>
			<div class="wpkoi-elements-accordion-content clearfix<?php if( $tab['wpkoi_elements_adv_accordion_tab_default_active'] == 'yes' ) : echo ' active-default'; endif; ?>">
				<?php if( 'content' == $tab['wpkoi_elements_adv_accordion_text_type'] ) : ?>
					<p><?php echo do_shortcode($tab['wpkoi_elements_adv_accordion_tab_content']); ?></p>
				<?php elseif( 'template' == $tab['wpkoi_elements_adv_accordion_text_type'] ) :
					if ( !empty( $tab['wpkoi_elements_primary_templates'] ) ) {
						$wpkoi_elements_template_id = $tab['wpkoi_elements_primary_templates'];
						$wpkoi_elements_frontend = new Frontend;
						echo $wpkoi_elements_frontend->get_builder_content( $wpkoi_elements_template_id, true );
					}
				endif; ?>
			</div>
		</div>
		<?php endforeach; ?>
	</div>
	<script>
		jQuery(document).ready(function($) {
			var $wpkoielementsAdvAccordion = $('#wpkoi-elements-adv-accordion-<?php echo esc_attr( $this->get_id() ); ?>');
			var $wpkoielementsAccordionList = $wpkoielementsAdvAccordion.find('.wpkoi-elements-accordion-list');
			var $wpkoielementsAccordionListHeader = $wpkoielementsAdvAccordion.find('.wpkoi-elements-accordion-list .wpkoi-elements-accordion-header');
			var $wpkoielementsAccordioncontent = $wpkoielementsAdvAccordion.find('.wpkoi-elements-accordion-content');
			$wpkoielementsAccordionList.each(function(i) {
				if( $(this).find('.wpkoi-elements-accordion-header').hasClass('active-default') ) {
					$(this).find('.wpkoi-elements-accordion-header').addClass('active');
					$(this).find('.wpkoi-elements-accordion-content').addClass('active').css('display', 'block').slideDown(<?php echo esc_attr( $settings['wpkoi_elements_adv_accordion_toggle_speed'] ); ?>);
				}
			});
			<?php if( 'accordion' == $settings['wpkoi_elements_adv_accordion_type'] ) : ?>
			$wpkoielementsAccordionListHeader.on('click', function() {
				// Check if 'active' class is already exists
				if( $(this).hasClass('active') ) {
					$(this).removeClass('active');
					$(this).next('.wpkoi-elements-accordion-content').removeClass('active').slideUp(<?php echo esc_attr( $settings['wpkoi_elements_adv_accordion_toggle_speed'] ); ?>);
				}else {
					$wpkoielementsAccordionListHeader.removeClass('active');
					$wpkoielementsAccordionListHeader.next('.wpkoi-elements-accordion-content').removeClass('active').slideUp(<?php echo esc_attr( $settings['wpkoi_elements_adv_accordion_toggle_speed'] ); ?>);

					$(this).toggleClass('active');
					$(this).next('.wpkoi-elements-accordion-content').slideToggle(<?php echo esc_attr( $settings['wpkoi_elements_adv_accordion_toggle_speed'] ); ?>, function() {
						$(this).toggleClass('active');
					});
				}
			});
			<?php endif; ?>
			<?php if( 'toggle' == $settings['wpkoi_elements_adv_accordion_type'] ) : ?>
			$wpkoielementsAccordionListHeader.on('click', function() {
				// Check if 'active' class is already exists
				if( $(this).hasClass('active') ) {
					$(this).removeClass('active');
					$(this).next('.wpkoi-elements-accordion-content').removeClass('active').slideUp(<?php echo esc_attr( $settings['wpkoi_elements_adv_accordion_toggle_speed'] ); ?>);
				}else {
					$(this).toggleClass('active');
					$(this).next('.wpkoi-elements-accordion-content').slideToggle(<?php echo esc_attr( $settings['wpkoi_elements_adv_accordion_toggle_speed'] ); ?>, function() {
						$(this).toggleClass('active');
					});
				}
			});
			<?php endif; ?>
		});
	</script>
	<?php
	}
	
	public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);

		wp_register_style('wpkoi-advance-accordion',WPKOI_ELEMENTS_LITE_URL . 'elements/advance-accordion/assets/advance-accordion.css',false,WPKOI_ELEMENTS_LITE_VERSION);
	}

	public function get_style_depends() {
		return [ 'wpkoi-advance-accordion' ];
	}

	protected function content_template() {}
}

Plugin::instance()->widgets_manager->register( new Widget_Lite_WPKoi_Elements_Adv_Accordion() );