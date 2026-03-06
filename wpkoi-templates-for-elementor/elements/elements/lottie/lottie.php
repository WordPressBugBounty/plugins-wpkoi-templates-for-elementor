<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class WPKoi_Lottie_Animations extends Widget_Base {
		
	public function get_name() {
		return 'wpkoi-lottie-animations';
	}

	public function get_title() {
		return esc_html__( 'Lottie Animations', 'wpkoi-elements' );
	}

	public function get_icon() {
		return 'eicon-lottie';
	}

	public function get_categories() {
		return [ 'wpkoi-addons-for-elementor'];
	}

	public function get_keywords() {
		return [ 'wpkoi', 'lottie', 'animation', 'animations', 'svg' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

    public function get_custom_help_url() {
    	return 'https://wpkoi.com/wpkoi-elementor-templates-demo/elements/';
    }
	
	protected function register_controls() {

		// Section: Settings ---------
		$this->start_controls_section(
			'section_settings',
			[
				'label' => esc_html__( 'Lottie File', 'wpkoi-elements' ),
			]
		);

		$this->add_control(
			'source',
			[
				'label'   => __( 'File Source', 'wpkoi-elements' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'url'  => __( 'External URL', 'wpkoi-elements' ),
					'file' => __( 'Media File', 'wpkoi-elements' ),
				],
				'default' => 'url',
			]
		);

		$this->add_control(
			'json_url',
			[
				'label'       => __( 'JSON URL', 'wpkoi-elements' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default'	  => 'https://lottie.host/e3ebe0ef-6aa0-464f-977d-b29158370c96/MYL8fZhSSU.json',
				'description' => 'Get JSON code URL from <a href="https://lottiefiles.com/" target="_blank">here</a>',
				'label_block' => true,
				'condition'   => [
					'source' => 'url',
				],
			]
		);

		$this->add_control(
			'json_file',
			array(
				'label'              => __( 'Upload JSON File', 'wpkoi-elements' ),
				'type'               => Controls_Manager::MEDIA,
				'media_type'         => 'application/json',
				'frontend_available' => true,
				'condition'          => [
					'source' => 'file',
				]
			)
		);

		$this->add_control(
			'autoplay',
			[
				'label' => esc_html__( 'Autoplay', 'wpkoi-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'separator'   => 'before',
			]
		);

		$this->add_control(
			'loop',
			[
				'label' => esc_html__( 'Loop', 'wpkoi-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'reverse',
			[
				'label' => __( 'Reverse', 'wpkoi-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'condition' => [
					'trigger!' => 'scroll'
				]
			]
		);

		$this->add_control(
			'speed',
			array(
				'label'   => __( 'Animation Speed', 'wpkoi-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 1,
				'min'     => 0.1,
				'max'     => 3,
				'step'    => 0.1,
			)
		);

		$this->add_control(
			'trigger',
			[
				'label' => __( 'Trigger', 'wpkoi-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options'            => array(
					'none'     => __( 'None', 'wpkoi-elements' ),
					'hover'    => __( 'Hover', 'wpkoi-elements' ),
				),
				'frontend_available' => true,
			]
		);
		
		$this->add_responsive_control(
			'animation_size',
			array(
				'label'       => __( 'Size', 'wpkoi-elements' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px', '%' ),
				'default'     => array(
					'unit' => '%',
					'size' => 50,
				),
				'range'       => array(
					'px' => array(
						'min' => 1,
						'max' => 800,
					),
					'em' => array(
						'min' => 1,
						'max' => 30,
					),
				),
				'render_type' => 'template',
				'separator'   => 'before',
				'selectors'   => array(
					'{{WRAPPER}} .wpkoi-lottie-animations svg' => 'width: 100% !important; height: 100% !important;',
					'{{WRAPPER}} .wpkoi-lottie-animations' => 'width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important;',
				),
			)
		);
		
		$this->add_responsive_control(
			'rotate',
			array(
				'label'       => __( 'Rotate (degrees)', 'wpkoi-elements' ),
				'type'        => Controls_Manager::SLIDER,
				'description' => __( 'Set rotation value in degrees', 'wpkoi-elements' ),
				'range'       => array(
					'px' => array(
						'min' => -180,
						'max' => 180,
					),
				),
				'default'     => array(
					'size' => 0,
				),
				'selectors'   => array(
					'{{WRAPPER}} .wpkoi-lottie-animations' => 'transform: rotate({{SIZE}}deg)',
				),
			)
		);
		
		$this->add_responsive_control(
			'animation_align',
			array(
				'label'     => __( 'Alignment', 'wpkoi-elements' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'wpkoi-elements' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'wpkoi-elements' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'wpkoi-elements' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'center',
				'toggle'    => false,
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .wpkoi-lottie-animations-wrapper' => 'display: flex; justify-content: {{VALUE}}; align-items: {{VALUE}};',
				),
			)
		);
		
		$this->add_control(
			'lottie_renderer',
			[
				'label'        => __( 'Render As', 'wpkoi-elements' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => array(
					'svg'    => __( 'SVG', 'wpkoi-elements' ),
					'canvas' => __( 'Canvas', 'wpkoi-elements' ),
				),
				'default'      => 'svg',
				'prefix_class' => 'wpkoi-lottie-',
				'render_type'  => 'template',
				'separator'    => 'before',
			]
		);

		$this->add_control(
			'render_notice',
			[
				'raw'             => __( 'Set render type to canvas if you\'re having performance issues on the page.', 'wpkoi-elements' ),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);
		
		$this->add_control(
			'link_switcher',
			[
				'label' => __( 'Wrapper Link', 'wpkoi-elements' ),
				'type' => Controls_Manager::SWITCHER,
			]
		);
		
		$this->add_control(
			'link',
			array(
				'label'       => __( 'Link', 'wpkoi-elements' ),
				'type'        => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'default'     => array(
					'url' => '#',
				),
				'placeholder' => 'https://wpkoi.com/',
				'label_block' => true,
				'condition'   => array(
					'link_switcher'  => 'yes',
				),
			)
		);


		$this->end_controls_section(); // End Controls Section

		$this->start_controls_section(
			'lottie_styles',
			[
				'label' => __( 'Animation', 'wpkoi-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_lottie' );

		$this->start_controls_tab(
			'tab_lottie_normal',
			[
				'label' => __( 'Normal', 'wpkoi-elements' ),
			]
		);

		$this->add_control(
			'opacity',
			[
				'label'     => __( 'Opacity', 'wpkoi-elements' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .wpkoi-lottie-animations' => 'opacity: {{SIZE}}',
				),
			]
		);

		$this->add_control(
			'hover_transition',
			[
				'label' => esc_html__( 'Transition Duration', 'wpkoi-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.3,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpkoi-lottie-animations' => 'transition-duration: {{VALUE}}s;'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name'     => 'css_filters',
				'selector' => '{{WRAPPER}} .wpkoi-lottie-animations',
			)
		);

		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'tab_lottie_hover',
			[
				'label' => __( 'Hover', 'wpkoi-elements' ),
			]
		);

		$this->add_control(
			'hover_opacity',
			array(
				'label'     => __( 'Opacity', 'wpkoi-elements' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .wpkoi-lottie-animations:hover' => 'opacity: {{SIZE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name'     => 'hover_css_filters',
				'selector' => '{{WRAPPER}} .wpkoi-lottie-animations:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section(); // End Controls Section
	
	}

	public function lottie_attributes($settings) {
		$attributes = [
			'loop' => $settings['loop'],
			'autoplay' => $settings['autoplay'],
			'speed' => $settings['speed'],
			'trigger' => $settings['trigger'],
			'reverse' => $settings['reverse'],
			'scroll_start'  => isset( $settings['animate_view']['sizes']['start'] ) ? $settings['animate_view']['sizes']['start'] : '0',
			'scroll_end'    => isset( $settings['animate_view']['sizes']['end'] ) ? $settings['animate_view']['sizes']['end'] : '100',
			'lottie_renderer' => $settings['lottie_renderer']
		];

		return json_encode($attributes);
	}

	protected function render() {
		
		// Get Settings
		$settings = $this->get_settings_for_display();
		$lottie_json = '';

		if ( 'url' === $settings['source'] ) {

			$lottie_json = esc_url( $settings['json_url'] );

		} else {

			if ( ! empty( $settings['json_file']['id'] ) ) {

				$lottie_json = wp_get_attachment_url( $settings['json_file']['id'] );

			}
			
			if ( ! $lottie_json && ! empty( $settings['json_file']['url'] ) ) {

				$file_name = basename( $settings['json_file']['url'] );

				$file_name = basename( $settings['json_file']['url'] );

				$args = [
					'post_type'  => 'attachment',
					'post_status'=> 'inherit',
					'posts_per_page' => 1,
					'meta_query' => [
						[
							'key'     => '_wp_attached_file',
							'value'   => $file_name,
							'compare' => 'LIKE'
						]
					]
				];

				$attachments = get_posts( $args );

				if ( $attachments ) {

					$lottie_json = wp_get_attachment_url( $attachments[0]->ID );

				}
			}

		}

		if ( empty( $lottie_json ) ) {

			$lottie_json = WPKOI_ELEMENTS_LITE_URL . 'elements/lottie/assets/default.json';

		}

		$lottie_animation = 'yes' === $settings['link_switcher']
				? '<a href="'. esc_url($settings['link']['url']) .'"><div class="wpkoi-lottie-animations" data-settings="'. esc_attr($this->lottie_attributes($settings)) .'" data-json-url="'. esc_url($lottie_json) .'"></div></a>'
				: '<div class="wpkoi-lottie-animations" data-settings="'. esc_attr($this->lottie_attributes($settings)) .'" data-json-url="'. esc_url($lottie_json) .'"></div>';

		echo '<div class="wpkoi-lottie-animations-wrapper">';
			echo ''. $lottie_animation; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '</div>';
		
	}
	
	public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);

		wp_register_script('wpkoi-lottie',WPKOI_ELEMENTS_LITE_URL.'elements/lottie/assets/lottie.min.js', [ 'elementor-frontend', 'jquery' ],WPKOI_ELEMENTS_LITE_VERSION, true);
	}

	public function get_script_depends() {
		return [ 'wpkoi-lottie' ];
	}
}

Plugin::instance()->widgets_manager->register( new WPKoi_Lottie_Animations() );