<?php

/**
 * Fichier de Pelican_Caches_Citroen_Faq : RubriqueContent
 * 
 * Cache remontant les contenus associés à une rubrique de FAQ (donnée de
 * référence)passée en paramètre
 * 
 *
 * @package Cache
 * @subpackage Pelican
 * @author  Mathieu Raiffé <mathieu.raiffe@businessdecision.com>
 * @since   23/08/2013
 * @param 0 SITE_ID                 Identifiant du site
 * @param 1 LANGUE_ID               Identifiant de la langue
 * @param 2 FAQ_RUBRIQUE_ID         Identifiant de la rubrique
 * @param 3 CONTENT_TYPE_ID         Identifiant du type de contenu à remonter
 * @param 4 VERSION                 Version des contenus à remonter CURRENT ou 
 *                                  DRAFT
 * @param 5 MOST_ASKED              Flag permettant de remonter uniquement les
 *                                  questions les plus posées pour une rubrique
 * 
 */
class Frontend_Citroen_Faq_RubriqueContent extends Pelican_Cache {

    var $duration = DAY;

    /** Valeur ou objet à mettre en Pelican_Cache */
    function getValue() {

        $oConnection = Pelican_Db::getInstance();
        /* Initialisation du Bind */
        $aBind[':SITE_ID'] = (int) $this->params[0];
        $aBind[':LANGUE_ID'] = (int) $this->params[1];
        $aBind[':FAQ_RUBRIQUE_ID'] = (int) $this->params[2];
        $aBind[':CONTENT_TYPE_ID'] = (int) $this->params[3];
        $aBind[':CORBEILLE_STATE'] = Pelican::$config["CORBEILLE_STATE"];
   

        /* Initialisation des variables */
        $sTypeVersion = (string) $this->params[4];
        /* Par défaut on ne remonte pas les questions les plus posées */
        $bMostAskedQuestions = false;
        if (isset($this->params[7])) {
            $bMostAskedQuestions = (bool) $this->params[7];
        }
        $aReturn = array();

        /* Si la version n'est pas passée en paramètre, on récupère la version 
         * courante 
         */
        if (empty($sTypeVersion)) {
            $sTypeVersion = 'CURRENT';
        }




        /* Récupération des contenus liés à la rubrique de FAQ par ordre 
         * Si l'ordre n'est pas connu les contenus ne possédant par d'ordre
         * s'afficheront en dernier
         */
        $sSql = <<<SQL
                SELECT
                    c.CONTENT_ID,
                    cv.CONTENT_TITLE,
                    cv.CONTENT_TEXT,
                    cv.CONTENT_TITLE2,
                    cv.CONTENT_CODE3 as VIDEO1_ORDER,
                    cv.CONTENT_CODE2 as VIDEO2_ORDER,
                    cv.CONTENT_CODE as GALLERY_ORDER,
                    
                    cv.CONTENT_TITLE_BO,
                    cv.CONTENT_SHORTTEXT,
                    cv.CONTENT_CLEAR_URL,
                    cv.CONTENT_URL2,
                    cv.CONTENT_SUBTITLE,
                    cv.CONTENT_SUBTITLE2,
                    cv.CONTENT_MOBILE,
                    cv.CONTENT_WEB,
                    cv.STATE_ID,
                    cv.CONTENT_VERSION,
                    cv.PAGE_ID,
                    cv.CONTENT_PICTO_URL,
                    cv.MEDIA_ID3 as VIDEO1_MEDIA_ID,
                    cv.MEDIA_ID4 as VIGNETTE1_MEDIA_ID,
                    cv.MEDIA_ID5 as VIDEO2_MEDIA_ID,
                    cv.MEDIA_ID6 as VIGNETTE2_MEDIA_ID,
                    cv.CONTENT_TITLE5 as LIBELLE_VIDEO1,
                    cv.CONTENT_TITLE6 as LIBELLE_VIDEO2,
  
                    cv.CONTENT_TITLE7 as LIBELLE_GALLERY,
                    cv.CONTENT_START_DATE,
                    cv.CONTENT_END_DATE,
                    cv.MEDIA_ID9,
                
                    
                    frc.FAQ_RUBRIQUE_CONTENT_ORDER
                    
                FROM
                    #pref#_content c
                        INNER JOIN #pref#_content_version cv ON (
                            c.CONTENT_ID = cv.CONTENT_ID 
                            AND c.LANGUE_ID = cv.LANGUE_ID
                            AND c.CONTENT_{$sTypeVersion}_VERSION = cv.CONTENT_VERSION
                            )
                        LEFT JOIN #pref#_faq_rubrique_content frc ON (
                            c.CONTENT_ID = frc.CONTENT_ID
                            AND c.LANGUE_ID = frc.LANGUE_ID
                            AND cv.CONTENT_TITLE13 = frc.FAQ_RUBRIQUE_ID
                            )
                WHERE
                    c.SITE_ID = :SITE_ID
                    AND c.LANGUE_ID = :LANGUE_ID
                    AND c.CONTENT_TYPE_ID = :CONTENT_TYPE_ID
                    AND cv.CONTENT_TITLE13 = :FAQ_RUBRIQUE_ID
                    AND cv.STATE_ID <> :CORBEILLE_STATE
                    
