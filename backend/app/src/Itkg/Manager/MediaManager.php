<?php

namespace Itkg\Manager;

class MediaManager
{
    protected $sqlCount = 0;
    private function getMediaTables()
    {
        $tables['psa_accessoires'] = 'MEDIA_ID';
        $tables['psa_after_sale_services'] = 'MEDIA_ID,MEDIA_ID2';
        $tables['psa_application_connect_apps'] = 'MEDIA_ID';
        $tables['psa_appli_mobile'] = 'MEDIA_ID';
        $tables['psa_content_attribut'] = 'MEDIA_ID';
        $tables['psa_content_version'] = 'MEDIA_ID9,MEDIA_ID8,MEDIA_ID7,MEDIA_ID6,MEDIA_ID5,MEDIA_ID4,MEDIA_ID3,MEDIA_ID2,MEDIA_ID';
        $tables['psa_content_version_media'] = 'MEDIA_ID';
        $tables['psa_content_zone'] = 'MEDIA_ID';
        $tables['psa_content_zone_media'] = 'MEDIA_ID';
        $tables['psa_content_zone_multi'] = 'MEDIA_ID,MEDIA_ID2';
        $tables['psa_formbuilder_mail_media'] = 'MEDIA_ID';
        $tables['psa_media_alt_translation'] = 'MEDIA_ID';
        $tables['psa_media_format_intercept'] = 'MEDIA_ID';
        $tables['psa_media_usage'] = 'MEDIA_ID';
        $tables['psa_mobapp_content'] = 'MEDIA_ID';
        $tables['psa_mobapp_site_home'] = 'MEDIA_ID';
        $tables['psa_page_multi'] = 'MEDIA_ID';
        $tables['psa_page_multi_zone'] = 'MEDIA_ID9,MEDIA_ID8,MEDIA_ID7,MEDIA_ID6,MEDIA_ID5,MEDIA_ID4,MEDIA_ID3,MEDIA_ID2,MEDIA_ID,MEDIA_ID10';
        $tables['psa_page_multi_zone_media'] = 'MEDIA_ID';
        $tables['psa_page_multi_zone_multi'] = 'MEDIA_ID,MEDIA_ID2,MEDIA_ID3,MEDIA_ID4,MEDIA_ID5,MEDIA_ID6';
        $tables['psa_page_version'] = 'MEDIA_ID2,MEDIA_ID';
        $tables['psa_page_version_media'] = 'MEDIA_ID';
        $tables['psa_page_zone'] = 'MEDIA_ID10,MEDIA_ID9,MEDIA_ID8,MEDIA_ID7,MEDIA_ID6,MEDIA_ID5,MEDIA_ID4,MEDIA_ID3,MEDIA_ID2,MEDIA_ID';
        $tables['psa_page_zone_media'] = 'MEDIA_ID';
        $tables['psa_page_zone_multi'] = 'MEDIA_ID6,MEDIA_ID5,MEDIA_ID4,MEDIA_ID3,MEDIA_ID2,MEDIA_ID';
        $tables['psa_page_zone_multi_multi'] = 'MEDIA_ID,MEDIA_ID2,MEDIA_ID3,MEDIA_ID4,MEDIA_ID5,MEDIA_ID6';
        $tables['psa_paragraph_media'] = 'MEDIA_ID';
        $tables['psa_pdv_service'] = 'MEDIA_ID';
        $tables['psa_research'] = 'MEDIA_ID';
        $tables['psa_reseau_social'] = 'MEDIA_ID,MEDIA_ID2';
        $tables['psa_service'] = 'MEDIA_ID';
        $tables['psa_site'] = 'MEDIA_ID';
        $tables['psa_vehicle_category'] = 'MEDIA_ID';

        return $tables;
    }

    public function countUsage($mediaId)
    {
        $count = 0;
        $datas = $this->searchMedia($mediaId);
        foreach ($datas as $data) {
            $count += count($data);
        }

        return $count;
    }
    public function searchMedia($mediaId)
    {
        $backup = \Pelican_Db::$values;
        $tables = $this->getMediaTables();
        $con = \Pelican_Db::getInstance();
        $return = [];
        foreach ($tables as $table => $fields) {
            $sql = 'SELECT '.$fields.' FROM '.$table.' WHERE '.$mediaId.' IN('.$fields.')';
            $data = $con->queryTab($sql, []);
            ++$this->sqlCount;
            if (!empty($data)) {
                $return[$table] = $data;
            }
        }
        \Pelican_Db::$values = $backup;

        return $return;
    }

    public function replaceMediaById($oldMediaId, $newMediaId)
    {
        $tables = $this->getMediaTables();
        $oldEntries = $this->searchMedia($oldMediaId);
        foreach ($oldEntries as $table => $data) {
            $fields = explode(',', $tables[$table]);
            $fieldsToUpdate = $this->filterFields($fields, $data, $oldMediaId);
            $this->updateDatabase($table, $fieldsToUpdate, $oldMediaId, $newMediaId);
        }
    }

    private function filterFields($fields, $data, $oldMediaid)
    {
        $fieldsToUpdate = [];
        $maxFields = count($fields);
        // si on a qu'un champ a mettre a jour et qu'on l'as  trouver on doit retourné celui la :)
        if ($maxFields == 1) {
            return $fields;
        }

        foreach ($data as $idx => $row) {
            foreach ($fields as $field) {
                if (!in_array($field, $fieldsToUpdate) && $row[$field] == $oldMediaid) {
                    $fieldsToUpdate[] = $field;
                    if ($maxFields == count($fieldsToUpdate)) {
                        // si on a la liste complete des des champs pas besoin de chercher plus
                     return $fieldsToUpdate;
                    }
                }
            }
        }

        return $fieldsToUpdate;
    }

    private function updateDatabase($table, $fieldsToUpdate, $oldMediaId, $newMediaId)
    {
        $con = \Pelican_Db::getInstance();
        foreach ($fieldsToUpdate as $field) {
            $sql = 'UPDATE '.$table.' SET '.$field.' = '.$newMediaId.' WHERE '.$field.' ='.$oldMediaId;
            $con->query($sql);
            ++$this->sqlCount;
        }
    }

    /**
     * @return int
     */
    public function getSqlCount()
    {
        return $this->sqlCount;
    }

    /**
     * @param int $sqlCount
     */
    public function setSqlCount($sqlCount)
    {
        $this->sqlCount = $sqlCount;
    }
}
