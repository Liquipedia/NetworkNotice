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

	function getNetworkNotices() {
		global $wgDBname;
		$dbr = wfGetDB( DB_MASTER, '', $wgDBname);
		$tablename = 'networknotice';
		return $dbr->select( $tablename, array('notice_id', 'label'));

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

		global $wgUser;
		global $wgOut;

		$output->addHTML( '<h2><span class="mw-headline" id="Create_networknotice">' . $this->msg( 'networknotice-create-network-notice-heading' )->text() . '</span></h2>' );
		$output->addHTML( $this->msg( 'networknotice-create-notice-desc' )->parse() );

		$reqLabel      	= $request->getText( 'noticelabel' );
		$reqText      	= $request->getText( 'noticetext' );
		$reqBgcolor  	= $request->getText( 'bgcolor' );
		$reqBordercolor = $request->getText( 'bordercolor' );
		$reqFontcolor 	= $request->getText( 'fontcolor' );
		$reqNamespace   = $request->getText( 'namespace' );
		$reqWiki 		= $request->getText( 'wiki' );
		$reqCategory 	= $request->getText( 'category' );
		$reqPrefix	 	= $request->getText( 'prefix' );
		$reqAction	 	= $request->getText( 'action' );
		$reqTemporary 	= $request->getBool( 'temporary' );
		$reqId			= $request->getText( 'noticedelete' );


		$bordercolors	= [
						"red" => "#ff0000",
						"green" => "#00ff00",
						"blue" => "#0000ff",
						"yellow" => "#ffff00",
						"purple" => "#ff00ff",
						"turquoise" => "#00ffff",
						"dark grey" => "#333333",
						"black" => "#000000"
		];
		$bgcolors	= [
						"light red" => "#ffcccc",
						"light green" => "#ccffcc",
						"light blue" => "#ccccff",
						"light yellow" => "#ffffcc",
						"light purple" => "#ffccff",
						"light turquoise" => "#ccffff",
						"grey" => "#cccccc",
						"light grey" => "#eeeeee"
		];


		$bordercolor_html = '';
		foreach ( array_keys( $bordercolors ) as $color ) {
			if( $bordercolors[$color] === $reqBordercolor ) {
				$bordercolor_html .= '<option selected="selected" value="' . $bordercolors[$color] . '">' . $color . ' <' . $bordercolors[$color] . '></option>';
			} else {
				$bordercolor_html .= '<option value="' . $bordercolors[$color] . '">' . $color . ' <' . $bordercolors[$color] . '></option>';
			}
		}

		$bgcolor_html = '';
		foreach ( array_keys( $bgcolors ) as $color ) {
			if( $bgcolors[$color] === $reqBgcolor ) {
				$bgcolor_html .= '<option selected="selected" value="' . $bgcolors[$color] . '">' . $color . ' <' . $bgcolors[$color] . '></option>';
			} else {
				$bgcolor_html .= '<option value="' . $bgcolors[$color] . '">' . $color . ' <' . $bgcolors[$color] . '></option>';
			}
		}


		$output->addHTML( '<form name="createform" id="createform" method="post" action="#Create_network_notice">
<table>
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
		<td class="input-label"><label for="bgcolor">' . $this->msg( 'networknotice-create-notice-bgcolor-label' )->text() . '</label></td>
		<td class="input-container"><select name="bgcolor" id="bgcolor" style="width: 165pt; ">' . $bgcolor_html . '</select></td>
		<td class="input-helper">' . $this->msg( 'networknotice-create-notice-bgcolor-helper' )->text() . '</td>
	</tr>
	<tr>
		<td class="input-label"><label for="bordercolor">' . $this->msg( 'networknotice-create-notice-bordercolor-label' )->text() . '</label></td>
		<td class="input-container"><select name="bordercolor" id="bordercolor" style="width: 165pt; ">' . $bordercolor_html . '</select></td>
		<td class="input-helper">' . $this->msg( 'networknotice-create-notice-bordercolor-helper' )->text() . '</td>
	</tr>
	<tr>
		<td class="input-label"><label for="fontcolor">' . $this->msg( 'networknotice-create-notice-fontcolor-label' )->text() . '</label></td>
		<td class="input-container"><input type="text" name="fontcolor" id="fontcolor" value="' . $reqFontcolor . '"></td>
		<td class="input-helper">' . $this->msg( 'networknotice-create-notice-fontcolor-helper' )->text() . '</td>
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
		<td colspan="2">
			<input type="submit" name="createbutton" value="' . $this->msg( 'networknotice-create-notice-create-button' )->text() . '"> 
			<input type="submit" name="createpreviewbutton" value="' . $this->msg( 'networknotice-create-notice-preview-button' )->text() . '">
		</td>
	</tr>
</table>
</form>' );


		if ( $request->getBool( 'createbutton' ) ) {

			$vars = array( 
					'label' => $reqLabel,
					'notice_text' => $reqText,
					'bgcolor' => $reqBgcolor,
					'bordercolor' => $reqBordercolor,
					'fontcolor' => $reqFontcolor,
					'namespace' => $reqNamespace,
					'wiki' => $reqWiki,
					'category' => $reqCategory,
					'temporary' => $reqTemporary,
					'prefix' => str_replace( '_', ' ', $reqPrefix ),
					'action' => $reqAction
				);

			self::createNetworkNotice( $vars );
		}
		if ( $request->getBool( 'createpreviewbutton' ) ) {
			$output->addHTML( '<h3>' . $this->msg( 'networknotice-preview-heading' )->text() . '</h3>' );
			$output->addHTML('<div style="background-color:' . $reqBgcolor .  '; margin-top:3px border-color:' . $reqBordercolor .  '; display:block; text-align:center; padding:5px; margin-bottom:20px; color:#444444; border-left:5px solid ' . $reqBordercolor  .  '; color:' . $reqFontcolor . ';">' . $wgOut->parseInline( $reqText ) . '</div>' );
		}

		$output->addHTML( '<h2><span class="mw-headline" id="Create_networknotice">' . $this->msg( 'networknotice-delete-network-notice-heading' )->text() . '</span></h2>' );


		if ( $request->getBool( 'deletebutton' ) ) {

			self::deleteNetworkNotice( $reqId );

		}
		$currentnotices = self::getNetworkNotices();


		$temp_html = '';
		foreach ( $currentnotices as $notice ) {
			$temp_html .= '<option value="' . $notice->{'notice_id'} . '">' . $notice->{'label'} . '</option>';
		}



		$output->addHTML( '<form name="deleteform" id="deleteform" method="post" action="#Delete_network_notice">
<table>
	<tr>
		<td class="input-label"><label for="noticedelete">' . $this->msg( 'networknotice-delete-notice-text-label' )->text() . '</label></td>
		<td class="input-container"><select name="noticedelete" id="noticedelete" style="width: 300pt; ">' . $temp_html . '</select></td>
	</tr>
	<tr>
		<td> </td>
		<td colspan="2">
			<input type="submit" name="deletebutton" value="' . $this->msg( 'networknotice-delete-notice-delete-button' )->text() . '"> 
		</td>
	</tr>
</table>
</form>');
		
		if ( $request->getBool( 'deleteviewbutton' ) ) {
			$output->addHTML( '<h3>' . $this->msg( 'networknotice-preview-heading' )->text() . '</h3>' );
			$output->addHTML( '<div style="background-color:' . $reqBgcolor .  '; margin-top:3px border-color:' . $reqBordercolor .  '; display:block; text-align:center; padding:5px; margin-bottom:20px; color:#444444; border-left:5px solid ' . $reqBordercolor  .  ';">' . $reqText  . '</div>' );
		}
		

	}

}