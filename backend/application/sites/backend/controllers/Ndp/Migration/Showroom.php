<?php
require_once Pelican::$config['APPLICATION_CONTROLLERS'] . '/Ndp.php';

use Itkg\Manager\MediaManager;
use PSA\MigrationBundle\Entity\User\PsaUser;
use PSA\MigrationBundle\Repository\PsaSiteRepository;
use PSA\MigrationBundle\Entity\Language\PsaLanguage;
use Itkg\Migration\ShowroomMigrationService;
use PSA\MigrationBundle\Entity\Site\PsaSite;
use Itkg\Migration\Exception\MigrationLockException;
use Itkg\Migration\UrlManager\ShowroomUrlManager;
use Itkg\Migration\Lock\LockHandler;
use Doctrine\ORM\EntityManager;

class Ndp_Migration_Showroom_Controller extends Ndp_Controller
{
    protected $form_name = "migrationshowroom";

    const MIGRATION_AVG_TIME = '3mn';
    const MAX_ECART = 60;

    /**
     * Index action, override done to forward to another action
     *
     */
    public function indexAction()
    {
        parent::indexAction();

        // Migration step 2
        if (!empty($_POST['stepAction'])) {
            $this->_forward($_POST['stepAction']);
        }
    }

    /**
     * Default list action used to display migration input IHM
     */
    public function listAction()
    {
        // Create Form
        $this->oForm = Pelican_Factory::getInstance('Form', true);
        /** @var Pelican_Form $oForm */
        $oForm = $this->oForm;

        $oForm->bDirectOutput = false;
        $form = $oForm->open('/_/Index/child?tid=' . $this->tid, "post", "fForm", false, true, "checkShowRoom");

        $form .= $this->beginForm($this->oForm);
        $form .= $this->generateFormInput($oForm);
        $form .= $oForm->createSubmit("submitUpload", t('NDP_MIG_IMPORTER'));
        $form .= $oForm->createHidden('stepAction', 'migrate');
        $form .= $this->endForm($this->oForm, array(), '');
        $form .= $oForm->close();
        $this->assign("form", $form, false);

        // Hide default list button
        $this->aButton["add"] = "";
        $this->aButton["save"] = "";
        $this->aButton["back"] = "";
        Backoffice_Button_Helper::init($this->aButton);

        $this->fetch();
    }

    public function replaceAction()
    {
        /** @var \Itkg\Manager\SignatureManager $signatureManager */
        $signatureManager = $this->getContainer()->get('psa_ndp.manager.signature');
        $signatureManager->setSiteId($_SESSION[APP]['SITE_ID']);
        /** @var \Itkg\Utils\SisterFinder $sisterFinder */
        $mediaManager = new MediaManager();
        if (!empty(Pelican_Db::$values['replace-image'])) {
            $imagesToReplace = Pelican_Db::$values['replace-image'];
            $total =0;
            foreach($imagesToReplace as $id=>$newId)
            {
                if ($newId != 0) {
                    $mediaManager->replaceMediaById($id, $newId);
                    $total += $mediaManager->getSqlCount();
                }
                $signatureManager->removeSignature($id);

            }
            $this->addFlashMessage(t('NDP_MIG_IMAGE_REPLACEMENT_SUCCESS'),'success');

        }

        $this->assign(
            'migrateBack',
            [
                'controller' => '/_/Index/child?tid=' . $this->tid,
                'action' => 'list',
            ]
        );
        $this->fetch();
    }

