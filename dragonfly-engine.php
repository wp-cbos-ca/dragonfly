<?php

defined( 'DRAGONFLY' ) || die();

function get_dragonfly_page () {
	$clean_url = false;
	$page_type = false;
	$page = false;
	$url = substr( $_SERVER['REQUEST_URI'], 1, 65 );
	if ( empty ( $url ) ){	
		$page_type = 'home';
	}
	else if ( substr( $url, -5 ) == '.html' ) {
		preg_match( '/([a-zA-Z0-9\-]+?\.html$)/', $url, $filtered );
		preg_match( '/(^[0-9]{8})/', $url, $uid );
		$page['uid'] = $uid[0];
		if ( ! $uid[0] ) {
			$page_type = 'static';			
		} else {
			$page_type = 'dynamic';
		}	
		$clean_url = $filtered[0];
	}
	else {
			
	}
	$page = get_dragonfly_page_detail( $clean_url, $page_type );
	return $page;
}

function get_dragonfly_page_detail( $clean_url, $page_type ){
	require_once( 'dragonfly-data.php' );
	$page['dirs'] = get_dragonfly_directory_data();
	$page['layout'] = get_page_layout_data();
	$items = get_dragonfly_files( $page );
	$page['url'] = $clean_url;
	$page['home'] = $page_type == 'home' ? true: false;
	$page['file'] = $page['home'] ? $items[0] : $clean_url;
	$page['header'] = get_dragonfly_header( $page );
	$file = $page['dirs']['html'] . '/' . $page['file'];
	if ( file_exists ( $file ) ) {
		$page['dir'] = $file;
		$page['stub'] = str_replace( '.html', '', $page['file'] );
		preg_match( '/(^[0-9]{8})/', $page['file'], $uid );
		$page['uid'] = $uid[0];
		$page['html'] = get_dragonfly_html( $page );
		$page['class'] = get_dragonfly_class( $page );
		$page['name'] = get_dragonfly_name( $page['file'] );
		$page['title'] = get_dragonfly_page_title( $page );
		$page['nav'] = get_dragonfly_nav( $page, $items );
		$page['sidebar'] = get_dragonfly_sidebar( $page, $items );
		$page['footer'] = get_dragonfly_footer( $page, $items );
		return $page;
	}
	else {
		return $page;
	}
}

function get_dragonfly_files( $page ){
	$handle = dirname(__FILE__) . '/' . $page['dirs']['html'] . '/';
	foreach (glob( $handle . "*.html" ) as $file ) {
		$arr[] = substr( strrchr( $file, '/' ), 1 );
	}
	return $arr;
}

function get_dragonfly_file( $page, $items ){
	$search = $page['url'];
	$file =	$page['home'] ? $items[0] : $items[ array_search( $search , $items ) ];
	return $file;
}

function get_dragonfly_page_url( $page ){
	if ( $page['stub'] ){
		$str = $page['stub'] . '.html';
		return $str;	
	}
	else {
		return false;
	}
}

function get_dragonfly_html( $page ){
	return file_get_contents( $page['dir'] );
}

function get_html_stub( $page ){
	return $page['dirs']['uploads'] . '/' . $page['dirs']['html'] . '/' . $page['stub'] . '.html';
}

function get_dragonfly_class( $page ){
	return 'resize';
}

function get_dragonfly_nav( $page, $items ) {
	$str = '';
	$lr = get_dragonfly_lr( $page, $items );
	if ( $lr['left'] ) {
		$lr['left'] = $lr['left'] == $items[0] ? '/' : $lr['left']; 
		$str .= '<nav class="left-middle">' . PHP_EOL;
		$str .= sprintf( '<a href="%s" title="Prev"><span class="align-float-left mid-circle hover">&laquo;</span></a>', $lr['left'], PHP_EOL );
		$str .= '</nav>' . PHP_EOL;
	}
	if ( $lr['right'] ) {
		$str .= '<nav class="right-middle">' . PHP_EOL;
		$str .= sprintf( '<a href="%s" title="Next"><span class="align-float-right mid-circle hover">&raquo;</span></a>', $lr['right'] , PHP_EOL );
		$str .= '</nav>' . PHP_EOL;
	}
	return $str;
}

function get_dragonfly_lr( $page, $items ){
	$key = array_search ( $page['file'], $items );
	if ( isset ( $items[ $key - 1 ] )  ) {
		if ( $key - 1 == 0 ) {
			$lr['left'] = '/';
		}
		else {
			$lr['left'] = $items[ $key - 1 ];
		}
	}
	if ( isset ( $items[ $key + 1 ] )  ) {
		$lr['right'] = $items[ $key + 1 ];
	}
	return $lr;
}

