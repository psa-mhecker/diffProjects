<?php
require_once Pelican::$config['APPLICATION_CONTROLLERS'].'/Ndp.php';

/** Génération des agent PDV
     *
*/
class Ndp_DealerLocDevenirAgent_Controller extends Ndp_Controller
{
    protected $form_name = 'pdv_deveniragent';
    protected $field_id = 'PDV_DEVENIRAGENT_ID';
    protected $defaultOrder = 'PDV_DEVENIRAGENT_NAME';
    protected $errors = array();
    protected $mappingCsvToBdd = array(
        // collumn position => array( bbd field, length max)
        0 => array('PDV_DEVENIRAGENT_ID', 6), // Id
        1 => array('PDV_DEVENIRAGENT_NAME'), // Name
        2 => '', // Acronym (Not used)
        3 => array('PDV_DEVENIRAGENT_DESC', 255), // Description
        4 => array('PDV_DEVENIRAGENT_ADDRESS1', 255), // Address 1
        5 => array('PDV_DEVENIRAGENT_ADDRESS2', 255), // Address 2
        6 => array('PDV_DEVENIRAGENT_ZIPCODE', 10), // Zip code
        7 => array('PDV_DEVENIRAGENT_CITY', 255), // City
        8 => array('PDV_DEVENIRAGENT_COUNTRY', 2), // Country
        9 => array('PDV_DEVENIRAGENT_EMAIL', 255), // Email
        10 => '', // Website (Not Used)
        11 => array('PDV_DEVENIRAGENT_TEL1', 20), // Telephone 1
        12 => array('PDV_DEVENIRAGENT_TEL2', 20), // Telephone 2
        13 => array('PDV_DEVENIRAGENT_FAX', 20), // Fax
        14 => array('PDV_DEVENIRAGENT_RRDI', 10), // RRDI
        15 => '', // City (city_id) - ID  (Not Used)
        16 => '', // City (city_id)  (Not Used)
        17 => array('PDV_DEVENIRAGENT_LAT'), // Latitude
        18 => array('PDV_DEVENIRAGENT_LNG'), // Longitude
        19 => array('PDV_DEVENIRAGENT_LIAISON_ID', 6) // ID de la liaison
     );

    const IMPORT_NB_FIELD = 20;
    const LIAISON_ID_AFFAIRE_A_VENDRE = 421119;
    const LIAISON_ID_AGENT = 421122;


    protected function setListModel()
    {
        $connection = Pelican_Db::getInstance();
        $bind[':SITE_ID'] = (int) $_SESSION[APP]['SITE_ID'];
        $bind[':LIAISON_ID_AFFAIRE_A_VENDRE'] = self::LIAISON_ID_AFFAIRE_A_VENDRE;
        $bind[':LIAISON_ID_AGENT'] = self::LIAISON_ID_AGENT;
        $bind[':NDP_DEVENIRAGENT_LIAISON_421119'] = $connection->strToBind(t('NDP_DEVENIRAGENT_LIAISON_421119'));
        $bind[':NDP_DEVENIRAGENT_LIAISON_421122'] = $connection->strToBind(t('NDP_DEVENIRAGENT_LIAISON_421122'));
        $sqlList = 'SELECT
						*,
						CASE
						    WHEN PDV_DEVENIRAGENT_LIAISON_ID = :LIAISON_ID_AFFAIRE_A_VENDRE
						        THEN :NDP_DEVENIRAGENT_LIAISON_421119
						    WHEN PDV_DEVENIRAGENT_LIAISON_ID = :LIAISON_ID_AGENT
						        THEN :NDP_DEVENIRAGENT_LIAISON_421122
						ELSE PDV_DEVENIRAGENT_LIAISON_ID
						END as LIAISON_LABEL
					FROM
						#pref#_pdv_deveniragent
					WHERE
						SITE_ID = :SITE_ID
					ORDER BY '.$this->listOrder;

        $this->listModel = $connection->queryTab($sqlList, $bind);
    }

