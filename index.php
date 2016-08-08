<?php

list( $start, $sec ) = explode( " ", microtime() );

DEFINE( 'DRAGONFLY', true );
DEFINE( 'DEVELOPMENT', true );
DEFINE( 'PUBLISH', false );

require_once( 'dragonfly-engine.php' );

$page = get_dragonfly_page();

$style_time = DEVELOPMENT ? '?' . time() : '';

header('Content-type: text/html; charset=utf-8;');
$str = '<!DOCTYPE html>' . PHP_EOL;
$str .= '<html lang=en>' . PHP_EOL;
$str .= '<head>' . PHP_EOL;
$str .= sprintf( '<title>%s</title>%s', $page['name'], PHP_EOL );
$str .= '<meta http-equiv="Content-Type" content="text/html;charset=utf-8">' . PHP_EOL;
$str .= DEVELOPMENT ? sprintf( '<link rel=stylesheet type="text/css" href="css/style.css%s">%s', $style_time, PHP_EOL ) : '';
$str .= ! DEVELOPMENT ? '<style type="text/css">'. file_get_contents( 'css/style.min.css' ) . '</style>' : '';
$str .= '</head>' . PHP_EOL;
$str .= '<body>' . PHP_EOL;
$str .= '<div id="corral">' . PHP_EOL;
$str .= $page['header'];
$str .= '<div id="frame">' . PHP_EOL;
$str .=  $page['title'];
$str .= '<div id="inner" class="inner hd-absolute">' . PHP_EOL;
$str .= '<article id="article" class="columns">';
$str .= $page['html'];
$str .= '</article>' . PHP_EOL;
$str .= '</div>' . PHP_EOL; //inner
$str .= $page['sidebar'];
$str .= $page['tap-grid'];
$str .= '</div>' . PHP_EOL; //frame
$str .= $page['nav'];
$str .= $page['footer'];
list( $end, $sec ) = explode( " ", microtime() );
$str .= sprintf( '<div id="elapsed-time"><a href="https://github.com/wp-cbos-ca/dragonfly" target="_blank">The "Dragonfly"</a> Elapsed: %sms</div>%s', number_format( ( (float)$end - (float)$start ) * 1000, 2, '.', ',' ) , PHP_EOL );
$str .= '</div>' . PHP_EOL; //corral
$str .= '</body>' . PHP_EOL;
$str .= '</html>';
echo $str;
