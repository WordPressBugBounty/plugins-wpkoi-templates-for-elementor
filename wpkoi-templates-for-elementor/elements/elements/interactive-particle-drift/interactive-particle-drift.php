<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit;

class Widget_WPKoi_Interactive_Particle_Drift extends Widget_Base {

	public function get_name() {
		return 'wpkoi-interactive-particle-drift';
	}

	public function get_title() {
		return esc_html__( 'Interactive Particle Drift', 'wpkoi-elements' );
	}

	public function get_icon() {
		return 'eicon-image';
	}

	public function get_categories() {
		return [ 'wpkoi-addons-for-elementor' ];
	}
	
	public function get_help_url() {
		return 'https://wpkoi.com/wpkoi-elementor-templates-demo/elements/interactive-particle-drift/';
	}

	public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);
		
		wp_register_script('wpkoi-three-js',WPKOI_ELEMENTS_LITE_URL.'elements/distorted-image/assets/three-160.min.js', ['jquery'],WPKOI_ELEMENTS_LITE_VERSION, true);
		
		wp_register_script('wpkoi-particle-drift',WPKOI_ELEMENTS_LITE_URL.'elements/interactive-particle-drift/assets/interactive-particle-drift.js', [ 'jquery' ],WPKOI_ELEMENTS_LITE_VERSION, true);
		
		wp_register_style('wpkoi-particle-drift-css',WPKOI_ELEMENTS_LITE_URL . 'elements/interactive-particle-drift/assets/interactive-particle-drift.css',false,WPKOI_ELEMENTS_LITE_VERSION);
		
		wp_localize_script(
			'wpkoi-particle-drift',
			'wpkoiParticleDrift',
			[
				'pluginUrl' => WPKOI_ELEMENTS_LITE_URL
			]
		);
	}

	public function get_script_depends() {
		return [ 'wpkoi-three-js', 'wpkoi-particle-drift' ];
	}

	public function get_style_depends() {
		return [ 'wpkoi-particle-drift-css' ];
	}
	
	private function hex_to_rgb($hex) {
		$hex = sanitize_hex_color($hex);

		if (!$hex) {
			return ['r' => 255, 'g' => 255, 'b' => 255];
		}

		$hex = str_replace('#', '', $hex);

		if (strlen($hex) === 3) {
			$hex = $hex[0].$hex[0] . $hex[1].$hex[1] . $hex[2].$hex[2];
		}

		return [
			'r' => hexdec(substr($hex, 0, 2)),
			'g' => hexdec(substr($hex, 2, 2)),
			'b' => hexdec(substr($hex, 4, 2)),
		];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Content', 'wpkoi-elements' ),
			]
		);
			
		$this->add_control(
			'interactive_particle_drift_subheading',
			array(
				'label' => esc_html__( 'Note: Using many Interactive widgets on the same page may affect performance and cause preview issues in the Elementor editor. This does not affect the live frontend.', 'wpkoi-elements' ),
				'type'  => Controls_Manager::HEADING
			)
		);
		
		$this->add_control(
			'heading_content',
			[
				'label' => esc_html__( 'Content', 'wpkoi-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'texture_type',
			[
				'label' => esc_html__( 'Texture Type', 'wpkoi-elements' ),
				'description' => esc_html__( 'Choose the visual style of the particles (cloud, smoke, dust, etc.).', 'wpkoi-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'cloud',
				'options' => [
					'cloud' => esc_html__( 'Cloud', 'wpkoi-elements' ),
					'smoke' => esc_html__( 'Smoke', 'wpkoi-elements' ),
					'ink' => esc_html__( 'Ink', 'wpkoi-elements' ),
					'dust' => esc_html__( 'Dust', 'wpkoi-elements' ),
					'flame' => esc_html__( 'Flame', 'wpkoi-elements' ),
            		'custom' => esc_html__( 'Custom', 'wpkoi-elements' ),
				],
			]
		);
		
		$this->add_control(
			'custom_texture',
			[
				'label' => esc_html__( 'Custom Texture', 'wpkoi-elements' ),
				'description' => esc_html__( 'Upload your own image to use as particles.', 'wpkoi-elements' ),
				'type' => Controls_Manager::MEDIA,
				'condition' => [
					'texture_type' => 'custom',
				],
			]
		);
		
		$this->add_responsive_control(
			'height',
			[
				'label' => esc_html__( 'Height', 'wpkoi-elements' ),
				'description' => esc_html__( 'Set the height of the animation area.', 'wpkoi-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vw', 'vh', '%' ],
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 1000,
					],
					'vh' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 500,
				],
				'selectors' => [
					'{{WRAPPER}} .wpkoi-particle-drift-wrapper' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'heading_clouds',
			[
				'label' => esc_html__( 'Cloud System', 'wpkoi-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'density',
			[
				'label' => esc_html__( 'Particle Amount', 'wpkoi-elements' ),
				'description' => esc_html__( 'Controls how many particles are rendered. Higher values create a denser scene but may affect performance.', 'wpkoi-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 50,
					],
				],
				'default' => [
					'size' => 20,
				],
			]
		);
		
		$this->add_control(
			'depth',
			[
				'label' => esc_html__( 'Scene Depth', 'wpkoi-elements' ),
				'description' => esc_html__( 'Defines how deep the particle field extends. Larger values create a stronger 3D effect.', 'wpkoi-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 20,
				],
			]
		);

		$this->add_control(
			'size_min',
			[
				'label' => esc_html__( 'Min Size', 'wpkoi-elements' ),
				'description' => esc_html__( 'Smallest particle size.', 'wpkoi-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0.1,
						'max' => 3,
						'step' => 0.1,
					],
				],
				'default' => [
					'size' => 0.5,
				],
			]
		);

		$this->add_control(
			'size_max',
			[
				'label' => esc_html__( 'Max Size', 'wpkoi-elements' ),
				'description' => esc_html__( 'Largest particle size.', 'wpkoi-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0.1,
						'max' => 3,
						'step' => 0.1,
					],
				],
				'default' => [
					'size' => 1.5,
				],
			]
		);

		$this->add_control(
			'opacity_min',
			[
				'label' => esc_html__( 'Min Opacity', 'wpkoi-elements' ),
				'description' => esc_html__( 'Minimum transparency of particles.', 'wpkoi-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0.01,
						'max' => 1,
						'step' => 0.01,
					],
				],
				'default' => [
					'size' => 0.5,
				],
			]
		);

		$this->add_control(
			'opacity_max',
			[
				'label' => esc_html__( 'Max Opacity', 'wpkoi-elements' ),
				'description' => esc_html__( 'Maximum transparency of particles.', 'wpkoi-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0.01,
						'max' => 1,
						'step' => 0.01,
					],
				],
				'default' => [
					'size' => 1,
				],
			]
		);

		$this->add_control(
			'heading_motion',
			[
				'label' => esc_html__( 'Motion', 'wpkoi-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'speed',
			[
				'label' => esc_html__( 'Speed', 'wpkoi-elements' ),
				'description' => esc_html__( 'Controls how fast the particles move.', 'wpkoi-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 30,
				],
			]
		);
		
		$this->add_control(
			'reverse_direction',
			[
				'label' => esc_html__( 'Reverse Direction', 'wpkoi-elements' ),
				'description' => esc_html__( 'Make particles move away instead of toward the viewer.', 'wpkoi-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'wpkoi-elements' ),
				'label_off' => esc_html__( 'No', 'wpkoi-elements' ),
				'return_value' => 'yes',
				'default' => '',
			]
		);
		
		$this->add_control(
			'heading_perspective',
			[
				'label' => esc_html__( 'Perspective', 'wpkoi-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'fov',
			[
				'label' => esc_html__( 'Perspective Strength', 'wpkoi-elements' ),
				'description' => esc_html__( 'Controls how strong the depth perspective appears. Lower values feel flatter, higher values feel more 3D.', 'wpkoi-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 30,
				],
			]
		);
		
		$this->add_control(
			'view_mode',
			[
				'label' => esc_html__( 'View Angle', 'wpkoi-elements' ),
				'description' => esc_html__( 'Change the viewing direction of the scene.', 'wpkoi-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default (Bottom)', 'wpkoi-elements' ),
					'tilt' => esc_html__( 'Tilted', 'wpkoi-elements' ),
					'top' => esc_html__( 'Top', 'wpkoi-elements' ),
					'left' => esc_html__( 'Left', 'wpkoi-elements' ),
					'right' => esc_html__( 'Right', 'wpkoi-elements' ),
				],
			]
		);
		
		$this->add_control(
			'base_tilt_y',
			[
				'label' => esc_html__( 'Vertical Offset', 'wpkoi-elements' ),
				'description' => esc_html__( 'Shifts the default vertical position of the view.', 'wpkoi-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -200,
						'max' => 200,
					],
				],
				'default' => [
					'size' => 0,
				],
			]
		);
		
		$this->add_control(
			'heading_style',
			[
				'label' => esc_html__( 'Style', 'wpkoi-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'enable_tint',
			[
				'label' => esc_html__( 'Enable Color Tint', 'wpkoi-elements' ),
				'description' => esc_html__( 'Apply a color overlay to all particles.', 'wpkoi-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
			]
		);

		$this->add_control(
			'tint_color',
			[
				'label' => esc_html__( 'Tint Color', 'wpkoi-elements' ),
				'description' => esc_html__( 'Choose the color applied to the particles.', 'wpkoi-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'condition' => [
					'enable_tint' => 'yes',
				],
			]
		);

		$this->add_control(
			'tint_strength',
			[
				'label' => esc_html__( 'Tint Strength', 'wpkoi-elements' ),
				'description' => esc_html__( 'Control how strong the color effect is.', 'wpkoi-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1,
						'step' => 0.01,
					],
				],
				'default' => [ 'size' => 0.5 ],
				'condition' => [
					'enable_tint' => 'yes',
				],
			]
		);
		
		$this->add_control(
			'blend_mode',
			[
				'label' => esc_html__( 'Blend Mode', 'wpkoi-elements' ),
				'description' => esc_html__( 'Defines how the particles blend with the background.', 'wpkoi-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'normal',
				'options' => [
					'normal' => esc_html__( 'Normal', 'wpkoi-elements' ),

						'multiply' => esc_html__( 'Multiply', 'wpkoi-elements' ),

						'screen' => esc_html__( 'Screen', 'wpkoi-elements' ),

						'overlay' => esc_html__( 'Overlay', 'wpkoi-elements' ),

						'darken' => esc_html__( 'Darken', 'wpkoi-elements' ),

						'lighten' => esc_html__( 'Lighten', 'wpkoi-elements' ),

						'color-dodge' => esc_html__( 'Color Dodge', 'wpkoi-elements' ),

						'color-burn' => esc_html__( 'Color Burn', 'wpkoi-elements' ),

						'hard-light' => esc_html__( 'Hard Light', 'wpkoi-elements' ),

						'soft-light' => esc_html__( 'Soft Light', 'wpkoi-elements' ),

						'difference' => esc_html__( 'Difference', 'wpkoi-elements' ),

						'exclusion' => esc_html__( 'Exclusion', 'wpkoi-elements' ),
				],
			]
		);
		
		$this->add_control(
			'edge_mask',
			[
				'label' => esc_html__( 'Edge Fade', 'wpkoi-elements' ),
				'description' => esc_html__( 'Fade out the edges of the animation for a smoother look.', 'wpkoi-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => 'On',
				'label_off' => 'Off',
				'return_value' => 'yes',
				'default' => '',
			]
		);
		
		$this->add_control(
			'mask_color',
			[
				'label' => esc_html__( 'Fade Color', 'wpkoi-elements' ),
				'description' => esc_html__( 'Color used for the edge fading effect.', 'wpkoi-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff', 
				'condition' => [
					'edge_mask' => 'yes',
				],
			]
		);
		
		$this->add_control(
			'heading_effects',
			[
				'label' => esc_html__( 'Effects', 'wpkoi-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'enable_noise',
			[
				'label' => esc_html__( 'Distortion Effect', 'wpkoi-elements' ),
				'description' => esc_html__( 'Adds subtle animated distortion to particles.', 'wpkoi-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
			]
		);

		$this->add_control(
			'noise_strength',
			[
				'label' => esc_html__( 'Distortion Strength', 'wpkoi-elements' ),
				'description' => esc_html__( 'Controls how strong the distortion effect is.', 'wpkoi-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'normal',
				'options' => [
					'low' => esc_html__( 'Low', 'wpkoi-elements' ),
					'normal' => esc_html__( 'Normal', 'wpkoi-elements' ),
					'high' => esc_html__( 'High', 'wpkoi-elements' ),
				],
				'condition' => [
					'enable_noise' => 'yes',
				],
			]
		);
		
		$this->add_control(
			'enable_depth_fade',
			[
				'label' => esc_html__( 'Depth Fade', 'wpkoi-elements' ),
				'description' => esc_html__( 'Fade particles as they move into the distance.', 'wpkoi-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
			]
		);

		$this->add_control(
			'depth_fade_strength',
			[
				'label' => esc_html__( 'Fade Distance', 'wpkoi-elements' ),
				'description' => esc_html__( 'Controls how quickly particles fade away.', 'wpkoi-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'normal',
				'options' => [
					'low' => esc_html__( 'Low', 'wpkoi-elements' ),
					'normal' => esc_html__( 'Normal', 'wpkoi-elements' ),
					'high' => esc_html__( 'High', 'wpkoi-elements' ),
				],
				'condition' => [
					'enable_depth_fade' => 'yes',
				],
			]
		);
		
		$this->add_control(
			'heading_interaction',
			[
				'label' => esc_html__( 'Interaction', 'wpkoi-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'mouse_strength',
			[
				'label' => esc_html__( 'Mouse Influence', 'wpkoi-elements' ),
				'description' => esc_html__( 'Controls how much the mouse movement affects the scene.', 'wpkoi-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1,
						'step' => 0.01,
					],
				],
				'default' => [ 'size' => 0.25 ],
			]
		);

		$this->add_control(
			'mouse_axis',
			[
				'label' => esc_html__( 'Mouse Direction', 'wpkoi-elements' ),
				'description' => esc_html__( 'Choose which direction responds to mouse movement.', 'wpkoi-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'both',
				'options' => [
					'both' => esc_html__( 'Both', 'wpkoi-elements' ),
					'x' => esc_html__( 'X only', 'wpkoi-elements' ),
					'y' => esc_html__( 'Y only', 'wpkoi-elements' ),
				],
			]
		);
		
		$this->add_control(
			'heading_performance',
			[
				'label' => esc_html__( 'Performance', 'wpkoi-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'quality_mode',
			[
				'label' => esc_html__( 'Quality Mode', 'wpkoi-elements' ),
				'description' => esc_html__( 'Adjust rendering quality and performance.', 'wpkoi-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'medium',
				'options' => [
					'low' => esc_html__( 'Low', 'wpkoi-elements' ),
					'medium' => esc_html__( 'Medium', 'wpkoi-elements' ),
					'high' => esc_html__( 'High', 'wpkoi-elements' ),
				],
			]
		);
		
		$this->add_control(
			'mobile_mode',
			[
				'label' => esc_html__( 'Mobile Behavior', 'wpkoi-elements' ),
				'description' => esc_html__( 'Define how the effect behaves on mobile devices.', 'wpkoi-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'optimized',
				'options' => [
					'full' => esc_html__( 'Full', 'wpkoi-elements' ),
					'optimized' => esc_html__( 'Optimized', 'wpkoi-elements' ),
					'static' => esc_html__( 'Static', 'wpkoi-elements' ),
					'disabled' => esc_html__( 'Disabled', 'wpkoi-elements' ),
				],
			]
		);
		
		$this->end_controls_section();
	}

	protected function render() {

		$settings = $this->get_settings_for_display();

		// === CLEAN SETTINGS ===
		$data = [
			// Content
			'texture_type' => $settings['texture_type'] ?? 'cloud',
    'custom_texture' => !empty($settings['custom_texture']['url']) 
        ? [ 'url' => esc_url($settings['custom_texture']['url']) ] 
        : null,

			// System
			'density' => isset($settings['density']['size']) ? (int)$settings['density']['size'] : 20,
			'depth' => isset($settings['depth']['size']) ? (int)$settings['depth']['size'] : 20,

			'size_min' => isset($settings['size_min']['size']) ? (float)$settings['size_min']['size'] : 0.5,
			'size_max' => isset($settings['size_max']['size']) ? (float)$settings['size_max']['size'] : 1.5,

			'opacity_min' => isset($settings['opacity_min']['size']) ? (float)$settings['opacity_min']['size'] : 0.5,
			'opacity_max' => isset($settings['opacity_max']['size']) ? (float)$settings['opacity_max']['size'] : 1,

			// Motion
			'speed' => isset($settings['speed']['size']) ? (int)$settings['speed']['size'] : 30,
			'reverse_direction' => $settings['reverse_direction'] ?? '',

			// Perspective
			'fov' => isset($settings['fov']['size']) ? (int)$settings['fov']['size'] : 30,
			'view_mode' => $settings['view_mode'] ?? 'default',
			'base_tilt_y' => isset($settings['base_tilt_y']['size']) ? (int)$settings['base_tilt_y']['size'] : 0,

			// Style
			'enable_tint' => $settings['enable_tint'] ?? '',
			'tint_color' => !empty($settings['tint_color']) ? sanitize_hex_color($settings['tint_color']) : '#ffffff',
			'tint_strength' => isset($settings['tint_strength']['size']) ? (float)$settings['tint_strength']['size'] : 0.5,

			'blend_mode' => $settings['blend_mode'] ?? 'normal',

			'edge_mask' => $settings['edge_mask'] ?? '',
			'mask_color' => !empty($settings['mask_color']) ? sanitize_hex_color($settings['mask_color']) : '#ffffff',

			// Effects
			'enable_noise' => $settings['enable_noise'] ?? '',
			'noise_strength' => $settings['noise_strength'] ?? 'normal',

			 'enable_depth_fade' => $settings['enable_depth_fade'] ?? '',
			'depth_fade_strength' => $settings['depth_fade_strength'] ?? 'normal',

			// Interaction
			'mouse_strength' => isset($settings['mouse_strength']['size']) ? (float)$settings['mouse_strength']['size'] : 0.25,
			'mouse_axis' => $settings['mouse_axis'] ?? 'both',

			// Performance
			'quality_mode' => $settings['quality_mode'] ?? 'medium',
			'mobile_mode' => $settings['mobile_mode'] ?? 'optimized',
		];

		// === STYLE VARS ===
		$mask_color = $data['mask_color'];
		$rgb = $this->hex_to_rgb($mask_color);

		$style = sprintf(
			'--wpkoi-particle-drift-mask:%s; --wpkoi-particle-drift-mask-rgb:%s;',
			esc_attr($mask_color),
			esc_attr($rgb['r'] . ',' . $rgb['g'] . ',' . $rgb['b'])
		);

		$widget_id = esc_attr($this->get_id());
		
		?>
		<div 
			class="wpkoi-particle-drift-wrapper <?php echo $data['edge_mask'] ? 'has-mask' : ''; ?>"
			id="wpkoi-particle-drift-<?php echo $widget_id; ?>"
			style="<?php echo esc_attr($style); ?>"
			data-settings="<?php echo esc_attr(wp_json_encode($data)); ?>"
		></div>
		<?php
	}

	protected function content_template() {}
}

Plugin::instance()->widgets_manager->register( new Widget_WPKoi_Interactive_Particle_Drift() );