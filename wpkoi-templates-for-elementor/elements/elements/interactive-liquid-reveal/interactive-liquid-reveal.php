<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit;

class Widget_WPKoi_Interactive_Liquid_Reveal extends Widget_Base {

	public function get_name() {
		return 'wpkoi-interactive-liquid-reveal';
	}

	public function get_title() {
		return esc_html__( 'Interactive Liquid Reveal', 'wpkoi-elements' );
	}

	public function get_icon() {
		return 'eicon-image';
	}

	public function get_categories() {
		return [ 'wpkoi-addons-for-elementor' ];
	}
	
	public function get_help_url() {
		return 'https://wpkoi.com/wpkoi-elementor-templates-demo/elements/interactive-liquid-reveal/';
	}

	public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);
		
		wp_register_script('wpkoi-liquid-reveal',WPKOI_ELEMENTS_LITE_URL.'elements/interactive-liquid-reveal/assets/interactive-liquid-reveal.js', [ 'jquery' ],WPKOI_ELEMENTS_LITE_VERSION, true);
		
		wp_register_style('wpkoi-liquid-reveal-css',WPKOI_ELEMENTS_LITE_URL . 'elements/interactive-liquid-reveal/assets/interactive-liquid-reveal.css',false,WPKOI_ELEMENTS_LITE_VERSION);
	}

	public function get_script_depends() {
		return [ 'wpkoi-liquid-reveal' ];
	}

	public function get_style_depends() {
		return [ 'wpkoi-liquid-reveal-css' ];
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
			'interactive_liquid_reveal_subheading',
			array(
				'label' => esc_html__( 'Note: Using many Interactive widgets on the same page may affect performance and cause preview issues in the Elementor editor. This does not affect the live frontend.', 'wpkoi-elements' ),
				'type'  => Controls_Manager::HEADING
			)
		);

		$this->add_control(
			'image',
			[
				'label' => esc_html__( 'Interactive Image', 'wpkoi-elements' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
				'description' => esc_html__( 'Select the image that will be animated.', 'wpkoi-elements' ),
			]
		);
		
		$this->add_responsive_control(
			'height',
			[
				'label' => esc_html__( 'Height', 'wpkoi-elements' ),
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
					'{{WRAPPER}} .wpkoi-liquid-reveal-wrapper' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'bg_color',
			[
				'label' => esc_html__( 'Overlay Color', 'wpkoi-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
			]
		);
		
		$this->add_control(
			'liquid_base_color',
			[
				'label' => esc_html__( 'Liquid Color', 'wpkoi-elements' ),
				'description' => esc_html__( 'Defines the color tone of the liquid distortion effect.', 'wpkoi-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#cc7f33', 
			]
		);
		
		$this->add_control(
			'mouse_radius',
			[
				'label' => esc_html__( 'Interaction Size', 'wpkoi-elements' ),
				'description' => esc_html__( 'Controls how large the interactive area is around the cursor.', 'wpkoi-elements' ),
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
			'dissipation',
			[
				'label' => esc_html__( 'Fade Speed', 'wpkoi-elements' ),
				'description' => esc_html__( 'Higher values make the effect fade more slowly.', 'wpkoi-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 99,
					],
				],
				'default' => [
					'size' => 80,
				],
			]
		);
		
		$this->add_control(
			'fluid_detail',
			[
				'label' => esc_html__( 'Fluid Detail', 'wpkoi-elements' ),
				'description' => esc_html__( 'Controls simulation complexity. Higher values look smoother but use more CPU/GPU.', 'wpkoi-elements' ),
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
			'render_quality',
			[
				'label' => esc_html__( 'Render Quality', 'wpkoi-elements' ),
				'description' => esc_html__( 'Controls visual sharpness. Higher values improve quality but may reduce performance.', 'wpkoi-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'high',
				'options' => [
					'low' => esc_html__( 'Low', 'wpkoi-elements' ),
					'high' => esc_html__( 'High', 'wpkoi-elements' ),
					'ultra' => esc_html__( 'Ultra', 'wpkoi-elements' ),
				],
			]
		);
		
		$this->add_control(
			'edge_mask',
			[
				'label' => esc_html__( 'Edge Mask', 'wpkoi-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => 'On',
				'label_off' => 'Off',
				'return_value' => 'yes',
				'default' => '',
			]
		);
		
		$this->add_control(
			'auto_demo',
			[
				'label' => esc_html__( 'Auto Demo Animation', 'wpkoi-elements' ),
				'description' => esc_html__( 'Plays a short automatic animation on load to demonstrate the effect.', 'wpkoi-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => 'On',
				'label_off' => 'Off',
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		
		$this->add_control(
			'mobile_mode',
			[
				'label' => esc_html__( 'Mobile Mode', 'wpkoi-elements' ),
				'description' => esc_html__( 'Optimized reduces GPU usage. Image Only disables animation. Disabled hides the widget on mobile.', 'wpkoi-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'optimized',
				'options' => [
					'default'   => esc_html__( 'Default', 'wpkoi-elements' ),
					'optimized' => esc_html__( 'Optimized', 'wpkoi-elements' ),
					'image'     => esc_html__( 'Image Only', 'wpkoi-elements' ),
					'disabled'  => esc_html__( 'Disabled', 'wpkoi-elements' ),
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {

		$settings = $this->get_settings_for_display();

		if ( empty($settings['image']['url']) || !filter_var($settings['image']['url'], FILTER_VALIDATE_URL)) {
			return;
		}

		$image_url = esc_url( $settings['image']['url'] );
		$widget_id = esc_attr( $this->get_id() );
		$bg_color = $settings['bg_color'] ?? '#ffffff';
		$rgb = $this->hex_to_rgb($bg_color);
		
		$style = sprintf(
			"background-image:url('%s'); --wpkoi-bg:%s; --wpkoi-bg-rgb:%s;",
			esc_url($image_url),
			esc_attr($bg_color),
			esc_attr($rgb['r'] . ',' . $rgb['g'] . ',' . $rgb['b'])
		);
		?>
		<div class="wpkoi-liquid-reveal-wrapper <?php echo !empty($settings['edge_mask']) && $settings['edge_mask'] === 'yes' ? 'has-mask' : ''; ?>" id="wpkoi-liquid-<?php echo $widget_id; ?>" style="<?php echo $style; ?>" data-image="<?php echo esc_url($image_url); ?>" data-settings='<?php echo esc_attr( wp_json_encode(['radius' => $settings['mouse_radius']['size'] ?? 30, 'dissipation' => $settings['dissipation']['size'] ?? 0.995, 'bgColor' => $settings['bg_color'] ?? '#ffffff', 'liquidColor' => $settings['liquid_base_color'] ?? '#cc7f33', 'autoDemo' => $settings['auto_demo'] === 'yes', 'edgeMask' => $settings['edge_mask'] === 'yes', 'fluidDetail' => $settings['fluid_detail'] ?? 'medium', 'renderQuality' => $settings['render_quality'] ?? 'high', 'mobileMode' => $settings['mobile_mode'] ?? 'optimized' ])); ?>'>
			<canvas class="wpkoi-liquid-canvas"></canvas>
		</div>
		<?php
	}

	protected function content_template() {}
}

Plugin::instance()->widgets_manager->register( new Widget_WPKoi_Interactive_Liquid_Reveal() );