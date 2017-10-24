NetworkNotice
==============

MediaWiki Extension for Liquipedia

Installation
============
* Extract the extension folder to extensions/HeaderAlert/
* Add the following line to LocalSettings.php:

	`wfLoadExtension( 'NetworkNotice' );`

* Add hook to skin in appropriate place:
	
	`<?php Hooks::run( 'LiquiFlowNetworkNotice'); ?>`

* Add table to DB:

	```CREATE TABLE `networknotice` (
  `notice_id` int(11) NOT NULL,
  `label` tinyblob NOT NULL,
  `wiki` blob NOT NULL,
  `namespace` tinyblob NOT NULL,
  `notice_text` blob NOT NULL,
  `bgcolor` tinyblob NOT NULL,
  `bordercolor` tinyblob NOT NULL,
  `category` blob NOT NULL,
  `prefix` blob NOT NULL,
  `action` blob NOT NULL,
  `temporary` tinyint(1) NOT NULL DEFAULT '0'
)```