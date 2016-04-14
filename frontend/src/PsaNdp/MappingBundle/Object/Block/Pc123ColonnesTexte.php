<?php

namespace PsaNdp\MappingBundle\Object\Block;

/**
 * Class Pc123ColonnesTexte.
 */
class Pc123ColonnesTexte extends Pc72Colonnes
{
    const DISPLAY_CARROUSEL = 0;
    const IMAGE_FORMAT = 55;

    const DESKTOP_FORMAT = 'NDP_MEDIA_ONE_ARTICLE_THREE_VISUAL';
    const MOBILE_FORMAT = 'NDP_GENERIC_4_3_640';

    protected $size = ['desktop' => self::DESKTOP_FORMAT, 'mobile' => self::MOBILE_FORMAT];
}
