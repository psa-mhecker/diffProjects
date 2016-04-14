<?php
namespace PsaNdp\ApiBundle\Transformers;

use OpenOrchestra\BaseApi\Facade\FacadeInterface;
use OpenOrchestra\BaseApi\Transformer\AbstractTransformer;
use PSA\MigrationBundle\Entity\Content\PsaContentCategoryCategory;
use PSA\MigrationBundle\Repository\PsaContentRepository;
use PSA\MigrationBundle\Repository\PsaContentTypeRepository;
use PsaNdp\ApiBundle\Facade\QuestionFacade;

/**
 * Class QuestionTransformer
 */
class QuestionTransformer extends AbstractTransformer
{
    /**
     * @var PsaContentRepository
     */
    private $contentRepository;

    /**
     * @var PsaContentTypeRepository
     */
    private $contentTypeRepository;

    /**
     * @var array $additionalData
     */
    protected $additionalData = [];

    public function __construct(PsaContentRepository $contentRepository, PsaContentTypeRepository $contentTypeRepository)
    {
        $this->contentRepository = $contentRepository;
        $this->contentTypeRepository = $contentTypeRepository;
    }

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
     * @param PsaContentCategoryCategory $mixed
     *
     * @return FacadeInterface
     */
    public function transform($mixed)
    {
        $question = new QuestionFacade();

        $childCat = $mixed->getChild();

        $question->question = $childCat->getContentCategoryLabel();

        $siteId = $childCat->getSite()->getSiteId();
        $langId = $childCat->getLangue()->getLangueId();

        $answers = $this->contentRepository
            ->findFaqByCategoryIdSiteIdAndLanguageId($childCat->getContentCategoryId(), $siteId, $langId);

        foreach ($answers as $answer) {
            if (isset($answer['contentWeb']) && intval($answer['contentWeb']) === 1) {
                $question->addAnswer($this->getTransformer('answer')->setDomain($siteId)->setLocale($langId)->setAdditionalData($this->additionalData)->transform($answer));
            }
        }

        if(count($question->listAnwsers) == 0) {
            return null;
        }

        return $question;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'question';
    }
}
