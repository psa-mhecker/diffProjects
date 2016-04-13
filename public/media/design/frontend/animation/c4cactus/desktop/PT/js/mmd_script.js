(function(){
	// jquery local ui
	(function(e){"function"==typeof define&&define.amd?define(["jquery"],e):e(jQuery)})(function(e){var t="ui-effects-",i=e;e.effects={effect:{}},function(e,t){function i(e,t,i){var s=d[t.type]||{};return null==e?i||!t.def?null:t.def:(e=s.floor?~~e:parseFloat(e),isNaN(e)?t.def:s.mod?(e+s.mod)%s.mod:0>e?0:e>s.max?s.max:e)}function s(i){var s=l(),n=s._rgba=[];return i=i.toLowerCase(),f(h,function(e,a){var o,r=a.re.exec(i),h=r&&a.parse(r),l=a.space||"rgba";return h?(o=s[l](h),s[u[l].cache]=o[u[l].cache],n=s._rgba=o._rgba,!1):t}),n.length?("0,0,0,0"===n.join()&&e.extend(n,a.transparent),s):a[i]}function n(e,t,i){return i=(i+1)%1,1>6*i?e+6*(t-e)*i:1>2*i?t:2>3*i?e+6*(t-e)*(2/3-i):e}var a,o="backgroundColor borderBottomColor borderLeftColor borderRightColor borderTopColor color columnRuleColor outlineColor textDecorationColor textEmphasisColor",r=/^([\-+])=\s*(\d+\.?\d*)/,h=[{re:/rgba?\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*(?:,\s*(\d?(?:\.\d+)?)\s*)?\)/,parse:function(e){return[e[1],e[2],e[3],e[4]]}},{re:/rgba?\(\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*(?:,\s*(\d?(?:\.\d+)?)\s*)?\)/,parse:function(e){return[2.55*e[1],2.55*e[2],2.55*e[3],e[4]]}},{re:/#([a-f0-9]{2})([a-f0-9]{2})([a-f0-9]{2})/,parse:function(e){return[parseInt(e[1],16),parseInt(e[2],16),parseInt(e[3],16)]}},{re:/#([a-f0-9])([a-f0-9])([a-f0-9])/,parse:function(e){return[parseInt(e[1]+e[1],16),parseInt(e[2]+e[2],16),parseInt(e[3]+e[3],16)]}},{re:/hsla?\(\s*(\d+(?:\.\d+)?)\s*,\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*(?:,\s*(\d?(?:\.\d+)?)\s*)?\)/,space:"hsla",parse:function(e){return[e[1],e[2]/100,e[3]/100,e[4]]}}],l=e.Color=function(t,i,s,n){return new e.Color.fn.parse(t,i,s,n)},u={rgba:{props:{red:{idx:0,type:"byte"},green:{idx:1,type:"byte"},blue:{idx:2,type:"byte"}}},hsla:{props:{hue:{idx:0,type:"degrees"},saturation:{idx:1,type:"percent"},lightness:{idx:2,type:"percent"}}}},d={"byte":{floor:!0,max:255},percent:{max:1},degrees:{mod:360,floor:!0}},c=l.support={},p=e("<p>")[0],f=e.each;p.style.cssText="background-color:rgba(1,1,1,.5)",c.rgba=p.style.backgroundColor.indexOf("rgba")>-1,f(u,function(e,t){t.cache="_"+e,t.props.alpha={idx:3,type:"percent",def:1}}),l.fn=e.extend(l.prototype,{parse:function(n,o,r,h){if(n===t)return this._rgba=[null,null,null,null],this;(n.jquery||n.nodeType)&&(n=e(n).css(o),o=t);var d=this,c=e.type(n),p=this._rgba=[];return o!==t&&(n=[n,o,r,h],c="array"),"string"===c?this.parse(s(n)||a._default):"array"===c?(f(u.rgba.props,function(e,t){p[t.idx]=i(n[t.idx],t)}),this):"object"===c?(n instanceof l?f(u,function(e,t){n[t.cache]&&(d[t.cache]=n[t.cache].slice())}):f(u,function(t,s){var a=s.cache;f(s.props,function(e,t){if(!d[a]&&s.to){if("alpha"===e||null==n[e])return;d[a]=s.to(d._rgba)}d[a][t.idx]=i(n[e],t,!0)}),d[a]&&0>e.inArray(null,d[a].slice(0,3))&&(d[a][3]=1,s.from&&(d._rgba=s.from(d[a])))}),this):t},is:function(e){var i=l(e),s=!0,n=this;return f(u,function(e,a){var o,r=i[a.cache];return r&&(o=n[a.cache]||a.to&&a.to(n._rgba)||[],f(a.props,function(e,i){return null!=r[i.idx]?s=r[i.idx]===o[i.idx]:t})),s}),s},_space:function(){var e=[],t=this;return f(u,function(i,s){t[s.cache]&&e.push(i)}),e.pop()},animate:function(e,t){var s=l(e),n=s._space(),a=u[n],o=0===this.alpha()?l("transparent"):this,r=o[a.cache]||a.to(o._rgba),h=r.slice();return s=s[a.cache],f(a.props,function(e,n){var a=n.idx,o=r[a],l=s[a],u=d[n.type]||{};null!==l&&(null===o?h[a]=l:(u.mod&&(l-o>u.mod/2?o+=u.mod:o-l>u.mod/2&&(o-=u.mod)),h[a]=i((l-o)*t+o,n)))}),this[n](h)},blend:function(t){if(1===this._rgba[3])return this;var i=this._rgba.slice(),s=i.pop(),n=l(t)._rgba;return l(e.map(i,function(e,t){return(1-s)*n[t]+s*e}))},toRgbaString:function(){var t="rgba(",i=e.map(this._rgba,function(e,t){return null==e?t>2?1:0:e});return 1===i[3]&&(i.pop(),t="rgb("),t+i.join()+")"},toHslaString:function(){var t="hsla(",i=e.map(this.hsla(),function(e,t){return null==e&&(e=t>2?1:0),t&&3>t&&(e=Math.round(100*e)+"%"),e});return 1===i[3]&&(i.pop(),t="hsl("),t+i.join()+")"},toHexString:function(t){var i=this._rgba.slice(),s=i.pop();return t&&i.push(~~(255*s)),"#"+e.map(i,function(e){return e=(e||0).toString(16),1===e.length?"0"+e:e}).join("")},toString:function(){return 0===this._rgba[3]?"transparent":this.toRgbaString()}}),l.fn.parse.prototype=l.fn,u.hsla.to=function(e){if(null==e[0]||null==e[1]||null==e[2])return[null,null,null,e[3]];var t,i,s=e[0]/255,n=e[1]/255,a=e[2]/255,o=e[3],r=Math.max(s,n,a),h=Math.min(s,n,a),l=r-h,u=r+h,d=.5*u;return t=h===r?0:s===r?60*(n-a)/l+360:n===r?60*(a-s)/l+120:60*(s-n)/l+240,i=0===l?0:.5>=d?l/u:l/(2-u),[Math.round(t)%360,i,d,null==o?1:o]},u.hsla.from=function(e){if(null==e[0]||null==e[1]||null==e[2])return[null,null,null,e[3]];var t=e[0]/360,i=e[1],s=e[2],a=e[3],o=.5>=s?s*(1+i):s+i-s*i,r=2*s-o;return[Math.round(255*n(r,o,t+1/3)),Math.round(255*n(r,o,t)),Math.round(255*n(r,o,t-1/3)),a]},f(u,function(s,n){var a=n.props,o=n.cache,h=n.to,u=n.from;l.fn[s]=function(s){if(h&&!this[o]&&(this[o]=h(this._rgba)),s===t)return this[o].slice();var n,r=e.type(s),d="array"===r||"object"===r?s:arguments,c=this[o].slice();return f(a,function(e,t){var s=d["object"===r?e:t.idx];null==s&&(s=c[t.idx]),c[t.idx]=i(s,t)}),u?(n=l(u(c)),n[o]=c,n):l(c)},f(a,function(t,i){l.fn[t]||(l.fn[t]=function(n){var a,o=e.type(n),h="alpha"===t?this._hsla?"hsla":"rgba":s,l=this[h](),u=l[i.idx];return"undefined"===o?u:("function"===o&&(n=n.call(this,u),o=e.type(n)),null==n&&i.empty?this:("string"===o&&(a=r.exec(n),a&&(n=u+parseFloat(a[2])*("+"===a[1]?1:-1))),l[i.idx]=n,this[h](l)))})})}),l.hook=function(t){var i=t.split(" ");f(i,function(t,i){e.cssHooks[i]={set:function(t,n){var a,o,r="";if("transparent"!==n&&("string"!==e.type(n)||(a=s(n)))){if(n=l(a||n),!c.rgba&&1!==n._rgba[3]){for(o="backgroundColor"===i?t.parentNode:t;(""===r||"transparent"===r)&&o&&o.style;)try{r=e.css(o,"backgroundColor"),o=o.parentNode}catch(h){}n=n.blend(r&&"transparent"!==r?r:"_default")}n=n.toRgbaString()}try{t.style[i]=n}catch(h){}}},e.fx.step[i]=function(t){t.colorInit||(t.start=l(t.elem,i),t.end=l(t.end),t.colorInit=!0),e.cssHooks[i].set(t.elem,t.start.animate(t.end,t.pos))}})},l.hook(o),e.cssHooks.borderColor={expand:function(e){var t={};return f(["Top","Right","Bottom","Left"],function(i,s){t["border"+s+"Color"]=e}),t}},a=e.Color.names={aqua:"#00ffff",black:"#000000",blue:"#0000ff",fuchsia:"#ff00ff",gray:"#808080",green:"#008000",lime:"#00ff00",maroon:"#800000",navy:"#000080",olive:"#808000",purple:"#800080",red:"#ff0000",silver:"#c0c0c0",teal:"#008080",white:"#ffffff",yellow:"#ffff00",transparent:[null,null,null,0],_default:"#ffffff"}}(i),function(){function t(t){var i,s,n=t.ownerDocument.defaultView?t.ownerDocument.defaultView.getComputedStyle(t,null):t.currentStyle,a={};if(n&&n.length&&n[0]&&n[n[0]])for(s=n.length;s--;)i=n[s],"string"==typeof n[i]&&(a[e.camelCase(i)]=n[i]);else for(i in n)"string"==typeof n[i]&&(a[i]=n[i]);return a}function s(t,i){var s,n,o={};for(s in i)n=i[s],t[s]!==n&&(a[s]||(e.fx.step[s]||!isNaN(parseFloat(n)))&&(o[s]=n));return o}var n=["add","remove","toggle"],a={border:1,borderBottom:1,borderColor:1,borderLeft:1,borderRight:1,borderTop:1,borderWidth:1,margin:1,padding:1};e.each(["borderLeftStyle","borderRightStyle","borderBottomStyle","borderTopStyle"],function(t,s){e.fx.step[s]=function(e){("none"!==e.end&&!e.setAttr||1===e.pos&&!e.setAttr)&&(i.style(e.elem,s,e.end),e.setAttr=!0)}}),e.fn.addBack||(e.fn.addBack=function(e){return this.add(null==e?this.prevObject:this.prevObject.filter(e))}),e.effects.animateClass=function(i,a,o,r){var h=e.speed(a,o,r);return this.queue(function(){var a,o=e(this),r=o.attr("class")||"",l=h.children?o.find("*").addBack():o;l=l.map(function(){var i=e(this);return{el:i,start:t(this)}}),a=function(){e.each(n,function(e,t){i[t]&&o[t+"Class"](i[t])})},a(),l=l.map(function(){return this.end=t(this.el[0]),this.diff=s(this.start,this.end),this}),o.attr("class",r),l=l.map(function(){var t=this,i=e.Deferred(),s=e.extend({},h,{queue:!1,complete:function(){i.resolve(t)}});return this.el.animate(this.diff,s),i.promise()}),e.when.apply(e,l.get()).done(function(){a(),e.each(arguments,function(){var t=this.el;e.each(this.diff,function(e){t.css(e,"")})}),h.complete.call(o[0])})})},e.fn.extend({addClass:function(t){return function(i,s,n,a){return s?e.effects.animateClass.call(this,{add:i},s,n,a):t.apply(this,arguments)}}(e.fn.addClass),removeClass:function(t){return function(i,s,n,a){return arguments.length>1?e.effects.animateClass.call(this,{remove:i},s,n,a):t.apply(this,arguments)}}(e.fn.removeClass),toggleClass:function(t){return function(i,s,n,a,o){return"boolean"==typeof s||void 0===s?n?e.effects.animateClass.call(this,s?{add:i}:{remove:i},n,a,o):t.apply(this,arguments):e.effects.animateClass.call(this,{toggle:i},s,n,a)}}(e.fn.toggleClass),switchClass:function(t,i,s,n,a){return e.effects.animateClass.call(this,{add:i,remove:t},s,n,a)}})}(),function(){function i(t,i,s,n){return e.isPlainObject(t)&&(i=t,t=t.effect),t={effect:t},null==i&&(i={}),e.isFunction(i)&&(n=i,s=null,i={}),("number"==typeof i||e.fx.speeds[i])&&(n=s,s=i,i={}),e.isFunction(s)&&(n=s,s=null),i&&e.extend(t,i),s=s||i.duration,t.duration=e.fx.off?0:"number"==typeof s?s:s in e.fx.speeds?e.fx.speeds[s]:e.fx.speeds._default,t.complete=n||i.complete,t}function s(t){return!t||"number"==typeof t||e.fx.speeds[t]?!0:"string"!=typeof t||e.effects.effect[t]?e.isFunction(t)?!0:"object"!=typeof t||t.effect?!1:!0:!0}e.extend(e.effects,{version:"1.11.4",save:function(e,i){for(var s=0;i.length>s;s++)null!==i[s]&&e.data(t+i[s],e[0].style[i[s]])},restore:function(e,i){var s,n;for(n=0;i.length>n;n++)null!==i[n]&&(s=e.data(t+i[n]),void 0===s&&(s=""),e.css(i[n],s))},setMode:function(e,t){return"toggle"===t&&(t=e.is(":hidden")?"show":"hide"),t},getBaseline:function(e,t){var i,s;switch(e[0]){case"top":i=0;break;case"middle":i=.5;break;case"bottom":i=1;break;default:i=e[0]/t.height}switch(e[1]){case"left":s=0;break;case"center":s=.5;break;case"right":s=1;break;default:s=e[1]/t.width}return{x:s,y:i}},createWrapper:function(t){if(t.parent().is(".ui-effects-wrapper"))return t.parent();var i={width:t.outerWidth(!0),height:t.outerHeight(!0),"float":t.css("float")},s=e("<div></div>").addClass("ui-effects-wrapper").css({fontSize:"100%",background:"transparent",border:"none",margin:0,padding:0}),n={width:t.width(),height:t.height()},a=document.activeElement;try{a.id}catch(o){a=document.body}return t.wrap(s),(t[0]===a||e.contains(t[0],a))&&e(a).focus(),s=t.parent(),"static"===t.css("position")?(s.css({position:"relative"}),t.css({position:"relative"})):(e.extend(i,{position:t.css("position"),zIndex:t.css("z-index")}),e.each(["top","left","bottom","right"],function(e,s){i[s]=t.css(s),isNaN(parseInt(i[s],10))&&(i[s]="auto")}),t.css({position:"relative",top:0,left:0,right:"auto",bottom:"auto"})),t.css(n),s.css(i).show()},removeWrapper:function(t){var i=document.activeElement;return t.parent().is(".ui-effects-wrapper")&&(t.parent().replaceWith(t),(t[0]===i||e.contains(t[0],i))&&e(i).focus()),t},setanimate:function(t,i,s,n){return n=n||{},e.each(i,function(e,i){var a=t.cssUnit(i);a[0]>0&&(n[i]=a[0]*s+a[1])}),n}}),e.fn.extend({effect:function(){function t(t){function i(){e.isFunction(a)&&a.call(n[0]),e.isFunction(t)&&t()}var n=e(this),a=s.complete,r=s.mode;(n.is(":hidden")?"hide"===r:"show"===r)?(n[r](),i()):o.call(n[0],s,i)}var s=i.apply(this,arguments),n=s.mode,a=s.queue,o=e.effects.effect[s.effect];return e.fx.off||!o?n?this[n](s.duration,s.complete):this.each(function(){s.complete&&s.complete.call(this)}):a===!1?this.each(t):this.queue(a||"fx",t)},show:function(e){return function(t){if(s(t))return e.apply(this,arguments);var n=i.apply(this,arguments);return n.mode="show",this.effect.call(this,n)}}(e.fn.show),hide:function(e){return function(t){if(s(t))return e.apply(this,arguments);var n=i.apply(this,arguments);return n.mode="hide",this.effect.call(this,n)}}(e.fn.hide),toggle:function(e){return function(t){if(s(t)||"boolean"==typeof t)return e.apply(this,arguments);var n=i.apply(this,arguments);return n.mode="toggle",this.effect.call(this,n)}}(e.fn.toggle),cssUnit:function(t){var i=this.css(t),s=[];return e.each(["em","px","%","pt"],function(e,t){i.indexOf(t)>0&&(s=[parseFloat(i),t])}),s}})}(),function(){var t={};e.each(["Quad","Cubic","Quart","Quint","Expo"],function(e,i){t[i]=function(t){return Math.pow(t,e+2)}}),e.extend(t,{Sine:function(e){return 1-Math.cos(e*Math.PI/2)},Circ:function(e){return 1-Math.sqrt(1-e*e)},Elastic:function(e){return 0===e||1===e?e:-Math.pow(2,8*(e-1))*Math.sin((80*(e-1)-7.5)*Math.PI/15)},Back:function(e){return e*e*(3*e-2)},Bounce:function(e){for(var t,i=4;((t=Math.pow(2,--i))-1)/11>e;);return 1/Math.pow(4,3-i)-7.5625*Math.pow((3*t-2)/22-e,2)}}),e.each(t,function(t,i){e.easing["easeIn"+t]=i,e.easing["easeOut"+t]=function(e){return 1-i(1-e)},e.easing["easeInOut"+t]=function(e){return.5>e?i(2*e)/2:1-i(-2*e+2)/2}})}(),e.effects});

	// global variables
	window.mainTimer = null, window.isInAutomaticAnimation = false, window.currentScreen = 0;

	// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	// dataLayer for tracking - enlever cette variable lors de l'intégration au template si le SDK de tracking est présent sur le template !!!!!!
	// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

	var dataLayer = [];

	function initWording(){
		
		$('.mmd_key_wording').each(function(index, element){

			$(this).html($(this).data('keyWording'));
		});
	}

	function executeGreyRoation(){
		var matrix = $("#mmd_screen_two_color_wheel_selector").css('transform') ||  $("#mmd_screen_two_color_wheel_selector").css('-webkit-transform') ||  $("#mmd_screen_two_color_wheel_selector").css('-moz-transform') ||  $("#mmd_screen_two_color_wheel_selector").css('-o-transform');
	    var values = matrix.split('(')[1].split(')')[0].split(',');
	    var coord = {
	        a: values[0],
	        b: values[1]
	    };

	    var angle = Math.round(Math.atan2(coord.b, coord.a) * (180/Math.PI));

		$({deg: angle}).animate(
			{deg: 76},
			{
				duration: 500,
				step: function(n, fx){
					$("#mmd_screen_two_color_wheel_selector").css({
						'transform':'rotate('+n+'deg)',
						'-webkit-transform':'rotate('+n+'deg)',
						'-moz-transform':'rotate('+n+'deg)',
						'-o-transform':'rotate('+n+'deg)',
						'-mstransform':'rotate('+n+'deg)'
					});
				},
				complete: function(){
					$("#mmd_screen_two_color_wheel_selector").css({
						'transform':'rotate(76deg)',
						'-webkit-transform':'rotate(76deg)',
						'-moz-transform':'rotate(76deg)',
						'-o-transform':'rotate(76deg)',
						'-ms-transform':'rotate(76deg)'
					});
				}
			}
		);

		$('#mmd_scree_two_green_car, #mmd_scree_two_purple_car, #mmd_scree_two_black_car, #mmd_scree_two_yellow_car').animate({'opacity':'0'}, 500);
		$('#mmd_scree_two_grey_car').animate({'opacity':'1'}, 500);
	}

	function executeBlackRotation(){
		var matrix = $("#mmd_screen_two_color_wheel_selector").css('transform') ||  $("#mmd_screen_two_color_wheel_selector").css('-webkit-transform') ||  $("#mmd_screen_two_color_wheel_selector").css('-moz-transform') ||  $("#mmd_screen_two_color_wheel_selector").css('-o-transform');
	    var values = matrix.split('(')[1].split(')')[0].split(',');
	    var coord = {
	        a: values[0],
	        b: values[1]
	    };

	    var angle = Math.round(Math.atan2(coord.b, coord.a) * (180/Math.PI));

		$({deg: angle}).animate(
			{deg: 145},
			{
				duration: 500,
				step: function(n, fx){
					$("#mmd_screen_two_color_wheel_selector").css({
						'transform':'rotate('+n+'deg)',
						'-webkit-transform':'rotate('+n+'deg)',
						'-moz-transform':'rotate('+n+'deg)',
						'-o-transform':'rotate('+n+'deg)',
						'-mstransform':'rotate('+n+'deg)'
					});
				},
				complete: function(){
					$("#mmd_screen_two_color_wheel_selector").css({
						'transform':'rotate(145deg)',
						'-webkit-transform':'rotate(145deg)',
						'-moz-transform':'rotate(145deg)',
						'-o-transform':'rotate(145deg)',
						'-ms-transform':'rotate(145deg)'
					});
				}
			}
		);

		$('#mmd_scree_two_green_car, #mmd_scree_two_purple_car, #mmd_scree_two_grey_car, #mmd_scree_two_yellow_car').animate({'opacity':'0'}, 500);
		$('#mmd_scree_two_black_car').animate({'opacity':'1'}, 500);
	}

	function executeGreenRotation(){
		var matrix = $("#mmd_screen_two_color_wheel_selector").css('transform') ||  $("#mmd_screen_two_color_wheel_selector").css('-webkit-transform') ||  $("#mmd_screen_two_color_wheel_selector").css('-moz-transform') ||  $("#mmd_screen_two_color_wheel_selector").css('-o-transform');
	    var values = matrix.split('(')[1].split(')')[0].split(',');
	    var coord = {
	        a: values[0],
	        b: values[1]
	    };

	    var angle = Math.round(Math.atan2(coord.b, coord.a) * (180/Math.PI));

		$({deg: angle}).animate(
			{deg: 216},
			{
				duration: 500,
				step: function(n, fx){
					$("#mmd_screen_two_color_wheel_selector").css({
						'transform':'rotate('+n+'deg)',
						'-webkit-transform':'rotate('+n+'deg)',
						'-moz-transform':'rotate('+n+'deg)',
						'-o-transform':'rotate('+n+'deg)',
						'-mstransform':'rotate('+n+'deg)'
					});
				},
				complete: function(){
					$("#mmd_screen_two_color_wheel_selector").css({
						'transform':'rotate(216deg)',
						'-webkit-transform':'rotate(216deg)',
						'-moz-transform':'rotate(216deg)',
						'-o-transform':'rotate(216deg)',
						'-ms-transform':'rotate(216deg)'
					});
				}
			}
		);

		$('#mmd_scree_two_black_car, #mmd_scree_two_purple_car, #mmd_scree_two_grey_car, #mmd_scree_two_yellow_car').animate({'opacity':'0'}, 500);
		$('#mmd_scree_two_green_car').animate({'opacity':'1'}, 500);
	}

	function executePurpleRotation(){
		var matrix = $("#mmd_screen_two_color_wheel_selector").css('transform') ||  $("#mmd_screen_two_color_wheel_selector").css('-webkit-transform') ||  $("#mmd_screen_two_color_wheel_selector").css('-moz-transform') ||  $("#mmd_screen_two_color_wheel_selector").css('-o-transform');
	    var values = matrix.split('(')[1].split(')')[0].split(',');
	    var coord = {
	        a: values[0],
	        b: values[1]
	    };

	    var angle = Math.round(Math.atan2(coord.b, coord.a) * (180/Math.PI));

		$({deg: 360+angle}).animate(
			{deg: 290},
			{
				duration: 500,
				step: function(n, fx){
					$("#mmd_screen_two_color_wheel_selector").css({
						'transform':'rotate('+n+'deg)',
						'-webkit-transform':'rotate('+n+'deg)',
						'-moz-transform':'rotate('+n+'deg)',
						'-o-transform':'rotate('+n+'deg)',
						'-mstransform':'rotate('+n+'deg)'
					});
				},
				complete: function(){
					$("#mmd_screen_two_color_wheel_selector").css({
						'transform':'rotate(290deg)',
						'-webkit-transform':'rotate(290deg)',
						'-moz-transform':'rotate(290deg)',
						'-o-transform':'rotate(290deg)',
						'-ms-transform':'rotate(290deg)'
					});
				}
			}
		);

		$('#mmd_scree_two_black_car, #mmd_scree_two_green_car, #mmd_scree_two_grey_car, #mmd_scree_two_yellow_car').animate({'opacity':'0'}, 500);
		$('#mmd_scree_two_purple_car').animate({'opacity':'1'}, 500);
	}

	function executeYellowRoation(){
		var matrix = $("#mmd_screen_two_color_wheel_selector").css('transform') ||  $("#mmd_screen_two_color_wheel_selector").css('-webkit-transform') ||  $("#mmd_screen_two_color_wheel_selector").css('-moz-transform') ||  $("#mmd_screen_two_color_wheel_selector").css('-o-transform');
	    var values = matrix.split('(')[1].split(')')[0].split(',');
	    var coord = {
	        a: values[0],
	        b: values[1]
	    };

	    var angle = Math.round(Math.atan2(coord.b, coord.a) * (180/Math.PI));

		$({deg: angle}).animate(
			{deg: -0},
			{
				duration: 500,
				step: function(n, fx){
					$("#mmd_screen_two_color_wheel_selector").css({
						'transform':'rotate('+n+'deg)',
						'-webkit-transform':'rotate('+n+'deg)',
						'-moz-transform':'rotate('+n+'deg)',
						'-o-transform':'rotate('+n+'deg)',
						'-mstransform':'rotate('+n+'deg)'
					});
				},
				complete: function(){
					$("#mmd_screen_two_color_wheel_selector").css({
						'transform':'rotate(0deg)',
						'-webkit-transform':'rotate(0deg)',
						'-moz-transform':'rotate(-deg)',
						'-o-transform':'rotate(0deg)',
						'-ms-transform':'rotate(0deg)'
					});
				}
			}
		);

		$('#mmd_scree_two_black_car, #mmd_scree_two_green_car, #mmd_scree_two_grey_car, #mmd_scree_two_purple_car').animate({'opacity':'0'}, 500);
		$('#mmd_scree_two_yellow_car').animate({'opacity':'1'}, 500);
	}

	var  rotation0 = null, rotation1 = null, rotation2 = null, rotation3 = null, rotation4 = null;

	function executeWheelColorSelectorAnimation(){

		window.clearTimeout(rotation0);
		rotation0 = window.setTimeout(function(){

			executeGreyRoation();
		}, 0);

		window.clearTimeout(rotation1);
		rotation1 = window.setTimeout(function(){

			executeBlackRotation();
		}, 1500);

		window.clearTimeout(rotation2);
		rotation2 = window.setTimeout(function(){
			executeGreenRotation();
		}, 3000);

		window.clearTimeout(rotation3);
		rotation3 = window.setTimeout(function(){
			executePurpleRotation();
		}, 4500);

		window.clearTimeout(rotation4);
		rotation4 = window.setTimeout(function(){
			executeYellowRoation();
		}, 6000);
	}

	function executeAnimationScreenTwo(){

		$('#mmd_screen_two_cars_container').animate({
			'left':'360px'
		}, {
			duration: 2000
		});

		$({deg: 0}).animate(
			{deg: -1442},
			{
				duration: 2000,
				step: function(n){
					$(".mmd_screen_two_car_wheel").css({
						'transform':'rotate('+n+'deg)',
						'-webkit-transform':'rotate('+n+'deg)',
						'-moz-transform':'rotate('+n+'deg)',
						'-ms-transform':'rotate('+n+'deg)'
					});
				}
			}
		);

		$('#mmd_screen_two_color_wheel_container').delay(1500).animate({
			'opacity':'1'
		}, {
			duration: 400
		});

		$('#mmd_screen_two_title').delay(1600).animate({
			'opacity':'1'
		}, {
			duration: 400
		});

		$('#mmd_screen_two_color_wheel_center_circle_container').delay(1600).animate({
			'opacity':'1'
		}, {
			duration: 700,
			complete: function(){
				executeWheelColorSelectorAnimation();

				// if auto = true
				if( window.isInAutomaticAnimation === true){

					window.clearTimeout(window.mainTimer);
					window.mainTimer = window.setTimeout(function(){
						executeLeaveScreenTwoAnimation(2);
					}, 10000);
				}
			}
		});
	}

	function clearRoatationTimer(){
		window.clearTimeout(rotation0);
		window.clearTimeout(rotation1);
		window.clearTimeout(rotation2);
		window.clearTimeout(rotation3);
		window.clearTimeout(rotation4);

		window.isInAutomaticAnimation = false;
	}

	function initAnimationScreenTwo(){
		// init automatic animattion
		window.isInAutomaticAnimation = true;

		// init current screent
		window.currentScreen = 1;

		window.clearTimeout(window.mainTimer);

		// adding selection to timeline
		$('.mmd_selected_screen').removeClass('mmd_selected_screen');
		$('#mmd_timeline_square_one').addClass('mmd_selected_screen');

		// init Interraction screenTwo

		$(document).off('click', '#mmd_screen_two_color_wheel_yellow');
		$(document).on('click', '#mmd_screen_two_color_wheel_yellow', function(){

			clearRoatationTimer();

			dataLayer.push({
				"event": "uaevent",
				"eventCategory": "SlideShowTopProduct",
				"eventAction": "C4CACTUS::PERSONNALISATION::Click",
				"eventLabel": "Jaune"
			});

			executeYellowRoation();

			window.isInAutomaticAnimation = false;

			return false;
		});

		$(document).off('click', '#mmd_screen_two_color_wheel_grey');
		$(document).on('click', '#mmd_screen_two_color_wheel_grey', function(){

			clearRoatationTimer();

			dataLayer.push({
				"event": "uaevent",
				"eventCategory": "SlideShowTopProduct",
				"eventAction": "C4CACTUS::PERSONNALISATION::Click",
				"eventLabel": "Gris"
			});


			executeGreyRoation();

			window.isInAutomaticAnimation = false;

			return false;
		});

		$(document).off('click', '#mmd_screen_two_color_wheel_black');
		$(document).on('click', '#mmd_screen_two_color_wheel_black', function(){

			clearRoatationTimer();

			dataLayer.push({
				"event": "uaevent",
				"eventCategory": "SlideShowTopProduct",
				"eventAction": "C4CACTUS::PERSONNALISATION::Click",
				"eventLabel": "Noir"
			});

			executeBlackRotation();

			window.isInAutomaticAnimation = false;

			return false;
		});

		$(document).off('click', '#mmd_screen_two_color_wheel_green');
		$(document).on('click', '#mmd_screen_two_color_wheel_green', function(){

			clearRoatationTimer();

			dataLayer.push({
				"event": "uaevent",
				"eventCategory": "SlideShowTopProduct",
				"eventAction": "C4CACTUS::PERSONNALISATION::Click",
				"eventLabel": "Vert"
			});

			executeGreenRotation();

			window.isInAutomaticAnimation = false;

			return false;
		});

		$(document).off('click', '#mmd_screen_two_color_wheel_purple');
		$(document).on('click', '#mmd_screen_two_color_wheel_purple', function(){

			clearRoatationTimer();

			dataLayer.push({
				"event": "uaevent",
				"eventCategory": "SlideShowTopProduct",
				"eventAction": "C4CACTUS::PERSONNALISATION::Click",
				"eventLabel": "Violet"
			});

			executePurpleRotation();

			window.isInAutomaticAnimation = false;

			return false;
		});

		// init animationScreenTwo
		$('#mmd_screen_two_title').css({
			'opacity':'0',
			'left':'234px'
		});

		$('#mmd_screen_two_color_wheel_container').css({
			'opacity':'0',
			'left':'260px'
		});

		$('#mmd_screen_two_color_wheel_center_circle_container').css({
			'opacity':'0'
		});

		$('#mmd_screen_two_cars_container').css({
			'left':'1080px'
		})

		$('.mmd_screen_two_car').css({
			'opacity':'0'
		});
		$('#mmd_scree_two_yellow_car').css({
			'opacity':'1'
		});

		$('#mmd_scree_two_yellow_car').addClass('mmd_selected_color_car');

		$('.mmd_screen_two_car_wheel').css({
			'-webkit-transform': 'rotate(0deg)',
			'-moz-transform': 'rotate(0deg)',
			'-o-transform': 'rotate(0deg)',
			'-ms-transform': 'rotate(0deg)',
			'transform': 'rotate(0deg)'
		});

		// execute animateScreentwo
		executeAnimationScreenTwo();
	}

	function executeAnimationScreenThree(){
		$('#mmd_screen_three_car').animate({
			'left':'452px',
			'top':'12px',
			'width':'458px',
			'height':'212px'
		}, {
			duration: 1300
		});

		$({deg: 0}).animate(
			{deg: -1080},
			{
				duration: 1300,
				step: function(n){
					$(".mmd_screen_three_wheel_content").css({
						'transform':'rotate('+n+'deg)',
						'-webkit-transform':'rotate('+n+'deg)',
						'-moz-transform':'rotate('+n+'deg)',
						'-ms-transform':'rotate('+n+'deg)'
					});
				}
			}
		);

		$('#mmd_screen_three_title_part_one').delay(900).animate({
			'opacity':1
		}, {
			duration: 600
		});

		$('#mmd_screen_three_title_part_two').delay(1100).animate({
			'opacity':1
		}, {
			duration: 600
		});

		$('#mmd_screen_three_title_part_three').delay(1300).animate({
			'opacity':1
		}, {
			duration: 600
		});

		$('#mmd_screen_three_tiple_icons').delay(1500).animate({
			'opacity':1
		}, {
			duration: 600
		});

		$('#mmd_screen_three_triple_icon_title').delay(2200).animate({
			'opacity':'1'
		}, {
			duration: 300
		});

		$('#mmd_screen_three_airbump_ball').delay(2400).animate({
				'top':'84px',
				'left':'312px',
				'width':'42px',
				'height':'42px'
			}, 800, 'linear', function(){

				$(this).animate({
					'width':'110px',
					'height':'110px',
					'top':'450px',
					'left':'400px'
				}, 800, 'linear');
			});

		$('#mmd_screen_three_car_airbump_choco').delay(3600).animate({'opacity':'1'}, 300, 'linear');
		$('#mmd_screen_three_car_airbump_dune').delay(5600).animate({'opacity':'1'}, 300, 'linear');
		$('#mmd_screen_three_car_airbump_grey').delay(7600).animate({'opacity':'1'}, 300, 'linear');

		$('#mmd_screen_three_car_airbump_choco, #mmd_screen_three_car_airbump_dune, #mmd_screen_three_car_airbump_grey').delay(9600).animate({'opacity':'0'}, 300, 'linear');

		$('#mmd_screen_three_car_airbump_choco').delay(11600).animate({'opacity':'1'}, 300, 'linear');
		$('#mmd_screen_three_car_airbump_dune').delay(13600).animate({'opacity':'1'}, 300, 'linear');
		$('#mmd_screen_three_car_airbump_grey').delay(14600).animate({'opacity':'1'}, 300, 'linear', function(){

			if(window.isInAutomaticAnimation === true){

				window.clearTimeout(window.mainTimer);
				window.mainTimer = window.setTimeout(function(){
					executeLeaveScreenThree(3);
				}, 1400);
			}
		});
	}

	function initAnimationScreenThree(){
		// init automatic animattion
		window.isInAutomaticAnimation = true;

		// init current screent
		window.currentScreen = 2;

		window.clearTimeout(window.mainTimer);

		// adding selection to timeline
		$('.mmd_selected_screen').removeClass('mmd_selected_screen');
		$('#mmd_timeline_square_two').addClass('mmd_selected_screen');

		// init animation screen thee
		$('#mmd_screen_three').css({'visibility':'visible'});

		$('#mmd_screen_three_title_part_one').css({
			'opacity':'0',
			'left':'0'
		});

		$('#mmd_screen_three_title_part_two').css({
			'opacity':'0',
			'left':'0'
		});

		$('#mmd_screen_three_title_part_three').css({
			'opacity':'0',
			'left':'0'
		});

		$('#mmd_screen_three_tiple_icons').css({
			'opacity':'0',
			'left':'194px'
		});

		$('#mmd_screen_three_triple_icon_title').css({
			'opacity':'0'
		});

		$('#mmd_screen_three_car').css({
			'left':'1080px',
			'width':'431px',
			'height':'198px',
			'top': '0'
		});

		// reset des roues
		$('.mmd_screen_three_wheel_content').css({
			'-webkit-transform':'rotate(0deg)',
			'-moz-transform':'rotate(0deg)',
			'-o-transform':'rotate(0deg)',
			'-ms-transform':'rotate(0deg)',
			'transform':'rotate(0deg)'
		});

		// Attention a l'exception IE
		var isIE_mmd1404 = (navigator.userAgent.indexOf("MSIE") != -1 || navigator.userAgent.indexOf("Trident") != -1);
		
		if(isIE_mmd1404 === true){
			$('#mmd_screen_three_car').addClass('mmd_screen_three_car_for_ei9');
			$('.mmd_screen_three_wheel').css({'display':'none'});
		}

		$('#mmd_screen_three_airbump_ball').css({
			'width':'24px',
			'height':'24px',
			'left':'1080px',
			'top':'-200px'
		});

		$('.mmd_screen_car_airbump').css({'opacity':'0'});


		executeAnimationScreenThree();
	}

	function executeAnimationScreenfour(){

		$('#mmd_screen_four_sky').animate({'opacity':'1'}, 400, 'linear');
		$('#mmd_screen_four_sky_content').animate({'left':'-1008px'}, 12000, 'linear');

		$('#mmd_screen_four_car').animate({'left':'182px'}, 1700);

		$('#mmd_screen_four_wording_part_one').delay(900).animate({'opacity':'1'}, 600, 'linear');
		$('#mmd_screen_four_wording_part_two').delay(1100).animate({'opacity':'1'}, 600, 'linear');
		$('#mmd_screen_four_wording_part_three').delay(1300).animate({'opacity':'1'}, 600, 'linear');

		$('#mmd_screen_four_wroding_rubben_text_container').delay(1500).animate({
			'width':'271px'
		}, {
			duration: 900,
			step: function(n, t){

				$('#mmd_screen_four_wording_rubben_text_background').css({
					'left': (-135 + n / 2)
				});
			},
			complete: function(){
				if(window.isInAutomaticAnimation === true){

					window.clearTimeout(window.mainTimer);
					window.mainTimer = window.setTimeout(function(){
						executeLeaveScreenFour(4);
					}, 9500);
				}
			}
		});
	}

	function initAnimationScreenFour(){
		// init automatic animattion
		window.isInAutomaticAnimation = true;

		// init current screent
		window.currentScreen = 3;

		window.clearTimeout(window.mainTimer);

		// adding selection to timeline
		$('.mmd_selected_screen').removeClass('mmd_selected_screen');
		$('#mmd_timeline_square_three').addClass('mmd_selected_screen');

		$('#mmd_screen_four').css({
			'visibility':'visible'
		});

		$('#mmd_screen_four_wording_part_one, #mmd_screen_four_wording_part_two, #mmd_screen_four_wording_part_three').css({
			'opacity':'0',
			'left':0
		});

		$('#mmd_screen_four_car').css({
			'left':'-900px'
		});

		$('#mmd_screen_four_sky').css({
			'left':'166px',
			'opacity':'0'
		});

		$('#mmd_screen_four_wording_rubben').css({
			'left':'0'
		})

		$('#mmd_screen_four_wroding_rubben_text_container').css({
			'width':'0px'
		});

		$('#mmd_screen_four_wording_rubben_text_background').css({
			'left':'-135px'
		});

		$('#mmd_screen_four_sky_content').css({
			'left':'0'
		});

		executeAnimationScreenfour();
	}

	function executeAnimationScreenFive(){

		$('#mmd_screen_five_tablets_container').animate({'opacity':'1'}, 900);

		$('.mmd_screen_five_tablet_left').delay(400).animate({'opacity':'0.4', 'left':'12px'}, 600);
		$('.mmd_screen_five_tablet_right').delay(400).animate({'opacity':'0.4', 'left':'136px'}, 600);

		$('#mmd_screen_five_wording_part_one').delay(800).animate({'opacity':'1', 'top':'0'}, 600);
		$('#mmd_screen_five_wording_part_two').delay(1000).animate({'opacity':'1', 'top':'26px'}, 600);
		$('#mmd_screen_five_wording_part_three').delay(1200).animate({'opacity':'1', 'bottom':'0'}, 600);
		$('#mmd_screen_five_btn_container').delay(1500).animate({'opacity':'1'}, 600, function(){
			autoplayTabletScreenFive();
		});
	}

	var tablet0 = null, tablet1 = null, tablet2 = null;
	var tablet3 = null, tablet4 = null, tablet5 = null;

	function clearAutoplayTabletScreenFive(){
		window.clearTimeout(tablet0);
		window.clearTimeout(tablet1);
		window.clearTimeout(tablet2);
		window.clearTimeout(tablet3);
		window.clearTimeout(tablet4);
		window.clearTimeout(tablet5);
	}

	function animTabletToRight(current, next){

		if( $('#mmd_screen_five_tablets_container').hasClass('disabled_screen_five_tablets_container') === false ){

			$('#mmd_screen_five_tablets_container').addClass('disabled_screen_five_tablets_container');

			$('.mmd_screen_five_tablet_left').animate({
				'left':'32px',
				'opacity':'0'
			}, 300, function(){
				$(this).css({'left':'12px'});
				$(this).animate({
					'opacity':'0.4'
				}, 300);
			});

			$('.mmd_screen_five_tablet_right').animate({
				'opacity':'0'
			}, 300, function(){
				$(this).animate({
					'opacity':'0.4'
				}, 300);
			});

			$('.mmd_screen_five_tablet_main').animate({
				'left':'0',
				'opacity':'0.4'
			}, 300, function(){
				$(this).css({
					'opacity':'0',
					'left':'34px'
				});
				$(this).find('.mmd_screen_five_mmd_tablet_'+current).css({
					'display':'none'
				});
			});

			$('.mmd_screen_five_tablet_next').find('.mmd_screen_five_mmd_tablet_'+next).css({'display':'block'});
			$('.mmd_screen_five_tablet_next').css({'opacity':'0.4'});
			$('.mmd_screen_five_tablet_next').animate({
				'left':'32px',
				'opacity':'1'
			}, 300, function(){

				$('.mmd_screen_five_tablet_main').find('.mmd_screen_five_mmd_tablet_'+next).css({'display':'block'});
				$('.mmd_screen_five_tablet_main').delay(50).css({
					'opacity':'1'
				});

				$('.mmd_screen_five_tablet_next').delay(60).css({
					'left':'190px',
					'opacity':'0'
				});				
				$('.mmd_screen_five_tablet_next').delay(60).find('.mmd_screen_five_mmd_tablet_'+next).css({'display':'none'});
				$('#mmd_screen_five_tablets_container').delay(70).removeClass('disabled_screen_five_tablets_container');
			});
		}
	}

	function animTabletToLeft(current, next){
		if( $('#mmd_screen_five_tablets_container').hasClass('disabled_screen_five_tablets_container') === false ){
			$('#mmd_screen_five_tablets_container').addClass('disabled_screen_five_tablets_container');

			$('.mmd_screen_five_tablet_left').animate({
				'opacity':'0'
			}, 300, function(){
				$(this).animate({
					'opacity':'0.4'
				}, 300);
			});

			$('.mmd_screen_five_tablet_right').animate({
				'left':'120px'
			}, 300, function(){
				$(this).css({'left':'136px'});
				$(this).animate({
					'opacity':'0.4'
				}, 300);
			});


			$('.mmd_screen_five_tablet_main').animate({
				'left':'68px',
				'opacity':'0.4'
			}, 300, function(){
				$(this).css({
					'opacity':'0',
					'left':'34px'
				});
				$(this).find('.mmd_screen_five_mmd_tablet_'+current).css({
					'display':'none'
				});
			});

			$('.mmd_screen_five_tablet_next').find('.mmd_screen_five_mmd_tablet_'+next).css({'display':'block'});
			$('.mmd_screen_five_tablet_next').css({
				'opacity':'0.4', 'left':'-126px'
			});
			$('.mmd_screen_five_tablet_next').animate({
				'left':'32px',
				'opacity':'1'
			}, 300, function(){

				$('.mmd_screen_five_tablet_main').find('.mmd_screen_five_mmd_tablet_'+next).css({'display':'block'});
				$('.mmd_screen_five_tablet_main').delay(50).css({
					'opacity':'1'
				});

				$('.mmd_screen_five_tablet_next').delay(60).css({
					'left':'190px',
					'opacity':'0'
				});				
				$('.mmd_screen_five_tablet_next').delay(60).find('.mmd_screen_five_mmd_tablet_'+next).css({'display':'none'});
				$('#mmd_screen_five_tablets_container').delay(70).removeClass('disabled_screen_five_tablets_container');
			});
		}
	}

	function autoplayTabletScreenFive(){

		window.clearTimeout(tablet0);
		tablet0 = window.setTimeout(function(){

			$('#mmd_screen_five_btn_climate').find('.mmd_screen_five_btn_text_container').css({'display':'none'});
			$('#mmd_screen_five_btn_climate').removeClass('mmd_screen_five_btn_selected');

			$('#mmd_screen_five_btn_help').find('.mmd_screen_five_btn_text_container').css({'display':'block'});
			$('#mmd_screen_five_btn_help').addClass('mmd_screen_five_btn_selected');

			animTabletToRight('climate', 'help');

		}, 2000);

		window.clearTimeout(tablet1);
		tablet1 = window.setTimeout(function(){

			$('#mmd_screen_five_btn_help').find('.mmd_screen_five_btn_text_container').css({'display':'none'});
			$('#mmd_screen_five_btn_help').removeClass('mmd_screen_five_btn_selected');

			$('#mmd_screen_five_btn_multimedia').find('.mmd_screen_five_btn_text_container').css({'display':'block'});
			$('#mmd_screen_five_btn_multimedia').addClass('mmd_screen_five_btn_selected');

			animTabletToRight('help', 'multimedia');

		}, 4000);

		window.clearTimeout(tablet2);
		tablet2 = window.setTimeout(function(){

			$('#mmd_screen_five_btn_multimedia').find('.mmd_screen_five_btn_text_container').css({'display':'none'});
			$('#mmd_screen_five_btn_multimedia').removeClass('mmd_screen_five_btn_selected');

			$('#mmd_screen_five_btn_navigation').find('.mmd_screen_five_btn_text_container').css({'display':'block'});
			$('#mmd_screen_five_btn_navigation').addClass('mmd_screen_five_btn_selected');

			animTabletToRight('multimedia', 'navigation');

		}, 6000);

		window.clearTimeout(tablet3);
		tablet3 = window.setTimeout(function(){

			$('#mmd_screen_five_btn_navigation').find('.mmd_screen_five_btn_text_container').css({'display':'none'});
			$('#mmd_screen_five_btn_navigation').removeClass('mmd_screen_five_btn_selected');

			$('#mmd_screen_five_btn_params').find('.mmd_screen_five_btn_text_container').css({'display':'block'});
			$('#mmd_screen_five_btn_params').addClass('mmd_screen_five_btn_selected');

			animTabletToRight('navigation', 'params');
			
		}, 8000);

		window.clearTimeout(tablet4);
		tablet4 = window.setTimeout(function(){

			$('#mmd_screen_five_btn_params').find('.mmd_screen_five_btn_text_container').css({'display':'none'});
			$('#mmd_screen_five_btn_params').removeClass('mmd_screen_five_btn_selected');

			$('#mmd_screen_five_btn_phone').find('.mmd_screen_five_btn_text_container').css({'display':'block'});
			$('#mmd_screen_five_btn_phone').addClass('mmd_screen_five_btn_selected');

			animTabletToRight('params', 'phone');
			
		}, 10000);


		if(window.isInAutomaticAnimation === true){

			window.clearTimeout(window.mainTimer);
			window.mainTimer = window.setTimeout(function(){
				
				executeLeaveScreenFive(5);
			}, 14000);
		}
	}

	function displayTabletScreenFive(next, current){

		clearAutoplayTabletScreenFive();
		window.isInAutomaticAnimation = false;

		var positions = {
			'climate':0,
			'help':1,
			'multimedia':2,
			'navigation':3,
			'params':4,
			'connect':5,
			'phone':6
		};

		positions[current] - positions[next] > 0 ? animTabletToLeft(current, next) : animTabletToRight(current, next);
	}

	function initAnimationScreenFive(){
		// init automatic animattion
		window.isInAutomaticAnimation = true;

		// init current screent
		window.currentScreen = 4;

		window.clearTimeout(window.mainTimer);

		// adding selection to timeline
		$('.mmd_selected_screen').removeClass('mmd_selected_screen');
		$('#mmd_timeline_square_four').addClass('mmd_selected_screen');

		$('#mmd_screen_five').css({
			'visibility':'visible'
		});

		$('#mmd_screen_five_wording_container').css({'left':'182px'});

		$('#mmd_screen_five_wording_part_one').css({'opacity':'0', 'top':'-10px', 'left':'0px'});
		$('#mmd_screen_five_wording_part_two').css({'opacity':'0', 'top':'16px', 'left':'0px'});
		$('#mmd_screen_five_wording_part_three').css({'opacity':'0','bottom':'10px', 'left':'0px'});

		$('#mmd_screen_five_tablets_container').css({'opacity':'0', 'left':'568px'});

		$('.mmd_screen_five_tablet_left').css({
			'opacity':'0',
			'left':'28px'
		});

		$('.mmd_screen_five_tablet_right').css({
			'opacity':'0',
			'left':'116px'
		});

		$('#mmd_screen_five_btn_container').css({
			'opacity':'0',
			'left':'208px'
		});

		$('.mmd_screen_five_btn_selected').find('.mmd_screen_five_btn_text_container').css({'display':'none'});
		$('.mmd_screen_five_btn_selected').removeClass('mmd_screen_five_btn_selected');

		$('#mmd_screen_five_btn_climate').find('.mmd_screen_five_btn_text_container').css({'display':'block'});
		$('#mmd_screen_five_btn_climate').addClass('mmd_screen_five_btn_selected');

		$('.mmd_screen_five_tablet_img').css({'display':'none'});
		$('.mmd_screen_five_tablet_main').find('.mmd_screen_five_mmd_tablet_climate').css({'display':'block'});

		var mapTablet = {
			'climate':'CLIMATISATION',
			'help': 'AIDES A lA CONDUITE',
			'multimedia':'MULTIMEDIA',
			'navigation':'NAVIGATION',
			'params':'PARAMETRES',
			'connect':'CITROEN MULTICITY CONNECT',
			'phone':'TELEPHONE'
		};

		// initilisation interractions
		$(document).off('click', '.mmd_screen_five_btn');
		$(document).on('click', '.mmd_screen_five_btn', function(){

			if($(this).is($('.mmd_screen_five_btn_selected')) === false){

				dataLayer.push({
					"event": "uaevent",
					"eventCategory": "SlideShowTopProduct",
					"eventAction": "C4CACTUS::Tablet::Click",
					"eventLabel": mapTablet[$(this).attr('id').split('_')[4]]
				});

				var current = $('.mmd_screen_five_btn_selected').attr('id').split('_')[4];

				$('.mmd_screen_five_btn_selected').find('.mmd_screen_five_btn_text_container').css({'display':'none'});
				$('.mmd_screen_five_btn_selected').removeClass('mmd_screen_five_btn_selected');

				$(this).find('.mmd_screen_five_btn_text_container').css({'display':'block'});
				$(this).addClass('mmd_screen_five_btn_selected');

				displayTabletScreenFive($(this).attr('id').split('_')[4], current);
			}

			return false;
		});


		executeAnimationScreenFive();
	}

	var digitCoundownInterval = null, queueCounter = 0;

	function clearDigitCountdownInterval(){
		window.clearInterval(digitCoundownInterval);
		queueCounter = 0;
	}

	function digitCountdownScreenSix(){

		var values = {
			firstDigit: {
				start: parseInt($('#mmd_screen_six_first_digit_container').find('.mmd_screen_six_digit_top_text').data('digitStartValue')),
				stop: parseInt($('#mmd_screen_six_first_digit_container').find('.mmd_screen_six_digit_top_text').data('digitStopValue'))
			},
			secondDigit: {
				start: parseInt($('#mmd_screen_six_second_digit_container').find('.mmd_screen_six_digit_top_text').data('digitStartValue')),
				stop: parseInt($('#mmd_screen_six_second_digit_container').find('.mmd_screen_six_digit_top_text').data('digitStopValue'))
			},
			thirdDigit: {
				start: parseInt($('#mmd_screen_six_third_digit_container').find('.mmd_screen_six_digit_top_text').data('digitStartValue')),
				stop: parseInt($('#mmd_screen_six_third_digit_container').find('.mmd_screen_six_digit_top_text').data('digitStopValue'))
			}
		};

		var countDownQueue = [];

		for( var i = values.firstDigit.start - 1; i > values.firstDigit.stop - 1; i--){

			for( var j = values.secondDigit.start - 1; j > values.secondDigit.stop - 1; j--){

				for( var k = values.thirdDigit.start - 1; k > values.thirdDigit.stop - 1; k--){

					countDownQueue.push([$('#mmd_screen_six_third_digit_container').find('.mmd_screen_six_digit_top_text'), k]);
					countDownQueue.push([$('#mmd_screen_six_third_digit_container').find('.mmd_screen_six_digit_bottom_text'), k]);
				}

				countDownQueue.push([$('#mmd_screen_six_second_digit_container').find('.mmd_screen_six_digit_top_text'), j]);
				countDownQueue.push([$('#mmd_screen_six_second_digit_container').find('.mmd_screen_six_digit_bottom_text'), j]);
			}

			countDownQueue.push([$('#mmd_screen_six_first_digit_container').find('.mmd_screen_six_digit_top_text'), i]);
			countDownQueue.push([$('#mmd_screen_six_first_digit_container').find('.mmd_screen_six_digit_bottom_text'), i]);
		}

		queueCounter = 0;

		window.clearInterval(digitCoundownInterval);
		digitCoundownInterval = window.setInterval(function(){

			if( queueCounter < countDownQueue.length){
				countDownQueue[queueCounter][0].html(countDownQueue[queueCounter][1]);

				queueCounter = queueCounter + 1;
			}
			else {
				clearDigitCountdownInterval();
			}
		}, 0.5);
	}

	function executeAnimationScreenSix(){
		var digitCoundown = null;

		$('#mmd_screen_six_car').animate({
			'opacity':'1'
		}, 1200);

		$('#mmd_screen_six_wording_part_one').delay(400).animate({'opacity':'1'}, 600);
		$('#mmd_screen_six_wording_part_two').delay(600).animate({'opacity':'1'}, 600);

		$('#mmd_screen_six_digits_container').delay(800).animate({'opacity':'1'}, 600);

		window.clearTimeout(digitCoundown);
		digitCoundown = window.setTimeout(function(){

			digitCountdownScreenSix();

		}, 1400);

		$('#mmd_screen_six_wording_part_three').delay(2200).animate({'opacity':'1'}, 600);
		$('#mmd_screen_six_bottom_wording').delay(2400).animate({'opacity':'1'}, 600);

		$('#mmd_screen_six_pump').delay(2700).animate({'opacity':'1'}, 600);
		$('#mmd_screen_six_pump_mask').delay(3000).animate({'height':'178px'}, 1000);

		$('#mmd_screen_six_cta_container').delay(6000).css({'visibility':'visible'});
		$('#mmd_screen_six_fuel_container').delay(6600).animate({
			'opacity':'0'
		}, 600);

		$('#mmd_screen_six_cta_try').delay(7000).animate({
			'opacity':'1'
		}, 600);

		$('#mmd_screen_six_cta_setup').delay(7200).animate({
			'opacity':'1'
		}, 600);

		if(window.isInAutomaticAnimation === true){

			window.clearTimeout(window.mainTimer);
			window.mainTimer = window.setTimeout(function(){
				
				executeLeaveScreenSix(1);
			}, 18000);
		}
	}

	function initAnimationScreenSix(){
		// init automatic animattion
		window.isInAutomaticAnimation = true;

		// init current screent
		window.currentScreen = 5;

		window.clearTimeout(window.mainTimer);

		// adding selection to timeline
		$('.mmd_selected_screen').removeClass('mmd_selected_screen');
		$('#mmd_timeline_square_five').addClass('mmd_selected_screen');

		$('.mmd_screen_six_digit_to_bind').each(function(index, element){
			$(this).html($(this).data('digitStartValue'));
		});

		$('#mmd_screen_six').css({
			'visibility':'visible'
		});

		$('#mmd_screen_six_car').css({
			'opacity':'0',
			'left':'72px'
		});

		$('#mmd_screen_six_fuel_container').css({'opacity':'1'});

		$('#mmd_screen_six_wording_part_one').css({'opacity':'0', 'left':'0'});
		$('#mmd_screen_six_wording_part_two').css({'opacity':'0', 'left':'0'});
		$('#mmd_screen_six_wording_part_three').css({'opacity':'0', 'left':'0'});
		$('#mmd_screen_six_bottom_wording').css({'opacity':'0', 'left':'0'});

		$('#mmd_screen_six_digits_container').css({'opacity':'0', 'left':'0'});

		$('#mmd_screen_six_pump').css({'opacity':'0', 'left':'188px'});
		$('#mmd_screen_six_pump_mask').css({'height':'0'});

		$('#mmd_screen_six_cta_container').css({'visibility':'hidden'});
		$('.mmd_screen_six_cta').css({'left':'0', 'opacity':'0'});

		$(document).off('click', '#mmd_screen_six_cta_try');
		$(document).on('click', '#mmd_screen_six_cta_try', function(){
			dataLayer.push({
				"event": "uaevent",
				"eventCategory": "Showroom::AnimationTopProduit::Step5",
				"eventAction": "Configurator::c4cactus",
				"eventLabel": "Essayez-la !"
			});

			window.clearTimeout(window.mainTimer);
			window.isInAutomaticAnimation = false;
		});

		$(document).off('click', '#mmd_screen_six_cta_setup');
		$(document).on('click', '#mmd_screen_six_cta_setup', function(){
			dataLayer.push({
				"event": "uaevent",
				"eventCategory": "Showroom::AnimationTopProduit::Step5",
				"eventAction": "Forms::TestDrive::c4cactus",
				"eventLabel": "Configurez-la !"
			});

			window.clearTimeout(window.mainTimer);
			window.isInAutomaticAnimation = false;
		});

		executeAnimationScreenSix();
	}

	function nextScreenSwitcher(nextScreenIndex){
		switch(nextScreenIndex){
			case 1:
				initAnimationScreenTwo();
				break;
			case 2:
				initAnimationScreenThree();
				break;
			case 3:
				initAnimationScreenFour();
				break;
			case 4:
				initAnimationScreenFive();
				break;
			case 5:
				initAnimationScreenSix();
				break;
			default:
				break;
		}
	}

	function executeLeaveScreenSix(nextScreenIndex){

		clearDigitCountdownInterval();

		$('#mmd_screen_six_car').clearQueue().stop().animate({
			'left':'-1080px'
		}, 600, 'easeInSine');

		$('#mmd_screen_six_wording_part_one').clearQueue().stop().delay(100).animate({'left':'-1080px'}, 500, 'easeInSine');
		$('#mmd_screen_six_wording_part_two').clearQueue().stop().delay(100).animate({'left':'-1080px'}, 500, 'easeInSine');
		$('#mmd_screen_six_wording_part_three').clearQueue().stop().delay(100).animate({'left':'-1080px'}, 500, 'easeInSine');
		$('#mmd_screen_six_bottom_wording').clearQueue().stop().delay(100).animate({'left':'-1080px'}, 700, 'easeInSine');
		$('#mmd_screen_six_digits_container').clearQueue().stop().delay(100).animate({'left':'-1080px'}, 600, 'easeInSine');

		$('#mmd_screen_six_cta_try').clearQueue().stop().delay(300).animate({'left':'-1080px'}, 500, 'easeInSine');
		$('#mmd_screen_six_cta_setup').clearQueue().stop().delay(300).animate({'left':'-1080px'}, 600, 'easeInSine');
		$('#mmd_screen_six_pump').clearQueue().stop().delay(300).animate({'left':'-1600px'}, 600, 'easeInSine', function(){

			$('#mmd_screen_six').css({'visibility':'hidden'});
			nextScreenSwitcher(nextScreenIndex);
		});
	}

	function executeLeaveScreenFive(nextScreenIndex){

		clearAutoplayTabletScreenFive();

		$('#mmd_screen_five_wording_part_one').clearQueue().stop().animate({
			'left':'-1080px'
		}, 500, 'easeInSine');

		$('#mmd_screen_five_wording_part_two').clearQueue().stop().animate({
			'left':'-1080px'
		}, 600, 'easeInSine');

		$('#mmd_screen_five_wording_part_three').clearQueue().stop().animate({
			'left':'-1080px'
		}, 700, 'easeInSine');

		$('.mmd_screen_five_tablet_left').clearQueue().stop().animate({
			'opacity':'0'
		}, 500, 'easeInSine');

		$('.mmd_screen_five_tablet_right').clearQueue().stop().animate({
			'opacity':'0'
		}, 500, 'easeInSine');

		$('#mmd_screen_five_btn_container').clearQueue().stop().animate({
			'left':'-1080px'
		}, 1200, 'easeInSine');

		$('#mmd_screen_five_tablets_container').clearQueue().stop().delay(200).animate({
			'left':'-1080px'
		}, 900, 'easeInSine', function(){
			$('#mmd_screen_five').css({'visibility':'hidden'});
			nextScreenSwitcher(nextScreenIndex);
		});
	}

	function executeLeaveScreenFour(nextScreenIndex){
		$('#mmd_screen_four_sky').clearQueue().stop().animate({'left':'-1080px'}, 500, 'easeInSine');
		$('#mmd_screen_four_car').clearQueue().stop().animate({'left':'-1080px'}, 700, 'easeInSine');

		$('#mmd_screen_four_wording_part_one').clearQueue().stop().delay(200).animate({'left':'-1080px'}, 600, 'easeInSine');
		$('#mmd_screen_four_wording_part_two').clearQueue().stop().delay(250).animate({'left':'-1080px'}, 600, 'easeInSine');
		$('#mmd_screen_four_wording_part_three').clearQueue().stop().delay(300).animate({'left':'-1080px'}, 600, 'easeInSine');
		$('#mmd_screen_four_wording_rubben').clearQueue().stop().delay(350).animate({'left':'-1750px'}, 600, 'easeInSine', function(){

			$('#mmd_screen_four').css({'visibility':'hidden'});
			nextScreenSwitcher(nextScreenIndex);
		});
	}

	function executeLeaveScreenThree(nextScreenIndex){
		$('#mmd_screen_three_title_part_one, #mmd_screen_three_title_part_two, #mmd_screen_three_title_part_three').clearQueue().stop();
		$('#mmd_screen_three_tiple_icons').clearQueue().stop();
		$('#mmd_screen_three_car_airbump_choco, #mmd_screen_three_car_airbump_dune, #mmd_screen_three_car_airbump_grey').clearQueue().stop();

		$('#mmd_screen_three_title_part_one').clearQueue().stop().animate({
			'left':'-1080px'
		}, 600, 'easeInSine');

		$('#mmd_screen_three_title_part_two').clearQueue().stop().delay(100).animate({
			'left':'-1080px'
		}, 600, 'easeInSine');

		$('#mmd_screen_three_title_part_three').clearQueue().stop().delay(200).animate({
			'left':'-1080px'
		}, 600, 'easeInSine');

		$('#mmd_screen_three_tiple_icons').clearQueue().stop().delay(300).animate({
			'left':'-1080px'
		}, 600, 'easeInSine');

		$('#mmd_screen_three_car').clearQueue().stop().delay(550).animate({
			'left':'-1080px'
		}, 600, 'easeInSine', function(){

			$('#mmd_screen_three').css({'visibility':'hidden'});
			nextScreenSwitcher(nextScreenIndex);
		});
	}

	function executeLeaveScreenTwoAnimation(nextScreenIndex){
		clearRoatationTimer();

		$('#mmd_screen_two_color_wheel_container, #mmd_screen_two_title').animate({'left':'-1080px'}, 600, 'easeInSine');
		$('#mmd_screen_two_cars_container').delay(200).animate({'left':'-1080px'}, 600, 'easeInSine', function(){
			nextScreenSwitcher(nextScreenIndex);
		});
	}

	function switcherLeaverTimeline(nextScreenIndex){
		switch(window.currentScreen){
			case 1:
				executeLeaveScreenTwoAnimation(nextScreenIndex);
				break;
			case 2:
				executeLeaveScreenThree(nextScreenIndex);
				break;
			case 3:
				executeLeaveScreenFour(nextScreenIndex);
				break;
			case 4:
				executeLeaveScreenFive(nextScreenIndex);
				break;
			case 5:
				executeLeaveScreenSix(nextScreenIndex);
				break;
		}
	}

	function initTimelineInterraction(){

		$(document).off('click', '#mmd_left_timeline_arrow');
		$(document).on('click', '#mmd_left_timeline_arrow', function(){

			window.clearTimeout(window.mainTimer);
			window.isInAutomaticAnimation = false;

			var currentScreenIndex = window.currentScreen;
			var nextScreenIndex = currentScreen === 1 ? 5 : currentScreen - 1;

			switch(currentScreenIndex){
				case 1:
					dataLayer.push({
						"event": "uaevent",
						"eventCategory": "SlideShowTopProduct",
						"eventAction": "C4CACTUS::SlideShow::Previous",
						"eventLabel": "EFFICIENCE"
					});

					executeLeaveScreenTwoAnimation(nextScreenIndex);
					break;
				case 2:

					dataLayer.push({
						"event": "uaevent",
						"eventCategory": "SlideShowTopProduct",
						"eventAction": "C4CACTUS::SlideShow::Previous",
						"eventLabel": "PERSONNALISATION"
					});

					executeLeaveScreenThree(nextScreenIndex);
					break;
				case 3:

					dataLayer.push({
						"event": "uaevent",
						"eventCategory": "SlideShowTopProduct",
						"eventAction": "C4CACTUS::SlideShow::Previous",
						"eventLabel": "AIRBUMP"
					});

					executeLeaveScreenFour(nextScreenIndex);
					break;
				case 4:
					dataLayer.push({
						"event": "uaevent",
						"eventCategory": "SlideShowTopProduct",
						"eventAction": "C4CACTUS::SlideShow::Previous",
						"eventLabel": "BIENETRE"
					});

					executeLeaveScreenFive(nextScreenIndex);
					break;
				case 5:

					dataLayer.push({
						"event": "uaevent",
						"eventCategory": "SlideShowTopProduct",
						"eventAction": "C4CACTUS::SlideShow::Previous",
						"eventLabel": "TABLETTE"
					});

					executeLeaveScreenSix(nextScreenIndex);
					break;
				default:
					break;
			}

			return false;
		});

		$(document).off('click', '#mmd_right_timeline_arrow');
		$(document).on('click', '#mmd_right_timeline_arrow', function(){

			window.clearTimeout(window.mainTimer);
			window.isInAutomaticAnimation = false;

			var currentScreenIndex = window.currentScreen;
			var nextScreenIndex = currentScreen === 5 ? 1 : currentScreen + 1;

			switch(currentScreenIndex){
				case 1:
					dataLayer.push({
						"event": "uaevent",
						"eventCategory": "SlideShowTopProduct",
						"eventAction": "C4CACTUS::SlideShow::Next",
						"eventLabel": "AIRBUMP"
					});

					executeLeaveScreenTwoAnimation(nextScreenIndex);
					break;
				case 2:

					dataLayer.push({
						"event": "uaevent",
						"eventCategory": "SlideShowTopProduct",
						"eventAction": "C4CACTUS::SlideShow::Next",
						"eventLabel": "BIENETRE"
					});

					executeLeaveScreenThree(nextScreenIndex);
					break;
				case 3:

					dataLayer.push({
						"event": "uaevent",
						"eventCategory": "SlideShowTopProduct",
						"eventAction": "C4CACTUS::SlideShow::Next",
						"eventLabel": "TABLETTE"
					});

					executeLeaveScreenFour(nextScreenIndex);
					break;
				case 4:

					dataLayer.push({
						"event": "uaevent",
						"eventCategory": "SlideShowTopProduct",
						"eventAction": "C4CACTUS::SlideShow::Next",
						"eventLabel": "EFFICIENCE"
					});

					executeLeaveScreenFive(nextScreenIndex);
					break;
				case 5:

					dataLayer.push({
						"event": "uaevent",
						"eventCategory": "SlideShowTopProduct",
						"eventAction": "C4CACTUS::SlideShow::Next",
						"eventLabel": "PERSONNALISATION"
					});

					executeLeaveScreenSix(nextScreenIndex);
					break;
				default:
					break;
			}

			return false;
		});

		$(document).off('click', '.mmd_timeline_square');
		$(document).on('click', '.mmd_timeline_square', function(){

			var nextScreenIndex = 0;

			window.clearTimeout(window.mainTimer);
			window.isInAutomaticAnimation = false;

			if($(this).is($('.mmd_selected_screen')) === false){
				if ($(this).is($('#mmd_timeline_square_one'))){

					dataLayer.push({
						"event": "uaevent",
						"eventCategory": "SlideShowTopProduct",
						"eventAction": "C4CACTUS::SlideShow::Click",
						"eventLabel": "PERSONNALISATION"
					});

					nextScreenIndex = 1;
				}
				else if ($(this).is($('#mmd_timeline_square_two'))){

					dataLayer.push({
						"event": "uaevent",
						"eventCategory": "SlideShowTopProduct",
						"eventAction": "C4CACTUS::SlideShow::Click",
						"eventLabel": "AIRBUMP "
					});

					nextScreenIndex = 2;
				}
				else if ($(this).is($('#mmd_timeline_square_three'))){

					dataLayer.push({
						"event": "uaevent",
						"eventCategory": "SlideShowTopProduct",
						"eventAction": "C4CACTUS::SlideShow::Click",
						"eventLabel": "BIENETRE"
					});

					nextScreenIndex = 3;
				}
				else if ($(this).is($('#mmd_timeline_square_four'))){

					dataLayer.push({
						"event": "uaevent",
						"eventCategory": "SlideShowTopProduct",
						"eventAction": "C4CACTUS::SlideShow::Click",
						"eventLabel": "TABLETTE"
					});

					nextScreenIndex = 4;
				}
				else if ($(this).is($('#mmd_timeline_square_five'))){

					dataLayer.push({
						"event": "uaevent",
						"eventCategory": "SlideShowTopProduct",
						"eventAction": "C4CACTUS::SlideShow::Click",
						"eventLabel": "EFFICIENCE"
					});

					nextScreenIndex = 5;
				}

				switcherLeaverTimeline(nextScreenIndex);
			}

			return false;
		});

		$('.mmd_timeline_square_digit').on('mouseenter', function(){
			$(this).css({'color':'#fff'});
			$(this).parent('.mmd_timeline_square').addClass('mmd_timeline_square_hovered');
		});

		$('.mmd_timeline_square_digit').on('mouseleave', function(){
			$(this).css({'color':'#afadc3'});
			$(this).parent('.mmd_timeline_square').removeClass('mmd_timeline_square_hovered');
		});
	}

	var mmdAutostart = null;

	function initSreenOneInterraction(){

		$('#mmd_screen_start_btn').on('click', function(){

			window.clearTimeout(mmdAutostart);

			dataLayer.push({
				"event": "uaevent",
				"eventCategory": "Showroom::AnimationTopProduit::Index",
				"eventAction": "Start",
				"eventLabel": "Démarrez !"
			});

			$('#mmd_screen_one').fadeOut(300);
			
			initTimelineInterraction();
			initAnimationScreenTwo();

			return false;
		});
	}

	function startAnimation(){

		$('#mmd_screen_one_title').css({
			'opacity':'0'
		});

		$('#mmd_screen_start_btn').css({
			'opacity':'0'
		});

		$('#mmd_screen_one_car').css({
			'bottom':'-170px'
		});

		$('#mmd_screen_one_car').animate({
			'bottom':'0'
		}, {
			duration: 900
		});


		$('#mmd_screen_one_title').delay(800).animate({
			'opacity':'1'
		}, {
			duration: 800
		});

		$('#mmd_screen_start_btn').delay(1300).animate({
			'opacity':'1'
		}, {
			duration: 800
		});

		window.clearTimeout(mmdAutostart);
		mmdAutostart = window.setTimeout(function(){
			$('#mmd_screen_one').fadeOut(300);
			
			initTimelineInterraction();
			initAnimationScreenTwo();
		}, 7000);
	}

	if( (/Android|iPad/i).test(window.navigator.userAgent)){
		$('#mmd_wrapper').css({
			'marginLeft':'-40px'
		});
	}

	initWording();
	initSreenOneInterraction();

	startAnimation();

}).call(this);