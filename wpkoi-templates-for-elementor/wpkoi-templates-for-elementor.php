<?php
/*
Plugin Name: WPKoi Templates for Elementor
Plugin URI: https://wpkoi.com/wpkoi-templates-for-elementor/
Description: WPKoi Templates for Elementor extends Elementor Template Library with WPKoi pages from the popular WPKoi Themes.
Version: 3.1.2
Author: WPKoi
Author URI: https://wpkoi.com
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html
Text Domain: wpkoi-templates-for-elementor
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Set our version
define( 'WPKOI_TEMPLATES_FOR_ELEMENTOR_VERSION', '3.1.2' );

// Set our root directory
define( 'WPKOI_TEMPLATES_FOR_ELEMENTOR_DIRECTORY', plugin_dir_path( __FILE__ ) );
define( 'WPKOI_TEMPLATES_FOR_ELEMENTOR_URL', plugins_url( '/', __FILE__ ) );
define( 'WPKOI_TEMPLATES_FOR_ELEMENTOR_WEB_URL', 'https://wpkoi.com/wpkoi-templates-for-elementor/' );


// Display admin error message if PHP version is older than 7.0.0.
if ( version_compare( phpversion(), '7.0.0', '<' ) ) {
    add_action( 'admin_notices', function() {
		/* translators: 1: current PHP version, 2: opening strong tag, 3: closing strong tag, 4: line break */
        $message = sprintf( esc_html__( 'The %2$sWPKoi Templates for Elementor%3$s plugins requires %2$sPHP 7.0.0+%3$s to run properly... Please contact your hosting company and ask them to update the PHP version of your site to at least PHP 7.0.0.%4$s Your current version of PHP: %2$s%1$s%3$s', 'wpkoi-templates-for-elementor' ), phpversion(), '<strong>', '</strong>', '<br>' );
        printf( '<div class="notice notice-error"><p>%1$s</p></div>', wp_kses_post( $message ) );
    });
    return;
}
if ( version_compare( phpversion(), '7.0.0', '<' ) ) {
	return;	
}

// Add additional links for the plugin at the plugins admin page
if ( ! function_exists( 'wpkoi_templates_for_elementor_action_links' ) ) {
	add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'wpkoi_templates_for_elementor_action_links' );
	
	function wpkoi_templates_for_elementor_action_links( $actions ) {
	$actions[] = '<a href="'. esc_url( get_admin_url(null, 'admin.php?page=wpkoi-templates-for-elementor%2Fwpkoi-templates.php') ) .'">Settings</a>';
	$actions[] = '<a href="' . WPKOI_TEMPLATES_FOR_ELEMENTOR_WEB_URL . '" target="_blank" style="color:#93003c;font-weight:700">Go Pro</a>';
	return $actions;
	}
}

//Checks to see if Premium plugin is active.
if ( ! function_exists( 'wpkoi_templates_for_elementor_active_premium' ) ) {
	add_action( 'admin_notices', 'wpkoi_templates_for_elementor_active_premium' );

	function wpkoi_templates_for_elementor_active_premium() {

		// Get the data
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		if ( is_plugin_active( 'wpkoi-templates-for-elementor-premium/wpkoi-templates-for-elementor-premium.php' ) )  {
			// Premium is active
			printf(
				'<div class="notice is-dismissible notice-warning">
					<p>%1$s</p>
				</div>',
				esc_html__( 'WPKoi Templates for Elementor Premium is active. You can deactivate the free version!', 'wpkoi-templates-for-elementor' )
			);
		}
	}
}

// Checks to see if Elementor plugin is active. If not, tell them.
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( is_plugin_active( 'wpkoi-templates-for-elementor-premium/wpkoi-templates-for-elementor-premium.php' ) )  {
	return;	
}

// Checks to see if Elementor plugin is active. If not, tell them.
if ( ! function_exists( 'wpkoi_templates_for_elementor_active_plugin' ) ) {
	add_action( 'admin_notices', 'wpkoi_templates_for_elementor_active_plugin' );

	function wpkoi_templates_for_elementor_active_plugin() {

		// Get the data
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		if ( !is_plugin_active( 'elementor/elementor.php' ) )  {
			if ( !is_plugin_active( 'elementor-pro/elementor-pro.php' ) )  {
				// Elementor is not active
				printf(
					'<div class="notice is-dismissible notice-warning">
						<p>%1$s <a href="https://wordpress.org/plugins/elementor/" target="_blank">%2$s</a></p>
					</div>',
					esc_html__( 'WPKoi Templates for Elementor requires Elementor Page Builder to be active.', 'wpkoi-templates-for-elementor' ),
					esc_html__( 'Install now.', 'wpkoi-templates-for-elementor' )
				);
			}
		}
	}
}

