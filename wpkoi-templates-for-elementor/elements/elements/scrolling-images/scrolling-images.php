<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.


class Widget_WPKoi_Scrolling_Images extends Widget_Base {

	public function get_name() {
		return 'wpkoi-scrolling-images';
	}

	public function get_title() {
		return esc_html__( 'Scrolling Images', 'wpkoi-elements' );
	}

	public function get_icon() {
		return 'eicon-image';
	}

    public function get_categories() {
		return [ 'wpkoi-addons-for-elementor' ];
	}
	
	public function get_help_url() {
		return 'https://wpkoi.com/wpkoi-elementor-templates-demo/elements/scroll-images/';
	}


	protected function register_controls() {

		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Images', 'wpkoi-elements' ),
			]
		);

		$this->add_control(
			'images',
			[
				'label' => __( 'Add Images', 'wpkoi-elements' ),
				'type' => Controls_Manager::GALLERY,
				'default' => [],
			]
		);
		
		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'image',
				'default' => 'full',
			]
		);
		
		$this->add_control(
			'use_custom_height',
			[
				'label' => __( 'Equal Image Height', 'wpkoi-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
			]
		);
		
		$this->add_responsive_control(
			'image_height',
			[
				'label' => __( 'Image Height', 'wpkoi-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', 'em', 'rem', '%', 'vw', 'vh'],
				'range' => [
					'px' => ['min' => 20, 'max' => 1000],
				],
				'default' => [
					'size' => 300,
				],
				'condition' => [
					'use_custom_height' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .wpkoi-marquee-img img' => 'height: {{SIZE}}{{UNIT}}; width: auto;',
				],
			]
		);
		
		$this->add_control(
			'reset_image_height',
			[
				'type' => Controls_Manager::HIDDEN,
				'selectors' => [
					'{{WRAPPER}} .wpkoi-marquee-img img' => 'height: auto; width: auto;',
				],
				'condition' => [
					'use_custom_height!' => 'yes',
				],
			]
		);
		
		$this->add_responsive_control(
			'image_spacing',
			[
				'label' => __( 'Image Spacing', 'wpkoi-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', 'em', 'rem', 'vw', 'vh'],
				'range' => [
					'px' => ['min' => 0, 'max' => 100],
				],
				'default' => [
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .wpkoi-marquee-img .wpkoi-scrolling-content' => 'display: flex; gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpkoi-marquee-vertical.wpkoi-marquee-img .wpkoi-scrolling-content' => 'flex-direction: column;',
				],
			]
		);

		$this->add_control(
			'image_links',
			[
				'label' => __( 'Enable Links', 'wpkoi-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
			]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_hover_effects',
			[
				'label' => __( 'Image Hover Effects', 'wpkoi-elements' ),
				'condition' => [
					'images!' => '',
				],
			]
		);
		
		$this->add_control(
			'hover_effect',
			[
				'label' => __( 'Hover Effect', 'wpkoi-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none' => __( 'None', 'wpkoi-elements' ),
					'grayscale' => __( 'Grayscale', 'wpkoi-elements' ),
					'colorize' => __( 'Colorize', 'wpkoi-elements' ),
					'scale' => __( 'Zoom', 'wpkoi-elements' ),
					'rotate' => __( 'Rotate', 'wpkoi-elements' ),
					'opacity' => __( 'Fade', 'wpkoi-elements' ),
				],
        		'prefix_class' => 'wpkoi-hover-',
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'hover_scale_amount',
			[
				'label' => __( 'Zoom Amount', 'wpkoi-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 2,
						'step' => 0.05,
					],
				],
				'default' => [
					'size' => 1.1,
				],
				'condition' => [
					'hover_effect' => 'scale',
				],
				'selectors' => [
					'{{WRAPPER}} .wpkoi-marquee-img img' => 'transition: transform 0.3s ease;',
					'{{WRAPPER}} .wpkoi-marquee-img img:hover' => 'transform: scale({{SIZE}});',
				],
			]
		);
		
		$this->add_control(
			'hover_rotate_deg',
			[
				'label' => __( 'Rotation (deg)', 'wpkoi-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -20,
						'max' => 20,
					],
				],
				'default' => [
					'size' => 5,
				],
				'condition' => [
					'hover_effect' => 'rotate',
				],
				'selectors' => [
					'{{WRAPPER}} .wpkoi-marquee-img img' => 'transition: transform 0.3s ease;',
					'{{WRAPPER}} .wpkoi-marquee-img img:hover' => 'transform: rotate({{SIZE}}deg);',
				],
			]
		);
		
		$this->add_control(
			'hover_opacity_value',
			[
				'label' => __( 'Opacity', 'wpkoi-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0.1,
						'max' => 1,
						'step' => 0.05,
					],
				],
				'default' => [
					'size' => 0.7,
				],
				'condition' => [
					'hover_effect' => 'opacity',
				],
				'selectors' => [
					'{{WRAPPER}} .wpkoi-marquee-img img' => 'transition: opacity 0.3s ease; opacity: {{SIZE}};',
					'{{WRAPPER}} .wpkoi-marquee-img img:hover' => 'opacity: 1;',
				],
			]
		);
		
		$this->add_control(
			'hover_transition_duration',
			[
				'label' => __( 'Transition Duration (s)', 'wpkoi-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0.1,
						'max' => 2,
						'step' => 0.1,
					],
				],
				'default' => [
					'size' => 0.3,
				],
				'selectors' => [
					'{{WRAPPER}} .wpkoi-marquee-img img' => 'transition-duration: {{SIZE}}s;',
				],
			]
		);


		$this->end_controls_section();


		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Scrolling Style', 'wpkoi-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'images!' => '',
				],
			]
		);

		$this->add_control(
			'main_heading_background',
			[
				'label'     => __( 'Background', 'wpkoi-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpkoi-marquee .wpkoi-scrolling-content' => 'background-color: {{VALUE}};',
				]
			]
		);

		$this->add_responsive_control(
			'main_heading_padding',
			[
				'label'      => esc_html__('Padding', 'wpkoi-elements'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem', '%', 'vw' ],
				'selectors'  => [
					'{{WRAPPER}} .wpkoi-marquee .wpkoi-scrolling-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'main_heading_border',
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .wpkoi-marquee .wpkoi-scrolling-content'
			]
		);

		$this->add_control(
			'main_heading_radius',
			[
				'label'      => esc_html__('Radius', 'wpkoi-elements'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .wpkoi-marquee .wpkoi-scrolling-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'main_heading_shadow',
				'selector' => '{{WRAPPER}} .wpkoi-marquee .wpkoi-scrolling-content'
			]
		);

		$this->add_control(
			'fade_edges',
			[
				'label' => __( 'Fade Edges', 'wpkoi-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'separator' => 'before',
				'prefix_class' => 'wpkoi-fade-',
			]
		);

		$this->add_responsive_control(
			'fade_size',
			[
				'label' => __( 'Fade Size (%)', 'wpkoi-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['%'],
				'range' => [
					'%' => ['min' => 0, 'max' => 50],
				],
				'default' => [
					'size' => 10,
					'unit' => '%',
				],
				'condition' => [
					'fade_edges' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .wpkoi-marquee:not(.wpkoi-marquee-vertical)' =>
						'-webkit-mask-image: linear-gradient(to right, transparent 0%, black {{SIZE}}{{UNIT}}, black calc(100% - {{SIZE}}{{UNIT}}), transparent 100%);
						 mask-image: linear-gradient(to right, transparent 0%, black {{SIZE}}{{UNIT}}, black calc(100% - {{SIZE}}{{UNIT}}), transparent 100%);',

					'{{WRAPPER}} .wpkoi-marquee-vertical' =>
						'-webkit-mask-image: linear-gradient(to bottom, transparent 0%, black {{SIZE}}{{UNIT}}, black calc(100% - {{SIZE}}{{UNIT}}), transparent 100%);
						 mask-image: linear-gradient(to bottom, transparent 0%, black {{SIZE}}{{UNIT}}, black calc(100% - {{SIZE}}{{UNIT}}), transparent 100%);',
				],
			]
		);

		$this->add_control(
			'scrolling_axis',
			[
				'label' => __( 'Direction', 'wpkoi-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => [
					'horizontal' => 'Horizontal',
					'vertical' => 'Vertical',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'vertical_scroll_height',
			[
				'label' => __( 'Vertical Height', 'wpkoi-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', '%', 'vw' ],
				'range' => [
					'px' => ['min' => 50, 'max' => 600],
				],
				'default' => [
					'size' => 300,
				],
				'condition' => [
					'scrolling_axis' => 'vertical',
				],
			]
		);

		$this->add_control(
			'scrolling_direction',
			[
				'label' => __( 'Reverse Direction', 'wpkoi-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'scrolling_speed',
			[
				'label' => __( 'Speed', 'wpkoi-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => ['min' => 1, 'max' => 100],
				],
				'default' => [
					'size' => 10,
				],
			]
		);

		$this->add_control(
			'scrolling_gap',
			[
				'label' => __( 'Gap', 'wpkoi-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => ['min' => 0, 'max' => 200],
				],
				'default' => [
					'size' => 20,
				],
			]
		);

		$this->add_control(
			'scrolling_pause',
			[
				'label' => __( 'Pause on Hover', 'wpkoi-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
			]
		);
		
		$this->add_control(
			'scrolling_overflow',
			[
				'label'        => __( 'Hidden overflow', 'wpkoi-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'default' => 'no',
				'return_value' => 'yes',
			]
		);

		$this->end_controls_section();
	}


	protected function render() {

		$settings = $this->get_settings_for_display();

		if ( empty( $settings['images'] ) ) {
			return;
		}

		$id = $this->get_id();

		$speed = isset($settings['scrolling_speed']['size']) ? $settings['scrolling_speed']['size'] * 10 : 100;
		$gap   = isset($settings['scrolling_gap']['size']) ? $settings['scrolling_gap']['size'] : 20;

		$direction = 'left';
		if ($settings['scrolling_direction'] === 'yes') {
			$direction = 'right';
		}

		if ($settings['scrolling_axis'] === 'vertical') {
			$direction = ($settings['scrolling_direction'] === 'yes') ? 'down' : 'up';
		}

		$pause = ($settings['scrolling_pause'] === 'yes') ? 'true' : 'false';
		
		$scrolling_overflow_v   = isset( $settings['scrolling_overflow'] ) ? $settings['scrolling_overflow'] : 'no';
		$scrolling_overflow     = '';
		if ($scrolling_overflow_v == 'yes') {$scrolling_overflow = ' wpkoi-marquee-hidof';}

		$vertical_class = ($settings['scrolling_axis'] === 'vertical') ? ' wpkoi-marquee-vertical' : '';

		$vertical_height = isset($settings['vertical_scroll_height']['size']) ? $settings['vertical_scroll_height']['size'] : 300;
		$vertical_unit   = isset($settings['vertical_scroll_height']['unit']) ? $settings['vertical_scroll_height']['unit'] : 'px';

		echo '<div id="' . esc_attr($id) . '" class="wpkoi-marquee wpkoi-marquee-img' . esc_attr($scrolling_overflow) . esc_attr($vertical_class) . '" 
			data-speed="' . esc_attr($speed) . '" 
			data-gap="' . esc_attr($gap) . '" 
			data-direction="' . esc_attr($direction) . '" 
			data-duplicated="true" 
			data-startvisible="true"
			data-pauseonhover="' . esc_attr($pause) . '" 
			data-vheight="' . esc_attr($vertical_height . $vertical_unit) . '">';

		echo '<div class="wpkoi-scrolling-content">';

		foreach ( $settings['images'] as $image ) {

			if ( empty( $image['url'] ) ) {
				continue;
			}

			if ( ! empty( $image['id'] ) ) {
				$img_url = Group_Control_Image_Size::get_attachment_image_src( $image['id'], 'image', $settings );
			} else {
				$img_url = $image['url'];
			}

			$img_html = '<img src="' . esc_url($img_url) . '" alt="" />';

			if ( $settings['image_links'] === 'yes' ) {
				$img_html = '<a href="' . esc_url($img_url) . '" target="_blank" rel="noopener noreferrer">' . $img_html . '</a>';
			}

			echo $img_html;
		}

		echo '</div>';
		echo '</div>';
	}
	
	public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);

		wp_register_script('wpkoi-marquee-js',WPKOI_ELEMENTS_LITE_URL.'elements/scrolling-text/assets/jquery.marquee.min.js', [ 'elementor-frontend', 'jquery' ],'1.0', true);
		
		wp_register_style('wpkoi-scrolling-text',WPKOI_ELEMENTS_LITE_URL . 'elements/scrolling-text/assets/scrolling-text.css',false,WPKOI_ELEMENTS_LITE_VERSION);
	}

	public function get_script_depends() {
		return [ 'wpkoi-marquee-js' ];
	}

	public function get_style_depends() {
		return [ 'wpkoi-scrolling-text' ];
	}

	protected function content_template() {}
}


Plugin::instance()->widgets_manager->register( new Widget_WPKoi_Scrolling_Images() );