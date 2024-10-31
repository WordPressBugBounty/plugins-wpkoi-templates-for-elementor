<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.


class Widget_WPKoi_Scrolling_Text extends Widget_Base {

	public function get_name() {
		return 'wpkoi-scrolling-text';
	}

	public function get_title() {
		return esc_html__( 'Scrolling Text', 'wpkoi-elements' );
	}

	public function get_icon() {
		return 'eicon-heading';
	}

    public function get_categories() {
		return [ 'wpkoi-addons-for-elementor' ];
	}
	
	public function get_help_url() {
		return 'https://wpkoi.com/wpkoi-templates-for-elementor/';
	}


	protected function register_controls() {


  		$this->start_controls_section(
			'section_content_heading',
			[
				'label' => __( 'Heading', 'wpkoi-elements' ),
			]
		);

		$this->add_control(
			'scrolling_text_content',
			[
				'label'       => __( 'Heading Text', 'wpkoi-elements' ),
				'type'        => Controls_Manager::TEXTAREA,
				'placeholder' => __( 'Add Your text here', 'wpkoi-elements' ),
				'default'     => __( 'Scrolling text...', 'wpkoi-elements' ),
			]
		);

		$this->add_control(
			'link',
			[
				'label'       => __( 'Link', 'wpkoi-elements' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => __( 'Paste URL or type', 'wpkoi-elements' ),
			]
		);

		$this->add_control(
			'header_size',
			[
				'label'   => __( 'HTML Tag', 'wpkoi-elements' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
						'h1'  => esc_html__( 'H1', 'wpkoi-elements' ),
						'h2'  => esc_html__( 'H2', 'wpkoi-elements' ),
						'h3'  => esc_html__( 'H3', 'wpkoi-elements' ),
						'h4'  => esc_html__( 'H4', 'wpkoi-elements' ),
						'h5'  => esc_html__( 'H5', 'wpkoi-elements' ),
						'h6'  => esc_html__( 'H6', 'wpkoi-elements' ),
						'div'  => esc_html__( 'div', 'wpkoi-elements' ),
						'span'  => esc_html__( 'span', 'wpkoi-elements' ),
						'p'  => esc_html__( 'p', 'wpkoi-elements' ),
					),
				'default' => 'h2',
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label'   => __( 'Alignment', 'wpkoi-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'wpkoi-elements' ),
						'icon'  => 'fas fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'wpkoi-elements' ),
						'icon'  => 'fas fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'wpkoi-elements' ),
						'icon'  => 'fas fa-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				],

			]
		);

		$this->end_controls_section();
		

		$this->start_controls_section(
			'section_style_main_heading',
			[
				'label'     => __( 'Scrolling style', 'wpkoi-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'scrolling_text_content!' => '',
				],
			]
		);

		$this->add_control(
			'main_heading_color',
			[
				'label'     => __( 'Color', 'wpkoi-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpkoi-marquee .wpkoi-scrolling-content' => 'color: {{VALUE}};',
				]
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

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'main_heading_typography',
				'selector' => '{{WRAPPER}} .wpkoi-marquee .wpkoi-scrolling-content',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Stroke::get_type(),
			[
				'name'     => 'main_heading_text_stroke',
				'selector' => '{{WRAPPER}} .wpkoi-marquee .wpkoi-scrolling-content'
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'main_heading_text_shadow',
				'selector' => '{{WRAPPER}} .wpkoi-marquee .wpkoi-scrolling-content'
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
					'{{WRAPPER}} .wpkoi-marquee .wpkoi-scrolling-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;'
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
            'scrolling_speed',
            [
                'label'   => __( 'Scrolling Speed', 'wpkoi-elements' ),
                'type'    => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 10,
					'unit' => 'px',
                ],
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
						'step' => 1,
					]
				],
				'separator' => 'before',
            ]
        );
		
		$this->add_control(
            'scrolling_gap',
            [
                'label'   => __( 'Gap between the tickers', 'wpkoi-elements' ),
                'type'    => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 20,
					'unit' => 'px',
                ],
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 200,
						'step' => 1,
					]
				]
            ]
        );
		
		$this->add_control(
			'scrolling_direction',
			[
				'label'        => __( 'Scrolling Direction', 'wpkoi-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'default' => 'no',
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


	protected function render( ) {

      	$settings         = $this->get_settings_for_display();
		$id               = $this->get_id();
		$heading_html     = [];
		$main_heading     = '';
		
		$scrolling_speed  	    = isset( $settings['scrolling_speed']['size'] ) ? $settings['scrolling_speed']['size'] : '10';
		$scrolling_speed		= $scrolling_speed * 10;
		$scrolling_gap   	    = isset( $settings['scrolling_gap']['size'] ) ? $settings['scrolling_gap']['size'] : '20';
		$scrolling_direction_v  = isset( $settings['scrolling_direction'] ) ? $settings['scrolling_direction'] : 'no';
		$scrolling_overflow_v   = isset( $settings['scrolling_overflow'] ) ? $settings['scrolling_overflow'] : 'no';
		$scrolling_direction    = 'left';
		if ($scrolling_direction_v == 'yes') {$scrolling_direction = 'right';}
		$scrolling_overflow     = '';
		if ($scrolling_overflow_v == 'yes') {$scrolling_overflow = ' wpkoi-marquee-hidof';}

		if ( empty( $settings['scrolling_text_content'] ) ) {
			return;
		}

		$this->add_render_attribute( 'heading', 'class', 'wpkoi-scrolling-content' );


		$this->add_render_attribute( 'scrolling_text_content', 'class', 'wpkoi-main-heading-inner' );
		$this->add_inline_editing_attributes( 'scrolling_text_content' );

		if ($settings['scrolling_text_content']) :

			$main_heading =  $settings['scrolling_text_content'];

		endif;


		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_render_attribute( 'url', 'href', esc_url($settings['link']['url']) );

			if ( $settings['link']['is_external'] ) {
				$this->add_render_attribute( 'url', 'target', '_blank' );
			}

			if ( ! empty( $settings['link']['nofollow'] ) ) {
				$this->add_render_attribute( 'url', 'rel', 'nofollow' );
			}

			$main_heading = sprintf( '<a %1$s>%2$s</a>', $this->get_render_attribute_string( 'url' ), $main_heading );
		}

		$heading_html[] = '<div id ="' . esc_attr( $id ) . '" class="wpkoi-marquee'.esc_attr($scrolling_overflow).'" data-speed="'.esc_attr($scrolling_speed).'" data-gap="'.esc_attr($scrolling_gap).'" data-direction="'.esc_attr($scrolling_direction).'" data-duplicated="true" data-startvisible="true">';
		
		// Validate header size
		$validated_header_size = in_array( $settings['header_size'], [ 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p' ], true ) ? $settings['header_size'] : 'h2';
		
		$heading_html[] = sprintf( '<%1$s %2$s>%3$s</%1$s>', $validated_header_size, $this->get_render_attribute_string( 'heading' ), $main_heading );
		
		$heading_html[] = '</div>';
		
		

		echo implode("", $heading_html);
	}
	
	public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);

		wp_register_script('wpkoi-marquee-js',WPKOI_ELEMENTS_LITE_URL.'elements/scrolling-text/assets/jquery.marquee.min.js', [ 'elementor-frontend', 'jquery' ],'1.0', true);
	}

	public function get_script_depends() {
		return [ 'wpkoi-marquee-js' ];
	}

	protected function content_template() {}
}


Plugin::instance()->widgets_manager->register( new Widget_WPKoi_Scrolling_Text() );