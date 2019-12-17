<?php

namespace Liquipedia\NetworkNotice;

use Action;
use MWNamespace;

class Hooks {

	private static function getNoticeHTML( $out, $row ) {
		return NoticeHtml::getNoticeHTML( $out, $row->style, $row->notice_text, $row->notice_id );
	}

	/**
	 * Add our notices to the SiteNotice return
	 * @param string &$siteNotice
	 * @param Skin $skin
	 * @return bool
	 */
	public static function onSiteNoticeAfter( &$siteNotice, $skin ) {
		$config = $skin->getConfig();
		$title = $skin->getTitle();
		$out = $skin->getOutput();

		$dbr = wfGetDB( DB_REPLICA, [], $config->get( 'DBname' ) );

		// Remove leading '/'
		$wiki = substr( $config->get( 'ScriptPath' ), 1 );
		$categories = $out->getCategories();
		$namespace = MWNamespace::getCanonicalName( $title->getNamespace() );
		$titleText = $title->getText();
		$action = Action::getActionName( $skin );

		if ( $title->isSpecialPage() && $title->isSpecial( 'Movepage' ) ) {
			$action = 'move';
		} elseif ( $action === 'edit' && !$title->exists() ) {
			$action = 'create';
		}

		// Do wiki and namespace checks in DB query
		$res = $dbr->select(
			'networknotice',
			[
				'notice_text',
				'style',
				'category',
				'prefix',
				'notice_id'
			], '(`namespace` = "' . $namespace . '" OR `namespace` = "") AND
			 (`wiki` = "' . $wiki . '" OR `wiki` = "") AND
			 (`action` = "' . $action . '" OR `action` = "") AND
			 (`disabled` = 0)' );

		foreach ( $res as $row ) {
			// If prefix doesnt match, go to next row/notice
			if ( strncmp( $titleText, $row->prefix, strlen( $row->prefix ) ) ) {
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

	/**
	 * Add our CSS files to the page
	 * @param OutputPage $out
	 * @param Skin $skin
	 * @return bool
	 */
	public static function onBeforePageDisplay( $out, $skin ) {
		$out->addModuleStyles( 'ext.networknotice.Notice' );
		return true;
	}

}
