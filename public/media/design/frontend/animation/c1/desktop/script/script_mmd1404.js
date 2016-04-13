var position_slider_mmd1404 = 1;
var position_tablet_mmd1404 = 1;
var position_generale_mmd1404 = 1;
var timeouts_mmd1404 = [];
var first_timeout = null;
var mili_mmd1404 = 9;
var centi_mmd1404 = 9;
var num_mmd1404 = 9;
var interval1_mmd1404, interval2_mmd1404, interval3_mmd1404, intervalsky_mmd1404;
var timer1_mmd1404,timer2_mmd1404, timer3_mmd1404;
var timer_auto_mmd1404, interval_anim1_mmd1404, interval_anim4_mmd1404, interval_anim2_mmd1404, interval_anim3_mmd1404;
var auto1_mmd1404 = 1;
var auto2_mmd1404 = 2;
var current360_mmd1404 = 30;
var interval360_mmd1404, interval_wheel_mmd1404, interval_city_mmd1404, interval_cligne_mmd1404 = null;
var auto_mmd1404=0;
var selected_mmd1404=0;
var video_mmd1404 = document.getElementById("video_mmd1404");
var degree_mmd1404 = 0;

var dataLayer = window['dataLayer'] || [];

window.mainTimer = null;
window.isInAutomaticAnimation = false;

