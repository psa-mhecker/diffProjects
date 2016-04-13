<?php
include_once (dirname(__FILE__) . '/../Page.php');
include_once (pelican_path('Form'));
require_once (pelican_path('Text.Utf8'));
include_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Div.php');
include_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Button.php');
//include_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Tab.php');
include_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Form.php');
include_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/Media.php');
include_once (Pelican::$config['APPLICATION_VIEW_HELPERS'] . '/File.php');

class Cms_Page_Perso_Controller extends Cms_Page_Controller
{

    public function addFormPersoAction ()
    {
        $oConnection = Pelican_Db::getInstance();
        
        $zoneId = $this->getParam('zoneId');
        $iZtid = $this->getParam('ztid');
        $target = $this->getParam('target');
        $indexTab = $this->getParam('indexTab');
        $multiId = $this->getParam('multiId');
        $bGeneral = $this->getParam('bGeneral');
        $iTpid = $this->getParam('iTpid');
        $iPid = $this->getParam('iPid');
        $iTypeExpand = $this->getParam('iTypeExpand');
        $action = 'append';
        
        $defaultSerialize = htmlspecialchars_decode($this->getParam('defaultSerialize'));

        $aDefaultTest = json_decode($defaultSerialize);
        if(is_array($aDefaultTest->ZONE_TOOL) && !empty($aDefaultTest->ZONE_TOOL))
        {
            $aDefaultTest->ZONE_TOOL = implode('|', $aDefaultTest->ZONE_TOOL);
            $defaultSerialize = json_encode($aDefaultTest);              
        }

        if($defaultSerialize != '' && is_string($defaultSerialize)){

            $aTab = \Citroen_View_Helper_Global::arrHtmlDecode(json_decode($defaultSerialize, true));
            if($this->getParam('persoForm')){
                $aMultiNames = explode(',',$this->getParam('listMulti'));
                $persoForm = $this->clearTab($this->getParam('persoForm'),$multiId, $aMultiNames, 'perso_'.$indexTab.'_',$bGeneral, $zoneId);
                $aTab = array_merge($persoForm,$aTab);

                $action = 'assign';
            }
        }

        $form = '';
        $sZonePath = $oConnection->queryItem(
                'SELECT 
                    ZONE_BO_PATH
                 FROM 
                    #pref#_zone 
                 WHERE ZONE_ID = :ZONE_ID', 
                array(':ZONE_ID'=>$zoneId)
        );
        
        // Lecture des métadonnées multi
        $multiMetadataForm = $this->getParam('multiMetadata');
        parse_str($multiMetadataForm, $multiMetadata);
        unset($multiMetadataForm);
        
        // Exclusion des nouveaux éléments multi génériques (pour ne pas créer de double ajout avec la synchro add/del)
        $multiList = array('PUSH', 'PUSH_OUTILS_MAJEUR', 'PUSH_OUTILS_MINEUR', 'PUSH_CONTENU_ANNEXE', 'SLIDESHOW_GENERIC', 'SLIDEOFFREADDFORM');
        $multiList = $multiList + array($aTab['MULTI_NAME']);
        $prefix = $this->getParam('multiId');
        foreach ($multiList as $multiName) {
            if (!is_array($aTab[$multiName])) {
                continue;
            }
            $addedMultiHashes = array();
            if (isset($multiMetadata['added_multi_index'][$prefix.$multiName])) {
                $addedMultiHashes = $multiMetadata['added_multi_index'][$prefix.$multiName];
            }
            foreach ($aTab[$multiName] as $key => $val) {
                if (in_array($val['MULTI_HASH'], $addedMultiHashes)) {
                    unset($aTab[$multiName][$key]);
                }
            }
        }
    
        $sNameFunctionCheck = ($bGeneral == false) ? "CheckFormPerso_".$multiId.$indexTab : "CheckFormPerso_".$indexTab;
        $this->oForm = Pelican_Factory::getInstance('Form', false);
        $form .= $this->oForm->open("", "post", "fForm".$indexTab, false, true, $sNameFunctionCheck, "", true, false);
        pelican_import('Controller.Back');
        $this->multi = ($bGeneral == false) ? 'perso_' . $indexTab.'_'.$multiId : 'perso_' . $indexTab.'_';
        if($bGeneral == false){
            /* Pour la lecture des multi on crée une entrée perso dans zoneValues */
            $aTab['PERSO'] = $aTab;
            $aTab['PAGE_ID'] = $iPid;
            $aTab['LANGUE_ID'] = $_SESSION[APP]['LANGUE_ID'];
            $this->zoneValues = $aTab;
        }else{
            $this->values = $aTab;
        }
        $this->getZone($form, $indexTab, $multiId, $sZonePath, $bGeneral, $iPid, $iTpid, $iTypeExpand);
        
        $this->oForm->_aIncludes["multi"] = true;
        $this->oForm->_aIncludes["num"] = true;
        $this->oForm->_aIncludes["text"] = true;
        $this->oForm->_aIncludes["date"] = true;
        $this->oForm->_aIncludes["list"] = true;
        $this->oForm->_aIncludes["popup"] = true;
        $this->oForm->_aIncludes["crosstab"] = true;

        $form_js_clean    = addslashes($this->oForm->_sJS);
        $form_js_clean    = preg_replace('#//.*$#m', '', $form_js_clean);

        $jsHide = <<<JS
hideProduct($('#{$this->multi}INDICATEUR_ID').val(), '{$this->multi}');
JS;
        if($this->oForm->getUseMulti() == true){
       $js .= <<<JS
var fonctionCheck = {$sNameFunctionCheck}_multi.toString();
fonctionCheck = fonctionCheck.substring(fonctionCheck.indexOf("{")+1, fonctionCheck.length - 2);
fonctionCheck = "{$form_js_clean}" + fonctionCheck;
{$sNameFunctionCheck}_multi = new Function("obj", fonctionCheck);
JS;
        }

        $form .= '<div class="blankForFooter"></div>';
         $this->oForm->setView($this->getView());
        $form .= $this->oForm->close();
        
        $deleteButton = new \Citroen\Html\Button(
                $this->multi."SUPPRIME_PROFILE",
                'button',
                t("SUPPRIMER_PERSO"),
                array('onclick' => "deleteTab(this,'".$indexTab."');"),
                '',
                '',
                "data-title='".t('CONFIRM_DELETE_PROFIL')."'"
            );
        $deleteButton->wrap('<ul class="footerTabPerso"><li>|</li></ul>');
        $form .= $deleteButton->render();
        $form .= '<div id="dialog-confirm"></div>';

                  
        if (Pelican::$config["CHARSET"] == "UTF-8") {
            pelican_import('Text.Utf8');
            //$form = Pelican_Factory::staticCall('Text.Utf8', 'utf8_to_unicode', $form);
        }
       
        $this->addResponseCommand($action, array(
            'id' => $target,
            'attr' => 'innerHTML',
            'value' => $form
        ));
        $this->addResponseCommand('script', array('value'=>$jsHide));
        $this->addResponseCommand('script', array('value'=>str_replace(array(chr(10), chr(13)), '', $js)));
    }

