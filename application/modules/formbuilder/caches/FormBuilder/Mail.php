<?php

/**
    * @package Cache
    * @subpackage General
    */
class FormBuilder_Mail extends Pelican_Cache
{

    /**
     * Valeur ou objet à mettre en Pelican_Cache
     */
    public function getValue()
    {
        $oConnection = Pelican_Db::getInstance();

        $query = "SELECT *
                FROM
                #pref#_formbuilder_mail ";
        if ($this->params[0]) {
            $query .= " WHERE FORMBUILDER_ID = " . $this->params[0];
        }
        $val = $oConnection->queryTab($query);
        foreach ($val as $mail) {
            $this->value[$mail['FORMBUILDER_MAIL_TYPE']] = $mail;
        }

        $query = "SELECT *
                FROM
                #pref#_formbuilder_mail_media mm
                inner join #pref#_media m on (m.MEDIA_ID=mm.MEDIA_ID)";
        if ($this->params[0]) {
            $query .= " WHERE FORMBUILDER_ID = " . $this->params[0];
        }
        $query .= ' ORDER BY FORMBUILDER_MAIL_MEDIA_ORDER';

        $attach = $oConnection->queryTab($query);
        if (is_array($attach)) {
            foreach ($attach as $file) {
                $this->value[$file['FORMBUILDER_MAIL_TYPE']]['ATTACHMENT'][] = Pelican::$config['MEDIA_ROOT'].$file['MEDIA_PATH'];
            }
        }
    }
}
