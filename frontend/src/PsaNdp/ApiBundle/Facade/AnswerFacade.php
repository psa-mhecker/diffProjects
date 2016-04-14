<?php
namespace PsaNdp\ApiBundle\Facade;

use JMS\Serializer\Annotation as Serializer;
use OpenOrchestra\BaseApi\Facade\FacadeInterface;

/**
 * Class AnswerFacade
 */
class AnswerFacade implements FacadeInterface
{
    /**
     * @Serializer\Type("string")
     */
    public $question;

    /**
     * @Serializer\Type("string")
     */
    public $answer;

    /**
     * @Serializer\SerializedName("surveyQuestion")
     * @Serializer\Type("string")
     */
    public $surveyQuestion;

    /**
     * @Serializer\SerializedName("surveyYes")
     * @Serializer\Type("string")
     */
    public $surveyYes;

    /**
     * @Serializer\SerializedName("surveyNo")
     * @Serializer\Type("string")
     */
    public $surveyNo;

    /**
     * @Serializer\Type("PsaNdp\ApiBundle\Facade\LinkFacade")
     */
    public $link;

    /**
     * @Serializer\Type("PsaNdp\ApiBundle\Facade\TranslateFacade")
     */
    public $translate;

    /**
     * @Serializer\Type("boolean")
     */
    public $show;
}

