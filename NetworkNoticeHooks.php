<?php

class NetworkNoticeHooks {

	private static function fromSiteNotice( $arg ){

		//echo '<div id="main-content" class="mw-body"><div style="background-color:#f2dede; margin-top:3px border-color:#ebccd1; display:block; text-align:center; padding:5px; margin-bottom:20px; color:#a94442; border-left:5px solid #ff0000;">Here is a <a href="https://www.google.ca">link</a>!! </div>'

		//$string = $string . $wgOut->getTitle()->getFullText();
		//$string = $string . $wgOut->getPageTitle();
		//$string = $string . $wgOut->getTitle()->getNamespace();


		$obj = json_decode(substr($arg, 6));//remove 'enable' keyword

		global $wgOut;

		$hasCategory = TRUE;
		if ( $obj->{'category'} ) {
			$hasCategory = FALSE;
			$categories = $wgOut->getCategories();
			foreach ($categories as &$category ){
				if($obj->{'category'} == $category)
					$hasCategory = TRUE;
					break;
			}
		}

		$hasNamespace = TRUE;
		if ( $obj->{'namespace'} ) {
			$hasNamespace = FALSE;
			if ($obj->{'namespace'} == MWNamespace::getCanonicalName($wgOut->getTitle()->getNamespace()) ){
				$hasNamespace = TRUE;
			}
		}

		if (($hasCategory && $hasNamespace) != TRUE)
			return;



		//Check bg color, border color, text for html escapes.  Better to do this on DB upload?  Ask fontax safety standards. if we are already allowing links.. how to protect from bad html?
		//if (preg_match() == 0)
		// return;

		if(preg_match("/[#][0-9a-fA-F]{6}\z/", $obj->{'bgcolor'}, $output_array) != 1){
			return;
		}
		if(preg_match("/[#][0-9a-fA-F]{6}\z/", $obj->{'bordercolor'}, $output_array) != 1){
			return;
		}



	}

	private static function echoNotice( $row )
	{
		echo '<div style="background-color:' . $row->{'bgcolor'}  .  '; margin-top:10px; border-color:' . $row->{'bordercolor'}  .  '; display:block; text-align:center; padding:5px; margin-bottom:20px; color:#444444; border-left:5px solid ' . $row->{'bordercolor'}  .  ';">' . $row->{'notice_text'}  . '</div>';
	}

	private static function wikiCommonName( $arg ){

		/*
		liquipedia-wiki2_ sc2
		liquipedia-wiki_ bw
		liquipedia-wikidota_
		liquipedia-wikics_
		liquipedia-wikihs_
		liquipedia-wikismash_
		liquipedia-wikihots_
		liquipedia-wikiow_
		liquipedia-wikirocket_

		liquipedia-wikiwar_
		liquipedia-wikiwow_
		liquipedia-wikifight_
		liquipedia-wikifortress_
		liquipedia-wikir6_
		liquipedia-wikilol_
		liquipedia-wikiquake_
		liquipedia-wikififa_
		liquipedia-wikitrack_
		liquipedia-wikipubg_
		liquipedia-wikicross_
		liquipedia-wikiclash_
		liquipedia-wikibattle_
		liquipedia-wikipoke_
		liquipedia-wikidia_

		liquipedia-wikicomm_
		*/

	}

	public static function onLiquiFlowNetworkNotice( ){
		

		global $wgDBname;
		global $wgOut;
		global $wgScriptPath;
		global $wgTitle;

		$dbr = wfGetDB( DB_REPLICA, '', $wgDBname);
		$tablename = 'networknotice';
		$id = 8;

		$categories = $wgOut->getCategories();

		$namespace = MWNamespace::getCanonicalName($wgOut->getTitle()->getNamespace());
		//Media .. Special .. Talk .. User .. User_talk .. Project .. Project_talk .. File .. File_talk .. MediaWiki .. MediaWiki_talk .. Template .. Template_talk .. Help .. Help_talk .. Category .. Category_talk

		$wiki = substr($wgScriptPath, 1);  //Remove leading '/'

		$pagetitle = $wgTitle->getFullText();

		//echo $wgTitle->getFullText();

		$action = '';


		//do wiki and namespace checks in DB query
		$res = $dbr->select( $tablename, array('notice_text', 'bgcolor', 'bordercolor', 'wiki', 'category', 'prefix'), '(`namespace` = "' . $namespace . '" OR `namespace` = "") AND (`wiki` = "' . $wiki . '" OR `wiki` = "")');



		foreach ($res as $row){
			//If prefix doesnt match, go to next row/notice
			if(strncmp( $pagetitle, $row->{'prefix'}, strlen($row->{'prefix'}))){
				continue;
			}


			//finally, check categories
			if($row->{'category'} == ""){
				self::echoNotice($row);
			}else{
				foreach ($categories as $category){
					if($category == $row->{'category'}){
						self::echoNotice($row);
						break;
					}
				}
			}
		


		}
		
;

		return true;


	}


}