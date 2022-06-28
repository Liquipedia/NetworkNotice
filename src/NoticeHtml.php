<?php

namespace Liquipedia\Extension\NetworkNotice;

use Html;

class NoticeHtml {

	/**
	 * Generate the Html for out notice
	 * @param OutputPage $outputPage
	 * @param string $text
	 * @param string $id
	 * @return string Html for our notice
	 */
	public static function getNoticeHTML( $outputPage, $text, $id = '0' ) {
		$classes = [
			'networknotice',
			'networknotice-default',
		];
		$attributes = [
			'id' => 'networknotice-' . $id,
			'data-id' => $id,
			'class' => implode( ' ', $classes ),
		];

		$element = Html::rawElement(
				'div',
				$attributes,
				$outputPage->parseInlineAsInterface( $text, false )
		);
		return $element;
	}

}
