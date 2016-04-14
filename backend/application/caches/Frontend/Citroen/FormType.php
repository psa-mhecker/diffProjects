<?php
/**
 * Fichier de Pelican_Cache : Formulaire.
 *
 * @param 0 full (boolean) : Si vrai, on retourne tout l'enregistrement. Sinon, on retourne uniquement le label.
 */
class Frontend_Citroen_FormType extends Pelican_Cache
{
    public $duration = DAY;

    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    public function getValue()
    {
        $full = isset($this->params[0]) && $this->params[0] == true ? true : false;

        $oConnection = Pelican_Db::getInstance();
        $sSQL = "SELECT * FROM  #pref#_form_type";

        $aFormtypes =  $oConnection->queryTab($sSQL);
        $aFormtypesIndexed = array();
        if (count($aFormtypes)) {
            foreach ($aFormtypes as $aOneFormtype) {
                $aFormtypesIndexed[$aOneFormtype['FORM_TYPE_ID']] = $full ? $aOneFormtype : urlencode($aOneFormtype['FORM_TYPE_LABEL']);
            }
        }
        $this->value = $aFormtypesIndexed;
    }
}