    public function migrateImageAction()
    {
        /** @var \Itkg\Manager\SignatureManager $signatureManager */
        $signatureManager = $this->getContainer()->get('psa_ndp.manager.signature');
        $signatureManager->setSiteId($_SESSION[APP]['SITE_ID']);
        /** @var \Itkg\Utils\SisterFinder $sisterFinder */
        $sisterFinder = $this->getContainer()->get('psa_ndp.utils.sisterfinder');
        // write report result in a file
        $signatures = $signatureManager->getSignatures();
        $mediaManager = new MediaManager();

        $sisterFinder->generateSisters($signatures);
        $sisterFinder->sortResult();
        $sisters = $sisterFinder->getSisters();
        $maxEcart = self::MAX_ECART;
        array_walk($sisters, function(&$sister, $id) use($mediaManager) {
            $sister['count'] = $mediaManager->countUsage($id);
        });
        $filteredSisters = array_filter($sisters, function($sister) use($maxEcart) {
            return ($sister['ecart'] <= $maxEcart) && ($sister['count'] > 0)  ;
        });

        $this->assign('sisters', $filteredSisters);
        $this->assign('signatures', $signatures);
        $this->assign('mediaRoot', Pelican::$config['MEDIA_HTTP']);
        $this->assign('mediaManager',$mediaManager);
        $this->assign('maxEcart',self::MAX_ECART);
        $this->assign(
            'migrateImage',
            [
                'controller' => '/_/Index/child?tid=' . $this->tid,
                'action' => 'replace',
            ]
        );
        $this->assign(
            'migrateBack',
            [
                'controller' => '/_/Index/child?tid=' . $this->tid,
                'action' => 'list',
            ]
        );
        $this->fetch();
    }

    /**
     *  Launch migration and display result report
     */
    public function migrateAction()
    {
        //Set 10 mn as time out for migration
        set_time_limit(600);
        $pelicanValue = Pelican_Db::$values;

        // Get current user, site and list of languages
        $user = $this->getUser();
        $site = $this->getSite();
        $languages = $site->getLangues();

        // Get post parameters value
        $urlType = $_POST['URL_TYPE'];
        $showroomUrls = [];
        foreach ($languages as $langage) {
            /** @var PsaLanguage $langage */
            $showroomUrl = $_POST['NDP_MIG_URL_LANGUAGE_' . $langage->getLangueCode()];

            if ($showroomUrl !== null && $showroomUrl !== '') {
                $showroomUrls[$langage->getLangueCode()] = $showroomUrl;
            }
        }

        // launch migration
        try {
            /** @var ShowroomMigrationService $showroomService */
            $showroomService = $this->getContainer()->get('psa_ndp.migration.showroom.service');
            $reporting = $showroomService->migrate($site, $user, $showroomUrls, $urlType);

            // write report result in a file
            $reporting->writeReport();
            // display report result
            $this->assign('reporting', $reporting);
            $this->assign(
                'migrateImage',
                [
                    'controller' => '/_/Index/child?tid=' . $this->tid,
                    'action' => 'migrateImage',
                ]
            );

        } catch (MigrationLockException $e) {
            $this->assign(
                'infos',
                [
                    'date' => $e->getStartDate(),
                    'hour' => $e->getStartHour(),
                    'user' => $e->getUserName()
                ]
            );
            $this->assign(
                'unlock',
                [
                    'controller' => '/_/Index/child?tid=' . $this->tid,
                    'action' => 'unlock',
                ]
            );
            $this->assign('avgTime', self::MIGRATION_AVG_TIME);
            $this->replaceTemplate('migrate', 'migrationLocked');
        }

        // Important set back initial Pelican Value before migration was launched
        Pelican_Db::$values = $pelicanValue;
        $this->fetch();
    }


    /**
     *  Unlock current migration process
     */
    public function unlockAction()
    {
        $site = $this->getSite();
        /** @var LockHandler $lock */
        /** @var ShowroomMigrationService $showroomService */
        $showroomService = $this->getContainer()->get('psa_ndp.migration.showroom.service');
        $lock = $showroomService->getLock();

        // Unlock migration
        $lock->setLockName($showroomService->createLockName($site));
        $lock->unlock();

        $this->fetch();
    }

    /**
     * Generate Form Table for input data field
     *
     * @param Pelican_Form $oForm
     * @return string
     */
    private function generateFormInput(Pelican_Form &$oForm)
    {
        $site = $this->getSite();

        // Generate form
        $form = $oForm->beginFormTable();

        // Title
        $form .= $oForm->createLabel($this->formatLabel('NDP_MIG_SHOWROOM_MIGRATE_TITLE'), '');
        $form .= $oForm->showSeparator();
        // Radio choice : showroom type
        $form .= $this->generateUrlTypeRadioButton($oForm);
        // Showroom url input for each language
        $form .= $this->generateShowroomUrlInputByLanguage($site, $oForm);

        $form .= $oForm->endFormTable();

        return $form;
    }