(function(e){"function"==typeof define&&define.amd?define(["jquery"],e):e(jQuery)})(function(e){var t="ui-effects-",i=e;e.effects={effect:{}},function(e,t){function i(e,t,i){var s=d[t.type]||{};return null==e?i||!t.def?null:t.def:(e=s.floor?~~e:parseFloat(e),isNaN(e)?t.def:s.mod?(e+s.mod)%s.mod:0>e?0:e>s.max?s.max:e)}function s(i){var s=l(),n=s._rgba=[];return i=i.toLowerCase(),f(h,function(e,a){var o,r=a.re.exec(i),h=r&&a.parse(r),l=a.space||"rgba";return h?(o=s[l](h),s[u[l].cache]=o[u[l].cache],n=s._rgba=o._rgba,!1):t}),n.length?("0,0,0,0"===n.join()&&e.extend(n,a.transparent),s):a[i]}function n(e,t,i){return i=(i+1)%1,1>6*i?e+6*(t-e)*i:1>2*i?t:2>3*i?e+6*(t-e)*(2/3-i):e}var a,o="backgroundColor borderBottomColor borderLeftColor borderRightColor borderTopColor color columnRuleColor outlineColor textDecorationColor textEmphasisColor",r=/^([\-+])=\s*(\d+\.?\d*)/,h=[{re:/rgba?\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*(?:,\s*(\d?(?:\.\d+)?)\s*)?\)/,parse:function(e){return[e[1],e[2],e[3],e[4]]}},{re:/rgba?\(\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*(?:,\s*(\d?(?:\.\d+)?)\s*)?\)/,parse:function(e){return[2.55*e[1],2.55*e[2],2.55*e[3],e[4]]}},{re:/#([a-f0-9]{2})([a-f0-9]{2})([a-f0-9]{2})/,parse:function(e){return[parseInt(e[1],16),parseInt(e[2],16),parseInt(e[3],16)]}},{re:/#([a-f0-9])([a-f0-9])([a-f0-9])/,parse:function(e){return[parseInt(e[1]+e[1],16),parseInt(e[2]+e[2],16),parseInt(e[3]+e[3],16)]}},{re:/hsla?\(\s*(\d+(?:\.\d+)?)\s*,\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*(?:,\s*(\d?(?:\.\d+)?)\s*)?\)/,space:"hsla",parse:function(e){return[e[1],e[2]/100,e[3]/100,e[4]]}}],l=e.Color=function(t,i,s,n){return new e.Color.fn.parse(t,i,s,n)},u={rgba:{props:{red:{idx:0,type:"byte"},green:{idx:1,type:"byte"},blue:{idx:2,type:"byte"}}},hsla:{props:{hue:{idx:0,type:"degrees"},saturation:{idx:1,type:"percent"},lightness:{idx:2,type:"percent"}}}},d={"byte":{floor:!0,max:255},percent:{max:1},degrees:{mod:360,floor:!0}},c=l.support={},p=e("<p>")[0],f=e.each;p.style.cssText="background-color:rgba(1,1,1,.5)",c.rgba=p.style.backgroundColor.indexOf("rgba")>-1,f(u,function(e,t){t.cache="_"+e,t.props.alpha={idx:3,type:"percent",def:1}}),l.fn=e.extend(l.prototype,{parse:function(n,o,r,h){if(n===t)return this._rgba=[null,null,null,null],this;(n.jquery||n.nodeType)&&(n=e(n).css(o),o=t);var d=this,c=e.type(n),p=this._rgba=[];return o!==t&&(n=[n,o,r,h],c="array"),"string"===c?this.parse(s(n)||a._default):"array"===c?(f(u.rgba.props,function(e,t){p[t.idx]=i(n[t.idx],t)}),this):"object"===c?(n instanceof l?f(u,function(e,t){n[t.cache]&&(d[t.cache]=n[t.cache].slice())}):f(u,function(t,s){var a=s.cache;f(s.props,function(e,t){if(!d[a]&&s.to){if("alpha"===e||null==n[e])return;d[a]=s.to(d._rgba)}d[a][t.idx]=i(n[e],t,!0)}),d[a]&&0>e.inArray(null,d[a].slice(0,3))&&(d[a][3]=1,s.from&&(d._rgba=s.from(d[a])))}),this):t},is:function(e){var i=l(e),s=!0,n=this;return f(u,function(e,a){var o,r=i[a.cache];return r&&(o=n[a.cache]||a.to&&a.to(n._rgba)||[],f(a.props,function(e,i){return null!=r[i.idx]?s=r[i.idx]===o[i.idx]:t})),s}),s},_space:function(){var e=[],t=this;return f(u,function(i,s){t[s.cache]&&e.push(i)}),e.pop()},transition:function(e,t){var s=l(e),n=s._space(),a=u[n],o=0===this.alpha()?l("transparent"):this,r=o[a.cache]||a.to(o._rgba),h=r.slice();return s=s[a.cache],f(a.props,function(e,n){var a=n.idx,o=r[a],l=s[a],u=d[n.type]||{};null!==l&&(null===o?h[a]=l:(u.mod&&(l-o>u.mod/2?o+=u.mod:o-l>u.mod/2&&(o-=u.mod)),h[a]=i((l-o)*t+o,n)))}),this[n](h)},blend:function(t){if(1===this._rgba[3])return this;var i=this._rgba.slice(),s=i.pop(),n=l(t)._rgba;return l(e.map(i,function(e,t){return(1-s)*n[t]+s*e}))},toRgbaString:function(){var t="rgba(",i=e.map(this._rgba,function(e,t){return null==e?t>2?1:0:e});return 1===i[3]&&(i.pop(),t="rgb("),t+i.join()+")"},toHslaString:function(){var t="hsla(",i=e.map(this.hsla(),function(e,t){return null==e&&(e=t>2?1:0),t&&3>t&&(e=Math.round(100*e)+"%"),e});return 1===i[3]&&(i.pop(),t="hsl("),t+i.join()+")"},toHexString:function(t){var i=this._rgba.slice(),s=i.pop();return t&&i.push(~~(255*s)),"#"+e.map(i,function(e){return e=(e||0).toString(16),1===e.length?"0"+e:e}).join("")},toString:function(){return 0===this._rgba[3]?"transparent":this.toRgbaString()}}),l.fn.parse.prototype=l.fn,u.hsla.to=function(e){if(null==e[0]||null==e[1]||null==e[2])return[null,null,null,e[3]];var t,i,s=e[0]/255,n=e[1]/255,a=e[2]/255,o=e[3],r=Math.max(s,n,a),h=Math.min(s,n,a),l=r-h,u=r+h,d=.5*u;return t=h===r?0:s===r?60*(n-a)/l+360:n===r?60*(a-s)/l+120:60*(s-n)/l+240,i=0===l?0:.5>=d?l/u:l/(2-u),[Math.round(t)%360,i,d,null==o?1:o]},u.hsla.from=function(e){if(null==e[0]||null==e[1]||null==e[2])return[null,null,null,e[3]];var t=e[0]/360,i=e[1],s=e[2],a=e[3],o=.5>=s?s*(1+i):s+i-s*i,r=2*s-o;return[Math.round(255*n(r,o,t+1/3)),Math.round(255*n(r,o,t)),Math.round(255*n(r,o,t-1/3)),a]},f(u,function(s,n){var a=n.props,o=n.cache,h=n.to,u=n.from;l.fn[s]=function(s){if(h&&!this[o]&&(this[o]=h(this._rgba)),s===t)return this[o].slice();var n,r=e.type(s),d="array"===r||"object"===r?s:arguments,c=this[o].slice();return f(a,function(e,t){var s=d["object"===r?e:t.idx];null==s&&(s=c[t.idx]),c[t.idx]=i(s,t)}),u?(n=l(u(c)),n[o]=c,n):l(c)},f(a,function(t,i){l.fn[t]||(l.fn[t]=function(n){var a,o=e.type(n),h="alpha"===t?this._hsla?"hsla":"rgba":s,l=this[h](),u=l[i.idx];return"undefined"===o?u:("function"===o&&(n=n.call(this,u),o=e.type(n)),null==n&&i.empty?this:("string"===o&&(a=r.exec(n),a&&(n=u+parseFloat(a[2])*("+"===a[1]?1:-1))),l[i.idx]=n,this[h](l)))})})}),l.hook=function(t){var i=t.split(" ");f(i,function(t,i){e.cssHooks[i]={set:function(t,n){var a,o,r="";if("transparent"!==n&&("string"!==e.type(n)||(a=s(n)))){if(n=l(a||n),!c.rgba&&1!==n._rgba[3]){for(o="backgroundColor"===i?t.parentNode:t;(""===r||"transparent"===r)&&o&&o.style;)try{r=e.css(o,"backgroundColor"),o=o.parentNode}catch(h){}n=n.blend(r&&"transparent"!==r?r:"_default")}n=n.toRgbaString()}try{t.style[i]=n}catch(h){}}},e.fx.step[i]=function(t){t.colorInit||(t.start=l(t.elem,i),t.end=l(t.end),t.colorInit=!0),e.cssHooks[i].set(t.elem,t.start.transition(t.end,t.pos))}})},l.hook(o),e.cssHooks.borderColor={expand:function(e){var t={};return f(["Top","Right","Bottom","Left"],function(i,s){t["border"+s+"Color"]=e}),t}},a=e.Color.names={aqua:"#00ffff",black:"#000000",blue:"#0000ff",fuchsia:"#ff00ff",gray:"#808080",green:"#008000",lime:"#00ff00",maroon:"#800000",navy:"#000080",olive:"#808000",purple:"#800080",red:"#ff0000",silver:"#c0c0c0",teal:"#008080",white:"#ffffff",yellow:"#ffff00",transparent:[null,null,null,0],_default:"#ffffff"}}(i),function(){function t(t){var i,s,n=t.ownerDocument.defaultView?t.ownerDocument.defaultView.getComputedStyle(t,null):t.currentStyle,a={};if(n&&n.length&&n[0]&&n[n[0]])for(s=n.length;s--;)i=n[s],"string"==typeof n[i]&&(a[e.camelCase(i)]=n[i]);else for(i in n)"string"==typeof n[i]&&(a[i]=n[i]);return a}function s(t,i){var s,n,o={};for(s in i)n=i[s],t[s]!==n&&(a[s]||(e.fx.step[s]||!isNaN(parseFloat(n)))&&(o[s]=n));return o}var n=["add","remove","toggle"],a={border:1,borderBottom:1,borderColor:1,borderLeft:1,borderRight:1,borderTop:1,borderWidth:1,margin:1,padding:1};e.each(["borderLeftStyle","borderRightStyle","borderBottomStyle","borderTopStyle"],function(t,s){e.fx.step[s]=function(e){("none"!==e.end&&!e.setAttr||1===e.pos&&!e.setAttr)&&(i.style(e.elem,s,e.end),e.setAttr=!0)}}),e.fn.addBack||(e.fn.addBack=function(e){return this.add(null==e?this.prevObject:this.prevObject.filter(e))}),e.effects.animateClass=function(i,a,o,r){var h=e.speed(a,o,r);return this.queue(function(){var a,o=e(this),r=o.attr("class")||"",l=h.children?o.find("*").addBack():o;l=l.map(function(){var i=e(this);return{el:i,start:t(this)}}),a=function(){e.each(n,function(e,t){i[t]&&o[t+"Class"](i[t])})},a(),l=l.map(function(){return this.end=t(this.el[0]),this.diff=s(this.start,this.end),this}),o.attr("class",r),l=l.map(function(){var t=this,i=e.Deferred(),s=e.extend({},h,{queue:!1,complete:function(){i.resolve(t)}});return this.el.animate(this.diff,s),i.promise()}),e.when.apply(e,l.get()).done(function(){a(),e.each(arguments,function(){var t=this.el;e.each(this.diff,function(e){t.css(e,"")})}),h.complete.call(o[0])})})},e.fn.extend({addClass:function(t){return function(i,s,n,a){return s?e.effects.animateClass.call(this,{add:i},s,n,a):t.apply(this,arguments)}}(e.fn.addClass),removeClass:function(t){return function(i,s,n,a){return arguments.length>1?e.effects.animateClass.call(this,{remove:i},s,n,a):t.apply(this,arguments)}}(e.fn.removeClass),toggleClass:function(t){return function(i,s,n,a,o){return"boolean"==typeof s||void 0===s?n?e.effects.animateClass.call(this,s?{add:i}:{remove:i},n,a,o):t.apply(this,arguments):e.effects.animateClass.call(this,{toggle:i},s,n,a)}}(e.fn.toggleClass),switchClass:function(t,i,s,n,a){return e.effects.animateClass.call(this,{add:i,remove:t},s,n,a)}})}(),function(){function i(t,i,s,n){return e.isPlainObject(t)&&(i=t,t=t.effect),t={effect:t},null==i&&(i={}),e.isFunction(i)&&(n=i,s=null,i={}),("number"==typeof i||e.fx.speeds[i])&&(n=s,s=i,i={}),e.isFunction(s)&&(n=s,s=null),i&&e.extend(t,i),s=s||i.duration,t.duration=e.fx.off?0:"number"==typeof s?s:s in e.fx.speeds?e.fx.speeds[s]:e.fx.speeds._default,t.complete=n||i.complete,t}function s(t){return!t||"number"==typeof t||e.fx.speeds[t]?!0:"string"!=typeof t||e.effects.effect[t]?e.isFunction(t)?!0:"object"!=typeof t||t.effect?!1:!0:!0}e.extend(e.effects,{version:"1.11.4",save:function(e,i){for(var s=0;i.length>s;s++)null!==i[s]&&e.data(t+i[s],e[0].style[i[s]])},restore:function(e,i){var s,n;for(n=0;i.length>n;n++)null!==i[n]&&(s=e.data(t+i[n]),void 0===s&&(s=""),e.css(i[n],s))},setMode:function(e,t){return"toggle"===t&&(t=e.is(":hidden")?"show":"hide"),t},getBaseline:function(e,t){var i,s;switch(e[0]){case"top":i=0;break;case"middle":i=.5;break;case"bottom":i=1;break;default:i=e[0]/t.height}switch(e[1]){case"left":s=0;break;case"center":s=.5;break;case"right":s=1;break;default:s=e[1]/t.width}return{x:s,y:i}},createWrapper:function(t){if(t.parent().is(".ui-effects-wrapper"))return t.parent();var i={width:t.outerWidth(!0),height:t.outerHeight(!0),"float":t.css("float")},s=e("<div></div>").addClass("ui-effects-wrapper").css({fontSize:"100%",background:"transparent",border:"none",margin:0,padding:0}),n={width:t.width(),height:t.height()},a=document.activeElement;try{a.id}catch(o){a=document.body}return t.wrap(s),(t[0]===a||e.contains(t[0],a))&&e(a).focus(),s=t.parent(),"static"===t.css("position")?(s.css({position:"relative"}),t.css({position:"relative"})):(e.extend(i,{position:t.css("position"),zIndex:t.css("z-index")}),e.each(["top","left","bottom","right"],function(e,s){i[s]=t.css(s),isNaN(parseInt(i[s],10))&&(i[s]="auto")}),t.css({position:"relative",top:0,left:0,right:"auto",bottom:"auto"})),t.css(n),s.css(i).show()},removeWrapper:function(t){var i=document.activeElement;return t.parent().is(".ui-effects-wrapper")&&(t.parent().replaceWith(t),(t[0]===i||e.contains(t[0],i))&&e(i).focus()),t},setTransition:function(t,i,s,n){return n=n||{},e.each(i,function(e,i){var a=t.cssUnit(i);a[0]>0&&(n[i]=a[0]*s+a[1])}),n}}),e.fn.extend({effect:function(){function t(t){function i(){e.isFunction(a)&&a.call(n[0]),e.isFunction(t)&&t()}var n=e(this),a=s.complete,r=s.mode;(n.is(":hidden")?"hide"===r:"show"===r)?(n[r](),i()):o.call(n[0],s,i)}var s=i.apply(this,arguments),n=s.mode,a=s.queue,o=e.effects.effect[s.effect];return e.fx.off||!o?n?this[n](s.duration,s.complete):this.each(function(){s.complete&&s.complete.call(this)}):a===!1?this.each(t):this.queue(a||"fx",t)},show:function(e){return function(t){if(s(t))return e.apply(this,arguments);var n=i.apply(this,arguments);return n.mode="show",this.effect.call(this,n)}}(e.fn.show),hide:function(e){return function(t){if(s(t))return e.apply(this,arguments);var n=i.apply(this,arguments);return n.mode="hide",this.effect.call(this,n)}}(e.fn.hide),toggle:function(e){return function(t){if(s(t)||"boolean"==typeof t)return e.apply(this,arguments);var n=i.apply(this,arguments);return n.mode="toggle",this.effect.call(this,n)}}(e.fn.toggle),cssUnit:function(t){var i=this.css(t),s=[];return e.each(["em","px","%","pt"],function(e,t){i.indexOf(t)>0&&(s=[parseFloat(i),t])}),s}})}(),function(){var t={};e.each(["Quad","Cubic","Quart","Quint","Expo"],function(e,i){t[i]=function(t){return Math.pow(t,e+2)}}),e.extend(t,{Sine:function(e){return 1-Math.cos(e*Math.PI/2)},Circ:function(e){return 1-Math.sqrt(1-e*e)},Elastic:function(e){return 0===e||1===e?e:-Math.pow(2,8*(e-1))*Math.sin((80*(e-1)-7.5)*Math.PI/15)},Back:function(e){return e*e*(3*e-2)},Bounce:function(e){for(var t,i=4;((t=Math.pow(2,--i))-1)/11>e;);return 1/Math.pow(4,3-i)-7.5625*Math.pow((3*t-2)/22-e,2)}}),e.each(t,function(t,i){e.easing["easeIn"+t]=i,e.easing["easeOut"+t]=function(e){return 1-i(1-e)},e.easing["easeInOut"+t]=function(e){return.5>e?i(2*e)/2:1-i(-2*e+2)/2}})}(),e.effects});
$(document).ready(function(){

	if( (/Android|iPad/i).test(window.navigator.userAgent)){
		$('#content_mmd1404').css({
			'marginLeft':'-40px'
		});
	}

	anim_intro_mmd1404();
});

(function(){

	$('.editable-wording').each(function(index, value){
		$(this).html($(this).data('keyWording'));
	});
}).call(this);

function autoplayTablerSlide(element, isClicked){

	var current_id_mmd1404 = element.attr("id");
	var id_mmd1404 = parseInt(current_id_mmd1404.substring(9, 10));

	if(selected_mmd1404 != id_mmd1404){

		$(".nav_tablet_mmd1404").removeClass("current_nav_tablet_mmd1404");
		$("#nav_tablet"+id_mmd1404+"_mmd1404").addClass("current_nav_tablet_mmd1404");
		$(".tiret_part3_mmd1404").css({opacity: 0});
		$(".legende_part3_mmd1404").css({opacity: 0});
		$("#nav_tablet"+id_mmd1404+"_mmd1404").find(".tiret_part3_mmd1404").css({opacity: 1});
		$("#nav_tablet"+id_mmd1404+"_mmd1404").find(".legende_part3_mmd1404").css({opacity: 1});
		$(".screen_tablet_mmd1404").clearQueue().animate({opacity : 0},200);
		setTimeout(function(){
			$("#screen"+id_mmd1404+"_mmd1404").clearQueue().animate({opacity : 1},200);
		},210);
		selected_mmd1404 = id_mmd1404;
	}
}

function anim_intro_mmd1404(){

	$("#cta_begin_mmd1404").css({right : "230px"});

	timeouts_mmd1404.push(setTimeout(function(){
		$("#cta_begin_mmd1404").animate({right : "0px"}, 400);
		$("#zone_slide2_mmd1404").fadeOut(0);
	},900));
	first_timeout = setTimeout(function(){
		$("#cta_begin_mmd1404").trigger("click");
	},11000);

}

function anim_slide_mmd1404(next_mmd1404){

	window.clearTimeout(window.mainTimer);
	window.isInAutomaticAnimation = true;

	if(next_mmd1404==1){
		auto1_mmd1404 = 1;
		clearTimeout(timer_auto_mmd1404);
		$("#zone_slide1_mmd1404").fadeIn();
		$("#zone_slide2_mmd1404, #zone_slide3_mmd1404, #zone_slide4_mmd1404, #zone_slide5_mmd1404").fadeOut();

		//initial
		$("#zone_car_part1_mmd1404").animate({left : "1500px", top : "400px", "margin-left" : "0px"},0);
		$("#porte1_part1_mmd1404, #porte2_part1_mmd1404, #porte3_part1_mmd1404, #porte4_part1_mmd1404").animate({rotate : "0deg"},0);
		$("#line1_part1_mmd1404, #line2_part1_mmd1404, #cadre_part1_mmd1404").animate({opacity : 0, "margin-left" : "0px"},0);

		//anim
		$("#zone_car_part1_mmd1404").animate({left : "600px", top : "10px"},1600, "easeOutQuad");
		timeouts_mmd1404.push(setTimeout(function(){
			// $("#porte1_part1_mmd1404").animate({rotate : "60deg"},500, "easeOutQuad");
			// $("#porte2_part1_mmd1404").animate({rotate : "-60deg"},500, "easeOutQuad");
			// $("#porte3_part1_mmd1404").animate({rotate : "60deg"},500, "easeOutQuad");
			// $("#porte4_part1_mmd1404").animate({rotate : "-60deg"},500, "easeOutQuad");
			$({deg: 0}).animate(
			{deg: 60},
				{
					duration: 500,
					step: function(n){
						$("#porte1_part1_mmd1404, #porte3_part1_mmd1404").css({
							'transform':'rotate('+n+'deg)'
						});
					}
				}
			);

			$({deg: 0}).animate(
			{deg: -60},
				{
					duration: 500,
					step: function(n){
						$("#porte2_part1_mmd1404, #porte4_part1_mmd1404").css({
							'transform':'rotate('+n+'deg)'
						});
					}
				}
			);
		},1500));
		timeouts_mmd1404.push(setTimeout(function(){
			$("#line1_part1_mmd1404").animate({opacity : 1},600);
		},1900));
		timeouts_mmd1404.push(setTimeout(function(){
			$("#line2_part1_mmd1404").animate({opacity : 1},600);
		},2200));
		timeouts_mmd1404.push(setTimeout(function(){
			$("#cadre_part1_mmd1404").animate({opacity : 1},600);
		},2700));

		if(window.isInAutomaticAnimation === true){

			window.mainTimer = window.setTimeout(function(){
				anim_auto_mmd1404();
			}, 13500);
		}
	}
	else if(next_mmd1404==2){

		$("#zone_slide2_mmd1404").fadeIn();
		$("#zone_slide1_mmd1404, #zone_slide3_mmd1404, #zone_slide4_mmd1404, #zone_slide5_mmd1404").fadeOut();

		//initial
		current360_mmd1404=30;
		auto_mmd1404 = 0;
		clearInterval(interval360_mmd1404);

		$(".turn_mmd1404").css({display : "none"});
		$("#img30_360_mmd1404").css({display : "block"});
		$("#back_part2_mmd1404").animate({opacity: 0, "margin-left" : "0px"},0);
		$("#line1_part2_mmd1404, #line2_part2_mmd1404, #line3_part2_mmd1404").animate({opacity : 0, "margin-left" : "0px"},0);
		$("#right_turn_part2_mmd1404, #left_turn_part2_mmd1404").css({display : "none"});
		$("#zone_360_mmd1404").animate({opacity: 0, "margin-left" : "0px"},0);

		//anim
		$("#back_part2_mmd1404").animate({opacity: 1},500);
		timeouts_mmd1404.push(setTimeout(function(){
			$("#line1_part2_mmd1404").animate({opacity : 1},400);
		},400));
		timeouts_mmd1404.push(setTimeout(function(){
			$("#line2_part2_mmd1404").animate({opacity : 1},400);
		},600));
		timeouts_mmd1404.push(setTimeout(function(){
			$("#line3_part2_mmd1404").animate({opacity : 1},400);
		},800));
		timeouts_mmd1404.push(setTimeout(function(){
			$("#zone_360_mmd1404").animate({opacity: 1},500);

			interval360_mmd1404 = setInterval(function(){
				if(current360_mmd1404 < 72){
					// $("#img"+current360_mmd1404+"_360_mmd1404").css({display: "none"});
					// $("#img"+(current360_mmd1404+1)+"_360_mmd1404").css({display: "block"});
					document.getElementById("img"+current360_mmd1404+"_360_mmd1404").style.display = "none";
					document.getElementById("img"+(current360_mmd1404+1)+"_360_mmd1404").style.display = "block";

					current360_mmd1404 = current360_mmd1404 + 1;

					if(current360_mmd1404 == 30 && auto_mmd1404 == 1){
						// alert('ok');
						window.clearInterval(interval360_mmd1404);
					}
				}else{

					auto_mmd1404 = 1;
					document.getElementById("img72_360_mmd1404").style.display = "none";
					document.getElementById("img1_360_mmd1404").style.display = "block";
					current360_mmd1404=1;
				}
			}, 45);
		},1000));
		timeouts_mmd1404.push(setTimeout(function(){
			$("#right_turn_part2_mmd1404, #left_turn_part2_mmd1404").fadeIn(200);
		},4700));



		if(window.isInAutomaticAnimation === true){

			window.mainTimer = window.setTimeout(function(){
				anim_auto_mmd1404();
			}, 15000);
		}
	}
	else if(next_mmd1404==3){

		$("#zone_slide3_mmd1404").fadeIn();
		$("#zone_slide2_mmd1404, #zone_slide1_mmd1404, #zone_slide4_mmd1404, #zone_slide5_mmd1404").fadeOut();

		//initial
		selected_mmd1404=0;

		$("#line1_part3_mmd1404, #line2_part3_mmd1404").animate({opacity: 0, "margin-left" : "0px"},0);
		$("#zone_tablet_mmd1404").animate({opacity: 0, "margin-left" : "0px"},0);
		$("#zone_nav_tablet_mmd1404").animate({opacity: 0, top: "145px", "margin-left" : "0px"},0);
		$(".nav_tablet_mmd1404").removeClass("current_nav_tablet_mmd1404");
		$(".screen_tablet_mmd1404").css({opacity: 0});
		$(".tiret_part3_mmd1404").css({opacity: 0});
		$(".legende_part3_mmd1404").css({opacity: 0});


		//anim
		$("#zone_tablet_mmd1404").animate({opacity: 1},500);
		timeouts_mmd1404.push(setTimeout(function(){
			$("#line1_part3_mmd1404").animate({opacity : 1},400);
		},400));
		timeouts_mmd1404.push(setTimeout(function(){
			$("#line2_part3_mmd1404").animate({opacity : 1},400);
		},600));
		timeouts_mmd1404.push(setTimeout(function(){
			$("#zone_nav_tablet_mmd1404").animate({opacity : 1, top: "118px"},400);
		},800));
		timeouts_mmd1404.push(setTimeout(function(){
			// $("#picto_nav1_mm1404").trigger("click");
			autoplayTablerSlide($("#picto_nav1_mm1404"), false);
		},1300));


		if(window.isInAutomaticAnimation === true){

			window.mainTimer = window.setTimeout(function(){
				anim_auto_mmd1404();
			}, 13000);
		}
	}
	else if(next_mmd1404==4){

		$("#zone_slide4_mmd1404").fadeIn();
		$("#zone_slide2_mmd1404, #zone_slide3_mmd1404, #zone_slide1_mmd1404, #zone_slide5_mmd1404").fadeOut();

		//initial
		$("#zone_voiture_part4_mmd1404").animate({scale : "0.6", left : "-120px", top: "-5px", "margin-left" : "0px"},0);
		$("#roue1_part4_mmd1404, #roue2_part4_mmd1404").animate({rotate : 0},0);
		$("#line1_part4_mmd1404, #line2_part4_mmd1404, #line3_part4_mmd1404, #zone_oeil_mmd1404, #zone_video_mmd1404").animate({opacity: 0, "margin-left" : "0px"},0);
		$("#top_oeil_mmd1404").animate({rotate : "-26deg"},0);
		$("#bot_oeil_mmd1404").animate({rotate : "26deg"},0);
		$("#light_part4_mmd1404").animate({opacity : 1},0);
		video_mmd1404.currentTime = 0;

		if (isIE () && isIE () <= 9) {
			// $("#roue1_part4_mmd1404").css({display : "none"});
			// $("#roue2_part4_mmd1404").css({display : "none"});

			// var expcetionImage = $("#car_part4_mmd1404").data('exceptionIe');
			// $("#car_part4_mmd1404").attr("src", expcetionImage);

			$('#zone_video_mmd1404, #zone_voiture_part4_mmd1404').css({'display':'none'});
			$('#mmd_zone_voiture_ie').css({'display':'block'});
		}

		//anim
		$("#zone_voiture_part4_mmd1404").animate({scale : "1", left : "148px", top: "32px"},3200);
		// $("#roue1_part4_mmd1404, #roue2_part4_mmd1404").animate({rotate : "600deg"},1200);
		$({deg: 0}).animate(
			{deg: 600},
				{
					duration: 3200,
					step: function(n){
						$("#roue1_part4_mmd1404, #roue2_part4_mmd1404").css({
							'transform':'rotate('+n+'deg)'
						});
					}
				}
			);

		timeouts_mmd1404.push(setTimeout(function(){
			$("#zone_video_mmd1404").animate({opacity : 1},400);
			video_mmd1404.play();
		},1000));
		timeouts_mmd1404.push(setTimeout(function(){
			$("#light_part4_mmd1404").animate({opacity : 0},200);
		},3200));
		timeouts_mmd1404.push(setTimeout(function(){
			$("#line1_part4_mmd1404").animate({opacity : 1},400);
		},3300));
		timeouts_mmd1404.push(setTimeout(function(){
			$("#line2_part4_mmd1404").animate({opacity : 1},400);
		},3500));
		timeouts_mmd1404.push(setTimeout(function(){
			$("#line3_part4_mmd1404").animate({opacity : 1},400);
		},3700));
		timeouts_mmd1404.push(setTimeout(function(){
			$("#zone_oeil_mmd1404").animate({opacity : 1},400);
		},3900));
		timeouts_mmd1404.push(setTimeout(function(){
			$("#top_oeil_mmd1404").animate({rotate : "0deg"},400);
			$("#bot_oeil_mmd1404").animate({rotate : "0deg"},400);
		},9500));
		timeouts_mmd1404.push(setTimeout(function(){
			$("#top_oeil_mmd1404").animate({rotate : "-26deg"},400);
			$("#bot_oeil_mmd1404").animate({rotate : "26deg"},400);
			interval_cligne_mmd1404 = setInterval(function(){
				$("#top_oeil_mmd1404").animate({rotate : "0deg"},400);
				$("#bot_oeil_mmd1404").animate({rotate : "0deg"},400);
				timeouts_mmd1404.push(setTimeout(function(){
					$("#top_oeil_mmd1404").animate({rotate : "-26deg"},400);
					$("#bot_oeil_mmd1404").animate({rotate : "26deg"},400);
				},410));
			},8900);
		},9910));

		if(window.isInAutomaticAnimation === true){

			window.mainTimer = window.setTimeout(function(){
				anim_auto_mmd1404();
			}, 30000);
		}
	}
	else if(next_mmd1404==5){

		$("#zone_slide5_mmd1404").fadeIn();
		$("#zone_slide2_mmd1404, #zone_slide3_mmd1404, #zone_slide4_mmd1404, #zone_slide1_mmd1404").fadeOut();
		degree_mmd1404 = 0;
		clearInterval(interval_wheel_mmd1404);
		clearInterval(interval_city_mmd1404);

		//initial
		$("#zone_voiture_part5_mmd1404").animate({left : "1450px", "margin-left" : "0px"},0);
		$("#line1_part5_mmd1404, #line2_part5_mmd1404, #line3_part5_mmd1404, #line4_part5_mmd1404, #cadre_part5_mmd1404").animate({opacity :0, "margin-left" : "0px"},0);
		$("#zone_gouttes_mmd1404").animate({"margin-left" : "0px"},0);
		$("#cache_gouttes_mmd1404").animate({width:"100%", left : "0%"},0);
		$("#zone_route_mmd1404").animate({opacity : 0},0);
		$("#roue1_part5_mmd1404, #roue2_part5_mmd1404").animate({rotate : "0deg"},0);
		$("#city_mmd1404").animate({left : "-541px"},0);
		$("#txt_part5_mmd1404").animate({opacity : 1},0);
		$("#cta_fin1_mmd1404, #cta_fin2_mmd1404").animate({opacity : 0, "margin-left" : "0px"},0);
		$("#zone_cta_fin_mmd1404").css({display : "none"});

		//anim
		$("#zone_voiture_part5_mmd1404").animate({left : "108px"},1800);
		// $("#roue1_part5_mmd1404, #roue2_part5_mmd1404").animate({rotate : "-"+degree_mmd1404+"deg"},200, "linear");
		interval_wheel_mmd1404 = setInterval(function(){
			degree_mmd1404=degree_mmd1404+150;
			$("#roue1_part5_mmd1404, #roue2_part5_mmd1404").animate({rotate : "-"+degree_mmd1404+"deg"},200, "linear");
			// $({deg: -(degree_mmd1404)}).animate(
			// {deg: -(degree_mmd1404+150)},
			// 	{
			// 		duration: 200,
			// 		step: function(n){
			// 			$("#roue1_part5_mmd1404, #roue2_part5_mmd1404").css({
			// 				'transform':'rotate('+n+'deg)'
			// 			});
			// 		}
			// 	}
			// );
		},200);

		$("#city_mmd1404").animate({left : "0px"},5000, "linear").animate({left : "-541px"},0);
		interval_city_mmd1404 = setInterval(function(){
			$("#city_mmd1404").animate({left : "0px"},5000, "linear").animate({left : "-541px"},0);
		},5000);

		timeouts_mmd1404.push(setTimeout(function(){
			$("#zone_route_mmd1404").animate({opacity : 1},400);
		},900));

		timeouts_mmd1404.push(setTimeout(function(){
			$("#line1_part5_mmd1404").animate({opacity : 1},400);
		},1400));

		timeouts_mmd1404.push(setTimeout(function(){
			$("#cache_gouttes_mmd1404").animate({left : "100%", width : "0%"},500);
		},1600));

		timeouts_mmd1404.push(setTimeout(function(){
			$("#line2_part5_mmd1404").animate({opacity : 1},400);
		},2000));

		timeouts_mmd1404.push(setTimeout(function(){
			$("#line3_part5_mmd1404").animate({opacity : 1},400);
		},2200));

		timeouts_mmd1404.push(setTimeout(function(){
			$("#cadre_part5_mmd1404").animate({opacity : 1},400);
		},2400));


		timeouts_mmd1404.push(setTimeout(function(){
			$("#txt_part5_mmd1404").animate({opacity : 0},500);
		},7000));

		timeouts_mmd1404.push(setTimeout(function(){
			$("#zone_cta_fin_mmd1404").css({display : "block"});
			$("#cta_fin1_mmd1404").animate({opacity : 1},500);
		},7300));

		timeouts_mmd1404.push(setTimeout(function(){
			$("#cta_fin2_mmd1404").animate({opacity : 1},500);
		},7600));

		if(window.isInAutomaticAnimation === true){

			window.mainTimer = window.setTimeout(function(){
				anim_auto_mmd1404();
			}, 18000);
		}
	}
}

function leave_slide_mmd1404(current_mmd1404){
	if(current_mmd1404==1){
		 $("#line1_part1_mmd1404").clearQueue().animate({"margin-left" : "-1500px"},600,"easeInSine");
		 setTimeout(function(){
		 	$("#line2_part1_mmd1404").clearQueue().animate({"margin-left" : "-1500px"},600,"easeInSine");
		 },100);
		 setTimeout(function(){
		 	$("#cadre_part1_mmd1404").clearQueue().animate({"margin-left" : "-1500px"},600,"easeInSine");
		 },250);
		 setTimeout(function(){
		 	$("#zone_car_part1_mmd1404").clearQueue().animate({"margin-left" : "-1500px"},600,"easeInSine");
		 },300);
	}
	else if(current_mmd1404==2){
		clearInterval(interval360_mmd1404);
		$("#left_turn_part2_mmd1404, #right_turn_part2_mmd1404").fadeOut(200);
		$("#back_part2_mmd1404").clearQueue().animate({"margin-left" : "-1500px"},600,"easeInSine");
		setTimeout(function(){
		 	$("#zone_360_mmd1404").clearQueue().animate({"margin-left" : "-1500px"},600,"easeInSine");
		 },100);
	}
	else if(current_mmd1404==3){
		$("#zone_tablet_mmd1404").clearQueue().animate({"margin-left" : "-1500px"},600,"easeInSine");
	 	setTimeout(function(){
	 		$("#line1_part3_mmd1404").clearQueue().animate({"margin-left" : "-2500px"},600,"easeInSine");
	 	},200);
	 	setTimeout(function(){
	 		$("#line2_part3_mmd1404").clearQueue().animate({"margin-left" : "-2500px"},600,"easeInSine");
	 	},300);
	 	setTimeout(function(){
	 		$("#zone_nav_tablet_mmd1404").clearQueue().animate({"margin-left" : "-2000px"},600,"easeInSine");
	 	},400);
	}
	else if(current_mmd1404==4){
		video_mmd1404.pause();
		clearInterval(interval_cligne_mmd1404);
		$("#zone_video_mmd1404").clearQueue().animate({"margin-left" : "-1500px"},600,"easeInSine");
		setTimeout(function(){
	 		$("#zone_voiture_part4_mmd1404").clearQueue().animate({"margin-left" : "-2500px"},600,"easeInSine");
	 	},200);
	 	setTimeout(function(){
	 		$("#line1_part4_mmd1404").clearQueue().animate({"margin-left" : "-2500px"},600,"easeInSine");
	 	},400);
	 	setTimeout(function(){
	 		$("#line2_part4_mmd1404").clearQueue().animate({"margin-left" : "-2500px"},600,"easeInSine");
	 	},500);
	 	setTimeout(function(){
	 		$("#line3_part4_mmd1404").clearQueue().animate({"margin-left" : "-2500px"},600,"easeInSine");
	 	},600);
	 	setTimeout(function(){
	 		$("#zone_oeil_mmd1404").clearQueue().animate({"margin-left" : "-2500px"},600,"easeInSine");
	 	},700);
	}
	else if(current_mmd1404==5){
		clearInterval(interval_wheel_mmd1404);
		clearInterval(interval_city_mmd1404);
		$("#zone_route_mmd1404").animate({opacity : 0},600);
		$("#zone_voiture_part5_mmd1404").clearQueue().animate({"margin-left" : "-1500px"},600,"easeInSine");
		$("#cta_fin1_mmd1404").clearQueue().animate({"margin-left" : "-1500px"},600,"easeInSine");
		setTimeout(function(){
	 		$("#line1_part5_mmd1404, #zone_gouttes_mmd1404").clearQueue().animate({"margin-left" : "-2500px"},600,"easeInSine");
	 		$("#cta_fin2_mmd1404").clearQueue().animate({"margin-left" : "-1500px"},600,"easeInSine");
	 	},200);
	 	setTimeout(function(){
	 		$("#line2_part5_mmd1404, #line3_part5_mmd1404").clearQueue().animate({"margin-left" : "-2500px"},600,"easeInSine");
	 	},300);
	 	setTimeout(function(){
	 		$("#line4_part5_mmd1404, #cadre_part5_mmd1404").clearQueue().animate({"margin-left" : "-2500px"},600,"easeInSine");
	 	},400);
	}
}

//NAV

$(".selec_nav_mmd1404").click(function(){

	var current_id_mmd1404 = $(this).attr("id");
	var number_id_mmd1404 = parseInt(current_id_mmd1404.substring(5,6));

	$(".current_selec_nav_mmd1404").removeClass("current_selec_nav_mmd1404");
	$(".current_title_mmd1404").removeClass("current_title_mmd1404");

	$(this).addClass("current_selec_nav_mmd1404");
	$("#titre"+number_id_mmd1404+"_nav_mmd1404").addClass("current_title_mmd1404");

	clearTimeout(timer_auto_mmd1404);

	window.clearTimeout(window.mainTimer);
	window.isInAutomaticAnimation = false;

	if(number_id_mmd1404!=position_generale_mmd1404){
		for (var i = 0; i < timeouts_mmd1404.length; i++) {
		    clearTimeout(timeouts_mmd1404[i]);
		}
		timeouts_mmd1404 = [];
		leave_slide_mmd1404(position_generale_mmd1404);
		setTimeout(function(){
			anim_slide_mmd1404(number_id_mmd1404);
			position_generale_mmd1404=number_id_mmd1404;
		},800);
	}
});

$("#nav_left_mmd1404").click(function(){
	clearTimeout(timer_auto_mmd1404);

	window.clearTimeout(window.mainTimer);
	window.isInAutomaticAnimation = false;

	if(position_generale_mmd1404==1){
		$("#selec5_nav_mmd1404").trigger("click");
	}else{
		$("#selec"+(position_generale_mmd1404-1)+"_nav_mmd1404").trigger("click");
	}
});

$("#nav_right_mmd1404").click(function(){
	clearTimeout(timer_auto_mmd1404);

	window.clearTimeout(window.mainTimer);
	window.isInAutomaticAnimation = false;

	if(position_generale_mmd1404==5){
		$("#selec1_nav_mmd1404").trigger("click");
	}else{
		$("#selec"+(position_generale_mmd1404+1)+"_nav_mmd1404").trigger("click");
	}
});



//BEGIN

$("#cta_begin_mmd1404").click(function(event, auto){
	$("#zone_intro_mmd1404").fadeOut(300);
	window.clearTimeout(first_timeout);
	setTimeout(function(){
		anim_slide_mmd1404(1);
		$("#zone_nav_mmd1404").fadeIn();
	},200);

	dataLayer.push({
		"event": "uaevent",
		"eventCategory": "Showroom::AnimationTopProduit::Index",
		"eventAction": "Start",
		"eventLabel": "Démarrez !"
	});
});

$("#cta_fin1_mmd1404").click(function(){
		dataLayer.push({
			"event": "uaevent",
			"eventCategory": "Showroom::AnimationTopProduit::Step5",
			"eventAction": "Forms::C1",
			"eventLabel": "Essayez-la !"
		});
});

$("#cta_fin2_mmd1404").click(function(){
		dataLayer.push({
			"event": "uaevent",
			"eventCategory": "Showroom::AnimationTopProduit::Step5",
			"eventAction": "Configurator::TestDrive::C1",
			"eventLabel": "Configurez-la !"
		});
});

function anim_auto_mmd1404(){
	if(position_generale_mmd1404==5){
		$("#selec1_nav_mmd1404").trigger("click");
	}else{
		$("#selec"+(position_generale_mmd1404+1)+"_nav_mmd1404").trigger("click");
	}
}


//360

$("#right_turn_part2_mmd1404").mousedown(function(){
	window.clearTimeout(window.mainTimer);
	window.isInAutomaticAnimation = false;

	interval360_mmd1404 = setInterval(function(){
		if(current360_mmd1404<72){
			// $("#img"+current360_mmd1404+"_360_mmd1404").css({opacity: 0});
			// $("#img"+(current360_mmd1404+1)+"_360_mmd1404").css({opacity: 1});
			document.getElementById("img"+current360_mmd1404+"_360_mmd1404").style.display = "none";
			document.getElementById("img"+(current360_mmd1404+1)+"_360_mmd1404").style.display = "block";
			current360_mmd1404=current360_mmd1404+1;
		}else{
			// $("#img72_360_mmd1404").css({opacity: 0});
			// $("#img1_360_mmd1404").css({opacity: 1});
			document.getElementById("img72_360_mmd1404").style.display = "none";
			document.getElementById("img1_360_mmd1404").style.display = "block";
			current360_mmd1404=1;
		}
	},40);
});

$("#right_turn_part2_mmd1404").mouseup(function(){
	clearInterval(interval360_mmd1404);

	window.clearTimeout(window.mainTimer);
	window.isInAutomaticAnimation = false;
});

$("#right_turn_part2_mmd1404").mouseleave(function(){
	clearInterval(interval360_mmd1404);

	window.clearTimeout(window.mainTimer);
	window.isInAutomaticAnimation = false;
});

$("#left_turn_part2_mmd1404").mousedown(function(){
	window.clearTimeout(window.mainTimer);
	window.isInAutomaticAnimation = false;

	interval360_mmd1404 = setInterval(function(){
		if(current360_mmd1404>1){
			// $("#img"+current360_mmd1404+"_360_mmd1404").css({opacity: 0});
			// $("#img"+(current360_mmd1404-1)+"_360_mmd1404").css({opacity: 1});
			document.getElementById("img"+current360_mmd1404+"_360_mmd1404").style.display = "none";
			document.getElementById("img"+(current360_mmd1404-1)+"_360_mmd1404").style.display = "block";
			current360_mmd1404=current360_mmd1404-1;
		}else{
			$("#img1_360_mmd1404").css({opacity: 0});
			$("#img72_360_mmd1404").css({opacity: 1});
			document.getElementById("img1_360_mmd1404").style.display = "none";
			document.getElementById("img72_360_mmd1404").style.display = "block";
			current360_mmd1404=72;
		}
	},40);
});

$("#left_turn_part2_mmd1404").mouseup(function(){
	clearInterval(interval360_mmd1404);

	window.clearTimeout(window.mainTimer);
	window.isInAutomaticAnimation = false;
});

$("#left_turn_part2_mmd1404").mouseleave(function(){
	clearInterval(interval360_mmd1404);

	window.clearTimeout(window.mainTimer);
	window.isInAutomaticAnimation = false;
});


//slide tablette


$(".picto_nav_mmd1404").click(function(){

	window.clearTimeout(window.mainTimer);
	window.isInAutomaticAnimation = false;

	autoplayTablerSlide($(this), true);
});

function isIE () {
  var myNav = navigator.userAgent.toLowerCase();
  return (myNav.indexOf('msie') != -1) ? parseInt(myNav.split('msie')[1]) : false;
}
