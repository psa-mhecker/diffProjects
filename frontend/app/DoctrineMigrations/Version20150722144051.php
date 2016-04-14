<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150722144051 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
   $this->addSql("INSERT INTO psa_label (LABEL_ID, LABEL_INFO, LABEL_BACK, LABEL_LENGTH, LABEL_CASE, LABEL_BO, LABEL_FO) VALUES
            ('NDP_RATIO_1_3', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_RATIO_16_9', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_RATIO_4_3', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_RATIO_CINEMASCOPE_2_31_1', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_RATIO_RECTANGLE_BADGE_15_3', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_RATIO_BADGE_MOBILE_APPS', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_RATIO_IAB_BILLBOARD', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_RATIO_IAB_HORIZONTAL', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_RATIO_IAB_PAVE', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_RATIO_SQUARE_1_1', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_RATIO_SMALL_RECTANGLE_15_7', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_RATIO_CONFISHOW_MAIN_MEDIA', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_RATIO_HOME_PAGE_MAIN_MEDIA', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_RATIO_LARGE_RECTANGLE', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_RATIO_ACCESSORIES_MEDIA_SPECIFIC', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_RATIO_DIMENSIONS_V3D', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_RATIO_VEHICULE_V3D', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_RATIO_WIDTH_MINI_1368_HD_SUGGESTED_4_3', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_RATIO_WIDTH_MINI_684_STANDARD_SUGGESTED_4_3', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_RATIO_WIDTH_MINI_1778_HD_SUGGESTED_4_3', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_RATIO_WIDTH_MINI_600_STANDARD_SUGGESTED_4_3', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_RATIO_WIDTH_MINI_1200_HD_SUGGESTED_4_3', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_RATIO_WIDTH_MINI_1560_HD_SUGGESTED_4_3', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_RATIO_WIDTH_MINI_586_MOBILE_SUGGESTED_4_3', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_RATIO_WIDTH_MINI_345_STANDARD_SUGGESTED_4_3', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_RATIO_WIDTH_MINI_2080_HD_SUGGESTED_16_9', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_RATIO_WIDTH_MINI_1040_STANDARD_SUGGESTED_16_9', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_RATIO_WIDTH_MINI_2704_HD_SUGGESTED_16_9', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_RATIO_WIDTH_MINI_640_MOBILE_SUGGESTED_4_3', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_RATIO_WIDTH_MINI_1038_HD_SUGGESTED_4_3', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_RATIO_WIDTH_MINI_1545_HD_SUGGESTED_4_3', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_RATIO_WIDTH_MINI_773_STANDARD_SUGGESTED_4_3', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_RATIO_WIDTH_MINI_2009_HD_SUGGESTED_4_3', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_RATIO_WIDTH_MINI_483_HD_SUGGESTED_4_3', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_RATIO_WIDTH_MINI_420_STANDARD_SUGGESTED_4_3', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_RATIO_WIDTH_MINI_241_STANDARD_SUGGESTED_4_3', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_RATIO_WIDTH_MINI_627_HD_SUGGESTED_4_3', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_RATIO_WIDTH_MINI_519_STANDARD_SUGGESTED_4_3', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_RATIO_WIDTH_MINI_1349_HD_SUGGESTED_4_3', NULL, 2, NULL, NULL, 1, NULL),
            ('NDP_RATIO_WIDTH_MINI_572_MOBILE_SUGGESTED_4_3', NULL, 2, NULL, NULL, 1, NULL)
            ");



        $this->addSql("INSERT INTO psa_label_langue_site (LABEL_ID, LANGUE_ID, SITE_ID, LABEL_TRANSLATE) VALUES
          
            ('NDP_RATIO_1_3', 1, 1, '1/3'),
            ('NDP_RATIO_16_9', 1, 1, '16/9'),
            ('NDP_RATIO_4_3', 1, 1, '16/9'),
            ('NDP_RATIO_CINEMASCOPE_2_31_1', 1, 1, 'cinémascope 2,31/1'),
            ('NDP_RATIO_RECTANGLE_BADGE_15_3', 1, 1, 'rectangle badge 15/3'),
            ('NDP_RATIO_BADGE_MOBILE_APPS', 1, 1, 'badge mobile apps'),
            ('NDP_RATIO_IAB_BILLBOARD', 1, 1, 'IAB billboard'),
            ('NDP_RATIO_IAB_HORIZONTAL', 1, 1, 'IAB horizontal'),
            ('NDP_RATIO_IAB_PAVE', 1, 1, 'IAB pavé'),
            ('NDP_RATIO_SQUARE_1_1', 1, 1, 'square 1/1'),
            ('NDP_RATIO_SMALL_RECTANGLE_15_7', 1, 1, 'small rectangle 15/7'),
            ('NDP_RATIO_CONFISHOW_MAIN_MEDIA', 1, 1, 'Confishow_ main media'),
            ('NDP_RATIO_HOME_PAGE_MAIN_MEDIA', 1, 1, 'Home page_ main media'),
            ('NDP_RATIO_LARGE_RECTANGLE', 1, 1, 'large rectangle'),
            ('NDP_RATIO_ACCESSORIES_MEDIA_SPECIFIC', 1, 1, 'accessories media (specific)'),
            ('NDP_RATIO_DIMENSIONS_V3D', 1, 1, 'Dimensions V3D'),
            ('NDP_RATIO_VEHICULE_V3D', 1, 1, 'véhicule V3D'),
            ('NDP_RATIO_WIDTH_MINI_1368_HD_SUGGESTED_4_3', 1, 1, 'width mini 1368_HD_suggested   4:3'),
            ('NDP_RATIO_WIDTH_MINI_684_STANDARD_SUGGESTED_4_3', 1, 1, 'width mini 684_standard_suggested   4:3'),
            ('NDP_RATIO_WIDTH_MINI_1778_HD_SUGGESTED_4_3', 1, 1, 'width mini 1778_HD+_suggested   4:3'),
            ('NDP_RATIO_WIDTH_MINI_600_STANDARD_SUGGESTED_4_3', 1, 1, 'width mini 600_standard_suggested   4:3'),
            ('NDP_RATIO_WIDTH_MINI_1200_HD_SUGGESTED_4_3', 1, 1, 'width mini 1200_HD_suggested   4:3'),
            ('NDP_RATIO_WIDTH_MINI_1560_HD_SUGGESTED_4_3', 1, 1, 'width mini 1560_HD+_suggested   4:3'),
            ('NDP_RATIO_WIDTH_MINI_586_MOBILE_SUGGESTED_4_3', 1, 1, 'width mini 586_ mobile_suggested   4:3'),
            ('NDP_RATIO_WIDTH_MINI_345_STANDARD_SUGGESTED_4_3', 1, 1, 'width mini 345_standard_suggested   4:3'),
            ('NDP_RATIO_WIDTH_MINI_2080_HD_SUGGESTED_16_9', 1, 1, 'width mini 2080_HD_suggested   16/9'),
            ('NDP_RATIO_WIDTH_MINI_1040_STANDARD_SUGGESTED_16_9', 1, 1, 'width mini 1040_standard_suggested   16/9'),
            ('NDP_RATIO_WIDTH_MINI_2704_HD_SUGGESTED_16_9', 1, 1, 'width mini 2704_HD+_suggested   16/9'),
            ('NDP_RATIO_WIDTH_MINI_640_MOBILE_SUGGESTED_4_3', 1, 1, 'width mini 640 _ mobile_suggested   4:3'),
            ('NDP_RATIO_WIDTH_MINI_1038_HD_SUGGESTED_4_3', 1, 1, 'width mini 1038 _ HD_suggested   4:3'),
            ('NDP_RATIO_WIDTH_MINI_1545_HD_SUGGESTED_4_3', 1, 1, 'width mini 1545 _ HD_suggested   4:3'),
            ('NDP_RATIO_WIDTH_MINI_773_STANDARD_SUGGESTED_4_3', 1, 1, 'width mini 773 _ standard_suggested   4:3'),
            ('NDP_RATIO_WIDTH_MINI_2009_HD_SUGGESTED_4_3', 1, 1, 'width mini 2009 _ HD+_suggested   4:3'),
            ('NDP_RATIO_WIDTH_MINI_483_HD_SUGGESTED_4_3', 1, 1, 'width mini 483 _ HD_suggested   4:3'),
            ('NDP_RATIO_WIDTH_MINI_420_STANDARD_SUGGESTED_4_3', 1, 1, 'width mini 420 _ standard_suggested   4:3'),
            ('NDP_RATIO_WIDTH_MINI_241_STANDARD_SUGGESTED_4_3', 1, 1, 'width mini 241 _ Standard_suggested   4:3'),
            ('NDP_RATIO_WIDTH_MINI_627_HD_SUGGESTED_4_3', 1, 1, 'width mini 627 _ HD+_suggested   4:3'),
            ('NDP_RATIO_WIDTH_MINI_519_STANDARD_SUGGESTED_4_3', 1, 1, 'width mini 519 _ standard_suggested   4:3'),
            ('NDP_RATIO_WIDTH_MINI_1349_HD_SUGGESTED_4_3', 1, 1, 'width mini 1349 _ HD+_suggested   4:3'),
            ('NDP_RATIO_WIDTH_MINI_572_MOBILE_SUGGESTED_4_3', 1, 1, 'width mini 572_ mobile_suggested   4:3')
            ");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
  $tables = array('psa_label','psa_label_langue_site');
        foreach ($tables as $table) {
            $this->addSql(
                "DELETE FROM `".$table."`  WHERE  `LABEL_ID` IN
                 (
                 'NDP_RATIO_1_3',
            'NDP_RATIO_16_9',
            'NDP_RATIO_4_3',
            'NDP_RATIO_CINEMASCOPE_2_31_1',
            'NDP_RATIO_RECTANGLE_BADGE_15_3',
            'NDP_RATIO_BADGE_MOBILE_APPS',
            'NDP_RATIO_IAB_BILLBOARD',
            'NDP_RATIO_IAB_HORIZONTAL',
            'NDP_RATIO_IAB_PAVE',
            'NDP_RATIO_SQUARE_1_1',
            'NDP_RATIO_SMALL_RECTANGLE_15_7',
            'NDP_RATIO_CONFISHOW_MAIN_MEDIA',
            'NDP_RATIO_HOME_PAGE_MAIN_MEDIA',
            'NDP_RATIO_LARGE_RECTANGLE',
            'NDP_RATIO_ACCESSORIES_MEDIA_SPECIFIC',
            'NDP_RATIO_DIMENSIONS_V3D',
            'NDP_RATIO_VEHICULE_V3D',
            'NDP_RATIO_WIDTH_MINI_1368_HD_SUGGESTED_4_3',
            'NDP_RATIO_WIDTH_MINI_684_STANDARD_SUGGESTED_4_3',
            'NDP_RATIO_WIDTH_MINI_1778_HD_SUGGESTED_4_3',
            'NDP_RATIO_WIDTH_MINI_600_STANDARD_SUGGESTED_4_3',
            'NDP_RATIO_WIDTH_MINI_1200_HD_SUGGESTED_4_3',
            'NDP_RATIO_WIDTH_MINI_1560_HD_SUGGESTED_4_3',
            'NDP_RATIO_WIDTH_MINI_586_MOBILE_SUGGESTED_4_3',
            'NDP_RATIO_WIDTH_MINI_345_STANDARD_SUGGESTED_4_3',
            'NDP_RATIO_WIDTH_MINI_2080_HD_SUGGESTED_16_9',
            'NDP_RATIO_WIDTH_MINI_1040_STANDARD_SUGGESTED_16_9',
            'NDP_RATIO_WIDTH_MINI_2704_HD_SUGGESTED_16_9',
            'NDP_RATIO_WIDTH_MINI_640_MOBILE_SUGGESTED_4_3',
            'NDP_RATIO_WIDTH_MINI_1038_HD_SUGGESTED_4_3',
            'NDP_RATIO_WIDTH_MINI_1545_HD_SUGGESTED_4_3',
            'NDP_RATIO_WIDTH_MINI_773_STANDARD_SUGGESTED_4_3',
            'NDP_RATIO_WIDTH_MINI_2009_HD_SUGGESTED_4_3',
            'NDP_RATIO_WIDTH_MINI_483_HD_SUGGESTED_4_3',
            'NDP_RATIO_WIDTH_MINI_420_STANDARD_SUGGESTED_4_3',
            'NDP_RATIO_WIDTH_MINI_241_STANDARD_SUGGESTED_4_3',
            'NDP_RATIO_WIDTH_MINI_627_HD_SUGGESTED_4_3',
            'NDP_RATIO_WIDTH_MINI_519_STANDARD_SUGGESTED_4_3',
            'NDP_RATIO_WIDTH_MINI_1349_HD_SUGGESTED_4_3',
            'NDP_RATIO_WIDTH_MINI_572_MOBILE_SUGGESTED_4_3'
                 )
                "
            );
        }
    }
}