    /**
     * Generate radio buton choice for showroom url type
     *
     * @param Pelican_Form $oForm
     *
     * @return string
     */
    private function generateUrlTypeRadioButton(Pelican_Form $oForm)
    {
        $radioBtnNameUrlType = 'URL_TYPE';
        $typeShowroom = [
            ShowroomUrlManager::URL_TYPE_VN_PUBLISHED => t(
                'NDP_MIG_URL_TYPE_' . ShowroomUrlManager::URL_TYPE_VN_PUBLISHED
            ),
            ShowroomUrlManager::URL_TYPE_CONCEPT_PUBLISHED => t(
                'NDP_MIG_URL_TYPE_' . ShowroomUrlManager::URL_TYPE_CONCEPT_PUBLISHED
            ),
            ShowroomUrlManager::URL_TYPE_TECHNO_PUBLISHED => t(
                'NDP_MIG_URL_TYPE_' . ShowroomUrlManager::URL_TYPE_TECHNO_PUBLISHED
            ),
            ShowroomUrlManager::URL_TYPE_NOT_PUBLISHED => t(
                'NDP_MIG_URL_TYPE_' . ShowroomUrlManager::URL_TYPE_NOT_PUBLISHED
            )
        ];
        // Set a default showroom type
        if (empty($this->values[$radioBtnNameUrlType])) {
            $this->values[$radioBtnNameUrlType] = ShowroomUrlManager::URL_TYPE_VN_PUBLISHED;
        }

        return $oForm->createRadioFromList(
            $this->multi . $radioBtnNameUrlType,
            t('NDP_MIG_URL_TYPE'),
            $typeShowroom,
            $this->values[$radioBtnNameUrlType],
            true,
            $this->readO,
            'v',
            false,
            ''
        );
    }

    /**
     * Generate showroom url inputs for each language of the current connected site
     *
     * @param PsaSite $site
     * @param Pelican_Form $oForm
     *
     * @return string
     */
    private function generateShowroomUrlInputByLanguage(PsaSite $site, Pelican_Form &$oForm)
    {
        $langages = $site->getLangues();

        $form = '';
        foreach ($langages as $language) {
            /** @var  $language PsaLanguage */
            $form .= $oForm->createInput(
                'NDP_MIG_URL_LANGUAGE_' . $language->getLangueCode(),
                t('NDP_MIG_URL_LANGUAGE') . " " . $language->getLangueCode(),
                1024,
                "",
                false,
                "",
                "",
                70
            );
        }
        $oForm->createJs($this->getUrlAlertJs());

        return $form;
    }

    /**
     * JS for form submit checking that at least one url is filled
     *
     * @return string
     */
    private function getUrlAlertJs()
    {
        return '
            if (!$("input[name^=\'NDP_MIG_URL_LANGUAGE_\'][value!=\'\']").length) {
                alert("' . t('NDP_MIG_URL_MISSING') . '"); return false;
            }
        ';
    }

    /**
     * Return PsaSite for current siteId
     *
     * @return PsaSite
     */
    private function getSite()
    {
        $siteId = $_SESSION[APP]['SITE_ID'];
        /** @var EntityManager $entityManager */
        $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');

        /** @var PsaSiteRepository $siteRepository */
        $siteRepository = $entityManager->getRepository('PSA\MigrationBundle\Entity\Site\PsaSite');
        /** @var PsaSite $site */
        $site = $siteRepository->find($siteId);

        return $site;
    }


    /**
     * Return PsaSite for current siteId
     *
     * @return PsaUser
     */
    private function getUser()
    {
        $userLogin = $_SESSION[APP]['user']['id'];
        /** @var EntityManager $entityManager */
        $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');

        /** @var PsaSiteRepository $siteRepository */
        $siteRepository = $entityManager->getRepository('PSA\MigrationBundle\Entity\User\PsaUser');
        /** @var PsaSite $site */
        $site = $siteRepository->find($userLogin);

        return $site;
    }

    /**
     * Mise en forme specifique des labels
     *
     * @param string $labelKey code constante de langue
     *
     * @return string label formaté
     */
    public function formatLabel($labelKey)
    {
        return '<span style="font-weight:bold">' . t($labelKey) . '</span>';
    }

}
