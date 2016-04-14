<?php
/**
 * Javascript pour l'atelier de retaillage.
 *
 * @author Raphaël Carles <rcarles@businessdecision.com>
 *
 * @since 15/05/2004
 */

// Check navigateur
$browser = array('type' => null, 'version' => null);
if (preg_match('#MSIE ([0-9]{1,2}.[0-9]{1,2})#', $_SERVER['HTTP_USER_AGENT'], $matches)) {
    $browser = array('type' => 'IE', 'version' => $matches[1]);
}
?>
<script type="text/javascript">
var current = new Object;
var ie = document.all;
var ns6 = document.getElementById && !document.all;
var z, x, y;
var mediaFormatWidth = "<?=$imageSize[0]?>px";
var mediaFormatHeight = "<?=$imageSize[1]?>px";
var mySlider = new Bs_Slider();
var horizMax = true;
var origMediaFormatWidth = <?=$imageSize[0]?>;
var origMediaFormatHeight= <?=$imageSize[1]?>;

var topelement = ns6 ? "HTML" : "BODY";

var dragapproved = false;

function evalStyle(value)
{
	if (value) {
		return parseInt(parseInt(value.replace("px","")) + 0);
	} else {
		return 0;
	}
}

function setPosition(object)
{
	var max_width = <?=$imageSize[0]?>;
	var max_height = <?=$imageSize[1]?>;

	z = document.getElementById("selection");
	var zTop = evalStyle(z.style.top);
	var zLeft = evalStyle(z.style.left);
	var zWidth = evalStyle(z.style.width);
	var zHeight = evalStyle(z.style.height);

	value = parseInt(object.value.replace("px",""));
	/** vérififications */
	switch (object.name) {
		case "top" :
		if (value > max_height-zHeight) value=max_height-zHeight;
		break;
		case "height" :
		if (value > max_height-zTop) value=max_height-zTop;
		break;
		case "left" :
		if (value > max_width-zWidth) value=max_width-zWidth;
		break;
		case "width" :
		if (value > max_width-zLeft) value=max_width-zLeft;
		break;
	}
	if (value <0 ) value = 0;
	object.value = parseInt(value + 0) + "px";
	z.style[object.name] = object.value;
	getPreview();
}

function setCachePosition()
{
	var correctionTop = -19;
	var correctionLeft = -1;
	<?php if ($browser['type'] == 'IE' && version_compare($browser['version'], '10.0', '<')): ?>
		var correctionTop = 0;
		var correctionLeft = 0;
	<?php endif; ?>

	var max_width = <?=$imageSize[0]?>;
	var max_height = <?=$imageSize[1]?>;
	var z = document.getElementById("selection");
	var zeroTop = parseInt(document.getElementById("container").offsetTop) + parseInt(document.getElementById("sliderDiv").style.height)+4;
	var zeroLeft = parseInt(document.getElementById("container").offsetLeft) + 1;
	var cache
	var zTop, iLeft, iWidth, iHeight;

	var zTop = evalStyle(z.style.top);
	var zLeft = evalStyle(z.style.left);
	var zWidth = evalStyle(z.style.width);
	var zHeight = evalStyle(z.style.height);

	// Tour Haut
	cache = document.getElementById("haut");
	cache.style.top = (zeroTop - 1) + correctionTop + "px";
	cache.style.left = zeroLeft + correctionLeft + "px";
	cache.style.width = max_width + "px";
	cache.style.height = (zTop + 1) + "px";

	// Tour Gauche
	cache = document.getElementById("gauche");
	cache.style.top = (zeroTop + zTop) + correctionTop + "px";
	cache.style.left = zeroLeft + correctionLeft + "px";
	cache.style.width = zLeft + "px";
	cache.style.height = zHeight + "px";

	// Tour Droit
	cache = document.getElementById("droite");
	cache.style.top = (zeroTop + zTop) + correctionTop + "px";
	cache.style.left = (zeroLeft + zLeft+zWidth) + correctionLeft + "px";
	cache.style.width = (max_width - (zLeft+zWidth)) + "px";
	cache.style.height = zHeight + "px";

	// Tour Bas
	cache = document.getElementById("bas");
	cache.style.top = (zeroTop + zTop + zHeight) + correctionTop + "px";
	cache.style.left = zeroLeft + correctionLeft + "px";
	cache.style.width = max_width + "px";
	cache.style.height = (max_height - (zTop+evalStyle(z.style.height)) + 1) + "px";
}

function changeMediaFormat()
{
	document.getElementById("iframeMediaFormat").src='<?=Pelican::$config["MEDIA_LIB_PATH"]?>/image_format.php?format=<?=$_GET["format"]?>';
}

