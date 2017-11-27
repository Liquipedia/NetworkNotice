<?php

class SpecialNetworkNotice extends SpecialPage {
	
	function __construct() {
		parent::__construct( 'NetworkNotice', 'usenetworknotice' );
	}

	function getGroupName() {
		return 'liquipedia';
	}


	function createNetworkNotice( $vars ) {
		global $wgDBname;
		$dbr = wfGetDB( DB_MASTER, '', $wgDBname );
		$tablename = 'networknotice';
		$dbr->insert( $tablename, $vars );

	}


	function updateNetworkNotice( $vars, $id ) {
		global $wgDBname;
		$dbr = wfGetDB( DB_MASTER, '', $wgDBname );
		$tablename = 'networknotice';
		$dbr->update( $tablename, $vars, array( 'notice_id' => $id ) );

	}

	function getNetworkNotices() {
		global $wgDBname;
		$dbr = wfGetDB( DB_MASTER, '', $wgDBname);
		$tablename = 'networknotice';
		return $dbr->select( $tablename, array('notice_id', 'label', 'notice_text', 'style', 'wiki', 'category', 'prefix', 'namespace', 'action' ));

	}


	function deleteNetworkNotice( $var ) {
		global $wgDBname;
		$dbr = wfGetDB( DB_MASTER, '', $wgDBname);
		$tablename = 'networknotice';

		return $dbr->delete( $tablename, array( 'notice_id' => $var ) );

	}