    public function savePersoAction ()
    {
        $aPerso = array();
        $values = $_POST["values"]["obj"];
        
        $sMultiName = $_POST["values"]["multiName"];
        $sListeMultiNames = $_POST["values"]["ListMultiName"];
        $bGeneral = $_POST["values"]["bGeneral"];
        $zoneId = $_POST["values"]["zoneId"];
        $aMultiNames = explode(',',$sListeMultiNames);
        $profileList = array();
        $j=1;

        //On teste s'il existe des profils qui ont �t� param�tr�s
        if(is_array($values) && count($values) > 0 ){
            //On boucle sur les profils param�tr�s
            foreach($values as $i=>$data){
                if(!empty($values[$i])){
                	//$profil = $this->clearTab($values[$i],$sMultiName, $aMultiNames, 'perso_'.$i.'_',$bGeneral,$zoneId);
					$tmp = explode("_",$values[$i]);
                    
                    $idForm = $tmp[1];
                    unset($tmp); 
                    $profil = $this->clearTab($values[$i],$sMultiName, $aMultiNames, 'perso_'.$idForm.'_',$bGeneral,$zoneId);
                    
                    // Nettoyage multi vides
                    $cleanMulti = function ($profil) {
                        $multiList = array('PUSH', 'PUSH_OUTILS_MAJEUR', 'PUSH_OUTILS_MINEUR', 'PUSH_CONTENU_ANNEXE', 'SLIDESHOW_GENERIC', 'SLIDEOFFREADDFORM');
                        $multiList = $multiList + array($profil['MULTI_NAME']);
                        foreach ($multiList as $multiName) {
                            if (!is_array($profil[$multiName])) {
                                continue;
                            }
                            foreach ($profil[$multiName] as $key => $val) {
                                $keys = array_keys($val);
                                if (count($val) == 1 && preg_match('#'.preg_quote($multiName, '#').'$#', $keys[0])) {
                                    unset($profil[$multiName][$key]);
                                }
                            }
                        }
                        return $profil;
                    };
                    $profil = $cleanMulti($profil);
                    
                    if(!empty($profil)){
                        //CPW-3463
                        //on recupere la tranche multi perso
                      $MULTI_NAME = array_search($profil['MULTI_NAME'], Pelican::$config['PERSO_MULTI_NAME']);
                      //si ce multi existe
                        if(isset($profil[$MULTI_NAME])) {
                            usort($profil[$MULTI_NAME], array('Citroen\Perso\Synchronizer', 'multiCompare'));
                        }

                        if($MULTI_NAME == 'PUSH_CONTENU_ANNEXE')
                        {
                            $MULTI_NAME = 'PUSH_OUTILS_MINEUR';
                            if(isset($profil[$MULTI_NAME])) {
                                usort($profil[$MULTI_NAME], array('Citroen\Perso\Synchronizer', 'multiCompare'));
                            }
                        }

                        $aPerso['PROFIL_'.$j] = $profil;
                        $j++;
                    }

                }
            }
        }
        $sSerialize = '';
        if(!empty($aPerso)){
            foreach ($aPerso as $keyPerso => $valuePerso) {                
                if(empty($valuePerso['PROFIL_DATE_DEB'])){                    
                    $aPerso[$keyPerso]['PROFIL_DATE_DEB']   =   date('d/m/Y');
                    $aPerso[$keyPerso]['PROFIL_DATE_FIN']   =   $valuePerso['PROFIL_DATE_FIN'];

                    $sDateDeb = new DateTime( );                    
                    $sDateDeb = $sDateDeb->format('Ymd');
                    
                    $sDateFin   = str_replace("/", "-", $valuePerso['PROFIL_DATE_FIN']);
                    $oDateFin   = new DateTime( $sDateFin ); 
                    $sDateFin   = $oDateFin->format('Ymd');
                    
                    if($sDateDeb > $sDateFin){
                        $aPerso[$keyPerso]['PROFIL_DATE_FIN']  =   '';
                    }                 
                }
                if ($valuePerso['ZONE_TOOL']) {
                    $aPerso[$keyPerso]['ZONE_TOOL'] = implode('|', $valuePerso['ZONE_TOOL']);                  
                }
            }
            
            $sSerialize = \Citroen_View_Helper_Global::arrHtmlEncode($aPerso);
            $sSerialize = json_encode($sSerialize);
        }
 
        if($bGeneral == false){
            $js = '$("#'.$sMultiName.'ZONE_PERSO").val(\''.$sSerialize.'\');';
        }else{
            if(is_array($profileList)){
                $profiles = implode('##', $profileList);
                $js = '$("#PROFILE_LIST").val(\''.$profiles.'\');';
            }
            $js .= '$("#PAGE_PERSO").val(\''.$sSerialize.'\');';

        }
        $this->addResponseCommand('script', array(
            'value' =>  $js
        ));
    }
}
