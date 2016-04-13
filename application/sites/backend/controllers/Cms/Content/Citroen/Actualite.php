<?php
class Cms_Content_Citroen_Actualite extends Cms_Content_Module
{
	public static $decacheBack = array(
        array('Frontend/Citroen/Actualites/Detail',
            array('CONTENT_ID','SITE_ID', 'LANGUE_ID')
        ),
		array('Frontend/Citroen/Actualites/PageClearUrlByActu',
            array('PAGE_ID','SITE_ID', 'LANGUE_ID')
        ),
		array('Frontend/Citroen/Actualites/Pager',
            array('PAGE_ID')
        ),
		array('Frontend/Citroen/Actualites/Liste',
            array('PAGE_ID','SITE_ID', 'LANGUE_ID')
        ),
		array('Frontend/Citroen/Home/Actualites',
            array('SITE_ID', 'LANGUE_ID')
        )
    );
	public static $decachePublication = array(
       array('Frontend/Citroen/Actualites/Detail',
            array('CONTENT_ID','SITE_ID', 'LANGUE_ID')
        ),
		array('Frontend/Citroen/Actualites/PageClearUrlByActu',
            array('PAGE_ID','SITE_ID', 'LANGUE_ID')
        ),
		array('Frontend/Citroen/Actualites/Pager',
            array('PAGE_ID')
        ),
		array('Frontend/Citroen/Actualites/Liste',
            array('PAGE_ID','SITE_ID', 'LANGUE_ID')
        ),
		array('Frontend/Citroen/Home/Actualites',
            array('SITE_ID', 'LANGUE_ID')
        )
    );
    public static function render(Pelican_Controller $controller)
    {
        $return .= $controller->oForm->createJS("
            var image = document.getElementById('divMEDIA_ID');
            var video = document.getElementById('divDOC_ID');
                
            if(image.innerHTML == '' && video.innerHTML != ''){
                alert('".t('CHOIX_IMG', 'js')."');
                return false;
            }
        ");
        $return .= $controller->oForm->createMedia("MEDIA_ID", t('VISUEL'), false, "image", "", $controller->values['MEDIA_ID'], $controller->readO, true, false, "16_9");
        $return .= $controller->oForm->createMedia("DOC_ID", t('VIDEO'), false, "video", "", $controller->values['DOC_ID'], $controller->readO);
        $return .= $controller->oForm->createInput("CONTENT_DATE2", t('DATE'), 255, "date", true, $controller->values['CONTENT_DATE2'], $controller->readO, 75);
        $aBind[':SITE_ID'] =  $_SESSION[APP]['SITE_ID'];
        $sSQL = "
            select
                THEME_ACTUALITES_ID as id,
                THEME_ACTUALITES_LABEL as lib
            from #pref#_theme_actualites
            where SITE_ID = :SITE_ID
			ORDER BY THEME_ACTUALITES_ORDER
        ";
        $return .= $controller->oForm->createComboFromSql($oConnection, "CONTENT_CODE", t('THEMES'), $sSQL, $controller->values['CONTENT_CODE'], true, $controller->readO, "1", false, "", true, false, "", "", $aBind);
        $return .= $controller->oForm->createComboFromList("CONTENT_CODE2", t('FORMAT_DATE'), array(1 => "UK", 2 => "FR"), $controller->values['CONTENT_CODE2'], true, $controller->readO);
        $return .= $controller->oForm->createEditor("CONTENT_TEXT2", t('CHAPO'), true, $controller->values['CONTENT_TEXT2'], $controller->readO, true, "", 500, 200);
        $return .= $controller->oForm->createEditor("CONTENT_TEXT", t('TEXTE'), true, $controller->values['CONTENT_TEXT'], $controller->readO, true, "", 500, 200);
        return $return;
    }

    public static function save(Pelican_Controller $controller)
    {
        parent::save($controller);
        foreach (self::$decachePublication as $keyCache => $valueCache) {
            Pelican_Cache::clean($valueCache[0]);
        }
    }

}