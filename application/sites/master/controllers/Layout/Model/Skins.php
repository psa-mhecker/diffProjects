<?php

class Layout_Model_Skins_Controller extends Pelican_Controller_Front
{

    public function indexAction()
    {
        if (empty($_REQUEST['skin'])) {
            $_REQUEST['skin'] = '';
        }
        $url = $_SERVER['REQUEST_URI'];
        $url = str_replace('skin=' . $_REQUEST['skin'], '1=1', $url);
        $url = str_replace('&1=1', '', $url);
        $skin = Pelican_Cache::fetch('Frontend/Skins');
        ksort($skin);
        foreach ($skin as $key => $value) {
            $key = basename($key);
            $opt[] = Pelican_Html::option(array(
                label => $value , 
                value => $key , 
                selected => ($key == $_SESSION[APP]['skin'] ? selected : '')), $value);
        }
        $query = str_replace('index.php', '', $_SERVER['SCRIPT_NAME'] . '?' . trim(str_replace('&&', '&', str_replace('skin=' . $_REQUEST['skin'], '', $_SERVER['QUERY_STRING']) . '&skin='), '&'));
        $this->assign('skins', Pelican_Html::select(array(
            onchange => "document.location.href = '" . $query . "' + this.value"), implode('', $opt)), false);
        
        $this->setParam('ZONE_TITRE', 'Skin');
        $this->model();
        $this->fetch();
    }
}