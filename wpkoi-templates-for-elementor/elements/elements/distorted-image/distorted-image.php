<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit;

class Widget_WPKoi_Distorted_Image extends Widget_Base {

	public function get_name() {
		return 'wpkoi-distorted-image';
	}

	public function get_title() {
		return esc_html__( 'Distorted Image', 'wpkoi-elements' );
	}

	public function get_icon() {
		return 'eicon-image';
	}

	public function get_categories() {
		return [ 'wpkoi-addons-for-elementor' ];
	}
	
	public function get_help_url() {
		return 'https://wpkoi.com/wpkoi-templates-for-elementor/';
	}

	public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);

		wp_register_script('wpkoi-three-js',WPKOI_ELEMENTS_LITE_URL.'elements/distorted-image/assets/three-160.min.js', ['jquery'],WPKOI_ELEMENTS_LITE_VERSION, true);
		
		wp_register_script('wpkoi-distorted-image',WPKOI_ELEMENTS_LITE_URL.'elements/distorted-image/assets/distorted-image.js', ['jquery', 'wpkoi-three-js'],WPKOI_ELEMENTS_LITE_VERSION, true);
		
		wp_register_style('wpkoi-distorted-image-css',WPKOI_ELEMENTS_LITE_URL . 'elements/distorted-image/assets/distorted-image.css',false,WPKOI_ELEMENTS_LITE_VERSION);
	}

	public function get_script_depends() {
		return [ 'wpkoi-three-js', 'wpkoi-distorted-image' ];
	}

	public function get_style_depends() {
		return [ 'wpkoi-distorted-image-css' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Content', 'wpkoi-elements' ),
			]
		);
			
		$this->add_control(
			'distorted_image_subheading',
			array(
				'label' => esc_html__( 'Note: Using many Distorted Heading or Distorted Image widgets on the same page may affect performance and cause preview issues in the Elementor editor. This does not affect the live frontend.', 'wpkoi-elements' ),
				'type'  => Controls_Manager::HEADING
			)
		);

		$this->add_control(
			'image',
			[
				'label' => esc_html__( 'Image', 'wpkoi-elements' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
				'description' => esc_html__( 'Select the image that will be animated.', 'wpkoi-elements' ),
			]
		);
		
		$this->add_control(
			'link',
			[
				'label' => esc_html__( 'Link', 'wpkoi-elements' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'wpkoi-elements' ),
				'show_external' => true,
				'default' => [
					'url' => '',
					'is_external' => false,
					'nofollow' => false,
				],
				'description' => esc_html__( 'Make the image clickable by adding a URL.', 'wpkoi-elements' ),
			]
		);
		
		$this->add_responsive_control(
			'width',
			[
				'label' => esc_html__( 'Width', 'wpkoi-elements' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['%', 'px'],
				'range' => [
					'%' => [
						'min' => 10,
						'max' => 100,
					],
					'px' => [
						'min' => 100,
						'max' => 2000,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .wpkoi-distorted-image-wrapper' => 'width: {{SIZE}}{{UNIT}};',
				],
				'description' => esc_html__( 'Control the width of the animated image container.', 'wpkoi-elements' ),
			]
		);
		
		$this->add_control(
			'scale_multiplier',
			[
				'label' => esc_html__( 'Image Scale', 'wpkoi-elements' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 2,
						'step' => 0.01,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 1,
				],
				'description' => esc_html__( 'Zoom the image inside the distortion effect.', 'wpkoi-elements' ),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_animation',
			[
				'label' => esc_html__( 'Animation', 'wpkoi-elements' ),
			]
		);

		$this->add_control(
			'animation_mode',
			[
				'label' => esc_html__( 'Trigger Mode', 'wpkoi-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'autoplay',
				'options' => [
					'autoplay' => esc_html__( 'Autoplay', 'wpkoi-elements' ),
					'scroll'   => esc_html__( 'On Scroll', 'wpkoi-elements' ),
					'hover'    => esc_html__( 'On Hover', 'wpkoi-elements' ),
				],
				'description' => esc_html__( 'Choose when the animation should be active.', 'wpkoi-elements' ),
			]
		);
		
		$this->add_control(
			'projection_mode',
			[
				'label' => esc_html__( 'Effect Style', 'wpkoi-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'standard',
				'options' => [
					'standard'   => esc_html__( 'Wave Distortion', 'wpkoi-elements' ),
					'brick' 	 => esc_html__( '3D Bricks', 'wpkoi-elements' ),
					'bubbles' 	 => esc_html__( '3D Bubbles', 'wpkoi-elements' ),
					'spikes' 	 => esc_html__( 'Spikes', 'wpkoi-elements' ),
				],
				'description' => esc_html__( 'Select the 3D distortion style.', 'wpkoi-elements' ),
			]
		);
		
		$this->add_control(
			'instance_density',
			[
				'label' => esc_html__( 'Element Density', 'wpkoi-elements' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0.5,
						'max' => 2,
						'step' => 0.05,
					],
				],
				'default' => [
					'size' => 1,
				],
				'condition' => [
					'projection_mode' => ['brick', 'bubbles'],
				],
				'description' => esc_html__( 'Increase or decrease the number of bricks or bubbles.', 'wpkoi-elements' ),
			]
		);
		
		$this->add_control(
			'projection_strength',
			[
				'label' => esc_html__( 'Effect Strength', 'wpkoi-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 3,
						'step' => 0.01,
					],
				],
				'default' => [
					'size' => 1,
				],
				'description' => esc_html__( 'Increase or decrease the intensity of the 3D deformation.', 'wpkoi-elements' ),
			]
		);
		
		$this->add_control(
			'vertex_speed',
			[
				'label' => esc_html__( 'Animation Speed', 'wpkoi-elements' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 5,
						'step' => 0.1,
					],
				],
				'default' => [
					'size' => 1,
				],
				'description' => esc_html__( 'Control how fast the animation moves.', 'wpkoi-elements' ),
			]
		);

		$this->add_control(
			'disable_mobile',
			[
				'label' => esc_html__( 'Disable on Mobile', 'wpkoi-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'wpkoi-elements' ),
				'label_off' => esc_html__( 'No', 'wpkoi-elements' ),
				'return_value' => 'yes',
				'default' => '',
				'description' => esc_html__( 'Disable animation on smaller screens for better performance.', 'wpkoi-elements' ),
			]
		);
		
		$this->end_controls_section();

		$this->start_controls_section(
			'section_interaction',
			[
				'label' => esc_html__( 'Interactions', 'wpkoi-elements' ),
			]
		);

		$this->add_control(
			'mouse_effect',
			[
				'label' => esc_html__( 'Mouse Interaction', 'wpkoi-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'wpkoi-elements' ),
				'label_off' => esc_html__( 'No', 'wpkoi-elements' ),
				'return_value' => 'yes',
				'default' => 'no',
				'description' => esc_html__( 'Add an interactive distortion effect that reacts to the mouse cursor.', 'wpkoi-elements' ),
			]
		);

		$this->add_control(
			'hole_radius',
			[
				'label' => esc_html__( 'Mouse Radius', 'wpkoi-elements' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0.1,
						'max' => 1,
						'step' => 0.01,
					],
				],
				'default' => [
					'size' => 0.3,
				],
				'condition' => [
					'mouse_effect' => 'yes',
				],
				'description' => esc_html__( 'Adjust how large the mouse interaction area should be.', 'wpkoi-elements' ),
			]
		);

		$this->add_control(
			'light_intensity',
			[
				'label' => __('Light Intensity', 'wpkoi'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 2,
						'step' => 0.05,
					],
				],
				'default' => [
					'size' => 0.8,
				],
				'description' => esc_html__( 'Control the brightness of highlights on 3D surfaces.', 'wpkoi-elements' ),
			]
		);

		$this->add_control(
			'ambient_level',
			[
				'label' => __('Ambient Level', 'wpkoi'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1,
						'step' => 0.05,
					],
				],
				'default' => [
					'size' => 0.5,
				],
				'description' => esc_html__( 'Adjust overall brightness and reduce contrast.', 'wpkoi-elements' ),
			]
		);

		$this->add_control(
			'rgb_glitch',
			[
				'label' => esc_html__( 'RGB Glitch Effect', 'wpkoi-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'wpkoi-elements' ),
				'label_off' => esc_html__( 'No', 'wpkoi-elements' ),
				'return_value' => 'yes',
				'default' => 'no',
				'description' => esc_html__( 'Add a subtle color separation glitch animation.', 'wpkoi-elements' ),
			]
		);
		
		$this->add_control(
			'turbulence_enabled',
			[
				'label' => __('Radial Wave Effect', 'wpkoi'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __('On', 'wpkoi'),
				'label_off' => __('Off', 'wpkoi'),
				'return_value' => 'yes',
				'default' => '',
				'description' => esc_html__( 'Adds a circular wave motion that radiates from the center of the image, creating a dynamic ripple effect.', 'wpkoi-elements' ),
			]
		);

		$this->add_control(
			'turbulence_speed',
			[
				'label' => __('Wave Speed', 'wpkoi'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 15,
						'step' => 0.1,
					],
				],
				'default' => [
					'size' => 1.3,
				],
				'condition' => [
					'turbulence_enabled' => 'yes',
				],
				'description' => esc_html__( 'Controls how fast the radial waves move across the surface.', 'wpkoi-elements' ),
			]
		);

		$this->add_control(
			'turbulence_frequency',
			[
				'label' => __('Wave Density', 'wpkoi'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 3,
						'step' => 0.05,
					],
				],
				'default' => [
					'size' => 0.8,
				],
				'condition' => [
					'turbulence_enabled' => 'yes',
				],
				'description' => esc_html__( 'Adjust how close the waves appear to each other. Higher values create tighter ripples.', 'wpkoi-elements' ),
			]
		);

		$this->add_control(
			'turbulence_amplitude',
			[
				'label' => __('Wave Strength', 'wpkoi'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 4,
						'step' => 0.05,
					],
				],
				'default' => [
					'size' => 0.25,
				],
				'condition' => [
					'turbulence_enabled' => 'yes',
				],
				'description' => esc_html__( 'Increase to make the waves more pronounced and dramatic.', 'wpkoi-elements' ),
			]
		);

		$this->add_control(
			'turbulence_falloff',
			[
				'label' => __('Wave Fade Distance', 'wpkoi'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 3,
						'step' => 0.05,
					],
				],
				'default' => [
					'size' => 1.3,
				],
				'condition' => [
					'turbulence_enabled' => 'yes',
				],
				'description' => esc_html__( 'Controls how quickly the waves fade toward the edges of the image.', 'wpkoi-elements' ),
			]
		);

		$this->end_controls_section();
	}

	protected function render() {

		$settings = $this->get_settings_for_display();

		if ( empty( $settings['image']['url'] ) && empty( $settings['image']['id'] ) ) {
			return;
		}

		$allowed_projection_modes = [ 'standard', 'brick', 'bubbles', 'spikes' ];
		$allowed_animation_modes  = [ 'autoplay', 'hover', 'scroll' ];

		$clamp = function( $value, $min, $max ) {
			$value = floatval( $value );
			return max( $min, min( $max, $value ) );
		};

		$widget_id = esc_attr( $this->get_id() );
		$image_url = '';

		if ( ! empty( $settings['image']['id'] ) ) {

			$image_url = wp_get_attachment_image_url(
				$settings['image']['id'],
				'full'
			);

		}

		if ( ! $image_url && ! empty( $settings['image']['url'] ) ) {
			$image_url = $settings['image']['url'];
		}

		$image_url = esc_url_raw( $image_url );

		$projection_mode = in_array( $settings['projection_mode'] ?? '', $allowed_projection_modes, true )
			? $settings['projection_mode']
			: 'standard';

		$animation_mode = in_array( $settings['animation_mode'] ?? '', $allowed_animation_modes, true )
			? $settings['animation_mode']
			: 'autoplay';

		$disable_mobile = ! empty( $settings['disable_mobile'] );

		$scale_multiplier   = $clamp( $settings['scale_multiplier']['size'] ?? 1, 0.5, 2 );
		$projection_strength= $clamp( $settings['projection_strength']['size'] ?? 1, 0, 3 );
		$vertex_speed       = $clamp( $settings['vertex_speed']['size'] ?? 1, 0, 5 );
		$light_intensity    = $clamp( $settings['light_intensity']['size'] ?? 0.8, 0, 2 );
		$ambient_level      = $clamp( $settings['ambient_level']['size'] ?? 0.5, 0, 1 );
		$hole_radius        = $clamp( $settings['hole_radius']['size'] ?? 0.3, 0.1, 1 );

		$turbulence_enabled = ! empty( $settings['turbulence_enabled'] );
		$turbulence_speed   = $clamp( $settings['turbulence_speed']['size'] ?? 1.3, 0, 15 );
		$turbulence_freq    = $clamp( $settings['turbulence_frequency']['size'] ?? 0.8, 0, 3 );
		$turbulence_amp     = $clamp( $settings['turbulence_amplitude']['size'] ?? 0.25, 0, 4 );
		$turbulence_falloff = $clamp( $settings['turbulence_falloff']['size'] ?? 1.3, 0, 3 );
		$instance_density 	= $clamp( $settings['instance_density']['size'] ?? 1, 0.5, 2 );

		$rgb_glitch   = isset( $settings['rgb_glitch'] ) && $settings['rgb_glitch'] === 'yes';
		$mouse_effect = isset( $settings['mouse_effect'] ) && $settings['mouse_effect'] === 'yes';

		$link_attrs = '';

		if ( ! empty( $settings['link'] ) && is_array( $settings['link'] ) ) {

			$url = ! empty( $settings['link']['url'] )
				? esc_url( $settings['link']['url'] )
				: '';

			if ( $url ) {

				$target = ! empty( $settings['link']['is_external'] ) ? ' target="_blank"' : '';

				$rel_values = [ 'noopener' ];

				if ( ! empty( $settings['link']['nofollow'] ) ) {
					$rel_values[] = 'nofollow';
				}

				$rel = ' rel="' . esc_attr( implode( ' ', $rel_values ) ) . '"';

				$link_attrs = 'href="' . esc_url( $url ) . '"' . $target . $rel;
			}
		}

		$data_settings = [
			'image'              => esc_url_raw( $image_url ),
			'projectionMode'     => $projection_mode,
			'animationMode'      => $animation_mode,
			'instanceDensity' 	 => $instance_density,
			'projectionStrength' => $projection_strength,
			'vertexSpeed'        => $vertex_speed,
			'scaleMultiplier'    => $scale_multiplier,
			'disableMobile'      => $disable_mobile,
			'mouseEffect'        => $mouse_effect,
			'holeRadius'         => $hole_radius,
			'rgbGlitch'          => $rgb_glitch,
			'lightIntensity'     => $light_intensity,
			'ambientLevel'       => $ambient_level,
			'turbulenceEnabled'  => $turbulence_enabled,
			'turbulenceSpeed'    => $turbulence_speed,
			'turbulenceFrequency'=> $turbulence_freq,
			'turbulenceAmplitude'=> $turbulence_amp,
			'turbulenceFalloff'  => $turbulence_falloff,
		];

		$json = wp_json_encode(
			$data_settings,
			JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
		);

		?>

		<div class="wpkoi-distorted-image-wrapper"
			 id="wpkoi-distorted-image-<?php echo esc_attr( $widget_id ); ?>"
			 data-settings="<?php echo esc_attr( $json ); ?>">

			<?php if ( $link_attrs ) : ?>
				<a class="wpkoi-distorted-image-link" <?php echo $link_attrs; ?>></a>
			<?php endif; ?>

			<div class="wpkoi-distorted-image-canvas"></div>

		</div>

		<?php
	}

	protected function content_template() {}
}

Plugin::instance()->widgets_manager->register( new Widget_WPKoi_Distorted_Image() );