// Checks to see if Elementor plugin is active. If not, tell them.
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( !is_plugin_active( 'elementor/elementor.php' ) )  {
	if ( !is_plugin_active( 'elementor-pro/elementor-pro.php' ) )  {
		return;	
	}
}

if ( ! function_exists( 'wpkoi_templates_for_elementor_admin_add_scripts' ) ) {
	// Add script to Editor
	add_action( 'admin_enqueue_scripts', 'wpkoi_templates_for_elementor_admin_add_scripts');
	function wpkoi_templates_for_elementor_admin_add_scripts(){
		
		// Check if we are on the custom admin page
		$screen = get_current_screen();
		
		// Load styles and scripts only on the 'WPKoi Templates for Elementor' admin page
		if ( $screen->id === 'wpkoi-templates-for-elementor/wpkoi-templates' ) {
			wp_register_style( 'wpkoi-templates-for-elementor-css',  WPKOI_TEMPLATES_FOR_ELEMENTOR_URL . 'assets/css/wpkoi-templates-for-elementor.css', '', WPKOI_TEMPLATES_FOR_ELEMENTOR_VERSION );
			wp_enqueue_style( 'wpkoi-templates-for-elementor-css' );
		}

		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'wtfe-ajax-script', plugins_url( 'assets/js/import.js', __FILE__ ), array( 'jquery' ), WPKOI_TEMPLATES_FOR_ELEMENTOR_VERSION, true );

		// Add nonce for AJAX security
		$ajax_nonce = wp_create_nonce( 'wtfe_ajax_nonce' );
		wp_localize_script( 'wtfe-ajax-script', 'wtfe_ajax_obj', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'ajax_nonce' => $ajax_nonce,  // Pass nonce to JS
        	'nonce'    => wp_create_nonce( 'wtfe_save_action' ),
		));
	}
}

// Enqueue jquery to frontend
if ( ! function_exists( 'wpkoi_templates_for_elementor_scripts' ) ) {
	add_action( 'wp_enqueue_scripts', 'wpkoi_templates_for_elementor_scripts' );
	
	function wpkoi_templates_for_elementor_scripts() {
		wp_enqueue_script( 'jquery' );
	}
}

// Element options
require WPKOI_TEMPLATES_FOR_ELEMENTOR_DIRECTORY . 'inc/element-options.php';

// Create page in the admin
if ( ! function_exists( 'wpkoi_templates_for_elementor_create_menu' ) ) {
	add_action( 'admin_menu', 'wpkoi_templates_for_elementor_create_menu' );
	// Adds our WPKoi Templates for Elementor admin menu item
	function wpkoi_templates_for_elementor_create_menu() {
		add_menu_page( 'WPKoi Templates for Elementor', 'WPKoi Templates', 'manage_options', 'wpkoi-templates-for-elementor/wpkoi-templates.php', '', '', 59 );
	}
}

// Disable admin notices on the specific page
if ( ! function_exists( 'wpkoi_templates_for_elementor_disable_admin_notices' ) ) {

	add_action( 'admin_head', 'wpkoi_templates_for_elementor_disable_admin_notices' );
	function wpkoi_templates_for_elementor_disable_admin_notices() {
		$current_screen = get_current_screen();
		// Check if we are on the WPKoi Templates admin page
		if ( isset( $current_screen->id ) && $current_screen->id === 'wpkoi-templates-for-elementor/wpkoi-templates' ) {
			remove_all_actions( 'admin_notices' );
        	remove_all_actions( 'all_admin_notices' );
		}
	}
}

// Add WPKoi elements to page builder
add_action( 'plugins_loaded', 'wpkoi_templates_for_elementor_add_elements' );
function wpkoi_templates_for_elementor_add_elements() {
	if ( ( !defined('WPKOI_ELEMENTS_PATH' ) ) && ( ! function_exists( 'add_wpkoi_elements_elements' ) ) && ( ! function_exists( 'add_asagi_premium_elements' ) ) && ( ! function_exists( 'add_bekko_premium_elements' ) ) && ( ! function_exists( 'add_chagoi_premium_elements' ) ) && ( ! function_exists( 'add_lovewp_premium_elements' ) ) && ( ! function_exists( 'add_goshiki_premium_elements' ) ) && ( ! function_exists( 'add_ochiba_premium_elements' ) ) && ( ! function_exists( 'add_koromo_premium_elements' ) ) && ( ! function_exists( 'add_kohaku_premium_elements' ) ) ) {
		require WPKOI_TEMPLATES_FOR_ELEMENTOR_DIRECTORY . 'elements/elementor.php';
	}
}

