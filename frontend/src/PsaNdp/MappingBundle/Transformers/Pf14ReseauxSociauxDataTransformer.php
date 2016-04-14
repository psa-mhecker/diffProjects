<?php

namespace PsaNdp\MappingBundle\Transformers;

/**
 * Data transformer for Pf14ReseauxSociaux block
 */
class Pf14ReseauxSociauxDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{
    const TARGET_BLANK = 2;
    const CTA_CLASS    = 'more';
    const CTA_STYLE    = 'STYLE_SIMPLELINK';
    const NO_DATE      = 0;
    const FORMAT_PICTO = 84;

    /**
     *
     * @var string
     */
    private $articleTitle = "";

    /**
     *
     * @var bool
     */
    protected $isMobile;

    /**
     *  Fetching data slice Reseaux Sociaux (pf14)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {
        $this->articleTitle = $dataSource['block']->getZoneTitre();
        $this->isMobile = $isMobile;
        $result =  array(
            'slicePF14' => array(
                'title' => $dataSource['block']->getZoneTitre(),
            )
        );

        $this->getReseauxSociauxData($dataSource['reseauxSociaux'], $result['slicePF14']);

        return $result;
    }

    public function getReseauxSociauxData($reseauxSociaux, &$data)
    {
        $data['socNetwork'] = [];
        $data['boxSocNet'] = [];
        $data['links'] = [];

        foreach ($reseauxSociaux as $reseauSocial) {
            if (isset($reseauSocial['UNDEFINED']) || !isset($reseauSocial['ENTITE'])) {
                continue;
            }
            $url = $reseauSocial['ENTITE']->getReseauSocialUrlWeb();

            // recuperation des 3 derniers posts
            $socNetwork = $this->getPostsData($reseauSocial['POSTS'], 3);
            $media = $reseauSocial['ENTITE']->getMedia();
            $mediaPath = '';
            $mediaAlt = '';
            if ($media) {
                $mediaPath = $this->mediaServer.$media->getMediaPathWithFormat(self::FORMAT_PICTO);
                $mediaAlt = $media->getMediaAlt();
            }
            // recuperation du widget
            $boxSocNet = array(
                'ico' => $mediaPath,
                'icoActive' => $mediaPath,
                'alt' => $mediaAlt,
                'script' => $reseauSocial['WIDGET'],
                'url' => $url,
                'target' => '_blank',
            );
            // recuperation du lien
            $tmpLangVar = $this->trans('NDP_FOLLOW_US_ON');
            $link = array(
                'class' => static::CTA_CLASS,
                'style' => static::CTA_STYLE,
                'title' => $tmpLangVar.' '.$reseauSocial['ENTITE']->getReseauSocialLabel(),
                'url' => $url
            );

            if (intval($reseauSocial['ENTITE']->getReseauSocialUrlModeOuverture()) === static::TARGET_BLANK) {
                $link['target'] = '_blank';
            }

            $data['socNetwork'][$reseauSocial['CLASS']] = $socNetwork;
            $data['boxSocNet'][] = $boxSocNet;
            $data['links'][] = $link;
        }
    }

    /**
     * Return Data Transformer for Array of Post section
     *
     * @param array $posts
     * @param int   $nbPosts
     *
     * @return array $data

     */
    public function getPostsData($posts = array(), $nbPosts = 3)
    {
        $data = array();
        if(!empty($posts)) {
            foreach ($posts as $post) {
                $data[] = array(
                    'img' => array(
                        'src' => $post['img']['src'],
                        'alt' => $this->articleTitle,
                        'url' => $post['img']['url']
                    ),
                    'text' => $this->addPostDate($post['text'], $post['diffDate'])
                );

                if (count($data) == $nbPosts) {
                    break;
                }
            }
        }

        return $data;
    }

    /**
     *
     * @param string $text
     * @param \DateInterval $diffDate
     *
     * @return string
     */
    private function addPostDate($text, $diffDate)
    {
        if ($diffDate != NULL) {
            $text .= '<p>'.$this->trans("NDP_POSTED").' ';
            $time = '';
            if ($diffDate->s != self::NO_DATE) {
                $time = $diffDate->s.' '.$this->trans("NDP_POSTED_SECOND");
                if ($diffDate->s > 1) {
                    $time = $diffDate->s.' '.$this->trans("NDP_POSTED_SECONDS");
                }
            }
            if ($diffDate->i != self::NO_DATE) {
                $time = $diffDate->i.' '.$this->trans("NDP_POSTED_MINUTE");
                if ($diffDate->i > 1) {
                    $time = $diffDate->i.' '.$this->trans("NDP_POSTED_MINUTES");
                }
            }
            if ($diffDate->h != self::NO_DATE) {
                $time = $diffDate->h.' '.$this->trans("NDP_POSTED_HOUR");
                if ($diffDate->h > 1) {
                    $time = $diffDate->h.' '.$this->trans("NDP_POSTED_HOURS");
                }
            }
            if ($diffDate->days != self::NO_DATE) {
                $time = $diffDate->days.' '.$this->trans("NDP_POSTED_DAY");
                if ($diffDate->days > 1) {
                    $time = $diffDate->days.' '.$this->trans("NDP_POSTED_DAYS");
                }
            }
            $text .= $time.'</p>';

        }

        return $text;
    }
}
