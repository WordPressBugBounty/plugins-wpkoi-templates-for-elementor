<?php
/**
 * Class: WPKoi_Elements_Animated_Text
 * Name: Animated Text
 * Slug: wpkoi-animated-text
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WPKoi_Lite_Elements_Animated_Text extends Widget_Base {

	public function get_name() {
		return 'wpkoi-animated-text';
	}

	public function get_title() {
		return esc_html__( 'Animated Text', 'wpkoi-elements' );
	}

	public function get_icon() {
		return 'eicon-animated-headline';
	}

	public function get_categories() {
		return [ 'wpkoi-addons-for-elementor' ];
	}
	
	public function get_help_url() {
		return 'https://wpkoi.com/wpkoi-elementor-templates-demo/elements/animated-text/';
	}

	protected function register_controls() {

		$css_scheme = apply_filters(
			'wpkoi-elements/wpkoi-animated-text/css-scheme',
			array(
				'animated_text_instance' => '.wpkoi-animated-text',
				'before_text'            => '.wpkoi-animated-text__before-text',
				'animated_text'          => '.wpkoi-animated-text__animated-text',
				'animated_text_item'     => '.wpkoi-animated-text__animated-text-item',
				'after_text'             => '.wpkoi-animated-text__after-text',
			)
		);

		$this->start_controls_section(
			'section_general',
			array(
				'label' => esc_html__( 'Animated Text', 'wpkoi-elements' ),
			)
		);

		$this->add_control(
			'before_text_content',
			array(
				'label'   => esc_html__( 'Before Animation', 'wpkoi-elements' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Before', 'wpkoi-elements' ),
				'dynamic' => array( 'active' => true ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'item_text',
			array(
				'label'   => esc_html__( 'Text', 'wpkoi-elements' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Create', 'wpkoi-elements' ),
				'dynamic' => array( 'active' => true ),
			)
		);

		$this->add_control(
			'animated_text_list',
			array(
				'type'    => Controls_Manager::REPEATER,
				'label'   => esc_html__( 'Animated Text', 'wpkoi-elements' ),
				'fields'  => $repeater->get_controls(),
				'default' => array(
					array(
						'item_text' => esc_html__( 'First', 'wpkoi-elements' ),
					),
					array(
						'item_text' => esc_html__( 'Second', 'wpkoi-elements' ),
					),
				),
				'title_field' => '{{{ item_text }}}',
			)
		);

		$this->add_control(
			'after_text_content',
			array(
				'label'   => esc_html__( 'After Animation', 'wpkoi-elements' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'After', 'wpkoi-elements' ),
				'dynamic' => array( 'active' => true ),
			)
		);

		$this->add_control(
			'animation_effect',
			array(
				'label'   => esc_html__( 'Animation Type', 'wpkoi-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'fx1',
				'options' => array(
					'fx1'  => esc_html__( 'Joke', 'wpkoi-elements' ),
					'fx55' => esc_html__( 'WPKoi', 'wpkoi-elements' ),
					'fx2'  => esc_html__( 'Kinnect', 'wpkoi-elements' ),
					'fx3'  => esc_html__( 'Circus', 'wpkoi-elements' ),
					'fx4'  => esc_html__( 'Rotation fall', 'wpkoi-elements' ),
					'fx5'  => esc_html__( 'Simple Fall', 'wpkoi-elements' ),
					'fx6'  => esc_html__( 'Rotation', 'wpkoi-elements' ),
					'fx7'  => esc_html__( 'Anime', 'wpkoi-elements' ),
					'fx8'  => esc_html__( 'Label', 'wpkoi-elements' ),
					'fx9'  => esc_html__( 'Croco', 'wpkoi-elements' ),
					'fx10' => esc_html__( 'Scaling', 'wpkoi-elements' ),
					'fx11' => esc_html__( 'Fun', 'wpkoi-elements' ),
					'fx12' => esc_html__( 'Typing', 'wpkoi-elements' ),
				),
			)
		);

		$this->add_control(
			'animation_delay',
			array(
				'label'   => esc_html__( 'Animation delay', 'wpkoi-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 2000,
				'min'     => 500,
				'step'    => 100,
			)
		);

		$this->add_control(
			'split_type',
			array(
				'label'   => esc_html__( 'Split Type', 'wpkoi-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'symbol',
				'options' => array(
					'symbol' => esc_html__( 'Characters', 'wpkoi-elements' ),
					'word'   => esc_html__( 'Words', 'wpkoi-elements' ),
				),
				'condition' => array(
					'animation_effect!' => 'fx55',
				),
			)
		);

		$this->add_control(
			'animated_text_alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'wpkoi-elements' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => array(
					'flex-start'    => array(
						'title' => esc_html__( 'Left', 'wpkoi-elements' ),
						'icon'  => 'fas fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'wpkoi-elements' ),
						'icon'  => 'fas fa-align-center',
					),
					'flex-end' => array(
						'title' => esc_html__( 'Right', 'wpkoi-elements' ),
						'icon'  => 'fas fa-align-right',
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_scheme['animated_text_instance'] => 'justify-content: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_before_text_style',
			array(
				'label'      => esc_html__( 'Before Animation', 'wpkoi-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'before_text_color',
			array(
				'label' => esc_html__( 'Color', 'wpkoi-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['before_text'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'before_text_bg_color',
			array(
				'label' => esc_html__( 'Background color', 'wpkoi-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['before_text'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'before_text_typography',
				'label'    => esc_html__( 'Typography', 'wpkoi-elements' ),
				'selector' => '{{WRAPPER}} ' . $css_scheme['before_text'],
			)
		);

		$this->add_responsive_control(
			'before_text_padding',
			array(
				'label'      => esc_html__( 'Padding', 'wpkoi-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', 'rem', '%', 'vw' ),
				'selectors'  => array(
					'{{WRAPPER}} '  . $css_scheme['before_text'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_animated_text_style',
			array(
				'label'      => esc_html__( 'Animated Text', 'wpkoi-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'animated_text_color',
			array(
				'label' => esc_html__( 'Color', 'wpkoi-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['animated_text'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'animated_text_bg_color',
			array(
				'label' => esc_html__( 'Background color', 'wpkoi-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['animated_text'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'animated_text_cursor_color',
			array(
				'label' => esc_html__( 'Cursor Color', 'wpkoi-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['animated_text_item'] . ':after' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'animation_effect' => 'fx12',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'animated_text_typography',
				'label'    => esc_html__( 'Typography', 'wpkoi-elements' ),
				'selector' => '{{WRAPPER}} ' . $css_scheme['animated_text'],
			)
		);

		$this->add_responsive_control(
			'animated_text_padding',
			array(
				'label'      => esc_html__( 'Padding', 'wpkoi-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', 'rem', '%', 'vw' ),
				'selectors'  => array(
					'{{WRAPPER}} '  . $css_scheme['animated_text'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_after_text_style',
			array(
				'label'      => esc_html__( 'After Animation', 'wpkoi-elements' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'after_text_color',
			array(
				'label' => esc_html__( 'Color', 'wpkoi-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['after_text'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'after_text_bg_color',
			array(
				'label' => esc_html__( 'Background color', 'wpkoi-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_scheme['after_text'] => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'after_text_typography',
				'label'    => esc_html__( 'Typography', 'wpkoi-elements' ),
				'selector' => '{{WRAPPER}} ' . $css_scheme['after_text'],
			)
		);

		$this->add_responsive_control(
			'after_text_padding',
			array(
				'label'      => esc_html__( 'Padding', 'wpkoi-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', 'rem', '%', 'vw' ),
				'selectors'  => array(
					'{{WRAPPER}} '  . $css_scheme['after_text'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

	}

	/**
	 * Generate spenned html string
	 *
	 * @param  string $str Base text
	 * @return string
	 */
	public function str_to_spanned_html( $base_string, $split_type = 'symbol' ) {

		$module_settings = $this->get_settings_for_display();
		$symbols_array = array();
		$spanned_array = array();

		$base_words = explode( ' ', $base_string );

		if ( 'symbol' === $split_type ) {
			$glue = '';
			$symbols_array = $this->wpkoiat_string_split( $base_string );
		} else {
			$glue = ' ';
			$symbols_array = $base_words;
		}

		foreach ( $symbols_array as $symbol ) {

			if ( ' ' === $symbol ) {
				$symbol = '&nbsp;';
			}

			$spanned_array[] = sprintf( '<span>%s</span>', $symbol );
		}

		return implode( $glue, $spanned_array );
	}

	/**
	 * Split string
	 *
	 * @param  [type] $string [description]
	 * @return [type]         [description]
	 */
	public function wpkoiat_string_split( $string ) {

		$strlen = mb_strlen( $string );
		$result = array();

		while ( $strlen ) {

			$result[] = mb_substr( $string, 0, 1, "UTF-8" );
			$string   = mb_substr( $string, 1, $strlen, "UTF-8" );
			$strlen   = mb_strlen( $string );

		}

		return $result;
	}

	/**
	 * Generate setting json
	 *
	 * @return string
	 */
	public function generate_setting_json() {
		$module_settings = $this->get_settings_for_display();

		$settings = array(
			'effect' => $module_settings['animation_effect'],
			'delay'  => $module_settings['animation_delay'],
		);

		$settings = json_encode( $settings );

		return sprintf( 'data-settings=\'%1$s\'', $settings );
	}

	protected function render() {

		echo '<div class="elementor-wpkoi-animated-text wpkoi-elements">';
		$settings = $this->get_settings_for_display();
		$data_settings = $this->generate_setting_json();
		$classnumber = 0;
		
		$classes[] = 'wpkoi-animated-text';
		$classes[] = 'wpkoi-animated-text--effect-' . esc_attr( $settings['animation_effect'] );
		
		?>
		<div class="<?php echo implode( ' ', $classes ); ?>" <?php echo $data_settings; ?>>
            <div class="wpkoi-animated-text__before-text">
                <?php
                    echo $this->str_to_spanned_html( $settings['before_text_content'], 'word' ) . '&nbsp;';
                ?>
            </div>
            <div class="wpkoi-animated-text__animated-text">
				<?php 
				if ( 'fx55' === $settings['animation_effect'] ) {
				?>
				<div class="wpkoi-animated-text__animated-text-item active visible">
				<?php	
				foreach( $settings['animated_text_list'] as $animatetext ) :

					$module_settings = $this->get_settings_for_display();
					$symbols_array = array();
					$spanned_array = array();

					$base_words = array($animatetext['item_text']);
					$glue = ' ';
					$symbols_array = $base_words;

					foreach ( $symbols_array as $symbol ) {

						if ( ' ' === $symbol ) {
							$symbol = '&nbsp;';
						}
						
						$hidefirst = '';
						if ( 0 != $classnumber ) {
							$hidefirst = 'style="display: none;"';
						}

						$spanned_array[] = sprintf( '<span %s>%s</span>', $hidefirst, $symbol );
					}

					echo implode( $glue, $spanned_array );

					$animateclasses = array();
					$classnumber++;
				endforeach;
				?>
				</div>
				<?php
				} else {
				foreach( $settings['animated_text_list'] as $animatetext ) : ?>
					<?php 
                    $item_text = $animatetext['item_text'];
					$animateclasses[] = 'wpkoi-animated-text__animated-text-item';
					
					if ( 0 == $classnumber ) {
						$animateclasses[] = 'active';
						$animateclasses[] = 'visible';
					}
					
					$split_type = ( 'fx12' === $settings['animation_effect'] ) ? 'symbol' : $settings['split_type'];
					
					?>
					<div class="<?php echo implode( ' ', $animateclasses ); ?>">
						<?php
							echo $this->str_to_spanned_html( $item_text, $split_type );
							$animateclasses = array();
						?>
					</div>
                <?php $classnumber++;
				endforeach;
				} ?>
            </div>
			<div class="wpkoi-animated-text__after-text">
				<?php
                    echo '&nbsp;' . $this->str_to_spanned_html( $settings['after_text_content'], 'word' );
                ?>
            </div>
		</div>
		<?php
		echo '</div>';
		$classnumber = 0;
	}
	
	public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);

		wp_register_style('wpkoi-animated-text',WPKOI_ELEMENTS_LITE_URL . 'elements/animated-text/assets/animated-text.css',false,WPKOI_ELEMENTS_LITE_VERSION);
	}

	public function get_style_depends() {
		return [ 'wpkoi-animated-text' ];
	}
}

Plugin::instance()->widgets_manager->register( new WPKoi_Lite_Elements_Animated_Text() );