<?php
ini_set('max_execution_time', 0);
set_time_limit(0);
include_once 'config.php';
Pelican::$config['SHOW_DEBUG'] = true;
// $mediaUrl = 'http://www.box.net/shared/oh2u2unl1hbmx2n8hhep';
$baseUrl = $_SERVER['REDIRECT_URL'];
// $mediaUrl = Pelican::$config ['MEDIA_HTTP'] . '/mobile';
$mediaUrl = 'http://phpfactory_mobile.interakting.com'; // 'http://phpfactory-media.interakting.com/mobile';
$searchUrl = 'http://www.google.fr/search?tbm=isch&hl=fr&source=hp&biw=1920&bih=971&q=';

if ($_GET['updateDatabase']) {
    include_once (pelican_path('Http/UserAgent/Features/Adapter/WurflApi'));
    Pelican_Http_UserAgent_Features_Adapter_WurflApi::updateDatabase();
} elseif ($_GET['updateTable']) {
    include_once (pelican_path('Http/UserAgent/Features/Adapter/WurflApi'));
    Pelican_Http_UserAgent_Features_Adapter_WurflApi::initWurflTable();
    Pelican_Cache::clean('Mobile/List');
} else {
    
    $urlsimulator = '/?';
    if ($_GET['external']) {
        $external = rawurlencode($_GET['external']);
        $urlsimulator = '/library/Pelican/Http/UserAgent/public/external.php?url=' . $external . '&';
    }
    
    if ($_GET['id']) {
        $id = rawurldecode($_GET['id']);
    }
    
    if (empty($_GET['brand']) || empty($_GET['model'])) {
        $_SESSION['HTTP_USER_AGENT'] = null;
    }
    
    if ($_GET['brand']) {
        $brandName = rawurldecode($_GET['brand']);
        $_SESSION['HTTP_USER_AGENT'] = null;
    }
    if ($_GET['model']) {
        $modelName = rawurldecode($_GET['model']);
        $_SESSION['HTTP_USER_AGENT'] = null;
    }
    if ($_GET['markup']) {
        $markup = rawurldecode($_GET['markup']);
    }
    
    $explorer = new Device_Explorer();
    if ($_GET['filter']) {
        $filter = array();
        $temp = explode('::', $_GET['filter']);
        $filter[$temp[0]] = $temp[1];
        $explorer->setFilter($filter);
    }
    
    if (! $brandName) {
        /**
         * liste des marques
         */
        $brandList = $explorer->getBrandList();
    } else {
        if (! $modelName) {
            $modelList = $explorer->getModelList($brandName);
        } else {
            $modelDetail = $explorer->getModelDetail($brandName, $modelName);
            if ($_GET['id']) {
                $id = rawurldecode($_GET['id']);
            }
            if ($id) {
                $deviceDetail = $explorer->getDeviceDetail($id);
            }
        }
    }
    
    // hauteur de la previsu
    $height = 500;
    $minheight = 100;
    if (isset($explorer->capabilities['display']['resolution_height'])) {
        if ($explorer->capabilities['display']['resolution_height'] >= $height) {
            $height = $explorer->capabilities['display']['resolution_height'] + 30;
        }
        if ($explorer->capabilities['display']['resolution_height'] <= $minheight) {
            $explorer->capabilities['display']['resolution_height'] = $minheight;
        }
    }
    $width = 500;
    if (isset($explorer->capabilities['display']['resolution_width'])) {
        if ($explorer->capabilities['display']['resolution_width'] >= $width) {
            $width = $explorer->capabilities['display']['resolution_width'] + 30;
        }
    }
    ?>
<head>
<title>Device explorer</title>
<link
	href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/redmond/jquery-ui.css"
	media="screen" rel="stylesheet" type="text/css" />
<script src="/library/External/jquery/jquery-1.6.1.min.js"
	type="text/javascript"></script>
<script src="/library/External/jquery/ui/js/jquery.ui.core.min.js"
	type="text/javascript"></script>
<script src="/library/External/jquery/ui/js/jquery.ui.widget.min.js"
	type="text/javascript"></script>
<script src="/library/External/jquery/ui/js/jquery.ui.position.min.js"
	type="text/javascript"></script>
<script src="/library/External/jquery/ui/js/jquery.effects.core.min.js"
	type="text/javascript"></script>
<script src="/library/External/jquery/ui/js/jquery.ui.accordion.min.js"
	type="text/javascript"></script>
<script src="/library/External/jquery/ui/js/jquery.ui.tabs.min.js"
	type="text/javascript"></script>
<script
	src="/library/External/jquery/image-dropdown/js/jquery.dd.2.1.js"
	type="text/javascript"></script>
<link rel="stylesheet" type="text/css"
	href="/library/External/jquery/image-dropdown/css/dd.css" />
<link rel="stylesheet" type="text/css" href="<?=$mediaUrl?>/style.css" />
<script>
    $(function() {
        $( "#accordion" ).accordion({
            autoHeight: false,
            navigation: true
        });
    });

    $(function() {
        $( "#tabs" ).tabs();
    });

    </script>
<script>
function showDevice(id, brand, model)
{
    document.location.href='<?=$baseUrl?>?id=' + escape(id) + '&brand=' + escape(brand)+ '&model=' + escape(model);
}
function showMarkup(id, brand)
{
    document.location.href='<?=$baseUrl?>?markup=' + escape(id) + '&brand=' + escape(brand);
}
function showBrand(brand)
{
    document.location.href='<?=$baseUrl?>?brand=' + escape(brand);
}

function getIFrameDocument(aID)
{
    /* if contentDocument exists, W3C compliant (Mozilla) */
    if (document.getElementById(aID).contentDocument) {
        return document.getElementById(aID).contentDocument;
    } else {
        /* IE */

        return document.frames[aID].document;
    }
}
</script>
</head>
<body>
	<div id="content">
		<div class="container">
			<div class="all-content">

<?php
    echo '<h1>PHP Factory Device Explorer</h1>';
    if ($brandList) {
        echo '<h2>Base de :</h2>';
        echo Pelican_Html::center(Pelican_Html::div(array(
            'class' => "greenup"
        ), $explorer->getCount('brand_name') . ' constructeurs<br />
    ' . $explorer->getCount('model_name') . ' modeles<br />
    ' . $explorer->getCount('device_id') . ' signatures'));
        echo $explorer->getFilterTags($brandName);
        
        echo $brandList;
    }
    
    if ($modelList) {
        echo $explorer->getPath($brandName);
        echo '<h2>' . $brandName . ' : </h2>';
        echo Pelican_Html::center(Pelican_Html::div(array(
            'class' => "greenup"
        ), '<li>' . $explorer->getCount('model_name', $brandName) . ' modeles</li>
    <li>' . $explorer->getCount('device_id', $brandName) . ' signatures</li>'));
        echo $explorer->getFilterTags($brandName, $modelName);
        
        echo $modelList;
    }
    if ($modelDetail) {
        echo $explorer->getPath($brandName, $modelName);
        echo Pelican_Html::div(array(
            "class" => "proptitle"
        ), Pelican_Html::img(array(
            src => $explorer->getModelImage($brandName, $modelName),
            "class" => "propimg"
        )) . Pelican_Html::h2(array(
            "class" => "propheading"
        ), str_replace($brandName . ' ' . $brandName, $brandName, $brandName . ' ' . $modelName)) . Pelican_Html::div(array(
            "class" => "clearboth"
        )));
        echo $explorer->getFilterTags($brandName, $modelName, $id);
        echo $explorer->getFilter('mobile_browser_full', $brandName, $modelName, $id);
        echo $explorer->getFilter('device_os_full', $brandName, $modelName, $id);
        
        /*
         * if (file_exists(Pelican::$config['LIB_ROOT'] . '/mobile_tools/resources/images/' . $explorer->capabilities['identity']['fall_back'] . '.gif')) { echo Pelican_Html::img(array( src => Pelican::$config['MEDIA_HTTP'] . '/application/configs/Wurfl/images/' . $explorer->capabilities['identity']['fall_back'] . '.gif' )) . '<br />'; } if (file_exists(Pelican::$config['LIB_ROOT'] . '/mobile_tools/resources/images/' . $explorer->capabilities['identity']['device_id'] . '.gif')) { echo Pelican_Html::img(array( src => Pelican::$config['MEDIA_HTTP'] . '/application/configs/Wurfl/images/' . $explorer->capabilities['identity']['device_id'] . '.gif' )) . '<br />'; }
         */
        echo $modelDetail;
        if ($deviceDetail) {
            ?>
    <!-- <div id="summary"
            class="ui-widget ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom ui-accordion-content-active ui-state-focus"
            style="padding: 10px; margin-left: 20px; font-size: 12px;"></div> -->
<?php
        }
    }
    ?>

<!---------- ----------->
				<span style="clear: both;"></span>
				<!---------- ----------->
        <?php
    if ($explorer->capabilities) {
        ?>
<div id="tabs">
					<ul>
						<li><a href="#display">Affichage</a></li>
						<li><a href="#accordion"><?=t('Informations')?></a></li>
					</ul>

					<!---------- ----------->
					<div id="display"
    style="overflow:hidden;marging:10px;padding: 10px; width: <?=$width?>px; height: <?=$height?>px; font-size: 12px; ">
<?php
        
        $w['resolution'] = $explorer->capabilities['display']['resolution_width'];
        $h['resolution'] = $explorer->capabilities['display']['resolution_height'];
        $w['screen'] = $explorer->capabilities['display']['physical_screen_width'] * 3.779527559;
        $h['screen'] = $explorer->capabilities['display']['physical_screen_height'] * 3.779527559;
        if (! $_GET['screen']) {
            $w = $w['resolution'];
            $h = $h['resolution'];
        } else {
            $w = $w['screen'];
            $h = $h['screen'];
        }
        ?>
<button
							onclick="window.open('<?=$urlsimulator . "useragent=" . rawurlencode($explorer->capabilities['identity']['user_agent'])?>')";>Voir
							dans une fenetre autonome</button>
						<br />
						<iframe id="child" name="child" width="<?=$w?>" height="<?=$h?>"
							style="border: 1px;"
							src="<?=$urlsimulator . "useragent=" . rawurlencode($explorer->capabilities['identity']['user_agent'])?>"></iframe>
					</div>
					<!---------- ----------->

					<div id="accordion">

    <?php
        foreach ($explorer->capabilities as $group => $cap) {
            ?>
    <h3>
							<a href="#"><?=$group?></a>
						</h3>
						<div>
    <?php
            foreach ($cap as $key => $value) {
                ?>
        <div><?=Pelican_Html::label(Pelican_Html::b($key))?> : <span><?=formatDisplay($value)?></span>
							</div>
    <?php
            }
            ?>
    </div>
    <?php
        }
        ?>
</div>
					<!---------- ----------->
<?php
    }
    ?>
</div>

			</div>
		</div>
	</div>
</body>
</html>
<?php
}

function mobile ($buffer)
{
    $return = $buffer;
    $return = str_replace('</head>', '<style>.art-post-body {padding:2px !important;}</style></head>', $return);
    
    return $return;
}

function formatDisplay ($value)
{
    $return = $value;
    switch ($value) {
        case '0':
        case 'false':
        case 'not_supported':
            {
                $return = Pelican_Html::span(array(
                    style => "color:red;"
                ), $value);
                break;
            }
        case 'true':
        case 'supported':
            {
                $return = Pelican_Html::span(array(
                    style => "color:green;"
                ), $value);
                break;
            }
    }
    
    return $return;
}

class Device_Explorer
{

    public $capabilities = array();

    public $count = array();

    public function __construct ()
    {
        if (! $this->_isReady()) {
            require_once 'Pelican/Http/UserAgent/Features/Adapter/WurflApi.php';
            Pelican_Http_UserAgent_Features_Adapter_WurflApi::initWurflTable();
        }
    }

    private function _isReady ()
    {
        $oConnection = Pelican_Db::getInstance();
        $count = $oConnection->queryItem('select count(*) from #pref#_wurfl');
        
        return ($count > 0);
    }

    public function getCount ($field, $brand = '')
    {
        if (! $this->count[$field . '_' . $brand]) {
            $oConnection = Pelican_Db::getInstance();
            if ($brand) {
                $where[] = "brand_name='" . $brand . "'";
            }
            if ($this->filter) {
                $where[] = $this->filter;
            }
            $this->count[$field . '_' . $brand] = $oConnection->queryItem('select count(distinct ' . $field . ') from #pref#_wurfl' . ($where ? ' where ' . implode(' AND ', $where) : ''));
        }
        
        return $this->count[$field . '_' . $brand];
    }

    public function setFilter ($data)
    {
        $key = key($data);
        $this->filter = $key . "='" . $data[$key] . "'";
    }

    public function getFilter ($field, $brand = '', $model = '', $device = '')
    {
        $upfield = strtoupper($field);
        $oConnection = Pelican_Db::getInstance();
        if ($brand) {
            $where[] = "brand_name='" . $brand . "'";
        }
        if ($model) {
            $where[] = "model_name='" . $model . "'";
        }
        /*
         * if ($device) { $where[] = "device_id='" . $device . "'"; }
         */
        
        $data = $oConnection->queryTab('select ' . $upfield . ', count(1) from #pref#_wurfl' . ($where ? ' where ' . implode(' AND ', $where) : '') . ' group by ' . $field . ' order by ' . $field);
        foreach ($data as $values) {
            $filter = $field . "::" . $values[$upfield];
            if ($values[$upfield]) {
                $li[] = Pelican_Html::li(array(
                    "class" => ($this->filter == $field . "='" . $values[$upfield] . "'" ? " selectedtag" : "")
                ), Pelican_Html::a(array(
                    href => $baseUrl . "?" . "filter=" . $filter . ($brand ? '&brand=' . $brand : '') . ($model ? '&model=' . $model : ''),
                    "class" => "taglink"
                ), $values[$upfield]));
            }
        }
        if ($li) {
            $return = Pelican_Html::fieldset(array(), Pelican_Html::legend($field) . Pelican_Html::ul(array(
                "class" => "tags"
            ), implode('', $li)));
        }
        
        return $return;
    }

    public function getFilterTags ($brand = '', $model = '', $device = '')
    {
        $return = $this->getFilter('device_type', $brand, $model, $device);
        $return .= $this->getFilter('release_year', $brand, $model, $device);
        $return .= $this->getFilter('markup', $brand, $model, $device);
        $return .= $this->getFilter('device_os', $brand, $model, $device);
        // $return .= $this->getFilter('device_os_full', $brand, $model, $device);
        $return .= $this->getFilter('pointing_method', $brand, $model, $device);
        
        return $return;
    }

    public function getBrandList ()
    {
        $oConnection = Pelican_Db::getInstance();
        
        $where = '';
        if ($this->filter) {
            $where = ' where ' . $this->filter . ' ';
        }
        
        $data = $oConnection->queryTab('select BRAND_NAME, count(distinct model_name) NBMODEL from #pref#_wurfl ' . $where . ' group by brand_name order by brand_name');
        $k = 0;
        foreach ($data as $brandData) {
            $brand = $brandData['BRAND_NAME'];
            $k ++;
            $tr[self::getRowIndex($k)][] = Pelican_Html::td(array(
                style => 'text-align:center; vertical-align:top;'
            ), Pelican_Html::div(array(
                'class' => 'vendor-container'
            ), Pelican_Html::a(array(
                href => $baseUrl . '?brand=' . rawurlencode($brandData['BRAND_NAME']) . ($_GET['filter'] ? '&filter=' . $_GET['filter'] : '')
            ), Pelican_Html::img(array(
                'class' => 'vendor-img sprite-brand sprite-' . self::cleanName(strtolower($brand)),
                width => '77',
                height => '91',
                src => '/library/public/images/xtrans.gif'
            )) . $brand . ' (' . $brandData['NBMODEL'] . ')')));
        }
        
        return self::getList($tr);
    }

    public function getModelList ($brandName)
    {
        $oConnection = Pelican_Db::getInstance();
        
        $where = '';
        if ($this->filter) {
            $where = ' and ' . $this->filter . ' ';
        }
        $data = $oConnection->queryTab("select BRAND_NAME, MARKETING_NAME, MODEL_NAME, count(*) as NBDEVICE from #pref#_wurfl where brand_name='" . $brandName . "' " . $where . " group by brand_name,model_name order by CONCAT(RELEASE_YEAR,'/',RELEASE_MONTH) desc");
        $k = 0;
        foreach ($data as $modelData) {
            $brand = ($modelData['BRAND_NAME'] ? $modelData['BRAND_NAME'] : '[divers]');
            $k ++;
            
            $img = Pelican_Html::img(array(
                'class' => 'vendor-img',
                width => '75',
                height => '100',
                src => $this->getModelImage($brand, $modelData['MODEL_NAME'])
            )) . '<br />' . $modelData['MODEL_NAME'] . ($modelData['MARKETING_NAME'] ? '<br />[' . $modelData['MARKETING_NAME'] . '] ' : '') . '<br/>(' . $modelData['NBDEVICE'] . ')' . '<br />';
            if ($_GET['recherche']) {
                $img .= Pelican_Html::a(array(
                    href => $this->getModelSearchImage($brand, $modelData['MODEL_NAME']),
                    "target" => "_blank"
                ), 'Recherche');
            }
            
            $tr[self::getRowIndex($k)][] = Pelican_Html::td(array(
                style => 'text-align:center; vertical-align:top;'
            ), Pelican_Html::div(array(
                'class' => 'vendor-container'
            ), Pelican_Html::a(array(
                href => $baseUrl . '?brand=' . rawurlencode(($modelData['BRAND_NAME'])) . '&model=' . rawurlencode($modelData['MODEL_NAME']) . ($_GET['filter'] ? '&filter=' . $_GET['filter'] : '')
            ), $img)));
        }
        
        return self::getList($tr);
    }

    public function getModelDetail ($brandName, $modelName)
    {
        $oConnection = Pelican_Db::getInstance();
        
        $where[] = "brand_name='" . $brandName . "'";
        $where[] = "model_name='" . $modelName . "'";
        if ($this->filter) {
            $where[] = $this->filter;
        }
        
        $data = $oConnection->queryTab("select DEVICE_ID from #pref#_wurfl where " . implode(' AND ', $where));
        
        foreach ($data as $device) {
            if (! $_GET['id']) {
                $_GET['id'] = $device['DEVICE_ID'];
            }
            $option[] = Pelican_Html::option(array(
                value => $device['DEVICE_ID'],
                selected => ($_GET['id'] == $device['DEVICE_ID'] ? 'selected' : '')
            ), $device['DEVICE_ID']);
        }
        $return = '';
        if (count($option) >= 1) {
            $return = Pelican_Html::label(count($option) . ' signatures : ') . Pelican_Html::select(array(
                id => "terminal",
                onchange => "showDevice(this.value, '" . $brandName . "','" . $modelName . "');"
            ), implode('', $option));
        }
        
        return $return;
    }

    public function getPath ($brand = '', $model = '')
    {
        global $baseUrl;
        
        $url['Accueil'] = Pelican_Html::a(array(
            href => $baseUrl
        ), 'Accueil');
        if ($model) {
            $url[] = Pelican_Html::a(array(
                href => $baseUrl . '?brand=' . rawurlencode($brand)
            ), $brand);
        }
        if ($id) {
            $url[] = Pelican_Html::a(array(
                href => $baseUrl . '?brand=' . rawurlencode($brand) . '&model=' . rawurlencode($model)
            ), $model);
        }
        
        return implode(' &gt; ', $url);
    }

    public static function cleanName ($name)
    {
        $return = str_replace(array(
            '.',
            ' ',
            '-',
            '/'
        ), array(
            '_',
            '_',
            '_',
            '_'
        ), $name);
        
        return $return;
    }

    public static function getRowIndex ($value)
    {
        $return = abs(intval(($value - 1) / 8));
        
        return $return;
    }

    public static function getList ($tr)
    {
        foreach ($tr as $td) {
            $t[] = Pelican_Html::tr(array(), implode('', $td));
        }
        $return = Pelican_Html::table(array(
            border => "0",
            cellpadding => "0",
            cellspacing => "0"
        ), implode('', $t));
        
        return $return;
    }

    public function getDeviceDetail ($id)
    {
        $this->capabilities = Pelican_Cache::fetch('Mobile/List', array(
            $id
        ));
        $return = array();
        $return[] = Pelican_Html::b('Langage : ') . $this->capabilities['markup']['preferred_markup'];
        $return[] = Pelican_Html::b('Navigateur : ') . $this->capabilities['product_info']['mobile_browser'];
        $return[] = Pelican_Html::b('Os : ') . $this->capabilities['product_info']['device_os'];
        $return[] = Pelican_Html::b('Resolution : ') . $this->capabilities['display']['resolution_height'] . 'x' . $this->capabilities['display']['resolution_width'] . ' pixels';
        $return[] = Pelican_Html::b('Ecran : ') . $this->capabilities['display']['physical_screen_height'] . 'x' . $this->capabilities['display']['physical_screen_width'] . ' millimetres';
        $return[] = Pelican_Html::b('Flash : ') . ($this->capabilities['flash_lite']['full_flash_support'] == 'true' ? 'Oui' : 'Non');
        $return[] = Pelican_Html::b('jpg : ') . ($this->capabilities['image_format']['jpg'] == 'true' ? 'Oui' : 'Non');
        $return[] = Pelican_Html::b('gif : ') . ($this->capabilities['image_format']['gif'] == 'true' ? 'Oui' : 'Non');
        
        return implode('<br />', $return);
    }

    public function getModelImage ($brand, $model)
    {
        global $mediaUrl;
        $brand = str_replace(array(
            'sonyericsson',
            'rim',
            'acer_incorporated'
        ), array(
            'sony_ericsson',
            'blackberry',
            'acer'
        ), strtolower($brand));
        
        return str_replace('blackberry_blackberry', 'blackberry', $mediaUrl . '/devices/' . self::cleanName($brand) . '/' . self::cleanName($brand) . '_' . self::cleanName(strtolower($model)) . '.jpg');
    }

    public function getModelSearchImage ($brand, $model)
    {
        global $searchUrl;
        $return = basename($this->getModelImage($brand, $model));
        
        return str_replace('.jpg', '', $searchUrl . $return);
    }
}
