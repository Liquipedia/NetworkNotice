NetworkNotice
==============

MediaWiki Extension for Liquipedia

Installation
============
* Extract the extension folder to extensions/HeaderAlert/
* Add the following line to LocalSettings.php:

	wfLoadExtension( 'NetworkNotice' );

* Add hook to skin in appropriate place:
	
	<?php Hooks::run( 'LiquiFlowNetworkNotice'); ?>
