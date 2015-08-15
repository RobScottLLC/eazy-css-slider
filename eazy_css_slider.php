<?php 
/*
Plugin Name: Eazy CSS Slider
Plugin URI: http://robjscott.com/wordpress/
Description:  Adds slide custom post type for uploading slides. Slides can be added to slider using shortcodes. 
Version: 1.0.0
Author: Rob Scott, LLC
Author URI: http://robjscott.com
License: GPLv2
Copyright: Rob Scott, LLC
*/

// Register Custom Post Type
function eazy_css_slides() {

	$labels = array(
		'name'                => _x( 'Eazy CSS Slides', 'Post Type General Name', 'text_domain' ),
		'singular_name'       => _x( 'Eazy CSS Slide', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'           => __( 'Eazy CSS Slider', 'text_domain' ),
		'name_admin_bar'      => __( 'Eazy CSS Slide', 'text_domain' ),
		'parent_item_colon'   => __( 'Parent Item:', 'text_domain' ),
		'all_items'           => __( 'All Slides', 'text_domain' ),
		'add_new_item'        => __( 'Add New Slide', 'text_domain' ),
		'add_new'             => __( 'Add New Slide', 'text_domain' ),
		'new_item'            => __( 'New Slide', 'text_domain' ),
		'edit_item'           => __( 'Edit Slide', 'text_domain' ),
		'update_item'         => __( 'Update Slide', 'text_domain' ),
		'view_item'           => __( 'View Slide', 'text_domain' ),
		'search_items'        => __( 'Search Slides', 'text_domain' ),
		'not_found'           => __( 'Slide Not found', 'text_domain' ),
		'not_found_in_trash'  => __( 'Slide Not found in Trash', 'text_domain' ),
	);
	$args = array(
		'label'               => __( 'eazy_slide', 'text_domain' ),
		'description'         => __( 'Eazy CSS Slide', 'text_domain' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'thumbnail', 'editor' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 100,
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'menu_position'		  => 99, /* this is what order you want it to appear in on the left hand side menu */ 
		'menu_icon' 		  => plugin_dir_url(__FILE__) . 'css/slidericon.png', /* the icon for the custom post type menu */
		'has_archive'         => true,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
		'capability_type'     => 'post',
	);

	register_post_type( 'eazy_slide', $args );

}
// Hook into the 'init' action
add_action( 'init', 'eazy_css_slides', 0 );



// Register Custom Taxonomy
function eazy_css_slider() {
	$labels = array(
		'name'                       => _x( 'Eazy CSS Sliders', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( 'Slider', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Sliders', 'text_domain' ),
		'all_items'                  => __( 'All Sliders', 'text_domain' ),
		'parent_item'                => __( 'Parent Slider', 'text_domain' ),
		'parent_item_colon'          => __( 'Parent Slider:', 'text_domain' ),
		'new_item_name'              => __( 'New Slider Name', 'text_domain' ),
		'add_new_item'               => __( 'Add New Slider', 'text_domain' ),
		'edit_item'                  => __( 'Edit Slider', 'text_domain' ),
		'update_item'                => __( 'Update Slider', 'text_domain' ),
		'view_item'                  => __( 'View Slider', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove sliders', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),

		'search_items'               => __( 'Search Sliders', 'text_domain' ),
		'not_found'                  => __( 'Not Found', 'text_domain' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => false,
	);
	register_taxonomy( 'slider', array( 'eazy_slide' ), $args );
}
// Hook into the 'init' action
add_action( 'init', 'eazy_css_slider', 0 );


// change default text in title 
function custom_enter_title( $input ) {
    global $post_type;
    if( is_admin() && 'Enter title here' == $input && 'eazy_slide' == $post_type )
        return 'Enter Slide Title';
    return $input;
}
add_filter('gettext','custom_enter_title');


// remove featured image metabox & add Eazy CSS Slide Image
function eazy_slider_image_box() {
	remove_meta_box( 'postimagediv', 'eazy_slide', 'side' );
	add_meta_box('postimagediv', __('Add Eazy CSS Slide Image'), 'post_thumbnail_meta_box', 'eazy_slide', 'side', 'default');
}
add_action('do_meta_boxes', 'eazy_slider_image_box');



// change size of admin featured image size in edit screen 
function eazy_slider_image_size( $downsize, $id, $size ) {
if ( ! is_admin() || ! get_current_screen() || 'edit' !== get_current_screen()->parent_base ) {
    return $downsize;
}
remove_filter( 'image_downsize', __FUNCTION__, 10, 3 );

// settings can be thumbnail, medium, large, full 
$image = image_downsize( $id, 'medium' ); 
add_filter( 'image_downsize', __FUNCTION__, 10, 3 );
return $image;
}
add_filter( 'image_downsize', 'eazy_slider_image_size', 10, 3 );


//Add Shortcode
include( 'eazy_css_slider_shortcode.php') ;


//Add CSS
function eazy_css() {
	wp_enqueue_style( 'eazy-css-slider',  plugins_url( 'css/style.css', __FILE__ ) );
}

add_action( 'wp_enqueue_scripts', 'eazy_css' );
