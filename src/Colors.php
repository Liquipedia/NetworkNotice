<?php

namespace Liquipedia\NetworkNotice;

class Colors {

	/**
	 * @var array
	 */
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

	/**
	 * Get the available color options
	 * @return array List of available color options
	 */
	public static function getNoticeColors() {
		return self::$styles;
	}

}
