<?php
/**
 * Header and Footer functions
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'wpkoi_construct_footer' ) ) {
	// Build our footer.
	add_action( WPKOI_PARENT_THEME_SLUG . '_footer', 'wpkoi_construct_footer' );
	function wpkoi_construct_footer() {
		$wpkoi_settings = wp_parse_args(
			get_option( 'wpkoi_settings', array() ),
			wpkoi_get_defaults()
		);
		
		if ( $wpkoi_settings[ 'disable_site_info' ] != 'disable'  ) {
		?>
		<footer class="site-info" itemtype="https://schema.org/WPFooter" itemscope="itemscope">
			<div class="inside-site-info">
				<?php
				// wpkoi_before_copyright hook.
				do_action( 'wpkoi_before_copyright' );
				?>
				<div class="copyright-bar">
					<?php do_action( 'wpkoi_credits' ); ?>
				</div>
			</div>
		</footer><!-- .site-info -->
		<?php
		}
	}
}

if ( ! function_exists( 'wpkoi_add_footer_info' ) ) {
	add_action( 'wpkoi_credits', 'wpkoi_add_footer_info' );
	// Add the copyright to the footer
	function wpkoi_add_footer_info() {
		echo '<span class="copyright">&copy; ' . esc_html( gmdate( 'Y' ) ) . ' ' . esc_html( get_bloginfo( 'name' ) ) . '</span> &bull; ' . esc_html__( 'Powered by', 'wpkoi-templates-for-elementor' ) . ' <a href="' . esc_url( wpkoi_theme_uri_link() ) . '" itemprop="url">' . esc_html__( 'WPKoi', 'wpkoi-templates-for-elementor' ) . '</a>';
	}
}

if ( ! function_exists( 'wpkoi_back_to_top' ) ) {
	// Build the back to top button
	add_action( WPKOI_PARENT_THEME_SLUG . '_after_footer', 'wpkoi_back_to_top', 2 );
	function wpkoi_back_to_top() {
		$wpkoi_settings = wp_parse_args(
			get_option( 'wpkoi_settings', array() ),
			wpkoi_get_defaults()
		);

		if ( 'enable' !== $wpkoi_settings[ 'back_to_top' ] ) {
			return;
		}

		echo '<a title="' . esc_attr__( 'Scroll back to top', 'wpkoi-templates-for-elementor' ) . '" rel="nofollow" href="#" id="wpkoi-back-to-top" class="wpkoi-back-to-top">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M201.4 137.4c12.5-12.5 32.8-12.5 45.3 0l160 160c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L224 205.3 86.6 342.6c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3l160-160z"/></svg>
				<span class="screen-reader-text">' . esc_html__( 'Scroll back to top', 'wpkoi-templates-for-elementor' ) . '</span>
			</a>';
	}
}

if ( ! function_exists( 'wpkoi_fixed_side_content_footer' ) ) {
	add_action( WPKOI_PARENT_THEME_SLUG . '_after_footer', 'wpkoi_fixed_side_content_footer', 5 );
	function wpkoi_fixed_side_content_footer() { 

		$fixed_side_content   		=  wpkoi_get_setting( 'fixed_side_content' ); 
		$socials_facebook_url  		=  wpkoi_get_setting( 'socials_facebook_url' );
		$socials_twitter_url   		=  wpkoi_get_setting( 'socials_twitter_url' );
		$socials_instagram_url    	=  wpkoi_get_setting( 'socials_instagram_url' );
		$socials_youtube_url   		=  wpkoi_get_setting( 'socials_youtube_url' );
		$socials_tiktok_url   		=  wpkoi_get_setting( 'socials_tiktok_url' );
		$socials_twitch_url   		=  wpkoi_get_setting( 'socials_twitch_url' );
		$socials_tumblr_url    		=  wpkoi_get_setting( 'socials_tumblr_url' );
		$socials_pinterest_url 		=  wpkoi_get_setting( 'socials_pinterest_url' );
		$socials_linkedin_url  		=  wpkoi_get_setting( 'socials_linkedin_url' );
		$socials_custom_icon_1  	=  wpkoi_get_setting( 'socials_custom_icon_1' );
		$socials_custom_icon_url_1  =  wpkoi_get_setting( 'socials_custom_icon_url_1' );
		$socials_custom_icon_2  	=  wpkoi_get_setting( 'socials_custom_icon_2' );
		$socials_custom_icon_url_2  =  wpkoi_get_setting( 'socials_custom_icon_url_2' );
		$socials_custom_icon_3  	=  wpkoi_get_setting( 'socials_custom_icon_3' );
		$socials_custom_icon_url_3  =  wpkoi_get_setting( 'socials_custom_icon_url_3' );
		$socials_mail_url     		=  wpkoi_get_setting( 'socials_mail_url' );


		if ( ( $fixed_side_content != '' ) || ( $socials_facebook_url != '' ) || ( $socials_twitter_url != '' ) || ( $socials_instagram_url != '' ) || ( $socials_youtube_url != '' ) || ( $socials_tiktok_url != '' ) || ( $socials_twitch_url != '' ) || ( $socials_tumblr_url != '' ) || ( $socials_pinterest_url != '' ) || ( $socials_linkedin_url != '' ) || ( $socials_custom_icon_url_1 != '' ) || ( $socials_custom_icon_1 != '' ) || ( $socials_custom_icon_url_2 != '' ) || ( $socials_custom_icon_2 != '' ) || ( $socials_custom_icon_url_3 != '' ) || ( $socials_custom_icon_3 != '' ) || ( $socials_mail_url != '' ) ) { ?>
		<div class="wpkoi-side-left-content">
			<div class="wpkoi-side-left-socials">
			<?php do_action( 'wpkoi_social_bar_action' ); ?>
			</div>
			<?php if ( $fixed_side_content != '' ) { ?>
			<div class="wpkoi-side-left-text">
				<div class="wpkoi-side-left-text-content"><?php echo wp_kses_post( $fixed_side_content ); ?></div>
			</div>
			<?php } ?>
		</div>
		<?php
		}
	}
}

if ( ! function_exists( 'wpkoi_social_bar' ) ) {
	add_action( 'wpkoi_social_bar_action', 'wpkoi_social_bar' );
	function wpkoi_social_bar() {
		$socials_facebook_url  		=  wpkoi_get_setting( 'socials_facebook_url' );
		$socials_twitter_url   		=  wpkoi_get_setting( 'socials_twitter_url' );
		$socials_instagram_url    	=  wpkoi_get_setting( 'socials_instagram_url' );
		$socials_youtube_url   		=  wpkoi_get_setting( 'socials_youtube_url' );
		$socials_tiktok_url   		=  wpkoi_get_setting( 'socials_tiktok_url' );
		$socials_twitch_url   		=  wpkoi_get_setting( 'socials_twitch_url' );
		$socials_tumblr_url    		=  wpkoi_get_setting( 'socials_tumblr_url' );
		$socials_pinterest_url 		=  wpkoi_get_setting( 'socials_pinterest_url' );
		$socials_linkedin_url  		=  wpkoi_get_setting( 'socials_linkedin_url' );
		$socials_custom_icon_1  	=  wpkoi_get_setting( 'socials_custom_icon_1' );
		$socials_custom_icon_url_1  =  wpkoi_get_setting( 'socials_custom_icon_url_1' );
		$socials_custom_icon_2  	=  wpkoi_get_setting( 'socials_custom_icon_2' );
		$socials_custom_icon_url_2  =  wpkoi_get_setting( 'socials_custom_icon_url_2' );
		$socials_custom_icon_3  	=  wpkoi_get_setting( 'socials_custom_icon_3' );
		$socials_custom_icon_url_3  =  wpkoi_get_setting( 'socials_custom_icon_url_3' );
		$socials_mail_url     		=  wpkoi_get_setting( 'socials_mail_url' );
		
		if ( ( $socials_facebook_url != '' ) || ( $socials_twitter_url != '' ) || ( $socials_instagram_url != '' ) || ( $socials_youtube_url != '' ) || ( $socials_tiktok_url != '' ) || ( $socials_twitch_url != '' ) || ( $socials_tumblr_url != '' ) || ( $socials_pinterest_url != '' ) || ( $socials_linkedin_url != '' ) || ( $socials_custom_icon_url_1 != '' ) || ( $socials_custom_icon_1 != '' ) || ( $socials_custom_icon_url_2 != '' ) || ( $socials_custom_icon_2 != '' ) || ( $socials_custom_icon_url_3 != '' ) || ( $socials_custom_icon_3 != '' ) || ( $socials_mail_url != '' ) ) {
	?>
    <div class="wpkoi-social-bar">
    	<ul class="wpkoi-socials-list">
        <?php if ( $socials_facebook_url != '' ) { ?>
        	<li><a href="<?php echo esc_url( $socials_facebook_url ); ?>" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M504 256C504 119 393 8 256 8S8 119 8 256c0 123.78 90.69 226.38 209.25 245V327.69h-63V256h63v-54.64c0-62.15 37-96.48 93.67-96.48 27.14 0 55.52 4.84 55.52 4.84v61h-31.28c-30.8 0-40.41 19.12-40.41 38.73V256h68.78l-11 71.69h-57.78V501C413.31 482.38 504 379.78 504 256z"/></svg></a></li>
        <?php } ?>
        <?php if ( $socials_twitter_url != '' ) { ?>
        	<li><a href="<?php echo esc_url( $socials_twitter_url ); ?>" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z"/></svg></a></li>
        <?php } ?>
        <?php if ( $socials_instagram_url != '' ) { ?>
        	<li><a href="<?php echo esc_url( $socials_instagram_url ); ?>" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"/></svg></a></li>
        <?php } ?>
        <?php if ( $socials_youtube_url != '' ) { ?>
        	<li><a href="<?php echo esc_url( $socials_youtube_url ); ?>" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M549.655 124.083c-6.281-23.65-24.787-42.276-48.284-48.597C458.781 64 288 64 288 64S117.22 64 74.629 75.486c-23.497 6.322-42.003 24.947-48.284 48.597-11.412 42.867-11.412 132.305-11.412 132.305s0 89.438 11.412 132.305c6.281 23.65 24.787 41.5 48.284 47.821C117.22 448 288 448 288 448s170.78 0 213.371-11.486c23.497-6.321 42.003-24.171 48.284-47.821 11.412-42.867 11.412-132.305 11.412-132.305s0-89.438-11.412-132.305zm-317.51 213.508V175.185l142.739 81.205-142.739 81.201z"/></svg></a></li>
        <?php } ?>
        <?php if ( $socials_tiktok_url != '' ) { ?>
        	<li><a href="<?php echo esc_url( $socials_tiktok_url ); ?>" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M448,209.91a210.06,210.06,0,0,1-122.77-39.25V349.38A162.55,162.55,0,1,1,185,188.31V278.2a74.62,74.62,0,1,0,52.23,71.18V0l88,0a121.18,121.18,0,0,0,1.86,22.17h0A122.18,122.18,0,0,0,381,102.39a121.43,121.43,0,0,0,67,20.14Z"/></svg></a></li>
        <?php } ?>
        <?php if ( $socials_twitch_url != '' ) { ?>
        	<li><a href="<?php echo esc_url( $socials_twitch_url ); ?>" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M391.17,103.47H352.54v109.7h38.63ZM285,103H246.37V212.75H285ZM120.83,0,24.31,91.42V420.58H140.14V512l96.53-91.42h77.25L487.69,256V0ZM449.07,237.75l-77.22,73.12H294.61l-67.6,64v-64H140.14V36.58H449.07Z"/></svg></a></li>
        <?php } ?>
        <?php if ( $socials_tumblr_url != '' ) { ?>
        	<li><a href="<?php echo esc_url( $socials_tumblr_url ); ?>" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M309.8 480.3c-13.6 14.5-50 31.7-97.4 31.7-120.8 0-147-88.8-147-140.6v-144H17.9c-5.5 0-10-4.5-10-10v-68c0-7.2 4.5-13.6 11.3-16 62-21.8 81.5-76 84.3-117.1.8-11 6.5-16.3 16.1-16.3h70.9c5.5 0 10 4.5 10 10v115.2h83c5.5 0 10 4.4 10 9.9v81.7c0 5.5-4.5 10-10 10h-83.4V360c0 34.2 23.7 53.6 68 35.8 4.8-1.9 9-3.2 12.7-2.2 3.5.9 5.8 3.4 7.4 7.9l22 64.3c1.8 5 3.3 10.6-.4 14.5z"/></svg></a></li>
        <?php } ?>
        <?php if ( $socials_pinterest_url != '' ) { ?>
        	<li><a href="<?php echo esc_url( $socials_pinterest_url ); ?>" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512"><path d="M496 256c0 137-111 248-248 248-25.6 0-50.2-3.9-73.4-11.1 10.1-16.5 25.2-43.5 30.8-65 3-11.6 15.4-59 15.4-59 8.1 15.4 31.7 28.5 56.8 28.5 74.8 0 128.7-68.8 128.7-154.3 0-81.9-66.9-143.2-152.9-143.2-107 0-163.9 71.8-163.9 150.1 0 36.4 19.4 81.7 50.3 96.1 4.7 2.2 7.2 1.2 8.3-3.3.8-3.4 5-20.3 6.9-28.1.6-2.5.3-4.7-1.7-7.1-10.1-12.5-18.3-35.3-18.3-56.6 0-54.7 41.4-107.6 112-107.6 60.9 0 103.6 41.5 103.6 100.9 0 67.1-33.9 113.6-78 113.6-24.3 0-42.6-20.1-36.7-44.8 7-29.5 20.5-61.3 20.5-82.6 0-19-10.2-34.9-31.4-34.9-24.9 0-44.9 25.7-44.9 60.2 0 22 7.4 36.8 7.4 36.8s-24.5 103.8-29 123.2c-5 21.4-3 51.6-.9 71.2C65.4 450.9 0 361.1 0 256 0 119 111 8 248 8s248 111 248 248z"/></svg></a></li>
        <?php } ?>
        <?php if ( $socials_linkedin_url != '' ) { ?>
        	<li><a href="<?php echo esc_url( $socials_linkedin_url ); ?>" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M416 32H31.9C14.3 32 0 46.5 0 64.3v383.4C0 465.5 14.3 480 31.9 480H416c17.6 0 32-14.5 32-32.3V64.3c0-17.8-14.4-32.3-32-32.3zM135.4 416H69V202.2h66.5V416zm-33.2-243c-21.3 0-38.5-17.3-38.5-38.5S80.9 96 102.2 96c21.2 0 38.5 17.3 38.5 38.5 0 21.3-17.2 38.5-38.5 38.5zm282.1 243h-66.4V312c0-24.8-.5-56.7-34.5-56.7-34.6 0-39.9 27-39.9 54.9V416h-66.4V202.2h63.7v29.2h.9c8.9-16.8 30.6-34.5 62.9-34.5 67.2 0 79.7 44.3 79.7 101.9V416z"/></svg></a></li>
        <?php } ?>
        <?php do_action( 'wpkoi_after_socials' ); ?>
        <?php if ( $socials_mail_url != '' ) { ?>
        	<li><a href="mailto:<?php echo esc_attr( $socials_mail_url ); ?>"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M48 64C21.5 64 0 85.5 0 112c0 15.1 7.1 29.3 19.2 38.4L236.8 313.6c11.4 8.5 27 8.5 38.4 0L492.8 150.4c12.1-9.1 19.2-23.3 19.2-38.4c0-26.5-21.5-48-48-48H48zM0 176V384c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V176L294.4 339.2c-22.8 17.1-54 17.1-76.8 0L0 176z"/></svg></a></li>
        <?php } ?>
        </ul>
    </div>    
	<?php
		}
	}
}

if ( ! function_exists( 'wpkoi_noise_holder' ) ) {
	//Adds div to footer for the noise animation
	add_action( WPKOI_PARENT_THEME_SLUG . '_after_footer', 'wpkoi_noise_holder' );
	function wpkoi_noise_holder() {
		$wpkoi_settings = wp_parse_args(
			get_option( 'wpkoi_settings', array() ),
			wpkoi_get_defaults()
		);

		if ( 'enable' !== $wpkoi_settings[ 'enable_noise' ] ) {
			return;
		}
		
		$noise_image = isset( $wpkoi_settings['noise_image'] ) ? $wpkoi_settings['noise_image'] : WPKOI_TEMPLATES_FOR_ELEMENTOR_URL . 'theme/img/wpkoi-noise.webp';
		if ($noise_image == '') { $noise_image = WPKOI_TEMPLATES_FOR_ELEMENTOR_URL . 'theme/img/wpkoi-noise.webp'; }

		echo '<div class="wpkoi-noise" style="background: transparent url(' . esc_url( $noise_image ) . ') repeat 0 0;}"></div>';
		
	}
}

add_action( 'after_setup_theme', 'wpkoi_remove_default_header', 11 );
function wpkoi_remove_default_header() {
    // Remove the default header action added by the theme
    remove_action( WPKOI_PARENT_THEME_SLUG . '_default_header', WPKOI_PARENT_THEME_SLUG . '_build_default_header' );
}

// Top bar
add_action( WPKOI_PARENT_THEME_SLUG . '_top_bar', 'wpkoi_construct_top_bar' );
if ( ! function_exists( 'wpkoi_construct_top_bar' ) ) {
	// Add the top bar
	function wpkoi_construct_top_bar() {
		$wpkoi_settings = wp_parse_args(
			get_option( 'wpkoi_settings', array() ),
			wpkoi_get_defaults()
		);
		
		if ( $wpkoi_settings['top_bar_mobile'] == 'hide' ) {
			return;
		}
		?>
        <div class="<?php echo esc_attr( WPKOI_PARENT_THEME_SLUG ); ?>-top-bar-content">
        	<?php 
				// Add topbar template.
				block_template_part( 'topbar' );
			?>
        </div>
        <?php
	}
}

// Build before header content
add_action( WPKOI_PARENT_THEME_SLUG . '_before_header_content', 'wpkoi_construct_before_header' );
if ( ! function_exists( 'wpkoi_construct_before_header' ) ) {
	// Build the header.
	function wpkoi_construct_before_header() {
		$wpkoi_settings = wp_parse_args(
			get_option( 'wpkoi_settings', array() ),
			wpkoi_get_defaults()
		);
		
		$stickydata = '781';
		if ( $wpkoi_settings['sticky_header'] == 'enablemobile' ) {
			$stickydata = '0';
		}
		$siteheaderclass = '';
		if ( $wpkoi_settings['header_layout_setting'] == 'contained-header' ) {
			$siteheaderclass = 'grid-container grid-parent';
		}
		$insideheaderclass = '';
		if ( $wpkoi_settings['header_inner_width'] == 'contained' ) {
			$insideheaderclass = 'grid-container grid-parent';
		}
		?>
        <div class="site-header-holder" data-minwidth="<?php echo esc_attr( $stickydata ); ?>">
			<header class="site-header has-inline-mobile-toggle <?php echo esc_attr( $siteheaderclass ); ?>" id="masthead">
				<div class="inside-header <?php echo esc_attr( $insideheaderclass ); ?>">
        <?php
	}
}

// Build after header content
add_action( WPKOI_PARENT_THEME_SLUG . '_after_header_content', 'wpkoi_construct_after_header' );
if ( ! function_exists( 'wpkoi_construct_after_header' ) ) {
	// Build the header.
	function wpkoi_construct_after_header() {
		?>
				</div>
			</header>
        </div>
        <?php
	}
}

// Build the header contents.
add_action( WPKOI_PARENT_THEME_SLUG . '_header_content', 'wpkoi_header_items' );
if ( ! function_exists( 'wpkoi_header_items' ) ) {
	function wpkoi_header_items() {
		$order = apply_filters(
			'wpkoi_header_items_order',
			array(
				'site-branding',
			)
		);

		foreach ( $order as $item ) {

			if ( 'site-branding' === $item ) {
				wpkoi_construct_site_title();
			}
		}
	}
}

// Build the logo
if ( ! function_exists( 'wpkoi_construct_logo' ) ) {
	function wpkoi_construct_logo() {
		$wpkoi_settings = wp_parse_args(
			get_option( 'wpkoi_settings', array() ),
			wpkoi_get_defaults()
		);
		
		$logo_url = esc_url( $wpkoi_settings['custom_logo'] );
		$retina_logo_url = esc_url( $wpkoi_settings['retina_logo'] );

		// If we don't have a logo, bail.
		if ( empty( $logo_url ) ) {
			return;
		}

		do_action( 'wpkoi_before_logo' );

		$attr = apply_filters(
			'wpkoi_logo_attributes',
			array(
				'class' => 'header-image is-logo-image',
				'alt'   => esc_attr( apply_filters( 'wpkoi_logo_title', get_bloginfo( 'name', 'display' ) ) ),
				'src'   => esc_url( $logo_url ),
			)
		);

		$data = false;

		if ( '' !== $retina_logo_url ) {
			$attr['srcset'] = esc_url( $logo_url ) . ' 1x, ' . esc_url( $retina_logo_url ) . ' 2x';
		}

		if ( $data ) {
			if ( isset( $data['width'] ) ) {
				$attr['width'] = $data['width'];
			}

			if ( isset( $data['height'] ) ) {
				$attr['height'] = $data['height'];
			}
		}

		$attr = array_map( 'esc_attr', $attr );

		$html_attr = '';
		foreach ( $attr as $name => $value ) {
			$html_attr .= " $name=" . '"' . $value . '"';
		}

		// Print our HTML.
		echo apply_filters( 
			'wpkoi_logo_output',
			sprintf(
				'<div class="site-logo">
					<a href="%1$s" rel="home">
						<img %2$s />
					</a>
				</div>',
				esc_url( apply_filters( 'wpkoi_logo_href', home_url( '/' ) ) ),
				$html_attr
			),
			esc_html( $logo_url ),
			$html_attr
		);

		do_action( 'wpkoi_after_logo' );
	}
}

// Build the site title and tagline.
if ( ! function_exists( 'wpkoi_construct_site_title' ) ) {
	function wpkoi_construct_site_title() {
		$wpkoi_settings = wp_parse_args(
			get_option( 'wpkoi_settings', array() ),
			wpkoi_get_defaults()
		);

		// Get the title and tagline.
		$title = get_bloginfo( 'title' );
		$tagline = get_bloginfo( 'description' );

		// If the disable title checkbox is checked, or the title field is empty, return true.
		$disable_title = ( '1' == $wpkoi_settings['hide_title'] || '' == $title ) ? true : false;

		// If the disable tagline checkbox is checked, or the tagline field is empty, return true.
		$disable_tagline = ( '1' == $wpkoi_settings['hide_tagline'] || '' == $tagline ) ? true : false;

		// Build our site title.
		$site_title = apply_filters(
			'wpkoi_site_title_output',
			sprintf(
				'<%1$s class="main-title">
					<a href="%2$s" rel="home">%3$s</a>
				</%1$s>',
				( is_front_page() && is_home() ) ? 'h1' : 'p',
				esc_url( apply_filters( 'wpkoi_site_title_href', home_url( '/' ) ) ),
				get_bloginfo( 'name' )
			)
		);

		// Build our tagline.
		$site_tagline = apply_filters(
			'wpkoi_site_description_output',
			sprintf(
				'<p class="site-description">%1$s</p>',
				html_entity_decode( get_bloginfo( 'description', 'display' ) )
			)
		);

		// Site title and tagline.
		echo '<div class="site-branding-container">';
		wpkoi_construct_logo();

		echo apply_filters(
			'wpkoi_site_branding_output',
			sprintf(
				'<div class="site-branding">
					%1$s
					%2$s
				</div>',
				( ! $disable_title ) ? $site_title : '',
				( ! $disable_tagline ) ? $site_tagline : ''
			)
		);

		echo '</div>';
	}
}

// Get the location of the navigation and filter it.
if ( ! function_exists( 'wpkoi_get_navigation_location' ) ) {
	function wpkoi_get_navigation_location() {
		$wpkoi_settings = wp_parse_args(
			get_option( 'wpkoi_settings', array() ),
			wpkoi_get_defaults()
		);
		
		return apply_filters( 'wpkoi_navigation_location', $wpkoi_settings['nav_position_setting'] );
	}
}

//Build the navigation.
if ( ! function_exists( 'wpkoi_navigation_position' ) ) {
	function wpkoi_navigation_position() {
		$wpkoi_settings = wp_parse_args(
			get_option( 'wpkoi_settings', array() ),
			wpkoi_get_defaults()
		);
		
		if ( 'full-width' !== $wpkoi_settings['nav_inner_width'] ) {
			$inav_class = 'grid-container';
		} else {
			$inav_class = 'grid-parent';
		}
		
		$submenu_direction = 'sub-menu-right';

		if ( 'left' === $wpkoi_settings['nav_dropdown_direction'] ) {
			$submenu_direction = 'sub-menu-left';
		}
		
		do_action( 'wpkoi_before_navigation' );
		?>
		<nav id="site-navigation" class="main-navigation <?php echo esc_attr($submenu_direction); ?>">
			<div class="inside-navigation <?php echo esc_attr($inav_class); ?>">
				<?php
				
				do_action( 'wpkoi_inside_navigation' );
				
				do_action( 'wpkoi_after_mobile_menu_button' );

				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container' => 'div',
						'container_class' => 'main-nav',
						'container_id' => 'primary-menu',
						'menu_class' => '',
						'fallback_cb' => 'wpkoi_menu_fallback',
						'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
					)
				);

				do_action( 'wpkoi_after_primary_menu' );
				?>
			</div>
		</nav>
		<?php
		do_action( 'wpkoi_after_navigation' );
	}
}

// Build the mobile menu toggle in the header.
add_action( 'wpkoi_before_navigation', 'wpkoi_do_header_mobile_menu_toggle' );
function wpkoi_do_header_mobile_menu_toggle() {
	?>
	<nav class="main-navigation mobile-menu-control-wrapper" id="mobile-menu-control-wrapper">
		<?php
		do_action( 'wpkoi_inside_mobile_menu_control_wrapper' );
		?>
		<button data-nav="site-navigation" class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
			<?php
			do_action( 'wpkoi_inside_mobile_menu' );
			?>
			<span class="icon-menu-bars">
			<svg viewBox="0 0 512 512" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><path d="M0 96c0-13.255 10.745-24 24-24h464c13.255 0 24 10.745 24 24s-10.745 24-24 24H24c-13.255 0-24-10.745-24-24zm0 160c0-13.255 10.745-24 24-24h464c13.255 0 24 10.745 24 24s-10.745 24-24 24H24c-13.255 0-24-10.745-24-24zm0 160c0-13.255 10.745-24 24-24h464c13.255 0 24 10.745 24 24s-10.745 24-24 24H24c-13.255 0-24-10.745-24-24z" /></svg>
			<svg viewBox="0 0 512 512" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><path d="M71.029 71.029c9.373-9.372 24.569-9.372 33.942 0L256 222.059l151.029-151.03c9.373-9.372 24.569-9.372 33.942 0 9.372 9.373 9.372 24.569 0 33.942L289.941 256l151.03 151.029c9.372 9.373 9.372 24.569 0 33.942-9.373 9.372-24.569 9.372-33.942 0L256 289.941l-151.029 151.03c-9.373 9.372-24.569 9.372-33.942 0-9.372-9.373-9.372-24.569 0-33.942L222.059 256 71.029 104.971c-9.372-9.373-9.372-24.569 0-33.942z"></path></svg>
			</span>
			<span class="screen-reader-text"><?php echo esc_html( 'Menu', 'wpkoi-templates-for-elementor' ) ?></span>
		</button>
	</nav>
	<?php
}

// Menu fallback.
if ( ! function_exists( 'wpkoi_menu_fallback' ) ) {
	function wpkoi_menu_fallback( $args ) {
		?>
		<div id="primary-menu" class="main-nav">
			<ul>
				<?php
				$args = array(
					'sort_column' => 'menu_order',
					'title_li' => '',
					'walker' => new WPKoi_Page_Walker(),
				);

				wp_list_pages( $args );
				?>
			</ul>
		</div>
		<?php
	}
}

// Generate the navigation based on settings
if ( ! function_exists( 'wpkoi_add_navigation_after_header' ) ) {
	add_action( WPKOI_PARENT_THEME_SLUG . '_after_header', 'wpkoi_add_navigation_after_header', 5 );
	function wpkoi_add_navigation_after_header() {
		if ( 'nav-below-header' === wpkoi_get_navigation_location() ) {
			wpkoi_navigation_position();
		}
	}
}

if ( ! function_exists( 'wpkoi_add_navigation_before_header' ) ) {
	add_action( WPKOI_PARENT_THEME_SLUG . '_before_header', 'wpkoi_add_navigation_before_header', 5 );
	function wpkoi_add_navigation_before_header() {
		if ( 'nav-above-header' === wpkoi_get_navigation_location() ) {
			wpkoi_navigation_position();
		}
	}
}

if ( ! function_exists( 'wpkoi_add_navigation_float_right' ) ) {
	add_action( WPKOI_PARENT_THEME_SLUG . '_after_header_content', 'wpkoi_add_navigation_float_right', 5 );
	function wpkoi_add_navigation_float_right() {
		if ( 'nav-float-right' === wpkoi_get_navigation_location() || 'nav-float-left' === wpkoi_get_navigation_location() ) {
			wpkoi_navigation_position();
		}
	}
}

// Add current-menu-item to the current item if no theme location is set
if ( ! class_exists( 'WPKoi_Page_Walker' ) && class_exists( 'Walker_Page' ) ) {
	class WPKoi_Page_Walker extends Walker_Page {
		function start_el( &$output, $page, $depth = 0, $args = array(), $current_page = 0 ) { // phpcs:ignore
			$css_class = array( 'page_item', 'page-item-' . $page->ID );
			$button = '';

			if ( isset( $args['pages_with_children'][ $page->ID ] ) ) {
				$css_class[] = 'menu-item-has-children';
				$icon = '<svg viewBox="0 0 330 512" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><path d="M305.913 197.085c0 2.266-1.133 4.815-2.833 6.514L171.087 335.593c-1.7 1.7-4.249 2.832-6.515 2.832s-4.815-1.133-6.515-2.832L26.064 203.599c-1.7-1.7-2.832-4.248-2.832-6.514s1.132-4.816 2.832-6.515l14.162-14.163c1.7-1.699 3.966-2.832 6.515-2.832 2.266 0 4.815 1.133 6.515 2.832l111.316 111.317 111.316-111.317c1.7-1.699 4.249-2.832 6.515-2.832s4.815 1.133 6.515 2.832l14.162 14.163c1.7 1.7 2.833 4.249 2.833 6.515z" /></svg>';
				$button = '<span role="presentation" class="dropdown-menu-toggle">' . $icon . '</span>';
			}

			if ( ! empty( $current_page ) ) {
				$_current_page = get_post( $current_page );
				if ( $_current_page && in_array( $page->ID, $_current_page->ancestors ) ) {
					$css_class[] = 'current-menu-ancestor';
				}

				if ( $page->ID == $current_page ) { // phpcs:ignore
					$css_class[] = 'current-menu-item';
				} elseif ( $_current_page && $page->ID == $_current_page->post_parent ) { // phpcs:ignore
					$css_class[] = 'current-menu-parent';
				}
			} elseif ( $page->ID == get_option( 'page_for_posts' ) ) { // phpcs:ignore
				$css_class[] = 'current-menu-parent';
			}

			// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Core filter name.
			$css_classes = implode( ' ', apply_filters( 'page_css_class', $css_class, $page, $depth, $args, $current_page ) );

			$args['link_before'] = empty( $args['link_before'] ) ? '' : $args['link_before'];
			$args['link_after'] = empty( $args['link_after'] ) ? '' : $args['link_after'];

			$output .= sprintf(
				'<li class="%s"><a href="%s">%s%s%s%s</a>',
				$css_classes,
				get_permalink( $page->ID ),
				$args['link_before'],
				apply_filters( 'the_title', $page->post_title, $page->ID ), // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Core filter name.
				$args['link_after'],
				$button
			);
		}
	}
}

// Add dropdown icon if menu item has children.
if ( ! function_exists( 'wpkoi_dropdown_icon_to_menu_link' ) ) {
	add_filter( 'nav_menu_item_title', 'wpkoi_dropdown_icon_to_menu_link', 10, 4 );
	function wpkoi_dropdown_icon_to_menu_link( $title, $item, $args, $depth ) {
		$role        = 'presentation';
		$tabindex    = '';
		$aria_label  = '';
		$wpkoi_settings = wp_parse_args(
			get_option( 'wpkoi_settings', array() ),
			wpkoi_get_defaults()
		);

		if ( 'click-arrow' === $wpkoi_settings['nav_dropdown_type'] ) {
			$role = 'button';
			$tabindex = ' tabindex="0"';
			$aria_label = sprintf(
				' aria-label="%s"',
				esc_attr__( 'Open Sub-Menu', 'wpkoi-templates-for-elementor' )
			);
		}

		if ( isset( $args->container_class ) && 'main-nav' === $args->container_class ) {
			foreach ( $item->classes as $value ) {
				if ( 'menu-item-has-children' === $value ) {
					$arrow_direction = 'down';

					if ( 'primary' === $args->theme_location ) {
						if ( 0 !== $depth ) {
							$arrow_direction = 'right';

							if ( 'left' === $wpkoi_settings['nav_dropdown_direction'] ) {
								$arrow_direction = 'left';
							}
						}

						if ( 'hover' !== $wpkoi_settings['nav_dropdown_type'] ) {
							$arrow_direction = 'down';
						}
					}

					$arrow_direction = apply_filters( 'wpkoi_menu_item_dropdown_arrow_direction', $arrow_direction, $args, $depth );

					if ( 'down' === $arrow_direction ) {
						$arrow_direction = '';
					} else {
						$arrow_direction = '-' . $arrow_direction;
					}

					$icon = '<svg viewBox="0 0 330 512" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><path d="M305.913 197.085c0 2.266-1.133 4.815-2.833 6.514L171.087 335.593c-1.7 1.7-4.249 2.832-6.515 2.832s-4.815-1.133-6.515-2.832L26.064 203.599c-1.7-1.7-2.832-4.248-2.832-6.514s1.132-4.816 2.832-6.515l14.162-14.163c1.7-1.699 3.966-2.832 6.515-2.832 2.266 0 4.815 1.133 6.515 2.832l111.316 111.317 111.316-111.317c1.7-1.699 4.249-2.832 6.515-2.832s4.815 1.133 6.515 2.832l14.162 14.163c1.7 1.7 2.833 4.249 2.833 6.515z" /></svg>';
					$title = $title . '<span role="' . $role . '" class="dropdown-menu-toggle"' . $tabindex . $aria_label . '>' . $icon . '</span>';
				}
			}
		}

		return $title;
	}
}

// Add attributes to the menu item link when using the Click - Menu Item option.
add_filter( 'nav_menu_link_attributes', 'wpkoi_set_menu_item_link_attributes', 10, 4 );
function wpkoi_set_menu_item_link_attributes( $atts, $item, $args, $depth ) {
	$wpkoi_settings = wp_parse_args(
		get_option( 'wpkoi_settings', array() ),
		wpkoi_get_defaults()
	);
	
	if ( ! isset( $args->container_class ) || 'main-nav' !== $args->container_class ) {
		return $atts;
	}

	if ( 'click' !== $wpkoi_settings['nav_dropdown_type'] ) {
		return $atts;
	}

	if ( in_array( 'menu-item-has-children', $item->classes, true ) ) {
		$atts['role'] = 'button';
		$atts['aria-expanded'] = 'false';
		$atts['aria-haspopup'] = 'true';
		$atts['aria-label'] = esc_attr__( 'Open Sub-Menu', 'wpkoi-templates-for-elementor' );
	}

	return $atts;
}

// Add the search bar to the navigation.
if ( ! function_exists( 'wpkoi_navigation_search' ) ) {
	add_action( 'wpkoi_inside_navigation', 'wpkoi_navigation_search' );
	function wpkoi_navigation_search() {
		$wpkoi_settings = wp_parse_args(
			get_option( 'wpkoi_settings', array() ),
			wpkoi_get_defaults()
		);

		if ( 'enable' !== $wpkoi_settings['nav_search'] ) {
			return;
		}

		echo apply_filters( 
			'wpkoi_navigation_search_output',
			sprintf(
				'<form method="get" class="search-form navigation-search" action="%1$s">
					<input type="search" class="search-field" value="%2$s" name="s" title="%3$s" />
				</form>',
				esc_url( home_url( '/' ) ),
				esc_attr( get_search_query() ),
				esc_attr_x( 'Search', 'label', 'wpkoi-templates-for-elementor' )
			)
		);
	}
}

// Add search icon to primary menu if set.
if ( ! function_exists( 'wpkoi_menu_search_icon' ) ) {
	add_filter( 'wp_nav_menu_items', 'wpkoi_menu_search_icon', 10, 2 );
	function wpkoi_menu_search_icon( $nav, $args ) {
		$wpkoi_settings = wp_parse_args(
			get_option( 'wpkoi_settings', array() ),
			wpkoi_get_defaults()
		);

		// If the search icon isn't enabled, return the regular nav.
		if ( 'enable' !== $wpkoi_settings['nav_search'] ) {
			return $nav;
		}

		// If our primary menu is set, add the search icon.
		if ( isset( $args->theme_location ) && 'primary' === $args->theme_location ) {
			$search_item = apply_filters(
				'wpkoi_navigation_search_menu_item_output',
				sprintf(
					'<li class="search-item menu-item-align-right"><a aria-label="%1$s" href="#">%2$s</a></li>',
					esc_attr__( 'Open Search Bar', 'wpkoi-templates-for-elementor' ),
					'<svg viewBox="0 0 512 512" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M208 48c-88.366 0-160 71.634-160 160s71.634 160 160 160 160-71.634 160-160S296.366 48 208 48zM0 208C0 93.125 93.125 0 208 0s208 93.125 208 208c0 48.741-16.765 93.566-44.843 129.024l133.826 134.018c9.366 9.379 9.355 24.575-.025 33.941-9.379 9.366-24.575 9.355-33.941-.025L337.238 370.987C301.747 399.167 256.839 416 208 416 93.125 416 0 322.875 0 208z" /></svg>'
				)
			);

			return $nav . $search_item;
		}

		// Our primary menu isn't set, return the regular nav.
		// In this case, the search icon is added to the wpkoi_menu_fallback() function in navigation.php.
		return $nav;
	}
}

// Add a pingback url auto-discovery header for singularly identifiable articles.
if ( ! function_exists( 'wpkoi_pingback_header' ) ) {
	add_action( 'wp_head', 'wpkoi_pingback_header' );
	function wpkoi_pingback_header() {
		if ( is_singular() && pings_open() ) {
			printf( '<link rel="pingback" href="%s">' . "\n", esc_url( get_bloginfo( 'pingback_url' ) ) );
		}
	}
}
