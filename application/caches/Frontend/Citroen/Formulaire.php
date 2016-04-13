<?php
/**
 * Fichier de Pelican_Cache : Formulaire
 * @package Cache
 * @subpackage Pelican
 */
class Frontend_Citroen_Formulaire extends Pelican_Cache {

    var $duration = DAY;

    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    function getValue()
    {
        $oConnection = Pelican_Db::getInstance();
        $aBind[':FORM_TYPE_ID'] = $this->params[0];
        $aBind[':FORM_USER_TYPE_CODE'] = $oConnection->strToBind($this->params[1]);
        $aBind[':FORM_EQUIPEMENT_CODE'] = $oConnection->strToBind($this->params[2]);
        $aBind[':SITE_ID'] = $this->params[3];
        $aBind[':LANGUE_ID'] = $this->params[4];
        $aBind[':FORM_ID'] = $this->params[5];
        $aBind[':FORM_INCE_CODE'] = $oConnection->strToBind($this->params[6]);
        $where = '';
        
        if($this->params[5] != ''){
            $where =  '
            AND FORM_ID = :FORM_ID';
        }elseif($this->params[6] != ''){
        $where =  '
            AND FORM_INCE_CODE = :FORM_INCE_CODE';
        }else{
            $where =  '
            AND FORM_TYPE_ID = :FORM_TYPE_ID
			AND FORM_USER_TYPE_CODE = :FORM_USER_TYPE_CODE
			AND FORM_EQUIPEMENT_CODE = :FORM_EQUIPEMENT_CODE';
        }
        
        if($this->params[7] != '' && $this->params[7] != 'undefined'){

            if($this->params[7] == 'RTO')
            {
                $aBind[':FORM_CONTEXT_CODE'] = $oConnection->strToBind('RTO'); 
            }
            else
            {
                $aBind[':FORM_CONTEXT_CODE'] = $oConnection->strToBind('CAR');
            }
           
        }else{
            $aBind[':FORM_CONTEXT_CODE'] = $oConnection->strToBind('STD');
        }
        
        if(isset($this->params[7])){
            $where .=  '
            AND FORM_CONTEXT_CODE = :FORM_CONTEXT_CODE';
        }
        

        $sSQL = "
            SELECT
				*
			FROM
				#pref#_form
			WHERE
				SITE_ID = :SITE_ID
			AND LANGUE_ID = :LANGUE_ID ".$where;

        $formulaire = $oConnection->queryRow($sSQL, $aBind);

        $this->value = $formulaire;
    }
}
