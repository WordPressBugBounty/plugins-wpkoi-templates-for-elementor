<?php
/**
 * Element options for admin.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_action('wp_ajax_wpkoi_templates_for_elementor_lite_wtfe_submit', 'wpkoi_templates_for_elementor_lite_wtfe_submit');
// Turn Elements off and on
function wpkoi_templates_for_elementor_lite_wtfe_submit() {
    // Verify nonce for security
    check_ajax_referer( 'wtfe_save_action', 'security' );

    // Check if the user has the right capability
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( array( 'message' => __( 'You do not have permission to perform this action.', 'wpkoi-templates-for-elementor' ) ) );
    }

    // Sanitize input values
    $wtfe_element_effects    = isset($_POST['wtfe_element_effects']) ? intval($_POST['wtfe_element_effects']) : 0;
    $wtfe_advanced_headings  = isset($_POST['wtfe_advanced_headings']) ? intval($_POST['wtfe_advanced_headings']) : 0;
    $wtfe_countdown          = isset($_POST['wtfe_countdown']) ? intval($_POST['wtfe_countdown']) : 0;
    $wtfe_darkmode           = isset($_POST['wtfe_darkmode']) ? intval($_POST['wtfe_darkmode']) : 0;
    $wtfe_scrolling_text     = isset($_POST['wtfe_scrolling_text']) ? intval($_POST['wtfe_scrolling_text']) : 0;
    $wtfe_qr_code            = isset($_POST['wtfe_qr_code']) ? intval($_POST['wtfe_qr_code']) : 0;

    // Update options in the database
    $update_effects = update_option( 'wtfe_element_effects', $wtfe_element_effects );
    $update_headings = update_option( 'wtfe_advanced_headings', $wtfe_advanced_headings );
    $update_countdown = update_option( 'wtfe_countdown', $wtfe_countdown );
    $update_darkmode = update_option( 'wtfe_darkmode', $wtfe_darkmode );
    $update_text = update_option( 'wtfe_scrolling_text', $wtfe_scrolling_text );
    $update_qr = update_option( 'wtfe_qr_code', $wtfe_qr_code );

    // Check if all options were updated correctly
    if ($update_effects || $update_headings || $update_countdown || $update_darkmode || $update_text || $update_qr) {
        wp_send_json_success( array( 'message' => __( 'Settings saved successfully.', 'wpkoi-templates-for-elementor' ) ) );
    } else {
        wp_send_json_error( array( 'message' => __( 'No changes were made or an error occurred.', 'wpkoi-templates-for-elementor' ) ) );
    }
}

// Import json file to Elementor page templates
if ( ! function_exists( 'wpkoi_templates_for_elementor_import_template' ) ) {
	function wpkoi_templates_for_elementor_import_template( $filepath ) {
		$fileContent = file_get_contents( $filepath );
		$fileJson = json_decode( $fileContent, true );

		$result = \Elementor\Plugin::instance()->templates_manager->import_template( [
				'fileData' => base64_encode( $fileContent ),
				'fileName' => 'test.json',
			]
		);

		if ( empty( $result ) || empty( $result[0] ) ) {
			return;
		}

		update_post_meta( $result[0]['template_id'], '_elementor_location', 'myCustomLocation' );
		update_post_meta( $result[0]['template_id'], '_elementor_conditions', [ 'include/general' ] );
		
		// Return the imported template ID so it can be used later
		return $result[0]['template_id'];
	}
}

if ( ! function_exists( 'wpkoi_templates_for_elementor_import_template_ajax_handler' ) ) {
	function wpkoi_templates_for_elementor_import_template_ajax_handler() {
		// Verify nonce and permissions
		check_ajax_referer( 'wtfe_ajax_nonce', 'security' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( 'Unauthorized access.', 'wpkoi-templates-for-elementor' ) );
		}

		// Ensure required parameters are set
		if ( ! isset( $_POST['template_id'] ) || ! isset( $_POST['template_title'] ) ) {
			wp_send_json_error( __( 'Missing required parameters.', 'wpkoi-templates-for-elementor' ) );
		}

		$template_id = sanitize_text_field( wp_unslash( $_POST['template_id'] ) );
		$template_title = sanitize_text_field( wp_unslash( $_POST['template_title'] ) );

		// Validate template URL
		$template_url = 'https://wpkoi.com/wet/json/' . esc_attr( $template_id ) . '.json';

		// Validate response
		$response = wp_remote_get( $template_url );
		if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) !== 200 ) {
			wp_send_json_error( __( 'Template URL validation failed.', 'wpkoi-templates-for-elementor' ) );
		}

		$imported_template_id = wpkoi_templates_for_elementor_import_template( $template_url );
		if ( ! $imported_template_id ) {
			wp_send_json_error( __( 'Template import failed.', 'wpkoi-templates-for-elementor' ) );
		}

		wp_send_json_success( array(
			'progress' => 1,
			'message'  => __( '1/3 Elementor template imported.', 'wpkoi-templates-for-elementor' ),
			'template_id' => $imported_template_id,
		));
	}
}

if ( ! function_exists( 'wpkoi_templates_for_elementor_create_page_ajax_handler' ) ) {
	function wpkoi_templates_for_elementor_create_page_ajax_handler() {

		check_ajax_referer( 'wtfe_ajax_nonce', 'security' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( 'Unauthorized access.', 'wpkoi-templates-for-elementor' ) );
		}

		if ( ! isset( $_POST['template_id'] ) || ! isset( $_POST['template_title'] ) ) {
			wp_send_json_error( __( 'Missing required parameters.', 'wpkoi-templates-for-elementor' ) );
		}

		$imported_template_id = intval( $_POST['template_id'] );
		$template_title = sanitize_text_field( wp_unslash( $_POST['template_title'] ) );

		// Part 2: Create new page
		$new_page = array(
			'post_type'    => 'page',
			'post_title'   => $template_title,
			'post_content' => get_post_field( 'post_content', $imported_template_id ),
			'post_status'  => 'publish',
			'post_author'  => get_current_user_id(),
		);
		$page_id = wp_insert_post( $new_page );

		if ( ! $page_id || is_wp_error( $page_id ) ) {
			wp_send_json_error( __( 'Failed to create a new page.', 'wpkoi-templates-for-elementor' ) );
		}

		// Send progress update: 2/3 complete
		wp_send_json_success( array(
			'progress' => 2,
			'message'  => __( '2/3 New page created.', 'wpkoi-templates-for-elementor' ),
			'page_id'  => $page_id,
		));
	}
}

if ( ! function_exists( 'wpkoi_templates_for_elementor_update_page_meta_ajax_handler' ) ) {
	function wpkoi_templates_for_elementor_update_page_meta_ajax_handler() {
		if ( ! isset( $_POST['page_id'] ) || ! isset( $_POST['template_id'] ) ) {
			wp_send_json_error( __( 'Missing required parameters.', 'wpkoi-templates-for-elementor' ) );
		}

		$page_id = intval( $_POST['page_id'] );
		$imported_template_id = intval( $_POST['template_id'] );

		// Part 3: Update page metas
		update_post_meta( $page_id, '_wp_page_template', 'elementor_header_footer' );
		update_post_meta( $page_id, '_elementor_edit_mode', 'builder' );
		update_post_meta( $page_id, '_elementor_template_type', 'wp-page' );
		update_post_meta( $page_id, '_elementor_version', ELEMENTOR_VERSION );

		// Copy Elementor settings, data, assets, and controls from the template to the page
		$settings = get_post_meta( $imported_template_id, '_elementor_page_settings', true );
		$data = json_decode(get_post_meta( $imported_template_id, '_elementor_data', true), true);
		$assets = get_post_meta( $imported_template_id, '_elementor_page_assets', true );
		$controls = get_post_meta( $imported_template_id, '_elementor_controls_usage', true );

		update_post_meta( $page_id, '_elementor_page_settings', $settings );
		update_post_meta( $page_id, '_elementor_data', $data );
		update_post_meta( $page_id, '_elementor_page_assets', $assets );
		update_post_meta( $page_id, '_elementor_controls_usage', $controls );

		// Set user meta flag to show review notification
		update_user_meta(get_current_user_id(), 'wpkoi_show_review_notice', true);

		// Get the URL of the new page
		$page_url = get_permalink( $page_id );

		// Send final success response with page URL
		wp_send_json_success( array(
			'message' => __( 'Congrats! The page is successfully imported!', 'wpkoi-templates-for-elementor' ),
			'page_url' => $page_url
		));
	}
}

add_action( 'wp_ajax_wtfe_import_template_ajax', 'wpkoi_templates_for_elementor_import_template_ajax_handler' );
add_action( 'wp_ajax_wtfe_create_page_ajax', 'wpkoi_templates_for_elementor_create_page_ajax_handler' );
add_action( 'wp_ajax_wtfe_update_page_meta_ajax', 'wpkoi_templates_for_elementor_update_page_meta_ajax_handler' );

// Svg outputs
if ( ! function_exists( 'wpkoi_templates_for_elementor_svg' ) ) {
	function wpkoi_templates_for_elementor_svg( $svg ) {
		$showsvg = '';
		
		if ($svg == 'eye') {
			$showsvg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/></svg>';
		}
		
		if ($svg == 'check') {
			$showsvg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/></svg>';
		}
		
		return $showsvg;
	}
}