SQL;
        if ($bMostAskedQuestions == true) {
            $sSql .= <<<SQL
                    AND cv.CONTENT_SUBTITLE2 = '1'
SQL;
        }
        
         if ($this->params[5] == true) {
            $sSql .= <<<SQL
                   AND cv.CONTENT_WEB = 1
SQL;
        }

        $sSql .= <<<SQL
                ORDER BY frc.FAQ_RUBRIQUE_CONTENT_ORDER, c.CONTENT_ID
SQL;


        $aRubriqueContents = $oConnection->queryTab($sSql, $aBind);
        if (count($aRubriqueContents)) {

            for ($i = 0; $i < count($aRubriqueContents); $i++) {
                // $aRubriqueContentIds[]=$aRubriqueContent['CONTENT_ID'];
                //constructMedias
           
                $aPushMediaOrders = array(
                    'VIDEO1' => $aRubriqueContents[$i]['VIDEO1_ORDER'],
                    'VIDEO2' => $aRubriqueContents[$i]['VIDEO2_ORDER'],
                    'GALLERY' => $aRubriqueContents[$i]['GALLERY_ORDER']
                );


                if($aPushMediaOrders['VIDEO1'] ==  $aPushMediaOrders['VIDEO2'] && $aPushMediaOrders['VIDEO1'] == $aPushMediaOrders['GALLERY'] )
                {
                    krsort($aPushMediaOrders);
                      
                }
                else
                {
                     asort($aPushMediaOrders);
                }
                 
                
                
                foreach ($aPushMediaOrders as $mediaType => $order) {

                    switch ($mediaType) {
                        case'VIDEO1':
                            $aRubriqueContents[$i]['PUSH_MEDIA'][] = array(
                                'type' => 'VIDEO',
                                'video' => Pelican_Cache::fetch('Media/Detail', array($aRubriqueContents[$i]['VIDEO1_MEDIA_ID'])),
                                'thumb' => Pelican_Cache::fetch('Media/Detail', array($aRubriqueContents[$i]['VIGNETTE1_MEDIA_ID'])),
                                'lib' => $aRubriqueContents[$i]['LIBELLE_VIDEO1']
                            );
                            break;
                        case'VIDEO2':
                            $aRubriqueContents[$i]['PUSH_MEDIA'][] = array(
                                'type' => 'VIDEO',
                                'video' => Pelican_Cache::fetch('Media/Detail', array($aRubriqueContents[$i]['VIDEO2_MEDIA_ID'])),
                                'thumb' => Pelican_Cache::fetch('Media/Detail', array($aRubriqueContents[$i]['VIGNETTE2_MEDIA_ID'])),
                                'lib' => $aRubriqueContents[$i]['LIBELLE_VIDEO2']
                            );

                            break;
                        case'GALLERY':
                            $aBindGallery = array(
                                ':CONTENT_ID' => $aRubriqueContents[$i]['CONTENT_ID'],
                                ':LANGUE_ID' => (int) $this->params[1],
                                ':PAGE_ID' => $aRubriqueContents[$i]['PAGE_ID'],
                                ':CONTENT_VERSION' => $aRubriqueContents[$i]['CONTENT_VERSION'],
                            );

                            $sSqlGallery = "
                            SELECT *               
                                FROM #pref#_content_zone_multi cz
                                WHERE
                                cz.CONTENT_ZONE_MULTI_TYPE = 'GALLERYFORM'
                                AND
                                cz.LANGUE_ID = :LANGUE_ID
                                AND
                                cz.CONTENT_ZONE_ID = :PAGE_ID
                                AND
                                cz.CONTENT_ID = :CONTENT_ID
                                AND
                                cz.CONTENT_VERSION = :CONTENT_VERSION
                            ";
                            $aGalleryMedias = $oConnection->queryTab($sSqlGallery, $aBindGallery);
                            $aGalleryMediaDetails = array();
                            if ($aGalleryMedias) {

                                foreach ($aGalleryMedias as $aGalleryMedia) {
                                    $aGalleryMediaDetails[$aGalleryMedia['CONTENT_ZONE_MULTI_ORDER']-1] = Pelican_Cache::fetch('Media/Detail', array($aGalleryMedia['MEDIA_ID']));
                                }
                            }



                            $aRubriqueContents[$i]['PUSH_MEDIA'][] = array(
                                'type' => 'GALLERY',
                                'gallery' => $aGalleryMediaDetails,
                                'lib' => $aRubriqueContents[$i]['LIBELLE_GALLERY']
                            );
                            break;
                    }
                }
            }
        }

        //debug(count($aRubriqueContents));
        if (is_array($aRubriqueContents)) {
            $aReturn = $aRubriqueContents;
        }
        $this->value = $aReturn;
    }

}

?>