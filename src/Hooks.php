<?php

namespace Liquipedia\NetworkNotice;

class Hooks {

	private static function getNoticeHTML( $out, $row ) {
		return HTML::getNoticeHTML( $out, $row->style, $row->notice_text, $row->notice_id );
	}

	public static function onSiteNoticeAfter( &$siteNotice, $skin ) {
		$config = $skin->getConfig();
		$title = $skin->getTitle();
		$out = $skin->getOutput();

		$dbr = wfGetDB( DB_REPLICA, [], $config->get( 'DBname' ) );

		$categories = $out->getCategories();
		$namespace = \MWNamespace::getCanonicalName( $title->getNamespace() );
		$wiki = substr( $config->get( 'ScriptPath' ), 1 );  // Remove leading '/'
		$pageTitle = $title->getText();
		$action = \Action::getActionName( $skin );

		$movepage = 'Special:MovePage';
		if ( strncmp( $pageTitle, $movepage, strlen( $movepage ) ) === 0 ) {
			$action = 'move';
		} elseif ( $action === 'edit' && !$title->exists() ) {
			$action = 'create';
		}

		// Do wiki and namespace checks in DB query
		$res = $dbr->select( 'networknotice', [ 'notice_text', 'style', 'category', 'prefix', 'notice_id' ], '(`namespace` = "' . $namespace . '" OR `namespace` = "") AND
			 (`wiki` = "' . $wiki . '" OR `wiki` = "") AND
			 (`action` = "' . $action . '" OR `action` = "") AND
			 (`disabled` = 0)' );

		foreach ( $res as $row ) {
			// If prefix doesnt match, go to next row/notice
			if ( strncmp( $pageTitle, $row->prefix, strlen( $row->prefix ) ) ) {
				continue;
			}
			// Finally, check categories
			if ( empty( $row->category ) ) {
				$siteNotice .= self::getNoticeHTML( $out, $row );
			} else {
				foreach ( $categories as $category ) {
					if ( $category === $row->category ) {
						$siteNotice .= self::getNoticeHTML( $out, $row );
						break;
					}
				}
			}
		}
		return true;
	}

	public static function onBeforePageDisplay( $out, $skin ) {
		$out->addModuleStyles( 'ext.networknotice.Notice' );
		return true;
	}

}
