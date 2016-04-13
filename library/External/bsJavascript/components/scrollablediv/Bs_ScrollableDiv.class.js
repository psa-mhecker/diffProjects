/********************************************************************************************
* BlueShoes Framework; This file is part of the php application framework.
* NOTE: This code is stripped (obfuscated). To get the clean documented code goto 
*       www.blueshoes.org and register for the free open source *DEVELOPER* version or 
*       buy the commercial version.
*       
*       In case you've already got the developer version, then this is one of the few 
*       packages/classes that is only available to *PAYING* customers.
*       To get it go to www.blueshoes.org and buy a commercial version.
* 
* @copyright www.blueshoes.org
* @author    Samuel Blume <sam at blueshoes dot org>
* @author    Andrej Arn <andrej at blueshoes dot org>
*/
function Bs_ScrollableDiv() {
this.sliderObj;this.bwCheckObj;this.lastScrollTo = 0;this.containerObj;this.contentObj;this.containerElm;this.contentElm;this.init = function(containerDivId, contentDivId) {
this.bwCheckObj = lib_bwcheck();this.containerObj = new makeObj(this.bwCheckObj, containerDivId);this.contentObj   = new makeObj(this.bwCheckObj, contentDivId, containerDivId);this.contentObj.moveIt(0, 0);this.containerObj.css.visibility = "visible";this.containerElm = document.getElementById(containerDivId);this.contentElm   = document.getElementById(contentDivId);}
this.setSliderObject = function(sliderObj, sliderDivId) {
this.sliderObj = sliderObj;if (this.sliderObj.height == 'auto') {
this.sliderObj.height = this.containerElm.offsetHeight - (this.sliderObj._arrowIconLeftHeight + this.sliderObj._arrowIconRightHeight);}
this.sliderObj.Bs_ScrollableDiv = this;this.sliderObj.drawInto(sliderDivId);}
this.bsSliderChange = function(val, newPos) {
var percent = val * 100 / (this.sliderObj.maxVal - this.sliderObj.minVal);var scrollableSpace = this.contentElm.offsetHeight - this.containerElm.offsetHeight;var scrollTo = parseInt(scrollableSpace * percent  / 100);if (scrollTo != this.lastScrollTo) {
this.contentObj.moveIt(0, -scrollTo);this.lastScrollTo = scrollTo;}
}
}
function lib_bwcheck() {
this.ver=navigator.appVersion;this.agent=navigator.userAgent;this.dom=(document.getElementById) ? 1 : 0;this.opera5=(navigator.userAgent.indexOf("Opera")>-1 && document.getElementById)?1:0;this.ie5=(this.ver.indexOf("MSIE 5")>-1 && this.dom && !this.opera5)?1:0;this.ie6=(this.ver.indexOf("MSIE 6")>-1 && this.dom && !this.opera5)?1:0;this.ie4=(document.all && !this.dom && !this.opera5)?1:0;this.ie=this.ie4||this.ie5||this.ie6;this.mac=this.agent.indexOf("Mac")>-1;this.ns6=(this.dom && parseInt(this.ver) >= 5) ?1:0;this.ns4=(document.layers && !this.dom)?1:0;this.bw=(this.ie6 || this.ie5 || this.ie4 || this.ns4 || this.ns6 || this.opera5);bs_px_char = (this.ns4 || window.opera) ? "" : "px";return this;}
function Bs_ScrollableDiv_moveIt(x,y) {
this.x = x;this.y = y;this.css.left = this.x + bs_px_char;this.css.top  = this.y + bs_px_char;}
function Bs_ScrollableDiv_sliderChange(sliderObj, val, newPos){
sliderObj.Bs_ScrollableDiv.bsSliderChange(val, newPos);}
function makeObj(bwCheckObj, obj, nest){
nest=(!nest) ? "":'document.'+nest+'.';this.el=bwCheckObj.dom?document.getElementById(obj):bwCheckObj.ie4?document.all[obj]:bwCheckObj.ns4?eval(nest+'document.'+obj):0;this.css= (bwCheckObj.dom) ? document.getElementById(obj).style : (bwCheckObj.ie4) ? document.all[obj].style : (bwCheckObj.ns4) ? eval(nest+'document.'+obj) : 0;this.scrollHeight=bwCheckObj.ns4?this.css.document.height:this.el.offsetHeight;this.clipHeight=bwCheckObj.ns4?this.css.clip.height:this.el.offsetHeight;this.moveIt=Bs_ScrollableDiv_moveIt;this.x=0;this.y=0;this.obj = obj + "Object";eval(this.obj + "=this");return this;}
