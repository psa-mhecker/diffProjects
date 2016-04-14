var strMaxCar="il faut 6 caractères";
var strBadCode="Mauvais code de couleur";

hexa=new Array;
for(var i=0; i < 10; i++)
hexa[i]=i;
hexa[10]="a"; hexa[11]="b"; hexa[12]="c";
hexa[13]="d"; hexa[14]="e"; hexa[15]="f";
var tabCoul = new Array;
i = 0;
tabCoul[i] = "FFFFFF";i++;
tabCoul[i] = "CCCCFF";i++;
tabCoul[i] = "FF9999";i++;
tabCoul[i] = "6666CC";i++;
tabCoul[i] = "336633";i++;
tabCoul[i] = "006666";i++;
tabCoul[i] = "FFFFCC";i++;
var nbcoulTab = i;

function iToHex(i)
{
	if (i < 0)
	return "00"
	else
	if (i > 255)
	return "ff"
	else
	return "" + hexa[Math.floor(i/16)] + hexa[i%16]
}

function hexToI(h)
{
	if ((h.charCodeAt(0) >= "0".charCodeAt(0))&&(h.charCodeAt(0) <= "9".charCodeAt(0)))
	return (h.charCodeAt(0)-"0".charCodeAt(0));
	else
	if ((h.charCodeAt(0) >= "a".charCodeAt(0))&&(h.charCodeAt(0) <= "f".charCodeAt(0)))
	return (10+h.charCodeAt(0)-"a".charCodeAt(0));
	else
	if ((h.charCodeAt(0) >= "A".charCodeAt(0))&&(h.charCodeAt(0) <= "F".charCodeAt(0)))
	return (10+h.charCodeAt(0)-"A".charCodeAt(0));
	return 0 ;
}

function rgvToHexa(rgb)
{
	var hexa;
	//alert(rgb.r+" "+rgb.g+" "+rgb.b);
	hexa = ""+iToHex(rgb.r)+iToHex(rgb.g)+iToHex(rgb.b);
	return hexa;
}

function hexaToRgb(hexa)
{
	var rgb = new Object();
	var r1,r2,v1,v2,b1,b2;
	var i = 0;
	r1 = hexa.substr(i,1);i++;
	r2 = hexa.substr(i,1);i++;
	v1 = hexa.substr(i,1);i++;
	v2 = hexa.substr(i,1);i++;
	b1 = hexa.substr(i,1);i++;
	b2 = hexa.substr(i,1);i++;
	var r,v,b;
	r = hexToI(r1)*16+hexToI(r2);
	v = hexToI(v1)*16+hexToI(v2);
	b = hexToI(b1)*16+hexToI(b2);
	rgb.r = r;
	rgb.g = v;
	rgb.b = b;
	return rgb;
}

function hsvToRgb(hsv) {
	var rgb = new Object();
	var i, f, p, q, t;
	
	
	if (hsv.s == 0) {
		// achromatic (grey)
		rgb.r = rgb.g = rgb.b = hsv.v;
		return rgb;
	}
	hsv.h /= 60;			// sector 0 to 5
	i = Math.floor( hsv.h );
	f = hsv.h - i;			// factorial part of h
	p = hsv.v * ( 1 - hsv.s );
	q = hsv.v * ( 1 - hsv.s * f );
	t = hsv.v * ( 1 - hsv.s * ( 1 - f ) );
	switch( i ) {
		case 0:
		rgb.r = hsv.v;
		rgb.g = t;
		rgb.b = p;
		break;
		case 1:
		rgb.r = q;
		rgb.g = hsv.v;
		rgb.b = p;
		break;
		case 2:
		rgb.r = p;
		rgb.g = hsv.v;
		rgb.b = t;
		break;
		case 3:
		rgb.r = p;
		rgb.g = q;
		rgb.b = hsv.v;
		break;
		case 4:
		rgb.r = t;
		rgb.g = p;
		rgb.b = hsv.v;
		break;
		default:		// case 5:
		rgb.r = hsv.v;
		rgb.g = p;
		rgb.b = q;
		break;
	}
	
	return rgb;
}

