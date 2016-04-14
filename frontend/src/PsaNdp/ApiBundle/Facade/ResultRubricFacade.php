<?php
namespace PsaNdp\ApiBundle\Facade;

use JMS\Serializer\Annotation as Serializer;
use OpenOrchestra\BaseApi\Facade\FacadeInterface;

/**
 * Class ResultRubricFacade
 */
class ResultRubricFacade implements FacadeInterface
{
    /**
     * @Serializer\SerializedName("nameRubric")
     * @Serializer\Type("string")
     */
    public $nameRubric;

    /**
     * @Serializer\SerializedName("listQuestions")
     * @Serializer\Type("array<PsaNdp\ApiBundle\Facade\QuestionFacade>")
     */
    public $listQuestions;

    /**
     * @param FacadeInterface $question
     */
    public function addQuestion(FacadeInterface $question = null)
    {
        if ($question !== null) {
            $this->listQuestions[] = $question;
        }
    }
}
