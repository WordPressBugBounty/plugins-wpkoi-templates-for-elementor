<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.


class Widget_Lite_WPKoi_Darkmode extends Widget_Base {

	public function get_name() {
		return 'wpkoi-darkmode';
	}

	public function get_title() {
		return esc_html__( 'Darkmode', 'wpkoi-elements' );
	}

	public function get_icon() {
		return 'eicon-adjust';
	}

    public function get_categories() {
		return [ 'wpkoi-addons-for-elementor' ];
	}
	
	public function get_help_url() {
		return 'https://wpkoi.com/wpkoi-elementor-templates-demo/elements/darkmode/';
	}


	protected function register_controls() {


  		$this->start_controls_section(
            'section_layout',
            [
                'label' => esc_html__('Dark Mode', 'wpkoi-elements'),
            ]
        );
		
		$this->add_control(
			'darkmode_subheading',
			[
				'label' => esc_html__( 'The result of the effects is not visible in the editor, only on the live page.', 'wpkoi-elements' ),
				'type'  => Controls_Manager::HEADING
			]
		);
		
		$this->add_control(
			'darkmode_subheading_2',
			[
				'label' => esc_html__( 'Add "darkmode-ignore" class to elements to exclude from dark mode.', 'wpkoi-elements' ),
				'type'  => Controls_Manager::HEADING
			]
		);

        $this->add_responsive_control(
            'icon_margin_right',
            [
                'label' => __('Margin from right', 'wpkoi-elements'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 40,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '.elementor-default .darkmode-toggle, .elementor-default  .darkmode-layer' => 'right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_margin_bottom',
            [
                'label' => __('Margin from bottom', 'wpkoi-elements'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 40,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    ' .elementor-default .darkmode-toggle, .elementor-default  .darkmode-layer' => 'bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
		
		$this->add_control(
			'icon_for_button',
			[
				'label' => esc_html__( 'Icon', 'wpkoi-elements' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-adjust',
					'library' => 'fa-solid',
				]
			]
		);

        $this->add_control(
            'time',
            [
                'label' => esc_html__('Animation Time', 'wpkoi-elements'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 500,
                    'unit' => 'px',
                ],
				'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 5000,
                    ],
                ],
            ]
        );

        $this->add_control(
            'saveInCookies',
            [
                'label' => esc_html__('Save In Cookies', 'wpkoi-elements'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_control(
            'autoMatchOsTheme',
            [
                'label' => esc_html__('Auto Match Os Theme', 'wpkoi-elements'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            [
                'label' => esc_html__('Dark Mode', 'wpkoi-elements'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'default_background',
            [
                'label' => esc_html__('Default Background Color', 'wpkoi-elements'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
            ]
        );

        $this->add_control(
            'mix_color',
            [
                'label' => esc_html__('Content Mix Color', 'wpkoi-elements'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff'
            ]
        );

        $this->add_control(
            'day_mode_icon_background',
            [
                'label' => esc_html__('Icon Background Day Mode', 'wpkoi-elements'),
                'type' => Controls_Manager::COLOR,
                'default' => '#111111',
                'selectors' => [
                    '.darkmode-toggle' => 'background: {{VALUE}} !important',
                ],
            ]
        );

        $this->add_control(
            'day_mode_icon_color',
            [
                'label' => esc_html__('Icon Day Mode', 'wpkoi-elements'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '.darkmode-toggle svg' => 'fill: {{VALUE}} !important',
                ],
            ]
        );

        $this->add_control(
            'dark_mode_icon_background',
            [
                'label' => esc_html__('Icon Background Dark Mode', 'wpkoi-elements'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '.darkmode-toggle.darkmode-toggle--white' => 'background: {{VALUE}} !important',
                ],
            ]
        );

        $this->add_control(
            'dark_mode_icon_color',
            [
                'label' => esc_html__('Icon Dark Mode', 'wpkoi-elements'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '.darkmode-toggle.darkmode-toggle--white svg' => 'fill: {{VALUE}} !important',
                ],
            ]
        );

        $this->add_control(
            'icon_size',
            [
                'label' => esc_html__('Icon Size', 'wpkoi-elements'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 18,
                ],
                'selectors' => [
                    '.darkmode-toggle svg' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'icon_button_width',
            [
                'label' => __('Switcher Size', 'wpkoi-elements'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 54,
                ],
                'selectors' => [
                    '.darkmode-toggle' => 'height: {{SIZE}}{{UNIT}} !important; width: {{SIZE}}{{UNIT}} !important;',
					'.darkmode-layer.darkmode-layer--button' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'icon_border',
                'label' => __('Border', 'wpkoi-elements'),
                'selector' => 'body .darkmode-toggle',
            ]
        );

        $this->add_control(
            'icon_border_radius',
            [
                'label' => __('Border Radius', 'wpkoi-elements'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '.darkmode-toggle, .darkmode-layer' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();


	}


	protected function render( ) {
		static $widget_rendered = false;

		// Check if the widget has already been rendered
		if ( $widget_rendered ) {
			return; // Don't render again
		}

		$widget_rendered = true; // Mark as rendered

        $settings = $this->get_settings();

        ?>

        <script>
            jQuery(document).ready(function($) {
				
				// Check if we're inside the Elementor editor
				if (typeof elementorFrontend !== 'undefined' && elementorFrontend.isEditMode()) {
					// Remove the darkmode-background div, darkmode-layer div and darkmode-toggle button if added before.
					$('.darkmode-background').remove();
					$('.darkmode-layer').remove();
					$('.darkmode-toggle').remove();
				}

                var options = {
                    left: 'unset', // default: 'unset'
                    time: '<?php echo esc_attr( $settings['time']['size'] ) / 1000; ?>s',
                    mixColor: '<?php echo esc_attr( $settings['mix_color'] ); ?>',
                    backgroundColor: '<?php echo esc_attr( $settings['default_background'] ); ?>',
                    saveInCookies: '<?php echo esc_attr( $settings['saveInCookies'] ); ?>',
                    label: '<?php Icons_Manager::render_icon( $settings['icon_for_button'], [ 'aria-hidden' => 'true' ] ); ?>',
                    autoMatchOsTheme: '<?php echo esc_attr( $settings['autoMatchOsTheme'] ); ?>'
                }

                const darkmode = new Darkmode(options);
                darkmode.showWidget();
				
				// Check if we're inside the Elementor editor
				if (typeof elementorFrontend !== 'undefined' && elementorFrontend.isEditMode()) {
					// Remove the darkmode-background div, darkmode-layer div and darkmode-toggle button if added before.
					$('.darkmode-background').remove();
					$('.darkmode-layer').remove();
				}
            });
        </script>

    <?php
    }
	
	public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);

		wp_register_script('darkmode',WPKOI_ELEMENTS_LITE_URL.'elements/darkmode/assets/darkmode-js.min.js', [ 'elementor-frontend', 'jquery' ],'1.0', true);
	}

	public function get_script_depends() {
		return [ 'darkmode' ];
	}

	protected function content_template() {}
}


Plugin::instance()->widgets_manager->register( new Widget_Lite_WPKoi_Darkmode() );