<?php

namespace PsaNdp\MappingBundle\Utils;

use Doctrine\DBAL\Migrations\AbstractMigration;

/**
 * Class AbstractPsaMigration
 */
abstract class AbstractPsaMigration extends AbstractMigration
{

    private $addTranslationKeyPattern = "INSERT `psa_label` (`LABEL_ID`, `LABEL_INFO`, `LABEL_BACK`, `LABEL_LENGTH`, `LABEL_CASE`, `LABEL_BO`, `LABEL_FO`) VALUES ('%s', NULL, 2, NULL, NULL, %s, %s)";
    private $addTranslationPattern = "REPLACE `psa_label_langue_site` (`LABEL_ID`, `LANGUE_ID`, `SITE_ID`, `LABEL_TRANSLATE`) VALUES ('%s', %d, 1, '%s')";
    private $addFrontendTranslationPattern = "REPLACE `psa_label_langue` (`LABEL_ID`, `LANGUE_ID`, `LABEL_TRANSLATE`) VALUES ('%s', %d, '%s')";
    private $deleteTranslationPattern = 'DELETE FROM %s WHERE  LABEL_ID = "%s"';
    private $updateTranslationPattern = "REPLACE INTO `psa_label_langue_site` (`LABEL_ID`, `LANGUE_ID`, `SITE_ID`, `LABEL_TRANSLATE`) VALUES ('%s', %d, 1, '%s')";

    /**
     * add sql migration for a given translations key :('expression'=>value,'languageId'=n)
     *
     * @param $translations
     */

    public function upTranslations($translations)
    {
        foreach ($translations as $translationKey => $translation) {
            //NULL here referes to SQL 'NULL'
            $backOffice = 'NULL';
            $frontOffice = 'NULL';
            $languageId = 1;

            if (is_array($translation)) {
                if (array_key_exists('bo', $translation) && $translation['bo']) {
                    $backOffice = $translation['bo'];
                }
                if (array_key_exists('fo', $translation) && $translation['fo']) {
                    $frontOffice = $translation['fo'];
                }


                $translationExpression = $translation['expression'];

                if(!empty($translation['expression'])){
                    $translationExpression = $translation['expression'];
                }

                if(!empty($translation['TRANSLATION'])){
                    $translationExpression = $translation['TRANSLATION'];
                }

                if(!empty($translation['languageId'])){
                    $languageId = $translation['languageId'];
                }
                if(!empty($translation['LANGUE_ID'])){
                    $languageId = $translation['LANGUE_ID'];
                }

            } elseif (is_string($translation)) {
                $translationExpression = $translation;
            }

            $translationExpression = addslashes($translationExpression);

            $this->addSql(sprintf($this->addTranslationKeyPattern, $translationKey, $backOffice, $frontOffice));
            $this->addSql(sprintf($this->addTranslationPattern, $translationKey, $languageId,$translationExpression));
            if($frontOffice == 1) {
                $this->addSql(
                    sprintf($this->addFrontendTranslationPattern, $translationKey, $languageId, $translationExpression)
                );
            }


        }
    }

    /**
     * add sql migration for a given translations key :('TRANSLATION'=>value,'LANGUE_ID'=n)
     *
     * @param $translations
     */

    public function replaceTranslations($translations)
    {
        foreach ($translations as $translationKey => $translation) {
            $languageId = 1;

            if(!empty($translation['languageId'])){
                $languageId = $translation['languageId'];
            }
            if(!empty($translation['LANGUE_ID'])){
                $languageId = $translation['LANGUE_ID'];
            }

            if(!empty($translation['expression'])){
                $translationExpression = $translation['expression'];
            }

            if(!empty($translation['TRANSLATION'])){
                $translationExpression = $translation['TRANSLATION'];
            }

            $translationExpression = addslashes($translationExpression);

            $this->addSql(
                sprintf(
                    $this->updateTranslationPattern,
                    $translationKey,
                    $languageId,
                    $translationExpression
                )
            );
        }
    }

    /**
     * Delete the given translations
     *
     * @param $translationKeys
     */
    public function downTranslations($translationKeys)
    {
        $tables = array('psa_label_langue_site','psa_label','psa_label_langue' );

        foreach ($translationKeys as $oneTranslationKey) {
            foreach ($tables as $table) {
                $this->addSql(sprintf($this->deleteTranslationPattern, $table, $oneTranslationKey));
            }
        }

    }
}