function get_paging_buttons_numbered( $page, $items ){
	$str = '<span class="align-absolute-center">';
	if ( ! empty ( $items ) ){
		foreach ( $items as $k => $item ) {
		$str .= sprintf('<span class="tap-button"><a href="%s" title="%s">%s</a></span>%s', $item, get_dragonfly_name( $item ), $k, PHP_EOL );		
		}
	$str .= '</span>';
	return $str;
	}
}

function get_paging_buttons_named( $page, $items ){
	$str = '';
	$max = 100;
	if ( ! empty ( $items ) ){
		foreach ( $items as $k => $item ) {
			if ( $k < $max ) {
				$str .= sprintf('<span class="tap-button"><a href="%s" title="%s">%s</a></span>%s', $item, get_dragonfly_name( $item ), get_dragonfly_name( $item ), PHP_EOL );
			}
			else {
				break;
			}
		}
		return $str;
	}
}

function get_dragonfly_header( $page ){
	$header = get_dragonfly_header_data();
	if ( $header['header'] ) {
	$str = '<header id="site-header">' . PHP_EOL;
	$str .= '<div class="inner">' . PHP_EOL;
	$str .= '</div>' . PHP_EOL;
	$str .= '</header>' . PHP_EOL;
	return $str;
	} else {
		return false;
	}
}

function get_dragonfly_sidebar( $page, $items ){
	$site = get_dragonfly_site_data();
	$sidebar = get_dragonfly_sidebar_data();
	if ( $sidebar['sidebar'] ) {
		$horizontal = $sidebar['left'] ? ' left' : ' right';
		$vertical = $sidebar['bottom'] ? ' bottom' : ' top';
		$overlay = $sidebar['overlay'] ? ' overlay' : ' beside';
		$str .= '<section id="menu" class="menu">' . PHP_EOL;
		$str .= sprintf( '<div class="sidebar hide position-absolute%s%s%s">%s', $horizontal, $vertical, $overlay, PHP_EOL );
		$str .= $sidebar['title'] ? sprintf( '<h1 class="negative-top-margin centered">%s</h1>%s', $site['title'], PHP_EOL ) : '';
		$str .= '<div class="inner">' . PHP_EOL;
		$str .= get_paging_buttons_named( $page, $items );
		$str .= '</div>' . PHP_EOL; //inner
		$str .= '</div>' . PHP_EOL; //default hide
		$str .= get_dragonfly_tap_grid( $page );
		$str .= '<a href="#menu" class="open-menu">' . PHP_EOL;
		$str .= sprintf( '<section id="handle" class="handle%s%s tap-button button gradient" title="Click to show pages">', $horizontal, $vertical );
		$str .= '<span class="rotate-90 opaque inline-block">III</span>';
		$str .= '</section>' . PHP_EOL;
		$str .= '</a>' . PHP_EOL;
		$str .= '<a href="#" class="close-menu">' . PHP_EOL;
		$str .= sprintf( '<section id="handle" class="handle%s%s tap-button button gradient" title="Click to show pages">', $horizontal, $vertical );
		$str .= '<span class="rotate-90 opaque inline-block">III</span>';
		$str .= '</section>' . PHP_EOL;
		$str .= '</a>' . PHP_EOL;
		$str .= '</section>' . PHP_EOL; //menu
		
		return $str;
	} else {
		return false;
	}
}

function get_dragonfly_static_menu_items( $page ) {
	$items = get_dragonfly_static_menu_data();
	if( $items['static'] ){
		$str = '<span id="static-menu" class="">';
		if ( ! empty ( $items )){
			$cnt = 0;
			foreach ( $items as $key => $item ){
				if ( $cnt > 0 ) {
					$file = $cnt == 0 ? '/' : $key . '.html';
					$str .= sprintf('<span class="tap-button"><a href="%s" title="%s">%s</a></span>%s', $file , get_name_from_stub( $key ), get_name_from_stub( $key ), PHP_EOL );
				}
				$cnt++;
			}
			$str .= '</span>';
			return $str;
			}
			else {
				return false;
			}
	}		
	else {
		return false;
	}
}

function get_dragonfly_footer( $page, $items ){
	$footer = get_dragonfly_footer_data();
	if ( $footer['footer'] ){
		$str = '<footer id="site-footer">' . PHP_EOL;
		$str .= '<div class="inner">' . PHP_EOL;
		$str .= '<span class="align-absolute-center">';
		$str .= get_dragonfly_static_menu_items( $page );
		$str .= '</span>';
		$str .= '</div>' . PHP_EOL;
		$str .= '</footer>' . PHP_EOL;
		return $str;
	}
	else {
		return false;
	}
}