function drags(e)
{
	if(!e && document.all) e=event;

	if (e.target) source = e.target;
	else if (e.srcElement) source = e.srcElement;
	if (source.nodeType == 3) source = source.parentNode;

	if (source.id == "container" || source.id == "selection") {
		var firedobj = source;
		if (firedobj != null) {
			bouton = e.button;
			//			if ((ie && bouton == 1) || bouton < 1) {
			if (bouton == 1 || (!ie && bouton < 1)) {
				z = firedobj;
				dragapproved = true;
				temp1 = parseInt(z.style.left + 0);
				temp2 = parseInt(z.style.top + 0);
				x = (ns6 ? e.clientX : e.clientX);
				y = (ns6 ? e.clientY : e.clientY);

				return false;
			}
		}
	}
	e.cancelBubble=false;

	return true;

}

function moveTop(value)
{
	z = document.getElementById("selection");
	z.style.top = parseInt(parseInt(z.style.top)+value)+"px";
	if (parseInt(z.style.top+0) < 0) {
		z.style.top= "0px";
	}
	if (parseInt(z.style.top +0) > <?=$imageSize[1]?> - parseInt(z.offsetHeight +0)) {
		// on tient compte de la bordure
		z.style.top = (<?=$imageSize[1]?> + 2 - parseInt(z.offsetHeight + 0))+"px";
	}
	document.getElementById('top').value=z.style.top;
	setCachePosition()
}

function moveLeft(value)
{
	z = document.getElementById("selection");
	z.style.left = parseInt(parseInt(z.style.left)+value)+"px";
	if (parseInt(z.style.left +0)<0) {
		z.style.left = "0px";
	}
	if (parseInt(z.style.left + 0) > <?=$imageSize[0]?> - parseInt(z.offsetWidth +0)) {
	// on tient compte de la bordure
		z.style.left = (<?=$imageSize[0]?> +2 - parseInt(z.offsetWidth + 0))+"px";
	}
	document.getElementById('left').value=z.style.left;
	setCachePosition()
}

function move(e)
{
	if(!e && document.all) e=event;

	if (e.target) source = e.target;
	else if (e.srcElement) source = e.srcElement;
	if (source.nodeType == 3) source = source.parentNode;

	//	document.getElementById('previewDiv').style.top = 20+(ns6 ? e.clientY : e.clientY+document.body.scrollTop);
	//	document.getElementById('previewDiv').style.left = 20+(ns6 ? e.clientX : e.clientX+document.body.scrollLeft);
	if (source.id == "container" || source.id == "selection") {
		if (dragapproved){
			z.style.top = Math.max((ns6 ? temp2 + e.clientY - y : temp2 + e.clientY - y),0) + "px";
			moveTop(0);
			z.style.left = Math.max((ns6 ? temp1 + e.clientX - x : temp1 + e.clientX - x),0) + "px";
			moveLeft(0);

			return false;
		}
	}
	ech_mousemove(e);

	return true;
}

function undrags(e)
{
	if(!e && document.all) e=event;

	if (e.target) source = e.target;
	else if (e.srcElement) source = e.srcElement;
	if (source.nodeType == 3) source = source.parentNode;

	if (source.id == "container" || source.id == "selection") {
		getPreview();
		dragapproved = false;
		if (!ie && !ns6) {
			return;
		}

		return false;
	}
	e.cancelBubble=false;

	return true;
}

function codeEvents()
{
	document.onmousemove = move;
	document.onkeyup = keyUpHandler;
	document.onkeydown = keyDownHandler;
	document.onmousewheel=wheel;
}


if (document.all) {
	document.onclick = drags;
}
if (document.layers) {
	document.captureEvents(Event.MOUSEDOWN);
}

codeEvents();

function keyDownHandler(e)
{
	var code;

	if(!e && document.all) e=event;

	if (e.target) source = e.target;
	else if (e.srcElement) source = e.srcElement;
	if (source.nodeType == 3) source = source.parentNode;

	code = ns6 ? e.which : e.keyCode;
	switch (code) {
		case 27 : undrags(e); break;
		case 37 : case 100 : moveLeft(-1); return false; break;
		case 39 : case 102 : moveLeft(1); return false; break;
		case 38 : case 104 : moveTop(-1); return false; break;
		case 40 : case 98 : moveTop(1);return false;  break;
		case 107 : if (mySlider.getValue()<mySlider.maxVal) mySlider.setValue(mySlider.getValue()+1); break;
		case 109 : if (mySlider.getValue()>mySlider.minVal) mySlider.setValue(mySlider.getValue()-1); break;
	}
}

function wheel(e)
{
	if(!e && document.all) e=event;

	if (e.target) source = e.target;
	else if (e.srcElement) source = e.srcElement;
	if (source.nodeType == 3) source = source.parentNode;

	if (source.id == "container" || source.id == "selection") {
		if (e.wheelDelta >= 120 || e.wheelDelta <= -120) {
			value = parseInt(e.wheelDelta/120);
			if (value > 0) {
				if (mySlider.getValue()<mySlider.maxVal+value)
				mySlider.setValue(mySlider.getValue()+value);
			}
			if (value < 0) {
				if (mySlider.getValue()>mySlider.minVal+value)
				mySlider.setValue(mySlider.getValue()+value);
			}

			return false;
		}
	}
	e.cancelBubble=false;

	return true;
}

