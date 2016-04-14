<?php

namespace PsaNdp\MappingBundle\Transformers;

/**
 * Data transformer for Pt18PreHomeImportateur block
 */
class Pt18PreHomeImportateurDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{
    /**
     *  Fetching data slice Pre Home Importateur (pt18)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {
        $result = [];

        if (!$isMobile) {
            $result = $this->getImporterDataDesktop($dataSource);
        }

        if ($isMobile) {
            $result = $this->getImporterDataMobile($dataSource);
        }

        return $result;
    }

    /**
     * Data Transformer for "Choix des importateurs" Desktop
     *
     * @todo : transform the data
     * @param array $data
     * @return array
     */
    public function getImporterDataDesktop(array $data)
    {
        $result = array(
            'pt18' => array(
                'headerSection'      => array(
                    'title' => 'choisissez votre importateur'
                ),
                'welcomeSection'     => array(
                    'title'    => 'bienvenue sur le site de peugeot',
                    'city'     => 'vatican-sur-mer',
                    'subtitle' => 'Veuillez choisir l’importateur de votre choix :'
                ),
                'importLeftSection'  => array(
                    'title'          => 'nom#1',
                    'subtitle'       => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis lacinia lobortis lacus quis sagittis. Nulla id purus vulputate, eleifend nisl et, euismod massa. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis lacinia lobortis lacus quis sagittis.',
                    'visuMap'        => array(
                        'url' => $this->mediaServer . '/desktop/img/.jpg'
                    ),
                    'ctaImport'      => array(
                        'title'   => 'choisir cet importateur',
                        'urlLink' => '#'
                    ),
                    'visuMap'        => array(
                        'url' => $this->mediaServer . '/desktop/img/map1.jpg'
                    ),
                    'promoSection'   => array(
                        'urlLink' => '#',
                        'visu'    => array(
                            'url' => $this->mediaServer . '/desktop/img/thumb1.jpg'
                        ),
                    ),
                    'subtitleBottom' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis lacinia lobortis lacus quis sagittis. Nulla id purus vulputate, eleifend nisl et, euismod massa. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis lacinia lobortis lacus quis sagittis.'
                ),
                'importRightSection' => array(
                    'title'          => 'nom#2',
                    'subtitle'       => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis lacinia lobortis lacus quis sagittis. Nulla id purus vulputate, eleifend nisl et, euismod massa. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis lacinia lobortis lacus quis sagittis.',
                    'visuMap'        => array(
                        'url' => $this->mediaServer . '/desktop/img/.jpg'
                    ),
                    'ctaImport'      => array(
                        'title'   => 'choisir cet importateur',
                        'urlLink' => '#'
                    ),
                    'visuMap'        => array(
                        'url' => $this->mediaServer . '/desktop/img/map2.jpg'
                    ),
                    'promoSection'   => array(
                        'urlLink' => '#',
                        'visu'    => array(
                            'url' => $this->mediaServer . '/desktop/img/thumb2.jpg'
                        ),
                    ),
                    'subtitleBottom' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis lacinia lobortis lacus quis sagittis. Nulla id purus vulputate, eleifend nisl et, euismod massa. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis lacinia lobortis lacus quis sagittis.'
                )
            )
        );

        return $result;
    }

    /**
     * Data Transformer for "Choix des importateurs" Mobile
     *
     * @todo : transform the data
     * @param array $data
     * @return array
     */
    public function getImporterDataMobile(array $data)
    {
        $result = [];
        if (isset($data['list'])) {
            $result = array(
                'welcomeSection'      => array(
                    'title'    => 'bienvenue sur le site de peugeot',
                    'city'     => 'vatican-sur-mer',
                    'subtitle' => 'Veuillez choisir l’importateur de votre choix :'
                ),
                'importerListSection' => array(
                    'importer1' => array(
                        'title' => 'nom#1',
                        'desc'  => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis lacinia lobortis lacus quis sagittis. Nulla id purus vulputate, eleifend nisl et, euismod massa.',
                        'cta1'  => array(
                            'title' => 'choisir cet importateur',
                            'url'   => '#'
                        ),
                        'cta2'  => array(
                            'title' => 'en savoir plus',
                            'url'   => 'default-nom-1.html'
                        )
                    ),
                    'importer2' => array(
                        'title' => 'nom#2',
                        'desc'  => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis lacinia lobortis lacus quis sagittis. Nulla id purus vulputate, eleifend nisl et, euismod massa.',
                        'cta1'  => array(
                            'title' => 'choisir cet importateur',
                            'url'   => '#'
                        ),
                        'cta2'  => array(
                            'title' => 'en savoir plus',
                            'url'   => 'default-nom-2.html'
                        )
                    ),
                )
            );
        }
        if (isset($data['importer1'])) {
            $result = array(
                'headerUrl'            => 'default-defaut.html',
                'firstImporterSection' => array(
                    'title'    => 'nom#1',
                    'desc'     => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis lacinia lobortis lacus quis sagittis. Nulla id purus vulputate, eleifend nisl et, euismod massa.',
                    'mapUrl'   => $this->mediaServer . '/mobile/img/visuel-map.png',
                    'cta'      => array(
                        'title' => 'choisir cet importateur',
                        'url'   => '#'
                    ),
                    'thumbUrl' => $this->mediaServer . '/mobile/img/visuel-thumb.png',
                    'descFoot' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis lacinia lobortis lacus quis sagittis. Nulla id purus vulputate, eleifend nisl et, euismod massa.'
                )
            );
        }
        if (isset($data['importer2'])) {
            $result = array(
                'headerUrl'            => 'default-defaut.html',
                'firstImporterSection' => array(
                    'title'    => 'nom#2',
                    'desc'     => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis lacinia lobortis lacus quis sagittis. Nulla id purus vulputate, eleifend nisl et, euismod massa.',
                    'mapUrl'   => $this->mediaServer . '/mobile/img/visuel-map.png',
                    'cta'      => array(
                        'title' => 'choisir cet importateur',
                        'url'   => '#'
                    ),
                    'thumbUrl' => $this->mediaServer . '/mobile/img/visuel-thumb.png',
                    'descFoot' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis lacinia lobortis lacus quis sagittis. Nulla id purus vulputate, eleifend nisl et, euismod massa.'
                )
            );
        }

        return $result;
    }
}