// Hook to admin_notices to display the notification.
add_action('admin_notices', 'wpkoi_templates_review_notice');

function wpkoi_templates_review_notice() {
	
	// Get the remind me later time
    $remind_me_time = get_user_meta(get_current_user_id(), 'wpkoi_review_notice_remind_me_later', true);

    // Check if the user has dismissed the notice or has selected remind me later and it's still within the 48 hours
    if (get_user_meta(get_current_user_id(), 'wpkoi_review_dismissed', true) || 
        ($remind_me_time && $remind_me_time > time()) ||
        !get_user_meta(get_current_user_id(), 'wpkoi_show_review_notice', true)) {
        return;
    }

    // Your notice HTML with buttons for actions
    echo '<div id="wpkoi-review-notice" class="notice notice-success is-dismissible">
            <p>Enjoying <strong>WPKoi Templates for Elementor</strong>? Your feedback helps us grow and improve! If you\'re loving the plugin, we\'d be grateful for your positive review. It helps us build a better product for you and others!</p>
            <p>
                <button class="button button-primary" id="wpkoi-like">I like it!</button>
                <button class="button" id="wpkoi-dislike">I don\'t like it</button>
                <button class="button" id="wpkoi-dismiss">Dismiss</button>
                <button class="button" id="wpkoi-remind-me-later">Remind me later</button>
            </p>
          </div>';

    // Add JavaScript for handling button clicks and AJAX calls.
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('#wpkoi-like').click(function () {
                window.open('https://wordpress.org/support/plugin/wpkoi-templates-for-elementor/reviews/?rate=5#new-post', '_blank');
                sendWPKoiNoticeResponse('like');
            });

            $('#wpkoi-dislike').click(function () {
                window.open('https://wpkoi.com/help-us-with-your-feedback/', '_blank');
                sendWPKoiNoticeResponse('dislike');
            });

            $('#wpkoi-dismiss').click(function () {
                sendWPKoiNoticeResponse('dismiss');
            });

            $('#wpkoi-remind-me-later').click(function () {
                sendWPKoiNoticeResponse('remind_me_later');
            });

            function sendWPKoiNoticeResponse(action) {
                $.post(ajaxurl, {
                    action: 'wpkoi_handle_notice_response',
                    response: action
                });
                $('#wpkoi-review-notice').remove();
            }
        });
    </script>
	<style>#wpkoi-review-notice {padding: 10px;border-left: none;background: #222;color: #fff;}#wpkoi-review-notice button.button {margin-right: 8px;background: #222;border-radius: 0;border: 2px solid #fff;color: #fff;font-weight: 600;transition: all 0.2s ease-out;}#wpkoi-review-notice button.button:hover, #wpkoi-review-notice button.button-primary {background: #fff;color: #222;}</style>
    <?php
}

// Handle the AJAX response to store user choice.
add_action('wp_ajax_wpkoi_handle_notice_response', 'wpkoi_handle_notice_response');

function wpkoi_handle_notice_response() {
    $response = isset($_POST['response']) ? sanitize_text_field( wp_unslash( $_POST['response'] ) ) : '';

    if ($response === 'like' || $response === 'dislike' || $response === 'dismiss') {
        update_user_meta(get_current_user_id(), 'wpkoi_review_dismissed', true);
		delete_user_meta(get_current_user_id(), 'wpkoi_show_review_notice'); // Remove flag
    } elseif ($response === 'remind_me_later') {
        // Store the time for 48 hours later to show the notice again.
        update_user_meta(get_current_user_id(), 'wpkoi_review_notice_remind_me_later', time() + 48 * 3600);
    }

    wp_die(); // Stop execution after AJAX call.
}

// Check to show the notice after 48 hours if they clicked 'Remind me later'.
add_action('admin_init', 'wpkoi_check_remind_me_later');

function wpkoi_check_remind_me_later() {
    $remind_me_time = get_user_meta(get_current_user_id(), 'wpkoi_review_notice_remind_me_later', true);

    if ($remind_me_time && $remind_me_time <= time()) {
        delete_user_meta(get_current_user_id(), 'wpkoi_review_notice_remind_me_later');
    }
}
