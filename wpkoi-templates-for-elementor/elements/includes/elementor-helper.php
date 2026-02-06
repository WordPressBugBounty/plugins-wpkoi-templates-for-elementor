<?php
namespace Elementor;

function wpkoi_elements_lite_elementor_init(){
    Plugin::instance()->elements_manager->add_category(
        'wpkoi-addons-for-elementor',
        [
            'title'  => 'WPKoi Addons for Elementor',
            'icon' => 'font'
        ],
        1
    );
}
add_action('elementor/init','Elementor\wpkoi_elements_lite_elementor_init');

function wpkoi_elements_lite_s_template_path() {
	return apply_filters( 'wpkoi-elements/template-path', 'wpkoi-elements/' );
}

/**
 * Editor Css
 */
add_action( 'elementor/editor/before_enqueue_scripts', function() {

   wp_register_style( 'wpkoi_elements_elementor_editor-css', WPKOI_ELEMENTS_LITE_URL .'/assets/css/addons-editor.css', '', WPKOI_ELEMENTS_LITE_VERSION );
   wp_enqueue_style( 'wpkoi_elements_elementor_editor-css' );

} );

// Get all elementor page templates
if ( !function_exists('wpkoi_elements_lite_get_page_templates') ) {
    function wpkoi_elements_lite_get_page_templates(){
        $page_templates = get_posts( array(
            'post_type'         => 'elementor_library',
            'posts_per_page'    => -1
        ));

        $options = array();

        if ( ! empty( $page_templates ) && ! is_wp_error( $page_templates ) ){
            foreach ( $page_templates as $post ) {
                $options[ $post->ID ] = $post->post_title;
            }
        }
        return $options;
    }
}