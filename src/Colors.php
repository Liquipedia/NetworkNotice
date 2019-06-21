<?php

namespace Liquipedia\NetworkNotice;

class Colors {

	private static $styles = [
		'default',
		'inverse',
		'red',
		'green',
		'blue',
		'yellow',
		'purple',
		'turquoise',
		'lightgrey',
		'darkgrey',
	];

	public static function getNoticeColors() {
		return self::$styles;
	}

}
