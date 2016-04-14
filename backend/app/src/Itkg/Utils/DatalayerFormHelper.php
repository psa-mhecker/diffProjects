<?php

namespace Itkg\Utils;


use Cms_Page_Ndp;
use PsaNdp\MappingBundle\Datalayer\Datalayer;
use Symfony\Component\DependencyInjection\Container;

class DatalayerFormHelper
{

    /**
     * @var Container
     */
    private $container;

    /**
     * @var array
     */
    private $datalayerFormFields = array(
        'siteTypeLevel2',
        'siteTarget',
        'siteFamily',
        'pageCategory',
    );

    /**
     * @var \Pelican_Form;
     */
    private $form;

    /**
     * @var array
     */
    private $values;
    /**
     * @var string
     */
    private $formView;

    /**
     * @var Datalayer
     */
    private $datalayer;

    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param Container $container
     * @return DatalayerFormHelper
     */
    public function setContainer($container)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * @return \Pelican_Form
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @param \Pelican_Form $form
     * @return DatalayerFormHelper
     */
    public function setForm($form)
    {
        $this->form = $form;

        return $this;
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @param array $values
     * @return DatalayerFormHelper
     */
    public function setValues($values)
    {
        $this->values = $values;

        return $this;
    }


    /**
     * @return string
     */
    public  function getFormView()
    {

        try {
            $this->initDatalayer();
            $this->formViewContent();
        } catch(\Exception $e) {
            $this->formView = t('NDP_PAGE_NOT_PUBLISHED');
        }

        return $this->formView;
    }

    /**
     * initialize current context & datalayer
     */
    private function initDatalayer()
    {
        $this->datalayer = $this->getContainer()->get('psa_ndp.datalayer');

        if (!empty($this->values) && isset($this->values['PAGE_ID']) &&  $this->values['PAGE_ID'] != \Pelican_Db::DATABASE_INSERT_ID) {
            /** @var  \PSA\MigrationBundle\Entity\Page\PsaPage; $node */
            $node = $this->getContainer()->get('open_orchestra_model.repository.node')
                ->findOnePublishedByNodeIdAndLanguageAndSiteIdInLastVersion(
                    $this->values['PAGE_ID'],
                    $this->values['LANGUE_CODE'],
                    $this->values['SITE_ID'],
                    false
                );
            /** @var Datalayer $dataLayer */

            $context = $this->getContainer()->get('psa_ndp.context');


            if (null !== $node) {
                // dans le BO on travail sur la draft version :)
                $node->setPreview(true);
                $context->setNode($node);
            } else {
                throw new \Exception('node is not published', 1445006090);
            }

            $this->datalayer->setContext($context);
            $this->datalayer->initTemplateValues();
        }
    }


    /**
     * build datalayer variables select lists
     */
    private function datalayerFormFields()
    {
        $availableValues = $this->datalayer->getAvailableValues();
        $selectedValues = array();

        foreach ($this->datalayerFormFields as $fieldName) {
            $selectedValues[$fieldName] = $this->datalayer->getVariable($fieldName);
            $options['infoBull'] = array( 'isIcon' => true, 'message' => t(strtoupper($fieldName).'_INFO'));
            $options['attribute'] = array( 'data-name' => $fieldName );
            $this->formView .= $this->form->createCombofromList(
                sprintf(
                    'DEFAULT_VARIABLES[%s]',
                    $fieldName
                ),
                $fieldName,
                $availableValues[$fieldName],
                array($selectedValues[$fieldName]),
                false,
                Cms_Page_Ndp::isTranslator(),
                "1",
                false,
                "",
                true,
                false,
                "",
                $options
            );
        }

        if ($this->values['PAGE_CURRENT_VERSION'] < 1) {

            $this->form->createJS(
                'messageToDisplay ="'.t("NDP_TAGGAGE_SELECTED_DATA").'\r\n";
                $(\'select[id^="DEFAULT_VARIABLES"]\').each( function(i) {
                   messageToDisplay += $(this).data("name")+": "+$(this).val()+" \r\n";
                });

                var res = confirm(messageToDisplay);
                if(!res) {
                    ongletFW("4");
                    return res;
                }'
            );
        }

    }

    /**
     * build form view
     */
    private function formViewContent()
    {
        $this->datalayerFormFields();
    }


    /**
     *  Saves datalayer form
     */
    public function saveDatalayer()
    {
        $oConnection = \Pelican_Db::getInstance();
        $bind[':PAGE_ID'] = \Pelican_Db::$values['PAGE_ID'];

        $bind[':DATALAYER'] = $oConnection->strToBind(json_encode(\Pelican_Db::$values['DEFAULT_VARIABLES']));

        $sqlDelete = "DELETE FROM #pref#_page_datalayer
            WHERE PAGE_ID = :PAGE_ID
            ";
        $oConnection->query($sqlDelete, $bind);

        $sqlInsert = "INSERT INTO #pref#_page_datalayer (PAGE_ID, DATALAYER) VALUES (
             :PAGE_ID, :DATALAYER)";

        $oConnection->query($sqlInsert, $bind);
        unset(\Pelican_Db::$values['DEFAULT_VARIABLES']);
    }
}