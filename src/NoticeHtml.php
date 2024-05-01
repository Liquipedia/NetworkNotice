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
				'tabindex' => '0',
			],
			$closeButtonIcon
		);

		$contentIconElement = Html::rawElement(
			'i',
			[
				'class' => 'fa fa-info-circle',
			]
		);

		$contentIconWrapper = Html::rawElement(
			'div',
			[
				'class' => 'network-notice__content-icon',
			],
			$contentIconElement
		);

		$contentDiv = Html::rawElement(
			'div',
			[
				'class' => 'network-notice__content',
			],
			$outputPage->parseInlineAsInterface( $text, false )
		);

		$contentWrapper = Html::rawElement(
			'div',
			[
				'class' => 'network-notice__content-wrapper',
			],
			$contentIconWrapper . $contentDiv
		);

		return Html::rawElement(
				'div',
				[
					'id' => 'network-notice-' . $id,
					'data-id' => $id,
					'class' => 'network-notice',
					'data-component' => 'network-notice'
				],
				$contentWrapper . $closeButton
		);
	}

}
