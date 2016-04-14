<?php


namespace PsaNdp\MappingBundle\Utils;

use JeroenDesloovere\VCard\VCard;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class VCardUtils
 * @package PsaNdp\MappingBundle\Utils
 */
class VCardUtils
{

    /**
     * Create a new Vard
     *
     * @param null|string $name
     * @param null|string $phone
     * @param null|string $email
     *
     * @return VCard
     */
    public function newVCard($name = null, $phone = null, $email = null)
    {
        $vcard = new VCard();

        if ($name !== null) {
            $vcard->addName($name);
        }
        if ($phone !== null) {
            $vcard->addPhoneNumber($phone, 'PREF;WORK');
        }

        if ($email !== null) {
            $vcard->addEmail($email);
        }
        // Check Vard Object for other field

        return $vcard;
    }

    /**
     * Return a Response with .vcs file genereated for dwd. Optionnally set iOs option to dwd .ics file for iOs Devices
     *
     * @param VCard $vcard
     * @param bool  $checkIOsIcs option to dwd .ics file instead of .vcs for iOs Devices
     *
     * @return Response
     */
    public function downloadResponse(VCard $vcard, $checkIOsIcs)
    {
        $response = new Response();

        // define header and output
        $output = $vcard->buildVCard();
        $response->headers->set('Content-type', 'text/x-vcard; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename=' . $vcard->getFilename() . '.vcf;');

        // Activate below code if needed for iOs to create a ics file
        if ($checkIOsIcs && $vcard->isIOS()) {
            $output = $vcard->buildVCalendar();
            $response->headers->set('Content-type', 'text/x-vcalendar; charset=utf-8');
            $response->headers->set('Content-Disposition', 'attachment; filename=' . $vcard->getFilename() . '.ics;');
        }

        $response->setContent($output);

        return $response;
    }
}
