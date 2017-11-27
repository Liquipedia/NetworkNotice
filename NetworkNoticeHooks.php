<?php

class NetworkNoticeHooks {


	private static $styles = [
			"default" => array(
				"bordercolor"=>"default",
				"bgcolor"=>"default",
				"fontcolor"=>"#444444",
							),
			"inverse" => array(
				"bordercolor"=>"inverse",
				"bgcolor"=>"inverse",
				"fontcolor"=>"white",
							),
			"red" => array(
				"bordercolor"=>"#ff0000",
				"bgcolor"=>"#ffcccc",
				"fontcolor"=>"#444444",
							),
			"green" => array(
				"bordercolor"=>"#00ff00",
				"bgcolor"=>"#ccffcc",
				"fontcolor"=>"#444444",
							),
			"blue" => array(
				"bordercolor"=>"#0000ff",
				"bgcolor"=>"#ccccff",
				"fontcolor"=>"#444444",
							),
			"yellow" => array(
				"bordercolor"=>"#ffff00",
				"bgcolor"=>"#ffffcc",
				"fontcolor"=>"#444444",
							),
			"purple" => array(
				"bordercolor"=>"#ff00ff",
				"bgcolor"=>"#ffccff",
				"fontcolor"=>"#444444",
							),
			"turquoise" => array(
				"bordercolor"=>"#00ffff",
				"bgcolor"=>"#ccffff",
				"fontcolor"=>"#444444",
							),
			"light grey" => array(
				"bordercolor"=>"#333333",
				"bgcolor"=>"#cccccc",
				"fontcolor"=>"#444444",
							),
			"dark grey" => array(
				"bordercolor"=>"#000000",
				"bgcolor"=>"#eeeeee",
				"fontcolor"=>"#444444",
							),
	];
	
	public static function onParserFirstCallInit( $parser ) {

	}

	private static function echoNotice( $row ) {


		global $wgOut;
		if ( $row->{'style'} == "default" ){
				echo '<div class="bgc-light bdc-dark" style="margin-top:3px; display:block; text-align:center; padding:5px; margin-bottom:20px; border-left-width:5px border-left-style:solid; color:' . self::$styles[$row->{'style'}]['fontcolor'] . ';">' . $wgOut->parseInline( $row->{'notice_text'} ) . '</div>';
		} else if ( $row->{'style'} == "inverse" ){
				echo '<div class="bgc-dark bdc-light" style="margin-top:3px; display:block; text-align:center; padding:5px; margin-bottom:20px; border-left-width:5px border-left-style:solid; color:' . self::$styles[$row->{'style'}]['fontcolor'] . ';">' . $wgOut->parseInline( $row->{'notice_text'} ) . '</div>';
		} else {
				echo '<div style="background-color:' . self::$styles[$row->{'style'}]['bgcolor'] .  '; margin-top:3px; display:block; text-align:center; padding:5px; margin-bottom:20px; border-left:5px solid ' . self::$styles[$row->{'style'}]['bordercolor']  .  '; color:' . self::$styles[$row->{'style'}]['fontcolor'] . ';">' . $wgOut->parseInline(  $row->{'notice_text'} ) . '</div>';
		}

		//echo '<div style="background-color:' . $row->{'bgcolor'}  .  '; margin-top:10px; border-color:' . $row->{'bordercolor'}  .  '; display:block; text-align:center; padding:5px; margin-bottom:20px; color:#444444; border-left:5px solid ' . $row->{'bordercolor'}  .  '; color:' . $row->{'fontcolor'} . ';">' . $wgOut->parseInline( $row->{'notice_text'} ) . '</div>';

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
			 (`action` = "' . $action . '" OR `action` = "")' );


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