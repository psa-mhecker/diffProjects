<?php
include_once('Pelican/View/plugins/function.gtm.php');

class Backoffice_Share_Helper
{
    /**
     * récupération du sharer
     * @param string $idGroupSharer    Id du groupe de réseaux sociaux
     * @param string $siteId           Id du site
     * @param string $langueId         Langue du site
     *
     * @return array
     */
    public static function getSharer($idGroupSharer, $siteId, $langueId, $shareParams, $additional = array())
    {
        $sSharerHtml = "";
        $fakeView = null;
        switch($shareParams){
            case Pelican::$config['MODE_SHARER'][0]:
                $aSharer = Pelican_Cache::fetch("Frontend/Citroen/GroupeReseauxSociaux", array(
                    $idGroupSharer,
                    $siteId,
                    $langueId
                ));

                if(is_array($aSharer) && count($aSharer) > 0){
                    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https" : "http";
                    $sSharerURL = $scheme.'://'.Pelican::$config["HTTP_HOST"].$_SERVER['REQUEST_URI'];
					
                    if( isset($additional['index_sharebox']) && $additional['index_sharebox'] == true ){
						$sSharerURL = '<%= url %>';
                        $sSharerHtml = '<span class="sharebox addthis_toolbox addthis_default_style addthis_32x32_style" addthis:url="'.$sSharerURL.'">';
                    }elseif(isset($additional['ANCHOR']) && !empty($additional['ANCHOR'])){
						
						if(!empty($additional['MEDIA_IFRAME_SHARE'])){
							$sSharerURL.= $additional['MEDIA_IFRAME_SHARE'];
						}
						 $sSharerURL.=$additional['ANCHOR'];
						$sSharerHtml = '<div class="sharer_iframe addthis_toolbox addthis_default_style addthis_32x32_style" addthis:url="'.$sSharerURL.'" addthis:title="'.$additional['content_title'].'">';
					}else { 
                        $sSharerHtml = '<div class="sharer addthis_toolbox addthis_default_style addthis_32x32_style" addthis:url="'.$sSharerURL.'">';
                    }
                    if(is_array($aSharer) && count($aSharer) > 0){
                        foreach($aSharer as $sharer){
                            $addthisBtn = Pelican::$config['TYPE_RESEAUX_SOCIAUX_DETAIL']['ADDTHIS'][$sharer['RESEAU_SOCIAL_TYPE']];
                            if ($addthisBtn['value'] && $sharer['RESEAU_SOCIAL_TYPE'] != Pelican::$config['TYPE_RESEAUX_SOCIAUX']['AUTRE']) {
                                $addthisBtn = $addthisBtn['value'];
                                $addthisTitle = !empty($sharer['RESEAU_SOCIAL_LABEL']) ? ' title="'.htmlspecialchars($sharer['RESEAU_SOCIAL_LABEL']).'"' : '';

                                // Marquage GTM
                                $networkUrl = parse_url($sharer['RESEAU_SOCIAL_URL_WEB']);
                                $networkName = preg_replace('#^(www\.)?(.*?)\.[a-z]{2,5}$#i', '$2', $networkUrl['host']);
                                
                                $gtmAttr = '';
                                if (!empty($additional['getParams'])) {
                                    $gtmAttr = smarty_function_gtm(array(
                                        'action' => 'SocialShare',
                                        'data' => isset($additional['getParams']) ? $additional['getParams'] : array(),
                                        'datasup' => array('eventLabel' => $sharer['RESEAU_SOCIAL_LABEL']),
                                      
                                    ), $fakeView);
                                }

                                $sSharerHtml .= '<a '.$gtmAttr. ' class="' . $addthisBtn . '"'. $addthisTitle . ' ></a>';
                            }
                        }
                        $addthisBtn = Pelican::$config['TYPE_RESEAUX_SOCIAUX_DETAIL']['ADDTHIS'][Pelican::$config['TYPE_RESEAUX_SOCIAUX']['AUTRE']];
                        if ($addthisBtn['value']) {
                            $addthisBtn = $addthisBtn['value'];
                            $addthisTitle = !empty($sharer['RESEAU_SOCIAL_LABEL']) ? ' title="'.htmlspecialchars($sharer['RESEAU_SOCIAL_LABEL']).'"' : '';

                            $gtmAttr = '';
                          /*  if (!empty($additional['getParams'])) {
                                $gtmAttr = smarty_function_gtm(array(
                                    'name' => 'sharer_ouverture_menu_de_partage',
                                    'data' => isset($additional['getParams']) ? $additional['getParams'] : array(),
                                    'datasup' => array(),
                                    'labelvars' => array(
                                        '%ouverture fermeture%' => 'ouverture',
                                    ),
                                ), $fakeView);
                            }*/

                            $sSharerHtml .= '<a '.$gtmAttr.' class="' . $addthisBtn . '"'.$addthisTitle . '></a>';
                        }
                    }
                    if( isset($additional['index_sharebox']) && $additional['index_sharebox'] == true ){
                        $sSharerHtml .= '</span>';
                    } else {
                        $sSharerHtml .= '</div>';
                    }
                }
            break;
            case Pelican::$config['MODE_SHARER'][1]:
                $sSharerHtml .= '<div class="buttons">
                    <a class="addthis_button_facebook_like" fb:like:layout="box_count"></a>
                    <a class="addthis_button_tweet" tw:count="vertical"></a>
                    <a class="addthis_button_google_plusone" g:plusone:size="tall"></a>
                </div>';
            break;
            case Pelican::$config['MODE_SHARER'][2]:
                $sSharerHtml .= '<div class="buttons">
					<span class="st_twitter_hcount" data-displaytext="Tweet"></span>
					<span class="st_facebook_hcount" data-displaytext="Facebook" addthis:url="david"></span>
					<span class="st_googleplus_hcount" data-displaytext="Google +"></span>
					<span class="st_pinterest_hcount" data-displaytext="Pinterest"></span>
					<span class="st_linkedin_hcount" data-displaytext="LinkedIn"></span>
				</div>';
            break;
            case Pelican::$config['MODE_SHARER'][4]:
                $sSharerHtml .= '
                    <div class="line addthis_toolbox addthis_default_style">
                        <a class="addthis_button_facebook_like" addthis:title="TITRE NEWS"  fb:like:layout="button_count" addthis:url="david"></a>
                        <a class="addthis_button_tweet" addthis:title="TITRE NEWS" ></a>
                        <a class="addthis_button_google_plusone" addthis:title="TITRE NEWS"  g:plusone:size="medium"></a>
                        <a class="addthis_button_linkedin_counter" addthis:title="TITRE NEWS" ></a>
                        <a class="addthis_button_compact" addthis:title="TITRE NEWS" ></a>
                    </div>';
            break;
            case Pelican::$config['MODE_SHARER'][5]:
                $sSharerHtml .= '<div class="addthis_toolbox addthis_default_style social-buttons">
                    <a class="addthis_button_facebook_like" fb:like:layout="box_count"></a>
                    <a class="addthis_button_tweet" tw:count="vertical"></a>
                    <a class="addthis_button_google_plusone" g:plusone:size="tall"></a>
                </div>';
            break;
        }
        return $sSharerHtml;
    }

}
?>
