<?php
include_once pelican_path('Plugin');

class Administration_Plugin_Build_Controller extends Pelican_Controller_Back
{
    protected $administration = true;

    protected $form_name = "build";

    protected $field_id = "BUILD_ID";

    protected $defaultOrder = "BUILD_LABEL";

    protected $front = array();

    public function listAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $this->id = - 2;
        $oConnection = Pelican_Db::getInstance();

        $this->sAddUrl = false;

        parent::editAction();
        // ------------ Begin startStandardForm ----------
        $this->oForm = Pelican_Factory::getInstance('Form', true);
        $this->oForm->bDirectOutput = false;
        $form = $this->oForm->open(Pelican::$config['DB_PATH']);
        $form .= $this->beginForm($this->oForm);
        $form .= $this->oForm->beginFormTable();
        // ------------ End startStandardForm ----------

        $form .= $this->oForm->createInput("PLUGIN_LABEL", t('POPUP_LABEL_NAME'), 100, "", true, '', $this->readO, 100);

        // blocs
        $sqlData = "select ZONE_ID id, ZONE_LABEL lib from #pref#_zone order by lib";
        $form .= $this->oForm->createAssocFromSql($oConnection, 'ZONE_ID', "Blocs", $sqlData, "", false, true, $this->readO, 8, 200, false, "", array(
            'ZONE_LABEL',
        ));

        // contents
        $sqlData = "select TEMPLATE_ID id, TEMPLATE_LABEL lib from #pref#_template where TEMPLATE_TYPE_ID = 3 order by lib";
        $form .= $this->oForm->createAssocFromSql($oConnection, 'TEMPLATE_ID_CONTENT', "Contenus", $sqlData, "", false, true, $this->readO, 8, 200, false, "", array(
            'TEMPLATE_LABEL',
        ));

        // modules
        $sqlData = "select TEMPLATE_ID id, TEMPLATE_LABEL lib from #pref#_template where TEMPLATE_TYPE_ID = 1 order by lib";
        $form .= $this->oForm->createAssocFromSql($oConnection, 'TEMPLATE_ID_MODULE', "Modules d'administration", $sqlData, "", false, true, $this->readO, 8, 200, false, "", array(
            'TEMPLATE_LABEL',
        ));

        // navigation
        $sqlData = "select TEMPLATE_ID id, TEMPLATE_LABEL lib from #pref#_template where TEMPLATE_TYPE_ID = 2 order by lib";
        $form .= $this->oForm->createComboFromSql($oConnection, "TEMPLATE_ID_NAVIGATION", "Navigation", $sqlData, "", false, $this->readO);

