<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'WPKoi_Elements_Lite_Effects_Extension' ) ) {

	/**
	 * Define WPKoi_Elements_Lite_Effects_Extension class
	 */
	class WPKoi_Elements_Lite_Effects_Extension {

		/**
		 * Sections Data
		 */
		public $sections_data = array();

		/**
		 * Columns Data
		 */
		public $columns_data = array();

		/**
		 * Widgets Data
		 */
		public $widgets_data = array();

		public $view_more_sections = array();


		public $default_widget_settings = array(
			
		);

		/**
		 * A reference to an instance of this class.
		 */
		private static $instance = null;

		/**
		 * Init Handler
		 */
		public function init() {
			add_action( 'elementor/element/common/_section_style/after_section_end', array( $this, 'after_common_section_responsive' ), 10, 2 );

			add_action( 'elementor/frontend/widget/before_render', array( $this, 'widget_before_render' ), 10, 1 );

			add_action( 'elementor/widget/before_render_content', array( $this, 'widget_before_render_content' ) );

			add_action( 'elementor/frontend/before_enqueue_scripts', array( $this, 'enqueue_scripts' ), 9 );
		}


		/**
		 * After section_layout callback
		 */
		public function after_common_section_responsive( $obj, $args ) {
			$obj->start_controls_section(
				'widget_wpkoi_tricks',
				array(
					'label' => esc_html__( 'Effects (WPKoi)', 'wpkoi-elements' ),
					'tab'   => Elementor\Controls_Manager::TAB_ADVANCED,
				)
			);
			
			$obj->add_control(
				'wpkoi_pulse_heading',
				array(
					'label' => esc_html__( 'Pulse', 'wpkoi-elements' ),
					'type'  => Elementor\Controls_Manager::HEADING,
				)
			);
			
			$obj->add_control(
				'wpkoi_widget_pulse',
				[
					'label'        => esc_html__( 'Use Non Stop Pulse?', 'wpkoi-elements' ),
					'type'         => Elementor\Controls_Manager::SWITCHER,
					'prefix_class' => 'wpkoi-pulse-effect-',
				]
			);
			
			$obj->add_control(
				'wpkoi_widget_pulse_subheading',
				array(
					'label' => esc_html__( 'Note: effects are visible only on the live site, not inside the editor preview.', 'wpkoi-elements' ),
					'type'  => Elementor\Controls_Manager::HEADING,
					'condition' => [
						'wpkoi_widget_pulse' => 'yes',
					],
				)
			);
			
			$obj->add_responsive_control(
				'wpkoi_widget_pulse_from',
				[
					'label'       => esc_html__( 'From Size', 'wpkoi-elements' ),
					'description' => esc_html__( '10 is the normal size', 'wpkoi-elements' ),
					'type'        => Elementor\Controls_Manager::SLIDER,
					'default' => [
						'size' => 10,
					],
					'size_units' => ['s'],
					'range'      => [
						'px' => [
							'min'  => 1,
							'max'  => 50,
							'step' => 1,
						],
					],
					'condition' => array(
						'wpkoi_widget_pulse' => 'yes',
					),
					'render_type'  => 'template',
				]
			);
			
			$obj->add_responsive_control(
				'wpkoi_widget_pulse_to',
				[
					'label'      => esc_html__( 'To Size', 'wpkoi-elements' ),
					'description' => esc_html__( '10 is the normal size', 'wpkoi-elements' ),
					'type'       => Elementor\Controls_Manager::SLIDER,
					'default' => [
						'size' => 11,
					],
					'size_units' => ['s'],
					'range'      => [
						'px' => [
							'min'  => 1,
							'max'  => 50,
							'step' => 1,
						],
					],
					'condition' => array(
						'wpkoi_widget_pulse' => 'yes',
					),
					'render_type'  => 'template',
				]
			);
			
			$obj->add_responsive_control(
				'wpkoi_widget_pulse_speed',
				[
					'label'      => esc_html__( 'Speed', 'wpkoi-elements' ),
					'type'       => Elementor\Controls_Manager::SLIDER,
					'default' => [
						'size' => 3,
					],
					'size_units' => ['s'],
					'range'      => [
						'px' => [
							'min'  => 0.5,
							'max'  => 30,
							'step' => 0.5,
						],
					],
					'condition' => array(
						'wpkoi_widget_pulse' => 'yes',
					),
					'render_type'  => 'template',
				]
			);

			$obj->add_control(
				'rotate_heading',
				array(
					'label' => esc_html__( 'Rotate', 'wpkoi-elements' ),
					'type'  => Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);
			
			$obj->add_control(
				'wpkoi_widget_rotate',
				[
					'label'        => esc_html__( 'Use Rotate?', 'wpkoi-elements' ),
					'type'         => Elementor\Controls_Manager::SWITCHER,
					'prefix_class' => 'wpkoi-rotate-effect-',
				]
			);
			
			$obj->add_control(
				'wpkoi_widget_rotate_nonstop',
				array(
					'label'        => esc_html__( 'Nonstop rotation', 'wpkoi-elements' ),
					'type'         => Elementor\Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Yes', 'wpkoi-elements' ),
					'label_off'    => esc_html__( 'No', 'wpkoi-elements' ),
					'return_value' => 'true',
					'default'      => 'false',
					'condition' => array(
						'wpkoi_widget_rotate' => 'yes',
					),
				)
			);
			
			$obj->add_responsive_control(
				'wpkoi_widget_rotate_nonstop_speed',
				[
					'label'      => esc_html__( 'Speed', 'wpkoi-elements' ),
					'type'       => Elementor\Controls_Manager::SLIDER,
					'default' => [
						'size' => 3,
					],
					'size_units' => ['s'],
					'range'      => [
						'px' => [
							'min'  => 0.5,
							'max'  => 30,
							'step' => 0.5,
						],
					],
					'condition' => array(
						'wpkoi_widget_rotate' => 'yes',
						'wpkoi_widget_rotate_nonstop' => 'true',
					),
					'selectors'  => array(
						'{{WRAPPER}}:not(.wpkoi-rotate-reverse-true)' => 'animation: wpkoirotation {{SIZE}}s infinite linear;',
    					'{{WRAPPER}}.wpkoi-rotate-reverse-true' => 'animation: wpkoirotationreverse {{SIZE}}s infinite linear;',
					),
					'render_type'  => 'template',
				]
			);
			
			$obj->add_control(
				'wpkoi_widget_rotate_reverse',
				array(
					'label'        => esc_html__( 'Reverse Direction', 'wpkoi-elements' ),
					'type'         => Elementor\Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Yes', 'wpkoi-elements' ),
					'label_off'    => esc_html__( 'No', 'wpkoi-elements' ),
					'return_value' => 'true',
					'default'      => 'false',
					'condition' => array(
						'wpkoi_widget_rotate' => 'yes',
						'wpkoi_widget_rotate_nonstop' => 'true',
					),
					'prefix_class' => 'wpkoi-rotate-reverse-',
				)
			);
			
			$obj->start_controls_tabs( 'wpkoi_widget_motion_effect_tabs' );

			$obj->start_controls_tab(
				'wpkoi_widget_motion_effect_tab_normal',
				[
					'label' => esc_html__( 'Normal', 'wpkoi-elements' ),
					'condition' => [
						'wpkoi_widget_rotate' => 'yes',
						'wpkoi_widget_rotate_nonstop!' => 'true'
					],
				]
			);
		
			$obj->add_responsive_control(
				'wpkoi_widget_effect_rotatex_normal',
				[
					'label'      => esc_html__( 'Rotate X', 'wpkoi-elements' ),
					'type'       => Elementor\Controls_Manager::SLIDER,
					'size_units' => ['px'],
					'range'      => [
						'px' => [
							'min'  => -180,
							'max'  => 180,
						],
					],
					'condition' => [
						'wpkoi_widget_rotate' => 'yes',
						'wpkoi_widget_rotate_nonstop!' => 'true'
					],
				]
			);
	
			$obj->add_responsive_control(
				'wpkoi_widget_effect_rotatey_normal',
				[
					'label'      => esc_html__( 'Rotate Y', 'wpkoi-elements' ),
					'type'       => Elementor\Controls_Manager::SLIDER,
					'size_units' => ['px'],
					'range'      => [
						'px' => [
							'min'  => -180,
							'max'  => 180,
						],
					],
					'condition' => [
						'wpkoi_widget_rotate' => 'yes',
						'wpkoi_widget_rotate_nonstop!' => 'true'
					],
				]
			);
	
	
			$obj->add_responsive_control(
				'wpkoi_widget_effect_rotatez_normal',
				[
					'label'   => __( 'Rotate Z', 'wpkoi-elements' ),
					'type'    => Elementor\Controls_Manager::SLIDER,
					'size_units' => ['px'],
					'range' => [
						'px' => [
							'min'  => -180,
							'max'  => 180,
						],
					],
					'selectors' => [
						'(desktop){{WRAPPER}}.wpkoi-rotate-effect-yes.elementor-widget' => 'transform: translate( {{wpkoi_widget_effect_transx_normal.SIZE || 0}}px, {{wpkoi_widget_effect_transy_normal.SIZE || 0}}px) rotateX({{wpkoi_widget_effect_rotatex_normal.SIZE || 0}}deg) rotateY({{wpkoi_widget_effect_rotatey_normal.SIZE || 0}}deg) rotateZ({{wpkoi_widget_effect_rotatez_normal.SIZE || 0}}deg);',
						'(tablet){{WRAPPER}}.wpkoi-rotate-effect-yes.elementor-widget' => 'transform: translate( {{wpkoi_widget_effect_transx_normal_tablet.SIZE || 0}}px, {{wpkoi_widget_effect_transy_normal_tablet.SIZE || 0}}px) rotateX({{wpkoi_widget_effect_rotatex_normal_tablet.SIZE || 0}}deg) rotateY({{wpkoi_widget_effect_rotatey_normal_tablet.SIZE || 0}}deg) rotateZ({{wpkoi_widget_effect_rotatez_normal_tablet.SIZE || 0}}deg);',
						'(mobile){{WRAPPER}}.wpkoi-rotate-effect-yes.elementor-widget' => 'transform: translate( {{wpkoi_widget_effect_transx_normal_mobile.SIZE || 0}}px, {{wpkoi_widget_effect_transy_normal_mobile.SIZE || 0}}px) rotateX({{wpkoi_widget_effect_rotatex_normal_mobile.SIZE || 0}}deg) rotateY({{wpkoi_widget_effect_rotatey_normal_mobile.SIZE || 0}}deg) rotateZ({{wpkoi_widget_effect_rotatez_normal_mobile.SIZE || 0}}deg);',
					],
					'condition' => [
						'wpkoi_widget_rotate' => 'yes',
						'wpkoi_widget_rotate_nonstop!' => 'true'
					],
				]
			);
	
			$obj->end_controls_tab();
	
			$obj->start_controls_tab(
				'wpkoi_widget_motion_effect_tab_hover',
				[
					'label' => esc_html__( 'Hover', 'wpkoi-elements' ),
					'condition' => [
						'wpkoi_widget_rotate' => 'yes',
						'wpkoi_widget_rotate_nonstop!' => 'true'
					],
				]
			);
	
			$obj->add_responsive_control(
				'wpkoi_widget_effect_rotatex_hover',
				[
					'label'      => esc_html__( 'Rotate X', 'wpkoi-elements' ),
					'type'       => Elementor\Controls_Manager::SLIDER,
					'size_units' => ['px'],
					'range'      => [
						'px' => [
							'min'  => -180,
							'max'  => 180,
						],
					],
					'condition' => [
						'wpkoi_widget_rotate' => 'yes',
						'wpkoi_widget_rotate_nonstop!' => 'true'
					],
				]
			);
	
			$obj->add_responsive_control(
				'wpkoi_widget_effect_rotatey_hover',
				[
					'label'      => esc_html__( 'Rotate Y', 'wpkoi-elements' ),
					'type'       => Elementor\Controls_Manager::SLIDER,
					'size_units' => ['px'],
					'range'      => [
						'px' => [
							'min'  => -180,
							'max'  => 180,
						],
					],
					'condition' => [
						'wpkoi_widget_rotate' => 'yes',
						'wpkoi_widget_rotate_nonstop!' => 'true'
					],
				]
			);
	
	
			$obj->add_responsive_control(
				'wpkoi_widget_effect_rotatez_hover',
				[
					'label'   => __( 'Rotate Z', 'wpkoi-elements' ),
					'type'    => Elementor\Controls_Manager::SLIDER,
					'size_units' => ['px'],
					'range' => [
						'px' => [
							'min'  => -180,
							'max'  => 180,

						],
					],
					'selectors' => [
						'(desktop){{WRAPPER}}.wpkoi-rotate-effect-yes.elementor-widget:hover' => 'transform: translate( {{wpkoi_widget_effect_transx_hover.SIZE || 0}}px, {{wpkoi_widget_effect_transy_hover.SIZE || 0}}px) rotateX({{wpkoi_widget_effect_rotatex_hover.SIZE || 0}}deg) rotateY({{wpkoi_widget_effect_rotatey_hover.SIZE || 0}}deg) rotateZ({{wpkoi_widget_effect_rotatez_hover.SIZE || 0}}deg);',
						'(tablet){{WRAPPER}}.wpkoi-rotate-effect-yes.elementor-widget:hover' => 'transform: translate( {{wpkoi_widget_effect_transx_hover_tablet.SIZE || 0}}px, {{wpkoi_widget_effect_transy_hover_tablet.SIZE || 0}}px) rotateX({{wpkoi_widget_effect_rotatex_hover_tablet.SIZE || 0}}deg) rotateY({{wpkoi_widget_effect_rotatey_hover_tablet.SIZE || 0}}deg) rotateZ({{wpkoi_widget_effect_rotatez_hover_tablet.SIZE || 0}}deg);',
						'(mobile){{WRAPPER}}.wpkoi-rotate-effect-yes.elementor-widget:hover' => 'transform: translate( {{wpkoi_widget_effect_transx_hover_mobile.SIZE || 0}}px, {{wpkoi_widget_effect_transy_hover_mobile.SIZE || 0}}px) rotateX({{wpkoi_widget_effect_rotatex_hover_mobile.SIZE || 0}}deg) rotateY({{wpkoi_widget_effect_rotatey_hover_mobile.SIZE || 0}}deg) rotateZ({{wpkoi_widget_effect_rotatez_hover_mobile.SIZE || 0}}deg);',
					],
					'condition' => [
						'wpkoi_widget_rotate' => 'yes',
						'wpkoi_widget_rotate_nonstop!' => 'true'
					],
				]
			);
	
	
			$obj->end_controls_tab();
	
			$obj->end_controls_tabs();
			
			$obj->end_controls_section();

		}

		public function widget_before_render( $widget ) {
			$data     = $widget->get_data();
			$settings = $data['settings'];

			$settings = wp_parse_args( $settings, $this->default_widget_settings );

			$widget_settings = array();
			
			$parallax_settings = $widget->get_settings_for_display();
			
			if( isset($parallax_settings['wpkoi_widget_pulse']) && $parallax_settings['wpkoi_widget_pulse'] == 'yes' ) {
				if ( $parallax_settings['wpkoi_widget_pulse_from'] ) {
					$wpkoi_widget_pulse_from   = isset( $parallax_settings['wpkoi_widget_pulse_from']['size'] ) ? $parallax_settings['wpkoi_widget_pulse_from']['size'] : '10';
					$widget->add_render_attribute( '_wrapper', 'data-wpkoipulse-from', esc_attr( $wpkoi_widget_pulse_from ) );
				}
				if ( $parallax_settings['wpkoi_widget_pulse_to'] ) {
					$wpkoi_widget_pulse_to   = isset( $parallax_settings['wpkoi_widget_pulse_to']['size'] ) ? $parallax_settings['wpkoi_widget_pulse_to']['size'] : '10';
					$widget->add_render_attribute( '_wrapper', 'data-wpkoipulse-to', esc_attr( $wpkoi_widget_pulse_to ) );
				}
				if ( $parallax_settings['wpkoi_widget_pulse_speed'] ) {
					$wpkoi_widget_pulse_speed   = isset( $parallax_settings['wpkoi_widget_pulse_speed']['size'] ) ? $parallax_settings['wpkoi_widget_pulse_speed']['size'] : '3';
					$widget->add_render_attribute( '_wrapper', 'data-wpkoipulse-speed', esc_attr( $wpkoi_widget_pulse_speed ) );
				}
			}
		
			$widget_settings = apply_filters(
				'wpkoi-tricks/frontend/widget/settings',
				$widget_settings,
				$widget,
				$this
			);

			if ( ! empty( $widget_settings ) ) {
				$widget->add_render_attribute( '_wrapper', array(
					'data-wpkoi-tricks-settings' => wp_json_encode( $widget_settings ),
				) );
			}

			$this->widgets_data[ $data['id'] ] = $widget_settings;
		}

		public function widget_before_render_content( $widget ) {

			$data     = $widget->get_data();
			$settings = $data['settings'];

			$settings = wp_parse_args( $settings, $this->default_widget_settings );
			$settings = apply_filters( 'wpkoi-tricks/frontend/widget-content/settings', $settings, $widget, $this );

			
		}

		public function enqueue_scripts() {

			wpkoi_elements_lite_integration()->elements_data['sections'] = $this->sections_data;
			wpkoi_elements_lite_integration()->elements_data['columns'] = $this->columns_data;
			wpkoi_elements_lite_integration()->elements_data['widgets'] = $this->widgets_data;
		}

		/**
		 * Returns the instance.
		 */
		public static function get_instance() {
			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}
	}
}

if ( ! class_exists( 'WPKoi_Scroll_Effects_Lite_Extension' ) ) {

	class WPKoi_Scroll_Effects_Lite_Extension {

		/**
		 * Sections Data
		 */
		public $sections_data = array();

		/**
		 * Columns Data
		 */
		public $columns_data = array();

		/**
		 * Widgets Data
		 */
		public $widgets_data = array();

		public $view_more_sections = array();


		public $default_widget_settings = array();

		/**
		 * A reference to an instance of this class.
		 */
		private static $instance = null;

		/**
		 * Init Handler
		 */
		public function init() {
			
			
			add_action( 'elementor/element/container/section_layout/after_section_end', [ $this, 'register_controls' ], 10, 2 );
			add_action( 'elementor/frontend/container/before_render', [ $this, 'before_render' ], 10, 1 );
			
			add_action( 'elementor/element/common/_section_style/after_section_end', array( $this, 'register_controls' ), 10, 2 );
			add_action( 'elementor/frontend/widget/before_render', array( $this, 'before_render' ), 10, 1 );
			
			add_action( 'elementor/frontend/before_enqueue_scripts', array( $this, 'enqueue_scripts' ), 9 );
		}


		/**
		 * After section_layout callback
		 */
		public function register_controls( $obj, $args ) {
			$obj->start_controls_section(
				'wpkoi_container_motion_effects',
				array(
					'label' => esc_html__( 'Scroll Effects (WPKoi)', 'wpkoi-elements' ),
					'tab'   => Elementor\Controls_Manager::TAB_ADVANCED,
				)
			);
			
			$obj->add_control(
				'adv_parallax_effects_show',
				[
					'label'        => esc_html__( 'Use On scroll effects?', 'wpkoi-elements' ),
					'type'         => Elementor\Controls_Manager::SWITCHER,
					'default'      => '',
					'return_value' => 'yes',
					'render_type'  => 'none',
				]
			);
			
			$obj->add_control(
				'adv_parallax_subheading',
				array(
					'label' => esc_html__( 'Note: effects are visible only on the live site, not inside the editor preview.', 'wpkoi-elements' ),
					'type'  => Elementor\Controls_Manager::HEADING,
					'condition' => [
						'adv_parallax_effects_show' => 'yes',
					],
				)
			);
		
			$obj->add_control(
				'adv_parallax_effects_y',
				[
					'label' => __( 'Vertical Parallax (px)', 'wpkoi-elements' ),
					'type' => Elementor\Controls_Manager::POPOVER_TOGGLE,
					'condition' => [
						'adv_parallax_effects_show' => 'yes',
					],
					'render_type'  => 'none'
				]
			);
	
			$obj->start_popover();
	
			$obj->add_control(
				'adv_parallax_effects_y_start',
				[
					'label'       => esc_html__( 'Start', 'wpkoi-elements' ),
					'type'        => Elementor\Controls_Manager::SLIDER,
					'range'       => [
						'px' => [
							'min'   => -500,
							'max'   => 500,
							'step' => 10,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 50,
					],
					'render_type'  => 'none',
					'condition'    => [
						'adv_parallax_effects_show' => 'yes',
					],
				]
			);
	
			$obj->add_control(
				'adv_parallax_effects_y_end',
				[
					'label'       => esc_html__( 'End', 'wpkoi-elements' ),
					'type'        => Elementor\Controls_Manager::SLIDER,
					'range'       => [
						'px' => [
							'min'   => -500,
							'max'   => 500,
							'step' => 10,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 0,
					],
					'render_type'  => 'none',
					'condition'    => [
						'adv_parallax_effects_show' => 'yes',
					],
				]
			);
	
	
	
			$obj->end_popover();
		
			$obj->add_control(
				'adv_parallax_effects_yp',
				[
					'label' => __( 'Vertical Parallax (%)', 'wpkoi-elements' ),
					'type' => Elementor\Controls_Manager::POPOVER_TOGGLE,
					'condition' => [
						'adv_parallax_effects_show' => 'yes',
					],
					'render_type'  => 'none'
				]
			);
	
			$obj->start_popover();
	
			$obj->add_control(
				'adv_parallax_effects_yp_start',
				[
					'label'       => esc_html__( 'Start', 'wpkoi-elements' ),
					'type'        => Elementor\Controls_Manager::SLIDER,
					'range'       => [
						'px' => [
							'min'   => -200,
							'max'   => 200,
							'step' => 1,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 0,
					],
					'render_type'  => 'none',
					'condition'    => [
						'adv_parallax_effects_show' => 'yes',
					],
				]
			);
	
			$obj->add_control(
				'adv_parallax_effects_yp_end',
				[
					'label'       => esc_html__( 'End', 'wpkoi-elements' ),
					'type'        => Elementor\Controls_Manager::SLIDER,
					'range'       => [
						'px' => [
							'min'   => -200,
							'max'   => 200,
							'step' => 1,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 0,
					],
					'render_type'  => 'none',
					'condition'    => [
						'adv_parallax_effects_show' => 'yes',
					],
				]
			);
	
	
	
			$obj->end_popover();
	
	
			$obj->add_control(
				'adv_parallax_effects_x',
				[
					'label' => __( 'Horizontal Parallax (px)', 'wpkoi-elements' ),
					'type' => Elementor\Controls_Manager::POPOVER_TOGGLE,
					'condition' => [
						'adv_parallax_effects_show' => 'yes',
					],
					'render_type' => 'none',
				]
			);
	
			$obj->start_popover();
	
			$obj->add_control(
				'adv_parallax_effects_x_start',
				[
					'label'       => esc_html__( 'Start', 'wpkoi-elements' ),
					'type'        => Elementor\Controls_Manager::SLIDER,
					'range'       => [
						'px' => [
							'min'   => -500,
							'max'   => 500,
							'step' => 10,
						],
					],
	
					'condition'    => [
						'adv_parallax_effects_show' => 'yes',
					],
					'render_type' => 'none',
				]
			);
	
			$obj->add_control(
				'adv_parallax_effects_x_end',
				[
					'label'       => esc_html__( 'End', 'wpkoi-elements' ),
					'type'        => Elementor\Controls_Manager::SLIDER,
					'range'       => [
						'px' => [
							'min'   => -500,
							'max'   => 500,
							'step' => 10,
						],
					],
					'condition'    => [
						'adv_parallax_effects_show' => 'yes',
					],
					'render_type' => 'none',
				]
			);
	
			$obj->end_popover();
		
			$obj->add_control(
				'adv_parallax_effects_xp',
				[
					'label' => __( 'Horizontal Parallax (%)', 'wpkoi-elements' ),
					'type' => Elementor\Controls_Manager::POPOVER_TOGGLE,
					'condition' => [
						'adv_parallax_effects_show' => 'yes',
					],
					'render_type'  => 'none'
				]
			);
	
			$obj->start_popover();
	
			$obj->add_control(
				'adv_parallax_effects_xp_start',
				[
					'label'       => esc_html__( 'Start', 'wpkoi-elements' ),
					'type'        => Elementor\Controls_Manager::SLIDER,
					'range'       => [
						'px' => [
							'min'   => -200,
							'max'   => 200,
							'step' => 1,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 0,
					],
					'render_type'  => 'none',
					'condition'    => [
						'adv_parallax_effects_show' => 'yes',
					],
				]
			);
	
			$obj->add_control(
				'adv_parallax_effects_xp_end',
				[
					'label'       => esc_html__( 'End', 'wpkoi-elements' ),
					'type'        => Elementor\Controls_Manager::SLIDER,
					'range'       => [
						'px' => [
							'min'   => -200,
							'max'   => 200,
							'step' => 1,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 0,
					],
					'render_type'  => 'none',
					'condition'    => [
						'adv_parallax_effects_show' => 'yes',
					],
				]
			);
	
	
	
			$obj->end_popover();
			
			$obj->add_control(
				'adv_parallax_effects_opacity',
				[
					'label' => __( 'Opacity', 'wpkoi-elements' ),
					'type' => Elementor\Controls_Manager::POPOVER_TOGGLE,
					'condition' => [
						'adv_parallax_effects_show' => 'yes',
					],
					'render_type' => 'none',
				]
			);
	
			$obj->start_popover();
	
			$obj->add_control(
				'adv_parallax_effects_opacity_start',
				[
					'label'       => esc_html__( 'Start', 'wpkoi-elements' ),
					'type'        => Elementor\Controls_Manager::SLIDER,
					'range'       => [
						'px' => [
							'min'   => 1,
							'max'   => 100,
							'step' => 1,
						],
					],
					'condition'    => [
						'adv_parallax_effects_show' => 'yes',
					],
					'render_type' => 'none',
				]
			);
	
			$obj->add_control(
				'adv_parallax_effects_opacity_end',
				[
					'label'       => esc_html__( 'End', 'wpkoi-elements' ),
					'type'        => Elementor\Controls_Manager::SLIDER,
					'range'       => [
						'px' => [
							'min'   => 1,
							'max'   => 100,
							'step' => 1,
						],
					],
					'condition'    => [
						'adv_parallax_effects_show' => 'yes',
					],
					'render_type' => 'none',
				]
			);
	
			$obj->end_popover();
		
			$obj->add_control(
				'adv_parallax_effects_rotate',
				[
					'label' => __( 'Rotate', 'wpkoi-elements' ),
					'type' => Elementor\Controls_Manager::POPOVER_TOGGLE,
					'condition' => [
						'adv_parallax_effects_show' => 'yes',
					],
					'render_type' => 'none',
				]
			);
	
			$obj->start_popover();
	
			$obj->add_control(
				'adv_parallax_effects_rotate_value_start',
				[
					'label'       => esc_html__( 'Start', 'wpkoi-elements' ),
					'type'        => Elementor\Controls_Manager::SLIDER,
					'range'       => [
						'px' => [
							'min'  => -360,
							'max'  => 360,
							'step' => 5,
						],
					],
					'condition'    => [
						'adv_parallax_effects_show' => 'yes',
					],
					'render_type' => 'none',
				]
			);
	
			$obj->add_control(
				'adv_parallax_effects_rotate_value_end',
				[
					'label'       => esc_html__( 'End', 'wpkoi-elements' ),
					'type'        => Elementor\Controls_Manager::SLIDER,
					'range'       => [
						'px' => [
							'min'  => -360,
							'max'  => 360,
							'step' => 5,
						],
					],
					'condition'    => [
						'adv_parallax_effects_show' => 'yes',
					],
					'render_type' => 'none',
				]
			);
	
			$obj->end_popover();
	
			$obj->add_control(
				'adv_parallax_effects_scale',
				[
					'label' => __( 'Scale', 'wpkoi-elements' ),
					'type' => Elementor\Controls_Manager::POPOVER_TOGGLE,
					'condition' => [
						'adv_parallax_effects_show' => 'yes',
					],
					'render_type' => 'none',
				]
			);
	
			$obj->start_popover();
	
			$obj->add_control(
				'adv_parallax_effects_scale_start_value',
				[
					'label'       => esc_html__( 'Start', 'wpkoi-elements' ),
					'type'        => Elementor\Controls_Manager::SLIDER,
					'range'       => [
						'px' => [
							'min'  => 0,
							'max'  => 10,
							'step' => 0.1,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 1,
					],
					'condition'    => [
						'adv_parallax_effects_show' => 'yes',
					],
					'render_type' => 'none',
				]
			);
	
			$obj->add_control(
				'adv_parallax_effects_scale_value',
				[
					'label'       => esc_html__( 'End', 'wpkoi-elements' ),
					'type'        => Elementor\Controls_Manager::SLIDER,
					'range'       => [
						'px' => [
							'min'  => 0,
							'max'  => 10,
							'step' => 0.1,
						],
					],
					'condition'    => [
						'adv_parallax_effects_show' => 'yes',
					],
					'render_type' => 'none',
				]
			);
	
			$obj->end_popover();
	
			$obj->add_control(
				'adv_parallax_effects_blur',
				[
					'label' => __( 'Blur', 'wpkoi-elements' ),
					'type' => Elementor\Controls_Manager::POPOVER_TOGGLE,
					'condition' => [
						'adv_parallax_effects_show' => 'yes',
					],
					'render_type' => 'none',
				]
			);
	
			$obj->start_popover();
	
			$obj->add_control(
				'adv_parallax_effects_blur_start',
				[
					'label'       => esc_html__( 'Start', 'wpkoi-elements' ),
					'type'        => Elementor\Controls_Manager::SLIDER,
					'range'       => [
						'px' => [
							'min'   => 0,
							'max'   => 20,
							'step' => 1,
						],
					],
					'condition'    => [
						'adv_parallax_effects_show' => 'yes',
					],
					'render_type' => 'none',
				]
			);
	
			$obj->add_control(
				'adv_parallax_effects_blur_end',
				[
					'label'       => esc_html__( 'End', 'wpkoi-elements' ),
					'type'        => Elementor\Controls_Manager::SLIDER,
					'range'       => [
						'px' => [
							'min'   => 0,
							'max'   => 20,
							'step' => 1,
						],
					],
					'condition'    => [
						'adv_parallax_effects_show' => 'yes',
					],
					'render_type' => 'none',
				]
			);
	
			$obj->end_popover();
	
			$obj->add_control(
				'adv_parallax_effects_hue',
				[
					'label' => __( 'Hue', 'wpkoi-elements' ),
					'type' => Elementor\Controls_Manager::POPOVER_TOGGLE,
					'condition' => [
						'adv_parallax_effects_show' => 'yes',
					],
					'render_type' => 'none',
				]
			);
	
			$obj->start_popover();
	
			$obj->add_control(
				'adv_parallax_effects_hue_value',
				[
					'label'       => esc_html__( 'Value', 'wpkoi-elements' ),
					'type'        => Elementor\Controls_Manager::SLIDER,
					'range'       => [
						'px' => [
							'min'  => 0,
							'max'  => 360,
							'step' => 1,
						],
					],
					'condition'    => [
						'adv_parallax_effects_show' => 'yes',
					],
					'render_type' => 'none',
				]
			);
	
			$obj->end_popover();
	
	
			$obj->add_control(
				'adv_parallax_effects_grayscale',
				[
					'label' => __( 'Grayscale', 'wpkoi-elements' ),
					'type' => Elementor\Controls_Manager::POPOVER_TOGGLE,
					'condition' => [
						'adv_parallax_effects_show' => 'yes',
					],
					'render_type' => 'none',
				]
			);
	
			$obj->start_popover();
	
			$obj->add_control(
				'adv_parallax_effects_grayscale_s_value',
				[
					'label'       => esc_html__( 'Start', 'wpkoi-elements' ),
					'type'        => Elementor\Controls_Manager::SLIDER,
					'range'       => [
						'%' => [
							'min'  => 0,
							'max'  => 100,
							'step' => 1,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 0,
					],
					'condition'    => [
						'adv_parallax_effects_show' => 'yes',
					],
					'render_type' => 'none',
				]
			);
	
			$obj->add_control(
				'adv_parallax_effects_grayscale_value',
				[
					'label'       => esc_html__( 'End', 'wpkoi-elements' ),
					'type'        => Elementor\Controls_Manager::SLIDER,
					'range'       => [
						'%' => [
							'min'  => 0,
							'max'  => 100,
							'step' => 1,
						],
					],
					'condition'    => [
						'adv_parallax_effects_show' => 'yes',
					],
					'render_type' => 'none',
				]
			);
	
	
			$obj->end_popover();
	
	
			$obj->add_control(
				'adv_parallax_effects_saturate',
				[
					'label' => __( 'Saturate', 'wpkoi-elements' ),
					'type' => Elementor\Controls_Manager::POPOVER_TOGGLE,
					'condition' => [
						'adv_parallax_effects_show' => 'yes',
					],
					'render_type' => 'none',
				]
			);
	
			$obj->start_popover();
	
			$obj->add_control(
				'adv_parallax_effects_saturate_s_value',
				[
					'label'       => esc_html__( 'Start', 'wpkoi-elements' ),
					'type'        => Elementor\Controls_Manager::SLIDER,
					'range'       => [
						'%' => [
							'min'  => 0,
							'max'  => 100,
							'step' => 1,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 0,
					],
					'condition'    => [
						'adv_parallax_effects_show' => 'yes',
					],
					'render_type' => 'none',
				]
			);
	
			$obj->add_control(
				'adv_parallax_effects_saturate_value',
				[
					'label'       => esc_html__( 'End', 'wpkoi-elements' ),
					'type'        => Elementor\Controls_Manager::SLIDER,
					'range'       => [
						'%' => [
							'min'  => 0,
							'max'  => 100,
							'step' => 1,
						],
					],
					'condition'    => [
						'adv_parallax_effects_show' => 'yes',
					],
					'render_type' => 'none',
				]
			);
	
	
			$obj->end_popover();
	
	
			$obj->add_control(
				'adv_parallax_effects_sepia',
				[
					'label' => __( 'Sepia', 'wpkoi-elements' ),
					'type' => Elementor\Controls_Manager::POPOVER_TOGGLE,
					'condition' => [
						'adv_parallax_effects_show' => 'yes',
					],
					'render_type' => 'none',
				]
			);
	
			$obj->start_popover();
	
			$obj->add_control(
				'adv_parallax_effects_sepia_s_value',
				[
					'label'       => esc_html__( 'Start', 'wpkoi-elements' ),
					'type'        => Elementor\Controls_Manager::SLIDER,
					'range'       => [
						'%' => [
							'min'  => 0,
							'max'  => 100,
							'step' => 1,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 0,
					],
					'condition'    => [
						'adv_parallax_effects_show' => 'yes',
					],
					'render_type' => 'none',
				]
			);
	
			$obj->add_control(
				'adv_parallax_effects_sepia_value',
				[
					'label'       => esc_html__( 'End', 'wpkoi-elements' ),
					'type'        => Elementor\Controls_Manager::SLIDER,
					'range'       => [
						'%' => [
							'min'  => 0,
							'max'  => 100,
							'step' => 1,
						],
					],
					'condition'    => [
						'adv_parallax_effects_show' => 'yes',
					],
					'render_type' => 'none',
				]
			);
	
	
			$obj->end_popover();
			
			$obj->add_control(
				'adv_parallax_effects_color',
				[
					'label' => __( 'Color', 'wpkoi-elements' ),
					'type' => Elementor\Controls_Manager::POPOVER_TOGGLE,
					'condition' => [
						'adv_parallax_effects_show' => 'yes',
					],
					'render_type' => 'none',
				]
			);
	
			$obj->start_popover();
			
			$obj->add_control(
				'adv_parallax_effects_color_heading',
				array(
					'label' => esc_html__( 'Add color for both. For the best result add only HEX colors.', 'wpkoi-elements' ),
					'type'  => Elementor\Controls_Manager::HEADING,
				)
			);
			
			$obj->add_control(
				'adv_parallax_effects_colors',
				array(
					'label' => esc_html__( 'Start', 'wpkoi-elements' ),
					'type' => Elementor\Controls_Manager::COLOR,
					'alpha' => false,
					'condition'    => [
						'adv_parallax_effects_show' => 'yes',
					],
					'render_type' => 'none',
				)
			);
			
			$obj->add_control(
				'adv_parallax_effects_colore',
				array(
					'label' => esc_html__( 'End', 'wpkoi-elements' ),
					'type' => Elementor\Controls_Manager::COLOR,
					'alpha' => false,
					'condition'    => [
						'adv_parallax_effects_show' => 'yes',
					],
					'render_type' => 'none',
				)
			);
	
			$obj->end_popover();
			
			$obj->add_control(
				'adv_parallax_effects_bgcolor',
				[
					'label' => __( 'Background Color', 'wpkoi-elements' ),
					'type' => Elementor\Controls_Manager::POPOVER_TOGGLE,
					'condition' => [
						'adv_parallax_effects_show' => 'yes',
					],
					'render_type' => 'none',
				]
			);
	
			$obj->start_popover();
			
			$obj->add_control(
				'adv_parallax_effects_bgcolor_heading',
				array(
					'label' => esc_html__( 'Add color for both. For the best result add only HEX colors.', 'wpkoi-elements' ),
					'type'  => Elementor\Controls_Manager::HEADING,
				)
			);
			
			$obj->add_control(
				'adv_parallax_effects_bgcolors',
				array(
					'label' => esc_html__( 'Start', 'wpkoi-elements' ),
					'type' => Elementor\Controls_Manager::COLOR,
					'alpha' => false,
					'condition'    => [
						'adv_parallax_effects_show' => 'yes',
					],
					'render_type' => 'none',
				)
			);
			
			$obj->add_control(
				'adv_parallax_effects_bgcolore',
				array(
					'label' => esc_html__( 'End', 'wpkoi-elements' ),
					'type' => Elementor\Controls_Manager::COLOR,
					'alpha' => false,
					'condition'    => [
						'adv_parallax_effects_show' => 'yes',
					],
					'render_type' => 'none',
				)
			);
	
			$obj->end_popover();
			
			$obj->add_control(
				'adv_parallax_effects_bordercolor',
				[
					'label' => __( 'Border Color', 'wpkoi-elements' ),
					'type' => Elementor\Controls_Manager::POPOVER_TOGGLE,
					'condition' => [
						'adv_parallax_effects_show' => 'yes',
					],
					'render_type' => 'none',
				]
			);
	
			$obj->start_popover();
			
			$obj->add_control(
				'adv_parallax_effects_bordercolor_heading',
				array(
					'label' => esc_html__( 'Add color for both. For the best result add only HEX colors.', 'wpkoi-elements' ),
					'type'  => Elementor\Controls_Manager::HEADING,
				)
			);
			
			$obj->add_control(
				'adv_parallax_effects_bordercolors',
				array(
					'label' => esc_html__( 'Start', 'wpkoi-elements' ),
					'type' => Elementor\Controls_Manager::COLOR,
					'alpha' => false,
					'condition'    => [
						'adv_parallax_effects_show' => 'yes',
					],
					'render_type' => 'none',
				)
			);
			
			$obj->add_control(
				'adv_parallax_effects_bordercolore',
				array(
					'label' => esc_html__( 'End', 'wpkoi-elements' ),
					'type' => Elementor\Controls_Manager::COLOR,
					'alpha' => false,
					'condition'    => [
						'adv_parallax_effects_show' => 'yes',
					],
					'render_type' => 'none',
				)
			);
	
			$obj->end_popover();
			
			$obj->add_control(
				'adv_parallax_end_midscreen',
				array(
					'label'        => esc_html__( 'End animation at midscreen', 'wpkoi-elements' ),
					'type'         => Elementor\Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Yes', 'wpkoi-elements' ),
					'label_off'    => esc_html__( 'No', 'wpkoi-elements' ),
					'return_value' => 'true',
					'default'      => 'false',
					'condition' => array(
						'adv_parallax_effects_show' => 'yes',
					),
					'render_type' => 'none',
				)
			);
			
			$obj->add_control(
				'adv_parallax_end_midscreen_position',
				array(
					'label'   => esc_html__( 'Position', 'wpkoi-elements' ),
					'type'    => Elementor\Controls_Manager::SELECT,
					'default' => '0.4',
					'options' => array(
						'0.1'    => esc_html__( '10%', 'wpkoi-elements' ),
						'0.2'    => esc_html__( '20%', 'wpkoi-elements' ),
						'0.3'    => esc_html__( '30%', 'wpkoi-elements' ),
						'0.4'    => esc_html__( '40%', 'wpkoi-elements' ),
						'0.5'    => esc_html__( '50%', 'wpkoi-elements' ),
						'0.6'    => esc_html__( '60%', 'wpkoi-elements' ),
						'0.7'    => esc_html__( '70%', 'wpkoi-elements' ),
						'0.8' 	 => esc_html__( '80%', 'wpkoi-elements' ),
						'0.9' 	 => esc_html__( '90%', 'wpkoi-elements' ),
					),
					'condition' => array(
						'adv_parallax_effects_show' => 'yes',
						'adv_parallax_end_midscreen' => 'true',
					),
					'render_type' => 'none',
				)
			);
			
			$obj->add_control(
				'adv_parallax_only_mobil',
				array(
					'label'        => esc_html__( 'Disable on mobile (under 768px)', 'wpkoi-elements' ),
					'type'         => Elementor\Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Yes', 'wpkoi-elements' ),
					'label_off'    => esc_html__( 'No', 'wpkoi-elements' ),
					'return_value' => 'true',
					'default'      => 'false',
					'condition' => array(
						'adv_parallax_effects_show' => 'yes',
					),
					'render_type' => 'none',
				)
			);
			
			$obj->end_controls_section();
		}
		
		public function before_render( $element ) {
			
			
			$parallax_settings = $element->get_settings_for_display();

			if( isset($parallax_settings['adv_parallax_effects_show']) && $parallax_settings['adv_parallax_effects_show'] == 'yes' ) {
				
				$end_midscreen		 = isset( $parallax_settings['adv_parallax_end_midscreen'] ) ? $parallax_settings['adv_parallax_end_midscreen'] : false;
				$end_midscreen_pos   = isset( $parallax_settings['adv_parallax_end_midscreen_position'] ) ? $parallax_settings['adv_parallax_end_midscreen_position'] : '0.4';
				$only_mobil			 = isset( $parallax_settings['adv_parallax_only_mobil'] ) ? $parallax_settings['adv_parallax_only_mobil'] : false;

				$parallax_y_start    = isset($parallax_settings['adv_parallax_effects_y_start']['size']) ? $parallax_settings['adv_parallax_effects_y_start']['size'] : 0;
				$parallax_y_end      = isset($parallax_settings['adv_parallax_effects_y_end']['size']) ? $parallax_settings['adv_parallax_effects_y_end']['size'] : 0;
				
				$parallax_yp_start    = isset($parallax_settings['adv_parallax_effects_yp_start']['size']) ? $parallax_settings['adv_parallax_effects_yp_start']['size'] : 0;
				$parallax_yp_end      = isset($parallax_settings['adv_parallax_effects_yp_end']['size']) ? $parallax_settings['adv_parallax_effects_yp_end']['size'] : 0;
	
				$parallax_x_start 	 = $parallax_settings['adv_parallax_effects_x_start']['size'];
				$parallax_x_end      = $parallax_settings['adv_parallax_effects_x_end']['size'];
				
				$parallax_xp_start    = isset($parallax_settings['adv_parallax_effects_xp_start']['size']) ? $parallax_settings['adv_parallax_effects_xp_start']['size'] : 0;
				$parallax_xp_end      = isset($parallax_settings['adv_parallax_effects_xp_end']['size']) ? $parallax_settings['adv_parallax_effects_xp_end']['size'] : 0;
	
				$parallax_opacity_start    = isset($parallax_settings['adv_parallax_effects_opacity_start']['size']) ? $parallax_settings['adv_parallax_effects_opacity_start']['size'] : 100;
				$parallax_opacity_end      = isset($parallax_settings['adv_parallax_effects_opacity_end']['size']) ? $parallax_settings['adv_parallax_effects_opacity_end']['size'] : 100;
	
				$parallax_blur_start = isset($parallax_settings['adv_parallax_effects_blur_start']['size']) ? $parallax_settings['adv_parallax_effects_blur_start']['size'] : 0;
				$parallax_blur_end   = isset($parallax_settings['adv_parallax_effects_blur_end']['size']) ? $parallax_settings['adv_parallax_effects_blur_end']['size'] : 0;
	
				$parallax_rotate_start     = isset($parallax_settings['adv_parallax_effects_rotate_value_start']['size']) ? $parallax_settings['adv_parallax_effects_rotate_value_start']['size'] : 0;
				$parallax_rotate_end     = isset($parallax_settings['adv_parallax_effects_rotate_value_end']['size']) ? $parallax_settings['adv_parallax_effects_rotate_value_end']['size'] : 0;
	
				$parallax_s_scale	 = isset($parallax_settings['adv_parallax_effects_scale_start_value']['size']) ? $parallax_settings['adv_parallax_effects_scale_start_value']['size'] : 1;
				$parallax_scale      = $parallax_settings['adv_parallax_effects_scale_value']['size'];
	
				$parallax_hue        = $parallax_settings['adv_parallax_effects_hue_value']['size'];
	
				$parallax_s_grayscale= isset($parallax_settings['adv_parallax_effects_grayscale_s_value']['size']) ? $parallax_settings['adv_parallax_effects_grayscale_s_value']['size'] : 0;
				$parallax_grayscale  = $parallax_settings['adv_parallax_effects_grayscale_value']['size'];
	
				$parallax_s_saturate = isset($parallax_settings['adv_parallax_effects_saturate_s_value']['size']) ? $parallax_settings['adv_parallax_effects_saturate_s_value']['size'] : 0;
				$parallax_saturate   = $parallax_settings['adv_parallax_effects_saturate_value']['size'];
	
				$parallax_s_sepia	 = isset($parallax_settings['adv_parallax_effects_sepia_s_value']['size']) ? $parallax_settings['adv_parallax_effects_sepia_s_value']['size'] : 0;
				$parallax_sepia      = $parallax_settings['adv_parallax_effects_sepia_value']['size'];
				
				$parallax_cs         = $parallax_settings['adv_parallax_effects_colors'];
				
				$parallax_ce         = $parallax_settings['adv_parallax_effects_colore'];
				
				$parallax_bgcs       = $parallax_settings['adv_parallax_effects_bgcolors'];
				
				$parallax_bgce       = $parallax_settings['adv_parallax_effects_bgcolore'];
				
				$parallax_bocs       = $parallax_settings['adv_parallax_effects_bordercolors'];
				
				$parallax_boce       = $parallax_settings['adv_parallax_effects_bordercolore'];
				
				$parallax = '';
	
				if ( $parallax_settings['adv_parallax_effects_y'] ) {
					$parallax .= "y: '" . esc_attr( $parallax_y_start ) . "," . esc_attr( $parallax_y_end ) . "', ";
				}
	
				if ( $parallax_settings['adv_parallax_effects_yp'] ) {
					$parallax .= "yp: '" . esc_attr( $parallax_yp_start ) . "," . esc_attr( $parallax_yp_end ) . "', ";
				}
	
				if ( $parallax_settings['adv_parallax_effects_x'] ) {
					$parallax .= "x: '" . esc_attr( $parallax_x_start ) . "," . esc_attr( $parallax_x_end ) . "', ";
				}
	
				if ( $parallax_settings['adv_parallax_effects_xp'] ) {
					$parallax .= "xp: '" . esc_attr( $parallax_xp_start ) . "," . esc_attr( $parallax_xp_end ) . "', ";
				}
	
				if ( $parallax_settings['adv_parallax_effects_opacity'] ) {
					$parallax_opacity_start_full = '0.' . $parallax_opacity_start;
					if ($parallax_opacity_start < 10){ $parallax_opacity_start_full = '0.0' . $parallax_opacity_start;}
					if ($parallax_opacity_start == 100){ $parallax_opacity_start_full = '1'; }
					$parallax_opacity_end_full = '0.' . $parallax_opacity_end;
					if ($parallax_opacity_end < 10){ $parallax_opacity_end_full = '0.0' . $parallax_opacity_end;}
					if ($parallax_opacity_end == 100){ $parallax_opacity_end_full = '1'; }
					
					$parallax .= "opacity: '" . esc_attr( $parallax_opacity_start_full ) . "," . esc_attr( $parallax_opacity_end_full ) . "', ";
				}
	
				if ( $parallax_settings['adv_parallax_effects_blur'] ) {
					$parallax .= "blur: '" . esc_attr( $parallax_blur_start ) . "," . esc_attr( $parallax_blur_end ) . "', ";
				}
	
				if ( $parallax_settings['adv_parallax_effects_rotate'] ) {
					$parallax .= "rotate: '" . esc_attr( $parallax_rotate_start ) . "," . esc_attr( $parallax_rotate_end ) . "', ";
				}
	
				if ( $parallax_settings['adv_parallax_effects_scale'] ) {
					$parallax .= "scale: '" . esc_attr( $parallax_s_scale ) . "," . esc_attr( $parallax_scale ) . "', ";
				}
	
				if ( $parallax_settings['adv_parallax_effects_hue'] ) {
					$parallax .= "hue: " . esc_attr( $parallax_hue ) . ", ";
				}
	
				if ( $parallax_settings['adv_parallax_effects_grayscale'] ) {
					$parallax .= "grayscale: '" . esc_attr( $parallax_s_grayscale ) . "," . esc_attr( $parallax_grayscale ) . "', ";
				}
	
				if ( $parallax_settings['adv_parallax_effects_saturate'] ) {
					$parallax .= "saturate: '" . esc_attr( $parallax_s_saturate ) . "," . esc_attr( $parallax_saturate ) . "', ";
				}
	
				if ( $parallax_settings['adv_parallax_effects_sepia'] ) {
					$parallax .= "sepia: '" . esc_attr( $parallax_s_sepia ) . "," . esc_attr( $parallax_sepia ) . "', ";
				}
				
				if ( ( $parallax_settings['adv_parallax_effects_colors'] ) && ( $parallax_settings['adv_parallax_effects_colore'] ) ) {
					$parallax .= "color: '" . esc_attr( $parallax_cs ) . "," . esc_attr( $parallax_ce ) . "', ";
					$element->add_render_attribute(
						'_wrapper',
						'class',
						'wpkoi-scroll-color'
					);
				}
				
				if ( ( $parallax_settings['adv_parallax_effects_bgcolors'] ) && ( $parallax_settings['adv_parallax_effects_bgcolore'] ) ) {
					$parallax .= "'background-color': '" . esc_attr( $parallax_bgcs ) . "," . esc_attr( $parallax_bgce ) . "', ";
				}
				
				if ( ( $parallax_settings['adv_parallax_effects_bordercolors'] ) && ( $parallax_settings['adv_parallax_effects_bordercolore'] ) ) {
					$parallax .= "'border-color': '" . esc_attr( $parallax_bocs ) . "," . esc_attr( $parallax_boce ) . "', ";
				}
					
				if( $end_midscreen == 'true' ) {
					$parallax .= "viewport: " . esc_attr( $end_midscreen_pos ) . ", ";
				}
				
				if( $only_mobil == 'true' ) {
					$parallax .= "media: 768, ";
				}
				
				if ( ! empty($parallax) ) {
					$element->add_render_attribute(
						'_wrapper',
						'data-uk-parallax',
						trim($parallax)
					);
				}
	
			}
			
		}
		
		public function enqueue_scripts() {

			wp_enqueue_script('uikit',WPKOI_ELEMENTS_LITE_URL.'elements/effects/assets/uikit.js', array('jquery'),WPKOI_ELEMENTS_LITE_VERSION, true);
			wp_enqueue_script('uikit-parallax',WPKOI_ELEMENTS_LITE_URL.'elements/effects/assets/parallax.js', array('jquery'),WPKOI_ELEMENTS_LITE_VERSION, true);
		}

		/**
		 * Returns the instance.
		 */
		public static function get_instance() {
			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}
	}
}


/**
 * Returns instance of WPKoi_Elements_Lite_Effects_Extension
 */
function wpkoi_elements_lite_effect_extension() {
	return WPKoi_Elements_Lite_Effects_Extension::get_instance();
}
wpkoi_elements_lite_effect_extension()->init();

function wpkoi_scroll_effect_lite_extension() {
	return WPKoi_Scroll_Effects_Lite_Extension::get_instance();
}
wpkoi_scroll_effect_lite_extension()->init();