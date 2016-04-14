<?php

class Cms_Content_Common_Common extends Cms_Content_Module
{
    const ENGAGEMENT = 2;
    const FAQ        = 6;
    const SLIDESHOW  = 3;
    const FORM       = 7;
    
    public static function render(Pelican_Controller $controller)
    {
        $con = Pelican_Db::getInstance();
        $return = '';
        if ($controller->values["PAGE_ID"]) {
            $return .= $controller->oForm->createHidden("OLD_PAGE_ID", $controller->values["PAGE_ID"]);
            $sSQL = "select PAGE_PARENT_ID from #pref#_page where PAGE_ID=:PAGE_ID AND LANGUE_ID=:LANGUE_ID";
            $aBind[":PAGE_ID"] = $controller->values["PAGE_ID"];
            $aBind[":LANGUE_ID"] = $controller->values['LANGUE_ID'];
            $item = $con->queryItem($sSQL, $aBind);
            $return .= $controller->oForm->createHidden("OLD_PAGE_PARENT_ID", ($item == 1 ? 0 : $item));
        }
        if ($controller->id != Pelican_Db::DATABASE_INSERT_ID) {
            $return .= $controller->oForm->createLabel("ID (cid)", $controller->values["CONTENT_ID"]);
        }
        $title = t("TITLE");
        $charNumber = 50;
        switch ($_GET['uid']) {
            case self::ENGAGEMENT: 
                $title = t("SHORT_TITLE");
                break;
            case self::FAQ: 
                $title = t('NDP_LABEL_BO');
                break;
            case self::SLIDESHOW: 
                $title = t('NDP_SLIDESHOW_TITLE');
                break;
            case self::FORM:
                $charNumber = 75;
            default: 
                //nothing
                break;
            
        }
        $return .= $controller->oForm->createInput('CONTENT_TITLE', $title, $charNumber, '', true, $controller->values['CONTENT_TITLE'], $controller->readO, 100);
        //Old title pour les redirections 301
        $return .= $controller->oForm->createHidden("CONTENT_OLD_TITLE", '');
        $accueil = $con->queryRow(self::getHomePage(), self::bind());
        $return .= $controller->oForm->createHidden("PAGE_ID", $accueil['PAGE_ID']); 

        return $return;
    }
    
    /**
     * 
     * @return array
     */
    protected function bind()
    {
        return [':SITE_ID' => $_SESSION[APP]['SITE_ID'], ':LANGUE_ID' => $_SESSION[APP]['LANGUE_ID']];
    }
    
    /**
     * 
     * @return string
     */
    protected function getHomePage()
    {
        $sql = 'SELECT PAGE_ID FROM #pref#_page p WHERE SITE_ID = :SITE_ID AND LANGUE_ID = :LANGUE_ID AND PAGE_PARENT_ID IS NULL AND PAGE_GENERAL = 0';
        
        return $sql;
    }
}
