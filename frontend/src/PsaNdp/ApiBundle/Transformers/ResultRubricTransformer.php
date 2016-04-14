<?php
namespace PsaNdp\ApiBundle\Transformers;

use OpenOrchestra\BaseApi\Facade\FacadeInterface;
use OpenOrchestra\BaseApi\Transformer\AbstractTransformer;
use PSA\MigrationBundle\Entity\Content\PsaContentCategory;
use PsaNdp\ApiBundle\Facade\ResultRubricFacade;

/**
 * Class ResultRubricTransformer
 */
class ResultRubricTransformer  extends AbstractTransformer
{
    /**
     * @var array $additionalData
     */
    protected $additionalData = [];

    /**
     * @param  array $additionalData
     * @return $this
     */
    public function setAdditionalData($additionalData)
    {
        $this->additionalData = $additionalData;

        return $this;
    }

    /**
     * @param PsaContentCategory $mixed
     *
     * @return FacadeInterface
     */
    public function transform($mixed)
    {
        $resultRubric = new ResultRubricFacade();

        $resultRubric->nameRubric = $mixed->getContentCategoryLabel();

        foreach ($mixed->getChildCategories() as $childCategory) {
            $resultRubric->addQuestion($this->getTransformer('question')->setAdditionalData($this->additionalData)->transform($childCategory));
        }

        return $resultRubric;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'result_rubric';
    }
}
