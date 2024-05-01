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
			'network-notice'
		];
		$attributes = [
			'id' => 'network-notice-' . $id,
			'data-id' => $id,
			'class' => implode( ' ', $classes ),
		];

		$iconElement = Html::rawElement(
			'i',
			[
				'class' => 'fa fa-info-circle',
			]
		);

		$iconWrapper = Html::rawElement(
			'div',
			[
				'class' => 'network-notice__content-icon',
			],
			$iconElement
		);

		$closeButtonText = wfMessage( 'networknotice-close-button' )->text();

		$closeButtonIcon = Html::rawElement(
			'i',
			[
				'class' => 'fa fa-times',
			]
		);

		$closeButton = Html::rawElement(
			'div',
			[
				'class' => 'network-notice__close-button',
				'aria-label' => $closeButtonText,
				'data-component' => 'network-notice-close-button',
				'title' => $closeButtonText,
			],
			$closeButtonIcon
		);

		$contentDiv = Html::rawElement(
			'div',
			[
				'class' => 'network-notice__content',
			],
			$iconWrapper . $outputPage->parseInlineAsInterface( $text, false )
		);

		$element = Html::rawElement(
				'div',
				$attributes,
				$contentDiv . $closeButton
		);
		return $element;
	}

}