        // ------------ Begin stopStandardForm ----------
        $form .= $this->oForm->endFormTable();
        $form .= $this->endForm($this->oForm);
        $form .= $this->oForm->close();
        // ------------ End stopStandardForm ----------
        $this->setResponse($form);
    }

    public function saveAction()
    {
        $front = glob(str_replace('/backend', '', Pelican::$config['DOCUMENT_ROOT']).'/*');
        foreach ($front as $dir) {
            $dir = str_replace(str_replace('/backend', '', Pelican::$config['DOCUMENT_ROOT'].'/'), '', $dir);
            if (! in_array($dir, array(
                'backend',
                'frontend',
                'media',
            ))) {
                $this->front[] = $dir;
            }
        }

        $oConnection = Pelican_Db::getInstance();
        Pelican_Db::$values = $_POST;
        // Code
        $this->plugin['class'] = ucfirst(strtolower(str_replace(' ', '', Pelican_Db::$values['PLUGIN_LABEL'])));
        $this->plugin['code'] = strtolower($this->plugin['class']);
        $this->plugin['title'] = Pelican_Db::$values['PLUGIN_LABEL'];
        $this->plugin['description'] = Pelican_Db::$values['PLUGIN_DESCRIPTION'];
        $this->plugin['version'] = Pelican_Db::$values['PLUGIN_VERSION'];
        $this->plugin['date'] = Pelican_Db::$values['PLUGIN_DATE'];
        $this->plugin['category'] = Pelican_Db::$values['PLUGIN_CATEGORY'];
        $this->structure['root_dir'] = PLUGIN_ROOT."/".$this->plugin['code'];
        $this->structure['config'] = $this->structure['root_dir']."/".PLUGIN_CONFIG_FILE;
        $this->structure['plugin'] = $this->structure['root_dir']."/".$this->plugin['class'].".php";
        $this->structure['root']['plugin_bloc'] = $this->plugin['class'].'/'.PLUGIN_ROUTE_BLOC;
        $this->structure['root']['plugin_content'] = $this->plugin['class'].'/'.PLUGIN_ROUTE_CONTENT;
        $this->structure['root']['plugin_module'] = $this->plugin['class'].'/'.PLUGIN_ROUTE_MODULE;
        $this->structure['root']['plugin_navigation'] = $this->plugin['class'].'/'.PLUGIN_ROUTE_NAVIGATION;
        $this->structure['path']['backend']['controllers'] = 'backend/controllers';
        $this->structure['path']['backend']['views'] = 'backend/views/scripts';
        $this->structure['path']['frontend']['controllers'] = 'frontend/controllers';
        $this->structure['path']['frontend']['views'] = 'frontend/views/scripts';

        // liste des fichiers sélectionnés
        // zone
        if (Pelican_Db::$values['ZONE_ID']) {
            $sql = $oConnection->query('select * from #pref#_zone where ZONE_ID in ('.implode(',', Pelican_Db::$values['ZONE_ID']).')');
            $this->mergePaths('plugin_bloc', Pelican_Db::$values['ZONE_ID'], $oConnection->data['ZONE_BO_PATH'], $oConnection->data['ZONE_FO_PATH'], $oConnection->data['ZONE_LABEL']);
        }

        // content
        if (Pelican_Db::$values['TEMPLATE_ID_CONTENT']) {
            $sql = $oConnection->query('select * from #pref#_template where TEMPLATE_ID in ('.implode(',', Pelican_Db::$values['TEMPLATE_ID_CONTENT']).')');
            $this->mergePaths('plugin_content', Pelican_Db::$values['TEMPLATE_ID_CONTENT'], $oConnection->data['TEMPLATE_PATH'], $oConnection->data['TEMPLATE_PATH_FO'], $oConnection->data['TEMPLATE_LABEL']);
        }

        // module
        if (Pelican_Db::$values['TEMPLATE_ID_MODULE']) {
            $sql = $oConnection->query('select * from #pref#_template where TEMPLATE_ID in ('.implode(',', Pelican_Db::$values['TEMPLATE_ID_MODULE']).')');
            $this->mergePaths('plugin_module', Pelican_Db::$values['TEMPLATE_ID_MODULE'], $oConnection->data['TEMPLATE_PATH'], $oConnection->data['TEMPLATE_PATH_FO'], $oConnection->data['TEMPLATE_LABEL']);
        }

        // navigation
        if (Pelican_Db::$values['TEMPLATE_ID_NAVIGATION']) {
            $sql = $oConnection->query('select * from #pref#_template where TEMPLATE_ID in ('.implode(',', Pelican_Db::$values['TEMPLATE_ID_NAVIGATION']).')');
            $this->mergePaths('plugin_navigaton', Pelican_Db::$values['TEMPLATE_ID_NAVIGATION'], $oConnection->data['TEMPLATE_PATH'], $oConnection->data['TEMPLATE_PATH_FO'], $oConnection->data['TEMPLATE_LABEL']);
        }

        debug($this->path);

        // recherche des fichiers de cache

        // recherche des ressources publiques

        // recherche des librairies

        $this->buildModule();

        die();
    }

    protected function mergePaths($type, $post, $path_bo, $path_fo, $title)
    {
        if (is_array($post)) {
            foreach ($post as $i => $id) {
                $this->path[$type][$i]['id'] = $id;
                $this->path[$type][$i]['title'] = trim(preg_replace('/(\(.*\) ){0,1}(.*)/', '$2', $title[$i]));
                setlocale(LC_ALL, 'fr_FR.UTF-8');
                $this->path[$type][$i]['code'] = Pelican_Text::dropAccent(Pelican_Text::cleanText(ucwords(strtolower(str_replace("'", "", $this->path[$type][$i]['title']))), '_'));
                // BO
                if ($path_bo[$i]) {
                    $this->path[$type][$i]['backend']['route']['old'] = $path_bo[$i];
                    $this->path[$type][$i]['backend']['path']['old']['php'] = ($path_bo[$i] ? Pelican::$config["CONTROLLERS_ROOT"].'/'.str_replace('_', '/', $path_bo[$i]).'.php' : '');
                    if ($this->path[$type][$i]['backend']['path']['old']['php']) {
                        if (! file_exists($this->path[$type][$i]['backend']['path']['old']['php'])) {
                            var_dump('-- pb bo : '.$this->path[$type][$i]['backend']['path']['old']['php']);
                            unset($this->path[$type][$i]['backend']['path']['old']['php']);
                        } else {
                            // recherche des vues
                            $this->searchViews($type, $i, 'backend');
                        }
                        $this->newPath($type, $i, 'backend');
                    }
                }
                // FO
                if ($path_fo[$i]) {
                    $this->path[$type][$i]['frontend']['route']['old'] = $path_fo[$i];
                    $this->path[$type][$i]['frontend']['path']['old']['php'] = ($path_fo[$i] ? str_replace('backend', 'frontend', Pelican::$config["CONTROLLERS_ROOT"]).'/'.str_replace('_', '/', $path_fo[$i]).'.php' : '');
                    if ($this->path[$type][$i]['frontend']['path']['old']['php']) {
                        if (! file_exists($this->path[$type][$i]['frontend']['path']['old']['php'])) {
                            $notfound = true;
                            foreach ($this->front as $dir) {
                                $tmp = str_replace('frontend', $dir, $this->path[$type][$i]['frontend']['path']['old']['php']);
                                if ($notfound && file_exists($tmp)) {
                                    $notfound = false;
                                    $this->path[$type][$i]['frontend']['path']['old']['php'] = $tmp;
                                    // recherche des vues
                                    $this->searchViews($type, $i, 'frontend');
                                }
                            }
                            if ($notfound) {
                                var_dump('pb fo : '.$this->path[$type][$i]['frontend']['path']['old']['php']);
                            }
                        }

                        $this->newPath($type, $i, 'frontend');
                    }
                }
            }
        }
    }

    protected function searchViews($type, $i, $frontend)
    {
        // recherche des vues
        $views = str_replace(array(
            '.php',
            'controllers',
        ), array(
            '',
            'views/scripts',
        ), $this->path[$type][$i][$frontend]['path']['old']['php']);
        if (is_dir($views)) {
            $this->path[$type][$i][$frontend]['path']['old']['views'] = $views;
        }
    }

    protected function newPath($type, $i, $frontend)
    {
        if ($this->path[$type][$i]['backend']['path']['new']['php'] && $frontend == 'frontend') {
            // il faut que le fo == le bo
            $this->path[$type][$i][$frontend]['path']['new']['php'] = str_replace('backend', 'frontend', $this->path[$type][$i]['backend']['path']['new']['php']);
        } else {
            $old = $this->path[$type][$i][$frontend]['path']['old']['php'];
            $part = explode('/controllers', $old);

            $new = Pelican::$config["PLUGIN_ROOT"].'/'.$this->plugin['code'].'/'.$this->structure['path'][$frontend]['controllers'].'/'.$this->structure['root'][$type].'/'.$this->path[$type][$i]['code'];
            $new = str_replace('_', '/', $new);
            $this->path[$type][$i][$frontend]['path']['new']['php'] = $new.'.php';
        }
        if (! empty($this->path[$type][$i][$frontend]['path']['new']['php']) && ! empty($this->path[$type][$i][$frontend]['path']['old']['views'])) {
            $this->path[$type][$i][$frontend]['path']['new']['views'] = str_replace(array(
                '.php',
                'controllers',
            ), array(
                '',
                'views/scripts',
            ), $this->path[$type][$i][$frontend]['path']['new']['php']);
        }
        // route
        if (! empty($this->path[$type][$i][$frontend]['path']['new']['php'])) {
            $tmp = explode('/controllers/', str_replace('.php', '', $this->path[$type][$i][$frontend]['path']['new']['php']));
            $this->path[$type][$i][$frontend]['route']['new'] = str_replace('/', '_', $tmp[1]);
        }
    }

    protected function buildModule($temporary = true)
    {
        $sub = array(
            'backend',
            'frontend',
        );
        if (is_array($this->plugin)) {
            $this->filePutContent($this->structure['config'], 'test', $temporary);
            $this->filePutContent($this->structure['plugin'], 'test', $temporary);
            foreach ($this->path as $type) {
                foreach ($type as $front) {
                    foreach ($sub as $f) {
                        if (! empty($front[$f]['path']['new']['php'])) {
                            $content = file_get_contents($front[$f]['path']['old']['php']);
                            $content = str_replace('class '.$front[$f]['route']['old'], 'class '.$front[$f]['route']['new'], $content);

                            preg_match_all('/(Pelican_Cache\:\:fetch\()(.*)\,/', preg_replace('/\s+/', '', $content), $cache);
                            var_dump($cache);

                            $this->filePutContent($front[$f]['path']['new']['php'], $content, $temporary);
                        }
                        if (! empty($front[$f]['path']['new']['views'])) {
                            $this->verifyDir($front[$f]['path']['new']['views'], $temporary);
                        }
                    }
                }
            }
        }
    }

    protected function filePutContent($fullpath, $content, $temporary = false)
    {
        $fullpath = $this->makeDirTemporary($fullpath, $temporary);
        $dir = dirname($fullpath);
        $this->verifyDir($dir, $temporary);
        file_put_contents($fullpath, $content);
    }

    protected function verifyDir($dir, $temporary = false)
    {
        $dir = $this->makeDirTemporary($dir, $temporary);
        if (! is_dir($dir)) {
            $cmd = "mkdir -p -m 777 ".$dir;
            var_dump($cmd);
            Pelican::runCommand($cmd);
        }
    }

    protected function makeDirTemporary($path, $temporary = false)
    {
        $tmproot = Pelican::$config["VAR_ROOT"].'/build/modules';
        if ($temporary) {
            $return = str_replace(PLUGIN_ROOT, $tmproot, $path);
        } else {
            $return = $path;
        }

        return $return;
    }
}
