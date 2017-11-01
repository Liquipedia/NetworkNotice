NetworkNotice
==============

MediaWiki Extension for Liquipedia

Installation
============
* Extract the extension folder to extensions/NetworkNotice/
* Add the following line to LocalSettings.php:

	`wfLoadExtension( 'NetworkNotice' );`

* Add hook to skin in appropriate place:
	
	`<?php Hooks::run( 'LiquiFlowNetworkNotice' ); ?>`

* Add table to DB (can import from networknotice.sql).
