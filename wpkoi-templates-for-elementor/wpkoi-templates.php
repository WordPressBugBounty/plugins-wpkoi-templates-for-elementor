<?php
/**
 * Builds our admin page.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Get the list of templates
require WPKOI_TEMPLATES_FOR_ELEMENTOR_DIRECTORY . 'inc/template-list.php';

?>
<header id="wet-sticky-header">
	<div class="wetpagelogo"><a href="<?php echo esc_url( WPKOI_TEMPLATES_FOR_ELEMENTOR_WEB_URL ); ?>" target="_blank"><h1><?php esc_html_e( 'WPKoi Templates', 'wpkoi-templates-for-elementor' );?><br><span><?php esc_html_e( 'for Elementor', 'wpkoi-templates-for-elementor' );?></span></h1></a></div>
	<button class="nav-btn" data-target="wet-page-templates"><?php esc_html_e( 'Templates', 'wpkoi-templates-for-elementor' );?></button>
	<button class="nav-btn" data-target="wet-page-features"><?php esc_html_e( 'Features', 'wpkoi-templates-for-elementor' );?></button>
	<button class="nav-btn" data-target="wet-page-info"><?php esc_html_e( 'How to use', 'wpkoi-templates-for-elementor' );?></button>
	<a href="<?php echo esc_url( WPKOI_TEMPLATES_FOR_ELEMENTOR_WEB_URL ); ?>premium-templates/" target="_blank" class="wet-sticky-header-p"><?php esc_html_e( 'Premium Templates', 'wpkoi-templates-for-elementor' );?></a>
	<div class="wetpage-rm">
		<div class="wetpage-social">
			<a target="_blank" href="https://wordpress.org/plugins/wpkoi-templates-for-elementor/"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M61.7 169.4l101.5 278C92.2 413 43.3 340.2 43.3 256c0-30.9 6.6-60.1 18.4-86.6zm337.9 75.9c0-26.3-9.4-44.5-17.5-58.7-10.8-17.5-20.9-32.4-20.9-49.9 0-19.6 14.8-37.8 35.7-37.8.9 0 1.8.1 2.8.2-37.9-34.7-88.3-55.9-143.7-55.9-74.3 0-139.7 38.1-177.8 95.9 5 .2 9.7.3 13.7.3 22.2 0 56.7-2.7 56.7-2.7 11.5-.7 12.8 16.2 1.4 17.5 0 0-11.5 1.3-24.3 2l77.5 230.4L249.8 247l-33.1-90.8c-11.5-.7-22.3-2-22.3-2-11.5-.7-10.1-18.2 1.3-17.5 0 0 35.1 2.7 56 2.7 22.2 0 56.7-2.7 56.7-2.7 11.5-.7 12.8 16.2 1.4 17.5 0 0-11.5 1.3-24.3 2l76.9 228.7 21.2-70.9c9-29.4 16-50.5 16-68.7zm-139.9 29.3l-63.8 185.5c19.1 5.6 39.2 8.7 60.1 8.7 24.8 0 48.5-4.3 70.6-12.1-.6-.9-1.1-1.9-1.5-2.9l-65.4-179.2zm183-120.7c.9 6.8 1.4 14 1.4 21.9 0 21.6-4 45.8-16.2 76.2l-65 187.9C426.2 403 468.7 334.5 468.7 256c0-37-9.4-71.8-26-102.1zM504 256c0 136.8-111.3 248-248 248C119.2 504 8 392.7 8 256 8 119.2 119.2 8 256 8c136.7 0 248 111.2 248 248zm-11.4 0c0-130.5-106.2-236.6-236.6-236.6C125.5 19.4 19.4 125.5 19.4 256S125.6 492.6 256 492.6c130.5 0 236.6-106.1 236.6-236.6z"></path></svg></a>
			<a target="_blank" href="https://www.facebook.com/wpkoithemes/"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M400 32H48A48 48 0 0 0 0 80v352a48 48 0 0 0 48 48h137.25V327.69h-63V256h63v-54.64c0-62.15 37-96.48 93.67-96.48 27.14 0 55.52 4.84 55.52 4.84v61h-31.27c-30.81 0-40.42 19.12-40.42 38.73V256h68.78l-11 71.69h-57.78V480H400a48 48 0 0 0 48-48V80a48 48 0 0 0-48-48z"></path></svg></a>
			<a target="_blank" href="https://dribbble.com/wpkoi"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 8C119.252 8 8 119.252 8 256s111.252 248 248 248 248-111.252 248-248S392.748 8 256 8zm163.97 114.366c29.503 36.046 47.369 81.957 47.835 131.955-6.984-1.477-77.018-15.682-147.502-6.818-5.752-14.041-11.181-26.393-18.617-41.614 78.321-31.977 113.818-77.482 118.284-83.523zM396.421 97.87c-3.81 5.427-35.697 48.286-111.021 76.519-34.712-63.776-73.185-116.168-79.04-124.008 67.176-16.193 137.966 1.27 190.061 47.489zm-230.48-33.25c5.585 7.659 43.438 60.116 78.537 122.509-99.087 26.313-186.36 25.934-195.834 25.809C62.38 147.205 106.678 92.573 165.941 64.62zM44.17 256.323c0-2.166.043-4.322.108-6.473 9.268.19 111.92 1.513 217.706-30.146 6.064 11.868 11.857 23.915 17.174 35.949-76.599 21.575-146.194 83.527-180.531 142.306C64.794 360.405 44.17 310.73 44.17 256.323zm81.807 167.113c22.127-45.233 82.178-103.622 167.579-132.756 29.74 77.283 42.039 142.053 45.189 160.638-68.112 29.013-150.015 21.053-212.768-27.882zm248.38 8.489c-2.171-12.886-13.446-74.897-41.152-151.033 66.38-10.626 124.7 6.768 131.947 9.055-9.442 58.941-43.273 109.844-90.795 141.978z"></path></svg></a>
			<a target="_blank" href="https://www.behance.net/wpkoi"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M232 237.2c31.8-15.2 48.4-38.2 48.4-74 0-70.6-52.6-87.8-113.3-87.8H0v354.4h171.8c64.4 0 124.9-30.9 124.9-102.9 0-44.5-21.1-77.4-64.7-89.7zM77.9 135.9H151c28.1 0 53.4 7.9 53.4 40.5 0 30.1-19.7 42.2-47.5 42.2h-79v-82.7zm83.3 233.7H77.9V272h84.9c34.3 0 56 14.3 56 50.6 0 35.8-25.9 47-57.6 47zm358.5-240.7H376V94h143.7v34.9zM576 305.2c0-75.9-44.4-139.2-124.9-139.2-78.2 0-131.3 58.8-131.3 135.8 0 79.9 50.3 134.7 131.3 134.7 61.3 0 101-27.6 120.1-86.3H509c-6.7 21.9-34.3 33.5-55.7 33.5-41.3 0-63-24.2-63-65.3h185.1c.3-4.2.6-8.7.6-13.2zM390.4 274c2.3-33.7 24.7-54.8 58.5-54.8 35.4 0 53.2 20.8 56.2 54.8H390.4z"></path></svg></a>
		</div>
		<div class="wetpage-more"><a href="<?php echo esc_url( WPKOI_TEMPLATES_FOR_ELEMENTOR_WEB_URL ); ?>" target="_blank"><h3><?php esc_html_e( 'Get premium', 'wpkoi-templates-for-elementor' );?></h3></a></div>
	</div>
</header>

<div id="wet-page-body">
	<div id="wet-page-templates" style="display: block;">
		<div class="wet-templates-loop">
		<?php
		$templates = wpkoi_templates_for_elementor_template_list();

		if ( ! empty( $templates ) ) {
			
			$counter = 0;
			foreach ( $templates as $template_data ) {
				$scrimg = $template_data['thumbnail'];
				$scrimg =  WPKOI_TEMPLATES_FOR_ELEMENTOR_URL . 'assets/thumbnails/' . $scrimg;
				
				$checkpremium = $template_data['url'];

				// Check if the URL ends with '-free/'
				if (substr($checkpremium, -6) === '-free/') {
					// Replace '-free/' with '-home/'
					$checkpremium = str_replace('-free/', '-home/', $checkpremium);
				} else {
					// If it doesn't end with '-free/', set $checkpremium to 'novalue'
					$checkpremium = 'novalue';
				}
				
				$checktheme = $template_data['title'];

				// Remove the last word from the string
				$checktheme = preg_replace('/\s(Free|Home|About Us|Contact)$/i', '', $checktheme);

				// Convert the remaining string to lowercase
				$checktheme = strtolower($checktheme);

				// Check if the result equals 'mallana' and set to 'novalue' if true
				if ($checktheme === 'mallanna') {
					$checktheme = 'novalue';
				}
				
				$counter++;
		?>
			<div class="wetl-template">
				<div class="wpkoi-home-preview-effect"></div>
				<div class="wetl-template-inner">
					<div class="wpkoi-ptemp">
						<a href="<?php echo esc_url( $template_data['url'] ); ?>" target="_blank">
							<div class="wpkoi-home-preview">
								<img height="auto" src="<?php echo esc_url( $scrimg ); ?>" style="transition: top 3s ease-out 0s; top: 0px;">
							</div>
						</a>
						<div class="wpkoi-ptemp-main-title">
							<div class="wet-info-list">
								<h3><?php echo esc_html($template_data['title']); ?></h3>
								<?php if ( $checkpremium != 'novalue' ) { ?>
								<div class="wet-bubbleh">
									<a href="<?php echo esc_url( $checkpremium ); ?>" target="_blank" class="wet-info-svg-link"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"/></svg></a>
									<div class="wet-bubble-text"><?php esc_html_e( 'This template has a full premium version. View the full demo here.', 'wpkoi-templates-for-elementor' );?></div>
								</div>
								<?php } ?>
								<?php if ( $checktheme != 'novalue' && $counter > 6 ) { ?>
								<div class="wet-bubbleh">
									<a href="https://wpkoi.com/<?php echo esc_attr( $checktheme ); ?>-wpkoi-wordpress-theme/" target="_blank" class="wet-info-svg-link"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M264.5 5.2c14.9-6.9 32.1-6.9 47 0l218.6 101c8.5 3.9 13.9 12.4 13.9 21.8s-5.4 17.9-13.9 21.8l-218.6 101c-14.9 6.9-32.1 6.9-47 0L45.9 149.8C37.4 145.8 32 137.3 32 128s5.4-17.9 13.9-21.8L264.5 5.2zM476.9 209.6l53.2 24.6c8.5 3.9 13.9 12.4 13.9 21.8s-5.4 17.9-13.9 21.8l-218.6 101c-14.9 6.9-32.1 6.9-47 0L45.9 277.8C37.4 273.8 32 265.3 32 256s5.4-17.9 13.9-21.8l53.2-24.6 152 70.2c23.4 10.8 50.4 10.8 73.8 0l152-70.2zm-152 198.2l152-70.2 53.2 24.6c8.5 3.9 13.9 12.4 13.9 21.8s-5.4 17.9-13.9 21.8l-218.6 101c-14.9 6.9-32.1 6.9-47 0L45.9 405.8C37.4 401.8 32 393.3 32 384s5.4-17.9 13.9-21.8l53.2-24.6 152 70.2c23.4 10.8 50.4 10.8 73.8 0z"/></svg></a>
									<div class="wet-bubble-text"><?php esc_html_e( 'This template has a related WordPress theme. Use the theme for a better look.', 'wpkoi-templates-for-elementor' );?></div>
								</div>
								<?php } ?>
							</div>
							<a href="https://wpkoi.com/wet/json/<?php echo esc_html( $template_data['template_id'] ); ?>.json" target="_blank" class="wet-dl-json-btn"><?php esc_html_e( 'Download manually', 'wpkoi-templates-for-elementor' );?></a>
							<button class="wtfe-import-button" data-template-id="<?php echo esc_html( $template_data['template_id'] ); ?>" data-template-title="<?php echo esc_html( $template_data['title'] ); ?>"><?php esc_html_e( 'Import', 'wpkoi-templates-for-elementor' );?></button>
						</div>
						<div class="home-explore">
							<a href="<?php echo esc_url( $template_data['url'] ); ?>" target="_blank">
								<div class="home-explore-i"><h5 class="home-explore-t"><?php esc_html_e( 'Preview', 'wpkoi-templates-for-elementor' );?></h5></div>
							</a>
						</div>

					</div>
					<div class="clearfix"></div>
				</div>
			</div>
		<?php
			}
		}
		?>
		</div>
		<a href="<?php echo esc_url( WPKOI_TEMPLATES_FOR_ELEMENTOR_WEB_URL ); ?>" target="_blank" class="wtfe-more-templates"><?php esc_html_e( 'Check Premium Templates', 'wpkoi-templates-for-elementor' );?></a>
		<div id="wtfe-import-popup" style="display:none;">
			<div id="wtfe-popup-content">
				<h3><?php esc_html_e( 'Import Elementor Page Template', 'wpkoi-templates-for-elementor' ); ?></h3>
				<p id="wtfe-import-status"><?php esc_html_e( 'Click "Start Import" to begin. It will import the page template to Elementor templates and generates a new page with the template content.', 'wpkoi-templates-for-elementor' ); ?></p>

				<!-- New confirmation buttons -->
				<button id="wtfe-start-import"><?php esc_html_e( 'Start Import', 'wpkoi-templates-for-elementor' ); ?></button>
				<button id="wtfe-popup-close"><?php esc_html_e( 'Close', 'wpkoi-templates-for-elementor' ); ?></button>
			</div>
		</div>
	</div>
	
	<div id="wet-page-features">
	<?php
	// options for effects
	$wtfe_element_effects 		= get_option( 'wtfe_element_effects', '' );
	// options for elements
	$wtfe_advanced_headings 	= get_option( 'wtfe_advanced_headings', '' );
	$wtfe_countdown 			= get_option( 'wtfe_countdown', '' );
	$wtfe_darkmode			 	= get_option( 'wtfe_darkmode', '' );
	$wtfe_scrolling_text	 	= get_option( 'wtfe_scrolling_text', '' );
	$wtfe_qr_code 				= get_option( 'wtfe_qr_code', '' );  
		
	?>
		<div class="wet-element-col-flex">
			<div class="wpkoi-disable-elements wet-element-col-1">
			<div class="wet-sidebar-element">
			<?php if ( ( !defined('WPKOI_ELEMENTS_PATH' ) ) && ( ! function_exists( 'add_wpkoi_elements_elements' ) ) && ( ! function_exists( 'add_asagi_premium_elements' ) ) && ( ! function_exists( 'add_bekko_premium_elements' ) ) && ( ! function_exists( 'add_chagoi_premium_elements' ) ) && ( ! function_exists( 'add_lovewp_premium_elements' ) ) && ( ! function_exists( 'add_goshiki_premium_elements' ) ) && ( ! function_exists( 'add_ochiba_premium_elements' ) ) && ( ! function_exists( 'add_koromo_premium_elements' ) ) && ( ! function_exists( 'add_kohaku_premium_elements' ) ) ) { ?>
				<form id="wtfe-form" method="post">
				<?php wp_nonce_field( 'wtfe_save_action', 'wtfe_nonce_field' ); ?>
    
				<h3><?php esc_html_e( 'Switch Your unused effects off!', 'wpkoi-templates-for-elementor' ); ?></h3>
				<p class="wet-de-p"><?php esc_html_e( 'Here You can switch off the WPKoi Effects for Elementor builder if You don˙t want to use. These effects used for elements, sections or columns.', 'wpkoi-templates-for-elementor' ); ?></p>
				<div class="wet-de-h">
					<div class="wet-de-e">
						<label class="switch">
						  <input id="wtfe_element_effects" name="wtfe_element_effects" type="checkbox"<?php if ( $wtfe_element_effects == true ){ ?> checked<?php } ?> >
						  <span class="slider"></span>
						</label>
						<p><?php esc_html_e( 'Element Effects', 'wpkoi-templates-for-elementor' ); ?></p>
						<div class="wet-de-d">
							<a target="_blank" href="https://wpkoi.com/wpkoi-elementor-templates-demo/elements/free-element-effects/"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/></svg></a> 
							<div class="wet-de-di"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/></svg></div>
						</div>
					</div>
				</div>

				<h3 class="switch-margin-top"><?php esc_html_e( 'Switch Your unused elements off!', 'wpkoi-templates-for-elementor' ); ?></h3>
				<p class="wet-de-p"><?php esc_html_e( 'Here You can switch off the WPKoi Elements for Elementor builder if You don˙t want to use.', 'wpkoi-templates-for-elementor' ); ?></p>
				<div class="wet-de-h">	
					<div class="wet-de-e">
						<label class="switch">
						  <input id="wtfe_advanced_headings" name="wtfe_advanced_headings" type="checkbox"<?php if ( $wtfe_advanced_headings == true ){ ?> checked<?php } ?> >
						  <span class="slider"></span>
						</label>
						<p><?php esc_html_e( 'Advanced Headings', 'wpkoi-templates-for-elementor' ); ?></p>
						<div class="wet-de-d">
							<a target="_blank" href="https://wpkoi.com/wpkoi-elementor-templates-demo/elements/advanced-heading/"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/></svg></a> 
							<div class="wet-de-di"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/></svg></div>
						</div>
					</div>
					<div class="wet-de-e">
						<label class="switch">
						  <input id="wtfe_countdown" name="wtfe_countdown" type="checkbox"<?php if ( $wtfe_countdown == true ){ ?> checked<?php } ?> >
						  <span class="slider"></span>
						</label>
						<p><?php esc_html_e( 'Countdown', 'wpkoi-templates-for-elementor' ); ?></p>
						<div class="wet-de-d">
							<a target="_blank" href="https://wpkoi.com/wpkoi-elementor-templates-demo/elements/countdown/"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/></svg></a> 
							<div class="wet-de-di"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/></svg></div>
						</div>
					</div>
					<div class="wet-de-e">
						<label class="switch">
						  <input id="wtfe_darkmode" name="wtfe_darkmode" type="checkbox"<?php if ( $wtfe_darkmode == true ){ ?> checked<?php } ?> >
						  <span class="slider"></span>
						</label>
						<p><?php esc_html_e( 'Darkmode', 'wpkoi-templates-for-elementor' ); ?></p>
						<div class="wet-de-d">
							<a target="_blank" href="https://wpkoi.com/wpkoi-elementor-templates-demo/elements/darkmode/"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/></svg></a> 
							<div class="wet-de-di"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/></svg></div>
						</div>
					</div>
					<div class="wet-de-e">
						<label class="switch">
						  <input id="wtfe_qr_code" name="wtfe_qr_code" type="checkbox"<?php if ( $wtfe_qr_code == true ){ ?> checked<?php } ?> >
						  <span class="slider"></span>
						</label>
						<p><?php esc_html_e( 'QR Code', 'wpkoi-templates-for-elementor' ); ?></p>
						<div class="wet-de-d">
							<a target="_blank" href="https://wpkoi.com/wpkoi-elementor-templates-demo/elements/qr-code/"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/></svg></a> 
							<div class="wet-de-di"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/></svg></div>
						</div>
					</div>
					<div class="wet-de-e">
						<label class="switch">
						  <input id="wtfe_scrolling_text" name="wtfe_scrolling_text" type="checkbox"<?php if ( $wtfe_scrolling_text == true ){ ?> checked<?php } ?> >
						  <span class="slider"></span>
						</label>
						<p><?php esc_html_e( 'Scrolling Text', 'wpkoi-templates-for-elementor' ); ?></p>
						<div class="wet-de-d">
							<div class="wet-de-di"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/></svg></div>
						</div>
					</div>
					
					<div class="wet-de-e wet-de-e-premium">
						<label class="switch">
							<a href="<?php echo esc_url( WPKOI_TEMPLATES_FOR_ELEMENTOR_WEB_URL ); ?>" target="_blank" class="wet-switch-link">
								<span class="slider"></span>
							</a>
						</label>
						<p><?php esc_html_e( 'Advanced Accordion', 'wpkoi-templates-for-elementor' ); ?></p>
						<div class="wet-de-d">
							<a target="_blank" href="https://wpkoi.com/wpkoi-elementor-templates-demo/elements/advanced-accordion/"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/></svg></a> 
							<div class="wet-de-di"><a href="<?php echo esc_url( WPKOI_TEMPLATES_FOR_ELEMENTOR_WEB_URL ); ?>" target="_blank"><?php esc_html_e( 'Premium', 'wpkoi-templates-for-elementor' ); ?></a></div>
						</div>
					</div>
					<div class="wet-de-e wet-de-e-premium">
						<label class="switch">
							<a href="<?php echo esc_url( WPKOI_TEMPLATES_FOR_ELEMENTOR_WEB_URL ); ?>" target="_blank" class="wet-switch-link">
								<span class="slider"></span>
							</a>
						</label>
						<p><?php esc_html_e( 'Advanced Tabs', 'wpkoi-templates-for-elementor' ); ?></p>
						<div class="wet-de-d">
							<a target="_blank" href="https://wpkoi.com/wpkoi-elementor-templates-demo/elements/advanced-tabs/"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/></svg></a> 
							<div class="wet-de-di"><a href="<?php echo esc_url( WPKOI_TEMPLATES_FOR_ELEMENTOR_WEB_URL ); ?>" target="_blank"><?php esc_html_e( 'Premium', 'wpkoi-templates-for-elementor' ); ?></a></div>
						</div>
					</div>
					<div class="wet-de-e wet-de-e-premium">
						<label class="switch">
							<a href="<?php echo esc_url( WPKOI_TEMPLATES_FOR_ELEMENTOR_WEB_URL ); ?>" target="_blank" class="wet-switch-link">
								<span class="slider"></span>
							</a>
						</label>
						<p><?php esc_html_e( 'Animated Text', 'wpkoi-templates-for-elementor' ); ?></p>
						<div class="wet-de-d">
							<a target="_blank" href="https://wpkoi.com/wpkoi-elementor-templates-demo/elements/animated-text/"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/></svg></a> 
							<div class="wet-de-di"><a href="<?php echo esc_url( WPKOI_TEMPLATES_FOR_ELEMENTOR_WEB_URL ); ?>" target="_blank"><?php esc_html_e( 'Premium', 'wpkoi-templates-for-elementor' ); ?></a></div>
						</div>
					</div>
					<div class="wet-de-e wet-de-e-premium">
						<label class="switch">
							<a href="<?php echo esc_url( WPKOI_TEMPLATES_FOR_ELEMENTOR_WEB_URL ); ?>" target="_blank" class="wet-switch-link">
								<span class="slider"></span>
							</a>
						</label>
						<p><?php esc_html_e( 'Button', 'wpkoi-templates-for-elementor' ); ?></p>
						<div class="wet-de-d">
							<a target="_blank" href="https://wpkoi.com/wpkoi-elementor-templates-demo/elements/button/"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/></svg></a> 
							<div class="wet-de-di"><a href="<?php echo esc_url( WPKOI_TEMPLATES_FOR_ELEMENTOR_WEB_URL ); ?>" target="_blank"><?php esc_html_e( 'Premium', 'wpkoi-templates-for-elementor' ); ?></a></div>
						</div>
					</div>
					<div class="wet-de-e wet-de-e-premium">
						<label class="switch">
							<a href="<?php echo esc_url( WPKOI_TEMPLATES_FOR_ELEMENTOR_WEB_URL ); ?>" target="_blank" class="wet-switch-link">
								<span class="slider"></span>
							</a>
						</label>
						<p><?php esc_html_e( 'Call To Action', 'wpkoi-templates-for-elementor' ); ?></p>
						<div class="wet-de-d">
							<a target="_blank" href="https://wpkoi.com/wpkoi-elementor-templates-demo/elements/call-to-action/"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/></svg></a> 
							<div class="wet-de-di"><a href="<?php echo esc_url( WPKOI_TEMPLATES_FOR_ELEMENTOR_WEB_URL ); ?>" target="_blank"><?php esc_html_e( 'Premium', 'wpkoi-templates-for-elementor' ); ?></a></div>
						</div>
					</div>
					<div class="wet-de-e wet-de-e-premium">
						<label class="switch">
							<a href="<?php echo esc_url( WPKOI_TEMPLATES_FOR_ELEMENTOR_WEB_URL ); ?>" target="_blank" class="wet-switch-link">
								<span class="slider"></span>
							</a>
						</label>
						<p><?php esc_html_e( 'Circle Progress', 'wpkoi-templates-for-elementor' ); ?></p>
						<div class="wet-de-d">
							<a target="_blank" href="https://wpkoi.com/wpkoi-elementor-templates-demo/elements/circle-progress/"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/></svg></a> 
							<div class="wet-de-di"><a href="<?php echo esc_url( WPKOI_TEMPLATES_FOR_ELEMENTOR_WEB_URL ); ?>" target="_blank"><?php esc_html_e( 'Premium', 'wpkoi-templates-for-elementor' ); ?></a></div>
						</div>
					</div>
					<div class="wet-de-e wet-de-e-premium">
						<label class="switch">
							<a href="<?php echo esc_url( WPKOI_TEMPLATES_FOR_ELEMENTOR_WEB_URL ); ?>" target="_blank" class="wet-switch-link">
								<span class="slider"></span>
							</a>
						</label>
						<p><?php esc_html_e( 'Content Ticker', 'wpkoi-templates-for-elementor' ); ?></p>
						<div class="wet-de-d">
							<a target="_blank" href="https://wpkoi.com/wpkoi-elementor-templates-demo/elements/content-ticker/"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/></svg></a> 
							<div class="wet-de-di"><a href="<?php echo esc_url( WPKOI_TEMPLATES_FOR_ELEMENTOR_WEB_URL ); ?>" target="_blank"><?php esc_html_e( 'Premium', 'wpkoi-templates-for-elementor' ); ?></a></div>
						</div>
					</div>
					<div class="wet-de-e wet-de-e-premium">
						<label class="switch">
							<a href="<?php echo esc_url( WPKOI_TEMPLATES_FOR_ELEMENTOR_WEB_URL ); ?>" target="_blank" class="wet-switch-link">
								<span class="slider"></span>
							</a>
						</label>
						<p><?php esc_html_e( 'Data Table', 'wpkoi-templates-for-elementor' ); ?></p>
						<div class="wet-de-d">
							<a target="_blank" href="https://wpkoi.com/wpkoi-elementor-templates-demo/elements/data-table/"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/></svg></a> 
							<div class="wet-de-di"><a href="<?php echo esc_url( WPKOI_TEMPLATES_FOR_ELEMENTOR_WEB_URL ); ?>" target="_blank"><?php esc_html_e( 'Premium', 'wpkoi-templates-for-elementor' ); ?></a></div>
						</div>
					</div>
					<div class="wet-de-e wet-de-e-premium">
						<label class="switch">
							<a href="<?php echo esc_url( WPKOI_TEMPLATES_FOR_ELEMENTOR_WEB_URL ); ?>" target="_blank" class="wet-switch-link">
								<span class="slider"></span>
							</a>
						</label>
						<p><?php esc_html_e( 'Distorted Headings', 'wpkoi-templates-for-elementor' ); ?></p>
						<div class="wet-de-d">
							<div class="wet-de-di wet-de-dini"><a href="<?php echo esc_url( WPKOI_TEMPLATES_FOR_ELEMENTOR_WEB_URL ); ?>" target="_blank"><?php esc_html_e( 'Premium', 'wpkoi-templates-for-elementor' ); ?></a></div>
						</div>
					</div>
					<div class="wet-de-e wet-de-e-premium">
						<label class="switch">
							<a href="<?php echo esc_url( WPKOI_TEMPLATES_FOR_ELEMENTOR_WEB_URL ); ?>" target="_blank" class="wet-switch-link">
								<span class="slider"></span>
							</a>
						</label>
						<p><?php esc_html_e( 'Filterable Gallery', 'wpkoi-templates-for-elementor' ); ?></p>
						<div class="wet-de-d">
							<a target="_blank" href="https://wpkoi.com/wpkoi-elementor-templates-demo/elements/filterable-gallery/"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/></svg></a> 
							<div class="wet-de-di"><a href="<?php echo esc_url( WPKOI_TEMPLATES_FOR_ELEMENTOR_WEB_URL ); ?>" target="_blank"><?php esc_html_e( 'Premium', 'wpkoi-templates-for-elementor' ); ?></a></div>
						</div>
					</div>
					<div class="wet-de-e wet-de-e-premium">
						<label class="switch">
							<a href="<?php echo esc_url( WPKOI_TEMPLATES_FOR_ELEMENTOR_WEB_URL ); ?>" target="_blank" class="wet-switch-link">
								<span class="slider"></span>
							</a>
						</label>
						<p><?php esc_html_e( 'Flip Box', 'wpkoi-templates-for-elementor' ); ?></p>
						<div class="wet-de-d">
							<a target="_blank" href="https://wpkoi.com/wpkoi-elementor-templates-demo/elements/flip-box/"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/></svg></a> 
							<div class="wet-de-di"><a href="<?php echo esc_url( WPKOI_TEMPLATES_FOR_ELEMENTOR_WEB_URL ); ?>" target="_blank"><?php esc_html_e( 'Premium', 'wpkoi-templates-for-elementor' ); ?></a></div>
						</div>
					</div>
					<div class="wet-de-e wet-de-e-premium">
						<label class="switch">
							<a href="<?php echo esc_url( WPKOI_TEMPLATES_FOR_ELEMENTOR_WEB_URL ); ?>" target="_blank" class="wet-switch-link">
								<span class="slider"></span>
							</a>
						</label>
						<p><?php esc_html_e( 'Hotspots', 'wpkoi-templates-for-elementor' ); ?></p>
						<div class="wet-de-d">
							<a target="_blank" href="https://wpkoi.com/wpkoi-elementor-templates-demo/elements/hotspots/"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/></svg></a> 
							<div class="wet-de-di"><a href="<?php echo esc_url( WPKOI_TEMPLATES_FOR_ELEMENTOR_WEB_URL ); ?>" target="_blank"><?php esc_html_e( 'Premium', 'wpkoi-templates-for-elementor' ); ?></a></div>
						</div>
					</div>
					<div class="wet-de-e wet-de-e-premium">
						<label class="switch">
							<a href="<?php echo esc_url( WPKOI_TEMPLATES_FOR_ELEMENTOR_WEB_URL ); ?>" target="_blank" class="wet-switch-link">
								<span class="slider"></span>
							</a>
						</label>
						<p><?php esc_html_e( 'Image Accordion', 'wpkoi-templates-for-elementor' ); ?></p>
						<div class="wet-de-d">
							<a target="_blank" href="https://wpkoi.com/wpkoi-elementor-templates-demo/elements/image-accordion/"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/></svg></a> 
							<div class="wet-de-di"><a href="<?php echo esc_url( WPKOI_TEMPLATES_FOR_ELEMENTOR_WEB_URL ); ?>" target="_blank"><?php esc_html_e( 'Premium', 'wpkoi-templates-for-elementor' ); ?></a></div>
						</div>
					</div>
					<div class="wet-de-e wet-de-e-premium">
						<label class="switch">
							<a href="<?php echo esc_url( WPKOI_TEMPLATES_FOR_ELEMENTOR_WEB_URL ); ?>" target="_blank" class="wet-switch-link">
								<span class="slider"></span>
							</a>
						</label>
						<p><?php esc_html_e( 'Image Comparison', 'wpkoi-templates-for-elementor' ); ?></p>
						<div class="wet-de-d">
							<a target="_blank" href="https://wpkoi.com/wpkoi-elementor-templates-demo/elements/image-comparison/"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/></svg></a> 
							<div class="wet-de-di"><a href="<?php echo esc_url( WPKOI_TEMPLATES_FOR_ELEMENTOR_WEB_URL ); ?>" target="_blank"><?php esc_html_e( 'Premium', 'wpkoi-templates-for-elementor' ); ?></a></div>
						</div>
					</div>
					<div class="wet-de-e wet-de-e-premium">
						<label class="switch">
							<a href="<?php echo esc_url( WPKOI_TEMPLATES_FOR_ELEMENTOR_WEB_URL ); ?>" target="_blank" class="wet-switch-link">
								<span class="slider"></span>
							</a>
						</label>
						<p><?php esc_html_e( 'Post Grid', 'wpkoi-templates-for-elementor' ); ?></p>
						<div class="wet-de-d">
							<div class="wet-de-di wet-de-dini"><a href="<?php echo esc_url( WPKOI_TEMPLATES_FOR_ELEMENTOR_WEB_URL ); ?>" target="_blank"><?php esc_html_e( 'Premium', 'wpkoi-templates-for-elementor' ); ?></a></div>
						</div>
					</div>
					<div class="wet-de-e wet-de-e-premium">
						<label class="switch">
							<a href="<?php echo esc_url( WPKOI_TEMPLATES_FOR_ELEMENTOR_WEB_URL ); ?>" target="_blank" class="wet-switch-link">
								<span class="slider"></span>
							</a>
						</label>
						<p><?php esc_html_e( 'Post Timeline', 'wpkoi-templates-for-elementor' ); ?></p>
						<div class="wet-de-d">
							<div class="wet-de-di wet-de-dini"><a href="<?php echo esc_url( WPKOI_TEMPLATES_FOR_ELEMENTOR_WEB_URL ); ?>" target="_blank"><?php esc_html_e( 'Premium', 'wpkoi-templates-for-elementor' ); ?></a></div>
						</div>
					</div>
					<div class="wet-de-e wet-de-e-premium">
						<label class="switch">
							<a href="<?php echo esc_url( WPKOI_TEMPLATES_FOR_ELEMENTOR_WEB_URL ); ?>" target="_blank" class="wet-switch-link">
								<span class="slider"></span>
							</a>
						</label>
						<p><?php esc_html_e( 'Pricing Table', 'wpkoi-templates-for-elementor' ); ?></p>
						<div class="wet-de-d">
							<a target="_blank" href="https://wpkoi.com/wpkoi-elementor-templates-demo/elements/pricing-table/"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/></svg></a> 
							<div class="wet-de-di"><a href="<?php echo esc_url( WPKOI_TEMPLATES_FOR_ELEMENTOR_WEB_URL ); ?>" target="_blank"><?php esc_html_e( 'Premium', 'wpkoi-templates-for-elementor' ); ?></a></div>
						</div>
					</div>
					<div class="wet-de-e wet-de-e-premium">
						<label class="switch">
							<a href="<?php echo esc_url( WPKOI_TEMPLATES_FOR_ELEMENTOR_WEB_URL ); ?>" target="_blank" class="wet-switch-link">
								<span class="slider"></span>
							</a>
						</label>
						<p><?php esc_html_e( 'Product Grid', 'wpkoi-templates-for-elementor' ); ?></p>
						<div class="wet-de-d">
							<div class="wet-de-di wet-de-dini"><a href="<?php echo esc_url( WPKOI_TEMPLATES_FOR_ELEMENTOR_WEB_URL ); ?>" target="_blank"><?php esc_html_e( 'Premium', 'wpkoi-templates-for-elementor' ); ?></a></div>
						</div>
					</div>
					<div class="wet-de-e wet-de-e-premium">
						<label class="switch">
							<a href="<?php echo esc_url( WPKOI_TEMPLATES_FOR_ELEMENTOR_WEB_URL ); ?>" target="_blank" class="wet-switch-link">
								<span class="slider"></span>
							</a>
						</label>
						<p><?php esc_html_e( 'Scroll Navigation', 'wpkoi-templates-for-elementor' ); ?></p>
						<div class="wet-de-d">
							<div class="wet-de-di wet-de-dini"><a href="<?php echo esc_url( WPKOI_TEMPLATES_FOR_ELEMENTOR_WEB_URL ); ?>" target="_blank"><?php esc_html_e( 'Premium', 'wpkoi-templates-for-elementor' ); ?></a></div>
						</div>
					</div>
					<div class="wet-de-e wet-de-e-premium">
						<label class="switch">
							<a href="<?php echo esc_url( WPKOI_TEMPLATES_FOR_ELEMENTOR_WEB_URL ); ?>" target="_blank" class="wet-switch-link">
								<span class="slider"></span>
							</a>
						</label>
						<p><?php esc_html_e( 'Team Member', 'wpkoi-templates-for-elementor' ); ?></p>
						<div class="wet-de-d">
							<a target="_blank" href="https://wpkoi.com/wpkoi-elementor-templates-demo/elements/team-member/"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/></svg></a> 
							<div class="wet-de-di"><a href="<?php echo esc_url( WPKOI_TEMPLATES_FOR_ELEMENTOR_WEB_URL ); ?>" target="_blank"><?php esc_html_e( 'Premium', 'wpkoi-templates-for-elementor' ); ?></a></div>
						</div>
					</div>
					<div class="wet-de-e wet-de-e-premium">
						<label class="switch">
							<a href="<?php echo esc_url( WPKOI_TEMPLATES_FOR_ELEMENTOR_WEB_URL ); ?>" target="_blank" class="wet-switch-link">
								<span class="slider"></span>
							</a>
						</label>
						<p><?php esc_html_e( 'Testimonial', 'wpkoi-templates-for-elementor' ); ?></p>
						<div class="wet-de-d">
							<a target="_blank" href="https://wpkoi.com/wpkoi-elementor-templates-demo/elements/testimonial/"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/></svg></a> 
							<div class="wet-de-di"><a href="<?php echo esc_url( WPKOI_TEMPLATES_FOR_ELEMENTOR_WEB_URL ); ?>" target="_blank"><?php esc_html_e( 'Premium', 'wpkoi-templates-for-elementor' ); ?></a></div>
						</div>
					</div>
					<div class="wet-de-e wet-de-e-premium">
						<label class="switch">
							<a href="<?php echo esc_url( WPKOI_TEMPLATES_FOR_ELEMENTOR_WEB_URL ); ?>" target="_blank" class="wet-switch-link">
								<span class="slider"></span>
							</a>
						</label>
						<p><?php esc_html_e( 'Unfold', 'wpkoi-templates-for-elementor' ); ?></p>
						<div class="wet-de-d">
							<a target="_blank" href="https://wpkoi.com/wpkoi-elementor-templates-demo/elements/unfold/"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/></svg></a> 
							<div class="wet-de-di"><a href="<?php echo esc_url( WPKOI_TEMPLATES_FOR_ELEMENTOR_WEB_URL ); ?>" target="_blank"><?php esc_html_e( 'Premium', 'wpkoi-templates-for-elementor' ); ?></a></div>
						</div>
					</div>
					<div class="wet-de-e wet-de-e-premium">
						<label class="switch">
							<a href="<?php echo esc_url( WPKOI_TEMPLATES_FOR_ELEMENTOR_WEB_URL ); ?>" target="_blank" class="wet-switch-link">
								<span class="slider"></span>
							</a>
						</label>
						<p><?php esc_html_e( 'View More', 'wpkoi-templates-for-elementor' ); ?></p>
						<div class="wet-de-d">
							<a target="_blank" href="https://wpkoi.com/wpkoi-elementor-templates-demo/elements/view-more/"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/></svg></a> 
							<div class="wet-de-di"><a href="<?php echo esc_url( WPKOI_TEMPLATES_FOR_ELEMENTOR_WEB_URL ); ?>" target="_blank"><?php esc_html_e( 'Premium', 'wpkoi-templates-for-elementor' ); ?></a></div>
						</div>
					</div>
				</div>
				<input type="button" class="button button-primary" id="wtfe-submit" name="wtfe_submit" value="<?php esc_html_e( 'Save settings', 'wpkoi-templates-for-elementor' );?>" />
				<div id="wtfe-response-message"></div>
				</form>
			<?php } else { ?>
				<h3><?php esc_html_e( 'It looks like you\'re using WPKoi Elements with another WPKoi product!', 'wpkoi-templates-for-elementor' ); ?></h3>
				<p class="wet-de-p"><?php 
				/* translators: 1: opening strong tag, 2: closing strong tag */
				printf( esc_html__( 'We’ve detected that you\'re using a different version of WPKoi Elements. If you\'re using a premium WPKoi theme, you can manage the element settings through the %1$sWPKoi Elements admin menu%2$s.', 'wpkoi-templates-for-elementor' ), '<strong>', '</strong>' ); 
				?></p>
				<p class="wet-de-p"><?php 
				/* translators: 1: opening strong tag, 2: closing strong tag */
				printf( esc_html__( 'To enable the Elements from the WPKoi Templates for Elementor plugin, please disable the %1$sElementor Addon%2$s in the %1$sAppearance -> [Your Theme Name]%2$s menu.', 'wpkoi-templates-for-elementor' ), '<strong>', '</strong>' ); 
				?></p>
			<?php } ?>
			</div>
			</div>
			<div class="wet-element-col-2">
				<div class="wpkoi-upgrade wet-sidebar-element wet-sc">
					<h3><?php esc_html_e( 'Need more templates?', 'wpkoi-templates-for-elementor' ); ?></h3>
					<p><?php esc_html_e( 'WPKoi Templates for Elementor Premium has a much more page templates with extra Elementor page builder elements.', 'wpkoi-templates-for-elementor' ); ?></p>
					<a href="<?php echo esc_url( WPKOI_TEMPLATES_FOR_ELEMENTOR_WEB_URL ); ?>" class="wpkoi-admin-button" target="_blank"><?php esc_html_e( 'Upgrade to Premium', 'wpkoi-templates-for-elementor' ); ?></a>
				</div>
				
				<div class="wpkoi-review wet-sidebar-element wet-sc">
					<h3><?php esc_html_e( 'Help with Your review', 'wpkoi-templates-for-elementor' ); ?></h3>
					<p><?php esc_html_e( 'If You like WPKoi Templates plugin, show it to the world with Your review. Your feedback helps a lot.', 'wpkoi-templates-for-elementor' ); ?></p>
					<a href="<?php echo esc_url('https://wordpress.org/support/plugin/wpkoi-templates-for-elementor/reviews/?rate=5#new-post'); ?>" class="wpkoi-admin-button" target="_blank"><?php esc_html_e( 'Add my review', 'wpkoi-templates-for-elementor' ); ?></a>
				</div>

				<div class="wpkoi-social wet-sidebar-element wet-sc">
					<h3><?php esc_html_e( 'WPKoi on Facebook', 'wpkoi-templates-for-elementor' ); ?></h3>
					<p><?php esc_html_e( 'If You want to get useful infos about WPKoi products, follow WPKoi on Facebook.', 'wpkoi-templates-for-elementor' ); ?></p>
					<a href="<?php echo esc_url('https://www.facebook.com/wpkoithemes/'); ?>" class="wpkoi-admin-button" target="_blank"><?php esc_html_e( 'Go to Facebook', 'wpkoi-templates-for-elementor' ); ?></a>
				</div>
			</div>
		</div>
	</div>
	
	<div id="wet-page-info">
		<div class="wet-element-col-flex">
			<div class="wpkoi-disable-elements wet-element-col-1"><div class="wet-sidebar-element">
				<h3 class="wet-nomargin"><?php esc_html_e( 'WPKoi Templates for Elementor', 'wpkoi-templates-for-elementor' );?></h3>
				<h4 class="wet-subtitle"><?php esc_html_e( 'Elevate the Spirit of Your Website!', 'wpkoi-templates-for-elementor' );?></h4>
				
				<h2 class="wet-nomargin"><?php esc_html_e( 'How to Import WPKoi Templates to Your Site', 'wpkoi-templates-for-elementor' );?></h2>
				
				<h5 class="wet-subtext"><?php esc_html_e( 'Preparation:', 'wpkoi-templates-for-elementor' );?></h5>
				<p><?php esc_html_e( 'Ensure you have installed and activated both the', 'wpkoi-templates-for-elementor' );?> <a href="https://wordpress.org/plugins/elementor/" target="_blank"><?php esc_html_e( 'Elementor', 'wpkoi-templates-for-elementor' );?></a> <?php esc_html_e( 'and', 'wpkoi-templates-for-elementor' );?> <a href="https://wordpress.org/plugins/wpkoi-templates-for-elementor/" target="_blank"><?php esc_html_e( 'WPKoi Templates for Elementor', 'wpkoi-templates-for-elementor' );?></a> <?php esc_html_e( 'plugins. All features work with the free version of the Elementor Website Builder.', 'wpkoi-templates-for-elementor' );?></p>
				
				<h5 class="wet-subtext"><?php esc_html_e( 'Importing a Template:', 'wpkoi-templates-for-elementor' );?></h5>
				<ol class="wet-sublist">
					<li><?php esc_html_e( 'Navigate to the', 'wpkoi-templates-for-elementor' );?> <strong><?php esc_html_e( 'Templates', 'wpkoi-templates-for-elementor' );?></strong> <?php esc_html_e( 'tab.', 'wpkoi-templates-for-elementor' );?></li>
					<li><?php esc_html_e( 'Find your favorite page template and click the', 'wpkoi-templates-for-elementor' );?> <strong><?php esc_html_e( 'IMPORT', 'wpkoi-templates-for-elementor' );?></strong> <?php esc_html_e( 'button. This will add the template to your', 'wpkoi-templates-for-elementor' );?> <a href="https://elementor.com/help/template-library/" target="_blank"><?php esc_html_e( 'Elementor Template Library', 'wpkoi-templates-for-elementor' );?></a> <?php esc_html_e( 'and automatically create a new page with the imported content.', 'wpkoi-templates-for-elementor' );?></li>
				</ol>
				
				<h5 class="wet-subtext"><?php esc_html_e( 'Editing the Content:', 'wpkoi-templates-for-elementor' );?></h5>
				<ol class="wet-sublist">
					<li><?php esc_html_e( 'Go to the', 'wpkoi-templates-for-elementor' );?> <strong><?php esc_html_e( 'Pages', 'wpkoi-templates-for-elementor' );?></strong> <?php esc_html_e( 'section in the WordPress admin menu to find the newly generated page.', 'wpkoi-templates-for-elementor' );?></li>
					<li><?php esc_html_e( 'If needed, adjust the page settings in the default page editor (e.g., set it to full-width, adjust margins, etc.).', 'wpkoi-templates-for-elementor' );?></li>
					<li><?php esc_html_e( 'Once the page settings are adjusted, use Elementor to edit the content and design as desired.', 'wpkoi-templates-for-elementor' );?></li>
				</ol>
				
				<h2 class="wet-addmargin"><?php esc_html_e( 'Manually Importing Templates', 'wpkoi-templates-for-elementor' );?></h2>
				<p><?php esc_html_e( "If you prefer not to generate pages automatically, or if your website's security settings or compatibility issues prevent automatic imports, you can manually download and upload the templates.", "wpkoi-templates-for-elementor" );?></p>
				
				<ol class="wet-sublist">
					<li><?php esc_html_e( 'In the', 'wpkoi-templates-for-elementor' );?> <strong><?php esc_html_e( 'Templates', 'wpkoi-templates-for-elementor' );?></strong> <?php esc_html_e( 'tab, click the', 'wpkoi-templates-for-elementor' );?> <strong><?php esc_html_e( 'Download Manually', 'wpkoi-templates-for-elementor' );?></strong> <?php esc_html_e( 'button for the template you want.', 'wpkoi-templates-for-elementor' );?></li>
					<li><?php esc_html_e( 'Right-click and select', 'wpkoi-templates-for-elementor' );?> <strong><?php esc_html_e( 'Save As', 'wpkoi-templates-for-elementor' );?></strong> <?php esc_html_e( 'to save the JSON file to your computer.', 'wpkoi-templates-for-elementor' );?></li>
					<li><?php esc_html_e( 'To import the downloaded file, go to the', 'wpkoi-templates-for-elementor' );?> <strong><?php esc_html_e( 'Templates', 'wpkoi-templates-for-elementor' );?></strong> <?php esc_html_e( 'section in the WordPress admin menu (Elementor Template Library) and upload it as a page template.', 'wpkoi-templates-for-elementor' );?></li>
				</ol>
				
				<div class="wet-video">
					<iframe src="https://www.youtube.com/embed/Y37HTFukEi0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
				</div>
			</div></div>
			<div class="wet-element-col-2">
				<div class="wpkoi-upgrade wet-sidebar-element wet-sc">
					<h3><?php esc_html_e( 'Need more templates?', 'wpkoi-templates-for-elementor' ); ?></h3>
					<p><?php esc_html_e( 'WPKoi Templates for Elementor Premium has a much more page templates with extra Elementor page builder elements.', 'wpkoi-templates-for-elementor' ); ?></p>
					<a href="<?php echo esc_url( WPKOI_TEMPLATES_FOR_ELEMENTOR_WEB_URL ); ?>" class="wpkoi-admin-button" target="_blank"><?php esc_html_e( 'Upgrade to Premium', 'wpkoi-templates-for-elementor' ); ?></a>
				</div>
				
				<div class="wpkoi-review wet-sidebar-element wet-sc">
					<h3><?php esc_html_e( 'Help with Your review', 'wpkoi-templates-for-elementor' ); ?></h3>
					<p><?php esc_html_e( 'If You like WPKoi Templates plugin, show it to the world with Your review. Your feedback helps a lot.', 'wpkoi-templates-for-elementor' ); ?></p>
					<a href="<?php echo esc_url('https://wordpress.org/support/plugin/wpkoi-templates-for-elementor/reviews/?rate=5#new-post'); ?>" class="wpkoi-admin-button" target="_blank"><?php esc_html_e( 'Add my review', 'wpkoi-templates-for-elementor' ); ?></a>
				</div>

				<div class="wpkoi-social wet-sidebar-element wet-sc">
					<h3><?php esc_html_e( 'WPKoi on Facebook', 'wpkoi-templates-for-elementor' ); ?></h3>
					<p><?php esc_html_e( 'If You want to get useful infos about WPKoi products, follow WPKoi on Facebook.', 'wpkoi-templates-for-elementor' ); ?></p>
					<a href="<?php echo esc_url('https://www.facebook.com/wpkoithemes/'); ?>" class="wpkoi-admin-button" target="_blank"><?php esc_html_e( 'Go to Facebook', 'wpkoi-templates-for-elementor' ); ?></a>
				</div>
			</div>
		</div>
	</div>
</div>

<?php 

// Enable json upload
require WPKOI_TEMPLATES_FOR_ELEMENTOR_DIRECTORY . 'inc/enable-upload.php';
