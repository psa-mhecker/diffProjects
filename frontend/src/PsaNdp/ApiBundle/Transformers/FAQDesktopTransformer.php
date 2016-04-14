<?php
namespace PsaNdp\ApiBundle\Transformers;

use OpenOrchestra\BaseApi\Facade\FacadeInterface;
use OpenOrchestra\BaseApi\Transformer\AbstractTransformer;
use PSA\MigrationBundle\Entity\Content\PsaContentCategory;
use PsaNdp\ApiBundle\Facade\FAQDesktopFacade;

/**
 * Class FAQDesktopTransformer
 */
class FAQDesktopTransformer  extends AbstractTransformer
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
        $FAQDesktop = new FAQDesktopFacade();

        $FAQDesktop->addResultRubric($this->getTransformer('result_rubric')->setAdditionalData($this->additionalData)->transform($mixed));

        return $FAQDesktop;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'faq_desktop';
    }
}
