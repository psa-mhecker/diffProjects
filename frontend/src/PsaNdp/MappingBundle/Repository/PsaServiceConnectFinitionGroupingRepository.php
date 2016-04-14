<?php namespace PsaNdp\MappingBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;

/**
 * Class PsaServiceConnectFinitionGroupingRepository
 */
class PsaServiceConnectFinitionGroupingRepository extends EntityRepository
{
    /**
     * @param $siteId
     * @param $langId
     * @param $connectServiceId (optional)
     * @param $lcdv4            (optional)
     * 
     * @return QueryBuilder
     */
    private function findModelConnectServiceQb( $siteId, $langId, $connectServiceId ='', $lcdv4='')
    {
        $qb = $this->createQueryBuilder('scfg')
            ->join(
                'PsaNdpMappingBundle:PsaModel', 'model', Expr\Join::WITH, 'model.lcdv4 = scfg.lcdv4'
                .' AND model.gender = :gender'
            )
            ->where('scfg.site = :site')
            ->andWhere('scfg.langue = :language')
            ->setParameter(':site', $siteId)
            ->setParameter(':language', $langId)
            ->setParameter(':gender', 'VP')
        ;
         if(!empty($connectServiceId)){
            $qb->andWhere('scfg.connectedService = :connectService')
                ->setParameter(':connectService', $connectServiceId);
        }
        if(!empty($lcdv4)){
            $qb->andWhere( 'scfg.lcdv4 = :lcdv4')
                ->setPArameter(':lcdv4',$lcdv4);
        }
        return $qb;
    }

    
    
    /**
     * @param $connectServiceId
     * @param $siteId
     * @param $langId
     * 
     * @return array
     */
    public function findModelLabelFromConnectServiceAndSiteAndLanguage($connectServiceId, $siteId, $langId)
    {
        return $this->findModelFromConnectServiceAndSiteAndLanguage($connectServiceId, $siteId, $langId, 'model.model');
    }

    /**
     * @param $connectServiceId
     * @param $siteId
     * @param $langId
     * 
     * @return array
     */
    public function findModelLabelAndLcdvFromConnectServiceAndSiteAndLanguage($connectServiceId, $siteId, $langId)
    {
        return $this->findModelFromConnectServiceAndSiteAndLanguage($connectServiceId, $siteId, $langId, ['scfg.lcdv4','model.model']);
    }

    /**
     * @param $connectServiceId
     * @param $siteId
     * @param $langId
     * @param $select (optional)
     * 
     * @return array
     */
    public function findModelFromConnectServiceAndSiteAndLanguage($connectServiceId, $siteId, $langId, $select = '')
    {
        $qb = $this->findModelConnectServiceQb( $siteId, $langId, $connectServiceId)->distinct();
        $oneDimensionalArrayResult = (!is_array($select) || count($select) == 1);
        $result = $this->getArrayResultWithDefaultFilter($qb, $select, $oneDimensionalArrayResult);

        return $result;
    }

    public function findModelLabelAndLcdvFromConnectServiceIdsAndSiteAndLanguage($connectServiceIds, $siteId, $langId, $select = '')
    {
        $qb = $this->findModelConnectServiceQb( $siteId, $langId);
        $qb = $qb->distinct()->andWhere($qb->expr()->in('scfg.connectedService', implode(',',$connectServiceIds)));
        $oneDimensionalArrayResult = (!is_array($select) || count($select) == 1);
        $result = $this->getArrayResultWithDefaultFilter($qb, $select, $oneDimensionalArrayResult);

        return $result;
    }

    /**
     * @param QueryBuilder $qb
     * @param string|array $select
     * @param bool $oneDimensionalArrayResult
     * @return array
     */
    private function getArrayResultWithDefaultFilter(QueryBuilder $qb, $select = '', $oneDimensionalArrayResult = true)
    {
        if (!empty($select)) {
            $qb->select($select);
        }
        // Filtrer sur les options != 3 ("Non dispo")
        $qb->andWhere('scfg.options != 3');
        $result = $qb->getQuery()->getScalarResult();
        if (true === $oneDimensionalArrayResult) {
            $result = $this->getOneDimensionalArrayFromNestedArray($result);
        }
        return $result;
    }

    /**
     * @param $nestedArray
     * @param string $columnName
     * @return array
     */
    protected function getOneDimensionalArrayFromNestedArray($nestedArray, $columnName = null)
    {
        $result = [];
        if (is_array($nestedArray)) {
            foreach ($nestedArray as $array) {
                if ($columnName === null) {
                    $result[] = reset($array);
                }

                if ($columnName !== null) {
                    $result[] = $array[$columnName];
                }
            }
        }

        return $result;
    }

    /**
     * 
     * @param type $siteId
     * @param type $langId
     * @return type
     */
    public function findModelLabelAndLcdvFromSiteAndLanguage($siteId, $langId)
    {
        return $this->findModelFromSiteAndLanguage($siteId, $langId, ['scfg.lcdv4','model.model']);
    }

    /**
     
     * @param $siteId
     * @param $langId
     * @param $select (optional)
     * @return array
     */
    public function findModelFromSiteAndLanguage($siteId, $langId, $select = '')
    {
        $qb = $this->findModelConnectServiceQb($siteId, $langId);        
        $oneDimensionalArrayResult = (!is_array($select) || count($select) == 1);
        $result = $this->getArrayResultWithDefaultFilter($qb, $select, $oneDimensionalArrayResult);

        return $result;
    }

    
     /**
     * @param int    $siteId
     * @param int    $langId
     * @param string $lcdv4
      *
     * @return array
     */
    public function findConnectedServicesByModelsAndSiteAndLanguage($siteId, $langId, $lcdv4 = '' )
    {
        $qb = $this->findModelConnectServiceQb($siteId, $langId,'', $lcdv4)->distinct();
        $result = $this->getArrayResultWithDefaultFilter($qb, ['scfg.*'], false);

        return $result;
    }
    
    /**
     * @param array $connectServiceIds
     * @param int   $siteId
     * @param int   $langId
     *
     * @return array
     */
    public function findFinitionByConnectServiceIdsAndSiteAndLanguage(array $connectServiceIds, $siteId, $langId)
    {
        $qb = $this->findModelConnectServiceQb($siteId, $langId);

        $qb->andWhere('scfg.options != 3')
            ->andWhere($qb->expr()->in('scfg.connectedService', $connectServiceIds));;
        $result = $qb->getQuery()->getResult();

        return $result;
    }
    
}
