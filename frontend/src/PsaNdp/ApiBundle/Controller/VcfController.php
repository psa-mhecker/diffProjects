<?php

namespace PsaNdp\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use JeroenDesloovere\VCard\VCard;

/**
 * Class VcfController
 * @package PsaNdp\ApiBundle\Controller
 *
 * @Route("vcf")
 */
class VcfController extends Controller
{

    const TP_404_TEMPLATE_PAGE_ID = 362;

    /**
     * Expected url paremeters zone
     *
     * @Route("/pf11/{langueCode}/{siteId}/{siteGeo}", name="psa_ndp_vcf_pcf11_sitegeo_card")
     *
     * @param string $langueCode
     * @param int    $siteId
     * @param string $siteGeo
     *
     * @return JsonResponse
     */
    public function getVcfCard($langueCode, $siteId, $siteGeo)
    {
        $vCardService = $this->get('psa_ndp_vcard_utils');

        $dealer = $this->getDealer($siteGeo);
        $response =  $this->redirect404($langueCode, $siteId );
        if(!empty($dealer)) {
            /** @var Vcard $card */
            $card = $vCardService->newVCard(
                $dealer['Name'],
                $dealer['Phones']['PhoneNumber'],
                $dealer['Emails']['Email']
            );
            $this->addDealerAddress($dealer, $card);
            $response = $vCardService->downloadResponse($card, true);
        }

        return $response;
    }



    /**
     * @param array $dealer
     * @param Vcard $card
     *
     */
    private function addDealerAddress($dealer, VCard $card)
    {
        $address = $dealer['Address']['Line1'];
        if (!empty($dealer['Address']['Line2'])) {
            $address .= ' '.$dealer['Address']['Line2'];
        }
        if (!empty($dealer['Address']['Line3'])) {
            $address .= ' '.$dealer['Address']['Line3'];
        }
        $card->addAddress(
            $dealer['Name'],
            '',
            $address,
            $dealer['Address']['City'],
            '',
            $dealer['Address']['ZipCode'],
            $dealer['Address']['Country']
        );
    }

    private function redirect404($langueCode, $siteId)
    {

        /** @var \PSA\MigrationBundle\Entity\Page\PsaPage $page */
        $page = $this->get('psa_ndp_page_repository')
            ->findPublishedByTemplateIdQuery(self::TP_404_TEMPLATE_PAGE_ID)
            ->andWhere('page.site = :site')
            ->andWhere('langue.langueCode = :langueCode')
            ->setParameter(':site', $siteId)
            ->setParameter(':langueCode', $langueCode)
            ->getQuery()
            ->getOneOrNullResult()
        ;

        // Protection contre un infinite loop si la page 404 n'est pas publiée
        if ($page === null || !$page->getVersion()->isDisplayable()) {
            return $this->redirect($this->get('request')->getBaseUrl());
        }

        return $this->redirect($page->getVersion()->getPageClearUrl());
    }




    private function getDealer($siteGeo )
    {
        $dealer = null;
        $parameters= [];
        $parameters['SiteGeo'] = $siteGeo;

        try {

            $service = $this->get('annuaire_pdv');
            foreach($parameters as $param=>$value) {
                $service->addParameter($param,$value);
            }

            $result = $service->getDealer();
        } catch(\Exception $e) {
            echo $e->getMessage().' erreur ws';
        }
        if(!empty($result['Dealer'])) {
            $dealer = $result['Dealer'];
        }

       return $dealer;
    }
}