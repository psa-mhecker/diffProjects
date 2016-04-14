<?php
include_once 'config.php';

Pelican_Request::$multidevice = true;
Pelican::$config['SHOW_DEBUG'] = true;

unset($_SESSION['HTTP_USER_AGENT']);
//$_GET['HTTP_USER_AGENT'] = 'Mozilla/5.0 (Linux; Android 4.1.2; GT-I9300 Build/JZO54K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/30.0.1599.82 Mobile Safari/537.36';

if ($_GET['HTTP_USER_AGENT']) {
    $_SERVER['HTTP_USER_AGENT'] = $_GET['HTTP_USER_AGENT'];
    $_SESSION['HTTP_USER_AGENT'] = $_GET['HTTP_USER_AGENT'];
}

if ($_SESSION['HTTP_USER_AGENT']) {
    if ($_SESSION['HTTP_USER_AGENT'] != $_SERVER['HTTP_USER_AGENT']) {
        $_SERVER['HTTP_USER_AGENT'] = $_SESSION['HTTP_USER_AGENT'];
    }
}

Pelican_Request::identifyUserAgent();
//$capability = self::$userAgentFeatures['device']->device->getFeature($feature);
$id = Pelican_Request::$userAgentFeatures['device']->device->getFeature('device_id');
$features = Pelican_Request::$userAgentFeatures['device']->device->getAllFeatures();

if ($features['is_mobile']) {
    $capabilities = Pelican_Cache::fetch('Mobile/List', array(
            $id,
        ));
} else {
    $capabilities ['product_info'] = $features;
}

function formatDisplay($value)
{
    $return = $value;
    switch ($value) {
        case '0':
        case 'false':
        case 'not_supported':
            {
                $return = Pelican_Html::span(array(
                    style => "color:red;",
                ), $value);
                break;
            }
        case 'true':
        case 'supported':
            {
                $return = Pelican_Html::span(array(
                    style => "color:green;",
                ), $value);
                break;
            }
    }

    return $return;
}

?>
<head>
<title>Device identification</title>
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
	<h1>PHP Factory Device identification</h1>

	<div id="content">
		<div class="container">
			<div class="all-content">
				<form>
					<input type="text" name="HTTP_USER_AGENT" value="<?=$_SERVER['HTTP_USER_AGENT']?>" style="width:100%"/>
					<input type="submit">
				</form>
					<div id="accordion">

    <?php
            foreach ($capabilities as $group => $cap) {
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
unset($_SESSION['HTTP_USER_AGENT']);
?>