function get_dragonfly_name ( $file ) {
	$fname = substr( $file, 0, count( $file ) - 6 );
	$ex2 = explode ( "-", $fname );
	$cnt = count ( $ex2 );
	$i = 0;
	$str = "";
	$caps = get_no_caps_data();
	if ( ! empty ( $ex2 ) ) foreach ( $ex2 as $item ) {
		//if ( $i !== 0 ) {
			if ( ! in_array( $item, $caps ) || $i == 1 ) {
				$str .= ucfirst ( $item );
			}
			else {
				$str .= $item;
			}
		//}
		if ( $i < $cnt ) {
			$str .= " ";
		}
			
		$i++;
	}
	return trim( $str );	
}

function get_name_from_stub ( $stub ) {
	$ex = explode ( "-", $stub );
	$cnt = count ( $ex );
	$i = 0;
	$str = "";
	$caps = get_no_caps_data();
	if ( ! empty ( $ex ) ) foreach ( $ex as $item ) {
		if ( ! in_array( $item, $caps ) || $i == 1 ) {
			$str .= ucfirst ( $item );
		}
		else {
			$str .= $item;
		}
		if ( $i < $cnt ) {
			$str .= " ";
		}
		$i++;
	}
	return trim( $str );
}

function get_dragonfly_page_title ( $page ) {
	if ( empty( $page['url'] ) ){
		$title = sprintf( '<h4 id="page-title" class="title negative-top-margin">%s</h4>', $page['name'] );
		return $title;
	}
	else {
		$title = sprintf( '<h4 id="page-title" class="title negative-top-margin"><a href="%s">%s</a></h4>', $page['url'], $page['name'] );
		return $title;
	}
}

function get_site_title(){
	global $page;
	$site = get_dragonfly_site_data();
	$str = sprintf( '%s%s%s', $site['title'], ' | ', $site['description'] );
	return $str;
}

function get_dragonfly_tap_grid( $page ) {
	$items = get_dragonfly_tap_grid_data();
	$share = get_dragonfly_share_data();
	$str = '';
	$str .= sprintf( '<div id="tap-grid" class="tap-grid hide position-absolute bottom-right">%s', PHP_EOL );
	$str .= '<div class="inner">' . PHP_EOL;
	$str .= sprintf( '<a href="%s"><div id="home" class="unit size1of4" title="%s"><div class="inner">%s</div></div></a>%s', $items['home']['value'], $items['home']['name'], $items['home']['name'],  PHP_EOL );
	$str .= sprintf( '<a href="#phone" class="unit size1of4" title="%s"><div class="inner">%s</div></a>%s', $items['phone']['name'], $items['phone']['name'], PHP_EOL );
	$str .= sprintf( '<a href="#mail" class="unit size1of4" title="%s"><div class="inner">%s</div></a>%s', $items['mail']['name'], $items['mail']['name'], PHP_EOL );
	$str .= sprintf( '<a href="#share" class="unit size1of4" title="%s"><div class="inner">%s</div></a>%s', $share['name'], $share['name'], PHP_EOL );
	$str .= '</div>' . PHP_EOL;
	$str .= '</div>' . PHP_EOL;
	$str .= get_dragonfly_tap_grid_items( $page );
	return $str;
}

function get_dragonfly_tap_grid_items( $page ) {
	$items = get_dragonfly_tap_grid_data();
	$share = get_dragonfly_share_data();
	if (! empty ( $items ) ) {
		$str = '';
		$str .= sprintf( '<div id="%s" class="fixed-center hide">%s<a href="#" class="close-x">X</a></div>%s', 'phone', $items['phone']['value'], PHP_EOL );
		$str .= sprintf( '<div id="%s" class="fixed-center hide">%s<a href="#" class="close-x">X</a></div>%s', 'mail', $items['mail']['value'], PHP_EOL );
		$str .= get_dragonfly_share_items( 'share', $share['value'] );
		return $str;		
	} else {
		return false;
	}
}

function get_dragonfly_share_items( $name, $items ){
	if ( ! empty( $items ) ) {
		$str = sprintf( '<div id="%s" class="fixed-center hide"><a href="#" class="close-x">X</a>%s', $name , PHP_EOL );
		foreach ( $items as $item ){
			if ( $item['url'] != '0' ) {
				$str .= sprintf( '<a href="%s" target="_blank" title="%s"><span class="words">%s</span></a>%s', $item['url'], $item['url'], $item['name'],  PHP_EOL );
			}
		}
		$str .= '</div>' . PHP_EOL;
		return $str;
	}
}
