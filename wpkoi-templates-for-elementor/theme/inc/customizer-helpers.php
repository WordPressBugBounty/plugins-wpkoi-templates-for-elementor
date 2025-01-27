<?php
/**
 * Load necessary Customizer controls and functions.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Create our template parts section.
if ( class_exists( 'WP_Customize_Section' ) && ! class_exists( 'WPKoi_Template_Parts_Section' ) ) {
	class WPKoi_Template_Parts_Section extends WP_Customize_Control {
		public $type = 'wpkoi-template-parts-section';
		public $tp_url = '';
		public $tp_text = '';
		public $id = '';

		public function json() {
			$json = parent::json();
			$json['tp_text'] = $this->tp_text;
			$json['tp_url']  = esc_url( $this->tp_url );
			$json['id'] = $this->id;
			return $json;
		}

		protected function content_template() {
			?>
			<a href="{{{ data.tp_url }}}" target="_blank"><h3 class="accordion-section-title">{{ data.tp_text }}</h3></a>
			<?php
		}
	}
}

// Create our upsell section.
if ( class_exists( 'WP_Customize_Section' ) && ! class_exists( 'WPKoi_Upsell_Section' ) ) {
	class WPKoi_Upsell_Section extends WP_Customize_Section {
		public $type = 'wpkoi-upsell-section';
		public $pro_url = '';
		public $pro_text = '';
		public $id = '';

		public function json() {
			$json = parent::json();
			$json['pro_text'] = $this->pro_text;
			$json['pro_url']  = esc_url( $this->pro_url );
			$json['id'] = $this->id;
			return $json;
		}

		protected function render_template() {
			?>
			<li id="accordion-section-{{ data.id }}" class="wpkoi-upsell-accordion-section control-section-{{ data.type }} cannot-expand accordion-section">
				<h3><a href="{{{ data.pro_url }}}" target="_blank">{{ data.pro_text }}</a></h3>
			</li>
			<?php
		}
	}
}

// Create our in-section upsell controls.
if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'WPKoi_Customize_Misc_Control' ) ) {
	class WPKoi_Customize_Misc_Control extends WP_Customize_Control {
		public $description = '';
		public $url = '';
		public $type = 'addon';
		public $label = '';

		public function enqueue() {}

		public function to_json() {
			parent::to_json();
			$this->json[ 'url' ] = esc_url( $this->url );
		}

		public function content_template() {
			?>
			<div class="wpkoi-addon">
				<svg class="get-wpkoi-addon-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M180.5 141.5C219.7 108.5 272.6 80 336 80s116.3 28.5 155.5 61.5c39.1 33 66.9 72.4 81 99.8c4.7 9.2 4.7 20.1 0 29.3c-14.1 27.4-41.9 66.8-81 99.8C452.3 403.5 399.4 432 336 432s-116.3-28.5-155.5-61.5c-16.2-13.7-30.5-28.5-42.7-43.1L48.1 379.6c-12.5 7.3-28.4 5.3-38.7-4.9S-3 348.7 4.2 336.1L50 256 4.2 175.9c-7.2-12.6-5-28.4 5.3-38.6s26.1-12.2 38.7-4.9l89.7 52.3c12.2-14.6 26.5-29.4 42.7-43.1zM448 256a32 32 0 1 0 -64 0 32 32 0 1 0 64 0z"/></svg>
				<p class="wpkoi-addon-description">{{{ data.description }}}</p>
				<ul class="get-wpkoi-addon-list">
					<li><p><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/></svg> <?php echo esc_html( 'Full demo import', 'wpkoi-templates-for-elementor' ); ?></p></li>
					<li><p><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/></svg> <?php echo esc_html( 'Customizable colors', 'wpkoi-templates-for-elementor' ); ?></p></li>
					<li><p><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/></svg> <?php echo esc_html( 'Customizable typography', 'wpkoi-templates-for-elementor' ); ?></p></li>
					<li><p><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/></svg> <?php echo esc_html( 'Margin controls', 'wpkoi-templates-for-elementor' ); ?></p></li>
					<li><p><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/></svg> <?php echo esc_html( 'Sticky header', 'wpkoi-templates-for-elementor' ); ?></p></li>
					<li><p><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/></svg> <?php echo esc_html( 'Extra Elementor features', 'wpkoi-templates-for-elementor' ); ?></p></li>
					<li><p><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/></svg> <?php echo esc_html( 'Much more...', 'wpkoi-templates-for-elementor' ); ?></p></li>
				</ul>
				<span class="get-addon get-wpkoi-addon">
					<a href="{{{ data.url }}}" class="get-wpkoi-addon-button" target="_blank">{{ data.label }}</a>
				</span>
			</div>
			<?php
		}
	}
}

// Create our in-section upsell controls.
if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'WPKoi_Customize_Help_Control' ) ) {
	class WPKoi_Customize_Help_Control extends WP_Customize_Control {
		public $description = '';
		public $url = '';
		public $type = 'addonhelp';
		public $label = '';

		public function enqueue() {}

		public function to_json() {
			parent::to_json();
			$this->json[ 'url' ] = esc_url( $this->url );
		}

		public function content_template() {
			?>
			<p class="wpkoi-addon-description">{{{ data.description }}}</p>
			<span class="get-addon get-wpkoi-addon">
				<a href="{{{ data.url }}}" class="get-wpkoi-addon-button" target="_blank">{{ data.label }}</a>
			</span>
			<?php
		}
	}
}

// Create a control to display titles within our sections
if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'WPKoi_Title_Customize_Control' ) ) :
class WPKoi_Title_Customize_Control extends WP_Customize_Control {
	public $type = 'wpkoi-customizer-title';
	public $title = '';
	
	public function enqueue() {
	}

	public function to_json() {
		parent::to_json();
		$this->json[ 'title' ] = esc_html( $this->title );
	}
	
	public function content_template() {
		?>
		<div class="wpkoi-customizer-title">
			<span>{{ data.title }}</span>
		</div>
		<?php
	}
}
endif;

// Alpha color selector
if ( ! class_exists( 'WPKoi_Alpha_Color_Customize_Control' ) ) :
class WPKoi_Alpha_Color_Customize_Control extends WP_Customize_Control {
	
	public $type = 'gp-alpha-color';
	
	public $palette;
	
	public $show_opacity;
	
	public function enqueue() {}

	public function to_json() {
		parent::to_json();
		$this->json['palette'] = $this->palette;
		$this->json['defaultValue'] = $this->setting->default;
		$this->json[ 'link' ] = $this->get_link();
		$this->json[ 'show_opacity' ] = $this->show_opacity;

		if ( is_array( $this->json['palette'] ) ) {
			$this->json['palette'] = implode( '|', $this->json['palette'] );
		} else {
			// Default to true.
			$this->json['palette'] = ( false === $this->json['palette'] || 'false' === $this->json['palette'] ) ? 'false' : 'true';
		}

		// Support passing show_opacity as string or boolean. Default to true.
		$this->json[ 'show_opacity' ] = ( false === $this->json[ 'show_opacity' ] || 'false' === $this->json[ 'show_opacity' ] ) ? 'false' : 'true';
	}

	public function render_content() {}

	public function content_template() {
		?>
		<# if ( data.label && '' !== data.label ) { #>
			<span class="customize-control-title">{{ data.label }}</span>
		<# } #>
		<input class="gp-alpha-color-control" type="text" data-palette="{{{ data.palette }}}" data-default-color="{{ data.defaultValue }}" {{{ data.link }}} />
		<?php
	}
}
endif;

// Range slider control
if ( ! function_exists( 'WPKoi_Pro_Range_Slider_Control' ) ) :
class WPKoi_Pro_Range_Slider_Control extends WP_Customize_Control {
	
	public $type = 'wpkoi-pro-range-slider';

	public $description = '';

	public function to_json() {
		parent::to_json();

		$devices = array( 'desktop','tablet','mobile' );
		foreach ( $devices as $device ) {
			$this->json['choices'][$device]['min']  = ( isset( $this->choices[$device]['min'] ) ) ? $this->choices[$device]['min'] : '0';
			$this->json['choices'][$device]['max']  = ( isset( $this->choices[$device]['max'] ) ) ? $this->choices[$device]['max'] : '100';
			$this->json['choices'][$device]['step'] = ( isset( $this->choices[$device]['step'] ) ) ? $this->choices[$device]['step'] : '1';
			$this->json['choices'][$device]['edit'] = ( isset( $this->choices[$device]['edit'] ) ) ? $this->choices[$device]['edit'] : false;
			$this->json['choices'][$device]['unit'] = ( isset( $this->choices[$device]['unit'] ) ) ? $this->choices[$device]['unit'] : false;
			$this->json['choices'][$device]['units'] = ( isset( $this->choices[$device]['units'] ) ) ? $this->choices[$device]['units'] : false;
		}

		foreach ( $this->settings as $setting_key => $setting_id ) {
			
			$unit_setting = '';
			if ( $setting_key == 'desktop') {
				$unit_setting = 'desktop_unit';
			}
			if ( $setting_key == 'tablet') {
				$unit_setting = 'tablet_unit';
			}
			if ( $setting_key == 'mobile') {
				$unit_setting = 'mobile_unit';
			}
			
			$link = $this->get_link( $setting_key );
	
			// Extract the setting name from the link value
			preg_match('/wpkoi_settings\[(.*?)\]/', $link, $matches);
			$setting_name = isset($matches[1]) ? $matches[1] : '';
			
			// Add "_unit" to the setting name
			$setting_name .= '_unit';

			// Add "desktop_" prefix if the setting name doesn't start with "tablet_" or "mobile_"
			if (strpos($setting_name, 'tablet_') !== 0 && strpos($setting_name, 'mobile_') !== 0) {
				$setting_name = 'desktop_' . $setting_name;
			}
			
			$this->json[ $setting_key ] = array(
				'link'  => $this->get_link( $setting_key ),
				'value' => $this->value( $setting_key ),
				'unit'  => isset( $this->settings[$unit_setting] ) ? $this->value( $unit_setting ) : 'px',
				'default' => isset( $setting_id->default ) ? $setting_id->default : '',
				'setting_name' => $setting_name, // Add the transformed setting name here
			);
		}

		$this->json['desktop_label'] = __( 'Desktop', 'wpkoi-templates-for-elementor' );
		$this->json['tablet_label'] = __( 'Tablet', 'wpkoi-templates-for-elementor' );
		$this->json['mobile_label'] = __( 'Mobile', 'wpkoi-templates-for-elementor' );
		$this->json['reset_label'] = __( 'Reset', 'wpkoi-templates-for-elementor' );

		$this->json['description'] = $this->description;
	}
	
	public function enqueue() {}
	
	protected function content_template() {
		?>
		<div class="wpkoi-range-slider-control">
			<div class="gp-range-title-area">
				<# if ( data.label || data.description ) { #>
					<div class="gp-range-title-info">
						<# if ( data.label ) { #>
							<span class="customize-control-title">{{{ data.label }}}</span>
						<# } #>

						<# if ( data.description ) { #>
							<p class="description">{{{ data.description }}}</p>
						<# } #>
					</div>
				<# } #>

				<div class="gp-range-slider-controls">
					<span class="gp-device-controls">
						<# if ( 'undefined' !== typeof ( data.desktop ) ) { #>
							<span class="wpkoi-device-desktop dashicons dashicons-desktop" data-option="desktop" title="{{ data.desktop_label }}"></span>
						<# } #>

						<# if ( 'undefined' !== typeof (data.tablet) ) { #>
							<span class="wpkoi-device-tablet dashicons dashicons-tablet" data-option="tablet" title="{{ data.tablet_label }}"></span>
						<# } #>

						<# if ( 'undefined' !== typeof (data.mobile) ) { #>
							<span class="wpkoi-device-mobile dashicons dashicons-smartphone" data-option="mobile" title="{{ data.mobile_label }}"></span>
						<# } #>
					</span>

					<span title="{{ data.reset_label }}" class="wpkoi-reset dashicons dashicons-image-rotate"></span>
				</div>
			</div>

			<div class="gp-range-slider-areas">
				<# if ( 'undefined' !== typeof ( data.desktop ) ) { #>
					<label class="range-option-area" data-option="desktop" style="display: none;">
						<div class="wrapper <# if ( '' !== data.choices['desktop']['unit'] ) { #>has-unit<# } #> <# if ( data.choices['desktop']['units'] ) { #>has-unit-selector<# } #>">
							<div class="gp_range_value <# if ( '' == data.choices['desktop']['unit'] && ! data.choices['desktop']['edit'] ) { #>hide-value<# } #>">
								<input <# if ( data.choices['desktop']['edit'] ) { #>style="display:inline-block;"<# } else { #>style="display:none;"<# } #> type="number" step="{{ data.choices['desktop']['step'] }}" class="desktop-range value" value="{{ data.desktop.value }}" min="{{ data.choices['desktop']['min'] }}" max="{{ data.choices['desktop']['max'] }}" {{{ data.desktop.link }}} data-reset_value="{{ data.desktop.default }}" />
								<span <# if ( ! data.choices['desktop']['edit'] ) { #>style="display:inline-block;"<# } else { #>style="display:none;"<# } #> class="value">{{ data.desktop.value }}</span>

								<# if ( data.choices['desktop']['units'] ) { #>
									<select data-customize-setting-link="wpkoi_settings[{{ data.desktop.setting_name }}]" class="unit-selector">
										<#
										var selectedUnit = ( data.desktop.unit ) ? data.desktop.unit : data.choices['desktop']['unit'];
										_.each( data.choices['desktop']['units'], function( unitOption ) {
										#>
											<option value="{{ unitOption }}" <# if ( unitOption === selectedUnit ) { #> selected <# } #>>{{ unitOption }}</option>
										<# }); #>
									</select>
								<# } else if ( data.choices['desktop']['unit'] ) { #>
									<span class="unit">{{ data.choices['desktop']['unit'] }}</span>
								<# } #>	
							</div>
							<div class="wpkoi-slider" data-step="{{ data.choices['desktop']['step'] }}" data-min="{{ data.choices['desktop']['min'] }}" data-max="{{ data.choices['desktop']['max'] }}"></div>
						</div>
					</label>
				<# } #>

				<# if ( 'undefined' !== typeof ( data.tablet ) ) { #>
					<label class="range-option-area" data-option="tablet" style="display:none">
						<div class="wrapper <# if ( '' !== data.choices['tablet']['unit'] ) { #>has-unit<# } #> <# if ( data.choices['tablet']['units'] ) { #>has-unit-selector<# } #>">
							<div class="gp_range_value <# if ( '' == data.choices['tablet']['unit'] && ! data.choices['desktop']['edit'] ) { #>hide-value<# } #>">
								<input <# if ( data.choices['tablet']['edit'] ) { #>style="display:inline-block;"<# } else { #>style="display:none;"<# } #> type="number" step="{{ data.choices['tablet']['step'] }}" class="tablet-range value" value="{{ data.tablet.value }}" min="{{ data.choices['tablet']['min'] }}" max="{{ data.choices['tablet']['max'] }}" {{{ data.tablet.link }}} data-reset_value="{{ data.tablet.default }}" />
								<span <# if ( ! data.choices['tablet']['edit'] ) { #>style="display:inline-block;"<# } else { #>style="display:none;"<# } #> class="value">{{ data.tablet.value }}</span>

								<# if ( data.choices['tablet']['units'] ) { #>
									<select data-customize-setting-link="wpkoi_settings[{{ data.tablet.setting_name }}]" class="unit-selector">
										<# 
										var selectedUnit = ( data.tablet.unit ) ? data.tablet.unit : data.choices['tablet']['unit'];
										_.each( data.choices['tablet']['units'], function( unitOption ) { 
										#>
											<option value="{{ unitOption }}" <# if ( unitOption === selectedUnit ) { #> selected <# } #>>{{ unitOption }}</option>
										<# }); #>
									</select>
								<# } else if ( data.choices['tablet']['unit'] ) { #>
									<span class="unit">{{ data.choices['tablet']['unit'] }}</span>
								<# } #>	
							</div>
							<div class="wpkoi-slider" data-step="{{ data.choices['tablet']['step'] }}" data-min="{{ data.choices['tablet']['min'] }}" data-max="{{ data.choices['tablet']['max'] }}"></div>
						</div>
					</label>
				<# } #>

				<# if ( 'undefined' !== typeof ( data.mobile ) ) { #>
					<label class="range-option-area" data-option="mobile" style="display:none;">
						<div class="wrapper <# if ( '' !== data.choices['mobile']['unit'] ) { #>has-unit<# } #> <# if ( data.choices['mobile']['units'] ) { #>has-unit-selector<# } #>">
							<div class="gp_range_value <# if ( '' == data.choices['mobile']['unit'] && ! data.choices['desktop']['edit'] ) { #>hide-value<# } #>">
								<input <# if ( data.choices['mobile']['edit'] ) { #>style="display:inline-block;"<# } else { #>style="display:none;"<# } #> type="number" step="{{ data.choices['mobile']['step'] }}" class="mobile-range value" value="{{ data.mobile.value }}" min="{{ data.choices['mobile']['min'] }}" max="{{ data.choices['mobile']['max'] }}" {{{ data.mobile.link }}} data-reset_value="{{ data.mobile.default }}" />
								<span <# if ( ! data.choices['mobile']['edit'] ) { #>style="display:inline-block;"<# } else { #>style="display:none;"<# } #> class="value">{{ data.mobile.value }}</span>

								<# if ( data.choices['mobile']['units'] ) { #>
									<select data-customize-setting-link="wpkoi_settings[{{ data.mobile.setting_name }}]" class="unit-selector">
										<# 
										var selectedUnit = ( data.mobile.unit ) ? data.mobile.unit : data.choices['mobile']['unit'];
										_.each( data.choices['mobile']['units'], function( unitOption ) { 
										#>
											<option value="{{ unitOption }}" <# if ( unitOption === selectedUnit ) { #> selected <# } #>>{{ unitOption }}</option>
										<# }); #>
									</select>
								<# } else if ( data.choices['mobile']['unit'] ) { #>
									<span class="unit">{{ data.choices['mobile']['unit'] }}</span>
								<# } #>	
							</div>
							<div class="wpkoi-slider" data-step="{{ data.choices['mobile']['step'] }}" data-min="{{ data.choices['mobile']['min'] }}" data-max="{{ data.choices['mobile']['max'] }}"></div>
						</div>
					</label>
				<# } #>
			</div>
		</div>
		<?php
	}
}
endif;

// Add CSS for our controls
if ( ! function_exists( 'wpkoi_customizer_controls_css' ) ) {
	add_action( 'customize_controls_enqueue_scripts', 'wpkoi_customizer_controls_css' );
	function wpkoi_customizer_controls_css() {
		wp_enqueue_style( 'wpkoi-customizer-controls-css', esc_url( WPKOI_TEMPLATES_FOR_ELEMENTOR_URL ) . 'theme/css/customizer-styles.css', array(), WPKOI_TEMPLATES_FOR_ELEMENTOR_VERSION );
		wp_enqueue_script( 'wpkoi-upsell', esc_url( WPKOI_TEMPLATES_FOR_ELEMENTOR_URL ) . 'theme/js/customizer-scripts.js', array( 'jquery', 'customize-base', 'jquery-ui-slider' ), WPKOI_TEMPLATES_FOR_ELEMENTOR_VERSION, true );
	}
}

// Helper functions
if ( ! function_exists( 'wpkoi_customize_partial_blogname' ) ) {
	function wpkoi_customize_partial_blogname() {
		bloginfo( 'name' );
	}
}

// Render the site tagline for the selective refresh partial.
if ( ! function_exists( 'wpkoi_customize_partial_blogdescription' ) ) {
	function wpkoi_customize_partial_blogdescription() {
		bloginfo( 'description' );
	}
}

// Add our custom color palettes to the color pickers in the Customizer.
if ( ! function_exists( 'wpkoi_enqueue_color_palettes' ) ) {
	add_action( 'customize_controls_enqueue_scripts', 'wpkoi_enqueue_color_palettes' );
	function wpkoi_enqueue_color_palettes() {
		
		// Grab our palette array and turn it into JS
		$palettes = json_encode( wpkoi_get_default_color_palettes() );

		// Add our custom palettes
		// json_encode takes care of escaping
		wp_add_inline_script( 'wp-color-picker', 'jQuery.wp.wpColorPicker.prototype.options.palettes = ' . $palettes . ';' );
	}
}

// Sanitize colors.
if ( ! function_exists( 'wpkoi_sanitize_hex_color' ) ) {
	function wpkoi_sanitize_hex_color( $color ) {
	    if ( '' === $color ) {
	        return '';
		}

	    // 3 or 6 hex digits, or the empty string.
	    if ( preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) ) {
	        return $color;
		}

	    return '';
	}
}

// Sanitize RGBA colors
if ( ! function_exists( 'wpkoi_sanitize_rgba' ) ) {
	function wpkoi_sanitize_rgba( $color ) {
	    if ( '' === $color ) {
	        return '';
		}

		// If string does not start with 'rgba', then treat as hex
		// sanitize the hex color and finally convert hex to rgba
		if ( false === strpos( $color, 'rgba' ) ) {
			return wpkoi_sanitize_hex_color( $color );
		}

		// By now we know the string is formatted as an rgba color so we need to further sanitize it.
		$color = str_replace( ' ', '', $color );
		sscanf( $color, 'rgba(%d,%d,%d,%f)', $red, $green, $blue, $alpha );
		return 'rgba('.$red.','.$green.','.$blue.','.$alpha.')';

	    return '';
	}
}

// Sanitize choices
if ( ! function_exists( 'wpkoi_sanitize_choices' ) ) {
	function wpkoi_sanitize_choices( $input, $setting ) {
		// Ensure input is a slug
		$input = sanitize_key( $input );

		// Get list of choices from the control
		// associated with the setting
		$choices = $setting->manager->get_control( $setting->id )->choices;

		// If the input is a valid key, return it;
		// otherwise, return the default
		return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
	}
}

// Sanitize checkbox
if ( ! function_exists( 'wpkoi_sanitize_checkbox' ) ) {
	function wpkoi_sanitize_checkbox( $checked ) {
		// Boolean check.
		return ( ( isset( $checked ) && true == $checked ) ? true : false );
	}
}

// Sanitize integers that can use decimals
if ( ! function_exists( 'wpkoi_sanitize_decimal_integer' ) ) {
	function wpkoi_sanitize_decimal_integer( $input ) {
		return abs( floatval( $input ) );
	}
}

// Sanitize integers that can use decimals
function wpkoi_sanitize_decimal_integer_empty( $input ) {
	if ( '' == $input ) {
		return '';
	}

	return abs( floatval( $input ) );
}

// Sanitize empty absint
if ( ! function_exists( 'wpkoi_sanitize_empty_absint' ) ) {
	function wpkoi_sanitize_empty_absint( $input ) {
		if ( '' == $input ) {
			return '';
		}

		return absint( $input );
	}
}