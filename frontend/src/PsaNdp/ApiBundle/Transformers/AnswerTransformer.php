<?php


namespace PsaNdp\ApiBundle\Transformers;

use OpenOrchestra\BaseApi\Facade\FacadeInterface;
use OpenOrchestra\BaseApi\Transformer\AbstractTransformer;
use PsaNdp\ApiBundle\Facade\AnswerFacade;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class AnswerTransformer
 */
class AnswerTransformer extends AbstractTransformer
{
    /** @var TranslatorInterface */
    protected $translator;
    /** @var  string */
    protected $domain;
    /** @var  string */
    protected $locale;
    /** @var array */
    protected $additionalData = [];

    public function setTranslator($translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param  string            $domain
     * @return AnswerTransformer
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * @param  string            $locale
     * @return AnswerTransformer
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
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
     * @param array $mixed
     *
     * @return FacadeInterface
     */
    public function transform($mixed)
    {
        $answer = new AnswerFacade();

        $answer->question = isset($mixed['contentTitle2']) ? $mixed['contentTitle2'] : null;
        $answer->answer = isset($mixed['contentText']) ? $mixed['contentText'] : null;

//        if (isset($mixed['contentCode3']) && $mixed['contentCode3'] === 1) {
        if (true) { // TODO en attente de prise en compte par ISOBAR
            $answer->surveyQuestion = $this->trans('NDP_PC36_HAS_THIS_ANSWER_BEEN_USEFUL_TO_YOU');
            $answer->surveyYes = $this->trans('NDP_PC36_THANK_YOU_FOR_HELPING_US_TO_IMPROVE_OUR_SERVICES');

            $answer->surveyNo = $this->trans('NDP_PC36_WE_ARE_SORRY_TO_GET_YOUR_ANSWER_YOU_CAN_CONTACT_US_BY');
            if (isset($this->additionalData['surveyNo'])) {
                $answer->surveyNo = $this->additionalData['surveyNo'];
            }

            // TODO attente de clarification de specs ou correction ISOBAR
//            $linkUrl = "#";
//            $linkTitle = $this->trans('NDP_PC36_CLICKING_HERE');
//            $answer->link = $this->getTransformer('link')->transform(array('url' => $linkUrl, 'title' => $linkTitle));

            $translateYes = $this->trans('NDP_YES');
            $translateNo = $this->trans('NDP_NO');
            $answer->translate = $this->getTransformer('translate')->transform(array('yes' => $translateYes, 'no' => $translateNo));
            $answer->show = true;
        }

        return $answer;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'answer';
    }

    /**
     * @param $id
     * @param array $parameters
     * @param null  $domain
     * @param null  $locale
     *
     * @return string
     */
    public function trans($id, array $parameters = array(), $domain = null, $locale = null)
    {
        if ($domain == null) {
            $domain = $this->domain;
        }
        if ($locale == null) {
            $locale = $this->locale;
        }

        return $this->translator->trans($id, $parameters, $domain, $locale);
    }
}
