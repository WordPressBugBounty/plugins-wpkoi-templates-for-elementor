<?php

if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'WPKoi_Sticky_Container_Extension_Lite' ) ) {

	class WPKoi_Sticky_Container_Extension_Lite {

		private static $instance = null;

		public static function get_instance() {
			if ( null === self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}

		public function init() {

			// Add controls to Container Advanced tab
			add_action( 'elementor/element/container/section_layout/after_section_end', [ $this, 'register_sticky_controls' ], 10, 2 );
			add_action( 'elementor/frontend/container/before_render', [ $this, 'add_sticky_attributes' ] );

			add_action( 'elementor/frontend/before_enqueue_scripts', array( $this, 'enqueue_scripts' ), 9 );
			add_action( 'elementor/editor/after_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		}

		public function register_sticky_controls( $element, $args ) {

			$element->start_controls_section(
				'wpkoi_sticky_section',
				[
					'label' => __( 'Sticky Container (WPKoi)', 'wpkoi-elements' ),
					'tab'   => \Elementor\Controls_Manager::TAB_ADVANCED,
				]
			);

			// Enable Sticky
			$element->add_control(
				'wpkoi_sticky_enable',
				[
					'label'        => __( 'Enable Sticky', 'wpkoi-elements' ),
					'type'         => \Elementor\Controls_Manager::SWITCHER,
					'label_on'     => __( 'Yes', 'wpkoi-elements' ),
					'label_off'    => __( 'No', 'wpkoi-elements' ),
					'return_value' => 'yes',
					'default'      => '',
				]
			);
						
			$element->add_control(
				'wpkoi_sticky_subheading',
				array(
					'label' => esc_html__( 'Note: Sticky effects are visible only on the live site, not inside the editor preview.', 'wpkoi-elements' ),
					'type'  => Elementor\Controls_Manager::HEADING
				)
			);

			// Sticky Mode
			$element->add_control(
				'wpkoi_sticky_mode',
				[
					'label'     => __( 'Sticky Mode', 'wpkoi-elements' ),
					'type'      => \Elementor\Controls_Manager::SELECT,
					'default'   => 'parent',
					'options'   => [
						'viewport' => __( 'Viewport Only', 'wpkoi-elements' ),
						'parent'   => __( 'Stop At Parent Bottom', 'wpkoi-elements' ),
					],
					'condition' => [
						'wpkoi_sticky_enable' => 'yes',
					],
				]
			);

			// Top Offset (responsive)
			$element->add_responsive_control(
				'wpkoi_sticky_offset_top',
				[
					'label'      => __( 'Top Offset', 'wpkoi-elements' ),
					'type'       => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'default'    => [
						'size' => 0,
						'unit' => 'px',
					],
					'condition'  => [
						'wpkoi_sticky_enable' => 'yes',
					],
				]
			);

			// Bottom Offset
			$element->add_responsive_control(
				'wpkoi_sticky_offset_bottom',
				[
					'label'      => __( 'Bottom Offset', 'wpkoi-elements' ),
					'type'       => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'default'    => [
						'size' => 0,
						'unit' => 'px',
					],
					'condition'  => [
						'wpkoi_sticky_enable' => 'yes',
					],
				]
			);

			// Z-Index
			$element->add_control(
				'wpkoi_sticky_zindex',
				[
					'label'     => __( 'Z-Index', 'wpkoi-elements' ),
					'type'      => \Elementor\Controls_Manager::NUMBER,
					'default'   => 10,
					'condition' => [
						'wpkoi_sticky_enable' => 'yes',
					],
				]
			);

			// Disable On
			$element->add_control(
				'wpkoi_sticky_disable_on',
				[
					'label'     => __( 'Disable On', 'wpkoi-elements' ),
					'type'      => \Elementor\Controls_Manager::SELECT2,
					'multiple'  => true,
					'options'   => [
						'desktop' => __( 'Desktop', 'wpkoi-elements' ),
						'tablet'  => __( 'Tablet', 'wpkoi-elements' ),
						'mobile'  => __( 'Mobile', 'wpkoi-elements' ),
					],
					'condition' => [
						'wpkoi_sticky_enable' => 'yes',
					],
				]
			);
			
			$element->add_control(
				'wpkoi_sticky_stop_selector',
				[
					'label'       => __( 'Stop At Selector', 'wpkoi-elements' ),
					'type'        => \Elementor\Controls_Manager::TEXT,
					'placeholder' => esc_attr__( '.stop-here or #footer', 'wpkoi-elements' ),
					'description' => __( 'CSS selector where sticky should stop instead of parent bottom.', 'wpkoi-elements' ),
					'condition'   => [
						'wpkoi_sticky_enable' => 'yes',
					],
				]
			);
			
			$element->add_control(
				'wpkoi_sticky_parallax_enable',
				[
					'label' => __( 'Enable Parallax Effect', 'wpkoi-elements' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => __( 'Yes', 'wpkoi-elements' ),
					'label_off' => __( 'No', 'wpkoi-elements' ),
					'return_value' => 'yes',
					'default' => '',
					'condition' => [
						'wpkoi_sticky_enable' => 'yes',
					],
				]
			);
			
			$element->add_control(
				'wpkoi_sticky_parallax_speed',
				[
					'label' => __( 'Parallax Speed', 'wpkoi-elements' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'description' => __( 'Lower values create a slower scrolling effect.', 'wpkoi-elements' ),
					'size_units' => [],
					'range' => [
						'px' => [
							'min' => 0.1,
							'max' => 1,
							'step' => 0.1,
						],
					],
					'default' => [
						'size' => 0.8,
					],
					'condition' => [
						'wpkoi_sticky_enable' => 'yes',
						'wpkoi_sticky_parallax_enable' => 'yes',
					],
				]
			);

			$element->end_controls_section();
		}
		
		public function add_sticky_attributes( $element ) {

			if ( 'yes' !== $element->get_settings_for_display( 'wpkoi_sticky_enable' ) ) {
				return;
			}

			$settings = $element->get_settings_for_display();

			$mode     = isset( $settings['wpkoi_sticky_mode'] ) ? sanitize_key( $settings['wpkoi_sticky_mode'] ) : 'parent';
			$top      = isset( $settings['wpkoi_sticky_offset_top']['size'] ) ? floatval( $settings['wpkoi_sticky_offset_top']['size'] ) : 0;
			$bottom   = isset( $settings['wpkoi_sticky_offset_bottom']['size'] ) ? floatval( $settings['wpkoi_sticky_offset_bottom']['size'] ) : 0;
			$zindex   = isset( $settings['wpkoi_sticky_zindex'] ) ? intval( $settings['wpkoi_sticky_zindex'] ) : 10;

			$disable_on = [];
			if ( ! empty( $settings['wpkoi_sticky_disable_on'] ) && is_array( $settings['wpkoi_sticky_disable_on'] ) ) {
				$disable_on = array_map( 'sanitize_key', $settings['wpkoi_sticky_disable_on'] );
			}

			$stop_at = isset( $settings['wpkoi_sticky_stop_selector'] )
				? sanitize_text_field( $settings['wpkoi_sticky_stop_selector'] )
				: '';

			$parallax = isset( $settings['wpkoi_sticky_parallax_enable'] ) && $settings['wpkoi_sticky_parallax_enable'] === 'yes'
				? 'yes'
				: '';

			$parallax_speed = isset( $settings['wpkoi_sticky_parallax_speed']['size'] )
				? floatval( $settings['wpkoi_sticky_parallax_speed']['size'] )
				: '';

			$data = [
				'mode'           => $mode,
				'top'            => $top,
				'bottom'         => $bottom,
				'zindex'         => $zindex,
				'disable_on'     => $disable_on,
				'stop_at'        => $stop_at,
				'parallax'       => $parallax,
				'parallax_speed' => $parallax_speed,
			];

			$element->add_render_attribute(
				'_wrapper',
				'class',
				'wpkoi-sticky-container'
			);

			$element->add_render_attribute(
				'_wrapper',
				'data-wpkoi-sticky',
				esc_attr( wp_json_encode( $data ) )
			);
		}
		
		public function enqueue_scripts() {

			wp_enqueue_script( 'wpkoi-sticky-container', WPKOI_ELEMENTS_LITE_URL . 'elements/sticky-container/assets/sticky-container.js', array( 'jquery' ),  WPKOI_ELEMENTS_LITE_VERSION, true);
			
		}
	}
}

if ( ! function_exists( 'WPKoi_Sticky_Container_Extension_Lite' ) ) {
function WPKoi_Sticky_Container_Extension_Lite() {
	return WPKoi_Sticky_Container_Extension_Lite::get_instance();
}
}

add_action( 'elementor/init', function() {
	WPKoi_Sticky_Container_Extension_Lite()->init();
});