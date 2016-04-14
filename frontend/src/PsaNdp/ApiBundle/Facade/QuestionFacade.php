<?php
namespace PsaNdp\ApiBundle\Facade;

use JMS\Serializer\Annotation as Serializer;
use OpenOrchestra\BaseApi\Facade\FacadeInterface;

/**
 * Class QuestionFacade
 */
class QuestionFacade implements FacadeInterface
{
    /**
     * @Serializer\Type("string")
     */
    public $question;

    /**
     * @Serializer\SerializedName("listAnwsers")
     * @Serializer\Type("array<PsaNdp\ApiBundle\Facade\AnswerFacade>")
     */
    public $listAnwsers;

    public function addAnswer(FacadeInterface $answer)
    {
        $this->listAnwsers[] = $answer;
    }
}
