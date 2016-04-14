<?php
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Citroen.php';
class Cms_Page_Citroen_Actualites_Contenu extends Cms_Page_Citroen
{
    public static $decacheBack = array(
        array('Frontend/Citroen/Actualites/Detail',
            array('CONTENT_ID','SITE_ID', 'LANGUE_ID'),
        ),
        array('Frontend/Citroen/Actualites/PageClearUrlByActu',
            array('PAGE_ID','SITE_ID', 'LANGUE_ID'),
        ),
        array('Frontend/Citroen/Actualites/Pager',
        ),
        array('Frontend/Citroen/Actualites/Liste',
            array('PAGE_ID','SITE_ID', 'LANGUE_ID'),
        ),
    );
    public static $decachePublication = array(
       array('Frontend/Citroen/Actualites/Detail',
            array('CONTENT_ID','SITE_ID', 'LANGUE_ID'),
        ),
        array('Frontend/Citroen/Actualites/PageClearUrlByActu',
            array('PAGE_ID','SITE_ID', 'LANGUE_ID'),
        ),
        array('Frontend/Citroen/Actualites/Pager',
        ),
        array('Frontend/Citroen/Actualites/Liste',
            array('PAGE_ID','SITE_ID', 'LANGUE_ID'),
        ),
    );

    public static function render(Pelican_Controller $controller)
    {
        $val = array();
        $id = $controller->zoneValues ["CONTENT_ID"];
        if ($id) {
            $oConnection = Pelican_Db::getInstance();
            $content = $oConnection->queryTab('select c.CONTENT_ID, CONTENT_TITLE from #pref#_content c inner join #pref#_content_version cv on (c.CONTENT_ID=cv.CONTENT_ID and c.CONTENT_CURRENT_VERSION=cv.CONTENT_VERSION and c.LANGUE_ID=cv.LANGUE_ID)
			where c.CONTENT_ID = '.$id.'');
            if ($content) {
                foreach ($content as $values) {
                    $val [$values ['CONTENT_ID']] = $values ['CONTENT_TITLE'];
                }
            }
            $ordre = explode(',', $id);
            foreach ($ordre as $o) {
                $value [$o] = $val [$o];
            }
        }
        $return = $controller->oForm->createContentFromList($controller->multi."CONTENT_ID", t("ACTUALITE"), $value, true, $controller->readO, 1, 300, false, true, Pelican::$config['CONTENT_TYPE_ID']['ACTUALITE'], false);

        return $return;
    }

    public static function save(Pelican_Controller $controller)
    {
        parent::save();
        foreach (self::$decachePublication as $keyCache => $valueCache) {
            Pelican_Cache::clean($valueCache[0]);
        }
    }
}
