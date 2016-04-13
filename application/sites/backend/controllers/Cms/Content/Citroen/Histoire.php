<?php
class Cms_Content_Citroen_Histoire extends Cms_Content_Module
{

    public static function render(Pelican_Controller $controller)
    {
		if(empty($controller->values['PERSON_ID'])){
			$controller->values['PERSON_ID']	=	1;
		}
		$return = '<script type="text/javascript">
			$( document ).ready(function() {
				';
				if($controller->values['PERSON_ID'] == 1 || empty($controller->values['PERSON_ID'])){
		$return .='		$("#divMEDIA_ID").parent().parent().parent().parent().parent().hide();
				$("#divCONTENT_CODE3").parent().parent().parent().parent().parent().hide();
				$("#divVIGNETTE_PLAYER").parent().parent().parent().parent().parent().hide();
				$("#DOC_ID").parent().parent().hide();
				$("#bloc").html("1");
				';				
				}
				if($controller->values['PERSON_ID'] == 2 || empty($controller->values['PERSON_ID'])){
		$return .='	$("#divMEDIA_ID").parent().parent().parent().parent().parent().show();						
					$("#DOC_ID").parent().parent().show();
					$("#divVIGNETTE_PLAYER").parent().parent().parent().parent().parent().hide();
					$("#divCONTENT_CODE3").parent().parent().parent().parent().parent().hide();';					
				}
				if($controller->values['PERSON_ID'] == 3 || empty($controller->values['PERSON_ID'])){
		$return .=' $("#DOC_ID").parent().parent().hide();
					$("#divMEDIA_ID").parent().parent().parent().parent().parent().hide();					
					$("#divCONTENT_CODE3").parent().parent().parent().parent().parent().show();
					$("#divVIGNETTE_PLAYER").parent().parent().parent().parent().parent().show();';
					
				}
		$return .='		
		$("input[name=\'PERSON_ID\']").change(function(){
					if(this.value == 1){
						$("#divMEDIA_ID").parent().parent().parent().parent().parent().hide();
						$("#divVIGNETTE_PLAYER").parent().parent().parent().parent().parent().hide();
						$("#divCONTENT_CODE3").parent().parent().parent().parent().parent().hide();
						$("#DOC_ID").parent().parent().hide();
						$("#bloc").html("1");
					}
					if(this.value == 2){
						$("#divMEDIA_ID").parent().parent().parent().parent().parent().show();						
						$("#DOC_ID").parent().parent().show();
						$("#divCONTENT_CODE3").parent().parent().parent().parent().parent().hide();	
						$("#divVIGNETTE_PLAYER").parent().parent().parent().parent().parent().hide();
						$("#bloc").html("2");
					}
					if(this.value == 3){
						$("#DOC_ID").parent().parent().hide();
						$("#divMEDIA_ID").parent().parent().parent().parent().parent().hide();
						$("#divCONTENT_CODE3").parent().parent().parent().parent().parent().show();
						$("#bloc").html("3");
					}
				});
			});

			
		</script>
        ';
		$texteObligatoire	=	t("LE_TEXTE_EST_OBLIGATOIRE", 'js2');
		$visuelObligatoire	=	t("LE_VISUEL_EST_OBLIGATOIRE", 'js2');
		$formatObligatoire	=	t("LE_FORMAT_DU_VISUEL_EST_OBLIGATOIRE", 'js2');
		$videoObligatoire	=	t("LA_VIDEO_EST_OBLIGATOIRE", 'js2');
		
			$return .= $controller->oForm->createJS('
				if($("#bloc").html() == 1){
					if($("#CONTENT_TEXT").val() == "" && $("input[name=\'PERSON_ID\']").val() == 1){
						alert(\''.$texteObligatoire.'\');
						return false;
					}
				}	
				if($("#bloc").html() == 2){
					if ( isBlank(obj.MEDIA_ID.value) ) {
						alert(\''.$visuelObligatoire.'\');						
						return false;
					}
				}
				
				if($("#bloc").html() == 3){
					if ( isBlank(obj.CONTENT_CODE3.value) ) {
						alert(\''.$videoObligatoire.'\');
						return false;
					}
				}	

			');
		
		$return .= "<div id='bloc' style='display:none'></div>";
		$return .= $controller->oForm->createRadioFromList('PERSON_ID', t('CHOIX_BLOCS'), array(1 => t('BLOC_TEXTE'), 2 => t('BLOC_IMAGE'), 3=> t('BLOC_VIDEO')), $controller->values['PERSON_ID'], true, $controller->readO);		
		$return .= $controller->oForm->createEditor("CONTENT_TEXT", t('TEXTE'), false, $controller->values['CONTENT_TEXT'], $controller->readO, true, "", 500, 200);
		$return .= $controller->oForm->createMedia("MEDIA_ID", t('VISUEL'), false, "image", "", $controller->values['MEDIA_ID'], $controller->readO, true, false, 'cinemascope');
		$return .= $controller->oForm->createMedia("CONTENT_CODE3", t('VIDEO'), false, "video", "", $controller->values['CONTENT_CODE3'], $controller->readO);
        $return .= $controller->oForm->createInput("CONTENT_DATE2", t('DATE'), 255, "date", true, $controller->values['CONTENT_DATE2'], $controller->readO, 75);
		$return .= $controller->oForm->createComboFromList("CONTENT_CODE", t('FORMAT_DATE'), array(1 => t('YEAR'), 2 => t('MOIS_ANNEE'), 3=> t('JOUR_MOIS_ANNEE')), $controller->values['CONTENT_CODE'], true, $controller->readO);      
        return $return;
    }

    public static function save(Pelican_Controller $controller)
    {
		parent::save($controller);
		Pelican_Cache::clean("Frontend_Citroen_ListeHistoire", array($_SESSION[APP]['SITE_ID'],$_SESSION[APP]['LANGUE_ID']));
    }

}