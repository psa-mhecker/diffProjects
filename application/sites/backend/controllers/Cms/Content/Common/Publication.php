<?php

class Cms_Content_Common_Publication extends Cms_Content_Module {

	public static function render(Pelican_Controller $controller) {
		$return = $controller->oForm->inputTaxonomy ( "TAXONOMY", t ( 'Taxonomy' ), "/_/Taxonomy/suggest", $controller->values ["CONTENT_ID"], 1 );

		$return .= $controller->oForm->showSeparator ();

		if (empty ( $controller->values ["CONTENT_START_DATE"] )) {
			$controller->values ["CONTENT_START_DATE"] = date ( t ( 'DATE_FORMAT_PHP' ) );
		}
        if (!in_array($_GET['uid'], array(Pelican::$config['CONTENT_TYPE_ID']['ACTUALITE']))) {
            //$return .= $controller->oForm->createInput ( "CONTENT_PUBLICATION_DATE", t ( 'Displayed publication date' ), 10, "date", false, str_replace ( ' 00:00', '', $controller->values ["CONTENT_PUBLICATION_DATE"] ), $controller->readO, 10, false );
            $return .= '<tr><td class="formVal">'.t ( 'Displayed publication date' ).'</td><td class="formLib">';
            $return .= $controller->oForm->createInput ( "CONTENT_PUBLICATION_DATE", t ( 'Displayed publication date' ), 10, "date", false, trim(preg_replace( '/[0-9]{2}:[0-9]{2}/', '', $controller->values ["CONTENT_PUBLICATION_DATE"] )), $controller->readO, 10, true );
            $return .= $controller->oForm->createInput ( "CONTENT_PUBLICATION_DATE_HEURE", t ( 'Displayed publication date' ), 10, "heure", false, trim(preg_replace( '/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/', '', $controller->values ["CONTENT_PUBLICATION_DATE"] )), $controller->readO, 10, true );
            $return .= '</td>';
        }
        $return .= '<tr><td class="formVal">'.t ( 'Display date begin' ).'</td><td class="formLib">';
        $return .= $controller->oForm->createInput ( "CONTENT_START_DATE", t ( 'Display date begin' ), 10, "date", false, trim(preg_replace( '/[0-9]{2}:[0-9]{2}/', '', $controller->values ["CONTENT_START_DATE"] )), $controller->readO, 10, true );
        $return .= $controller->oForm->createInput ( "CONTENT_START_DATE_HEURE", t ( 'Display date begin' ), 10, "heure", false, trim(preg_replace( '/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/', '', $controller->values ["CONTENT_START_DATE"] )), $controller->readO, 10, true );
        $return .= '</td>';
        //$return .= $controller->oForm->createInput ( "CONTENT_START_DATE", t ( 'Display date begin' ), 10, "date", false, str_replace ( ' 00:00', '', $controller->values ["CONTENT_START_DATE"] ), $controller->readO, 10, false );

         $return .= '<tr><td class="formVal">'.t ( 'Display end date' ).'</td><td class="formLib">';
        $return .= $controller->oForm->createInput ( "CONTENT_END_DATE", t ( 'Display end date' ), 10, "date", false, trim(preg_replace( '/[0-9]{2}:[0-9]{2}/', '', $controller->values ["CONTENT_END_DATE"] )), $controller->readO, 10, true );
        $return .= $controller->oForm->createInput ( "CONTENT_END_DATE_HEURE", t ( 'Display end date' ), 10, "heure", false, trim(preg_replace( '/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/', '', $controller->values ["CONTENT_END_DATE"] )), $controller->readO, 10, true );
        $return .= '</td>';
        //$return .= $controller->oForm->createInput ( "CONTENT_END_DATE", t ( 'Display end date' ), 10, "date", false, str_replace ( ' 00:00', '', $controller->values ["CONTENT_END_DATE"] ), $controller->readO, 10, false );

		$return .= $controller->oForm->showSeparator ();

		if ($controller->id == Pelican::$config ["DATABASE_INSERT_ID"]) {
			$controller->values ["CONTENT_DISPLAY_SEARCH"] = 1;
			$controller->values ["CONTENT_DISPLAY_DATE"] = 1;
			$controller->values ["CONTENT_DISPLAY_AUTHOR"] = 1;
			$controller->values ["CONTENT_DISPLAY_PDF"] = 1;
			$controller->values ["CONTENT_DISPLAY_PRINT"] = 1;
			$controller->values ["CONTENT_DISPLAY_SEND"] = 1;
			$controller->values ["CONTENT_DISPLAY_COMMENT"] = 1;
			$controller->values ["CONTENT_DISPLAY_TAGS"] = 1;
			$controller->values ["CONTENT_DISPLAY_SHARE"] = 1;
			$controller->values ["CONTENT_DISPLAY_QRCODE"] = 1;
		}
		$return .= $controller->oForm->createCheckBoxFromList ( "CONTENT_DISPLAY_DATE", t ( 'AFFICHER' ), array ("1" => t ( "la date de publication" ) ), $controller->values ["CONTENT_DISPLAY_DATE"], false, $controller->readO, "h" );
		$return .= $controller->oForm->createCheckBoxFromList ( "CONTENT_DISPLAY_AUTHOR", " ", array ("1" => t ( "l'auteur" ) ), $controller->values ["CONTENT_DISPLAY_AUTHOR"], false, $controller->readO, "h" );
		$return .= $controller->oForm->createCheckBoxFromList ( "CONTENT_DISPLAY_PDF", " ", array ("1" => t ( "l'icône PDF" ) ), $controller->values ["CONTENT_DISPLAY_PDF"], false, $controller->readO, "h" );
		$return .= $controller->oForm->createCheckBoxFromList ( "CONTENT_DISPLAY_PRINT", " ", array ("1" => t ( "l'icône d'impression" ) ), $controller->values ["CONTENT_DISPLAY_PRINT"], false, $controller->readO, "h" );
		$return .= $controller->oForm->createCheckBoxFromList ( "CONTENT_DISPLAY_SEND", " ", array ("1" => t ( "l'icône d'envoi à un ami" ) ), $controller->values ["CONTENT_DISPLAY_SEND"], false, $controller->readO, "h" );
		$return .= $controller->oForm->createCheckBoxFromList ( "CONTENT_DISPLAY_COMMENT", " ", array ("1" => t ( "les commentaires" ) ), $controller->values ["CONTENT_DISPLAY_COMMENT"], false, $controller->readO, "h" );
		$return .= $controller->oForm->createCheckBoxFromList ( "CONTENT_DISPLAY_TAGS", " ", array ("1" => t ( "les tags" ) ), $controller->values ["CONTENT_DISPLAY_TAGS"], false, $controller->readO, "h" );
		$return .= $controller->oForm->createCheckBoxFromList ( "CONTENT_DISPLAY_SHARE", " ", array ("1" => t ( "les partages sociaux" ) ), $controller->values ["CONTENT_DISPLAY_SHARE"], false, $controller->readO, "h" );
		$return .= $controller->oForm->createCheckBoxFromList ( "CONTENT_DISPLAY_QRCODE", " ", array ("1" => t ( "le QR Code" ) ), $controller->values ["CONTENT_DISPLAY_QRCODE"], false, $controller->readO, "h" );

		$return .= $controller->oForm->showSeparator ();
		$return .= $controller->oForm->createCheckBoxFromList ( "CONTENT_DISPLAY_SEARCH", t ( 'Indexer' ), array ("1" => t ( "dans la Recherche" ) ), $controller->values ["CONTENT_DISPLAY_SEARCH"], false, $controller->readO, "h" );

		$return .= $controller->getWorkflowFields ( $controller->oForm );

		return $return;
	}
}