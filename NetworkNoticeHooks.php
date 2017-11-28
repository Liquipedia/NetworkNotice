<?php

class NetworkNoticeHooks {

	
	public static function onParserFirstCallInit( $parser ) {

	}

	private static function echoNotice( $row ) {


		global $wgOut;
		if ( $row->{'style'} == "default" ){
				echo '<div class="bgc-light bdc-dark" style="margin-top:3px; display:block; text-align:center; padding:5px; margin-bottom:20px; border-left-width:5px; border-left-style:solid; color:' . NetworkNoticeColors::getNoticeColorValues($row->{'style'}, 'fontcolor') . ';">' . $wgOut->parseInline( $row->{'notice_text'} ) . '</div>';
		} else if ( $row->{'style'} == "inverse" ){
				echo '<div class="bgc-dark bdc-light" style="margin-top:3px; display:block; text-align:center; padding:5px; margin-bottom:20px; border-left-width:5px; border-left-style:solid; color:' . NetworkNoticeColors::getNoticeColorValues($row->{'style'}, 'fontcolor') . ';">' . $wgOut->parseInline( $row->{'notice_text'} ) . '</div>';
		} else {
				echo '<div style="background-color:' . NetworkNoticeColors::getNoticeColorValues($row->{'style'}, 'bgcolor') .  '; margin-top:3px; display:block; text-align:center; padding:5px; margin-bottom:20px; border-left:5px solid ' . NetworkNoticeColors::getNoticeColorValues($row->{'style'},'bordercolor')  .  '; color:' . NetworkNoticeColors::getNoticeColorValues($row->{'style'}, 'fontcolor') . ';">' . $wgOut->parseInline(  $row->{'notice_text'} ) . '</div>';
		}

	}

	public static function onLiquiFlowNetworkNotice( $context ) {


		global $wgDBname;
		global $wgOut;
		global $wgScriptPath;
		global $wgTitle;

		$dbr = wfGetDB( DB_REPLICA, '', $wgDBname );
		$tablename = 'networknotice';

		$categories = $wgOut->getCategories();

		$namespace = MWNamespace::getCanonicalName( $wgOut->getTitle()->getNamespace() );

		$wiki = substr( $wgScriptPath, 1 );  //Remove leading '/'

		$pagetitle = $wgTitle->getText();

		$action = Action::getActionName( $context );

		$movepage = "Special:MovePage";
		if ( strncmp( $pagetitle, $movepage, strlen( $movepage ) ) === 0 ) {
			$action = "move";
		} elseif ( $action == "edit" && !$wgTitle->exists() ) {
			$action = "create";
		}

		//do wiki and namespace checks in DB query
		$res = $dbr->select( $tablename, array( 'notice_text', 'style', 'category', 'prefix' ), 
			'(`namespace` = "' . $namespace . '" OR `namespace` = "") AND 
			 (`wiki` = "' . $wiki . '" OR `wiki` = "") AND 
			 (`action` = "' . $action . '" OR `action` = "") AND 
			 (`disabled` = 0)' );


		foreach ( $res as $row ) {
			//If prefix doesnt                                                                                                                         match, go to next row/notice
			if( strncmp( $pagetitle, $row->{'prefix'}, strlen( $row->{'prefix'} ) ) ){ 
				continue;
			}
			//finally, check categories
			if( $row->{'category'} == "" ) {
				self::echoNotice( $row );
			} else {
				foreach ( $categories as $category ) {
					if( $category == $row->{'category'} ) {
						self::echoNotice( $row );
						break;
					}
				}
			}
		}
		return true;

	}

}