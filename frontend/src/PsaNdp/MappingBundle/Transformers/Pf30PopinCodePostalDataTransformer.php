<?php

namespace PsaNdp\MappingBundle\Transformers;

use PsaNdp\MappingBundle\Object\Block\Pf30PopinCodePostal;

/**
 * Data transformer for Pf30PopinCodePostal block
 */
class Pf30PopinCodePostalDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{

    /**
     * @var Pf30PopinCodePostal
     */
    protected $pf30PopinCodePostal;

    /**
     * @param Pf30PopinCodePostal $pf30PopinCodePostal
     */
    public function __construct(Pf30PopinCodePostal $pf30PopinCodePostal)
    {
        $this->pf30PopinCodePostal = $pf30PopinCodePostal;
    }

    /**
     *  Fetching data slice Popin Code Postal (pf30)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {

        $pf30 = $this->pf30PopinCodePostal->setDataFromArray($dataSource);

        return array(
            'slicePF30' => array(
                'locality' => 'localité',
                'postalCode' => '75018',
                'changeLocality' => 'Changer de localité',
                'changeLocalityPopin' => array(
                    'btnClose' => 'fermer',
                    'desc' => array(
                        'title' => 'renseignez Votre code postal',
                        'txt' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                        'txtError' => 'Veuillez remplir ce champs.',
                        'placeholder' => 'Ex : 75018',
                        'btnCTA' => 'valider'
                    )
                )
            )
        );

    }
}