function keyUpHandler(e)
{
	var code;
	if (!e) {
		var e = window.event;
	}
	code = ns6 ? e.which : e.keyCode;

	switch (code) {
		case 37 : case 100 :
		case 39 : case 102 :
		case 38 : case 104 :
		case 40 : case 98 : getPreview(); return false; break;
	}
}

function getPreview()
{
	var topx=evalStyle(document.getElementById('selection').style.left);
	var topy=evalStyle(document.getElementById('selection').style.top);
	var bottomx=evalStyle(document.getElementById('selection').style.width);
	var bottomy=evalStyle(document.getElementById('selection').style.height);
	document.getElementById('crop').value = topx + "," + topy + "," + bottomx + "," + bottomy;
	document.getElementById('format').value = <?=$_GET["format"]?>;
	if (document.getElementById('format').value) {
		//		document.getElementById('preview').src = "<?=Pelican::$config["MEDIA_LIB_PATH"]?>/image_format.php?path=<?=rawurlencode($_GET["path"])?>&format=" + document.getElementById('format').value + "&nocache=1&crop=" + document.getElementById('crop').value;
	}
	setCachePosition();
}

function submit()
{
	document.formulaire.preview.value = "0";
	document.formulaire.target = "_self";
	document.formulaire.submit();
}

function submitpreview()
{
	document.formulaire.preview.value = "1";
	document.formulaire.target = "_blank";
	document.formulaire.submit();
}

function changeSlider(aWidth,aHeight)
{
	aWidth = parseInt(aWidth);
	aHeight = parseInt(aHeight);
	if (isNaN(aWidth)) {
		aWidth = <?=$imageSize[0]?>;
	}
	if (isNaN(aHeight)) {
		aHeight = <?=$imageSize[1]?>;
	}
	origMediaFormatWidth = aWidth;
	origMediaFormatHeight = aHeight;
	if (aWidth * <?=$imageSize[1]?> > aHeight * <?=$imageSize[0]?>) {
		horizMax = true;
		mySlider.minVal = (aWidth><?=$imageSize[0]?>)?<?=$imageSize[0]?>:aWidth;
		mySlider.setValue(mySlider.minVal);
		mySlider.maxVal = <?=$imageSize[0]?>;
	} else {
		horizMax = false;
		mySlider.minVal = (aHeight><?=$imageSize[1]?>)?<?=$imageSize[1]?>:aHeight;
		mySlider.setValue(mySlider.minVal);
		mySlider.maxVal = <?=$imageSize[1]?>;
	}
	mySlider.setDisabled(mySlider.minVal == mySlider.maxVal);
	mySlider.drawInto('sliderDiv');
	codeEvents();
	mySlider.setValue(mySlider.maxVal);
	//	document.getElementById('previewDiv').style.display='inline';
	onChangeSlider(mySlider,mySlider.maxVal,0);
}

function onChangeSlider(componentObj, newValue, newPosition)
{
	if (horizMax) {
		newWidth = newValue;
		newHeight = parseInt((origMediaFormatHeight*newValue)/origMediaFormatWidth);
	} else {
		newWidth = parseInt((origMediaFormatWidth*newValue)/origMediaFormatHeight);
		newHeight = newValue;
	}
	if (parseInt(document.getElementById("left").value) + newWidth > <?=$imageSize[0]?>) {
		document.getElementById("left").value = parseInt(<?=$imageSize[0]?> - newWidth)+"px";
		setPosition(document.getElementById("left"));
	}

	if (parseInt(document.getElementById("top").value) + newHeight > <?=$imageSize[1]?>) {
		document.getElementById("top").value = parseInt(<?=$imageSize[1]?> - newHeight)+"px";
		setPosition(document.getElementById("top"));
	}

	document.getElementById("width").value= newWidth+'px';
	document.getElementById("height").value = newHeight+'px';

	setPosition(document.getElementById("width"));
	setPosition(document.getElementById("height"));
}

function Init()
{
	document.getElementById("sliderDiv").innerText = '';
	mySlider.loadSkin('osx-scrollbar-horizontal');
	mySlider.height        = 15;
	mySlider.width         = <?=$imageSize[0]?>-17*2;
	mySlider.minVal        = <?=$imageSize[0]?>;
	mySlider.maxVal        = <?=$imageSize[0]?>;
	mySlider.valueDefault  = <?=$imageSize[0]?>;
	mySlider.styleValueFieldClass = 'sliderInput';
	mySlider.attachOnChange(onChangeSlider);
	mySlider.attachOnArrow(onChangeSlider);
	mySlider.setDisabled(true);
	mySlider.drawInto('sliderDiv');
	codeEvents();
	changeMediaFormat();
}
</script>