	function execute( $par ) {
		if ( !$this->userCanExecute( $this->getUser() ) ) {
			$this->displayRestrictionError();
			return;
		}
		$output = $this->getOutput();
		$this->setHeaders();
		$output->addModuleStyles( 'ext.networknotice.SpecialPage' );
		$request = $this->getRequest();
		$params = explode( '/', $par );

		if ( $params[0] == "delete" && isset( $params[1] ) && !empty( $params[1] ) ) {
			self::deleteNetworkNotice( $params[1] );
		}


		$currentnotices = self::getNetworkNotices();

		global $wgUser;
		global $wgOut;

		$reqNoticeid	= $request->getText( 'noticeid' );
		$reqLabel      	= $request->getText( 'noticelabel' );
		$reqText      	= $request->getText( 'noticetext' );
		$reqStyle		= $request->getText( 'style' );
		$reqNamespace   = $request->getText( 'namespace' );
		$reqWiki 		= $request->getText( 'wiki' );
		$reqCategory 	= $request->getText( 'category' );
		$reqPrefix	 	= $request->getText( 'prefix' );
		$reqAction	 	= $request->getText( 'action' );


		$styles = [
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
		if ( $params[0] == "edit" && isset( $params[1] ) && !empty( $params[1] ) && !$request->getBool( 'createpreviewbutton' ) && !$request->getBool( 'createbutton' ) && !$request->getBool( 'updatebutton' )){

			$output->addHTML( '<h2><span class="mw-headline" id="Create_networknotice">' . $this->msg( 'networknotice-edit-network-notice-heading' )->text() . '</span></h2>' );
			while ( $row = $currentnotices->fetchRow() ){
				if ( $row['notice_id'] ==  $params[1] ){
					$reqNoticeid	= $row['notice_id'];
					$reqLabel      	= $row['label'];
					$reqText      	= $row['notice_text'];
					$reqStyle  		= $row['style']; 
					$reqNamespace   = $row['namespace'];
					$reqWiki 		= $row['wiki'];
					$reqCategory 	= $row['category'];
					$reqPrefix	 	= $row['prefix'];
					$reqAction	 	= $row['action'];
				}
			}
		} else {

			$output->addHTML( '<h2><span class="mw-headline" id="Create_networknotice">' . $this->msg( 'networknotice-create-network-notice-heading' )->text() . '</span></h2>' );
		}




		$output->addHTML( $this->msg( 'networknotice-create-notice-desc' )->parse() );

		$style_html = '';
		foreach ( array_keys( $styles ) as $color ) {
			if ( $color == $reqStyle ) {
				$style_html .= '<option selected="selected" value="' . $color . '">' . $color . '</option>';
			} else {
				$style_html .= '<option value="' . $color . '">' . $color . '</option>';
			}
		}

		$output->addHTML( '<form name="createform" id="createform" method="post" action="#Create_network_notice"> 
<table>' );
		if ( $params[0] == "edit" ){
			$output->addHTML( '
	<tr>
		<td class="input-label"><label for="noticeid">' . $this->msg( 'networknotice-edit-notice-id-label' )->text() . '</label></td>
		<td class="input-container"><input type="text" name="noticeid" id="noticeid" value="' . $reqNoticeid . '" readonly></td>
		<td class="input-helper">' . $this->msg( 'networknotice-edit-notice-id-helper' )->text() . '</td>
	</tr>' );
		}
	$output->addHTML( '
	<tr>
		<td class="input-label"><label for="noticelabel">' . $this->msg( 'networknotice-create-notice-label-label' )->text() . '</label></td>
		<td class="input-container"><input type="text" name="noticelabel" id="noticelabel" value="' . $reqLabel . '"></td>
		<td class="input-helper">' . $this->msg( 'networknotice-create-notice-label-helper' )->text() . '</td>
	</tr>
	<tr>
		<td class="input-label"><label for="noticetext">' . $this->msg( 'networknotice-create-notice-text-label' )->text() . '</label></td>
		<td class="input-container"><textarea name="noticetext" id="noticetext" style="width: 300pt; height: 60pt;">' . $reqText . '</textarea></td>
		<td class="input-helper">' . $this->msg( 'networknotice-create-notice-text-helper' )->text() . '</td>
	</tr>
	<tr>
		<td class="input-label"><label for="style">' . $this->msg( 'networknotice-create-notice-style-label' )->text() . '</label></td>
		<td class="input-container"><select name="style" id="style" style="width: 165pt; ">' . $style_html . '</select></td>
		<td class="input-helper">' . $this->msg( 'networknotice-create-notice-style-helper' )->text() . '</td>
	</tr>
	<tr>
		<td class="input-label"><label for="namespace">' . $this->msg( 'networknotice-create-notice-namespace-label' )->text() . '</label></td>
		<td class="input-container"><input type="text" name="namespace" id="namespace" value="' . $reqNamespace . '"></td>
		<td class="input-helper">' . $this->msg( 'networknotice-create-notice-namespace-helper' )->text() . '</td>
	</tr>
	<tr>
		<td class="input-label"><label for="wiki">' . $this->msg( 'networknotice-create-notice-wiki-label' )->text() . '</label></td>
		<td class="input-container"><input type="text" name="wiki" id="wiki" value="' . $reqWiki . '"></td>
		<td class="input-helper">' . $this->msg( 'networknotice-create-notice-wiki-helper' )->text() . '</td>
	</tr>
	<tr>
		<td class="input-label"><label for="category">' . $this->msg( 'networknotice-create-notice-category-label' )->text() . '</label></td>
		<td class="input-container"><input type="text" name="category" id="category" value="' . $reqCategory . '"></td>
		<td class="input-helper">' . $this->msg( 'networknotice-create-notice-category-helper' )->text() . '</td>
	</tr>
	<tr>
		<td class="input-label"><label for="prefix">' . $this->msg( 'networknotice-create-notice-prefix-label' )->text() . '</label></td>
		<td class="input-container"><input type="text" name="prefix" id="prefix" value="' . $reqPrefix . '"></td>
		<td class="input-helper">' . $this->msg( 'networknotice-create-notice-prefix-helper' )->text() . '</td>
	</tr>
	<tr>
		<td class="input-label"><label for="action">' . $this->msg( 'networknotice-create-notice-action-label' )->text() . '</label></td>
		<td class="input-container"><input type="text" name="action" id="action" value="' . $reqAction . '"></td>
		<td class="input-helper">' . $this->msg( 'networknotice-create-notice-action-helper' )->text() . '</td>
	</tr>
	<tr>
		<td> </td>
		<td colspan="2">');
		if ( $params[0] == "edit" ){
			$output->addHTML( '
			<input type="submit" name="updatebutton" value="' . $this->msg( 'networknotice-create-notice-update-button' )->text() . '"> ');
		} else {
			$output->addHTML( '
			<input type="submit" name="createbutton" value="' . $this->msg( 'networknotice-create-notice-create-button' )->text() . '"> ');
		}
		$output->addHTML( '
			<input type="submit" name="createpreviewbutton" value="' . $this->msg( 'networknotice-create-notice-preview-button' )->text() . '">
		</td>
	</tr>
</table>
</form>
');
	

		if ( $request->getBool( 'createbutton' ) ) {

			$vars = array( 
					'label' => $reqLabel,
					'notice_text' => $reqText,
					'style' => $reqStyle,
					'namespace' => $reqNamespace,
					'wiki' => $reqWiki,
					'category' => $reqCategory,
					'prefix' => str_replace( '_', ' ', $reqPrefix ),
					'action' => $reqAction
				);

			self::createNetworkNotice( $vars );
		} else if ( $request->getBool( 'createpreviewbutton' ) ) {
			$output->addHTML( '<h3>' . $this->msg( 'networknotice-preview-heading' )->text() . '</h3>' );
			if ( $reqStyle == "default" ){
				$output->addHTML('<div class="bgc-light bdc-dark" style="margin-top:3px; display:block; text-align:center; padding:5px; margin-bottom:20px; border-left-width:5px border-left-style:solid;"> <div style="color:' . $styles[$reqStyle]['fontcolor'] . ';">' . $wgOut->parseInline( $reqText ) . '</div></div>' );
			} else if ( $reqStyle == "inverse" ){
				$output->addHTML('<div class="bgc-dark bdc-light" style="margin-top:3px; display:block; text-align:center; padding:5px; margin-bottom:20px; border-left-width:5px border-left-style:solid;"> <div style="color:' . $styles[$reqStyle]['fontcolor'] . ';">' . $wgOut->parseInline( $reqText ) . '</div></div>' );
			} else {
				$output->addHTML('<div style="background-color:' . $styles[$reqStyle]['bgcolor'] .  '; margin-top:3px; display:block; text-align:center; padding:5px; margin-bottom:20px; border-left:5px solid ' . $styles[$reqStyle]['bordercolor']  .  '; color:' . $styles[$reqStyle]['fontcolor'] . ';">' . $wgOut->parseInline( $reqText ) . '</div>' );
			}
		} else if ( $request->getBool( 'updatebutton' ) ) {
				$vars = array( 
					'label' => $reqLabel,
					'notice_text' => $reqText,
					'style' => $reqStyle,
					'namespace' => $reqNamespace,
					'wiki' => $reqWiki,
					'category' => $reqCategory,
					'prefix' => str_replace( '_', ' ', $reqPrefix ),
					'action' => $reqAction
				);
			self::updateNetworkNotice( $vars, $reqNoticeid );
		}





		$output->addHTML( '<h2>' . $this->msg( 'networknotice-all-network-notices-heading' )->text() . '</h2>' );

		$currentnotices = self::getNetworkNotices();

		$table = '{| class="wikitable sortable"' . "\n";
		$table .= "|-\n!" . $this->msg( 'networknotice-column-id-label' )->text() . "\n!" . $this->msg( 'networknotice-column-name-label' )->text() . "\n!" . $this->msg( 'networknotice-column-elements-label' )->text() . "\n!" . $this->msg( 'networknotice-column-edit-label' )->text() . "\n!" . $this->msg( 'networknotice-column-delete-label' )->text() . "\n";
		while( $row = $currentnotices->fetchRow() ) {
			$table .= "|-\n|" . $row['notice_id'] . "\n|" . $row['label'] . 
			"\n|<pre>" . 
			$this->msg( 'networknotice-create-notice-text-label' )->text() . $row['notice_text'] . "\n" .
			$this->msg( 'networknotice-create-notice-style-label' )->text() . $row['style'] . "\n";
			if( $row['wiki'] ) {
 				$table .= $this->msg( 'networknotice-create-notice-wiki-label' )->text() . $row['wiki'] . "\n";
			}
			if( $row['category'] ) {
 				$table .= $this->msg( 'networknotice-create-notice-category-label' )->text() . $row['category'] . "\n";
			}
			if( $row['prefix'] ) {
 				$table .= $this->msg( 'networknotice-create-notice-prefix-label' )->text() . $row['prefix'] . "\n";
			}
			if( $row['namespace'] ) {
 				$table .= $this->msg( 'networknotice-create-notice-namespace-label' )->text() . $row['namespace'] . "\n";
			}
			if( $row['action'] ) {
 				$table .= $this->msg( 'networknotice-create-notice-action-label' )->text() . $row['action'] . "\n";
			}
			$table .= "</pre>\n|[[Special:NetworkNotice/edit/" . $row['notice_id'] . '|edit]]' . "\n|[[Special:NetworkNotice/delete/" . $row['notice_id'] . '|delete]]' . "\n";
		}
		$table .= '|}';
		$output->addWikiText( $table );

	}

}