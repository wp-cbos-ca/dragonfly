<?php

defined( 'DRAGONFLY' ) || die();

function get_dragonfly_site_data(){
	$items = array(
		'title' => 'Dragonfly',
		'description' => 'The name says it all.',
	);
	return $items;
}

function get_dragonfly_header_data(){
	$items = array(
			'header' => 0,
			'tablet' => 1,
			'mobile' => 1,
	);
	return $items;
}

function get_page_layout_data(){
	$items = array(
		'sidebar' => 1,
		'mobile-header-show' => 0,
		'tablet-header-show' => 0,
		'sidebar-overlay' => 1,
	);
	return $items;
}

function get_dragonfly_sidebar_data(){
	$items = array(
			'sidebar' => 1,
			'title' => 0,
			'left' => 0,
			'bottom' => 1,
			'overlay' => 1,
			'button' => 1,
			'slide' => 0,
	);
	return $items;
}

function get_dragonfly_footer_data(){
	$items = array(
		'footer' => 1,
		'tablet' => 1,
		'mobile' => 1,
	);
	return $items;
}

function get_dragonfly_static_menu_data(){
	$items = array(
		'static' => 0,
		'item-1' => 1,
		'item-2' => 1,
		'item-3' => 1,
		'item-4' => 1,
	);
	return $items;
}

function get_dragonfly_image_data(){
	$items = array(
		'width' => '1600',
		'height' => '900',
		'ext' => 'png',
	);
	return $items;
}

function get_no_caps_data(){
	return array( 'the', 'in', 'at', 'is', 'by' );
}

function get_dragonfly_directory_data(){
	$items = array( 
		'uploads' => 'uploads', 
		'html' => 'html',
	);
	return $items;
}

function get_dragonfly_tap_grid_data(){
	$items = array(
			'home' => array( 'name' => 'Home', 'value' => '/' ),
			'phone' => array( 'name' => 'Phone', 'value' => '(123) 456-7890' ),
			'mail' => array( 'name' =>  'Email', 'value' => 'joe@example.ca' ),
	);
	return $items;
}

function get_dragonfly_share_data(){
	$items = array( 'name' => 'Share', 
			'value' => array(
				array( 'name' => 'LinkedIn', 'stub' => 'linkedin', 'url' => 'joe' ),
				array( 'name' => 'Facebook',  'stub' => 'facebook', 'url' => '0' ),
				array( 'name' => 'Google+',  'stub' => 'googleplus', 'url' => '#' ),
				array( 'name' => 'Twitter',  'stub' => 'twitter', 'url' => '#' ),
				)
			);
	return $items;
}
