<?php

namespace PsaNdp\MappingBundle\Transformers;

use PSA\MigrationBundle\Entity\Page\PsaPageZone;
use PsaNdp\MappingBundle\Object\Block\Pc36FAQ;

/**
 * Data transformer for Pc36FAQ block
 */
class Pc36FAQDataTransformer extends AbstractDataTransformer implements DataTransformerInterface
{

    /**
     * @var Pc36FAQ
     */
    protected $pc36FAQ;

    /**
     * @param Pc36FAQ $faq
     */
    public function __construct(Pc36FAQ $faq)
    {
        $this->pc36FAQ = $faq;
    }

    /**
     *  Fetching data slice FAQ (pc36)
     *
     * @param array $dataSource data source fetch from its associated DataSourceInterface fetch() call
     * @param bool  $isMobile   Indicate if is a mobile display
     *
     * @return array
     */
    public function fetch(array $dataSource, $isMobile)
    {
        $result = [];

        if (!$isMobile) {
            $result = $this->getFaqTemplate($dataSource);
        }

        if ($isMobile) {
            $showQuestionList = (!empty($dataSource['selectedCatAndQuestions']));

            if ($showQuestionList) {
                $result = $this->getFaqListMobileTemplateData($dataSource);
            }

            if (!$showQuestionList) {
                $result = $this->getFaqFirstLevelMobileTemplateData($dataSource);
            }
        }

        return array('slicePC36' => $result);
    }

    /**
     * Data Transformer for Desktop FAQ
     *
     * @param array $dataSource
     *
     * @return array
     */
    protected function getFaqTemplate(array $dataSource)
    {
        $selectedCatAndQuestions = $dataSource['selectedCatAndQuestions'];

        $faqTitle = '';
        if (isset($dataSource['pageZone']) && $dataSource['pageZone'] instanceof PsaPageZone) {
            $faqTitle = $dataSource['pageZone']->getZoneTitre();
        }

        if (!$faqTitle) {
            $faqTitle = $this->trans('NDP_PC36_CHOOSE_A_RUBRIC');
        }

        $result = array(
            // Titre
            'faqTitle' => $faqTitle,

            // Les rubriques
            'faqCat' => $dataSource['firstLevelData'],

            'faqSubtitle' => $selectedCatAndQuestions['parentTitle'],
            'faqSubCat' => $this->getFaqSubCatData($selectedCatAndQuestions['subCatArray']),

            'faqCTA' => $this->getFaqCTAData($dataSource)
        );

        $dataSource = array_merge($dataSource, $result);
        $this->pc36FAQ->setDataFromArray($dataSource);

        return $this->pc36FAQ;
    }

    /**
     * Data Transformer for display first level category for Mobile FAQ
     *
     * @param array $dataSource
     *
     * @return array
     */
    protected function getFaqFirstLevelMobileTemplateData(array $dataSource)
    {
        $result = array(
            'faqCat' => $dataSource['firstLevelData'],

            'faqTitle' => $this->trans('NDP_FAQ'),
            'faqSubTitle' => $this->trans('NDP_PC36_CHOOSE_A_RUBRIC'),

            'faqSurvey' => $this->getFaqSurveyData($dataSource),

            'faqCTA' => $this->getFaqCTAData($dataSource)
        );

        $dataSource = array_merge($dataSource, $result);
        $this->pc36FAQ->setDataFromArray($dataSource);

        return $this->pc36FAQ;
    }

    /**
     * Data Transformer for list of questions for a specifc category for Mobile FAQ
     *
     * @param array $dataSource
     *
     * @return array
     */
    protected function getFaqListMobileTemplateData(array $dataSource)
    {
        $selectedCatAndQuestions = $dataSource['selectedCatAndQuestions'];

        $result = array(
            'faqSubTitle' => $selectedCatAndQuestions['parentTitle'],
            'faqSubCat' => $this->getFaqSubCatData($selectedCatAndQuestions['subCatArray']),
            'faqBackURL' => $dataSource['faqBackURL'],

            'faqSurvey' => $this->getFaqSurveyData($dataSource)
        );

        $this->pc36FAQ->setDataFromArray($result);
        $this->pc36FAQ->setFaqCat(null); // Fait parce que le smarty mobile teste la presence de cette propriété

        return $this->pc36FAQ;
    }

    /**
     * @param array $dataSource
     * @return array
     */
    protected function getFaqSurveyData(array $dataSource)
    {
        return array(
            'question' => $this->trans('NDP_PC36_HAS_THIS_ANSWER_BEEN_USEFUL_TO_YOU'),
            'answers' => array(
                'yes' => array(
                    'buttonText' => $this->trans('NDP_YES'),
                    'answerText' => $this->trans('NDP_PC36_THANK_YOU_FOR_HELPING_US_TO_IMPROVE_OUR_SERVICES')
                ),
                'no' => array(
                    'buttonText' => $this->trans('NDP_NO'),
                    'answerText' => $dataSource['pageZone']->getZoneTexte()
                    // TODO attente de clarification de specs ou correction ISOBAR
//                    'answerText' => $this->trans('NDP_PC36_WE_ARE_SORRY_TO_GET_YOUR_ANSWER_YOU_CAN_CONTACT_US_BY'),
//                    'contactText' => $this->trans('NDP_PC36_CLICKING_HERE'),
//                    'contactURL' => '#'
                )
            )
        );
    }

    /**
     * @param array $dataSource
     *
     * @return array
     */
    protected function getFaqCTAData($dataSource)
    {
        $result = [];

        /** @var PsaPageZone $pageZone */
        $pageZone = $dataSource['pageZone'];

        foreach ($pageZone->getCtaReferences() as $ctaReference) {
            $result[] = $this->pc36FAQ->getCtaFactory()->create($ctaReference);
        }

        return $result;
    }

    /**
     * Return category, subcategories and questions data for selected categories
     *
     * @param array $subCatArray
     *
     * @return array
     */
    protected function getFaqSubCatData(array $subCatArray)
    {
        $result = [];

        foreach ($subCatArray as $subCat) {
            $newSubCat['title'] = $subCat['catTitle'];
            $newSubCat['questions'] = $this->getQuestionsData($subCat['questions']);

            if (count($newSubCat['questions']) !== 0) {
                $result[] = $newSubCat;
            }
        }

        return $result;
    }

    /**
     * @param array $questions
     *
     * @return array
     */
    protected function getQuestionsData(array $questions)
    {
        $result = [];

        if ($questions !== null) {
            foreach ($questions as $question) {
                if (isset($question['contentMobile']) && intval($question['contentMobile']) === 1) {
                    $newQuestion['title'] = $question['contentTitle2'];
                    $newQuestion['answer'] = $question['contentText'];
                    // TODO en attente de prise en compte par ISOBAR
                    $newQuestion['surveyQuestion'] = $question['contentCode3']; // Affichage ou non de la question de satisfaction
                    $result[] = $newQuestion;
                }
            }
        }

        return $result;
    }
}
