<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'WPKoi_Elements_Lite_Integration' ) ) {

	/**
	 * Define WPKoi_Elements_Lite_Integration class
	 */
	class WPKoi_Elements_Lite_Integration {

		/**
		 * A reference to an instance of this class.
		 */
		private static $instance = null;

		/**
		 * Localize data
		 */
		public $elements_data = array(
			'sections' => array(),
			'columns'  => array(),
			'widgets'  => array(),
		);

		/**
		 * Check if processing elementor widget
		 */
		private $is_elementor_ajax = false;

		/**
		 * Localize data array
		 */
		public $localize_data = array();

		/**
		 * Initalize integration hooks
		 */
		public function init() {
			
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );

			add_action( 'elementor/widgets/register', array( $this, 'register_addons' ), 10 );

			add_action( 'elementor/controls/controls_registered', array( $this, 'add_controls' ), 10 );

			add_action( 'wp_ajax_elementor_render_widget', array( $this, 'set_elementor_ajax' ), 10, -1 );
			
			add_action( 'elementor/frontend/before_register_scripts', array( $this, 'register_scripts' ) );

			add_action( 'elementor/frontend/before_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'editor_scripts' ) );

			// Frontend messages
			$this->localize_data['messages'] = array(
				'invalidMail' => esc_html__( 'Please specify a valid e-mail', 'wpkoi-elements' ),
			);
		}

		/**
		 * Enqueue public-facing stylesheets.
		 *
		 */
		public function enqueue_styles() {

			wp_enqueue_style('wpkoi-elements',WPKOI_ELEMENTS_LITE_URL . 'assets/css/wpkoi-elements.css',false,WPKOI_ELEMENTS_LITE_VERSION);
			
			// options for effects
			$wtfe_element_effects 		= get_option( 'wtfe_element_effects', '' );
			
			$wtfe_advanced_accordion 	= get_option( 'wtfe_advanced_accordion', '' );
			$wtfe_advanced_headings 	= get_option( 'wtfe_advanced_headings', '' );
			$wtfe_animated_text			= get_option( 'wtfe_animated_text', '' );
			$wtfe_button 				= get_option( 'wtfe_button', '' );
			$wtfe_countdown 			= get_option( 'wtfe_countdown', '' );
			$wtfe_darkmode			 	= get_option( 'wtfe_darkmode', '' );
			$wtfe_distorted_headings 	= get_option( 'wtfe_distorted_headings', '' );
			$wtfe_scrolling_text	 	= get_option( 'wtfe_scrolling_text', '' );
			$wtfe_qr_code 				= get_option( 'wtfe_qr_code', '' );
			
			if ( $wtfe_animated_text != true ) {
				wp_enqueue_script('wpkoi-anime-js');
			}
			
			if ( $wtfe_element_effects != true ) {
				wp_enqueue_style('wpkoi-effects-style',WPKOI_ELEMENTS_LITE_URL . 'elements/effects/assets/effects.css',false,WPKOI_ELEMENTS_LITE_VERSION);
			}

			$default_theme_enabled = apply_filters( 'wpkoi-elements/assets/css/default-theme-enabled', true );

			if ( ! $default_theme_enabled ) {
				return;
			}

			if ( wpkoi_elements_lite_integration()->in_elementor() ) {
				// Enqueue mediaelement css only in the editor.
				wp_enqueue_style( 'mediaelement' );
			}
		}
		
		/**
		 * Register plugin scripts
		 */
		public function register_scripts() {

			// Register vendor anime.js script (https://github.com/juliangarnier/anime)
			wp_register_script(
				'wpkoi-anime-js',
				WPKOI_ELEMENTS_LITE_URL . 'assets/js/anime.min.js',
				array(),
				WPKOI_ELEMENTS_LITE_VERSION,
				true
			);
		}

		/**
		 * Enqueue plugin scripts only with elementor scripts
		 */
		public function enqueue_scripts() {
			
			wp_enqueue_script(
				'wpkoi-elements-lite',
				WPKOI_ELEMENTS_LITE_URL . 'assets/js/wpkoi-elements.js',
				array( 'jquery', 'elementor-frontend' ),
				WPKOI_ELEMENTS_LITE_VERSION,
				true
			);
			
			wp_enqueue_script( 'wpkoi-effects-js',
				WPKOI_ELEMENTS_LITE_URL . 'elements/effects/assets/effects.js', 
				array( 'jquery', 'elementor-frontend' ), 
				WPKOI_ELEMENTS_LITE_VERSION, 
				true 
			);
		}

		/**
		 * Enqueue plugin scripts only with elementor scripts
		 */
		public function editor_scripts() {
			wp_enqueue_script(
				'wpkoi-elements-editor',
				WPKOI_ELEMENTS_LITE_URL . 'assets/js/wpkoi-elements-editor.js',
				array( 'jquery' ),
				WPKOI_ELEMENTS_LITE_VERSION,
				true
			);
		}

		/**
		 * Set $this->is_elementor_ajax to true on Elementor AJAX processing
		 */
		public function set_elementor_ajax() {
			$this->is_elementor_ajax = true;
		}

		/**
		 * Check if we currently in Elementor mode
		 */
		public function in_elementor() {

			$result = false;

			if ( wp_doing_ajax() ) {
				$result = $this->is_elementor_ajax;
			} elseif ( Elementor\Plugin::instance()->editor->is_edit_mode()
				|| Elementor\Plugin::instance()->preview->is_preview_mode() ) {
				$result = true;
			}

			/**
			 * Allow to filter result before return
			 */
			return apply_filters( 'wpkoi-elements/in-elementor', $result );
		}

		/**
		 * Register plugin addons
		 */
		public function register_addons( $widgets_manager ) {

			$wtfe_advanced_accordion 	= get_option( 'wtfe_advanced_accordion', '' );
			$wtfe_advanced_headings 	= get_option( 'wtfe_advanced_headings', '' );
			$wtfe_animated_text			= get_option( 'wtfe_animated_text', '' );
			$wtfe_button 				= get_option( 'wtfe_button', '' );
			$wtfe_countdown 			= get_option( 'wtfe_countdown', '' );
			$wtfe_darkmode			 	= get_option( 'wtfe_darkmode', '' );
			$wtfe_distorted_headings 	= get_option( 'wtfe_distorted_headings', '' );
			$wtfe_scrolling_text	 	= get_option( 'wtfe_scrolling_text', '' );
			$wtfe_qr_code 				= get_option( 'wtfe_qr_code', '' );
			
			if ( $wtfe_advanced_accordion != true ) {
				$this->register_addon(  WPKOI_ELEMENTS_LITE_PATH . 'elements/advance-accordion/advance-accordion.php', $widgets_manager );
			}
			
			if ( $wtfe_advanced_headings != true ) {
				$this->register_addon(  WPKOI_ELEMENTS_LITE_PATH . 'elements/advanced-heading/advanced-heading.php', $widgets_manager );
			}

			if ( $wtfe_animated_text != true ) {
				$this->register_addon(  WPKOI_ELEMENTS_LITE_PATH . 'elements/animated-text/wpkoi-elements-animated-text.php', $widgets_manager );
			}

			if ( $wtfe_button != true ) {
				$this->register_addon(  WPKOI_ELEMENTS_LITE_PATH . 'elements/button/wpkoi-elements-button.php', $widgets_manager );
			}

			if ( $wtfe_countdown != true ) {
				$this->register_addon(  WPKOI_ELEMENTS_LITE_PATH . 'elements/countdown/countdown.php', $widgets_manager );
			}

			if ( $wtfe_darkmode != true ) {
				$this->register_addon(  WPKOI_ELEMENTS_LITE_PATH . 'elements/darkmode/darkmode.php', $widgets_manager );
			}
			
			if ( $wtfe_distorted_headings != true ) {
				$this->register_addon(  WPKOI_ELEMENTS_LITE_PATH . 'elements/distorted-heading/distorted-heading.php', $widgets_manager );
			}
			
			if ( $wtfe_scrolling_text != true ) {
				$this->register_addon(  WPKOI_ELEMENTS_LITE_PATH . 'elements/scrolling-text/scrolling-text.php', $widgets_manager );
			}
			
			if ( $wtfe_qr_code != true ) {
				$this->register_addon(  WPKOI_ELEMENTS_LITE_PATH . 'elements/qr-code/qr-code.php', $widgets_manager );
			}

		}

		/**
		 * Add new controls.
		 */
		public function add_controls( $controls_manager ) {

		}

		/**
		 * Include control file by class name.
		 */
		public function include_control( $class_name, $grouped = false ) {

			$filename = sprintf(
				'includes/controls/%2$sclass-%1$s.php',
				str_replace( '_', '-', strtolower( $class_name ) ),
				( true === $grouped ? 'groups/' : '' )
			);

			if ( ! file_exists( WPKOI_ELEMENTS_LITE_PATH . '' . $filename ) ) {
				return false;
			}

			require WPKOI_ELEMENTS_LITE_PATH . '' . $filename ;

			return true;
		}

		/**
		 * Register addon by file name
		 */
		public function register_addon( $file, $widgets_manager ) {

			$base  = basename( str_replace( '.php', '', $file ) );
			$class = ucwords( str_replace( '-', ' ', $base ) );
			$class = str_replace( ' ', '_', $class );
			$class = sprintf( 'Elementor\%s', $class );

			require $file;

			if ( class_exists( $class ) ) {
				$widgets_manager->register( new $class );
			}
		}

		/**
		 * Returns the instance.
		 */
		public static function get_instance( $shortcodes = array() ) {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self( $shortcodes );
			}
			return self::$instance;
		}
	}

}

/**
 * Returns instance of WPKoi_Elements_Lite_Integration
 */
function wpkoi_elements_lite_integration() {
	return WPKoi_Elements_Lite_Integration::get_instance();
}
wpkoi_elements_lite_integration()->init();