<?php

include_once dirname(__FILE__).'/../Page.php';
include_once pelican_path('Form');
require_once pelican_path('Text.Utf8');
include_once Pelican::$config['APPLICATION_VIEW_HELPERS'].'/Div.php';
include_once Pelican::$config['APPLICATION_VIEW_HELPERS'].'/Button.php';
include_once Pelican::$config['APPLICATION_VIEW_HELPERS'].'/Form.php';
include_once Pelican::$config['APPLICATION_VIEW_HELPERS'].'/Media.php';
include_once Pelican::$config['APPLICATION_VIEW_HELPERS'].'/File.php';

class Cms_Page_Dynamique_Controller extends Cms_Page_Controller
{
    public function ajaxAddZoneAction($area_id, $zone_id, $cpt)
    {
        $oConnection = Pelican_Db::getInstance();
        if (!empty($_SESSION[APP]['PAGE_ID']) && $_SESSION[APP]['PAGE_ID']!=Pelican_db::DATABASE_INSERT_ID ) {
            $sql = "select p.*,	pv.*, pt.*
			from #pref#_page p
			INNER JOIN #pref#_page_version pv on (p.PAGE_ID = pv.PAGE_ID AND pv.PAGE_VERSION = PAGE_DRAFT_VERSION AND p.LANGUE_ID = pv.LANGUE_ID)
			INNER JOIN #pref#_template_page tp on (pv.TEMPLATE_PAGE_ID=tp.TEMPLATE_PAGE_ID)
			INNER JOIN #pref#_page_type pt on (tp.PAGE_TYPE_ID=pt.PAGE_TYPE_ID)
			where
			pv.LANGUE_ID = :LANGUE_ID
			and p.PAGE_ID= :PAGE_ID
			and p.SITE_ID = :SITE_ID";
            $bind = [':LANGUE_ID'=>$_SESSION[APP]['LANGUE_ID'],':PAGE_ID'=>$_SESSION[APP]['PAGE_ID'],':SITE_ID'=>$_SESSION[APP]['SITE_ID'] ];
            $this->pageValues = $oConnection->queryRow($sql, $bind);
        } else {
            $this->pageValues['AREA_ID'] = $area_id;
        }
        $form = '';
        $target = 'zone_multi_'.$cpt;
        $aZone = $oConnection->queryRow(
                'SELECT *, ZONE_LABEL as LABEL_ZONE
                     FROM #pref#_zone
                     WHERE ZONE_ID=:ZONE_ID',
                array(':ZONE_ID' => $zone_id)
        );

        // Gestion des champs code couleur (tranche outil)
        // Génération d'un identifiant unique pour la nouvelle tranche ajoutée à la zone dynamique
        $uniqUid = uniqid();
        $newBlockId = 'new-dynamic-block-marker-'.$uniqUid;

        // Ajout d'un marqueur dans le DOM, contenant l'identifiant de la nouvelle tranche (le marqueur est placé juste avant le <table> du getMultiZone)
        // Le marqueur permet de sélectionner le formulaire du nouveau bloc pour faire des traitements JS dessus.
        $form = '<span style="display:none;" data-uniq-uid="'.$uniqUid.'" class="'.htmlspecialchars($newBlockId).'"></span>'.$form;

        // Appel de initTrancheCodeCouleur sur la nouvelle tranche qui vient d'être ajoutée à la zone dynamique
        $jsCodeCouleur = <<<JS
jQuery(".{$newBlockId}").next("table.form").find(".outil-code-couleur-on").each(function(index, el){
    var tranche = $(this).closest("table").get();
    initTrancheCodeCouleur(tranche);
});
JS;

        ob_start();
        $this->oForm = Pelican_Factory::getInstance('Form', false);
        $this->oForm->open();
        pelican_import('Controller.Back');

        $this->getMultiZone($form, $aZone, $cpt, $area_id, true);
        $form .= $this->oForm->putHidden();

        $form_js_clean    = addslashes($this->oForm->_sJS);
        $form_js_clean    = preg_replace('#//.*$#m', '', $form_js_clean);

        $this->oForm->close();

        if (Pelican::$config["CHARSET"] == "UTF-8") {
            pelican_import('Text.Utf8');
            $form = Pelican_Factory::staticCall('Text.Utf8', 'utf8_to_unicode', $form);
        }
        $js = <<<JS
var fonctionCheck = CheckForm_multi.toString();
fonctionCheck = fonctionCheck.substring(fonctionCheck.indexOf("{")+1, fonctionCheck.length - 2);
fonctionCheck = "if (document.getElementById('{$this->multi}multi_display')) {
    if (document.getElementById('{$this->multi}multi_display').value) {
        {$form_js_clean}
    }
}" + fonctionCheck;
CheckForm_multi = new Function("obj", fonctionCheck);
var count = eval($('#count_pageMulti{$area_id}').val() || 0);
$("#count_pageMulti{$area_id}").val( count + 1);
{$form_eval}
JS;

        $this->addResponseCommand('script', array('value' => str_replace(array(chr(10), chr(13)), '', $js)));
        $this->addResponseCommand('append', array(
            'id' => $target,
            'attr' => 'value',
            'value' => $form,
        ));
        $this->addResponseCommand('script', array('value' => $jsBind));
        $this->addResponseCommand('script', array('value' => $jsCodeCouleur));
    }
}
