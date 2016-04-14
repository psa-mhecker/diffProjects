<?php
/**
 * Created by PhpStorm.
 * User: ayoub
 * Date: 23/06/15
 * Time: 11:10
 */

namespace PsaNdp\ApiBundle\Facade;

use JMS\Serializer\Annotation as Serializer;
use OpenOrchestra\BaseApi\Facade\FacadeInterface;

class StickerFacade implements FacadeInterface
{
    /**
     * @Serializer\Type("string")
     */
    public $text;
}
