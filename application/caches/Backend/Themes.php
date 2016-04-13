<?php
	/**
	* @package Cache
	* @subpackage General
	*/
	 
	/**
	* Fichier de Pelican_Cache : Resultat de requete sur Rubrique Faq et theme actualites, l'ensemble constitue les themes
	*
	* retour : id, lib
	*
	* @package Cache
	* @subpackage General
	* @author Rim Karray <rim.karray@businessdecision.com>
	* @since 17/02/2015
	*/
	class Backend_Themes extends Pelican_Cache {
		 
		/** Valeur ou objet a  mettre en Pelican_Cache */
		public function getValue() {
			 
			
			$oConnection = Pelican_Db::getInstance();
			$aBind[':SITE_ID'] = $this->params[0];
        	$aBind[':LANGUE_ID'] = $this->params[1];
        	
      		/*-------------Rubriques FAQ--------------*/
      		
        	$sSql = "SELECT 
                
                    	FAQ_RUBRIQUE_ID as \"id\",
                    	concat('&nbsp;&nbsp;',FAQ_RUBRIQUE_LABEL) as \"lib\" 
                    
               		FROM 
                    	#pref#_faq_rubrique
                    
                	WHERE 
                    	SITE_ID = :SITE_ID
                    	AND LANGUE_ID = :LANGUE_ID ";

	        
	      
            $rubFaq =   $oConnection->queryTab($sSql,$aBind);
         	array_unshift($rubFaq, array('id'=>-1, 'lib'=>'FAQ'));
         	
         	/*-------------Themes actualites--------------*/
      		
         	$sSql = "SELECT 
						concat('theme_', THEME_ACTUALITES_ID)  \"id\",
						concat('&nbsp;&nbsp;', THEME_ACTUALITES_LABEL) as \"lib\" 
						
					FROM 
						#pref#_theme_actualites 
						
					WHERE 
						SITE_ID = :SITE_ID 
						AND LANGUE_ID = :LANGUE_ID";
							
           $themeActu =   $oConnection->queryTab($sSql,$aBind);
           array_unshift($themeActu, array('id'=>0, 'lib'=>'Actualit&eacute;s'));
           
           /*-------------Generation d'un seul tableau de themes a partir des 2 tableaux--------------*/
      		
           $Themes=array_merge($rubFaq, $themeActu);
           
           $this->value=$Themes;
		}

}
 ?>