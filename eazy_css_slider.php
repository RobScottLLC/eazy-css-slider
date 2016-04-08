<?php 
/*
Plugin Name: Eazy CSS Slider
Plugin URI: http://robjscott.com/wordpress/
Description:  Adds slide custom post type for uploading slides. Slides can be added to slider using shortcodes. 
Version: 1.1.0
Author: Rob Scott, LLC
Author URI: http://robjscott.com
License: GPLv2
Copyright: Rob Scott, LLC
*/

// Register Custom Post Type
function eazy_css_slides() {

	$labels = array(
		'name'                => _x( 'Eazy CSS Slides', 'Post Type General Name', 'eazy_css_slider' ),
		'singular_name'       => _x( 'Eazy CSS Slide', 'Post Type Singular Name', 'eazy_css_slider' ),
		'menu_name'           => __( 'Eazy CSS Slider', 'eazy_css_slider' ),
		'name_admin_bar'      => __( 'Eazy CSS Slide', 'eazy_css_slider' ),
		'parent_item_colon'   => __( 'Parent Item:', 'eazy_css_slider' ),
		'all_items'           => __( 'All Slides', 'eazy_css_slider' ),
		'add_new_item'        => __( 'Add New Slide', 'eazy_css_slider' ),
		'add_new'             => __( 'Add New Slide', 'eazy_css_slider' ),
		'new_item'            => __( 'New Slide', 'eazy_css_slider' ),
		'edit_item'           => __( 'Edit Slide', 'eazy_css_slider' ),
		'update_item'         => __( 'Update Slide', 'eazy_css_slider' ),
		'view_item'           => __( 'View Slide', 'eazy_css_slider' ),
		'search_items'        => __( 'Search Slides', 'eazy_css_slider' ),
		'not_found'           => __( 'Slide Not found', 'eazy_css_slider' ),
		'not_found_in_trash'  => __( 'Slide Not found in Trash', 'eazy_css_slider' ),
	);
	$args = array(
		'label'               => __( 'eazy_css_slide', 'eazy_css_slider' ),
		'description'         => __( 'Eazy CSS Slide', 'eazy_css_slider' ),
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

	register_post_type( 'eazy_css_slide', $args );

}
// Hook into the 'init' action
add_action( 'init', 'eazy_css_slides', 0 );



// Register Custom Taxonomy
function eazy_css_slider() {
	$labels = array(
		'name'                       => _x( 'Eazy CSS Sliders', 'Taxonomy General Name', 'eazy_css_slider' ),
		'singular_name'              => _x( 'Slider', 'Taxonomy Singular Name', 'eazy_css_slider' ),
		'menu_name'                  => __( 'Sliders', 'eazy_css_slider' ),
		'all_items'                  => __( 'All Sliders', 'eazy_css_slider' ),
		'parent_item'                => __( 'Parent Slider', 'eazy_css_slider' ),
		'parent_item_colon'          => __( 'Parent Slider:', 'eazy_css_slider' ),
		'new_item_name'              => __( 'New Slider Name', 'eazy_css_slider' ),
		'add_new_item'               => __( 'Add New Slider', 'eazy_css_slider' ),
		'edit_item'                  => __( 'Edit Slider', 'eazy_css_slider' ),
		'update_item'                => __( 'Update Slider', 'eazy_css_slider' ),
		'view_item'                  => __( 'View Slider', 'eazy_css_slider' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'eazy_css_slider' ),
		'add_or_remove_items'        => __( 'Add or remove sliders', 'eazy_css_slider' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'eazy_css_slider' ),

		'search_items'               => __( 'Search Sliders', 'eazy_css_slider' ),
		'not_found'                  => __( 'Not Found', 'eazy_css_slider' ),
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
	register_taxonomy( 'eazy_css_slider', array( 'eazy_css_slide' ), $args );
}
// Hook into the 'init' action
add_action( 'init', 'eazy_css_slider', 0 );


// change default text in title 
function custom_eazy_css_title( $input ) {
    global $post_type;
    if( is_admin() && 'Enter title here' == $input && 'eazy_css_slide' == $post_type )
        return 'Enter Slide Title';
    return $input;
}
add_filter('gettext','custom_eazy_css_title');


// remove featured image metabox & add Eazy CSS Slide Image
function eazy_css_slider_image_box() {
	remove_meta_box( 'postimagediv', 'eazy_css_slide', 'side' );
	add_meta_box('postimagediv', __('Add Eazy CSS Slide Image'), 'post_thumbnail_meta_box', 'eazy_css_slide', 'side', 'default');
}
add_action('do_meta_boxes', 'eazy_css_slider_image_box');



// change size of admin featured image size in edit screen 
function eazy_css_slider_image_size( $downsize, $id, $size ) {
if ( ! is_admin() || ! get_current_screen() || 'edit' !== get_current_screen()->parent_base ) {
    return $downsize;
}
remove_filter( 'image_downsize', __FUNCTION__, 10, 3 );

// settings can be thumbnail, medium, large, full 
$image = image_downsize( $id, 'medium' ); 
add_filter( 'image_downsize', __FUNCTION__, 10, 3 );
return $image;
}
add_filter( 'image_downsize', 'eazy_css_slider_image_size', 10, 3 );


//Add Shortcode
include( 'eazy_css_slider_shortcode.php') ;


//Add CSS
function eazy_css_slider_style() {
	wp_enqueue_style( 'eazy-css-slider',  plugins_url( 'css/style.css', __FILE__ ) );
}

add_action( 'wp_enqueue_scripts', 'eazy_css_slider_style' );
