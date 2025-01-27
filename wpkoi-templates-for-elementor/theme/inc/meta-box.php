<?php
/**
 * Builds our main Layout meta box.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_action( 'admin_enqueue_scripts', 'wpkoi_enqueue_meta_box_scripts' );
// Adds any scripts for this meta box.
function wpkoi_enqueue_meta_box_scripts( $hook ) {
	if ( in_array( $hook, array( 'post.php', 'post-new.php' ) ) ) {
		$post_types = get_post_types( array( 'public' => true ) );
		$screen = get_current_screen();
		$post_type = $screen->id;

		if ( in_array( $post_type, ( array ) $post_types ) ) {
			wp_enqueue_style( 'wpkoi-layout-metabox', WPKOI_TEMPLATES_FOR_ELEMENTOR_URL . 'theme/css/meta-box.css', array(), WPKOI_TEMPLATES_FOR_ELEMENTOR_VERSION );
		}
	}
}

add_action( 'add_meta_boxes', 'wpkoi_register_layout_meta_box' );
// Generate the layout metabox
function wpkoi_register_layout_meta_box() {
	if ( ! current_user_can( apply_filters( 'wpkoi_metabox_capability', 'edit_theme_options' ) ) ) {
		return;
	}

	if ( ! defined( 'WPKOI_LAYOUT_META_BOX' ) ) {
		define( 'WPKOI_LAYOUT_META_BOX', true );
	}

	$post_types = get_post_types( array( 'public' => true ) );
	foreach ($post_types as $type) {
		add_meta_box (
			'wpkoi_layout_options_meta_box',
			esc_html__( 'Page options', 'wpkoi-templates-for-elementor' ),
			'wpkoi_do_layout_meta_box',
			$type,
			'advanced',
			'high'
		);
	}
}

// Build our meta box.
function wpkoi_do_layout_meta_box( $post ) {
	wp_nonce_field( basename( __FILE__ ), 'wpkoi_layout_nonce' );
	$stored_meta = (array) get_post_meta( $post->ID );
	$stored_meta['_wpkoi-transparent-header'][0] = ( isset( $stored_meta['_wpkoi-transparent-header'][0] ) ) ? $stored_meta['_wpkoi-transparent-header'][0] : '';

	$tabs = apply_filters( 'wpkoi_metabox_tabs',
		array(
			'disable_elements' => array(
				'title' => esc_html__( 'Disable Elements', 'wpkoi-templates-for-elementor' ),
				'target' => '#wpkoi-layout-disable-elements',
				'class' => '',
			),
			'transparent_header' => array(
				'title' => esc_html__( 'Transparent Header', 'wpkoi-templates-for-elementor' ),
				'target' => '#wpkoi-layout-transparent-header',
				'class' => 'current',
			),
		)
	);
	?>
	<script>
		jQuery(document).ready(function($) {
			$( '.wpkoi-meta-box-menu li a' ).on( 'click', function( event ) {
				event.preventDefault();
				$( this ).parent().addClass( 'current' );
				$( this ).parent().siblings().removeClass( 'current' );
				var tab = $( this ).attr( 'data-target' );

				// Page header module still using href.
				if ( ! tab ) {
					tab = $( this ).attr( 'href' );
				}

				$( '.wpkoi-meta-box-content' ).children( 'div' ).not( tab ).css( 'display', 'none' );
				$( tab ).fadeIn( 100 );
			});
		});
	</script>
	<div id="wpkoi-meta-box-container">
		<ul class="wpkoi-meta-box-menu">
			<?php
			foreach ( ( array ) $tabs as $tab => $data ) {
				echo '<li class="' . esc_attr( $data['class'] ) . '"><a data-target="' . esc_attr( $data['target'] ) . '" href="#">' . esc_html( $data['title'] ) . '</a></li>';
			}

			do_action( 'wpkoi_layout_meta_box_menu_item' );
			?>
		</ul>
		<div class="wpkoi-meta-box-content">
			
			
			<div id="wpkoi-layout-transparent-header">
				<h3 class="wpkoi-page-options">
					<?php esc_html_e( 'Switch to transparent header if You want to put content behind the header.', 'wpkoi-templates-for-elementor' ) ;?>
				</h3>

				<p class="wpkoi_transparent_header">
					<label for="default" style="display:block;margin-bottom:10px;">
						<input type="radio" name="_wpkoi-transparent-header" id="default" value="" <?php checked( $stored_meta['_wpkoi-transparent-header'][0], '' ); ?>>
						<?php esc_html_e( 'Default', 'wpkoi-templates-for-elementor' );?>
					</label>

					<label id="transparent" for="_wpkoi-transparent-header" style="display:block;margin-bottom:10px;">
						<input type="radio" name="_wpkoi-transparent-header" id="transparent" value="true" <?php checked( $stored_meta['_wpkoi-transparent-header'][0], 'true' ); ?>>
						<?php esc_html_e( 'Transparent', 'wpkoi-templates-for-elementor' );?>
					</label>
				</p>
			</div>
			<div id="wpkoi-layout-disable-elements" style="display: none;">
				<?php if ( ! defined( 'WPKOI_DE_VERSION' ) ) : ?>
					<div class="wpkoi_disable_elements">
						<h3 class="wpkoi-page-options">
							<a href="<?php echo esc_url( wpkoi_theme_uri_link() ); ?>" target="_blank"><?php esc_html_e( 'Premium module available', 'wpkoi-templates-for-elementor' ); ?></a>
						</h3>
						<p><?php esc_html_e( 'Get the premium version of the theme to hide the header or the footer from this page.', 'wpkoi-templates-for-elementor' ); ?></p>
					</div>
				<?php endif; ?>

				<?php do_action( 'wpkoi_layout_disable_elements_section', $stored_meta ); ?>
			</div>
			<?php do_action( 'wpkoi_layout_meta_box_content', $stored_meta ); ?>
		</div>
	</div>
    <?php
}

add_action( 'save_post', 'wpkoi_save_layout_meta_data' );
// Saves the meta data.
function wpkoi_save_layout_meta_data( $post_id ) {
	$is_autosave = wp_is_post_autosave( $post_id );
	$is_revision = wp_is_post_revision( $post_id );
	$is_valid_nonce = ( isset( $_POST[ 'wpkoi_layout_nonce' ] ) && wp_verify_nonce( sanitize_key( $_POST[ 'wpkoi_layout_nonce' ] ), basename( __FILE__ ) ) ) ? true : false;

	if ( $is_autosave || $is_revision || ! $is_valid_nonce ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return $post_id;
	}

	$transparent_header_key   = '_wpkoi-transparent-header';
	$transparent_header_value = filter_input( INPUT_POST, $transparent_header_key );

	if ( $transparent_header_value ) {
		update_post_meta( $post_id, $transparent_header_key, $transparent_header_value );
	} else {
		delete_post_meta( $post_id, $transparent_header_key );
	}

	do_action( 'wpkoi_layout_meta_box_save', $post_id );
}
