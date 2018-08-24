<?php

namespace Liquipedia\NetworkNotice;

class Hooks {

	private static function echoNotice( $out, $row ) {
		if ( $row->style == 'default' ) {
			echo '<div class="bgc-light bdc-dark" id="networknotice-' . $row->notice_id . '" style="margin-top:3px; display:block; text-align:center; padding:5px; margin-bottom:20px; border-left-width:5px; border-left-style:solid; color:' . Colors::getNoticeColorValues( $row->style, 'fontcolor' ) . ';">' . $out->parseInline( $row->notice_text, false ) . '</div>';
		} elseif ( $row->style == 'inverse' ) {
			echo '<div class="bgc-dark bdc-light" id="networknotice-' . $row->notice_id . '" style="margin-top:3px; display:block; text-align:center; padding:5px; margin-bottom:20px; border-left-width:5px; border-left-style:solid; color:' . Colors::getNoticeColorValues( $row->style, 'fontcolor' ) . ';">' . $out->parseInline( $row->notice_text, false ) . '</div>';
		} else {
			echo '<div id="networknotice-' . $row->notice_id . '" style="background-color:' . Colors::getNoticeColorValues( $row->style, 'bgcolor' ) . '; margin-top:3px; display:block; text-align:center; padding:5px; margin-bottom:20px; border-left:5px solid ' . Colors::getNoticeColorValues( $row->style, 'bordercolor' ) . '; color:' . Colors::getNoticeColorValues( $row->style, 'fontcolor' ) . ';">' . $out->parseInline( $row->notice_text, false ) . '</div>';
		}
	}

	public static function onBruinenNetworkNotice( $context ) {
		$config = $context->getConfig();
		$title = $context->getTitle();
		$out = $context->getOutput();

		$dbr = wfGetDB( DB_REPLICA, [], $config->get( 'DBname' ) );

		$categories = $out->getCategories();
		$namespace = \MWNamespace::getCanonicalName( $title->getNamespace() );
		$wiki = substr( $config->get( 'ScriptPath' ), 1 );  //Remove leading '/'
		$pagetitle = $title->getText();
		$action = \Action::getActionName( $context );

		$movepage = 'Special:MovePage';
		if ( strncmp( $pagetitle, $movepage, strlen( $movepage ) ) === 0 ) {
			$action = 'move';
		} elseif ( $action == 'edit' && !$title->exists() ) {
			$action = 'create';
		}

		// Do wiki and namespace checks in DB query
		$res = $dbr->select( 'networknotice', [ 'notice_text', 'style', 'category', 'prefix', 'notice_id' ], '(`namespace` = "' . $namespace . '" OR `namespace` = "") AND
			 (`wiki` = "' . $wiki . '" OR `wiki` = "") AND
			 (`action` = "' . $action . '" OR `action` = "") AND
			 (`disabled` = 0)' );

		foreach ( $res as $row ) {
			// If prefix doesnt match, go to next row/notice
			if ( strncmp( $pagetitle, $row->prefix, strlen( $row->prefix ) ) ) {
				continue;
			}
			// Finally, check categories
			if ( $row->category == '' ) {
				self::echoNotice( $out, $row );
			} else {
				foreach ( $categories as $category ) {
					if ( $category == $row->category ) {
						self::echoNotice( $out, $row );
						break;
					}
				}
			}
		}
		return true;
	}

}
