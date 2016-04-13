<?php
/**
 * Fichier de Pelican_Cache : Retour WS CitroenAdvisor
 * @package Cache
 * @subpackage Pelican
 */
 
use Citroen\Annuaire;
 
class Frontend_Citroen_Annuaire_citroenAdvisor extends Pelican_Cache 
{

    var $duration = DAY;
    
    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    function getValue()
    {
		$url = $this->params[0];
		$advisor = $this->params[1];
		
		$proxy = NULL;
    	if (isset(Pelican::$config['PROXY']) && !empty(Pelican::$config['PROXY'])) {
				$proxy = array(
					CURLOPT_PROXYTYPE => CURLPROXY_HTTP,
					CURLOPT_PROXY => Pelican::$config['PROXY']['URL'],
					CURLOPT_PROXYPORT => Pelican::$config['PROXY']['PORT'],
					CURLOPT_PROXYUSERPWD => sprintf('%s:%s', Pelican::$config['PROXY']['LOGIN'], Pelican::$config['PROXY']['PWD'])
				);
		}
		$url=$url.$advisor."/info";
		$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
      	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);	
        curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
        curl_setopt($ch, CURLOPT_PROXY, Pelican::$config['PROXY']['URL']);
		curl_setopt($ch, CURLOPT_PROXYPORT, Pelican::$config['PROXY']['PORT']);
		curl_setopt($ch, CURLOPT_PROXYUSERPWD, sprintf('%s:%s', Pelican::$config['PROXY']['LOGIN'], Pelican::$config['PROXY']['PWD']));
        
        
        
        $head = curl_exec($ch); 
       	curl_close($ch);
        
    	            
		if($head){
    		$this->value = json_decode($head, true);
		}
		else{
			$this->value = "";
		}

    }
}