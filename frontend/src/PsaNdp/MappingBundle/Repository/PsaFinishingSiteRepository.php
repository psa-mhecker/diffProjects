<?php

namespace PsaNdp\MappingBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class PsaFinishingSiteRepository
 */
class PsaFinishingSiteRepository extends EntityRepository
{
    /**
     * @param $site
     * @param $languageCode
     * @param $code
     *
     * @return mixed
     */
    public function findOneBySiteIdAndLanguageAndCode($site, $languageCode, $code)
    {
        $finishingSite = null;
        $qb = $this->createQueryBuilder('finishing')
            ->innerJoin(
                'finishing.language',
                'language'
            )
            ->where('finishing.site =:site')
            ->andWhere('language.langueCode =:language')
            ->andWhere('finishing.code =:code')
            ->setParameters(array(':site' => $site, ':language' => $languageCode, 'code' => $code));

        $results = $qb->getQuery()->execute();

        if(!empty($results)){
            $finishingSite = $results[0];
        }

        return $finishingSite;
    }
}
