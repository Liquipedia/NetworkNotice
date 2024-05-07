<?php

namespace Liquipedia\Extension\NetworkNotice\Hooks;

use DatabaseUpdater;
use MediaWiki\Installer\Hook\LoadExtensionSchemaUpdatesHook;
use MediaWiki\MediaWikiServices;

class SchemaHookHandler implements LoadExtensionSchemaUpdatesHook {

	/**
	 * @param DatabaseUpdater $updater
	 */
	public function onLoadExtensionSchemaUpdates( $updater ) {
		$config = MediaWikiServices::getInstance()->getMainConfig();
		$db = $updater->getDB();
		if ( !$db->tableExists( $config->get( 'DBname' ) . '.networknotice', __METHOD__ ) ) {
			$updater->addExtensionTable( 'networknotice', __DIR__ . '/../../sql/networknotice.sql' );
		}
		if ( $db->fieldExists( $config->get( 'DBname' ) . '.networknotice', 'style' ) ) {
			$updater->dropExtensionField( 'networknotice', 'style', __DIR__ . '/../../sql/3_3_0.sql' );
		}
	}

}