/*
var t = new Object();
t.h = 180;
t.s = 0.5;
t.v = 0.5;

alert(hsvToRgb(t).r)*/

function calculateRGB() {
	if (window.event.button == 1 && dragobject == null) {
		var hsv = new Object();
		var h = window.event.srcElement.offsetHeight;
		var y = window.event.offsetY;
		
		if (event.srcElement != colorImage)
		return;
		
		hsv.h = 360 * window.event.offsetX / window.event.srcElement.offsetWidth;
		
		if (y > h/2) {
			hsv.s = 1.0;
			hsv.v = 2 * (h - y) / h;
		}
		else {
			hsv.v = 1.0;
			hsv.s = y / (h/2);
		}
		
		var rgb = hsvToRgb(hsv);
		
		index_setColor(rgb.r, rgb.g, rgb.b);
		
	}
}

function update(el) {
	var red   = Math.round(255*redSlider.value);
	var green = Math.round(255*greenSlider.value);
	var blue  = Math.round(255*blueSlider.value);
	
	var color = "RGB(" + red + "," + green + "," + blue + ")";
	
	colorBox.style.backgroundColor = color;
	
	redLeft.style.background = "RGB(" + 0 + "," + green + "," + blue + ")";
	redRight.style.background = "RGB(" + 255 + "," + green + "," + blue + ")";
	greenLeft.style.background = "RGB(" + red + "," + 0 + "," + blue + ")";
	greenRight.style.background = "RGB(" + red + "," + 255 + "," + blue + ")";
	blueLeft.style.background = "RGB(" + red + "," + green + "," + 0 + ")";
	blueRight.style.background = "RGB(" + red + "," + green + "," + 255 + ")";
	
	redInput.value = Math.round(red);
	greenInput.value = Math.round(green);
	blueInput.value = Math.round(blue);
	
	raiseIt();
}

function updateInput(slider) {
	var v = parseInt(window.event.srcElement.value);
	
	if (!isNaN(v)) {
		setValue(slider, Math.min(255, v)/255);
		raiseIt();
	}
}

function clickOnGrad(sliderEl) {
	setValue(sliderEl, Math.min(Math.abs((event.offsetX+1)/event.srcElement.offsetWidth), 1.0));
}

function init() {
	index_setColor(0.5, 0.5, 0.5);
}

function changeColor(hex)
{
	var rgb = hexaToRgb(hex);
	index_setColor(rgb.r/255, rgb.g/255, rgb.b/255);
}

function index_setColor(r, g, b) {
	setValue(redSlider, Math.min(1.0, r));
	setValue(greenSlider, Math.min(1.0, g));
	setValue(blueSlider, Math.min(1.0, b));
	raiseIt();
}

function index_getColor() {
	var o = new Object();
	o.r = redSlider.value;
	o.g = greenSlider.value;
	o.b = blueSlider.value;
	
	return o;
}

function raiseIt() {
	var o = new Object();
	o.r = redInput.value;
	o.g = greenInput.value;
	o.b = blueInput.value;
	var hexa = rgvToHexa(o);
	hexaInput.value= hexa;
}

function VerifCouleur(coul)
{
	if (coul.length != "6")
	{alert(strMaxCar);return;}
	
	var chaine, nb;
	coul = coul.toUpperCase()
	chaine = coul;
	nb = chaine.length;
	code1 = "A".charCodeAt(0);
	code2 = "F".charCodeAt(0);
	code3 = "0".charCodeAt(0);
	code4 = "9".charCodeAt(0);
	test = true;
	for(a = 0 ; a < nb ; a++)
	{
		code = chaine.charCodeAt(a);
		if (!(((code >= code1) && (code <= code2)) || ((code >= code3) && (code <= code4))))
		test = false;
	}
	if (test == false)
	{
		alert(strBadCode);
		return;
	}
	else
	changeColor(coul);
}
