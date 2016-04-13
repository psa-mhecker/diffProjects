/**
*	Online Image Map Editor - main script file
*	@date	2006.10.24. 22:17:46
*	@author	Adam Maschek (maschek@freemail.hu)
*	@copyright
*	@version 1.0
*
*	This is the main script file of the Online Image Map Editor.
*/
var container=document.getElementById('img_area_container');
var pic=document.getElementById('pic');
var pic_container=document.getElementById('pic_container');
var preview=document.getElementById('map_preview');
var area_html=document.getElementById('area_html');
var srcroot='imgmap/';
var is_drawing=0;
var areas=new Array();
var props=new Array();
var memory=new Array();
var currentid=0;
var viewmode=0;
var mapname='';
var d0=new Image();d0.src=srcroot+'0.gif';
var d1=new Image();d1.src=srcroot+'1.gif';
var d2=new Image();d2.src=srcroot+'2.gif';
var d3=new Image();d3.src=srcroot+'3.gif';
var d4=new Image();d4.src=srcroot+'4.gif';
var d5=new Image();d5.src=srcroot+'5.gif';
var d6=new Image();d6.src=srcroot+'6.gif';
var d7=new Image();d7.src=srcroot+'7.gif';
var d8=new Image();d8.src=srcroot+'8.gif';
var d9=new Image();d9.src=srcroot+'9.gif';
document.getElementById('i_add').src=srcroot+'add.gif';
document.getElementById('i_delete').src=srcroot+'delete.gif';
document.getElementById('i_preview').src=srcroot+'zoom.gif';
//document.getElementById('i_html').src=srcroot+'html.gif';
document.getElementById('i_clipboard').src=srcroot+'clipboard.gif';
var xoxu=1;
var xoxv=11;
var xoxz=12;
var xoxy=13;
var xoxw=14;
var xoxx=15;
var xoxA=2;
var xoxB=21;
var xoxF=22;
var xoxE=23;
var xoxC=24;
var xoxD=25;
var xoxr=3;
var xoxs=30;
var xoxt=31;
var xoxi='#996666';
var xoxj='#ff2222';
var xoxo='#996666';
var xoxp='#ff0000';
var xoxk='#669966';
var xoxm='#22ff22';
var xoxn='#ffeeee';
var xoxq='#cfd1d5';
var xoxl='#e7e7e7';
var xoxR='Prêt';
var xoxQ='Mode Prévisu. Testez votre image mappée.';
var xoxG='Mode Edition. Ajouter de nouvelles zones ou modifiez celles qui existent.';
var xoxJ='Ajouter une nouvelle zone';
var xoxL='Supprimer la zone sélectionnée';
var xoxN='Prévisualiser l\'image mappée';
//var xoxM='Get image map HTML';
var xoxK='Copy to clipboard';
var xoxS='Dessin d\'un rectangle. Gardez la touche MAJ. enfoncée pour faire un carré.';
var xoxT='Déplacement d\'un rectangle';
var xoxX='Redimensionnement d\'un rectangle';
var xoxW='Redimensionnement d\'un rectangle';
var xoxU='Redimensionnement d\'un rectangle';
var xoxV='Redimensionnement d\'un rectangle';
var xoxZ='Dessin d\'un cercle';
var xoxY='Dessin d\'un carré. Relachez la touche MAJ. pour dessiner un rectangle.';
var xoxaa='Déplacement d\'un cercle';
var xoxae='Redimensionnement d\'un cercle';
var xoxad='Redimensionnement d\'un cercle';
var xoxab='Redimensionnement d\'un cercle';
var xoxac='Redimensionnement d\'un cercle';
var xoxO='Dessin d\'un polygone. Utilisez la touche MAJ pour terminer le dessin.';
var xoxP='Déplacement d\'un polygone';
var xoxH='Excanvas not loaded properly.';
var xoxI='Invalid coordinates.';
pic.onmousedown=img_mousedown;
pic.onmousemove=img_mousemove;
document.onkeydown=doc_keydown;
document.onkeyup=doc_keyup;
area_html.onblur=area_html_blur;
area_html.onfocus=area_html_focus;
document.getElementById('i_add').alt=xoxJ;
document.getElementById('i_add').title=xoxJ;
document.getElementById('i_delete').alt=xoxL;
document.getElementById('i_delete').title=xoxL;
document.getElementById('i_preview').alt=xoxN;
document.getElementById('i_preview').title=xoxN;
//document.getElementById('i_html').alt=xoxM;
//document.getElementById('i_html').title=xoxM;
document.getElementById('i_clipboard').alt=xoxK;
document.getElementById('i_clipboard').title=xoxK;
function init(imgurl,imgw,imgh){
	if (!xoxa()){
		addNewArea();
	}
	if (imgurl){
		var ts=new Date().getTime();pic.src=imgurl+'?'+ts;
	}
	if (!window.CanvasRenderingContext2D&&typeof G_vmlCanvasManager=="undefined"){
		statusMessage(xoxH);
	}
};
function statusMessage(str){
	document.getElementById('status').innerHTML=str;window.defaultStatus=str;
};
function getMapHTML(){
	var html='';for(var i=0;i<props.length;i++){
		if (props[i]){
			if (props[i].getElementsByTagName('input')[2].value!=''){
				html+='<area shape="'+props[i].getElementsByTagName('select')[0].value+'" alt="'+props[i].getElementsByTagName('input')[4].value+'" coords="'+props[i].getElementsByTagName('input')[2].value+'" href="'+props[i].getElementsByTagName('input')[3].value+'" target="'+props[i].getElementsByTagName('select')[1].value+'" />';
			}
		}
	}var ztid='';if(window.dialogArguments["ztid"]) ztid=window.dialogArguments["ztid"];var mapname='imgmap'+ztid;html='<map id="'+mapname+'" name="'+mapname+'">'+html+'</map>';
	return(html);
};
function setMapHTML(html){
	removeAllAreas();
	var div=document.createElement("div");div.innerHTML=html;mapname=div.getElementsByTagName('map')[0].name;
	var newareas=div.getElementsByTagName('area');for(var i=0;i<newareas.length;i++){
		//la ligne suivante supprime les "about:blank" qui s'inséraient automatiquement dans l'attribut href
		newareas[i].attributes["href"].value=newareas[i].attributes["href"].value.replace(/about:blank/,"");
		id=addNewArea();if (newareas[i].getAttribute('shape')){
			shape=newareas[i].getAttribute('shape').toLowerCase();if (shape=='rect')shape='rectangle';else if (shape=='circ')shape='circle';else if (shape=='poly')shape='polygon';
		} else{
			shape='rectangle';
		}props[id].getElementsByTagName('select')[0].value=shape;if (newareas[i].getAttribute('coords'))props[id].getElementsByTagName('input')[2].value=newareas[i].getAttribute('coords');if (newareas[i].getAttribute('href'))props[id].getElementsByTagName('input')[3].value=newareas[i].getAttribute('href');if (newareas[i].getAttribute('alt'))props[id].getElementsByTagName('input')[4].value=newareas[i].getAttribute('alt');if (newareas[i].getAttribute('target')){
			target=newareas[i].getAttribute('target').toLowerCase();if (target=='')target='_self';
			
		} else{
			target='_self';
		}props[id].getElementsByTagName('select')[1].value=target;initArea(id,shape);xoxf(id);relaxArea(id);
	}
};
function togglePreview(){
	if (viewmode==0){
		for(var i=0;i<areas.length;i++){
			if (areas[i])areas[i].style.display='none';
		}
		var nodes=container.getElementsByTagName("input");for(var i=0;i<nodes.length;i++){
			nodes[i].disabled=true;
		}
		var nodes=container.getElementsByTagName("select");for(var i=0;i<nodes.length;i++){
			nodes[i].disabled=true;
		}
		preview.innerHTML=getMapHTML();pic.setAttribute('useMap','#'+mapname);pic.onmousedown=null;pic.onmousemove=null;pic.style.cursor='auto';viewmode=1;document.getElementById('i_preview').src=srcroot+'edit.gif';statusMessage(xoxQ);
	} else{
		for(var i=0;i<areas.length;i++){
			if (areas[i])areas[i].style.display='';
		}
		var nodes=container.getElementsByTagName("input");for(var i=0;i<nodes.length;i++){
			nodes[i].disabled=false;
		}
		var nodes=container.getElementsByTagName("select");for(var i=0;i<nodes.length;i++){
			nodes[i].disabled=false;
		}
		preview.innerHTML='';pic.onmousedown=img_mousedown;pic.onmousemove=img_mousemove;pic.style.cursor='crosshair';viewmode=0;document.getElementById('i_preview').src=srcroot+'zoom.gif';statusMessage(xoxG);
	}
};
function addNewArea(){
	if (viewmode==1)
	return;
	var lastarea=xoxa();id=areas.length;areas[id]=document.createElement("div");areas[id].className='area';areas[id].id='area'+id;areas[id].aid=id;areas[id].shape='unknown';props[id]=document.createElement("div");container.appendChild(props[id]);props[id].id='img_area_'+id;props[id].aid=id;props[id].className='img_area';props[id].onmouseover=
	function(){
		img_area_mouseover(this);
	};
	props[id].onmouseout=
	function(){
		img_area_mouseout(this);
	};
	props[id].onclick=
	function(){
		img_area_click(this);
	};
	props[id].innerHTML='\
			<input type="text"  name="img_id" class="img_id" value="'+id+'" readonly="1"/>\
			<input type="radio" name="img_active" class="img_active" id="img_active_'+id+'" value="'+id+'" onclick="img_area_click('+id+')">\
			Zone :	<select name="img_shape" class="img_shape">\
				<option value="rectangle" >rectangle</option>\
				<option value="circle"    >cercle</option>\
				<option value="polygon"   >polygone</option>\
				</select>\
			<input type="text" name="img_coords" class="img_coords" value="" style="display:none;">\
			Lien : <input type="text" name="img_href" id="img_href'+id+'" class="img_href" value=""><img src="./editor/images/internal_link.gif" alt="Lien interne" width="23" height="22" border="0" align="middle" style="cursor:pointer" onclick="return popupInternalLink(document.getElementById(\'img_href'+id+'\'))">\
			Info-bulle : <input type="text" name="img_alt" class="img_alt" value="">\
			Cible :	<select name="img_target" class="img_target">\
				<option value="_self"  >fenêtre courante</option>\
				<option value="_blank" >nouvelle fenêtre</option>\
				</select>';props[id].getElementsByTagName('input')[1].onkeydown=img_area_keydown;props[id].getElementsByTagName('input')[2].onblur=img_area_blur;props[id].getElementsByTagName('input')[3].onblur=img_area_blur;props[id].getElementsByTagName('input')[4].onblur=img_area_blur;props[id].getElementsByTagName('select')[1].onblur=img_area_blur;if (lastarea)props[id].getElementsByTagName('select')[0].value=lastarea.shape;img_area_click(id);document.getElementById('img_active_'+id).focus();
	return(id);
};
function initArea(id,shape){
	if (areas[id].parentNode)areas[id].parentNode.removeChild(areas[id]);areas[id]=null;areas[id]=document.createElement("canvas");pic.parentNode.appendChild(areas[id]);if (typeof G_vmlCanvasManager!="undefined"){
		G_vmlCanvasManager.initElement(areas[id]);areas[id]=pic.parentNode.lastChild;
	}areas[id].className='area';areas[id].id='area'+id;areas[id].aid=id;areas[id].shape=shape;areas[id].onmousedown=area_mousedown;areas[id].onmousemove=area_mousemove;memory[id]=new Object();memory[id].downx=0;memory[id].downy=0;memory[id].left=0;memory[id].top=0;memory[id].width=0;memory[id].height=0;memory[id].xpoints=new Array();memory[id].ypoints=new Array();
};
function relaxArea(id){
	areas[id].style.borderWidth='1px';areas[id].style.borderStyle='solid';if (areas[id].shape=='circle'){
		areas[id].style.borderColor=xoxo;
	} else if (areas[id].shape=='rectangle'){
		areas[id].style.borderColor=xoxp;
	} else if (areas[id].shape=='polygon'){
		areas[id].style.borderColor=xoxo;
	}
};
function removeArea(id){
	if (viewmode==1)
	return;
	if (props[id]){
		var pprops=props[id].parentNode;pprops.removeChild(props[id]);
		var lastid=pprops.lastChild.aid;props[id]=null;try{
			img_area_click(lastid);document.getElementById('img_active_'+lastid).focus();
		}
		catch(err){
		}
		try{
			var pareas=areas[id].parentNode;pareas.removeChild(areas[id]);
		}
		catch(err){
		}
		areas[id]=null;area_html.value=getMapHTML();
	}
};
function removeAllAreas(){
	for(var i=0;i<props.length;i++){
		if (props[i]){
			if (props[i].parentNode)props[i].parentNode.removeChild(props[i]);if (areas[i].parentNode)areas[i].parentNode.removeChild(areas[i]);props[i]=null;areas[i]=null;if (props.length>0&&props[i])img_area_click((props.length-1));
		}
	}
};
function xoxe(id){
	if (window.opera)
	return;
	var idstring=String(id);
	var ctx=areas[id].getContext("2d");
	var digit='';
	var digitimg;for(var i=0;i<idstring.length;i++){
		digit=idstring.substring(i,i+1);eval("digitimg = d"+digit+";");ctx.drawImage(digitimg,i*digitimg.width,0);
	}
};
function xoxg(area,color,x,y){
	if (area.shape=='circle'){
		var width=parseInt(area.style.width);
		var radius=Math.floor(width/2)-1;
		var ctx=area.getContext("2d");ctx.clearRect(0,0,width,width);ctx.beginPath();ctx.strokeStyle=color;ctx.arc(radius,radius,radius,0,Math.PI*2,0);ctx.stroke();ctx.closePath();ctx.strokeStyle=xoxn;ctx.strokeRect(radius,radius,1,1);xoxe(area.aid);
	} else if (area.shape=='rectangle'){
		xoxe(area.aid);
	} else if (area.shape=='polygon'){
		var width=parseInt(area.style.width);
		var height=parseInt(area.style.height);
		var left=parseInt(area.style.left);
		var top=parseInt(area.style.top);
		var ctx=area.getContext("2d");ctx.clearRect(0,0,width,height);ctx.beginPath();ctx.strokeStyle=color;ctx.moveTo(area.xpoints[0]-left,area.ypoints[0]-top);for(var i=1;i<area.xpoints.length;i++){
			ctx.lineTo(area.xpoints[i]-left,area.ypoints[i]-top);
		}
		if (is_drawing==xoxr||is_drawing==xoxs){
			ctx.lineTo(x-left-5,y-top-5);
		}
		ctx.lineTo(area.xpoints[0]-left,area.ypoints[0]-top);ctx.stroke();ctx.closePath();xoxe(area.aid);
	}
};
function xoxh(){
	var input=props[currentid].getElementsByTagName('input')[2];
	var left=parseInt(areas[currentid].style.left);
	var top=parseInt(areas[currentid].style.top);
	var height=parseInt(areas[currentid].style.height);
	var width=parseInt(areas[currentid].style.width);if (areas[currentid].shape=='rectangle'){
		input.value=left+','+top+','+(left+width)+','+(top+height);areas[currentid].lastInput=input.value;
	} else if (areas[currentid].shape=='circle'){
		var radius=Math.floor(width/2)-1;input.value=(left+radius)+','+(top+radius)+','+radius;areas[currentid].lastInput=input.value;
	} else if (areas[currentid].shape=='polygon'){
		input.value='';for(var i=0;i<areas[currentid].xpoints.length;i++){
			input.value+=areas[currentid].xpoints[i]+','+areas[currentid].ypoints[i]+',';
		}input.value=input.value.substring(0,input.value.length-1);areas[currentid].lastInput=input.value;
	}
	area_html.value=getMapHTML();
};
function xoxf(id){
	var input=document.getElementById('img_area_'+id).getElementsByTagName('input')[2];
	var coords=input.value;
	var parts=coords.split(',');try{
		if (areas[id].shape=='rectangle'){
			if (parts.length!=4)throw "invalid coords";if (parseInt(parts[0])>parseInt(parts[2]))throw "invalid coords";if (parseInt(parts[1])>parseInt(parts[3]))throw "invalid coords";areas[id].style.left=parts[0]+'px';areas[id].style.top=parts[1]+'px';areas[id].style.width=(parts[2]-parts[0])+'px';areas[id].style.height=(parts[3]-parts[1])+'px';areas[id].setAttribute('width',(parts[2]-parts[0]));areas[id].setAttribute('height',(parts[3]-parts[1]));xoxg(areas[id],xoxp);
		} else if (areas[id].shape=='circle'){
			if (parts.length!=3)throw "invalid coords";if (parseInt(parts[2])<0)throw "invalid coords";
			var width=2*(1*parts[2]+1);areas[id].style.width=width+'px';areas[id].style.height=width+'px';areas[id].setAttribute('width',width);areas[id].setAttribute('height',width);areas[id].style.left=parts[0]-width/2+'px';areas[id].style.top=parts[1]-width/2+'px';xoxg(areas[id],xoxp);
		} else if (areas[id].shape=='polygon'){
			if (parts.length<2)throw "invalid coords";areas[id].xpoints=new Array();areas[id].ypoints=new Array();for(var i=0;i<parts.length;i+=2){
				areas[id].xpoints[areas[id].xpoints.length]=parseInt(parts[i]);areas[id].ypoints[areas[id].ypoints.length]=parseInt(parts[i+1]);xoxc(areas[id],parts[i],parts[i+1]);
			}
			xoxd(areas[id]);
		}
	}catch(err){
		statusMessage(xoxI);if (areas[id].lastInput)input.value=areas[id].lastInput;xoxg(areas[id],xoxp);
		return;
	}
	areas[id].lastInput=input.value;
};
function xoxc(area,newx,newy){
	var xdiff=newx-parseInt(area.style.left);
	var ydiff=newy-parseInt(area.style.top);
	var pad=2;
	var pad2=pad*2;if (newx<parseInt(area.style.left)){
		area.style.left=newx-pad+'px';area.style.width=parseInt(area.style.width)+Math.abs(xdiff)+pad2+'px';area.setAttribute('width',parseInt(area.style.width));
	}
	if (newy<parseInt(area.style.top)){
		area.style.top=newy-pad+'px';area.style.height=parseInt(area.style.height)+Math.abs(ydiff)+pad2+'px';area.setAttribute('height',parseInt(area.style.height));
	}
	if (newx>parseInt(area.style.left)+parseInt(area.style.width)){
		area.style.width=newx-parseInt(area.style.left)+pad2+'px';area.setAttribute('width',parseInt(area.style.width));
	}
	if (newy>parseInt(area.style.top)+parseInt(area.style.height)){
		area.style.height=newy-parseInt(area.style.top)+pad2+'px';area.setAttribute('height',parseInt(area.style.height));
	}
};
function xoxd(area){
	area.style.left=(area.xpoints[0]+1)+'px';area.style.top=(area.ypoints[0]+1)+'px';area.style.height='0px';area.style.width='0px';area.setAttribute('height','0');area.setAttribute('width','0');for(var i=0;i<area.xpoints.length;i++){
		xoxc(area,area.xpoints[i],area.ypoints[i]);
	}
	xoxg(area,xoxp);
};
function img_mousemove(e){
	var pos=xoxb(pic);
	var x=(window.event)?(window.event.x-pic.offsetLeft):(e.pageX-pos[0]);
	var y=(window.event)?(window.event.y-pic.offsetTop):(e.pageY-pos[1]);x=x+pic_container.scrollLeft;y=y+pic_container.scrollTop;if (x<0||y<0||x>pic.width||y>pic.height)
	return;if (memory[currentid]){
		var top=memory[currentid].top;
		var left=memory[currentid].left;
		var height=memory[currentid].height;
		var width=memory[currentid].width;
	}
	if (is_drawing==xoxu){
		var xdiff=x-memory[currentid].downx;
		var ydiff=y-memory[currentid].downy;areas[currentid].style.width=Math.abs(xdiff)+'px';areas[currentid].style.height=Math.abs(ydiff)+'px';areas[currentid].setAttribute('width',Math.abs(xdiff));areas[currentid].setAttribute('height',Math.abs(ydiff));if (xdiff<0){
			areas[currentid].style.left=(x+1)+'px';
		}
		if (ydiff<0){
			areas[currentid].style.top=(y+1)+'px';
		}
	} else if (is_drawing==xoxA){
		var xdiff=x-memory[currentid].downx;
		var ydiff=y-memory[currentid].downy;
		var diff;if (Math.abs(xdiff)<Math.abs(ydiff)){
			diff=Math.abs(xdiff);
		} else{
			diff=Math.abs(ydiff);
		}areas[currentid].style.width=diff+'px';areas[currentid].style.height=diff+'px';areas[currentid].setAttribute('width',diff);areas[currentid].setAttribute('height',diff);if (xdiff<0){
			areas[currentid].style.left=(memory[currentid].downx+diff*-1)+'px';
		}
		if (ydiff<0){
			areas[currentid].style.top=(memory[currentid].downy+diff*-1+1)+'px';
		}
	} else if (is_drawing==xoxr){
		xoxc(areas[currentid],x,y);
	} else if (is_drawing==xoxv||is_drawing==xoxB){
		if (x+width>pic.width||y+height>pic.height)
		return;areas[currentid].style.left=x+1+'px';areas[currentid].style.top=y+1+'px';
	} else if (is_drawing==xoxt){
		if (x+width>pic.width||y+height>pic.height)
		return;
		var xdiff=x-left;
		var ydiff=y-top;for(var i=0;i<areas[currentid].xpoints.length;i++){
			areas[currentid].xpoints[i]=memory[currentid].xpoints[i]+xdiff;areas[currentid].ypoints[i]=memory[currentid].ypoints[i]+ydiff;
		}areas[currentid].style.left=x+1+'px';areas[currentid].style.top=y+1+'px';
	} else if (is_drawing==xoxD){
		var diff=x-left;if ((width+(-1*diff))>0){
			areas[currentid].style.left=x+1+'px';areas[currentid].style.top=(top+(diff/2))+'px';areas[currentid].style.width=(width+(-1*diff))+'px';areas[currentid].style.height=(height+(-1*diff))+'px';areas[currentid].setAttribute('width',parseInt(areas[currentid].style.width));areas[currentid].setAttribute('height',parseInt(areas[currentid].style.height));
		} else{
			memory[currentid].width=0;memory[currentid].height=0;memory[currentid].left=x;memory[currentid].top=y;is_drawing=xoxE;
		}
	} else if (is_drawing==xoxE){
		var diff=x-left-width;if ((width+(diff))-1>0){
			areas[currentid].style.top=(top+(-1*diff/2))+'px';areas[currentid].style.width=(width+(diff))-1+'px';areas[currentid].style.height=(height+(diff))+'px';areas[currentid].setAttribute('width',parseInt(areas[currentid].style.width));areas[currentid].setAttribute('height',parseInt(areas[currentid].style.height));
		} else{
			memory[currentid].width=0;memory[currentid].height=0;memory[currentid].left=x;memory[currentid].top=y;is_drawing=xoxD;
		}
	} else if (is_drawing==xoxF){
		var diff=y-top;if ((width+(-1*diff))>0){
			areas[currentid].style.top=y+1+'px';areas[currentid].style.left=(left+(diff/2))+'px';areas[currentid].style.width=(width+(-1*diff))+'px';areas[currentid].style.height=(height+(-1*diff))+'px';areas[currentid].setAttribute('width',parseInt(areas[currentid].style.width));areas[currentid].setAttribute('height',parseInt(areas[currentid].style.height));
		} else{
			memory[currentid].width=0;memory[currentid].height=0;memory[currentid].left=x;memory[currentid].top=y;is_drawing=xoxC;
		}
	} else if (is_drawing==xoxC){
		var diff=y-top-height;if ((width+(diff))-1>0){
			areas[currentid].style.left=(left+(-1*diff/2))+'px';areas[currentid].style.width=(width+(diff))-1+'px';areas[currentid].style.height=(height+(diff))-1+'px';areas[currentid].setAttribute('width',parseInt(areas[currentid].style.width));areas[currentid].setAttribute('height',parseInt(areas[currentid].style.height));
		} else{
			memory[currentid].width=0;memory[currentid].height=0;memory[currentid].left=x;memory[currentid].top=y;is_drawing=xoxF;
		}
	} else if (is_drawing==xoxx){
		var xdiff=x-left;if (width+(-1*xdiff)>0){
			areas[currentid].style.left=x+1+'px';areas[currentid].style.width=width+(-1*xdiff)+'px';areas[currentid].setAttribute('width',parseInt(areas[currentid].style.width));
		} else{
			memory[currentid].width=0;memory[currentid].left=x;is_drawing=xoxy;
		}
	} else if (is_drawing==xoxy){
		var xdiff=x-left-width;if ((width+(xdiff))-1>0){
			areas[currentid].style.width=(width+(xdiff))-1+'px';areas[currentid].setAttribute('width',parseInt(areas[currentid].style.width));
		} else{
			memory[currentid].width=0;memory[currentid].left=x;is_drawing=xoxx;
		}
	} else if (is_drawing==xoxz){
		var ydiff=y-top;if ((height+(-1*ydiff))>0){
			areas[currentid].style.top=y+1+'px';areas[currentid].style.height=(height+(-1*ydiff))+'px';areas[currentid].setAttribute('height',parseInt(areas[currentid].style.height));
		} else{
			memory[currentid].height=0;memory[currentid].top=y;is_drawing=xoxw;
		}
	} else if (is_drawing==xoxw){
		var ydiff=y-top-height;if ((height+(ydiff))-1>0){
			areas[currentid].style.height=(height+(ydiff))-1+'px';areas[currentid].setAttribute('height',parseInt(areas[currentid].style.height));
		} else{
			memory[currentid].height=0;memory[currentid].top=y;is_drawing=xoxz;
		}
	}
	if (is_drawing){
		xoxg(areas[currentid],xoxj,x,y);xoxh();
	}
};
function img_mousedown(e){
	if (!props[currentid])
	return;
	var pos=xoxb(pic);
	var x=(window.event)?(window.event.x-pic.offsetLeft):(e.pageX-pos[0]);
	var y=(window.event)?(window.event.y-pic.offsetTop):(e.pageY-pos[1]);x=x+pic_container.scrollLeft;y=y+pic_container.scrollTop;if (is_drawing==xoxr){
		areas[currentid].xpoints[areas[currentid].xpoints.length]=x-5;areas[currentid].ypoints[areas[currentid].ypoints.length]=y-5;
	} else if (is_drawing&&is_drawing!=xoxr){
		if (is_drawing==xoxs){
			areas[currentid].xpoints[areas[currentid].xpoints.length]=x-5;areas[currentid].ypoints[areas[currentid].ypoints.length]=y-5;xoxh();is_drawing=0;xoxd(areas[currentid]);
		}is_drawing=0;statusMessage(xoxR);relaxArea(currentid);if (areas[currentid]==xoxa()){
			addNewArea();
			return;
		}
	} else if (props[currentid].getElementsByTagName('select')[0].value=='polygon'){
		if (areas[currentid].shape!=props[currentid].getElementsByTagName('select')[0].value){
			initArea(currentid,'polygon');
		}is_drawing=xoxr;statusMessage(xoxO);areas[currentid].style.left=x+'px';areas[currentid].style.top=y+'px';areas[currentid].style.borderWidth='1px';areas[currentid].style.borderStyle='dotted';areas[currentid].style.borderColor=xoxi;areas[currentid].style.width=0;areas[currentid].style.height=0;areas[currentid].xpoints=new Array();areas[currentid].ypoints=new Array();areas[currentid].xpoints[0]=x;areas[currentid].ypoints[0]=y;
	} else if (props[currentid].getElementsByTagName('select')[0].value=='rectangle'){
		if (areas[currentid].shape!=props[currentid].getElementsByTagName('select')[0].value){
			initArea(currentid,'rectangle');
		}is_drawing=xoxu;statusMessage(xoxS);areas[currentid].style.left=x+'px';areas[currentid].style.top=y+'px';areas[currentid].style.borderWidth='1px';areas[currentid].style.borderStyle='dotted';areas[currentid].style.borderColor=xoxj;areas[currentid].style.width=0;areas[currentid].style.height=0;
	} else if (props[currentid].getElementsByTagName('select')[0].value=='circle'){
		if (areas[currentid].shape!=props[currentid].getElementsByTagName('select')[0].value){
			initArea(currentid,'circle');
		}
		is_drawing=xoxA;statusMessage(xoxZ);areas[currentid].style.left=x+'px';areas[currentid].style.top=y+'px';areas[currentid].style.borderWidth='1px';areas[currentid].style.borderStyle='dotted';areas[currentid].style.borderColor=xoxi;areas[currentid].style.width=0;areas[currentid].style.height=0;
	}
	memory[currentid].downx=x;memory[currentid].downy=y;
};
function img_area_mouseover(obj){
	if (is_drawing)
	return;
	var id=obj.aid;props[id].style.borderWidth='1px';props[id].style.borderStyle='solid';props[id].style.borderColor=xoxk;if (areas[id]){
		areas[id].style.borderWidth='1px';areas[id].style.borderStyle='solid';if (areas[id].shape=='circle'){
			areas[id].style.borderColor=xoxk;
		} else if (areas[id].shape=='polygon'){
			areas[id].style.borderColor=xoxk;
		} else if (areas[id].shape=='rectangle'){
			areas[id].style.borderColor=xoxm;
		}
		xoxg(areas[id],xoxm);
	}
};
function img_area_mouseout(obj){
	if (is_drawing)
	return;
	var id=obj.aid;props[id].style.borderWidth='1px';props[id].style.borderStyle='solid';props[id].style.borderColor=xoxq;if (areas[id]){
		areas[id].style.borderWidth='1px';areas[id].style.borderStyle='solid';if (areas[id].shape=='circle'){
			areas[id].style.borderColor=xoxo;
		} else if (areas[id].shape=='polygon'){
			areas[id].style.borderColor=xoxo;
		} else if (areas[id].shape=='rectangle'){
			areas[id].style.borderColor=xoxp;
		}
		xoxg(areas[id],xoxp);
	}
};
function img_area_click(id){
	if (is_drawing)
	return;if (viewmode==1)
	return;if (typeof(id)=='object')id=id.aid;document.getElementById('img_active_'+id).checked=1;for(var i=0;i<props.length;i++){
		if (props[i]){
			props[i].style.background='';
		}
	}
	props[id].style.background=xoxl;currentid=id;
};
function img_area_keydown(e){
	if (viewmode==1)
	return;
	var key=(window.event)?event.keyCode:e.keyCode;if (key==46){
		if (document.all)obj=window.event.srcElement;else obj=e.currentTarget;removeArea(obj.parentNode.aid);
	}
};
function img_area_blur(){
	var id=this.parentNode.getElementsByTagName('input')[0].value;xoxf(id);area_html.value=getMapHTML();
};
function area_html_blur(){
	var oldvalue=area_html.getAttribute('oldvalue');if (oldvalue!=area_html.value){
		setMapHTML(area_html.value);
	}
};
function area_html_focus(){
	area_html.setAttribute('oldvalue',area_html.value);area_html.select();
};
function area_mousemove(e){
	if (is_drawing==0){
		if (document.all)obj=window.event.srcElement;else obj=e.currentTarget;if (obj.tagName=='image'){
			obj=obj.parentNode.parentNode;
		}var pos=xoxb(pic);
		var x=(window.event)?(window.event.x-pic.offsetLeft):(e.pageX-pos[0]);
		var y=(window.event)?(window.event.y-pic.offsetTop):(e.pageY-pos[1]);x=x+pic_container.scrollLeft;y=y+pic_container.scrollTop;
		var xdiff=Math.abs(x-parseInt(obj.style.left));
		var ydiff=Math.abs(y-parseInt(obj.style.top));if (xdiff<10&&ydiff<10){
			obj.style.cursor='move';
		} else if (xdiff<6&&ydiff>6){
			if (obj.shape!='polygon'){
				obj.style.cursor='w-resize';
			}
		} else if (xdiff>parseInt(obj.style.width)-6&&ydiff>6){
			if (obj.shape!='polygon'){
				obj.style.cursor='e-resize';
			}
		} else if (xdiff>6&&ydiff<6){
			if (obj.shape!='polygon'){
				obj.style.cursor='n-resize';
			}
		} else if (ydiff>parseInt(obj.style.height)-6&&xdiff>6){
			if (obj.shape!='polygon'){
				obj.style.cursor='s-resize';
			}
		} else{
			obj.style.cursor='crosshair';
		}
	} else{
		img_mousemove(e);
	}
};
function area_mousedown(e){
	if (is_drawing==0){
		if (document.all)obj=window.event.srcElement;else obj=e.currentTarget;if (obj.tagName=='image'){
			obj=obj.parentNode.parentNode;
		}
		if (areas[currentid]!=obj){
			img_area_click(obj.aid);document.getElementById('img_active_'+obj.aid).focus();
		}var pos=xoxb(pic);
		var x=(window.event)?(window.event.x-pic.offsetLeft):(e.pageX-pos[0]);
		var y=(window.event)?(window.event.y-pic.offsetTop):(e.pageY-pos[1]);x=x+pic_container.scrollLeft;y=y+pic_container.scrollTop;
		var xdiff=Math.abs(x-parseInt(areas[currentid].style.left));
		var ydiff=Math.abs(y-parseInt(areas[currentid].style.top));if (xdiff<10&&ydiff<10){
			if (areas[currentid].shape=='circle'){
				is_drawing=xoxB;statusMessage(xoxaa);areas[currentid].style.borderColor=xoxi;
			} else if (areas[currentid].shape=='rectangle'){
				is_drawing=xoxv;statusMessage(xoxT);areas[currentid].style.borderColor=xoxj;
			} else if (areas[currentid].shape=='polygon'){
				for(var i=0;i<areas[currentid].xpoints.length;i++){
					memory[currentid].xpoints[i]=areas[currentid].xpoints[i];memory[currentid].ypoints[i]=areas[currentid].ypoints[i];
				}is_drawing=xoxt;statusMessage(xoxP);areas[currentid].style.borderColor=xoxi;
			}
		} else if (xdiff<6&&ydiff>6){
			if (areas[currentid].shape=='circle'){
				is_drawing=xoxD;statusMessage(xoxac);areas[currentid].style.borderColor=xoxi;
			} else if (areas[currentid].shape=='rectangle'){
				is_drawing=xoxx;statusMessage(xoxV);areas[currentid].style.borderColor=xoxj;
			}
		} else if (xdiff>parseInt(areas[currentid].style.width)-6&&ydiff>6){
			if (areas[currentid].shape=='circle'){
				is_drawing=xoxE;statusMessage(xoxad);areas[currentid].style.borderColor=xoxi;
			} else if (areas[currentid].shape=='rectangle'){
				is_drawing=xoxy;statusMessage(xoxW);areas[currentid].style.borderColor=xoxj;
			}
		} else if (xdiff>6&&ydiff<6){
			if (areas[currentid].shape=='circle'){
				is_drawing=xoxF;statusMessage(xoxae);areas[currentid].style.borderColor=xoxi;
			} else if (areas[currentid].shape=='rectangle'){
				is_drawing=xoxz;statusMessage(xoxX);areas[currentid].style.borderColor=xoxj;
			}
		} else if (ydiff>parseInt(areas[currentid].style.height)-6&&xdiff>6){
			if (areas[currentid].shape=='circle'){
				is_drawing=xoxC;statusMessage(xoxab);areas[currentid].style.borderColor=xoxi;
			} else if (areas[currentid].shape=='rectangle'){
				is_drawing=xoxw;statusMessage(xoxU);areas[currentid].style.borderColor=xoxj;
			}
		}memory[currentid].width=parseInt(areas[currentid].style.width);memory[currentid].height=parseInt(areas[currentid].style.height);memory[currentid].top=parseInt(areas[currentid].style.top);memory[currentid].left=parseInt(areas[currentid].style.left);areas[currentid].style.borderWidth='1px';areas[currentid].style.borderStyle='dotted';
	} else{
		img_mousedown(e);
	}
};
function doc_keydown(e){
	var key=(window.event)?event.keyCode:e.keyCode;if (key==16){
		if (is_drawing==xoxr){
			is_drawing=xoxs;
		} else if (is_drawing==xoxu){
			is_drawing=xoxA;statusMessage(xoxY);
		}
	}
};
function doc_keyup(e){
	var key=(window.event)?event.keyCode:e.keyCode;if (key==16){
		if (is_drawing==xoxs){
			is_drawing=xoxr;
		} else if (is_drawing==xoxA&&areas[currentid].shape=='rectangle'){
			is_drawing=xoxu;
			statusMessage(xoxS);
		}
	}
};
function xoxb(element){
	var xpos=0;
	var ypos=0;if (element){
		var elementOffsetParent=element.offsetParent;if (elementOffsetParent){
			while((elementOffsetParent=element.offsetParent)!=null){
				xpos+=element.offsetLeft;
				ypos+=element.offsetTop;
				element=elementOffsetParent;
			}
		} else{
			xpos=element.offsetLeft;ypos=element.offsetTop;
		}
	}
	return new Array(xpos,ypos);
};
function xoxa(){
	for(var i=areas.length-1;i>=0;i--){
		if (areas[i])
		return areas[i];
	}
	return null;
};
function toggleFieldset(fieldset,on){
	if (fieldset){
		if (fieldset.className=='fieldset_off'||on==1){
			fieldset.className='';
		} else{
			fieldset.className='fieldset_off';
		}
	}
};
function toClipBoard(copyText){
	if (window.clipboardData){
		window.clipboardData.setData('Text',copyText);
	} else if (window.netscape){
		netscape.security.PrivilegeManager.enablePrivilege('UniversalXPConnect');
		var str=Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString);if (!str)
		return false;str.data=copyText;
		var trans=Components.classes["@mozilla.org/widget/transferable;1"].createInstance(Components.interfaces.nsITransferable);if (!trans)
		return false;trans.addDataFlavor("text/unicode");trans.setTransferData("text/unicode",str,copyText.length*2);
		var clipid=Components.interfaces.nsIClipboard;
		var clip=Components.classes["@mozilla.org/widget/clipboard;1"].getService(clipid);if (!clip)
		return false;clip.setData(trans,null,clipid.kGlobalClipboard);
	}
}
