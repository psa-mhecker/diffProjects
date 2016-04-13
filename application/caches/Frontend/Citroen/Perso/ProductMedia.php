<?php
/**
 * Fichier de Pelican_Cache
 * @package Cache
 * @subpackage Pelican
 */
class Frontend_Citroen_Perso_ProductMedia extends Pelican_Cache {

    var $duration = DAY;

    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    function getValue()
    {
        $oConnection = Pelican_Db::getInstance ();
        $aBind[':SITE_ID'] = $this->params[0];

        $sSQL = "
            SELECT
               pp.PRODUCT_ID,
               MEDIA_ID,
               PRODUCT_MEDIA_TYPE
            FROM
              #pref#_perso_product pp
            INNER JOIN #pref#_perso_product_media ppm ON (pp.PRODUCT_ID = ppm.PRODUCT_ID)
            WHERE
              pp.SITE_ID = :SITE_ID

        ";
        $results = $oConnection->queryTab($sSQL, $aBind);

        $media = array();
        if(is_array($results) && count($results)>0){
            foreach($results as $result){
                $aBind[":MEDIA_ID"] = $result['MEDIA_ID'];
                $sSQL = "
                        SELECT
                            MEDIA_PATH
                        FROM
                            #pref#_media
                        WHERE
                            MEDIA_ID = :MEDIA_ID
                ";
                $mediaPath = $oConnection->queryItem($sSQL,$aBind);
                if(!empty($mediaPath)){
                    $media[$result['PRODUCT_ID']][$result['PRODUCT_MEDIA_TYPE']] = $mediaPath;
                    $media['MEDIA_ID'][$result['PRODUCT_ID']][$result['PRODUCT_MEDIA_TYPE']] = $result['MEDIA_ID'];
                }
            }
        }

        $this->value = $media;
    }
}