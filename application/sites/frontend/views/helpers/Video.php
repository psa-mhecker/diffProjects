<?php
class Frontoffice_Video_Helper
{
    /**
    * @param string $mediaId    Id du media vidéo
    * Retourne le code HTML du player vidéo (player youtube ou player flash)
    */
    public static function getPlayer($mediaId,$sMediaPictureReferent=null,$bAutoPlay = false, $width = '', $height = '')
    {
        $sPlayerHtml = "";
		
		$iAutoPlay = 0;
		if($bAutoPlay){
			   $iAutoPlay = 1;
		}

        // Récupération de la vidéo dans la médiathèque
        $media = Pelican_Cache::fetch("Media/Detail", array($mediaId));
        
        // Si c'est une vidéo youtube, on affiche le player youtube
        if( !empty($media['YOUTUBE_ID']) ){
            if((isset($width) && $width > 0)  && (isset($height) && $height > 0)){
                $size = 'width="'.$width.'" height="'.$height.'"';
            }
            $sPlayerHtml = '<iframe '.$size.' src="//www.youtube.com/embed/'.$media['YOUTUBE_ID'].'?autoplay='.$iAutoPlay.'&autohide=1&fs=1&rel=0&hd=1&wmode=opaque&enablejsapi=1" frameborder="0"></iframe>';
        }
        // Sinon, on utilise le player par défaut
        else {
            // Mapping extension => type MIME
            $extMimeMap = array(
                'ogv'  => 'video/ogg',
                'webm' => 'video/webm',
                'mp4'  => 'video/mp4',
            );
            
            // Récupération des différents formats disponibles pour cette vidéo (media référent)
            $mediaReferent = Pelican_Cache::fetch("Frontend/MediaChild", array($media['MEDIA_ID']));
            if($mediaReferent['MEDIA_REFERENT']['MEDIA_PATH']){
                $filename_referent = basename($mediaReferent['MEDIA_REFERENT']['MEDIA_PATH']);
            }
            $videoList = array($mediaReferent['MEDIA_REFERENT']);
            $videoList = array_merge($videoList, $mediaReferent['MEDIA_ENFANTS']);
            
            // Affichage du player HTML5
            if($bAutoPlay){
                $sPlayerHtml  = "\n".'<video preload="auto" autoplay="true" poster="'.$sMediaPictureReferent.'" controls="controls" data-gtm-js={"type":"video","0":"eventGTM|Video|Click|'.$filename_referent.'|[Perso]|"} >';
            }else{
                $sPlayerHtml  = "\n".'<video preload="none" poster="'.$sMediaPictureReferent.'" controls="controls" data-gtm-js={"type":"video","0":"eventGTM|Video|Click|'.$filename_referent.'|[Perso]|"} >';
            }
            foreach($videoList as $key => $val){
                $sPlayerHtml .= "\n    ".'<source src="'.Pelican::$config['MEDIA_HTTP'].$val['MEDIA_PATH'].'"'.(isset($extMimeMap[$val['EXTENSION']]) ? ' type="'.$extMimeMap[$val['EXTENSION']].'"' : '').' />';
            }
            $sPlayerHtml .= "\n".'</video>'."\n";
        }
        
        return $sPlayerHtml;
    }

    public static function setYoutube($mediaId)
    {
        return '//www.youtube.com/embed/'.$mediaId;
    }

}
?>