    /** affichage des resultats
     *
     */
    public function listAction()
    {

        $form = '</table><div id="globalImport" style="width:100%">';
        $form .= $this->getImportForm($_SESSION[APP]['SITE_ID']);

        // message d'alerte
        if ($_SESSION[APP]['MESSAGE_KEY'] != '') {
             $form .= '<div>'.$_SESSION[APP]['MESSAGE_KEY'].'</div>';
            $_SESSION[APP]['MESSAGE_KEY'] = '';
        }

        $form .= '</div>';

        parent::listAction();
        $table = Pelican_Factory::getInstance('List', '', '', 0, 0, 0, 'liste');
        $table->setValues($this->getListModel(), 'PDV_DEVENIRAGENT_ID');
        $table->addColumn(t('ID'), 'PDV_DEVENIRAGENT_ID', '10', 'left', '', 'tblheader', 'PDV_DEVENIRAGENT_ID');
        $table->addColumn(t('NAME'), 'PDV_DEVENIRAGENT_NAME', '45', 'left', '', 'tblheader', 'PDV_DEVENIRAGENT_NAME');
        $table->addColumn(t('NDP_DESCRIPTION'), 'PDV_DEVENIRAGENT_DESC', '45', 'left', '', 'tblheader', 'PDV_DEVENIRAGENT_DESC');
        $table->addColumn(t('ADDRESS').' 1', 'PDV_DEVENIRAGENT_ADDRESS1', '45', 'left', '', 'tblheader', 'PDV_DEVENIRAGENT_ADDRESS1');
        $table->addColumn(t('ADDRESS').' 2', 'PDV_DEVENIRAGENT_ADDRESS2', '45', 'left', '', 'tblheader', 'PDV_DEVENIRAGENT_ADDRESS2');
        $table->addColumn(t('ZIP_CODE'), 'PDV_DEVENIRAGENT_ZIPCODE', '45', 'left', '', 'tblheader', 'PDV_DEVENIRAGENT_ZIPCODE');
        $table->addColumn(t('CITY'), 'PDV_DEVENIRAGENT_CITY', '45', 'left', '', 'tblheader', 'PDV_DEVENIRAGENT_CITY');
        $table->addColumn(t('COUNTRY'), 'PDV_DEVENIRAGENT_COUNTRY', '45', 'left', '', 'tblheader', 'PDV_DEVENIRAGENT_COUNTRY');
        $table->addColumn(t('EMAIL'), 'PDV_DEVENIRAGENT_EMAIL', '45', 'left', '', 'tblheader', 'PDV_DEVENIRAGENT_EMAIL');
        $table->addColumn(t('PHONE').' 1', 'PDV_DEVENIRAGENT_TEL1', '45', 'left', '', 'tblheader', 'PDV_DEVENIRAGENT_TEL1');
        $table->addColumn(t('PHONE').' 2', 'PDV_DEVENIRAGENT_TEL2', '45', 'left', '', 'tblheader', 'PDV_DEVENIRAGENT_TEL2');
        $table->addColumn(t('FAX'), 'PDV_DEVENIRAGENT_FAX', '45', 'left', '', 'tblheader', 'PDV_DEVENIRAGENT_FAX');
        $table->addColumn('RRDI', 'PDV_DEVENIRAGENT_RRDI', '45', 'left', '', 'tblheader', 'PDV_DEVENIRAGENT_RRDI');
        $table->addColumn(t('LAT'), 'PDV_DEVENIRAGENT_LAT', '45', 'left', '', 'tblheader', 'PDV_DEVENIRAGENT_LAT');
        $table->addColumn(t('LNG'), 'PDV_DEVENIRAGENT_LNG', '45', 'left', '', 'tblheader', 'PDV_DEVENIRAGENT_LNG');
        $table->addColumn(t('NDP_LIAISON_ID'), 'LIAISON_LABEL', '45', 'left', '', 'tblheader', 'PDV_DEVENIRAGENT_LIAISON_ID');


        $this->setResponse($form.$table->getTable());

        // hide add button
        $this->aButton['add'] = '';
        Backoffice_Button_Helper::init($this->aButton);
    }

