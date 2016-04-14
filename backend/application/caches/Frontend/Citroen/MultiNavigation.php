<?php
/**
 */

/**
 * Fichier de Pelican_Cache : Résultat de requête sur PAGE_ZONE_MULTI.
 *
 * retour : id, lib
 *
 * @author Kristopher Perin <kristopher.perin@businessdecision.com>
 *
 * @since 09/07/2013
 */
class Frontend_Citroen_MultiNavigation extends Citroen_Cache
{
    public $duration = DAY;

    public $isPersistent = true;

    /** Valeur ou objet à mettre en Pelican_Cache */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();

        $aBind = array();
        $aBind[":PAGE_ID"] = $this->params[0];
        $aBind[":PAGE_VERSION"] = $this->params[1];
        $aBind[":LANGUE_ID"] = $this->params[2];
        $aBind[":PAGE_MULTI_TYPE"] = $oConnection->strToBind($this->params[3]);

        $sSQL = "select
                    pm.*,
                    m.MEDIA_PATH,
                    m.MEDIA_ALT
                from #pref#_page_multi pm
                left join #pref#_media m
                    on (pm.MEDIA_ID = m.MEDIA_ID)
                where pm.PAGE_ID = :PAGE_ID
                and pm.PAGE_VERSION = :PAGE_VERSION
                and pm.LANGUE_ID = :LANGUE_ID
                and pm.PAGE_MULTI_TYPE = :PAGE_MULTI_TYPE
                order by pm.PAGE_ZONE_MULTI_ORDER asc";

        $results = $oConnection->queryTab($sSQL, $aBind);

        if (($this->params[3] == 'PUSH_CONTENU_ANNEXE' || $this->params[3] == 'PUSH') && $results) {
            foreach ($results as $key => $value) {
                if ($results[$key]['MEDIA_PATH']) {
                    $results[$key]['MEDIA_PATH'] = Pelican_Media::getFileNameMediaFormat($results[$key]['MEDIA_PATH'], Pelican::$config['MEDIA_FORMAT_ID']['EXPAND_PUSH']);
                }
            }
        }
        $this->value = $results;
    }

    /** Valeur ou objet à mettre en Pelican_Cache */
    public function getValueProfiling()
    {
        $params = $this->params;
        $perso = self::$perso;
        $return = !empty($perso[$params[3]]) ? $perso[$params[3]] : array();
        if (($this->params[3] == 'PUSH_CONTENU_ANNEXE' || $this->params[3] == 'PUSH') && $return) {
            foreach ($return as $key => $value) {
                if ($return[$key]['MEDIA_ID']) {
                    $return[$key]['MEDIA_PATH'] = Pelican_Media::getFileNameMediaFormat(Pelican_Media::getMediaPath($return[$key]['MEDIA_ID']), Pelican::$config['MEDIA_FORMAT_ID']['EXPAND_PUSH']);
                }
            }
        }
        $this->value = $return;
    }
}
