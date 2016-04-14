<?php

class Media_MediaFormat extends Pelican_Cache
{
    public $duration = WEEK;

    /** Valeur ou objet à mettre en Pelican_Cache */
    public function getValue()
    {
        $connection = Pelican_Db::getInstance();
        $bind= [];

        $sql = 'SELECT * FROM #pref#_media_format';

        if (!empty($this->params)) {
            if (isset($this->params['MEDIA_FORMAT_LABEL'])) {
                $bind[':MEDIA_FORMAT_LABEL'] = $this->params['MEDIA_FORMAT_LABEL'];
                $sql .= ' WHERE MEDIA_FORMAT_LABEL = ":MEDIA_FORMAT_LABEL"';

            }
            if (isset($this->params['MEDIA_FORMAT_ID'])) {
                $bind[':MEDIA_FORMAT_ID'] = $this->params['MEDIA_FORMAT_ID'];
                $sql .= ' WHERE MEDIA_FORMAT_ID = ":MEDIA_FORMAT_ID"';
            }

            $this->value = $connection->queryRow($sql, $bind);
        }
        else{
                $sql .= ' WHERE MEDIA_FORMAT_BO = 1';
                $this->value = $connection->queryTab($sql, $bind);
        }

        return  $this->value;
    }
}

