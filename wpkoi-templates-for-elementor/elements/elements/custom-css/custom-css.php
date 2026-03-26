<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'WPKoi_Custom_CSS_Lite' ) ) {

class WPKoi_Custom_CSS_Lite {

	private static $instance = null;

	public function __construct() {
		
		add_action('elementor/element/after_section_end', [$this, 'add_section_custom_css_controls'], 25, 3);
		add_action('elementor/element/parse_css', [$this, 'add_post_css'], 10, 2);
		
	}

	public function add_section_custom_css_controls($widget, $section_id, $args) {
		if ( 'section_custom_css_pro' !== $section_id ) {
			return;
		}

		$widget->start_controls_section(
			'wpkoi_section_custom_css',
			[
				'label' => esc_html__('WPKoi Custom CSS', 'wpkoi-elements'),
				'tab' => Elementor\Controls_Manager::TAB_ADVANCED
			]
		);

		$widget->add_control(
			'wpkoi_custom_css',
			[
				'type' => Elementor\Controls_Manager::CODE,
				'label' => esc_html__('Custom CSS', 'wpkoi-elements'),
				'language' => 'css',
				'render_type' => 'ui',
				'label_block' => true,
			]
		);

		$widget->add_control(
			'wpkoi_custom_css_description',
			[
				'raw' => esc_html__('Use "selector" to define this element.', 'wpkoi-elements'),
				'type' => Elementor\Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
				'separator' => 'none'
			]
		);

		$widget->end_controls_section();
	}



	public function add_post_css($post_css, $element) {
		$element_settings = $element->get_settings();

		if ( empty($element_settings['wpkoi_custom_css']) ) {
			return;
		}

		$css = trim($element_settings['wpkoi_custom_css']);

		if ( empty($css) ) {
			return;
		}

		$css = str_replace('selector', $post_css->get_element_unique_selector($element), $css);

		$css = sprintf('/* Start custom CSS for class: %s */', $element->get_unique_selector()) . $css;

		$post_css->get_stylesheet()->add_raw_css($css);
	}
}
	
}

new WPKoi_Custom_CSS_Lite();
