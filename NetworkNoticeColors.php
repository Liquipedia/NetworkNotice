<?php

namespace Liquipedia\NetworkNotice;

class Colors {

	private static $styles = [
		'default' => [
			'bordercolor' => 'default',
			'bgcolor' => 'default',
			'fontcolor' => '#444444',
		],
		'inverse' => [
			'bordercolor' => 'inverse',
			'bgcolor' => 'inverse',
			'fontcolor' => 'white',
		],
		'red' => [
			'bordercolor' => '#ff0000',
			'bgcolor' => '#ffcccc',
			'fontcolor' => '#444444',
		],
		'green' => [
			'bordercolor' => '#00ff00',
			'bgcolor' => '#ccffcc',
			'fontcolor' => '#444444',
		],
		'blue' => [
			'bordercolor' => '#0000ff',
			'bgcolor' => '#ccccff',
			'fontcolor' => '#444444',
		],
		'yellow' => [
			'bordercolor' => '#ffff00',
			'bgcolor' => '#ffffcc',
			'fontcolor' => '#444444',
		],
		'purple' => [
			'bordercolor' => '#ff00ff',
			'bgcolor' => '#ffccff',
			'fontcolor' => '#444444',
		],
		'turquoise' => [
			'bordercolor' => '#00ffff',
			'bgcolor' => '#ccffff',
			'fontcolor' => '#444444',
		],
		'light grey' => [
			'bordercolor' => '#333333',
			'bgcolor' => '#cccccc',
			'fontcolor' => '#444444',
		],
		'dark grey' => [
			'bordercolor' => '#000000',
			'bgcolor' => '#eeeeee',
			'fontcolor' => '#444444',
		],
	];

	public static function getNoticeColorValues( $colorname, $entity = null ) {
		if ( $entity == null && isset( self::$styles[ $colorname ] ) ) {
			return self::$colors[ $wiki ];
		} elseif ( $entity == null ) {
			return self::$styles[ 'default' ];
		} elseif ( isset( self::$styles[ $colorname ] ) ) {
			return self::$styles[ $colorname ][ $entity ];
		} else {
			return self::$styles[ 'default' ][ $entity ];
		}
	}

	public static function getNoticeColors() {
		return array_keys( self::$styles );
	}

}
