<?php
/**
 * Fichier de Pelican_Cache : OutilAideChoixFinancement
 * Retourne les données du bloc produit financier (2ColonneMixteEnrichi).
 */
class Frontend_Citroen_OutilAideChoixFinancement_ProduitFinancier extends Pelican_Cache
{
    public $duration = DAY;

    /*
     * Valeur ou objet à mettre en Pelican_Cache
     */
    public function getValue()
    {
        $aBind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
        $aBind[':LANGUE_ID'] = $_SESSION[APP]['LANGUE_ID'];
        $aBind[':PAGE_STATUS'] = 1;
        $aBind[':PAGE_ID'] = $this->params[0];
        $aBind[':ZONE_ORDER'] = $this->params[1];
        $aBind[':ZONE_ID'] = Pelican::$config['ZONE']['2COLONNES_MIXTE_ENRICHI'];
        $aProduct = array();
        $sSql = "select *
                    from #pref#_page p
                    inner join #pref#_page_version pv
                        on (pv.PAGE_ID = p.PAGE_ID
                            and pv.PAGE_VERSION = p.PAGE_CURRENT_VERSION
                            and pv.LANGUE_ID = p.LANGUE_ID)
                    inner join #pref#_page_multi_zone pmz
                        on (pmz.PAGE_ID = pv.PAGE_ID
                            and pmz.PAGE_VERSION = pv.PAGE_VERSION
                            and pmz.LANGUE_ID = pv.LANGUE_ID)
                    where p.SITE_ID = :SITE_ID
                    and p.LANGUE_ID = :LANGUE_ID
                    and pv.STATE_ID = 4
                    and p.PAGE_STATUS = 1
                    and p.PAGE_STATUS = :PAGE_STATUS
                    and p.PAGE_ID = :PAGE_ID
                    and pmz.ZONE_ID = :ZONE_ID
                    and pmz.ZONE_ORDER = :ZONE_ORDER
                    and pmz.ZONE_PARAMETERS = 1
                    ";
        $oConnection = Pelican_Db::getInstance();
        $aProduct = $oConnection->queryRow($sSql, $aBind);
        $this->value = $aProduct;
    }
}
