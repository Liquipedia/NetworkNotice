<?php

namespace Liquipedia\NetworkNotice;

class HTML {

	public static function getNoticeHTML( $outputPage, $style, $text, $id = '0' ) {
		$extraClasses = '';
		if ( $style === 'default' ) {
			$extraClasses = 'bgc-light bdc-dark ';
		} elseif ( $style === 'inverse' ) {
			$extraClasses = 'bgc-dark bdc-light ';
		}
		return '<div class="' . $extraClasses . 'networknotice networknotice-' . $style . '" id="networknotice-' . $id . '">' . $outputPage->parseInline( $text, false ) . '</div>';
	}

}
