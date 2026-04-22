<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.


class Widget_Lite_WPKoi_Distorted_Heading extends Widget_Base {

	public function get_name() {
		return 'wpkoi-distorted-heading';
	}

	public function get_title() {
		return esc_html__( 'Interactive Text', 'wpkoi-elements' );
	}

	public function get_icon() {
		return 'eicon-heading';
	}

    public function get_categories() {
		return [ 'wpkoi-addons-for-elementor' ];
	}
	
	public function get_help_url() {
		return 'https://wpkoi.com/wpkoi-elementor-templates-demo/elements/distorted-heading/';
	}

	protected function register_controls() {

  		$this->start_controls_section(
			'section_content_heading',
			[
				'label' => __( 'Interactive Text', 'wpkoi-elements' ),
			]
		);
			
		$this->add_control(
			'distorted_heading_subheading',
			array(
				'label' => esc_html__( 'Note: Using many Distorted Heading or Distorted Image widgets on the same page may affect performance and cause preview issues in the Elementor editor. This does not affect the live frontend.', 'wpkoi-elements' ),
				'type'  => Controls_Manager::HEADING
			)
		);

		$this->add_control(
			'main_heading',
			[
				'label'       => __( 'Heading Text', 'wpkoi-elements' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Add Your text here', 'wpkoi-elements' ),
				'default'     => '',
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

		$this->add_control(
			'heading_distort_text',
			[
				'label'     => __( 'Distort Effect', 'wpkoi-elements' ),
				'type'      => Controls_Manager::HEADING
			]
		);

		$this->add_control(
			'main_heading_distort',
			[
				'label'        => __( 'Add Distort', 'wpkoi-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'prefix_class' => 'wpkoi-distort-',
				'render_type'  => 'template',
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'distort_type',
			[
				'label'   => __( 'Style', 'wpkoi-elements' ),
				'type'    => Controls_Manager::VISUAL_CHOICE,
				'label_block' => true,
				'options' => [
					'1'  => [
						'title' => '1',
						'image'  => WPKOI_ELEMENTS_LITE_URL . 'elements/distorted-heading/assets/style-1.webp'
					],
					'2'  => [
						'title' => '2',
						'image'  => WPKOI_ELEMENTS_LITE_URL . 'elements/distorted-heading/assets/style-2.webp'
					],
					'3'  => [
						'title' => '3',
						'image'  => WPKOI_ELEMENTS_LITE_URL . 'elements/distorted-heading/assets/style-3.webp'
					],
					'4'  => [
						'title' => '4',
						'image'  => WPKOI_ELEMENTS_LITE_URL . 'elements/distorted-heading/assets/style-4.webp'
					],
					'5'  => [
						'title' => '5',
						'image'  => WPKOI_ELEMENTS_LITE_URL . 'elements/distorted-heading/assets/style-5.webp'
					],
					'6'  => [
						'title' => '6',
						'image'  => WPKOI_ELEMENTS_LITE_URL . 'elements/distorted-heading/assets/style-6.webp'
					],
					'7'  => [
						'title' => '7',
						'image'  => WPKOI_ELEMENTS_LITE_URL . 'elements/distorted-heading/assets/style-7.webp'
					],
					'8'  => [
						'title' => '8',
						'image'  => WPKOI_ELEMENTS_LITE_URL . 'elements/distorted-heading/assets/style-8.webp'
					],
					'9'  => [
						'title' => '9',
						'image'  => WPKOI_ELEMENTS_LITE_URL . 'elements/distorted-heading/assets/style-9.webp'
					],
					'10'  => [
						'title' => '10',
						'image'  => WPKOI_ELEMENTS_LITE_URL . 'elements/distorted-heading/assets/style-10.webp'
					],
					'11'  => [
						'title' => '11',
						'image'  => WPKOI_ELEMENTS_LITE_URL . 'elements/distorted-heading/assets/style-11.webp'
					],
					'12'  => [
						'title' => '12',
						'image'  => WPKOI_ELEMENTS_LITE_URL . 'elements/distorted-heading/assets/style-12.webp'
					],
				],
        		'columns' => 2,
				'default' => '1',
                'condition'   => [
                    'main_heading_distort' => 'yes'
                ]
			]
		);
		
		$this->add_control(
			'distort_trigger',
			[
				'label'   => __( 'Trigger', 'wpkoi-elements' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
						'onscroll'  => esc_html__( 'On Scroll', 'wpkoi-elements' ),
						'autoplay'  => esc_html__( 'Autoplay', 'wpkoi-elements' ),
						'mousemove'  => esc_html__( 'Mouse move', 'wpkoi-elements' ),
					),
				'default' => 'onscroll',
                'condition'   => [
                    'main_heading_distort' => 'yes'
                ]
			]
		);

        $this->add_control(
            'distort_intensity',
            [
                'label'   => __( 'Intensity', 'wpkoi-elements' ),
                'type'    => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 5,
					'unit' => 'px',
                ],
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0.1,
						'max' => 10,
						'step' => 0.1,
					]
				],
                'condition'   => [
                    'main_heading_distort' => 'yes'
                ]
            ]
        );

		$this->end_controls_section();
		

		$this->start_controls_section(
			'section_style_main_heading',
			[
				'label'     => __( 'Text Styles', 'wpkoi-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'main_heading!' => '',
				],
			]
		);

		$this->add_control(
			'main_heading_color',
			[
				'label'     => __( 'Color', 'wpkoi-elements' ),
				'type'      => Controls_Manager::COLOR,
				'render_type'  => 'template',
				'selectors' => [
					'{{WRAPPER}} .wpkoi-distorted-heading .wpkoi-heading-title' => 'color: {{VALUE}};',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'main_heading_typography',
				'selector' => '{{WRAPPER}} .wpkoi-distorted-heading .wpkoi-heading-title',
				'exclude' => [ 'text_transform', 'font_size', 'font_style', 'text_decoration', 'line_height', 'letter_spacing', 'word_spacing' ],
			]
		);

		$this->add_control(
			'heading_distort_h_text',
			[
				'label'     => __( 'Text', 'wpkoi-elements' ),
				'type'      => Controls_Manager::HEADING,
				'condition'   => [
                    'main_heading_distort' => 'yes'
                ]
			]
		);

        $this->add_control(
            'distort_size',
            [
                'label'   => __( 'Text Size', 'wpkoi-elements' ),
                'type'    => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 100,
					'unit' => 'px',
                ],
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 300,
						'step' => 1,
					]
				],
                'condition'   => [
                    'main_heading_distort' => 'yes'
                ]
            ]
        );

		$this->add_control(
			'distort_fullwidth',
			[
				'label'        => __( 'Full Width', 'wpkoi-elements' ),
				'type'         => Controls_Manager::SWITCHER,
				'render_type'  => 'template',
                'condition'   => [
                    'main_heading_distort' => 'yes'
                ]
			]
		);

        $this->add_control(
            'distort_lineheight',
            [
                'label'   => __( 'Line Height', 'wpkoi-elements' ),
                'type'    => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 1.8,
					'unit' => 'px',
                ],
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0.5,
						'max' => 3,
						'step' => 0.1,
					]
				],
                'condition'   => [
                    'main_heading_distort' => 'yes'
                ]
            ]
        );

		$this->add_control(
			'heading_distort_padding_text',
			[
				'label'     => __( 'Padding', 'wpkoi-elements' ),
				'type'      => Controls_Manager::HEADING,
				'condition'   => [
                    'main_heading_distort' => 'yes'
                ]
			]
		);

        $this->add_control(
            'padding_top',
            [
                'label'   => __( 'Padding Top', 'wpkoi-elements' ),
                'type'    => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0,
					'unit' => 'px',
                ],
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					]
				],
                'condition'   => [
                    'main_heading_distort' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'padding_right',
            [
                'label'   => __( 'Padding Right', 'wpkoi-elements' ),
                'type'    => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0,
					'unit' => 'px',
                ],
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					]
				],
                'condition'   => [
                    'main_heading_distort' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'padding_bottom',
            [
                'label'   => __( 'Padding Bottom', 'wpkoi-elements' ),
                'type'    => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0,
					'unit' => 'px',
                ],
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					]
				],
                'condition'   => [
                    'main_heading_distort' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'padding_left',
            [
                'label'   => __( 'Padding Left', 'wpkoi-elements' ),
                'type'    => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0,
					'unit' => 'px',
                ],
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					]
				],
                'condition'   => [
                    'main_heading_distort' => 'yes'
                ]
            ]
        );

		$this->end_controls_section();

	}

	protected function render( ) {

      	$settings         = $this->get_settings_for_display();
		$id               = $this->get_id();
		$heading_html     = [];
		$main_heading     = '';
		$widget_id = 'wpkoi-heading-title-' . esc_attr($id);

		if ( empty( $settings['main_heading'] ) ) {
			return;
		}

		$this->add_render_attribute( 'heading', 'class', 'wpkoi-heading-title' );
		$this->add_render_attribute( 'main_heading', 'class', 'wpkoi-distorted-main-inner' );
		$this->add_inline_editing_attributes( 'main_heading' );
		
		
		
		$distortstyle = $settings["distort_type"];
		$font_family = isset( $settings["main_heading_typography_font_family"] ) ? $settings["main_heading_typography_font_family"] : 'Roboto';
		$font_weight = isset( $settings["main_heading_typography_font_weight"] ) ? $settings["main_heading_typography_font_weight"] : '500';
		
		$distort_size = ( isset( $settings["distort_size"]["size"] ) && is_numeric( $settings["distort_size"]["size"] ) ) ? $settings["distort_size"]["size"] : '100';
		$padding_top = ( isset( $settings["padding_top"]["size"] ) && is_numeric( $settings["padding_top"]["size"] ) ) ? $settings["padding_top"]["size"] : '0';
		$padding_right = ( isset( $settings["padding_right"]["size"] ) && is_numeric( $settings["padding_right"]["size"] ) ) ? $settings["padding_right"]["size"] : '0';
		$padding_bottom = ( isset( $settings["padding_bottom"]["size"] ) && is_numeric( $settings["padding_bottom"]["size"] ) ) ? $settings["padding_bottom"]["size"] : '0';
		$padding_left = ( isset( $settings["padding_left"]["size"] ) && is_numeric( $settings["padding_left"]["size"] ) ) ? $settings["padding_left"]["size"] : '0';
		$line_height = ( isset( $settings["distort_lineheight"]["size"] ) && is_numeric( $settings["distort_lineheight"]["size"] ) ) ? $settings["distort_lineheight"]["size"] : '1.8';
		$intensity = ( isset( $settings["distort_intensity"]["size"] ) && is_numeric( $settings["distort_intensity"]["size"] ) ) ? $settings["distort_intensity"]["size"] : '5';
		
		$distort_trigger = isset( $settings["distort_trigger"] ) ? $settings["distort_trigger"] : 'onscroll';
		$main_heading_color = isset( $settings["main_heading_color"] ) ? $settings["main_heading_color"] : '';
		if ( $font_family == '' ) { $font_family = 'Roboto'; }
		if ( $font_weight == '' ) { $font_weight = '500'; }
		
		$data = [
			'style' => $distortstyle,
			'font_family' => $font_family,
			'font_weight' => $font_weight,
			'size' => $distort_size,
			'line_height' => $line_height,
			'padding' => [
				$padding_top,
				$padding_right,
				$padding_bottom,
				$padding_left
			],
			'color' => $main_heading_color,
			'trigger' => $distort_trigger,
			'intensity' => $intensity
		];

		$this->add_render_attribute(
			'main_heading',
			'data-wpkoi-distort',
			esc_attr(wp_json_encode($data))
		);

		if ($settings['main_heading']) :

			$main_heading = '<div '.$this->get_render_attribute_string( 'main_heading' ).'  id="wpkoi-heading-title-' . esc_attr( $id ) . '" data-blotter><span>' . esc_html( $settings['main_heading'] ) . '</span></div>';

			$main_heading = '<div class="wpkoi-distorted-main">' . $main_heading . '</div>';

		endif;


		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_render_attribute( 'url', 'href', esc_url( $settings['link']['url'] ) );

			if ( $settings['link']['is_external'] ) {
				$this->add_render_attribute( 'url', 'target', '_blank' );
			}

			if ( ! empty( $settings['link']['nofollow'] ) ) {
				$this->add_render_attribute( 'url', 'rel', 'nofollow' );
			}

			$main_heading = sprintf( '<a %1$s>%2$s</a>', $this->get_render_attribute_string( 'url' ), $main_heading );
		}
		
		$distort_fullwidth = "";
		if ( 'yes' == $settings['distort_fullwidth'] ) {
			$distort_fullwidth = " wpkoi-dhfw";
		}

		$heading_html[] = '<div id ="' . esc_attr( $id ) . '" class="wpkoi-distorted-heading' . esc_attr( $distort_fullwidth ) . '">';
		
		$validated_header_size = $settings['header_size'];
		if ( ( $validated_header_size != 'h1' ) && ( $validated_header_size != 'h2' ) && ( $validated_header_size != 'h3' ) && ( $validated_header_size != 'h4' ) && ( $validated_header_size != 'h5' ) && ( $validated_header_size != 'h6' ) && ( $validated_header_size != 'div' ) && ( $validated_header_size != 'span' ) && ( $validated_header_size != 'p' ) ){
			$validated_header_size = 'h2';
		}
		
		$heading_html[] = sprintf( '<%1$s %2$s">%3$s</%1$s>', $validated_header_size, $this->get_render_attribute_string( 'heading' ), $main_heading );
		
		$heading_html[] = '</div>';

		echo implode("", $heading_html);
	}
	
	public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);

		wp_register_script('wpkoi-blotter-js',WPKOI_ELEMENTS_LITE_URL.'elements/distorted-heading/assets/blotter.min.js', [ 'elementor-frontend' ],WPKOI_ELEMENTS_LITE_VERSION, true);
		wp_register_script('wpkoi-distortmaterials-js',WPKOI_ELEMENTS_LITE_URL.'elements/distorted-heading/assets/materials.js', [ 'elementor-frontend' ],WPKOI_ELEMENTS_LITE_VERSION, true);
	}

	public function get_script_depends() {
		return [ 'wpkoi-blotter-js', 'wpkoi-distortmaterials-js' ];
	}

	protected function content_template() {}
}

Plugin::instance()->widgets_manager->register( new Widget_Lite_WPKoi_Distorted_Heading() );