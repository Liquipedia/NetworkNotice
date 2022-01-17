<?php

namespace Liquipedia\Extension\NetworkNotice\Hooks;

class LegacyHooks {

	/**
	 * Callback for LPExtensionMenu hook
	 * @param array &$extensionsMenu List of extension menu entires
	 * @param Skin $skin Skin object for context
	 */
	public static function onLPExtensionMenu( &$extensionsMenu, $skin ) {
		if ( $skin->getUser()->isAllowed( 'usenetworknotice' ) ) {
			$extensionsMenu[ 'networknotice' ] = 'NetworkNotice';
		}
	}

}