    /** Génération du code d'import
     *
     * @param array $datas
     *
     */
    public function saveAction($datas = array())
    {
        $connection = Pelican_Db::getInstance();

        // on supprime les anciens
        $bind[':SITE_ID'] = $_SESSION[APP]['SITE_ID'];
        $connection->query('DELETE FROM #pref#_pdv_deveniragent where SITE_ID = :SITE_ID', $bind);

        // on ajoute les nouveaux
        if (count($datas) > 0) {
            foreach ($datas as $item) {
                Pelican_Db::$values = $item;
                Pelican_Db::$values['SITE_ID'] = (int) $_SESSION[APP]['SITE_ID'];

                $connection->updateTable(Pelican_Db::DATABASE_INSERT, '#pref#_pdv_deveniragent');
            }
        }
    }

    /** Génération du code d'import
     *
     * @param int $siteId
     *
     * @return string html
     */
    public function getImportForm($siteId)
    {

        $html = '<div id="importFo">';
        $html .= '<span style="font-weight:bold;">'.t('TRAD_IMPORT').'</span><br/> <br/>';
        $html .= '<form name="fFormImport" id="fFormImport" action="/_/Ndp_DealerLocDevenirAgent/importData" method="post" onSubmit="return checkImport();" enctype="multipart/form-data">';
        $html .= '<input type="hidden" name="MAX_FILE_SIZE" value="2097152">';
        $html .= '<input type="hidden" value="'.$siteId.'" id="site_id" name="site_id"/>';
        $html .= '<input type="file" name="FILE_IMPORT" id="FILE_IMPORT" size="40" /><br/>';

        $html .= '<br/><input name="submitUpload" type="submit" class="button" value="'.t('TRAD_IMPORT').'"/><br/> <br/>';
        $html .= '<script type="text/javascript">
                function checkImport() {
                    var sFichier = $("input[name=FILE_IMPORT]").val();
                    if (sFichier == "") {
                        alert(\'' . t('FICHIER_IMPORT_OBLIGATOIRE', 'js2').'\');
                        return false;
                     }
                }
                   </script>';
        $html .= '</div>';

        return $html;
    }

    /**
     * Methode permettant de lancer l'import des pdv
     * a partir d'un fichier recupere en POST
     * Si l'import s'est deroule correctement on redirige avec un bool a true
     */
    public function importDataAction()
    {
        $this->errors['FILE_IMPORT'] = false; // on considere qu'on est en erreur par defaut
        if (file_exists($_FILES['FILE_IMPORT']['tmp_name']) && $_FILES['FILE_IMPORT']['error'] == UPLOAD_ERR_OK) {
            unset($this->errors['FILE_IMPORT']); // pas d'erreur sur le fichier proprement dit

            // test CSV
            $isCSV = Backoffice_File_Helper::isCSV($_FILES['FILE_IMPORT']['type']);
            if (!$isCSV) {
                $this->errors['ISCSV'] = false;
            }
            // test UTF8
            $isUTF8 = Zend\Stdlib\StringUtils::isValidUtf8(file_get_contents($_FILES['FILE_IMPORT']['tmp_name']));
            if (!$isUTF8) {
                $this->errors['ISUTF8'] = false;
            }

            if (empty($this->errors)) {
                $handle = fopen($_FILES['FILE_IMPORT']['tmp_name'], 'r');
                if ($handle) {
                    $datas = $this->getDevenirAgentDatasToAdd($handle);
                    fclose($handle);
                }
            }
        }

        if (empty($this->errors)) {
            // on met a jour la bdd
            $this->saveAction($datas);
            $_SESSION[APP]['MESSAGE_KEY'] = "<span  style='color: green'>".t('NDP_DEVENIRAGENT_IMPORT_OK').', '.count($datas).' '.t('NDP_DEVENIRAGENT_ROW_ADDED').'</span><br/> <br/>';

        } else {
            $_SESSION[APP]['MESSAGE_KEY'] = $this->getErrorMessage();
        }

        echo '<script type="text/javascript">location.href = "/_/Index/child?tid='.Pelican::$config['TEMPLATE_ADMIN_DEALERLOCATOR_DEVENIRAGENT'].'"</script>';
    }

    /**
     * retourne le message de retour des erreurs
     *
     * @return string $message
     */
    public function getErrorMessage()
    {

        $message = "<span  style='color: red'>".t('NDP_DEVENIRAGENT_IMPORT_KO');
        if ($this->errors['FILE_IMPORT'] === false) {
            // @TODO message erreur non specifie
        }
        if ($this->errors['ISCSV'] === false) {
            $message .= '<br/>'.t('NDP_DEVENIRAGENT_CSV_KO');
        }
        if ($this->errors['ISUTF8'] === false) {
            $message .= '<br/>'.t('NDP_DEVENIRAGENT_UTF8_KO');
        }
        if ($this->errors['HAS_HEADER'] === false) {
            $message .= '<br/>'.t('NDP_DEVENIRAGENT_HEADER_KO');
        }
        if ($this->errors['HAS_NB_FIELD'] === false) {
            $message .= '<br/>'.t('NDP_DEVENIRAGENT_NBFIELD_KO');
        }
        if (!empty($this->errors['DOUBLON'])) {
            foreach ($this->errors['DOUBLON'] as $key => $item) {
                $message .= '<br/>'.t('POPUP_TABLE_LINE').' '.$key.': '.t('NDP_DUPLICATE').' '.$item;
            }
        }
        if (!empty($this->errors['DATA'])) {
            foreach ($this->errors['DATA'] as $key => $item) {
                $message .= '<br/>'.t('POPUP_TABLE_LINE').$key.':'.implode(',', $item);
            }
        }
        $message .= '</span><br/> <br/>';

        return $message;
    }

    /**
     * Retourne les données à inserer
     *
     * @param resource $handle
     * 
     * @return array $datas
     */
    public function getDevenirAgentDatasToAdd($handle)
    {
        $fields = array();
        $datas = array();
        $i = 1;
        $idsUsed = array();

        while (($row = fgetcsv($handle, 4096, ';', '"')) !== false) {
            if (empty($fields)) {
                $fields = $row;
                if (!isset($fields[0])) {
                    // cas pas une seule ligne
                    $this->errors['HAS_HEADER'] = false;
                    break;
                } elseif (count($fields) != self::IMPORT_NB_FIELD) {
                    // KO : il n'y a pas IMPORT_NB_FIELD champs
                    $this->errors['HAS_NB_FIELD'] = false;
                     break;
                } else {
                    continue;
                }
            }

            // on verifie les datas pour chaque ligne du fichier
            $errorTmp = array();
            foreach ($this->mappingCsvToBdd as $colKey => $bddParams) {
                // si le champ est mappé en bdd
                // $bddParams[0] : nom du champ en bb
                // $bddParams[1] : longueur max
                if ($bddParams[0] != '') {
                    switch ($colKey) {
                        case 0: // test specific sur Id
                             if (in_array($row[$colKey], $idsUsed)) {
                                 $this->errors['DOUBLON'][$i] = $row[$colKey];
                            } elseif ($row[$colKey] != '' && strlen($row[$colKey]) <= $bddParams[1] && is_numeric($row[$colKey])) {
                                $data[$bddParams[0]] = $row[$colKey];
                            } else {
                                $errorTmp[] = $fields[$colKey];
                            }
                            $idsUsed[] = $row[$colKey];
                            break;
                        case 17 : // Latitude
                        case 18 : // Longitude
                            if ($row[$colKey] != '' && is_numeric($row[$colKey])) {
                                $data[$bddParams[0]] = $row[$colKey];
                            } else {
                                $errorTmp[] = $fields[$colKey];
                            }
                            break;
                        case 19 : // ID de la liaison
                            if ($row[$colKey] != '' && strlen($row[$colKey]) <= $bddParams[1] && is_numeric($row[$colKey])) {
                                $data[$bddParams[0]] = $row[$colKey];
                            } else {
                                $errorTmp[] = $fields[$colKey];
                            }
                            break;
                        default :
                            if (!$bddParams[1] || strlen($row[$colKey]) <= $bddParams[1]) {
                                 $data[$bddParams[0]] = $row[$colKey];
                            } else {
                                $errorTmp[] = $fields[$colKey];
                            }

                    }
                }
            }

            if (count($errorTmp) > 0) {
                $this->errors['DATA'][$i] = $errorTmp;
            } else {
                $datas[] = $data;
            }

            $i++;
        }

        return $datas;
    }

}
