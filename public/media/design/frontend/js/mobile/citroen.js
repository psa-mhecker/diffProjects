/*
// Underscore.js 1.4.4
// ===================

// > http://underscorejs.org
// > (c) 2009-2013 Jeremy Ashkenas, DocumentCloud Inc.
// > Underscore may be freely distributed under the MIT license.

*/
;(function(){var n=this,t=n._,r={},e=Array.prototype,u=Object.prototype,i=Function.prototype,a=e.push,o=e.slice,c=e.concat,l=u.toString,f=u.hasOwnProperty,s=e.forEach,p=e.map,h=e.reduce,v=e.reduceRight,d=e.filter,g=e.every,m=e.some,y=e.indexOf,b=e.lastIndexOf,x=Array.isArray,_=Object.keys,j=i.bind,w=function(n){return n instanceof w?n:this instanceof w?(this._wrapped=n,void 0):new w(n)};"undefined"!=typeof exports?("undefined"!=typeof module&&module.exports&&(exports=module.exports=w),exports._=w):n._=w,w.VERSION="1.4.4";var A=w.each=w.forEach=function(n,t,e){if(null!=n)if(s&&n.forEach===s)n.forEach(t,e);else if(n.length===+n.length){for(var u=0,i=n.length;i>u;u++)if(t.call(e,n[u],u,n)===r)return}else for(var a in n)if(w.has(n,a)&&t.call(e,n[a],a,n)===r)return};w.map=w.collect=function(n,t,r){var e=[];return null==n?e:p&&n.map===p?n.map(t,r):(A(n,function(n,u,i){e[e.length]=t.call(r,n,u,i)}),e)};var O="Reduce of empty array with no initial value";w.reduce=w.foldl=w.inject=function(n,t,r,e){var u=arguments.length>2;if(null==n&&(n=[]),h&&n.reduce===h)return e&&(t=w.bind(t,e)),u?n.reduce(t,r):n.reduce(t);if(A(n,function(n,i,a){u?r=t.call(e,r,n,i,a):(r=n,u=!0)}),!u)throw new TypeError(O);return r},w.reduceRight=w.foldr=function(n,t,r,e){var u=arguments.length>2;if(null==n&&(n=[]),v&&n.reduceRight===v)return e&&(t=w.bind(t,e)),u?n.reduceRight(t,r):n.reduceRight(t);var i=n.length;if(i!==+i){var a=w.keys(n);i=a.length}if(A(n,function(o,c,l){c=a?a[--i]:--i,u?r=t.call(e,r,n[c],c,l):(r=n[c],u=!0)}),!u)throw new TypeError(O);return r},w.find=w.detect=function(n,t,r){var e;return E(n,function(n,u,i){return t.call(r,n,u,i)?(e=n,!0):void 0}),e},w.filter=w.select=function(n,t,r){var e=[];return null==n?e:d&&n.filter===d?n.filter(t,r):(A(n,function(n,u,i){t.call(r,n,u,i)&&(e[e.length]=n)}),e)},w.reject=function(n,t,r){return w.filter(n,function(n,e,u){return!t.call(r,n,e,u)},r)},w.every=w.all=function(n,t,e){t||(t=w.identity);var u=!0;return null==n?u:g&&n.every===g?n.every(t,e):(A(n,function(n,i,a){return(u=u&&t.call(e,n,i,a))?void 0:r}),!!u)};var E=w.some=w.any=function(n,t,e){t||(t=w.identity);var u=!1;return null==n?u:m&&n.some===m?n.some(t,e):(A(n,function(n,i,a){return u||(u=t.call(e,n,i,a))?r:void 0}),!!u)};w.contains=w.include=function(n,t){return null==n?!1:y&&n.indexOf===y?n.indexOf(t)!=-1:E(n,function(n){return n===t})},w.invoke=function(n,t){var r=o.call(arguments,2),e=w.isFunction(t);return w.map(n,function(n){return(e?t:n[t]).apply(n,r)})},w.pluck=function(n,t){return w.map(n,function(n){return n[t]})},w.where=function(n,t,r){return w.isEmpty(t)?r?null:[]:w[r?"find":"filter"](n,function(n){for(var r in t)if(t[r]!==n[r])return!1;return!0})},w.findWhere=function(n,t){return w.where(n,t,!0)},w.max=function(n,t,r){if(!t&&w.isArray(n)&&n[0]===+n[0]&&65535>n.length)return Math.max.apply(Math,n);if(!t&&w.isEmpty(n))return-1/0;var e={computed:-1/0,value:-1/0};return A(n,function(n,u,i){var a=t?t.call(r,n,u,i):n;a>=e.computed&&(e={value:n,computed:a})}),e.value},w.min=function(n,t,r){if(!t&&w.isArray(n)&&n[0]===+n[0]&&65535>n.length)return Math.min.apply(Math,n);if(!t&&w.isEmpty(n))return 1/0;var e={computed:1/0,value:1/0};return A(n,function(n,u,i){var a=t?t.call(r,n,u,i):n;e.computed>a&&(e={value:n,computed:a})}),e.value},w.shuffle=function(n){var t,r=0,e=[];return A(n,function(n){t=w.random(r++),e[r-1]=e[t],e[t]=n}),e};var k=function(n){return w.isFunction(n)?n:function(t){return t[n]}};w.sortBy=function(n,t,r){var e=k(t);return w.pluck(w.map(n,function(n,t,u){return{value:n,index:t,criteria:e.call(r,n,t,u)}}).sort(function(n,t){var r=n.criteria,e=t.criteria;if(r!==e){if(r>e||r===void 0)return 1;if(e>r||e===void 0)return-1}return n.index<t.index?-1:1}),"value")};var F=function(n,t,r,e){var u={},i=k(t||w.identity);return A(n,function(t,a){var o=i.call(r,t,a,n);e(u,o,t)}),u};w.groupBy=function(n,t,r){return F(n,t,r,function(n,t,r){(w.has(n,t)?n[t]:n[t]=[]).push(r)})},w.countBy=function(n,t,r){return F(n,t,r,function(n,t){w.has(n,t)||(n[t]=0),n[t]++})},w.sortedIndex=function(n,t,r,e){r=null==r?w.identity:k(r);for(var u=r.call(e,t),i=0,a=n.length;a>i;){var o=i+a>>>1;u>r.call(e,n[o])?i=o+1:a=o}return i},w.toArray=function(n){return n?w.isArray(n)?o.call(n):n.length===+n.length?w.map(n,w.identity):w.values(n):[]},w.size=function(n){return null==n?0:n.length===+n.length?n.length:w.keys(n).length},w.first=w.head=w.take=function(n,t,r){return null==n?void 0:null==t||r?n[0]:o.call(n,0,t)},w.initial=function(n,t,r){return o.call(n,0,n.length-(null==t||r?1:t))},w.last=function(n,t,r){return null==n?void 0:null==t||r?n[n.length-1]:o.call(n,Math.max(n.length-t,0))},w.rest=w.tail=w.drop=function(n,t,r){return o.call(n,null==t||r?1:t)},w.compact=function(n){return w.filter(n,w.identity)};var R=function(n,t,r){return A(n,function(n){w.isArray(n)?t?a.apply(r,n):R(n,t,r):r.push(n)}),r};w.flatten=function(n,t){return R(n,t,[])},w.without=function(n){return w.difference(n,o.call(arguments,1))},w.uniq=w.unique=function(n,t,r,e){w.isFunction(t)&&(e=r,r=t,t=!1);var u=r?w.map(n,r,e):n,i=[],a=[];return A(u,function(r,e){(t?e&&a[a.length-1]===r:w.contains(a,r))||(a.push(r),i.push(n[e]))}),i},w.union=function(){return w.uniq(c.apply(e,arguments))},w.intersection=function(n){var t=o.call(arguments,1);return w.filter(w.uniq(n),function(n){return w.every(t,function(t){return w.indexOf(t,n)>=0})})},w.difference=function(n){var t=c.apply(e,o.call(arguments,1));return w.filter(n,function(n){return!w.contains(t,n)})},w.zip=function(){for(var n=o.call(arguments),t=w.max(w.pluck(n,"length")),r=Array(t),e=0;t>e;e++)r[e]=w.pluck(n,""+e);return r},w.object=function(n,t){if(null==n)return{};for(var r={},e=0,u=n.length;u>e;e++)t?r[n[e]]=t[e]:r[n[e][0]]=n[e][1];return r},w.indexOf=function(n,t,r){if(null==n)return-1;var e=0,u=n.length;if(r){if("number"!=typeof r)return e=w.sortedIndex(n,t),n[e]===t?e:-1;e=0>r?Math.max(0,u+r):r}if(y&&n.indexOf===y)return n.indexOf(t,r);for(;u>e;e++)if(n[e]===t)return e;return-1},w.lastIndexOf=function(n,t,r){if(null==n)return-1;var e=null!=r;if(b&&n.lastIndexOf===b)return e?n.lastIndexOf(t,r):n.lastIndexOf(t);for(var u=e?r:n.length;u--;)if(n[u]===t)return u;return-1},w.range=function(n,t,r){1>=arguments.length&&(t=n||0,n=0),r=arguments[2]||1;for(var e=Math.max(Math.ceil((t-n)/r),0),u=0,i=Array(e);e>u;)i[u++]=n,n+=r;return i},w.bind=function(n,t){if(n.bind===j&&j)return j.apply(n,o.call(arguments,1));var r=o.call(arguments,2);return function(){return n.apply(t,r.concat(o.call(arguments)))}},w.partial=function(n){var t=o.call(arguments,1);return function(){return n.apply(this,t.concat(o.call(arguments)))}},w.bindAll=function(n){var t=o.call(arguments,1);return 0===t.length&&(t=w.functions(n)),A(t,function(t){n[t]=w.bind(n[t],n)}),n},w.memoize=function(n,t){var r={};return t||(t=w.identity),function(){var e=t.apply(this,arguments);return w.has(r,e)?r[e]:r[e]=n.apply(this,arguments)}},w.delay=function(n,t){var r=o.call(arguments,2);return setTimeout(function(){return n.apply(null,r)},t)},w.defer=function(n){return w.delay.apply(w,[n,1].concat(o.call(arguments,1)))},w.throttle=function(n,t){var r,e,u,i,a=0,o=function(){a=new Date,u=null,i=n.apply(r,e)};return function(){var c=new Date,l=t-(c-a);return r=this,e=arguments,0>=l?(clearTimeout(u),u=null,a=c,i=n.apply(r,e)):u||(u=setTimeout(o,l)),i}},w.debounce=function(n,t,r){var e,u;return function(){var i=this,a=arguments,o=function(){e=null,r||(u=n.apply(i,a))},c=r&&!e;return clearTimeout(e),e=setTimeout(o,t),c&&(u=n.apply(i,a)),u}},w.once=function(n){var t,r=!1;return function(){return r?t:(r=!0,t=n.apply(this,arguments),n=null,t)}},w.wrap=function(n,t){return function(){var r=[n];return a.apply(r,arguments),t.apply(this,r)}},w.compose=function(){var n=arguments;return function(){for(var t=arguments,r=n.length-1;r>=0;r--)t=[n[r].apply(this,t)];return t[0]}},w.after=function(n,t){return 0>=n?t():function(){return 1>--n?t.apply(this,arguments):void 0}},w.keys=_||function(n){if(n!==Object(n))throw new TypeError("Invalid object");var t=[];for(var r in n)w.has(n,r)&&(t[t.length]=r);return t},w.values=function(n){var t=[];for(var r in n)w.has(n,r)&&t.push(n[r]);return t},w.pairs=function(n){var t=[];for(var r in n)w.has(n,r)&&t.push([r,n[r]]);return t},w.invert=function(n){var t={};for(var r in n)w.has(n,r)&&(t[n[r]]=r);return t},w.functions=w.methods=function(n){var t=[];for(var r in n)w.isFunction(n[r])&&t.push(r);return t.sort()},w.extend=function(n){return A(o.call(arguments,1),function(t){if(t)for(var r in t)n[r]=t[r]}),n},w.pick=function(n){var t={},r=c.apply(e,o.call(arguments,1));return A(r,function(r){r in n&&(t[r]=n[r])}),t},w.omit=function(n){var t={},r=c.apply(e,o.call(arguments,1));for(var u in n)w.contains(r,u)||(t[u]=n[u]);return t},w.defaults=function(n){return A(o.call(arguments,1),function(t){if(t)for(var r in t)null==n[r]&&(n[r]=t[r])}),n},w.clone=function(n){return w.isObject(n)?w.isArray(n)?n.slice():w.extend({},n):n},w.tap=function(n,t){return t(n),n};var I=function(n,t,r,e){if(n===t)return 0!==n||1/n==1/t;if(null==n||null==t)return n===t;n instanceof w&&(n=n._wrapped),t instanceof w&&(t=t._wrapped);var u=l.call(n);if(u!=l.call(t))return!1;switch(u){case"[object String]":return n==t+"";case"[object Number]":return n!=+n?t!=+t:0==n?1/n==1/t:n==+t;case"[object Date]":case"[object Boolean]":return+n==+t;case"[object RegExp]":return n.source==t.source&&n.global==t.global&&n.multiline==t.multiline&&n.ignoreCase==t.ignoreCase}if("object"!=typeof n||"object"!=typeof t)return!1;for(var i=r.length;i--;)if(r[i]==n)return e[i]==t;r.push(n),e.push(t);var a=0,o=!0;if("[object Array]"==u){if(a=n.length,o=a==t.length)for(;a--&&(o=I(n[a],t[a],r,e)););}else{var c=n.constructor,f=t.constructor;if(c!==f&&!(w.isFunction(c)&&c instanceof c&&w.isFunction(f)&&f instanceof f))return!1;for(var s in n)if(w.has(n,s)&&(a++,!(o=w.has(t,s)&&I(n[s],t[s],r,e))))break;if(o){for(s in t)if(w.has(t,s)&&!a--)break;o=!a}}return r.pop(),e.pop(),o};w.isEqual=function(n,t){return I(n,t,[],[])},w.isEmpty=function(n){if(null==n)return!0;if(w.isArray(n)||w.isString(n))return 0===n.length;for(var t in n)if(w.has(n,t))return!1;return!0},w.isElement=function(n){return!(!n||1!==n.nodeType)},w.isArray=x||function(n){return"[object Array]"==l.call(n)},w.isObject=function(n){return n===Object(n)},A(["Arguments","Function","String","Number","Date","RegExp"],function(n){w["is"+n]=function(t){return l.call(t)=="[object "+n+"]"}}),w.isArguments(arguments)||(w.isArguments=function(n){return!(!n||!w.has(n,"callee"))}),"function"!=typeof/./&&(w.isFunction=function(n){return"function"==typeof n}),w.isFinite=function(n){return isFinite(n)&&!isNaN(parseFloat(n))},w.isNaN=function(n){return w.isNumber(n)&&n!=+n},w.isBoolean=function(n){return n===!0||n===!1||"[object Boolean]"==l.call(n)},w.isNull=function(n){return null===n},w.isUndefined=function(n){return n===void 0},w.has=function(n,t){return f.call(n,t)},w.noConflict=function(){return n._=t,this},w.identity=function(n){return n},w.times=function(n,t,r){for(var e=Array(n),u=0;n>u;u++)e[u]=t.call(r,u);return e},w.random=function(n,t){return null==t&&(t=n,n=0),n+Math.floor(Math.random()*(t-n+1))};var M={escape:{"&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#x27;","/":"&#x2F;"}};M.unescape=w.invert(M.escape);var S={escape:RegExp("["+w.keys(M.escape).join("")+"]","g"),unescape:RegExp("("+w.keys(M.unescape).join("|")+")","g")};w.each(["escape","unescape"],function(n){w[n]=function(t){return null==t?"":(""+t).replace(S[n],function(t){return M[n][t]})}}),w.result=function(n,t){if(null==n)return null;var r=n[t];return w.isFunction(r)?r.call(n):r},w.mixin=function(n){A(w.functions(n),function(t){var r=w[t]=n[t];w.prototype[t]=function(){var n=[this._wrapped];return a.apply(n,arguments),D.call(this,r.apply(w,n))}})};var N=0;w.uniqueId=function(n){var t=++N+"";return n?n+t:t},w.templateSettings={evaluate:/<%([\s\S]+?)%>/g,interpolate:/<%=([\s\S]+?)%>/g,escape:/<%-([\s\S]+?)%>/g};var T=/(.)^/,q={"'":"'","\\":"\\","\r":"r","\n":"n","	":"t","\u2028":"u2028","\u2029":"u2029"},B=/\\|'|\r|\n|\t|\u2028|\u2029/g;w.template=function(n,t,r){var e;r=w.defaults({},r,w.templateSettings);var u=RegExp([(r.escape||T).source,(r.interpolate||T).source,(r.evaluate||T).source].join("|")+"|$","g"),i=0,a="__p+='";n.replace(u,function(t,r,e,u,o){return a+=n.slice(i,o).replace(B,function(n){return"\\"+q[n]}),r&&(a+="'+\n((__t=("+r+"))==null?'':_.escape(__t))+\n'"),e&&(a+="'+\n((__t=("+e+"))==null?'':__t)+\n'"),u&&(a+="';\n"+u+"\n__p+='"),i=o+t.length,t}),a+="';\n",r.variable||(a="with(obj||{}){\n"+a+"}\n"),a="var __t,__p='',__j=Array.prototype.join,"+"print=function(){__p+=__j.call(arguments,'');};\n"+a+"return __p;\n";try{e=Function(r.variable||"obj","_",a)}catch(o){throw o.source=a,o}if(t)return e(t,w);var c=function(n){return e.call(this,n,w)};return c.source="function("+(r.variable||"obj")+"){\n"+a+"}",c},w.chain=function(n){return w(n).chain()};var D=function(n){return this._chain?w(n).chain():n};w.mixin(w),A(["pop","push","reverse","shift","sort","splice","unshift"],function(n){var t=e[n];w.prototype[n]=function(){var r=this._wrapped;return t.apply(r,arguments),"shift"!=n&&"splice"!=n||0!==r.length||delete r[0],D.call(this,r)}}),A(["concat","join","slice"],function(n){var t=e[n];w.prototype[n]=function(){return D.call(this,t.apply(this._wrapped,arguments))}}),w.extend(w.prototype,{chain:function(){return this._chain=!0,this},value:function(){return this._wrapped}})}).call(this);;/*-------------------- Google maps MOBILE 20/05/2015 --------------------*/
// the semi-colon before function invocation is a safety net against concatenated
// scripts and/or other plugins which may not be closed properly.
;
(function($, window, document, undefined) {

    // undefined is used here as the undefined global variable in ECMAScript 3 is
    // mutable (ie. it can be changed by someone else). undefined isn't really being
    // passed in so we can ensure the value of it is truly undefined. In ES5, undefined
    // can no longer be modified.

    // window and document are passed through as local variable rather than global
    // as this (slightly) quickens the resolution process and can be more efficiently
    // minified (especially when both are regularly referenced in your plugin).

    // Create the defaults once
    var pluginName = 'gLocator',

        /* Defaults */
        defaults = {

            /* Vars */
            config: {
                timeout: 10000
            },
            markers: [],
            clusterer: null,
            latest: {
                lat: null,
                lng: null,
                filters: null,
                zoom: null,
                type: 'geo'
            },
            state: 0,

            /* Callbacks */
            onLoad: function() {},
            onFilter: function() {},
            onList: function() {},
            onItemClick: function(storeId, storeRRDI) {},
            onDetails: function() {},
            onHashDetails: function() {},
            onGeoloc: function() {},
            onGeolocError: function() {}

        };

    // The actual plugin constructor
    function Plugin(element, options) {

        /* Static */
        this.element = element;
        this.timer = null;
        this.busyState = false;
        this.markers = [];

        // jQuery has an extend method which merges the contents of two or
        // more objects, storing the result in the first object. The first object
        // is generally empty as we don't want to alter the default options for
        // future instances of the plugin
        this.settings = $.extend({}, defaults, options);
        this._defaults = defaults;
        this._name = pluginName;
        this.init();
    };

    Plugin.prototype = {

        /* Initalisation */
        init: function() {

            var me = this,
                el = this.element,
                domid = el.getAttribute('data-dom') || 'map-canvas';

            me.loader = new Loader($('#' + domid));

            /* Vars */
            me.settings.base = domid;
            me.settings.dom = document.getElementById(domid);
            me.settings.wsConf = el.getAttribute('data-config'),
				me.settings.wsList = el.getAttribute('data-list'),
				me.settings.brandactivity = el.getAttribute('data-brand-activity'),
                me.settings.wsDetails = el.getAttribute('data-details');
            me.settings.imgPath = el.getAttribute('data-path') || '';
            me.settings.page = el.getAttribute('data-page');
            me.settings.version = el.getAttribute('data-version');
            me.settings.order = el.getAttribute('data-order');
            me.settings.area = el.getAttribute('data-area');
            me.settings.ztid = el.getAttribute('data-ztid');
            me.settings.mea = el.getAttribute('data-mea');
            me.settings.ds = ($(el).hasClass('ds'))?'true':'false';
            me.settings.meaPdvCount = 0;
            me.settings.meaDvnCount = 0;
			
		  //HACK
		  	//me.settings.ds = 'true';
		  

            if (me.settings.mea == "true") {
                me.settings.meaFile = "mea-";
            } else {
                me.settings.meaFile = "";
            }
            if (me.settings.ds == "true") {
                me.settings.dsFile = "ds-";
            } else {
                me.settings.dsFile = "";
            }
            me.settings.attribut = el.getAttribute('data-attribut') || '';

            me.$element = $(el);
            //			me.$locations = $(me.settings.dom).parent();
            me.currentmarkerid = [];

            me.initGeoPosButton();

            if (me.isBusy() || !me.settings.dom) return;
            me.busy(true);

            /* Service call */
            $.ajax({
                url: me.settings.wsConf,
                type: 'POST',
                dataType: 'json',
                cache: false,
                data: {
                    page: me.settings.page,
                    version: me.settings.version,
                    order: me.settings.order,
                    area: me.settings.area,
                    ztid: me.settings.ztid
                },
                success: function(response) {
                    me.busy(false);

				  //HACK getMapConfiguration ------
//					response = {"lat":48.8566,"lng":2.35222,"zoom":6,"timeout":2000,"country":"fr","autocomplete":true,"clusterer":false,"filter":"dvnpdv","search":{"step":10,"radius":40,"types":[{"label":"pdv","count":"5"},{"label":"dvn","count":"3"}]},"services":[{"code":"E","label":"Citro\u00ebn Select","TYPE_SERVICE":"L","ORDER_SERVICE":null,"ACTIF_SERVICE":"1","CODE_ID":"65","Picto":"<img src=\"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/E.png\" \/>","img":"\/design\/frontend\/images\/picto\/services\/E.png","big":"\/design\/frontend\/images\/picto\/services\/E_big.png","mobile":"\/design\/frontend\/images\/mobile\/picto\/services\/E_big.png","index":0,"service_icon_url":"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/white\/E.png"},{"code":"PR","label":"PIECES DE RECHANGE","TYPE_SERVICE":"A","ORDER_SERVICE":null,"ACTIF_SERVICE":"1","CODE_ID":"62","Picto":"<img src=\"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/PR.png\" \/>","img":"\/design\/frontend\/images\/picto\/services\/PR.png","big":"\/design\/frontend\/images\/picto\/services\/PR_big.png","mobile":"\/design\/frontend\/images\/mobile\/picto\/services\/PR_big.png","index":1,"service_icon_url":"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/white\/PR.png"},{"code":"VN","label":"VENTES DE VEHICULES NEUFS","TYPE_SERVICE":"A","ORDER_SERVICE":null,"ACTIF_SERVICE":"1","CODE_ID":"63","Picto":"<img src=\"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/VN.png\" \/>","img":"\/design\/frontend\/images\/picto\/services\/VN.png","big":"\/design\/frontend\/images\/picto\/services\/VN_big.png","mobile":"\/design\/frontend\/images\/mobile\/picto\/services\/VN_big.png","index":2,"service_icon_url":"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/white\/VN.png"},{"code":"VO","label":"VENTES DE VEHICULES D'OCCASION","TYPE_SERVICE":"A","ORDER_SERVICE":null,"ACTIF_SERVICE":"1","CODE_ID":"64","Picto":"<img src=\"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/VO.png\" \/>","img":"\/design\/frontend\/images\/picto\/services\/VO.png","big":"\/design\/frontend\/images\/picto\/services\/VO_big.png","mobile":"\/design\/frontend\/images\/mobile\/picto\/services\/VO_big.png","index":3,"service_icon_url":"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/white\/VO.png"},{"code":"APV","label":"APRES-VENTE","TYPE_SERVICE":"A","ORDER_SERVICE":null,"ACTIF_SERVICE":"1","CODE_ID":"61","Picto":"<img src=\"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/APV.png\" \/>","img":"\/design\/frontend\/images\/picto\/services\/APV.png","big":"\/design\/frontend\/images\/picto\/services\/APV_big.png","mobile":"\/design\/frontend\/images\/mobile\/picto\/services\/APV_big.png","index":4,"service_icon_url":"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/white\/APV.png"},{"code":"DS1","label":"DS World","TYPE_SERVICE":"I","ORDER_SERVICE":null,"ACTIF_SERVICE":"1","CODE_ID":"1000","Picto":"<img src=\"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/APV.png\" \/>","img":"\/design\/frontend\/images\/picto\/services\/APV.png","big":"\/design\/frontend\/images\/picto\/services\/APV_big.png","mobile":"\/design\/frontend\/images\/mobile\/picto\/services\/APV_big.png","index":5,"service_icon_url":"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/white\/APV.png"},{"code":"DS2","label":"DS Store","TYPE_SERVICE":"I","ORDER_SERVICE":null,"ACTIF_SERVICE":"1","CODE_ID":"1001","Picto":"<img src=\"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/APV.png\" \/>","img":"\/design\/frontend\/images\/picto\/services\/APV.png","big":"\/design\/frontend\/images\/picto\/services\/APV_big.png","mobile":"\/design\/frontend\/images\/mobile\/picto\/services\/APV_big.png","index":6,"service_icon_url":"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/white\/APV.png"},{"code":"DS3","label":"DS Salon","TYPE_SERVICE":"I","ORDER_SERVICE":null,"ACTIF_SERVICE":"1","CODE_ID":"1002","Picto":"<img src=\"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/APV.png\" \/>","img":"\/design\/frontend\/images\/picto\/services\/APV.png","big":"\/design\/frontend\/images\/picto\/services\/APV_big.png","mobile":"\/design\/frontend\/images\/mobile\/picto\/services\/APV_big.png","index":7,"service_icon_url":"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/white\/APV.png"},{"code":"DS4","label":"DS Distributeur","TYPE_SERVICE":"I","ORDER_SERVICE":null,"ACTIF_SERVICE":"1","CODE_ID":"1003","Picto":"<img src=\"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/APV.png\" \/>","img":"\/design\/frontend\/images\/picto\/services\/APV.png","big":"\/design\/frontend\/images\/picto\/services\/APV_big.png","mobile":"\/design\/frontend\/images\/mobile\/picto\/services\/APV_big.png","index":8,"service_icon_url":"http:\/\/media.citroen.fr\/design\/frontend\/images\/picto\/services\/white\/APV.png"}]};
//					console.log(response);
			  

					
					
                    $.extend(me.settings.config, response);

                    if (me.settings.latest.lat !== null && me.settings.latest.lng !== null && me.settings.latest.zoom !== null) {
                        me.settings.config.lat = me.settings.latest.lat;
                        me.settings.config.lng = me.settings.latest.lng;
                        me.settings.config.zoom = me.settings.latest.zoom;
                    }

                    var options = {
                        center: new google.maps.LatLng(me.settings.config.lat, me.settings.config.lng),
                        zoom: me.settings.config.zoom,
                        disableDefaultUI: true,
                        scaleControl: true,
                        streetViewControl: true,
                        panControl: true,
                        zoomControl: false,
                        mapTypeId: google.maps.MapTypeId.ROADMAP
                    };

                    /* Map init */

                    me.settings.map = new google.maps.Map(me.settings.dom, options);
                    me.settings.icon = {
                        url: me.settings.imgPath + 'design/frontend/images/illustrations/' + me.settings.meaFile + 'marker.png'
                    };
                    me.settings.iconHover = {
                        url: me.settings.imgPath + 'design/frontend/images/illustrations/' + me.settings.meaFile + 'markerHover.png'
                    };
                    me.settings.icon2 = {
                        url: me.settings.imgPath + 'design/frontend/images/illustrations/' + me.settings.meaFile + 'marker2.png'
                    };
                    me.settings.icon2Hover = {
                        url: me.settings.imgPath + 'design/frontend/images/illustrations/' + me.settings.meaFile + 'marker2Hover.png'
                    };

              /*if(me.settings.ds == "true"){
                  me.settings.icon3 = {
                      url: me.settings.imgPath + 'design/frontend/images/mobile/picto/' + me.settings.meaFile + me.settings.dsFile + 'marker1.png'
                  };
                  me.settings.icon3Hover = {
                      url: me.settings.imgPath + 'design/frontend/images/mobile/picto/' + me.settings.meaFile + me.settings.dsFile + 'marker1Hover.png'
                  };
                  me.settings.icon4 = {
                      url: me.settings.imgPath + 'design/frontend/images/mobile/picto/' + me.settings.meaFile + me.settings.dsFile + 'marker2.png'
                  };
                  me.settings.icon4Hover = {
                      url: me.settings.imgPath + 'design/frontend/images/mobile/picto/' + me.settings.meaFile + me.settings.dsFile + 'marker2Hover.png'
                  };
                  me.settings.icon5 = {
                      url: me.settings.imgPath + 'design/frontend/images/mobile/picto/' + me.settings.meaFile + me.settings.dsFile + 'marker3.png'
                  };
                  me.settings.icon5Hover = {
                      url: me.settings.imgPath + 'design/frontend/images/mobile/picto/' + me.settings.meaFile + me.settings.dsFile + 'marker3Hover.png'
                  };
                  me.settings.icon6 = {
                      url: me.settings.imgPath + 'design/frontend/images/mobile/picto/' + me.settings.meaFile + me.settings.dsFile + 'marker.png'
                  };
                  me.settings.icon6Hover = {
                      url: me.settings.imgPath + 'design/frontend/images/mobile/picto/' + me.settings.meaFile + me.settings.dsFile + 'markerHover.png'
                  };
                  me.settings.icon7 = {
                      url: me.settings.imgPath + 'design/frontend/images/mobile/picto/' + me.settings.meaFile + me.settings.dsFile + 'marker.png'
                  };
                  me.settings.icon7Hover = {
                      url: me.settings.imgPath + 'design/frontend/images/mobile/picto/' + me.settings.meaFile + me.settings.dsFile + 'markerHover.png'
                  };				  
				  
              }*/
                    me.settings.shadow = {
                        url: me.settings.imgPath + 'design/frontend/images/mobile/picto/marker-shadow.png',
                        anchor: new google.maps.Point(19, 35)
                    };
                    me.settings.shadowHover = {
                        url: me.settings.imgPath + 'design/frontend/images/mobile/picto/marker-shadowHover.png',
                        anchor: new google.maps.Point(19, 41)
                    };


                    /* Has default open item */
                    var hash = document.location.hash.substr(1),
                        open = me.markers[hash];
                    if (open) {
                        me.details(open);
                    };



                    var input = $(el).find('input[name="address"]').get(0);
                    /* Enable locations search with autocomplete module */
                    if (me.settings.config.autocomplete) {
                        var autocomplete = new google.maps.places.Autocomplete(input, {
                            componentRestrictions: {
                                country: me.settings.config.country
                            }
                        });

                        autocomplete.bindTo('bounds', me.settings.map);
                        google.maps.event.addListener(autocomplete, 'place_changed', function() {
                            var place = autocomplete.getPlace();

                            // Maj dataLayer
                            dataLayer[0].internalSearchKeyword = place.formatted_address;
                            dataLayer[0].internalSearchType = "pdv";

                            me.fitSearch(place);

                        });
                    };

                    /* Enable geocoder for custom search when button is clicked */
                    var geocoder = new google.maps.Geocoder();
                    $(el).on('submit', function(e) {
                        e.preventDefault();

                        // Maj dataLayer
                        dataLayer[0].internalSearchKeyword = input.value;
                        dataLayer[0].internalSearchType = "pdv";

                        var string = input.value;
                        geocoder.geocode({
                            'address': string
                        }, function(results, status) {

                            if (status == google.maps.GeocoderStatus.OK) {
                                var result = [];
                                

                                $('.locator fieldset input').addClass('searchdone');
                                if ($('.geoloc').hasClass('geolocdone')) {
                                    $('.geoloc').removeClass('geolocdone');
                                }

                                function getCountry(address) {
                                    for (var i = 0; i < address.length; i++) {
                                        if (address[i].types[0] === 'country') {
                                            return address[i].short_name.toLowerCase();
                                        }
                                    }
                                }

                                for (var i = 0; i < results.length; i++) {
                                    if ((me.settings.config.country === getCountry(results[i].address_components)) || (me.settings.config.country === 'ru')) {
                                        result.push(results[i]);
                                    }
                                }


                                var filledlist = $('.locations').find('.stores');
                                if (filledlist.hasClass('filled')) {
                                    filledlist.removeClass('filled');
                                    $('.locations .items').empty();
                                }

								
								for (var i = 0, len = me.settings.markers.length; i < len; i++) {
                                    me.settings.markers[i].setMap(null);
                                }
								if (result.length)
								{
                                 var currentlat = result[0].geometry.location.lat();
                                 var currentlng = result[0].geometry.location.lng();
								 me.list(currentlat, currentlng);
								}

                               
                                

                            }
                        });

                    });



                    /* Filters */
                    if (me.settings.config.services) {

                        var $filterTpl = $(me.settings.dom).parents('.locations').find('.filtersTpl'),
                            tpl = $filterTpl.html();
                        if (0 != $filterTpl.length) {

                            var compiledTemplate = _.template(tpl, {
                                services: me.settings.config.services,
                                base: me.settings.base
                            });

                            $(me.settings.dom).append(compiledTemplate).find('.mapFilters input').each(function() {
                                var root = $(this).parents('.mapFilters').get(0);
                                if (!root._inputs) root._inputs = [];

                                this._root = root;
                                root._inputs.push(this);

                            }).change(function() {

                                var ids = [];
                                $(this._root._inputs).each(function() {
                                    if (this.checked) ids.push(parseInt(this.value));
                                });
                                me.settings.latest.filters = ids;

                                /* Re do latest search */
                                me.filter(ids);

                            });

                            $(me.settings.dom).find('.mapFilters span').click(function() {
                                $(this).parent().toggleClass('open').find('ul').stop(true, false).slideToggle(250);
                            });

                        };

                    };

                    /* Callback */
                    me.settings.onLoad.call(me);

                }
            });

        },

        initGeoPosButton: function() {
            //redmine #3159
            var me = this,
                el = this.element,
                $geolocButton = $(el).find('.geoloc');
            /* Enable geolocation if enabled */
            if (navigator.geolocation) {
                /* Backup timer because browser doesen't trigger error when prompt is simply closed */
                var geolocTimer = 0,
                    geolocBackup = function() {

                        clearTimeout(geolocTimer);
                        me.busy(false);

                        /* Callback */
                        me.settings.onGeolocError.call(me);

                    },
                    checkGeoloc = function() {
                        // Empty previous list and marker
                        $('.locations').find('.stores').removeClass('filled');
                        $('.locations .items').empty();
                        for (var i = 0, len = me.settings.markers.length; i < len; i++) {
                            me.settings.markers[i].setMap(null);
                        }

                        if (me.isBusy()) return;
                        me.busy(true);
                        if (sessionStorage.getItem("position") === null) {

                            geolocTimer = window.setTimeout(function() {
                                geolocBackup();
                            }, 10000);

                            navigator.geolocation.getCurrentPosition(function(pos) {
                                clearTimeout(geolocTimer);
                                //$.fancybox.close();
                                me.busy(false);

                                var currentlat = pos.coords.latitude;
                                var currentlng = pos.coords.longitude;
                                me.list(currentlat, currentlng, 'geo');

                                posgeo = {
                                    latitude: pos.coords.latitude,
                                    longitude: pos.coords.longitude
                                }
                                sessionStorage.setItem('position', JSON.stringify(posgeo));
                                /* Callback */
                                me.settings.onGeoloc.call(me);

                            }, geolocBackup, {
                                enableHighAccuracy: false,
                                maximumAge: 60000,
                                timeout: 10000
                            });
                        } else {
                            var pos = JSON.parse(sessionStorage.getItem("position"));
                            me.busy(false);
                            me.list(pos.latitude, pos.longitude, 'geo');
                        }
                    }

                $geolocButton.click(checkGeoloc).css({
                    cursor: 'pointer'
                });
            } else {
                $geolocButton.css({
                    opacity: 0.25
                });
                checkGeoloc();
            }
            $('.geoloc').on('click', function() {
                $(this).addClass('geolocdone');
                if ($('.locator fieldset input').hasClass('searchdone')) {
                    $('.locator fieldset input').removeClass('searchdone')
                }
            });
        },
		getCodeFromIndex: function(index) { var code="";
											var me = this;
											var getcode = "";
												for(var ak=0; ak<me.settings.config.services.length; ak++){
													if (me.settings.config.services[ak].index==index) 
													getcode = me.settings.config.services[ak].code;
												}	
												return getcode;
		},
        /* Set markers on the map and activate clustering */
        setMarkers: function(markers) {
            /* Vars */
            var me = this,
                el = this.element;

            me.settings.markers = [];
            me.markers = [];

            /* Has clusterer */
            if (me.settings.config.clusterer) {
                /* Clear existing markers */
                if (me.settings.clusterer) {
                    me.settings.clusterer.clearMarkers();
                } else {
                    /* Initialize cluster manager */
                    var options = {
                        averageCenter: true,
                        gridSize: 25,
                        styles: [{
                            url: me.settings.imgPath + 'design/frontend/images/mobile/picto/cluster.png',
                            height: 30,
                            width: 37
                        }]
                    };
                    me.settings.clusterer = new MarkerClusterer(me.settings.map, me.settings.markers, options);
                };
            };

            if (markers !== null) {
                for (var i = 0, len = markers.length; i < len; i++) {
                    var data = markers[i];
					
					
					// ICI on cr�e une liste des services que l'on pourra tester
				var chaineServices =",";
				for(var zi=0; zi<data.services.length; zi++){
					//console.log('==> '+data.services[i]+']:' + me.getCodeFromIndex(data.services[i]));
					chaineServices=chaineServices+me.getCodeFromIndex(data.services[zi])+',';
				}
				 /*HACK*/
	/*			 
				console.log(data.name);	
				console.log(chaineServices);	
				console.log(data.services);
				*/
				
					

                    /* Has clusterer */
                    if (me.settings.config.clusterer) {
                        if('pdv' == data.type){
                            if('true' != me.settings.ds){
                                var icon = me.settings.icon;
                            } else {

									if(chaineServices.indexOf(',DS1,') !== -1){
										var icon = me.settings.icon3; 
										var iconRoll = me.settings.icon3Hover; 
									} else if(chaineServices.indexOf(',DS2,') !== -1){
										var icon = me.settings.icon4; 
										var iconRoll = me.settings.icon4Hover;
									} else if(chaineServices.indexOf(',DS3,') !== -1){
										var icon = me.settings.icon5; 
										var iconRoll = me.settings.icon5Hover;
									} else if(chaineServices.indexOf(',DS4,') !== -1){
										var icon = me.settings.icon6; 
										var iconRoll = me.settings.icon6Hover;
									} else{
										var icon = me.settings.icon7; 
										var iconRoll = me.settings.icon7Hover;
									}
                            }

                        } else {
                            if('true' != me.settings.ds){
                                var icon = me.settings.icon2;
                            } else {
												if(chaineServices.indexOf(',DS1,') !== -1){
											var icon = me.settings.icon3; 
											var iconRoll = me.settings.icon3Hover; 
										} else if(chaineServices.indexOf(',DS2,') !== -1){
											var icon = me.settings.icon4; 
											var iconRoll = me.settings.icon4Hover;
										} else if(chaineServices.indexOf(',DS3,') !== -1){
											var icon = me.settings.icon5; 
											var iconRoll = me.settings.icon5Hover;
										} else if(chaineServices.indexOf(',DS4,') !== -1){
											var icon = me.settings.icon6; 
											var iconRoll = me.settings.icon6Hover;
										} else{
											var icon = me.settings.icon7; 
											var iconRoll = me.settings.icon7Hover;
										}
                            }
                        }
                        var marker = new google.maps.Marker({
                            position: new google.maps.LatLng(data.lat, data.lng),
                            icon: icon,
                            shadow: me.settings.shadow
                        });
                    } else {
                        /* Create marker */
                        if('pdv' == data.type){
                            if('true' != me.settings.ds){
                                var icon = me.settings.icon;
                            } else {
        
		
										if(chaineServices.indexOf(',DS1,') !== -1){
															var icon = me.settings.icon3; 
															var iconRoll = me.settings.icon3Hover; 
														} else if(chaineServices.indexOf(',DS2,') !== -1){
															var icon = me.settings.icon4; 
															var iconRoll = me.settings.icon4Hover;
														} else if(chaineServices.indexOf(',DS3,') !== -1){
															var icon = me.settings.icon5; 
															var iconRoll = me.settings.icon5Hover;
														} else if(chaineServices.indexOf(',DS4,') !== -1){
															var icon = me.settings.icon6; 
															var iconRoll = me.settings.icon6Hover;
														} else{
															var icon = me.settings.icon7; 
															var iconRoll = me.settings.icon7Hover;
														}
		
                            }

                        } else {
                            if('true' != me.settings.ds){
                                var icon = me.settings.icon2;
                            } else {
										if(chaineServices.indexOf(',DS1,') !== -1){
																	var icon = me.settings.icon3; 
																	var iconRoll = me.settings.icon3Hover; 
																} else if(chaineServices.indexOf(',DS2,') !== -1){
																	var icon = me.settings.icon4; 
																	var iconRoll = me.settings.icon4Hover;
																} else if(chaineServices.indexOf(',DS3,') !== -1){
																	var icon = me.settings.icon5; 
																	var iconRoll = me.settings.icon5Hover;
																} else if(chaineServices.indexOf(',DS4,') !== -1){
																	var icon = me.settings.icon6; 
																	var iconRoll = me.settings.icon6Hover;
																} else{
																	var icon = me.settings.icon7; 
																	var iconRoll = me.settings.icon7Hover;
																}
							
							
                            }
                        }
                        var marker = new google.maps.Marker({
                            position: new google.maps.LatLng(data.lat, data.lng),
                            icon: icon,
                            shadow: me.settings.shadow,
                            map: me.settings.map
                        });
                    };

                    /* Store marker data */
                    marker._storeId = data.id;
					if('true' == me.settings.ds){
							marker._icon = icon;
							marker._iconHover = iconRoll;
						  }					
                    marker._storeRRDI = data.rrdi;
                    marker._storeName = data.name;
                    marker._type = data.type;
                    marker._services = data.services;
                    google.maps.event.addListener(marker, 'click', function() {
                        me.details(this);
                    });

                    google.maps.event.addListener(marker, 'mouseover', function() {
                        me.highlight(this, false, me.settings);
                    });
                    google.maps.event.addListener(marker, 'mouseout', function() {
                        me.downplay(this, false, me.settings);
                    });

                    /* Store marker */
                    me.settings.markers.push(marker);
                    me.markers[data.id] = marker;

                }
            }

            /* Has clusterer */
            if (me.settings.config.clusterer) {
                me.settings.clusterer.addMarkers(me.settings.markers);
            };

            /* Events */
            if (me.settings.latest.lat && me.settings.latest.lng) {
                me.list();
            };

        },

        filter: function(filters) {

            /* Vars */
            var me = this,
                el = this.element,
                output = [],
                markers = me.settings.config.markers;

            for (var i = markers.length; i--;) {
                for (var j = filters.length; j--;) {
                    if (-1 != markers[i].services.indexOf(filters[j])) {
                        output.push(markers[i]);
                        break;
                    };
                };
            };

            /* Reset markers */
            me.setMarkers(output);

            /* Callback */
            me.settings.onFilter.call(me);

            return output;

        },

        highlight: function(marker, markerOnly, instance) {
            var me = this;

		if('true' != me.settings.ds){
              var icon = ('pdv' == marker._type) ? instance.iconHover : instance.icon2Hover;
          } else {
              var icon = ('pdv' == marker._type) ? marker._iconHover : marker._iconHover;
          }
			
            marker.setIcon(icon);
            marker.setShadow(instance.shadowHover);
            if (marker._item && true != markerOnly) $(marker._item).addClass('hover');

        },
        downplay: function(marker, markerOnly, instance) {
            var icon = ('pdv' == marker._type) ? instance.icon : instance.icon2;
            marker.setIcon(icon);
            marker.setShadow(instance.shadow);
            if (marker._item && true != markerOnly) $(marker._item).removeClass('hover');
        },

        busy: function(is) {

            /* Vars */
            var me = this;

            /* Set state */
            me.busyState = is;

            /* Launch wait overlay timer if busy */
            if (is) {
                $(me.element).trigger('busy');
                me.loader.show();

                /* Hide wait overlay */
            } else {
                $(me.element).trigger('notbusy');
                me.loader.hide();

            }

        },
        isBusy: function() {

            /* Vars */
            var me = this;
            return me.busyState;

        },

        /* Clear results */
        clear: function() {

            $(this.settings.dom).parents('.locations').find('.stores .items').html('');

        },

        /* Find */
        find: function(lat, lng, type) {
            /* Vars */
            var me = this,
                el = me.element;

            /* Display if not displayed */
            $(me.settings.dom).parents('.locations').show();

            /* Refresh latest find if lat or lng missing */
            if (!lat || !lng) {
                var lat = me.settings.latest.lat,
                    lng = me.settings.latest.lng;
                type = me.settings.latest.type;
            } else {
                me.settings.latest.lat = lat;
                me.settings.latest.lng = lng;
                me.settings.latest.type = type;
            }

            /* Radian */
            function rad(x) {
                return x * Math.PI / 180;
            }

            var R = 6371,
                /* radius of earth in km */
                distances = [],
                markers = me.settings.markers;


            /* Calculate and store distances */
            for (var i = 0, len = markers.length; i < len; i++) {
                var that = markers[i]

                var mlat = that.position.lat(),
                    mlng = that.position.lng(),
                    dLat = rad(mlat - lat),
                    dLong = rad(mlng - lng),
                    a = Math.sin(dLat / 2) * Math.sin(dLat / 2) + Math.cos(rad(lat)) * Math.cos(rad(lat)) * Math.sin(dLong / 2) * Math.sin(dLong / 2),
                    c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a)),
                    d = R * c;

                that._km = d;
                distances.push(that);
            }

            /* Sorting by km and select nearest*/
            distances.sort(function(a, b) {
                return a._km - b._km;
            });
            /* Parse array to get firsts elements of type */
            var getType = function(type, count) {
                var selected = [];
                for (var i = 0, len = distances.length; i < len; i++) {
                    if (type == distances[i]._type) selected.push(i);
                    if (selected.length == count) break;
                }
                return selected;
            }

            /* If has privileged pattern */
            if (me.settings.config.search.types) {

                /* Privileged pattern */
                var privileged = [],
                    searchType;
                for (var i = 0, len = me.settings.config.search.types.length; i < len; i++) {
                    searchType = me.settings.config.search.types[i];
                    privileged = privileged.concat(getType(searchType.label, searchType.count));
                }

                privileged.sort(function(a, b) {
                    return a - b;
                });

                var base = privileged.length,
                    podium = [];
                for (var i = base - 1; i >= 0; i--) {
                    var index = privileged[i],
                        entry = distances.splice(index, 1);
                    podium.unshift(entry[0]);
                }
                distances = podium.concat(distances);
            }

            /* Strip all markers that are beyond the limit */
            var cut = -1,
                limit = me.settings.config.search.radius;
            for (var i = base || 0; i < distances.length; i++) {
                if (distances[i] && limit < distances[i]._km) {
                    cut = i;
                    break;
                }
            }
            distances = distances.slice(0, cut);

            /* Clear current results  */
            me.clear();

            var holder = $(me.settings.dom).parents('.locations').find('*[data-result]'),
                $holder = (holder.length) ? holder : $('#results'),
                string = $holder.attr('data-' + type),
                init = (privileged) ? privileged.length : me.settings.config.search.step;

            string = string.replace('###count###', distances.length);
            if ('search' == type) string = string.replace('###address###', $(el).find('*[name="address"]').val());

            $holder.html(string);

            me.settings.state = 0;
            me.list(distances, me.settings.state, init);

        },

        /* Adjust zoom to englobe results */
        fitSearch: function(place) {

            if (!place.geometry) return;

            var me = this;

            // // If the place has a geometry, then present it on a map.
            // if (place.geometry.viewport) {
            // 	locator.map.fitBounds(place.geometry.viewport);
            // } else {
            // 	locator.map.setCenter(place.geometry.location);
            // 	locator.map.setZoom(17);  // Why 17? Because it looks good.
            // }
            /* Find nearest stores from this place */
            var lat = place.geometry.location.lat(),
                lng = place.geometry.location.lng();

            me.list(lat, lng, 'search');

        },

        /* List and display */
        list: function(currentlat, currentlng, markers) {
            ///* Vars */
            var me = this,
                el = me.element;

            var start = 0;
            var filledlist = $('.locations').find('.stores');

            if (me.isBusy()) return;

            me.busy(true);
            $.ajax({
                url: me.settings.wsList,
                type: 'POST',
                dataType: 'json',
                cache: false,
                data: {
                    lat: Math.round(currentlat * 100) / 100,
                    long: Math.round(currentlng * 100) / 100,
                    page: me.settings.page,
                    version: me.settings.version,
                    order: me.settings.order,
                    area: me.settings.area,
                    ztid: me.settings.ztid,
                    attribut: me.settings.attribut,
					brandactivity: me.settings.brandactivity
                },
                success: function(items) {
                    me.busy(false);
						// Hack  
				//		items = [{"id":"0000038241","rrdi":"020842E01F","media":null,"name":"DS DISTRIBUTEUR -CITRO\u00cbN RETAIL PARIS REPUBLIQUE","address":"62 AVENUE DE LA REPUBLIQUE<br \/>75011&nbsp;PARIS","phone":"01 49 29 62 62","distance":2,"services":[4,1,2,8,3,0],"lat":48.8646774,"lng":2.376711,"type":"dvn","isAgent":false},{"id":"0000040609","rrdi":"990131Z01F","media":null,"name":"GARAGE DU FAUBOURG","address":"33 RUE DE REUILLY<br \/>75012&nbsp;PARIS","phone":"01 43 72 70 76","distance":3,"services":[4,1,2,3],"lat":48.8474,"lng":2.3867,"type":"pdv","isAgent":true},{"id":"0000046339","rrdi":"058870P01F","media":null,"name":"CITRO\u00cbN RETAIL PARIS 8","address":"25 RUE DE CONSTANTINOPLE<br \/>75008&nbsp;PARIS","phone":"01 40 08 60 40","distance":3.2,"services":[4,1,2,3,0],"lat":48.8802643,"lng":2.319153,"type":"pdv","isAgent":false},{"id":"0000041370","rrdi":"038901J01F","media":null,"name":"DS Salon - CITRO\u00cbN RETAIL PANTIN ETS JAURES","address":"59 BIS AVENUE JEAN JAURES<br \/>75019&nbsp;PARIS","phone":"01 44 52 79 79","distance":3.3,"services":[4,2,3,0,7],"lat":48.88437,"lng":2.376887,"type":"pdv","isAgent":false},{"id":"0000046330","rrdi":"020910D01F","media":null,"name":"DS WORLD PARIS","address":"33 RUE FRANCOIS 1ER<br \/>75008&nbsp;PARIS","phone":"01 53 57 33 08","distance":3.5,"services":[2,5],"lat":48.8683739,"lng":2.303964,"type":"pdv","isAgent":false},{"id":"0000038619","rrdi":"766108K01F","media":null,"name":"DS STORE - GARAGE CITE LECOURBE","address":"88 RUE LECOURBE<br \/>75015&nbsp;PARIS","phone":"01 47 83 22 18","distance":3.8,"services":[4,1,6,2,3],"lat":48.8432,"lng":2.30466,"type":"pdv","isAgent":true},{"id":"0000040618","rrdi":"990138L01F","media":null,"name":"DS WORLD AUTO SPECIALITES","address":"42 RUE BELGRAND<br \/>75020&nbsp;PARIS","phone":"01 47 97 20 59","distance":4,"services":[4,1,2,3,5],"lat":48.8647,"lng":2.40404,"type":"pdv","isAgent":true}];
						//console.log(items);
					
					
					me.settings.config.markers = items;
                    me.setMarkers(items);

                    var currentmarkerid = [];

                    if (filledlist.hasClass('filled')) {
                        var currentmarkerid = [];

                        for (var i = me.settings.markers.length - 1; i >= 0; i--) {
                            currentmarkerid.push(me.settings.markers[i]);
                        };
                        me.markersevent(currentmarkerid);
                    }

                    //if (!(filledlist.hasClass('filled'))) {
                        me.createList(items);
                    //}

                    // google.maps.event.addListener(me.settings.map, 'dragend', function(e) {
                    // 
                    //     var currentlat = this.center.lat();
                    //     var currentlng = this.center.lng();
                    // 
                    //     for (var i = 0, len = me.settings.markers.length; i < len; i++) {
                    //         me.settings.markers[i].setMap(null);
                    //     }
                    //     me.list(currentlat, currentlng);
                    // });
                }

            });

        },

        createList: function(items) {
            ///* Vars */
            var me = this,
                el = me.element,
                start = 0;
            /* Vars */
            var tpl = $(me.settings.dom).parents('.locations').find('.stores > script').html(),
                $placeholder = $(me.settings.dom).parents('.locations').find('.stores .items'),
                compiled = '';

            // Create a table of markers
            var currentmarkerid = [];
            for (var i = me.settings.markers.length - 1; i >= 0; i--) {
                currentmarkerid.push(me.settings.markers[i]);
            };


            /* Appends */
            if (items != null) {
                me.settings.meaPdvCount=0;
                me.settings.meaDvnCount=0;
                for (var i = 0, len = items.length; i < len; i++) {

                    if (me.settings.mea == "true") {
                        if (items[i].type == "pdv") {
                            me.settings.meaPdvCount++;
                        } else if (items[i].type == "dvn") {
                            me.settings.meaDvnCount++;
                        }
                    }

                    var compiledTemplate = _.template(tpl, {
                        data: items[i],
                        services: me.settings.config.services
                    });
                    compiled += compiledTemplate;
                }
            } else {
                items = [];
                items.length = 0;
                if (me.settings.mea == "true") {
                    me.settings.meaPdvCount = 0;
                    me.settings.meaDvnCount = 0;
                }
            }

            /* Events */
            $placeholder.append(compiled)

            me.markersevent(currentmarkerid);

            /* ADDMORE */
            var more = $placeholder.attr('data-more');
            var from = me.settings.config.search.step,
                step = me.settings.config.search.step;

            $placeholder.find('.addmore').remove();

            $placeholder.find('.item:gt(' + step + ')').hide();

            if ($placeholder.find('.item:hidden').length) {
                $placeholder.append(more).find('.addmore a').click(function(e) {
                    e.preventDefault;

                    $placeholder.find('.item:lt(' + (from + step) + '):not(.item:lt(' + from + '))').show();
                    from += step;

                    if ($placeholder.find('.item:hidden').length == 0) {
                        $placeholder.find('.addmore').remove();
                    }
                });
            }

            /* Callback */
            me.settings.onList.call(me);

            /* If is first */
            if (0 < start) return;

            /* Open results sidebar */
            $placeholder.parents('.stores').addClass('filled');

            /* Small delay to prevent bounds misplace */
            setTimeout(function() {
                /* Reset map center */
                var currCenter = me.settings.map.getCenter();
                google.maps.event.trigger(me.settings.map, 'resize');
                me.settings.map.setCenter(currCenter);

                /* Fit selection bounds */
                var bounds = new google.maps.LatLngBounds();
                for (var i = 0; i < me.settings.markers.length; i++) {
                    bounds.extend(me.settings.markers[i].position);
                };
                me.settings.map.fitBounds(bounds);
            }, 100);


            /***** Write number of results ****/
            if ($('.searchdone').length) {
                var holder = $(me.settings.dom).parents('.locations').find('*[data-result]'),
                    $holder = (holder.length) ? holder : $('#results');


                if (me.settings.mea == "true") {
                    if ((me.settings.meaPdvCount > 0) && (me.settings.meaDvnCount > 0)) {
                        string = $holder.attr('data-search-mea-both');
                    }
                    if ((me.settings.meaPdvCount > 0) && (me.settings.meaDvnCount == 0)) {
                        string = $holder.attr('data-search-mea-pdv');
                    }
                    if ((me.settings.meaPdvCount == 0) && (me.settings.meaDvnCount > 0)) {
                        string = $holder.attr('data-search-mea-dvn');
                    }
                } else {
                    string = $holder.attr('data-search');
                }

                string = string.replace('###count###', items.length);
                string = string.replace('###countPdv###', me.settings.meaPdvCount);
                string = string.replace('###countDvn###', me.settings.meaDvnCount);
                string = string.replace('###address###', '"' + $(el).find('*[name="address"]').val() + '"');

                $holder.html(string);

            }

            if ($('.geolocdone').length) {
                var holder = $(me.settings.dom).parents('.locations').find('*[data-result]'),
                    $holder = (holder.length) ? holder : $('#results');


                if (me.settings.mea == "true") {
                    if ((me.settings.meaPdvCount > 0) && (me.settings.meaDvnCount > 0)) {
                        string = $holder.attr('data-geo-mea-both');
                    }
                    if ((me.settings.meaPdvCount > 0) && (me.settings.meaDvnCount == 0)) {
                        string = $holder.attr('data-geo-mea-pdv');
                    }
                    if ((me.settings.meaPdvCount == 0) && (me.settings.meaDvnCount > 0)) {
                        string = $holder.attr('data-geo-mea-dvn');
                    }
                } else {
                    string = $holder.attr('data-geo');
                }

                string = string.replace('###count###', items.length);
                string = string.replace('###countPdv###', me.settings.meaPdvCount);
                string = string.replace('###countDvn###', me.settings.meaDvnCount);
                string = string.replace('###address###', '"' + $(el).find('*[name="address"]').val() + '"');

                $holder.html(string);
            }
        },

        markersevent: function(currentmarkerid) {
            var me = this,
                $items = me.$element.find('.items .item'),
                itemstoreid;

            $('.locations .items .item').on('mouseenter', function() {
                itemstoreid = $(this).data('storeid');
                for (var i = 0, len = currentmarkerid.length; i < len; i++) {
                    if (itemstoreid == currentmarkerid[i]._storeId) {
                        me.highlight(currentmarkerid[i], false, me.settings);
                    }
                }
            });

            $('.locations .items .item').on('mouseleave', function() {
                itemstoreid = $(this).data('storeid');
                for (var i = 0, len = currentmarkerid.length; i < len; i++) {
                    if (itemstoreid == currentmarkerid[i]._storeId) {
                        me.downplay(currentmarkerid[i], false, me.settings);
                    }

                }
            });

            $('.locations .items .item').on('click', function() {
                itemstoreid = $(this).data('storeid');
                for (var i = 0, len = currentmarkerid.length; i < len; i++) {
                    if (itemstoreid == currentmarkerid[i]._storeId) {
                        me.details(currentmarkerid[i]);
                    }
                }
            });
        },


        /* Details */
        details: function(caller) {
            /* Vars */
            var me = this;
            /* Callback */
            //if(!me.settings.onDetails.call(me)) return;
            var id = caller._storeId || caller.getAttribute('data-id'),
                url = me.settings.wsDetails.replace('###id###', id);

            document.location = url;

        }

    };

    // A really lightweight plugin wrapper around the constructor,
    // preventing against multiple instantiations
    $.fn[pluginName] = function(options) {
        return this.each(function() {
            if (!$.data(this, 'plugin_' + pluginName)) {
                $.data(this, 'plugin_' + pluginName, new Plugin(this, options));
            }
        });
    };

})(jQuery, window, document);;// CITROEN MAIN.JS
// LAST EDITED : 2015 / 08 / 26

/* CONTAINS :
1. - GENERAL SETTERS
2. - CLASSIC FUNCTIONS
	2.1 - shareThisButton // ADD THIS
	2.2 - iOSversion // iOS VERSION CHECK
	2.3 - isJQMGhostClick // GHOSTCLICK HANDLER FOR ANDROID NATIVE
	2.4 - clickzone // CLICKZONE
	2.5 - lazy // LAZY LOADING
	2.6 - Loader // LOADER
	2.7 - homepushCta // HEIGHT SETTER
	2.8 - magicCta // HEIGHT SETTER
	2.9 - $(window).scroll // BUTTON UP SCROLL
	2.10 - FooterPipes // FOOTER PIPES HANDLER
3. - SPECIFIC FUNCTIONS
	3.1 - SelectFeeder // MY PROJECT -> VEHICLES SELECTION -> FEEDER
	3.2 - swipeContent // SLIDER FOR DETAILS PAGES V1.0
	3.3 - SlidePages // SLIDER FOR DETAILS PAGES V2.0
	3.4 - iniTables // SCROLLABLE TABLES AND ASSOCIATED NOTES DISPLAYING
	3.5 - $container = $('.masonry') // SOCIAL WALL
	3.6 - menu // MENU
	3.7 - dropDownMenu // MENU -> DROPDOWN FOR LVL1 NAVIGATION
	3.8 - Accordion // ACCORDION - TOGGLE
	3.9 - bxSlider // SLIDER - USING BXSLIDER
	3.10 - checkboxSkin // FORMS -> CHECKBOXES
	3.11 - CarSelector // CAR SELECTOR AND FILTERS
	3.12 - nUiSlider // Minified source file
	3.13 - if( $( "#slider" ).length ) // CAR SELECTOR -> SLIDER FILTER
	3.14 - selectSkin // CAR SELECTOR -> SELECT SKIN
	3.15 - if($('#edit-vehicle-finish').length > 0) // CAR DETAILS -> FINISH CHECKBOXES
	3.16 - if($('.btn.dynscript').length > 0) // CAR DETAILS -> DISPLAY EQUIPMENTS & TECHNICAL NOTICE
	3.17 - colors // VEHICLE'S COLOR SELECTION
	3.18 - addSliderThumb // COLOR VEHICLE SELECTOR SLIDER
	3.19 - loadmore // LOADMORE
	3.20 - intMosaic // INTERACTIVE MOSAIC
	3.21 - if($('.hb').length > 0) // NOTES CLOSE BUTTON
	3.22 - mediaPop // MEDIA POPIN
	3.23 - noticePop // LEGAL NOTICES POPIN
	3.24 - if($('.connection-block-popin').length > 0) // LOGIN POPIN
	3.25 - if($('.citroenid-block-popin').length > 0) // CITROEN ID POPIN
	3.26 - if($('.subscribe-block-popin').length > 0) // SUBSCRIBE POPIN
	3.27 - readCookie // COOKIES READING
	3.28 - if($.fn.gLocator) // STORE LOCATOR
	3.29 - gtm_listener // GOOGLE TAG MANAGER
	3.30 - addThisScript // ADDTHIS SCRIPT CALL
	3.31 - shareThisButton // ADDTHIS INIT
	3.32 - linkMyCar // ADDTHIS INIT // 26 / 12 / 2014

*/

/* ***************************************************************************************************
 ** ***************************************************************************************************
 ** 1. - GENERAL SETTERS */

$('body').addClass('script');
window.scrollTo(0, 1);
var myScroll;
var scrollSwitch = 0;
var ie = (-1 != navigator.userAgent.indexOf('MSIE'));
var scrollTopV = $(window).scrollTop();

/* ***************************************************************************************************
 ** ***************************************************************************************************
 ** 2. - CLASSIC FUNCTIONS */

// ADDTHIS SCRIPT CALL
var addThisScript = function() {
    $.getScript("//s7.addthis.com/js/300/addthis_widget.js#username=ra-521f4a58354d5213&domready=1&async=1", function() {
        addthis.init();
        addthis.toolbox('.addthis_toolbox');
    });
}

// ADDTHIS INIT
var shareThisButton = function(addThisBtns) {
    if ($('.sharerm').length > 0) {
        var $addThisLayout = $('<div class="addthis_toolbox addthis_default_style addthis_32x32_style">' + addThisBtns + '</div>');
        $('.sharerm').append($addThisLayout);
        addThisScript();
    }

    $('.shareButton').each(function(indexb) {
        if(addThisBtns == "" && $(this).next() != undefined && $(this).next().val() != undefined){
            addThisBtns = $(this).next().val();
        }
        $(this).unbind('click');
        $(this).bind('click', function(e) {

            var layTitle = $(this).attr('data-text');

            var $addThisLayout = $('<div class="addthismask" id="addthismask' + indexb + '"></div><div class="addthislayer" id="addthislayer' + indexb + '"><div class="close"></div><span class="title">' + layTitle + '</span><div class="addthis_toolbox addthis_default_style addthis_32x32_style">' + addThisBtns + '</div></div>');
            $('.container').append($addThisLayout);


            $('#addthislayer' + indexb).css({
                top: parseInt(($(window).height() - $('#addthislayer' + indexb).height()) / 2) + 'px'
            });

            $('.content').css({
                top: -scrollTopV,
                left: 0
            });

            $('.container').addClass('popopen');
            $('.content').addClass('popopen');

            $('.container').css({
                height: $(window).height()
            });

            $('#addthismask' + indexb).css({
                width: $(window).width(),
                height: $(window).height()
            });

            $('#addthismask' + indexb).show();
            $('#addthislayer' + indexb).show()
            $('#addthislayer' + indexb + ' .close, #addthismask' + indexb).bind('click', function(e) {

                $('.container').removeClass('popopen');
                $('.content').removeClass('popopen');
                $('.content').css({
                    top: 0,
                    left: 0
                });
                $('.container').css({
                    height: 'auto'
                });
                $(window).scrollTo(scrollTopV, 0);
                $('#addthismask' + indexb).remove();
                $('#addthislayer' + indexb).remove();
            });

            addThisScript();
        });

    });
}

// iOS VERSION CHECK
function iOSversion() {
    if (/iP(hone|od|ad)/.test(navigator.platform)) {
        // supports iOS 2.0 and later: <http://bit.ly/TJjs1V>
        var v = (navigator.appVersion).match(/OS (\d+)_(\d+)_?(\d+)?/);
        return [parseInt(v[1], 10), parseInt(v[2], 10), parseInt(v[3] || 0, 10)];
    }
}

// GHOSTCLICK HANDLER FOR ANDROID NATIVE
var lastTapTime;

function isJQMGhostClick(event) {
    var currTapTime = new Date().getTime();
    if (lastTapTime == null || currTapTime > (lastTapTime + 800)) {
        lastTapTime = currTapTime;
        return false;
    } else {
        return true;
    }
}

// CLICKZONE (MAYBE DEPRECATED/
var clickzone = {
    build: function() {
        var link = $(this).find('a').attr('href');
        $(this).click(function(e) {
            e.preventDefault();
            e.stopPropagation();
            document.location = link;
        });
    }
};
$('[data-mobile="click"]').each(clickzone.build);

// LAZY LOADING
var lazy = {
    set: function($imgs) {
        $imgs.lazy({
            // general
            bind: "load",
            threshold: 1000,
            fallbackHeight: 2000,
            visibleOnly: true,
            // delay
            delay: -1,
            combined: false,
            // attribute
            attribute: "data-original",
            removeAttribute: true,
            // effect
            effect: "fadeIn",
            effectTime: 250,
            // throttle
            enableThrottle: true,
            throttle: 250,
            // callback
            beforeLoad: function(element) {},
            onLoad: function(element) {},
            afterLoad: function(element) {
                element.css({
                    display: 'inline-block'
                });
                element.removeClass('lazy');
                $(element).trigger("afterLoadDone");
                var elLink = element.parents('a');
                if ($(elLink).hasClass('video')) {
                    $(elLink).addClass('picto');
                }
            },
            onFinishedAll: function() {},
            onError: function(element) {}
        });
    },
    change: function($imgs) {
        $imgs.lazy({
            // general
            bind: "event",
            // delay
            delay: 0,
            combined: false,
            fallbackHeight: 1000,
            threshold: 2000,
            // attribute
            attribute: "data-original",
            removeAttribute: true,
            // effect
            effect: "fadeIn",
            effectTime: 250,

            // throttle
            enableThrottle: true,
            throttle: 250,
            visibleOnly: false,
            // callback
            beforeLoad: function(element) {},
            onLoad: function(element) {},
            afterLoad: function(element) {
                element.css({
                    display: 'inline-block'
                });
                element.removeClass('lazy');
                $(element).trigger("afterLoadDone");
                var elLink = element.parents('a');
                if ($(elLink).hasClass('video')) {
                    $(elLink).addClass('picto');
                }
            },
            onError: function(element) {}
        });
    },
    slider: function($imgs) {
        $imgs.lazy({
            // general
            bind: "event",
            // delay
            delay: 0,
            combined: false,
            fallbackHeight: 10000,
            threshold: 10000,
            // attribute
            attribute: "data-original",
            removeAttribute: true,
            // effect
            effect: "fadeIn",
            effectTime: 250,
            // throttle
            enableThrottle: true,
            throttle: 250,
            visibleOnly: false,
            // callback
            beforeLoad: function(element) {},
            onLoad: function(element) {},
            afterLoad: function(element) {
                element.css({
                    display: 'inline-block'
                });
                element.removeClass('lazy');
                $(element).trigger("afterLoadDone");
                var elLink = element.parents('a');
                if ($(elLink).hasClass('video')) {
                    $(elLink).addClass('picto');
                }
            },
            onError: function(element) {}
        });
    }
};
/* Set all lazy images except slider / layer ones */
lazy.set($('img.lazy').not('.slider img.lazy'));

// LOADER
var Loader = function(target) {
    this.init(target);
};
Loader.prototype = {
    init: function(target) {;
		this.interval = 0;

        this.tpl = $('<div class="loading"><div class="circ"></div></div>');
		this.$target = $(target) || $('body');
        this.$circ = this.tpl.find('.circ');


        if (!this.$target.is('body')) {
            this.$target.css('position', 'relative');
            this.tpl.css({
                'width': this.$target.innerWidth(),
                'height': this.$target.innerHeight(),
                'min-height': 0,
                'position': 'absolute'
            });
        }
        return this;
    },
    show: function(text, background) {
        var oThis = this;
        this.tpl.appendTo(this.$target);

        if (typeof text !== 'undefined' && text.length) {
            this.tpl.append('<div class="loading-text">' + text + '</div>');
            this.tpl.find('div').wrapAll("<div class='loading_content wtxt'/>");
            background = true;
        } else {
            if ($(oThis.$target).attr('data-loadtext')) {
                text = $(oThis.$target).attr('data-loadtext');
                this.tpl.append('<div class="loading-text">' + text + '</div>');
                this.tpl.find('div').wrapAll("<div class='loading_content wtxt'/>");
                background = true;
            } else {
                this.tpl.find('div').wrapAll("<div class='loading_content'/>");
            }
        }

        this.tpl.find('.loading_content').css('margin-top', (this.tpl.height() / 2) - 37);

        if (background == false) {
            this.tpl.css('background', 'none');
        }
    },
    hide: function() {
        clearInterval(this.interval);
        this.tpl.find('.loading_content div').unwrap();
        this.tpl.find('.loading_content').removeClass('wtxt');
        this.tpl.remove();
        this.tpl.find('.loading-text').remove();
        this.tpl.css('background', '');
    }
};


// HEIGHT SETTER -> HOME PAGE OFFERS BUTTONS
function homepushCta(setH) {
    if ($('.txt2').length > 0) {
        var maxH = setH;
        for (i = 0; i < $('.txt2 .btn').length; i++) {
            if ($('.txt2 .btn').eq(i).innerHeight() > maxH) {
                maxH = $('.txt2 .btn').eq(i).innerHeight();
            }
        }

        $('.txt2 .btn').each(function(index, el) {
            $(el).css({
                height: maxH
            });
        });
    }
}

// HEIGHT SETTER -> ALL CTA BUTTONS
var magicCta = function(root, col, target) {
    this.init(root, col, target);
};
magicCta.prototype = {
    init: function(root, col, target) {
        var oThis = this;
        oThis.root = root;
        oThis.col = $(oThis.root).find(col);
        oThis.target = (target != undefined) ? target : oThis.col;
        oThis.maxH = $(oThis.root).height();


        oThis.col.each(function(index) {
            if ((!oThis.root.hasClass('blocks')) && (!oThis.root.hasClass('noadjust'))) {
					if (oThis.target != oThis.col) {
						if ($(this).innerHeight() == oThis.maxH) oThis.setTarget($(this).find(oThis.target).height());
					} else {
						if ($(this).innerHeight() < oThis.maxH) {
							$(this).css({
								height: oThis.maxH
							});
						}
					}
				
            }
        });

    },
    setTarget: function(targetH) {
        var oThis = this;
        oThis.targetH = targetH;
        $(oThis.root).find(oThis.target).each(function() {
			if ((!oThis.root.hasClass('blocks')) && (!oThis.root.hasClass('noadjust'))) {
					$(this).css({
						height: oThis.targetH
					});
				
            }
        });
    }
}

$('.cta').not('.blocks').each(function() {
		$('.cta').not('.noadjust').each(function() { new magicCta($(this), 'li'); })
});


$('.media').each(function() {
    new magicCta($(this), 'a', 'span');
});



// BUTTON UP SCROLL
if($('.showroom-mobile').length>0){
    $('.btn-up').addClass('showroom-mobile');
}
$(window).scroll(function() {
    if (scrollSwitch == 0) {
        if ($(this).scrollTop() > 30) {
            $('.btn-up').fadeIn();
        } else {
            $('.btn-up').fadeOut();
        }
    }
});


// FOOTER PIPES HANDLER
var FooterPipes = function(root, elements) {
    this.init(root, elements);
}
FooterPipes.prototype = {
    init: function(root, elements) {
        var oThis = this;
        oThis.root = root;
        oThis.elements = elements;
        oThis.h = $(oThis.root).height();
        oThis.ref = elements.eq(0);
        oThis.refOT = oThis.ref.offsetTop;

        oThis.elements.each(function() {
            if (oThis.refOT == this.offsetTop) {
                if (oThis.ref != this) {
                    $(this).css({
                        borderLeft: '1px solid #fff'
                    });
                }
            } else {
                oThis.ref = this;
                oThis.refOT = oThis.ref.offsetTop;
            }
        });
    }
}
$('.footer').each(function() {
    new FooterPipes($(this), $(this).find('li'));
});

/* ***************************************************************************************************
 ** ***************************************************************************************************
 ** 3. - SPECIFIC FUNCTIONS */

// MY PROJECT -> VEHICLES SELECTION -> FEEDER
var SelectFeeder = function(root) {
    this.init(root);
}
SelectFeeder.prototype = {
    init: function(root) {
        var oThis = this;

        oThis.root = root;
        oThis.json = $(oThis.root).attr('data-feeder');
        oThis.formID = $(oThis.root).attr('ID');
        oThis.receipt = $(oThis.root).find('fieldset');
        oThis.visualCtnt = $(oThis.root).find('figure');
        oThis.visual = $(oThis.root).find('img');
        oThis.visual.on("afterLoadDone", function() {
            oThis.visualW = oThis.visual.width();
            oThis.visualH = oThis.visual.height();
        });
        oThis.lazySrc = $(oThis.visual).attr('src');
        oThis.dfltSrc = $(oThis.visual).attr('data-original');
        oThis.cta = $(oThis.root).find('.cta');
        oThis.dfltVehicule = $(oThis.root).attr('data-defaut-vehicule');
        oThis.dfltFinition = $(oThis.root).attr('data-defaut-finition');
        oThis.dfltVersion = $(oThis.root).attr('data-defaut-version');

        oThis.ctaH = oThis.cta.outerHeight() + parseInt(oThis.cta.css('marginTop'));

        oThis.build();
        oThis.ctaSwitch(0);

    },
    ctaSwitch: function(switcher) {
        var oThis = this;
        if (0 == switcher) {
            oThis.cta.hide();
            $(oThis.root).css({
                paddingBottom: oThis.ctaH
            });
        } else {
            $(oThis.root).css({
                paddingBottom: 0
            });
            oThis.cta.show();
        }
        $(window).trigger('resize');
    },
    imgRndr: function(dataSrc) {
        var oThis = this;
        $(oThis.visualCtnt).empty();
        $('<img />').appendTo($(oThis.visualCtnt));
        var visual = $(oThis.root).find('img');
        $(visual).attr('src', oThis.lazySrc);
        $(visual).attr('data-original', dataSrc);
        $(visual).css({
            height: oThis.visualH,
            width: oThis.visualW,
            minWidth: oThis.visualW,
            maxWidth: oThis.visualW
        });
        $(visual).addClass('lazy');

        lazy.change($(visual));
        $(visual).on("afterLoadDone", function() {
            setTimeout(function() {
                oThis.resizeW();
            }, 1);
        });

    },
    resizeW: function() {
        var oThis = this;
        $(window).trigger('resize');
    },
    build: function() {
        var oThis = this;
        $.ajax({
            url: oThis.json,
            type: "GET",
            dataType: "json",
            success: function(response) {
                if (response.vehicules.length > 0) {
                    // APPENDING SELECT ELEMENTS INTO OBJECT BINDED FORM
                    $('<select class="select1" name="mp-v-select1"><option value="0">' + response.label1 + '</option></select>').appendTo(oThis.receipt);
                    $('<select class="select2" name="mp-v-select2" disabled="true"></select>').appendTo(oThis.receipt);
                    $('<select class="select3" name="mp-v-select3" disabled="true"></select>').appendTo(oThis.receipt);
                    // GETTING THESE ELEMENTS INTO JQUERY OBJECT
                    oThis.selectLvl1 = $(oThis.root).find('.select1');
                    oThis.selectLvl2 = $(oThis.root).find('.select2');
                    oThis.selectLvl3 = $(oThis.root).find('.select3');
                    // SETTING SUB-LEVELS SELECTS LABELS
                    oThis.selectLvl2Label = '<option value="0">' + response.label2 + '</option>';
                    oThis.selectLvl3Label = '<option value="0">' + response.label3 + '</option>';
                    // DISABLING SUB-LEVELS SELECTS
                    oThis.selectLvl2.addClass('disabled');
                    oThis.selectLvl3.addClass('disabled');


                    oThis.lvl1slctd = "";
                    oThis.lvl1hsChldrn = "";

                    $(oThis.selectLvl2Label).appendTo(oThis.selectLvl2);
                    $(oThis.selectLvl3Label).appendTo(oThis.selectLvl3);
                    $(window).trigger('resize');

                    oThis.selectLvl1.on('change', function() {
                        // CHECKING SELECTED OPTION INDEX
                        oThis.selectLvl1.selected = oThis.selectLvl1.prop("selectedIndex");

                        // IF LABEL IS SELECTED
                        if (0 == oThis.selectLvl1.selected) {
                            oThis.selectLvl2.html('');
                            oThis.selectLvl3.html('');
                            $(oThis.selectLvl2Label).appendTo(oThis.selectLvl2);
                            $(oThis.selectLvl3Label).appendTo(oThis.selectLvl3);
                            oThis.selectLvl2.attr('disabled', true);
                            oThis.selectLvl3.attr('disabled', true);
                            oThis.selectLvl2.unbind('change');
                            oThis.selectLvl3.unbind('change');
                            oThis.selectLvl2.addClass('disabled');
                            oThis.selectLvl3.addClass('disabled');
                            oThis.ctaSwitch(0);

                            // IMAGE RENDERING
                            oThis.imgRndr(oThis.dfltSrc);
                        } else {

                            // GETTING AND DISPLAYING VEHICULE VISUAL

                            // IMAGE RENDERING
                            oThis.imgRndr(response.vehicules[oThis.selectLvl1.selected - 1].visuel);

                            // CHECKING IF THIS VALUE HAS CHILDREN
                            (response.vehicules[oThis.selectLvl1.selected - 1].finitions.length > 0) ? oThis.lvl1hsChldrn = true: oThis.lvl1hsChldrn = false;
                            // IF IT HAS NO CHILDREN
                            if (false == oThis.lvl1hsChldrn) {
                                // RESETING SUB-LEVELS SELECTS

                                // EMPTYING THE SELECTS
                                oThis.selectLvl2.html('');
                                oThis.selectLvl3.html('');
                                // SETTING THE LABELS
                                $(oThis.selectLvl2Label).appendTo(oThis.selectLvl2);
                                $(oThis.selectLvl3Label).appendTo(oThis.selectLvl3);
                                oThis.selectLvl2.attr('disabled', true);
                                oThis.selectLvl3.attr('disabled', true);
                                oThis.selectLvl2.unbind('change');
                                oThis.selectLvl3.unbind('change');
                                oThis.selectLvl2.addClass('disabled');
                                oThis.selectLvl3.addClass('disabled');
                                oThis.ctaSwitch(1);
                            }
                            // IF IT HAS CHILDREN
                            else {
                                /* ORIGINAL SCENARIO USED TO LOCK VALIDATION UNTIL EVERY PATH IS COMPLETE
										oThis.ctaSwitch(0);
									*/
                                oThis.ctaSwitch(1);
                                oThis.selectLvl1Value = this.value;

                                // RESETING SUB-LEVELS SELECTS

                                // EMPTYING THE SELECTS
                                oThis.selectLvl2.html('');
                                oThis.selectLvl3.html('');
                                // SETTING THE LABELS
                                $(oThis.selectLvl2Label).appendTo(oThis.selectLvl2);
                                $(oThis.selectLvl3Label).appendTo(oThis.selectLvl3);
                                //

                                oThis.lvl2slctd = "";
                                oThis.lvl2hsChldrn = "";

                                // MAKING SELECT SUB-LEVEL 2 USABLE
                                oThis.selectLvl2.attr('disabled', false);
                                oThis.selectLvl2.removeClass('disabled');
                                // LOCKING SELECT SUB-LEVEL 3
                                oThis.selectLvl3.attr('disabled', true);
                                oThis.selectLvl3.unbind('change');
                                oThis.selectLvl3.addClass('disabled');

                                oThis.selectLvl2.on('change', function() {
                                    // CHECKING SELECTED OPTION INDEX
                                    oThis.selectLvl2.selected = oThis.selectLvl2.prop("selectedIndex");
                                    // IF LABEL IS SELECTED
                                    if (0 == oThis.selectLvl2.selected) {
                                        oThis.selectLvl3.html('');
                                        $(oThis.selectLvl3Label).appendTo(oThis.selectLvl3);
                                        oThis.selectLvl3.attr('disabled', true);
                                        oThis.selectLvl3.unbind('change');
                                        oThis.selectLvl3.addClass('disabled');
                                        /* ORIGINAL SCENARIO USED TO LOCK VALIDATION UNTIL EVERY PATH IS COMPLETE
												oThis.ctaSwitch(0);
											*/

                                        // GETTING AND DISPLAYING VEHICULE VISUAL

                                        // IMAGE RENDERING
                                        oThis.imgRndr(response.vehicules[oThis.selectLvl1.selected - 1].visuel);
                                    } else {

                                        // GETTING AND DISPLAYING VEHICULE VISUAL

                                        // IMAGE RENDERING
                                        oThis.imgRndr(response.baseVisuel + response.vehicules[oThis.selectLvl1.selected - 1].finitions[oThis.selectLvl2.selected - 1].codeVisuel);

                                        // CHECKING IF THIS VALUE HAS CHILDREN
                                        (response.vehicules[oThis.selectLvl1.selected - 1].finitions[oThis.selectLvl2.selected - 1].versions.length > 0) ? oThis.lvl2hsChldrn = true: oThis.lvl2hsChldrn = false;

                                        // IF IT HAS NO CHILDREN
                                        if (false == oThis.lvl2hsChldrn) {
                                            // RESETING SUB-LEVELS SELECTS

                                            // EMPTYING THE SELECTS
                                            oThis.selectLvl3.html('');
                                            // SETTING THE LABELS
                                            $(oThis.selectLvl3Label).appendTo(oThis.selectLvl3);
                                            oThis.selectLvl3.attr('disabled', true);
                                            oThis.selectLvl3.unbind('change');
                                            oThis.selectLvl3.addClass('disabled');
                                            oThis.ctaSwitch(1);
                                        }
                                        // IF IT HAS CHILDREN
                                        else {
                                            /* ORIGINAL SCENARIO USED TO LOCK VALIDATION UNTIL EVERY PATH IS COMPLETE
													oThis.ctaSwitch(0);
												*/
                                            oThis.selectLvl2Value = this.value;

                                            // RESETING SUB-LEVELS SELECTS

                                            // EMPTYING THE SELECTS
                                            oThis.selectLvl3.html('');
                                            // SETTING THE LABELS
                                            $(oThis.selectLvl3Label).appendTo(oThis.selectLvl3);
                                            //

                                            oThis.lvl3slctd = "";
                                            oThis.lvl3hsChldrn = "";

                                            // MAKING SELECT SUB-LEVEL 2 USABLE
                                            oThis.selectLvl3.attr('disabled', false);
                                            oThis.selectLvl3.removeClass('disabled');
                                            oThis.selectLvl3.on('change', function() {
                                                // CHECKING SELECTED OPTION INDEX
                                                oThis.selectLvl3.selected = oThis.selectLvl3.prop("selectedIndex");

                                                // CHECKING IF THIS VALUE IS NOT THE LABEL (FIRST OPTION)
                                                if (0 != oThis.selectLvl3.selected) {
                                                    // GETTING AND DISPLAYING VEHICULE VISUAL

                                                    // IMAGE RENDERING
                                                    oThis.imgRndr(response.baseVisuel + response.vehicules[oThis.selectLvl1.selected - 1].finitions[oThis.selectLvl2.selected - 1].versions[oThis.selectLvl3.selected - 1].codeVisuel);

                                                    // END OF THE ROAD, DISPLAYING BUTTONS
                                                    oThis.ctaSwitch(1);
                                                }
                                                // IF LABEL IS SELECTED
                                                else {
                                                    // GETTING AND DISPLAYING VEHICULE VISUAL

                                                    // IMAGE RENDERING

                                                    oThis.imgRndr(response.baseVisuel + response.vehicules[oThis.selectLvl1.selected - 1].finitions[oThis.selectLvl2.selected - 1].codeVisuel);

                                                    /* ORIGINAL SCENARIO USED TO LOCK VALIDATION UNTIL EVERY PATH IS COMPLETE
															oThis.ctaSwitch(0);
														*/
                                                }
                                                oThis.dfltVersion = "";
                                            });

                                            // FEEDING SELECT SUB-LEVEL3
                                            for (var i in response.vehicules[oThis.selectLvl1.selected - 1].finitions[oThis.selectLvl2.selected - 1].versions) {
                                                (oThis.dfltVersion == response.vehicules[oThis.selectLvl1.selected - 1].finitions[oThis.selectLvl2.selected - 1].versions[i].id) ? oThis.lvl3slctd = "selected": oThis.lvl3slctd = "";
                                                $('<option value="' + response.vehicules[oThis.selectLvl1.selected - 1].finitions[oThis.selectLvl2.selected - 1].versions[i].id + '" ' + oThis.lvl3slctd + '>' + response.vehicules[oThis.selectLvl1.selected - 1].finitions[oThis.selectLvl2.selected - 1].versions[i].name + '</option>').appendTo(oThis.selectLvl3);

                                                if ("selected" == oThis.lvl3slctd) {
                                                    oThis.selectLvl3.change();
                                                }
                                            }
                                        }

                                    }
                                    oThis.dfltFinition = "";

                                });
                                // FEEDING SELECT SUB-LEVEL2
                                for (var i in response.vehicules[oThis.selectLvl1.selected - 1].finitions) {
                                    (oThis.dfltFinition == response.vehicules[oThis.selectLvl1.selected - 1].finitions[i].id) ? oThis.lvl2slctd = "selected": oThis.lvl2slctd = "";
                                    $('<option value="' + response.vehicules[oThis.selectLvl1.selected - 1].finitions[i].id + '" ' + oThis.lvl2slctd + '>' + response.vehicules[oThis.selectLvl1.selected - 1].finitions[i].name + '</option>').appendTo(oThis.selectLvl2);

                                    if ("selected" == oThis.lvl2slctd) {
                                        oThis.selectLvl2.change();
                                    }
                                }

                            }

                        }
                        oThis.dfltVehicule = "";
                    });


                    // LOOPING JSON's ANSWER AND BUILDING FIRST SELECT's OPTIONS
                    for (var i in response.vehicules) {
                        (oThis.dfltVehicule == response.vehicules[i].id) ? oThis.lvl1slctd = "selected": oThis.lvl1slctd = "";
                        $('<option value="' + response.vehicules[i].id + '" ' + oThis.lvl1slctd + '>' + response.vehicules[i].name + '</option>').appendTo(oThis.selectLvl1);
                        // IF THERE'S A DEFAULT VEHICULE SET
                        if ("selected" == oThis.lvl1slctd) {
                            oThis.selectLvl1.change();
                        }
                    }
                }
            }
        });

    }
}
$('.selectfeeder').each(function() {
    new SelectFeeder($(this));
});

// SLIDER FOR DETAILS PAGES V1.0
function swipeContent() {
    if ($('.pagenav.remote').length > 0) {
        var prevBtn = $('.pagenav.remote').find('.prev a');
        var nextBtn = $('.pagenav.remote').find('.next a');
        var prevLink = prevBtn.attr('href');
        var nextLink = nextBtn.attr('href');

        $('.swipezone').swipe({
            swipeLeft: function(event, direction, distance, duration, fingerCount) {
                nextBtn.trigger('click');
                document.location.replace(nextLink);
            },
            swipeRight: function(event, direction, distance, duration, fingerCount) {
                prevBtn.trigger('click');
                document.location.replace(prevLink);
            },
            threshold: 100
        });
    }
}

// SLIDER FOR DETAILS PAGES V2.0
var SlidePages = function(root) {
    this.build(root);
}
SlidePages.prototype = {
    build: function(root) {
        var oThis = this;

        oThis.root = root;
        oThis.pages = $(oThis.root).find('.page')
        oThis.startSlide = 1; // Set to 0 (previous) 1 (actual) 2 (next)
        oThis.currentSlide = oThis.startSlide;
        oThis.oStartSlide = $(oThis.pages).eq(oThis.startSlide);
        oThis.pageW = $(oThis.root).outerWidth();
        oThis.collection = $(oThis.root).attr('data-collection');
        oThis.generator = $(oThis.root).attr('data-generator');
        oThis.actualId = oThis.oStartSlide.attr('data-id');
        oThis.lazyPath = $(oThis.root).attr('data-lazy');
        oThis.shareText = $(oThis.root).attr('data-sharetext');

        oThis.pages.wrapAll("<div class='pages'></div>");

        oThis.init(oThis.root);
    },
    init: function() {
        var oThis = this;

        $(oThis.root).css({
            position: 'relative'
        });
        $(oThis.root).find('.pages').css({
            position: 'absolute',
            top: 0,
            width: (oThis.pageW * oThis.pages.length)
        });
        oThis.pages.css({
            display: 'inline',
            float: 'left',
            width: oThis.pageW
        });

        var slideLeft = $(oThis.pages).eq(oThis.currentSlide).position().left;
        $(oThis.root).find('.pages').css({
            left: -slideLeft
        });
        oThis.setHeight($(oThis.pages).eq(oThis.currentSlide));

        // Getting the ID
        $.ajax({
            url: oThis.collection,
            type: "GET",
            dataType: "json",
            success: function(reponse) {
                oThis.collectionLength = $(reponse.collection).length;
            }
        });
        oThis.initSlide();
    },
    setHeight: function(slide) {
        var oThis = this;
        oThis.pagesH = slide.outerHeight();
        oThis.lazy = slide.find('.lazy');
        if (oThis.lazy.length > 0) {
            oThis.lazy.each(function(index) {
                $(this).on("afterLoadDone", function() {
                    oThis.pagesH = slide.outerHeight();
                    $(oThis.root).animate({
                        height: oThis.pagesH
                    });
                });
            });
        } else {
            $(oThis.root).css({
                height: oThis.pagesH
            });
        }
        $(oThis.root).css({
            overflow: 'hidden'
        });

    },
    initSlide: function(root) {
        var oThis = this;
        $(oThis.root).swipe({
            swipeLeft: function(event, direction, distance, duration, fingerCount) {
                oThis.slideNext();
            },
            swipeRight: function(event, direction, distance, duration, fingerCount) {
                oThis.slidePrev();
            },
            threshold: 30
        });
    },
    slidePrev: function(root) {
        var oThis = this,
            arrival = oThis.currentSlide - 1,
            slideLeft = oThis.pages.eq(arrival).position().left;
        if (oThis.actualId > 1) {
            $(oThis.root.find('.pages')).animate({
                left: -slideLeft
            }, function() {
                oThis.currentSlide = arrival;
                oThis.arrangeSlide(-1);
            });
        }
    },
    slideNext: function(root) {
        var oThis = this,
            arrival = oThis.currentSlide + 1,
            slideLeft = oThis.pages.eq(arrival).position().left;
        if (oThis.actualId < oThis.collectionLength) {
            $(oThis.root.find('.pages')).animate({
                left: -slideLeft
            }, function() {
                oThis.currentSlide = arrival;
                oThis.arrangeSlide(1);
            });
        }
    },
    arrangeSlide: function(direction) {
        // Setting Variables
        var direction = direction,
            oThis = this,
            target = null,
            requestId = null,
            actualId = $(oThis.pages).eq(oThis.currentSlide).attr('data-id'), // /!\ pas sur
            requestIndex = null;
        oThis.actualId = actualId;

        // Setting DIVs in the right order
        if (0 == oThis.currentSlide) {
            $(oThis.pages).eq(oThis.pages.length - 1).prependTo($(oThis.root.find('.pages')));
        } else if (oThis.pages.length - 1 == oThis.currentSlide) {
            $(oThis.pages).eq(0).appendTo($(oThis.root.find('.pages')));
        }

        oThis.currentSlide = 1;
        oThis.pages = $(oThis.root).find('.page');
        var slideLeft = $(oThis.pages).eq(oThis.currentSlide).position().left;
        $(oThis.root).find('.pages').css({
            left: -slideLeft
        });
        oThis.pagesH = oThis.pages.eq(oThis.currentSlide).outerHeight();
        $(oThis.root).animate({
            height: oThis.pagesH
        });
        oThis.setHeight($(oThis.pages).eq(oThis.currentSlide));

        // Setting the receiver
        if (-1 == direction) {
            target = oThis.pages.eq(oThis.currentSlide - 1);
        } else if (1 == direction) {
            target = oThis.pages.eq(oThis.currentSlide + 1);
        }


        shareThisButton(addThisBtns);

        // Getting the ID if there's something more to load
        if (actualId > 1 && actualId < oThis.collectionLength) {
            $.ajax({
                url: oThis.collection,
                type: "GET",
                dataType: "json",
                success: function(reponse) {
                    $(reponse.collection).each(function(index) {
                        if (actualId == this.id) {
                            requestIndex = index + direction;
                            requestId = reponse.collection[requestIndex].id;
                            oThis.callContent(requestId, target);
                        }
                    });
                }
            });
        }
    },
    callContent: function(requestId, target) {
        var oThis = this;

        /* PHP VERSION */
        $.ajax({
            url: oThis.generator + "?dataId=" + requestId,
            type: "GET",
            dataType: "html",
            success: function(response) {
                $(target).html(response);
                $(target).attr('data-id', requestId);
                lazy.change($('.lazy', target));
            }
        });

        /* VERSION TEST JSON //
			$.ajax({
				url: oThis.collection,
				type: "GET",
				dataType: "json",
				success : function(response){
					$(response.collection).each(function(index){
						if(requestId==this.id){

							var contentImg = this.image,
								contentTitle = this.title,
								contentDate = this.date,
								contentHat = this.hat,
								contentText = this.text;

							var template =
								'<div class="head"> \
								<header class="news"> \
								<figure><img class="lazy" src="'+oThis.lazyPath+'" data-original="'+contentImg+'" alt="'+contentTitle+'" /></figure> \
								<div class="txt"> \
								<span class="shareButton st_sharethis_custom" data-text="'+oThis.shareText+'">'+oThis.shareText+'</span> \
								<em class="date">'+contentDate+'</em> \
								<h1 class="title-lvl0"><span>'+contentTitle+'</span></h1> \
								</div> \
								</header> \
								</div>  \
								<section> \
								<div class="addthis_toolbox addthis_default_style social-buttons"> \
								<a class="addthis_button_tweet" tw:count="vertical"></a> \
								<a class="addthis_button_facebook_like" fb:like:layout="box_count" fb:like:height="63"></a> \
								<a class="addthis_button_google_plusone" g:plusone:size="Tall"  ></a> \
								</div> \
								<div class="hat"> \
								'+contentHat+' \
								</div>\
								<div class="text"> \
								'+contentText+' \
								</div>\
								</section>';

							$(target).html(template);
							$(target).attr('data-id',requestId);
							lazy.change($('.lazy',target));
						}
					});
				}
			});
			*/
    }
}
$('.slidepages').each(function() {
    new SlidePages($(this));
});

// SCROLLABLE TABLES AND ASSOCIATED NOTES DISPLAYING
var tabScroll = new Array();
var iniTables = function(root) {
    this.init(root);
}
iniTables.prototype = {
    init: function(root) {
        var oThis = this;
        oThis.root = root;
        oThis.note = $(oThis.root).find('.table-scroll-note');
        oThis.tables = $(oThis.root).find('table');

        if (oThis.note.length > 0) oThis.noteInit();
        if (oThis.tables.length > 0) oThis.build();

    },
    build: function(root) {
        var oThis = this;
        oThis.tables.each(function(index, el) {
            if ($(el).parents('.accordion.onglets')) {
                $(this).on('tablescroll', function(){
                    oThis.iscroll(el, index);
                });
            } else {
                oThis.iscroll(el, index);
            }
        });
    },
    iscroll: function(el, index) {
        var oThis = this;
        var context = $(el).parents('.text, .notice-text');
        $(el).wrap('<div class="table-scroll" id="tabscroll' + index + '"></div>');
        $('#tabscroll' + index).css({
            height: $(el).outerHeight()
        });
        tabScroll[index] = new iScroll('tabscroll' + index, {
            vScroll: false,
            hScrollbar: false,
            vScrollbar: false,
            bounce: true,
            useTransform: true,
            onBeforeScrollStart: null,
            onScrollMove: function() {
                oThis.note.fadeOut(1000);
            }
        });
        tabScroll[index].refresh();

    },
    noteInit: function() {
        var oThis = this;

        if (oThis.note.hasClass('simple') && sessionStorage.clickcount != 1) {
            //oThis.note.css({display:'block'});
        }
        oThis.note.click(function() {
            oThis.note.css({
                display: 'none'
            });
            var tog = 1;

            if (oThis.note.hasClass('simple')) {
                sessionStorage.clickcount = 1;
            }
        });
        var tog = 0;
    }
}
$('.swipezone').each(function() {
    new iniTables($(this));
});

// SOCIAL WALL
try {
    var $container = $('.masonry');
    if (0 < $container.length) {
        //$('.zoner').each(zoner.build);
        var loader = new Loader();
        var url = $container.attr('data-ws'),
            start = 0,
            end = 5,
            package = 5,
            nextstart = 0,
            addItems = function(start) {
                loader.show();
                $.ajax({
                    url: url,
                    data: {
                        start: start || 0
                    },
                    success: function(response) {
                        package = response.length;
                        end = start + eval(package - 1);
                        var tpl = $('#masonryTpl').html();
                        var datas = [];
                        $(response.data).each(function(i) {
                            datas.push(response.data[i]);
                        });
                        var newJson = {
                            "length": response.length,
                            "data": datas,
                            "nextstart": nextstart
                        };
                        var compiledTemplate = _.template(tpl, {
                            obj: newJson
                        });
                        $container.append(compiledTemplate);
                        $container.find('.added').each(function(index) {
                            $(this).removeClass('added').find('img').load(function() {
                                $container.masonry();
                            });
                            $container.masonry('appended', this);
                        });
                        loader.hide();
                        $container.masonry();
                        if (response.nextstart) {
                            $('.masonry + .addmore a').show().unbind('click').click(function() {
                                addItems(response.nextstart);
                            });
                        } else {
                            $('.masonry + .addmore a').remove();
                        };
                    }
                });
            };
        if (url) {
            /* Initialize masonry */
            $container.masonry({
                singleMode: true,
                resizeable: true,
                columnWidth: '.item',
                itemSelector: '.item',
                isAnimated: false
            });
            /* Initialize updater */
            //$('.masonry + .addmore a').hide();
            /* Get first set */
            addItems(start);
        };
    };
} catch (e) {};

// MENU
var menu = {
    $dom: $('.nav'),
    $search: $('.content').find('header').find('.search'),
    $btn: $('.content').find('header').find('.menu'),
    build: function() {
        
        $('.nav nav li.upper').each(function(index, el) {
            /* GESTION DES CLASSES SUR LES NIVEAU 1 */
            if (0 == index) {
                $(el).addClass('first-of-type');
            }
            if ($('.nav nav li.upper').length - 1 == index) {
                $(el).addClass('last-of-type');
            }

            /* GESTION DES CLASSES SUR LES NIVEAU 2 */
            if ($('li', el).length > 0) {
                $('li', el).each(function(index, el2) {

                    if (0 == index) {
                        $(el2).addClass('first-of-type');
                    }
                    if ($('li', el).length - 1 == index) {
                        $(el2).addClass('last-of-type');
                    }
                });
            }
            /* SI PAS DE NIVEAU 2, CHANGEMENT DE STYLE DU N1 */
            else {
                $(el).addClass('no-child');
            }
        });
        $('.nav nav li.brd').each(function(index, el) {
            if (0 == index) {
                $(el).addClass('first-of-type');
            }
            if ($('.nav nav li.brd').length - 1 == index) {
                $(el).addClass('last-of-type');
            }
        });
        $(this).click(menu.toggle);
        if (navigator.userAgent.toLowerCase().indexOf('android') > -1) {
            $('.nav nav a').bind('touchstart', function() {
                $(this).addClass('fake-active');
            }).bind('touchend', function() {
                $(this).removeClass('fake-active');
            }).bind("touchcancel", function() {
                var $this = $(this);
                $this.removeClass('fake-active');
            });
        }

        myScroll = new iScroll('wrapper', {
            hScrollbar: false,
            vScrollbar: false,
            bounce: false,
            onBeforeScrollStart: function(e) {
                var target = e.target;
                while (target.nodeType != 1) target = target.parentNode;

                if (target.tagName != 'SELECT' && target.tagName != 'INPUT' && target.tagName != 'TEXTAREA')
                    e.preventDefault();
            },
            onTouchEnd: function(){
                
            }
        });
        myScroll.refresh();
    },
    toggle: function() {
        var open = menu.$dom.hasClass('open');
        if (open) menu.close();
        else menu.open();
    },
    open: function() {
        menu.$btn.addClass('on');
        menu.$dom.addClass('open');
        $('nav').bind('click', function(e) {
            $('#search').blur();
        });
        $('#search').bind('focus, click, tapone', function(e) {
            e.preventDefault();
            e.stopPropagation();
        });
        $('.content').bind('click', function() {
            $('.content').bind('click', menu.close);
            $('#search').blur();
        });

        var testMsGesture = window.navigator.msPointerEnabled;
        if (testMsGesture) {
            $('body').addClass('scroll-no-touch-action');
        }

        setTimeout(function() {
            $('body').css({
                overflow: 'hidden'
            });
            $('.content').css({
                overflow: 'hidden'
            });
            $('.content').css({
                height: '100%'
            });
            $('.container').css({
                height: '100%'
            });
            myScroll.refresh();
        }, 0);
    },
    close: function(e) {
        if (e) {
            e.preventDefault();
            e.stopPropagation();
        };
        menu.$dom.removeClass('open');
        menu.$btn.removeClass('on');
        $('nav').unbind('click');
        $('#search').unbind('focus, click, tapone');
        $('.content').unbind('click');
        $('.nav nav a').unbind('touchstart, touchend, touchcancel');

        var testMsGesture = window.navigator.msPointerEnabled;
        if (testMsGesture) {
            $('body').removeClass('scroll-no-touch-action');
        }
        $('body').css({
            overflow: 'auto'
        });
        $('.content').css({
            overflow: 'auto'
        });
        $('.container').css({
            height: 'auto'
        });
    }
};
$('header .search').click(function(e) {
    if (!$('.nav').hasClass('open')) $('header .menu').trigger('click');
    $('#search').focus();
    e.stopPropagation();
    e.preventDefault();
});
$('header .menu').each(menu.build);

// MENU -> DROPDOWN FOR LVL1 NAVIGATION
var dropDownMenu = function() {
    var $li = $('.nav').find('.upper'),
        $li_a = $li.find(" > a");
    var opened = 'null';

    $li_a.each(function(i, el) {

        $(el).bind('click', function(e) {
            
            var _this = $(el).parent();
            //prevent same event from firing twice
            if (isJQMGhostClick(e)) {
                return;
            }
            if (!_this.hasClass('no-child')) {
                e.preventDefault();
                e.stopPropagation();

                if (!_this.hasClass('open') && _this.hasClass('upper')) {
                    if (opened != 'null') {
                        $li.eq(opened).find('ul').slideUp(150, function() {
                            $li.eq(opened).removeClass('open')
                        });

                    }
                    _this.addClass('open').find('ul').slideDown(200, function() {
                        opened = i;
                        setTimeout(function() {
                            myScroll.refresh();
                        }, 0);
                    });
                } else {
                    _this.find('ul').slideUp(150, function() {
                        _this.removeClass('open');
                        opened = 'null';
                        setTimeout(function() {
                            myScroll.refresh();
                        }, 0);
                    });
                }
            }
        });
    });
}
var setAccordions = function() {

    $('.showroom-mobile .accordion').each(function() {
        var dftStyles = $(this).attr('data-off');
        var onStyles = $(this).attr('data-on');
        if($(this).attr('data-firstoff') && $(this).attr('data-firsthover')){
            var dftFirstStyles = $(this).attr('data-firstoff');
            var hvrFirstStyles = $(this).attr('data-firsthover');
        }

        $(this).find('a').each(function(index) {
            if(dftFirstStyles != undefined && index==0){
                $(this).attr('style', dftFirstStyles);
           
                $(this).on('mouseenter', function(e) {
                    e.stopPropagation();
                    $(this).attr('style', hvrFirstStyles);
                   
                }).on('mouseleave', function(e) {
                    e.stopPropagation();
                    $(this).attr('style', dftFirstStyles);
                });

            } else {
                $(this).attr('style', dftStyles);
           
                $(this).on('mouseenter', function(e) {
                    e.stopPropagation();
                    $(this).attr('style', hvrStyles);
                    //console.log('hover / '+hvrStyles)
                   
                }).on('mouseleave', function(e) {
                    e.stopPropagation();
                    $(this).attr('style', dftStyles);
                });
            }

        });
    });
}
setAccordions();

// ACCORDION - TOGGLE
var Accordion = function(root) {
    this.init(root);
}
Accordion.prototype = {
    init: function(root) {
        var oThis = this;
        oThis.root = root;
        oThis.items = $(oThis.root).find('.item');
        oThis.section = $(oThis.root).parent('section');
        oThis.itemDflt = eval($(oThis.root).attr('data-init'));
        ($(oThis.root).attr('data-tie') != "all") ? oThis.tie = false: oThis.tie = true;
        if($(oThis.root).parents().hasClass('showroom-mobile')){
            oThis.dftStyles = $(oThis.root).attr('data-off');
            oThis.onStyles = $(oThis.root).attr('data-on');
        }
        oThis.items.each(function(i) {
            var oItem = this;
            var head = $(oItem).prev();
            $(head).attr('style',oThis.dftStyles);
            if (oThis.itemDflt == i){
                $(head).addClass('open');
                $(head).attr('style',oThis.onStyles);
            }
            if (!$(head).hasClass('open')) {
                $(head).addClass('close');
                $(oItem).addClass('close');
                $(head).attr('style',oThis.dftStyles);
                $(oItem).hide();
            } else {
                $(oItem).addClass('open');
                $(head).attr('style',oThis.onStyles);
            }
            $(head).each(function(index, el) {
                $(this).on('click', function() {
                    ($(el).hasClass('open')) ? oThis.close(oItem): oThis.open(oItem);
                });

            });
        });

    },
    open: function(element) {
        var oThis = this;
        $(element).show();
        if (oThis.tie == true) {
            $(oThis.items).each(function() {
                if (this != element) {
                    oThis.close(this)
                }
            });
        }
        if($(element).find('table').length>0){
            $('body').trigger('tablescroll');
        }
        var head = $(element).prev();
        $(head).removeClass('close');
        $(head).addClass('open');
        $(head).attr('style',oThis.onStyles);
        $(element).removeClass('close');
        $(element).addClass('open');
        var imgs = $(this).find('img.lazy');
        lazy.change(imgs);
        //globalRefresh();
        $(window).trigger('resize');

    },
    close: function(element) {
        var oThis = this;
        $(element).hide();
        var head = $(element).prev();
        $(head).removeClass('open');
        $(head).addClass('close');
        $(head).attr('style',oThis.dftStyles);
        $(element).removeClass('open');
        $(element).addClass('close');
    }

}
$('.accordion').each(function() {
    new Accordion($(this));
});

// 360deg view



var create360view = function(root) {
    this.init(root);
}
create360view.prototype = {
    init: function(root) {
        var oThis = this;
        oThis.root = root;
        oThis.view = $(oThis.root).find('.view-360');
        oThis.statusView = 0;
        oThis.tabImages = window.tabImages;

        $(window).on('resize orientation', function(e){
            oThis.setDim();
        });


        if(oThis.tabImages!="undefined" && oThis.tabImages.length>0){
            //$(oThis.view).css({'top':-($(window).height())});
            /* SWITCH VIEWS */
            if($(oThis.root).find('.view-selector').length>0){
                var canvas = document.createElement('canvas'), context;
				  
				//iOS browser dectector :
				var isiOS = { 

					browser: (/iPad|iPhone|iPod/.test(navigator.platform)), 

					detectedVersion: function () { 
													if (!!window.indexedDB) { return 8; } 					// iOS 8
													if (!!window.SpeechSynthesisUtterance) { return 7; } 	// iOS 7
													if (!!window.webkitAudioContext) { return 6; }  		// iOS 6
													if (!!window.matchMedia) { return 5; } 					// iOS 5
													if (!!window.history && 'pushState' in window.history) { return 4; }  // iOS 4
													return 100; // Si non trouvé, on considère que la version est la plus récente.
					 } 
				};				  
				  
                if ( canvas.getContext && !(isiOS.browser && isiOS.detectedVersion() <= 7)   ) {
                    $(oThis.root).find('.view-selector').css({'display':'inline-block'});
                }
                
                $(oThis.root).find('.view-selector').find('> div').each(function(){
                    if($(this).hasClass('inside')){
                        $(this).find('a').on('click', function(e){
                            e.preventDefault();
                            oThis.build(oThis.root);
                            $(this).addClass('active');
                            oThis.statusView = 1;
                        });
                    }
                });
                $(oThis.view).find('.closer').on('click', function(){
                    $(oThis.view).css({'top':-3000});
                    $(oThis.root).find('.active').removeClass('active');
                });
  
            }
        }
    },
    setDim : function(){
        var oThis = this;
        $(oThis.view).find('canvas').css({
            'width':$(oThis.view).width(),
            'height':$(oThis.view).height()
        })
    },
    build: function(root){
        var oThis = this;
		oThis.root = root;
		
        if(oThis.statusView != 1){
            var canvas = document.createElement('canvas'), context;
            if (canvas.getContext) {
               
                oThis.loader = new Loader(oThis.root);
                oThis.loader.show();
                oThis.scriptsLoaded=0;
                oThis.loadScript(oThis.root);
            }
			
            oThis.setDim();
			
        } else {
            oThis.loader.hide();
            $(oThis.view).css({'top':0});
        }

    },
    loadScript: function(root){
        var oThis = this;
        oThis.root = root

            var t = $.getScript( tabScripts[oThis.scriptsLoaded] )
            .done(function( script, textStatus ) {
                if(oThis.scriptsLoaded<tabScripts.length-1){
                    oThis.scriptsLoaded = oThis.scriptsLoaded +1;
                    oThis.loadScript(oThis.root);

                } else {
                    $(oThis.view).css({'top':0});
                    oThis.loader.hide();
                    oThis.callBack360()
                }

            })
            .fail(function( jqxhr, settings, exception ) {
                
                $(oThis.root).find('.view-selector').find('> div').each(function(){
                    if($(this).hasClass('inside')){
                        $(this).find('a').hide();
                    }
                });


                //console.log( tabScripts[me.scriptsLoaded] );
                //console.log( exception );
                //console.log('error loading JS')
            });

    },
       callBack360: function(inc){
        var oThis = this;
        //console.log('All scripts loaded');
        (function($, Inside, PointOfInterest) {
            "use strict";
            oThis.inside = new Inside(document.getElementById('canvas'), $(oThis.view));
            console.log(oThis.inside);
            oThis.inside.cubeSize = 100;
            oThis.inside.init(oThis.tabImages);
            oThis.inside.start();
        }(window.jQuery, NameSpace('inside.Inside'), NameSpace('inside.object3D.PointOfInterest')));
        
            gtmCit.initVue360($('.view-360'));
            $('.view-360').on('mousedown touchstart', function(){
			
                if($(this).hasClass('init')){
                    $(this).removeClass('init');
                }
            })
    }
}
$('.color-selector').each(function() {
    if($(this).find('.view-360').length>0){
        this.myView = new create360view($(this));
    }
});

// SLIDER - USING BXSLIDER

var createSlider = function(root) {
    this.init(root);
}
createSlider.prototype = {
    init: function(root) {
        var oThis = this;
        oThis.root = root;
        // LOADING
        $(oThis.root).addClass('set');
        oThis.loader = new Loader(root);
        oThis.loader.show();

        oThis.row = $(oThis.root).find('> div.row');
        oThis.items = $(oThis.row).find('> article');
        oThis.imgs = $(oThis.row).find('img.lazy');
        oThis.class = $(oThis.row).attr('class');
        oThis.colnum = parseInt(oThis.class.substr(oThis.class.indexOf('of') + 2, 1));
        oThis.margin = (1 == oThis.colnum || $(oThis.row).hasClass('collapse')) ? 0 : 10;
        oThis.infloop = ($(oThis.root).hasClass('evf') == true) ? false : true;
        oThis.checkLoad = 0;

        if( $(oThis.root).parents().hasClass('showroom-mobile')){
            oThis.pagerOff = $(oThis.root).attr('data-pageroff');
            oThis.brdr = $(oThis.root).attr('data-border');

            oThis.pagerOn = $(oThis.root).attr('data-pageron');
            //console.log(oThis.pagerOff)
        }



        oThis.isCss;

        if ((navigator.userAgent.match(/iPhone/i)) || (navigator.userAgent.match(/iPod/i))) {
            var ver = iOSversion();
            if (undefined != ver) {
                if (ver[0] > 6) {
                    oThis.isCss = false;
                } else {
                    oThis.isCss = true;
                }
            } else {
                oThis.isCss = true;
            }
        } else {
            oThis.isCss = true;
        }

        lazy.slider(oThis.imgs);
        if (oThis.imgs.length > 0) {
            oThis.imgs.on("afterLoadDone", function() {
                oThis.checkLoad = oThis.checkLoad + 1;
                if (oThis.checkLoad == oThis.imgs.length) {
                    $(oThis.root).removeClass('set');
                    if (oThis.items.length > oThis.colnum) {
                        oThis.build(root);
                    } else {
                        oThis.loader.hide();
                    }
                }
                //setTimeout(function(){ $(window).trigger('resize'); }, 1);
            });
        } else {
            $(oThis.root).removeClass('set');
            if (oThis.items.length > oThis.colnum) {
                oThis.build(root);
            } else {
                oThis.loader.hide();
            }
        }

    },
    build: function(root) {
        var oThis = this;

        oThis.obj = {
            infiniteLoop: oThis.infloop,
            speed: 250,
            slideWidth: 5000,
            minSlides: oThis.colnum,
            maxSlides: oThis.colnum,
            moveSlides: oThis.colnum,
            adaptiveHeight: true,
            adaptiveHeightSpeed: 100,
            useCSS: oThis.isCss,
            slideMargin: oThis.margin,
            swipeThreshold: 50,
            oneToOneTouch: true,
            onSliderLoad: function() {
                var slideQty = oThis.row.getSlideCount();
                if (slideQty <= oThis.colnum) {
                    $(".bx-default-pager", $(oThis.root)).hide()
                }
                //console.log(oThis.brdr);
                var concStyle =  $(oThis.root).find('.bx-wrapper').attr('style') + oThis.brdr;
                $(oThis.root).find('.bx-wrapper').attr('style', concStyle);
                oThis.loader.hide();
                
                //CPW-4297
                $(oThis.root).find('.bx-clone').each(function(ind, el) {
                    $(el).attr('id', $(el).attr('id') + '_transition');
                });

            },
            onSlideBefore: function(dom, oldi, newi) {
                // Has GTM
                if($(oThis.root).attr('data-gtm-init')){

                }

                // Has associated content 'dynscript'
                var dyn = dom.find('.btn.dynscript').get(0);
                if (dyn) {
                    var $placeholders = $('#car-details .hubs');

                    if ($placeholders.length > 0) {

                        if (dyn._opened) {
                            $placeholders.hide();
                            $('.car-details' + newi).show();
                        } else {
                            $placeholders.hide();
                        };

                    };

                };

            },
            onSlideAfter: function(dom, oldi, newi) {
            }
        };
        $(oThis.root).attr('data-gtm-init', '0')
        oThis.row.bxSlider(oThis.obj);
        gtmCit.initSlider($(oThis.root));

    }

}
$(document).ready(function(){
    $('.slider').each(function() {
        this.mySlider = new createSlider($(this));
    });
});

var changeSlide = false;

// FORMS -> CHECKBOXES
var checkboxSkin = function() {
    var $form_lvl = $('.form-lvl');

    $form_lvl.find('label').bind("click", function() {}); // iphone click

    $form_lvl.find('input[type=checkbox]').bind("change", function() {

        var _this = $(this),
            nxt = _this.prev();

        if (!_this.is(':checked')) {
            nxt.removeClass('active');
            _this.attr('checked', '');
        } else {
            nxt.addClass('active');
            _this.attr('checked', 'checked');
        }
    });

    // reset form filters
    /*
	$('.reset').find('button').click( function(){
		$slide.slider("option", "value", $('#price').val());
    	$slide.slider("value", $slide.slider("value"));
    	$( "#amount" ).text(  $( "#slider" ).slider( "value" ) + " �" );
    	$( "#price" ).val(  $( "#slider" ).slider( "value" ) );
		$('#amount').css({ left : $('#slider .ui-slider-handle').position().left });

    	$form_lvl.find('.active').removeClass('active');
    	$form_lvl.find('input').attr('checked',false);
	});
	*/
}


// CAR SELECTOR -> SLIDER FILTER
if ($("#slider").length) {
    var devisebefore = $("#devisebefore").val();
    var deviseafter = $("#deviseafter").val();
    var amountprice = function(event, ui) {
        $("#amount").text("< " + devisebefore + " " + $('#slider').val() + " " + deviseafter);
        $("#price").val($('#slider').val());
        if ($('.noUi-handle').css('left') == '0px') {
            $('.noUi-handle').css('left', '-1px');
        }
    };
    /*
    	var $slide =$("#slider").slider({
    		value: eval($('#price').val()),
    		min: eval($('#price').attr('data-from')),
    		max: eval($('#price').attr('data-to')),
    		step: eval($('#price').attr('data-step')),
    		slide: amountprice,
    		change: function( event, ui ){
    			//call function to filterCarSelector
    			if( !changeSlide ){
    				//filterCarSelector();
    			}
    		},
    		stop: amountprice,
    		animate: "fast"

    	});
    	*/
    var sliderRange = $("#slider");
    sliderRange.noUiSlider({
        start: eval($('#price').val()), //
        range: {
            'min': eval($('#price').attr('data-from')), //
            'max': eval($('#price').attr('data-to')) //
        },
        step: eval($('#price').attr('data-step')) //
    });
    var dragHalf = $('#slider .noUi-handle').width() / 2;

    sliderRange.on({
        slide: function() {
            amountprice();
            //$( "#amount" ).css({ left: $('#slider .noUi-handle').position().left - dragHalf });
        }
    });

    $("#amount").appendTo($('#slider .noUi-handle'));
    $("#amount").text("< " + devisebefore + " " + $("#slider").val() + " " + deviseafter);
    $("#price").val($("#slider").val())
}

// CAR SELECTOR AND FILTERS
var CarSelector = function(root) {
    this.init(root);
}
CarSelector.prototype = {
    init: function(root) {
        this.root = $(root);
        this.inputs = this.root.find('input');
        this.mastercarsGroup = $('.mastercars-group');
        this.resetButton = this.root.find('.reset button');
        this.loader = new Loader();

        this.setHandlers();
    },
    setHandlers: function() {
        var oThis = this;
        this.inputs.click(function() {
            oThis.ajaxPost();
        });


        var sliderRange = $("#slider");
        sliderRange.on({
            set: function() {
                oThis.ajaxPost();
                //$( "#amount" ).css({ left: $('#slider .noUi-handle').position().left - dragHalf });
            }
        });

        this.resetButton.click(function(e) {
            e.preventDefault();
            oThis.resetForm();
        });
    },
    ajaxPost: function() {
        var oThis = this;

        var request = $.ajax({
            url: oThis.root.attr('action'),
            type: "POST",
            data: oThis.root.serialize(),
            dataType: "html",
            beforeSend: function(jqXHR, textStatus) {
                scrollTopV = $(window).scrollTop();

                $('.content').css({
                    top: -scrollTopV,
                    left: 0
                });

                $('.container').addClass('popopen');
                $('.content').addClass('popopen');

                $('.container').css({
                    height: $(window).height()
                });

                oThis.loader.show();
                $('.loading').css({
                    width: $(window).width(),
                    height: $(window).height()
                });
            }
        });

        request.done(function(response) {

            $('.container').removeClass('popopen');
            $('.content').removeClass('popopen');
            $('.content').css({
                top: 0,
                left: 0
            });
            $('.container').css({
                height: 'auto'
            });
            $(window).scrollTo(scrollTopV, 0);

            oThis.loader.hide();
            //$(window).scrollTo( oThis.mastercarsGroup, 250);
            oThis.mastercarsGroup.html(response);
            lazy.change(oThis.mastercarsGroup.find('img.lazy'));
        });

        request.fail(function(jqXHR, textStatus) {});
        /*
			request.beforeSend(function( jqXHR, textStatus ) {
					oThis.loader.show();
			});*/
    },
    resetForm: function() {
        this.root[0].reset();
        this.root.find('input[type="checkbox"], input[type="radio"]').removeAttr('checked');
        $("#slider").val(eval($('#price').attr('data-to')));
        $("#amount").text("< " + devisebefore + " " + $("#slider").val() + " " + deviseafter);
        $("#price").val($("#slider").val());

        this.root.find('.active').removeClass('active');
        this.root.find('input').attr('checked', false);

        this.root.find('.range').each(function() {
            var input = this.getElementsByTagName('input')[0],
                defaultValue = input.getAttribute('data-to');

            input.value = defaultValue;
            if (input.name === 'passengers') {
                input.value = input.getAttribute('data-from');
            }

            $(input).trigger('change');
        });

        this.root.find('input[value*=TOUT]').each(function() {
            this.checked = true;
        });
        this.ajaxPost();
    }
}

// CAR SELECTOR -> SELECT SKIN
var selectSkin = function() {
    var $select = $('.select'),
        $box_lvl2 = $('.box-lvl2'),
        $url = $select.parents('form').attr('action');

    if ($select.length) {
        //$select.select2();
        $select.change(function() {
            var _this = $(this),
                _val = _this.val();

            if (_val) {

                // CAS "MY PROJECT" RECUPERATION DE VERSION DE VEHICULE
                if ($select.parents('form').hasClass('evv')) {
                    var opt = $('option:selected', $select).index() - 1;
                    $select.parents('form').find('.row').css({
                        display: 'none'
                    });
                    if (opt >= 0) {
                        $select.parents('form').find('.row').eq(opt).css({
                            display: 'block'
                        });
                    }
                } /* FIN */

                $.ajax({
                    type: 'GET',
                    url: $url,
                    data: $select.parents('form').serialize(),
                    dataType: 'html',
                    success: function(data) {
                        $box_lvl2.find('.accordion').html(data).show();
                        $('.accordion', $box_lvl2).each(accordion.build);

                    },
                    error: function(x, y, z) {
                        alert('error - loading content on ' + $url);
                    }
                });
            }
        });
    }
}


// CAR DETAILS -> FINISH CHECKBOXES
if ($('#edit-vehicle-finish').length > 0) {
    $('#edit-vehicle-finish').each(function(index, el) {
        var sdefvalue = $('#s-finish', el).val();

        $('.check-label', el).each(function(index, elm) {
            $(elm).bind('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                if ($(elm).attr('data-value') != $('#s-finish', el).val()) {
                    $('#s-finish', el).val($(elm).attr('data-value'));
                    $('.check-label', el).removeClass('checked');
                    $(elm).addClass('checked');
                } else {
                    $('#s-finish', el).val('');
                    $('.check-label', el).removeClass('checked');
                }
                // DISPLAYING THE SET FINISH VALUE
                alert('Recording' + $('#s-finish', el).val() + 'in the DB');
            });
        });

    });
}

// CAR DETAILS -> DISPLAY EQUIPMENTS & TECHNICAL NOTICE
if ($('.btn.dynscript').length > 0) {
    var tpl = $('#itemTpl').html(),
        $placeholder = $('#car-details'),
        toggle = [];
    $('.btn.dynscript').each(function(index, el) {
        toggle[index] = 0;
        $(el).bind('click', function(e) {

            e.preventDefault();
            e.stopPropagation();

            var tmpfinish = $(el).parents('article.col').find($('.check-label'));
            var tmpfinishval = tmpfinish.attr('data-value');

            if (toggle[index] == 0) {
                $placeholder.append($('<div class="hubs car-details' + index + '"></div>'));
                //$placeholder.find('.car-details'+index).html(tpl).find('.accordion').each(accordion.build);

                $('#car-details .accordion').each(function() {
                    new Accordion($(this));
                });
                toggle[index] = 1;
            } else {

            }

            el._opened = true;
            $placeholder.show();
            selectSkin();

            // CAR DETAILS -> FINISH VERSION
            if ($('#edit-vehicle-version').length > 0) {
                $('#edit-vehicle-version').each(function(index, el) {
                    var vdefvalue = $('#s-version').val();

                    $('.check-label', el).each(function(index, elm) {
                        $(elm).bind('click', function(e) {

                            if ($('#s-finish').val() == '' || $('#s-finish').val() != tmpfinishval) {
                                tmpfinish.trigger('click');
                            }
                            if ($('#s-version').val() != $(elm).attr('data-value')) {
                                $('#s-version').val($(elm).attr('data-value'));
                                $('.check-label', el).removeClass('checked');
                                $(elm).addClass('checked');
                            } else {
                                $('#s-version').val('');
                                $('.check-label', el).removeClass('checked');
                            }
                            // DISPLAYING THE SET FINISH VALUE
                            alert('Recording' + $('#s-version').val() + 'in the DB');
                        });
                    });

                });
            };
        });
    });
}

// VEHICLE'S COLOR SELECTION
var colors = {
    build: function() {

        /* Vars */
        var me = this,
            $me = $(me),
            $bg = $me.find('figure .lazy'),
            $colors = $me.find('ul a');

        me.current = null;

        /* Delay init */
        $bg.load(function() {
            if ($(this).hasClass('lazy')) return false;
            colors.change.call(me, null, $colors.get(0));
        });

        /* Events */
        $colors.click(function(e) {
            colors.change.call(me, e, this)
        });

        console.log('colors built');

    },
    change: function(e, link) {

        /* Vars */
        var me = this;

        if (e) e.preventDefault();

        /* First click create image */
        if (!link.img) {

            var src = link.getAttribute('href');
            var srcLazy = link.getAttribute('data-lazy');

            link.img = document.createElement('img');
            link.img.className = 'lazy';
            link.img.setAttribute("data-original", src);
            link.img.src = srcLazy;
            $(link.img).load(function() {
                colors.show.call(me, link);
            });
            //me.appendChild(link.img);

        } else {

            colors.show.call(me, link);

        };

    },
    show: function(link) {

        /* Vars */
        var me = this;

        if (me.current && link != me.current) {
            colors.hide.call(me, me.current);
        };

        $(me).find('li').removeClass('on');
        $(link).parent().addClass('on');
        $(me).prev('figure').find('img').remove(); // delete old img
        $(link.img).appendTo($(me).prev('figure')).fadeIn();

        // call lazy
        lazy.change($(me).prev('figure').find('.lazy'));

        me.current = link;
    },
    hide: function(link) {
        /* Vars */
        var me = this;

        $(me).find('li').removeClass('on');
        $(link.img).delay(50).fadeOut();
        me.current = null;
    }
};

// COLOR VEHICLE SELECTOR SLIDER
var addSliderThumb = function() {
    if ($('.thumb-slider').length > 0) {
        var $bxpager = $('.bx-pager');
        var $maxSlides = 9;
        if($('.thumb-slider').parents().hasClass('showroom-mobile')){
            var $slideWidth = 39;
        } else {
            var $slideWidth = 27;
        }

        if ($bxpager.find('li').length > 1) {
            if ($bxpager.find('li').length > $maxSlides) {
                $bxpager.find('ul').bxSlider({
                    controls: false,
                    infiniteLoop: false,
                    pager: false,
                    maxSlides: $maxSlides,
                    slideWidth: $slideWidth,
                    slideMargin: 3,
                    moveSlides: 5,
                    onSliderLoad: function() {
                        $('.colors').each(colors.build);
                    }
                });
            } else {
                $('.colors').each(colors.build);
            }
        } else {
            $('.bx-pager').hide();
            $('.colors').each(colors.build);
        }
    }
}

// LINK MY CAR BY Y.M.R
var linkMyCar = function() {
    var $tactileName = $('input[name="tactileName"]'),
        $edgeModal = $('.edge-modal'),
        $secondStep = $("#eligibilite-form"),
        $notlinkedCar = $("#notlinkedCar"),
        $isOklinkedCar = $("#isOklinkedCar"),
        $verifyNumber = $("#verify-number"),
        $edgeNotice = $(".edge-notice, .edge-overlay"),
        $continued = $(".continued"),
        $inputVerifyNumber = $("input[name='verify-number']"),
        $modalContent = $('#edge-modal'),
        $closeModal = $modalContent.find(".close");

    /* CPW-3706. Old behavior, DOM has changed and new handlers are in applications.js
    if ($tactileName.length) {
        $tactileName.on("change", function() {
            var _this = $(this),
                _val = _this.val();

            if (_val) {
                if (_val == 1) {
                    $secondStep.fadeIn("slow");
                    $notlinkedCar.fadeOut("slow");
                };
                if (_val == 0) {
                    $secondStep.fadeOut("slow");
                    $isOklinkedCar.fadeOut("slow");
                    $notlinkedCar.fadeIn("slow");
                };
            };
        });
    };*/
    if ($edgeModal.length) {
        var i = 0;
        $edgeModal.on("click", function(e) {
            var _this = $(this),
                $offset = _this.offset();
            e.preventDefault();
            $modalContent.css({
                "position": "absolute",
                "top": 45,
                "right": 5
            });
            $modalContent.fadeIn(300, function() {
                $(this).focus();
            });
        });
        $inputVerifyNumber.on("blur focus", function(e) {
            $modalContent.fadeOut("slow");
        });
        $inputVerifyNumber.on("keypress", function(e) {
            $modalContent.fadeOut("slow");
            if (i > 17)
                $edgeNotice.fadeIn("slow");
            i++
        });
    };
    if ($verifyNumber.length) {
        $verifyNumber.on("click", function(e) {
            var _this = $(this),
                $offset = _this.offset();
            e.preventDefault();
            $isOklinkedCar.fadeIn("slow");
            $edgeNotice.fadeIn("slow");
        });
        $inputVerifyNumber.on("blur", function(e) {
            $modalContent.fadeOut("slow");
        });
    };
    if ($continued.length) {
        $continued.on("click", function(e) {
            var _this = $(this);
            $secondStep.find("input[type='text']").focus();
            $edgeNotice.fadeOut("slow");
        });
    };
    if ($closeModal.length) {
        $closeModal.on("click", function(e) {
            var _this = $(this);
            _this.closest("#edge-modal").fadeOut("slow");
        });
    };
}

// LOADMORE
var loadmore = {
    build: function() {

        var me = this;

        me.$items = $(me).find('.item');
        me.$way = 1;
        me.$btn = $(me).find('.loadMore');
        me.$toggle = me.$btn.attr('data-toggle');

        me.$btn.click(function() {

            // reset
            if (me.$way == 0) {
                $(me.$items).hide();
                $(me.$items).removeClass('shown');
                $(me.$items).addClass('hidden');
                $(me.$items).eq(0).show();
                $(me.$items).eq(0).removeClass('hidden');
                $(me.$items).eq(0).addClass('shown');
                $('a', me.$btn).html(me.$toggle);
                me.$way = 1;
                return;
            }

            if (me.$way == 1) {
                $(me.$items).removeClass('hidden');
                $(me.$items).addClass('shown');
                $(me.$items).find('img.lazy').lazy();
                $(me.$items).show();
                me.$way = 0;
                me.$toggle = $('a', me.$btn).html();
                $('a', me.$btn).html(me.$btn.attr('data-toggle'));
                return;
            }
        });

    }
}
$('.dyncont').each(loadmore.build);

// INTERACTIVE MOSAIC
var intMosaic = function(root) {
    this.init(root);
}
intMosaic.prototype = {
    init: function(root) {
        oThis = this;
        oThis.root = root;
        oThis.items = $(oThis.root).find('figure');
        oThis.items.each(function() {
            $(this).on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                oThis.display($(this).next());
            });
        });
    },
    display: function(content) {
        $('.int-mosaic .item .ctnt').css({
            display: 'none'
        });
        oThis = this;
        $(content).css({
            display: 'block'
        });
        $('<div class="close"></div>').appendTo($(content));
        $('.close', content).bind('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $('.close', content).remove();
            $(content).css({
                display: 'none'
            });
        });
        return false;
    }
}
$('.int-mosaic .item').each(function() {
    new intMosaic($(this));
});

// NOTES CLOSE BUTTON
if ($('.hb').length > 0) {
    $('.hb').each(function(index, el) {
        var par = $(el).parents('.popin-like');
        $(el).bind('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            par.hide();

        });
    });
}


// MEDIA POPIN

var mediaSlider = null;
var mediaPop = {
    init: function(col) {

        var me = this,
            $me = $(me);

        col.each(function(i, el) {
            var master = $(el).attr('data-bundle');
            if ("" == master || undefined == master) {
                master = "self" + i;
                $(el).attr('data-bundle', master);
            }
            $(el).bind('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                me.built(master, this);
                //lazy.change($('.mediaPop .slideThis img.lazy'));
            });
        });


    },
    built: function(master, elm) {
        var me = this,
            $me = $(me),
            mediaOk = 0,
            pointer = null,
            checkLoad = 0;

        $('body').append($('<div class="mediaMask"></div><div class="mediaPop"><span class="close"></span><div class="loading"><div class="circ"></div></div><div class="slideThis"></div></div>'));

        scrollTopV = $(window).scrollTop();
        $('.content').css({
            top: -scrollTopV,
            left: 0
        });
        $('.container').addClass('popopen');
        $('.content').addClass('popopen');
        $('.container').css({
            height: $(window).height()
        });


        $('.mediaMask, .mediaPop .close').bind('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            me.destroy();
        });

        $('[data-bundle="' + master + '"]').each(function(i, el) {
            if (el == elm) pointer = i;
            var legend = $(el).attr('title');
            var medium = $(el).attr('href');
            var altern = $(el).attr('data-other');

            // TESTS

            var isImg = (/\.jpeg|jpg|gif|png$/).test(medium),
                isVideo = (/\.mp4|webm$/).test(medium),
                isFlash = (/\.flv|swf$/).test(medium);

            var isYoutube = (/youtube.com/).test(medium),
                youtubeUrl = '';

            if (isYoutube) {
                if ((/\/embed/).test(medium)) {
                    youtubeUrl = medium;
                } else {
                    var ytRegExp = new RegExp('(//www\\.youtube\\.com/watch\\?v=)|(//youtu\\.be/)(\\w*)')
                    youtubeUrl = (ytRegExp.test(medium)) ? medium.replace(ytRegExp, '//www.youtube.com/embed/$3') : false;
                }
                youtubeUrl = youtubeUrl.split('&')[0];
            }

            if (isVideo) {
                var sVideoHref = medium.replace('%7C', '|');
                var videos = sVideoHref.split('|'),
                    testExt = /^.+\.([^.]+)$/;
            }


            var builtMedium = '';
            if (isYoutube) {
                builtMedium = document.createElement('iframe');
                builtMedium.src = youtubeUrl;
                builtMedium.setAttribute('width', '100%');
                builtMedium.setAttribute('height', ($(window).width() * 56.25) / 100);
                builtMedium.setAttribute('frameborder', 0);
                builtMedium.setAttribute('allowfullscreen', true);
                mediaOk = 1;
            }
            if (isVideo) {
                if (videos.length < 2) {
                    alert("Can't find every sources");
                    me.destroy();
                    mediaOk = 0;
                } else {
                    builtMedium = document.createElement('video');
                    builtMedium.setAttribute('width', '100%');
                    builtMedium.setAttribute('height', ($(window).width() * 56.25) / 100);
                    builtMedium.setAttribute('controls', true);
                    var source1 = document.createElement('source');
                    var source1Type = videos[0].slice(videos[0].lastIndexOf('.') + 1, videos[0].length);
                    source1.setAttribute('src', videos[0]);
                    source1.setAttribute('type', 'video/' + source1Type);
                    builtMedium.appendChild(source1);
                    var source2 = document.createElement('source');
                    var source2Type = videos[1].slice(videos[1].lastIndexOf('.') + 1, videos[1].length);
                    source2.setAttribute('src', videos[1]);
                    source2.setAttribute('type', 'video/' + source2Type);
                    builtMedium.appendChild(source2);
                    mediaOk = 1;
                }
            }
            if (isImg) {
                builtMedium = document.createElement('img');
                //builtMedium.src = medium;

                builtMedium.className = 'lazy';
                builtMedium.setAttribute("data-original", medium);
                builtMedium.src = altern;
                mediaOk = 1;
            }
            if (isFlash) {
                builtMedium = "SWF not supported";
                mediaOk = 0;
            }

            var mediaCxt = $('<figure><figcaption>' + legend + '</figcaption></figure>');
            mediaCxt.prepend(builtMedium);
            $('.mediaPop .slideThis').append(mediaCxt);
        });


        var isCss = false;

        if ((navigator.userAgent.match(/iPhone/i)) || (navigator.userAgent.match(/iPod/i))) {

            var ver = iOSversion();

            if (undefined != ver) {
                if (ver[0] > 6) {
                    isCss = false;
                } else {
                    isCss = true;
                }
            } else {
                isCss = true;
            }
        } else {
            isCss = false;
        }
        if (1 == mediaOk) {
            if ($('.mediaPop .slideThis img.lazy').length > 0) {
                lazy.change($('.mediaPop .slideThis img.lazy'));
                $('.mediaPop .slideThis img').each(function(index) {
                    $(this).on('afterLoadDone', function() {
                        checkLoad = checkLoad + 1;

                        if (checkLoad == $('.mediaPop .slideThis img').length) {
                            me.slider(pointer, isCss);
                        }
                    });
                });
            } else {
                me.slider(pointer, isCss);
            }
        }
    },
    slider: function(pointer, isCss) {
        var me = this,
            $me = $(me);
            
        var items = $('.mediaPop').find('figure');
        var gtmCloser = function(){
            /* GTM closers sur Galerie Média*/
            var close_logo = $('.mediaPop').find('.close');
            close_logo.attr('data-gtm','eventGTM|Showroom::'+page_vehicule_label+'::MediaGallery|Close::Logo|'+close_logo.text()+'||');

        }
        var gtmArrow = function(currentIndex){
            /* GTM arrows sur Galerie Média*/
            var left = $('.mediaPop').find('a.bx-prev');
            var right = $('.mediaPop').find('a.bx-next');
            if($(items[(currentIndex-1+items.length)%items.length]).find('img').attr('src')){
				var left_file = $(items[(currentIndex-1+items.length)%items.length]).find('img').attr('src').split('/').pop();
			}else{
				  var left_file = '';
			}
			
			if($(items[(currentIndex+1)%items.length]).find('img').attr('src')){
				 var right_file = $(items[(currentIndex+1)%items.length]).find('img').attr('src').split('/').pop();
			}else{
				  var right_file = '';
			}
            left.attr('data-gtm','eventGTM|Showroom::'+page_vehicule_label+'::MediaGallery|Navigation::Arrow::left|'+left_file+'||');
            right.attr('data-gtm','eventGTM|Showroom::'+page_vehicule_label+'::MediaGallery|Navigation::Arrow::right|'+right_file+'||');
        }
        var gtmPager = function(){
            /* GTM pagers sur Galerie Média*/
            $('.mediaPop').find('.bx-pager .bx-pager-item').each(function(){
                var pager_link = $(this).find('a');
                var slide_index = pager_link.attr('data-slide-index');
                var current_file = $(items[slide_index]).find('img').attr('src').split('/').pop();
                pager_link.attr('data-gtm','eventGTM|Showroom::'+page_vehicule_label+'::MediaGallery|Navigation::Pagers::'+slide_index+'|'+current_file+'||');
            });
        }
        
        mediaSlider = $('.mediaPop .slideThis').bxSlider({
            infiniteLoop: false,
            speed: 250,
            // slideWidth: $(window).width(),
            startSlide: pointer,
            minSlides: 1,
            maxSlides: 1,
            moveSlides: 1,
            useCSS: isCss,
            easing: 'linear',
            slideMargin: 0,
            swipeThreshold: 50,
            hideControlOnEnd: true,
            oneToOneTouch: true,
            slideSelector: 'figure',
            onSlideAfter: function(dom, oldi, newi) {
                if (dom.find('video').length > 0) {
                    var repaint = dom.find('video').clone();
                    dom.empty();
                    setTimeout(function() {
                        repaint.appendTo(dom);
                    }, 500);
                }
                
                gtmArrow(newi);
            },
            onSliderLoad: function(currentIndex) {
                $("body").css({
                    'position': 'fixed',
                    'width': '100%'
                });
                $('.mediaPop .bx-wrapper').eq(0).bind('click', function(e) {
                    if ($(e.target).is("figure")) {
                        if (isJQMGhostClick(e)) {
                            return;
                        }
                        me.destroy();
                    }
                });
                $(".mediaPop .bx-viewport").css("overflow", "inherit"); // #4502


                var wH = $(window).height(),
                    wW = $(window).width();

                $('.mediaPop .slideThis figure').each(function(index, el) {
                    // $(el).attr('style', styles)

                    $(el).css({
                        position: 'relative',
                        overflow: 'hidden',
                        height: wH,
                        width: wW
                    });

                    $(el).find('img, iframe, video').each(function() {
                        var oThis = this,
                            iH = $(oThis).height(),
                            iW = $(oThis).width();

                        //console.log("iH : " + iH + " / iW : " + iW)
                        if (wH > wW) {
                            //console.log("device : portrait");
                            var hValue = Math.ceil((iH * wW) / iW),
                                wValue = wW,
                                mLvalue = -(wValue / 2),
                                mTvalue = -(hValue / 2);

                                // IF IMAGE's 'HEIGHT BIGGER THAN WINDOW'S HEIGHT
                                if(hValue > wH){
                                    var hValue = wH,
                                        wValue = Math.ceil((iW * wH) / iH),
                                        mLvalue = -(wValue / 2),
                                        mTvalue = -(hValue / 2);
                                }

                        } else {
                            //console.log("device : landscape");
                            var hValue = wH,
                                wValue = Math.ceil((iW * wH) / iH),
                                mLvalue = -(wValue / 2),
                                mTvalue = -(hValue / 2);

                                // IF IMAGE's 'WIDTH LARGER THAN WINDOW'S WIDTH
                                if(wValue > wW){
                                    var wValue = wW,
                                        hValue = Math.ceil((iH * wW) / iW),
                                        mLvalue = -(wValue / 2),
                                        mTvalue = -(hValue / 2);
                                }
                        }
                        $(oThis).css({
                            position: 'absolute',
                            top: '50%',
                            left: '50%',
                            minHeight: hValue,
                            height: hValue,
                            maxHeight: hValue,
                            minWidth: wValue,
                            width: wValue,
                            maxWidth: wValue

                        });
                        setTimeout(function() {
                            $(oThis).css({
                                marginTop: mTvalue,
                                marginLeft: mLvalue
                            });
                        }, 500);

                    });

                });

                $('.mediaPop .loading').remove();
                if ($('.mediaPop .slideThis figure').eq(currentIndex).find('video').length > 0) {
                    var repaint = $('.mediaPop .slideThis figure').eq(currentIndex).find('video').clone();
                    $('.mediaPop .slideThis figure').eq(currentIndex).empty();
                    setTimeout(function() {
                        repaint.appendTo($('.mediaPop .slideThis figure').eq(currentIndex));
                        $(window).trigger('resize');
                    }, 500);
                }
                
                gtmCloser();
                gtmArrow(currentIndex);
                gtmPager();
            }
            
        });
        /*
                if (mediaSlider) {
                    $('.mediaPop .slideThis').css({
                        width: mediaSlider.getSlideCount() * $(window).width(),
                        height: $(window).height()
                    });
                }
        */
    },
    destroy: function() {
        $('.container').removeClass('popopen');
        $('.content').removeClass('popopen');
        $('.content').css({
            top: 0,
            left: 0
        });
        $('.container').css({
            height: 'auto'
        });
        $(window).scrollTo(scrollTopV, 0);
        if (mediaSlider) {
            mediaSlider.destroySlider();
        }
        $('.mediaPop').remove();
        $('.mediaMask').remove();
        $("body").css("position", "relative");
    }
}
if ($('.galleryPop').length > 0) mediaPop.init($('.galleryPop'));

// LEGAL NOTICES POPIN
var noticePop = function(root, index) {
    this.init(root, index);
}
noticePop.prototype = {
    init: function(root, index) {
        var oThis = this;
        oThis.root = root;
        oThis.loader = new Loader();
        oThis.checkLoad = 0;

        oThis.noticeScroll = new Array();

        oThis.mypos = $(oThis.root).position();
        oThis.mytext = $(oThis.root).next();
        oThis.dataClose = oThis.mytext.attr('data-close');
        if (oThis.mytext.hasClass('notice-text')) {
            $('a', oThis.root).removeAttr('href');
            $(oThis.root).bind('click', function(e) {
                oThis.scrollTopV = $(window).scrollTop();
                oThis.loader.show();
                //prevent same event from firing twice
                if (isJQMGhostClick(e)) {
                    return;
                }
                e.preventDefault();
                e.stopPropagation();

                $('.content').css({
                    top: -oThis.scrollTopV,
                    left: 0
                });

                $('.container').addClass('popopen');
                $('.content').addClass('popopen');

                // AJAX CONTENT DETECTION
                if (oThis.mytext.attr('data-src')) {
                    $.ajax({
                        url: oThis.mytext.attr('data-src'),
                        type: "GET",
                        dataType: "html",
                        success: function(response) {
                            $('<div class="ajax-filled"></div>').appendTo(oThis.mytext);
                            oThis.mytext.find('.ajax-filled').append($(response).find('.swipezone').html());
                            $(oThis.mytext).removeAttr('data-src');
                            mediaPop.init($('.galleryPop', oThis.mytext));
                            oThis.build(index);
                        }
                    });

                } else {
                    oThis.build(index);
                }
            });
        }
    },
    resizeMask: function(){
        $('.mask').css({
            width: $(window).width(),
            height: $(window).height()
        });
    },
    build: function(index) {
        var oThis = this;

        $('.container').css({
            height: $(window).height()
        });

        $('<div class="mask mask-n" style="z-index:199"></div>').prependTo($('body'));
        oThis.resizeMask();
        oThis.mytext.prependTo($('body'));
        oThis.lazyImgs = oThis.mytext.find('.lazy');
        if (oThis.lazyImgs.length > 0) {
            lazy.change(oThis.lazyImgs);

            oThis.lazyImgs.each(function(index) {
                $(this).on('afterLoadDone', function() {

                    oThis.checkLoad = oThis.checkLoad + 1;

                    if (oThis.checkLoad == oThis.lazyImgs.length) {
                        oThis.slider(index);
                    }
                });
            });

        } else {
            oThis.slider(index);
        }
        var testMsGesture = window.navigator.msPointerEnabled;
        if (testMsGesture) {
            $('body').addClass('scroll-no-touch-action');
        }
    },
    slider: function(index) {
        var oThis = this;
        if (Math.floor(($(window).height() * 90) / 100) <= oThis.mytext.height()) {
        
            oThis.mytext.css({
                height: Math.floor(($(window).height() * 90) / 100)
            });

            oThis.noticeScrollID = 'wrapper' + index;

            oThis.mytext.wrapInner('<div id="' + oThis.noticeScrollID + '" class="notice-wrapper"><div class="scrollnotice"></div></div>');

            oThis.noticeScroll[index] = new iScroll(oThis.noticeScrollID, {
                hScrollbar: false,
                vScrollbar: false,
                bounce: false
            });
            oThis.noticeScroll[index].refresh();

            setTimeout(function() {
                oThis.noticeScroll[index].refresh();
            }, 0);
            oThis.mytop = Math.floor((($(window).height() - oThis.mytext.outerHeight()) / 2));
        } else {
            oThis.mytop = Math.floor((($(window).height() - oThis.mytext.outerHeight()) / 2));
        }
        new iniTables(oThis.mytext);
        oThis.mytext.css({
            display: 'block',
            position: 'absolute',
            zIndex: 200,
            left: '5%',
            top: oThis.mytop,
            width: '90%'
        });


        $(window).on('resize', function(){
            oThis.resizeMask();
        })

        $('<div class="fermer">' + oThis.dataClose + '</div><div class="close"></div>').appendTo(oThis.mytext);

        $('.notice-text .fermer, .notice-text .close, .mask').bind('click', function(e) {
            //prevent same event from firing twice
            if (isJQMGhostClick(e)) {
                return;
            }

            e.preventDefault();
            e.stopPropagation();
            oThis.destroy(index);
        });
        oThis.loader.hide();
        return false;
    },
    destroy: function(index) {
        var oThis = this;
        var testMsGesture = window.navigator.msPointerEnabled;
        if (testMsGesture) {
            $('body').removeClass('scroll-no-touch-action');
        }


        $('.notice-text .close').remove();
        $('.notice-text .fermer').remove();
        $('.mask-n').remove();
        oThis.mytext.css({
            display: 'none'
        });
        oThis.mytext.insertAfter($(oThis.root));
        $('.container').removeClass('popopen');
        $('.content').removeClass('popopen');
        $('.content').css({
            top: 0,
            left: 0
        });
        $('.container').css({
            height: 'auto'
        });
        $(window).scrollTo(oThis.scrollTopV, 0);
    }
}
$('.notice, .tooltip').each(function(index) {
    new noticePop($(this), index);
});


// LOGIN POPIN
if ($('.connection-block-popin').length > 0) {
    $('a.login-btn').bind('click, tapone', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $('<div class="mask"></div>').prependTo($('body'));
        $('.connection-block-popin').prependTo($('body'));
        $('.connection-block-popin').css({
            display: 'block',
            position: 'absolute',
            zIndex: 10000,
            left: '5%',
            top: parseInt(($(window).height() - $('.connection-block-popin').height()) / 2) + 'px',
            width: '90%'
        });

        scrollTopV = $(window).scrollTop();
        $('.content').css({
            top: -scrollTopV,
            left: 0
        });
        $('.container').addClass('popopen');
        $('.content').addClass('popopen');
        $('.container').css({
            height: $(window).height()
        });

        //$('body').css({ overflow:'hidden' });
        $('.mask').css({
            width: $(window).width(),
            height: $(window).height()
        });

        $('<div class="close"></div>').appendTo($('.connection-block-popin'));

        $('.connection-block-popin .close, .mask').bind('click, tapone', function(e) {
            //$('body').css({ overflow:'auto' });

            $('.container').removeClass('popopen');
            $('.content').removeClass('popopen');
            $('.content').css({
                top: 0,
                left: 0
            });
            $('.container').css({
                height: 'auto'
            });
            $(window).scrollTo(scrollTopV, 0);
            $('.connection-block-popin').css({
                display: 'none'
            });
            $('.connection-block-popin .close').remove();
            $('.mask').remove();
        });
        return false;
    });
}

// CITROEN ID POPIN
if ($('.citroenid-block-popin').length > 0) {
    $('a.connection-btn').bind('click, tapone', function(e) {
        e.preventDefault();
        e.stopPropagation();
        if ($('.connection-block-popin').length > 0) {
            $('.connection-block-popin').hide();
        }
        if ($('.connection-block-popin').length == 0) {
            $('<div class="mask"></div>').prependTo($('body'));
        }
        $('.citroenid-block-popin').prependTo($('body'));
        $('.citroenid-block-popin').css({
            display: 'block',
            position: 'absolute',
            zIndex: 10000,
            left: '5%',
            top: parseInt(($(window).height() - $('.citroenid-block-popin').height()) / 2) + 'px',
            width: '90%'
        });

        scrollTopV = $(window).scrollTop();
        $('.content').css({
            top: -scrollTopV,
            left: 0
        });
        $('.container').addClass('popopen');
        $('.content').addClass('popopen');
        $('.container').css({
            height: $(window).height()
        });


        //$('body').css({ overflow:'hidden' });

        $('.mask').css({
            width: $(window).width(),
            height: $(window).height()
        });

        $('<div class="close"></div>').appendTo($('.citroenid-block-popin'));

        $('.citroenid-block-popin .close, .mask').bind('click, tapone', function(e) {
            //$('body').css({ overflow:'auto' });
            $('.container').removeClass('popopen');
            $('.content').removeClass('popopen');
            $('.content').css({
                top: 0,
                left: 0
            });
            $('.container').css({
                height: 'auto'
            });
            $(window).scrollTo(scrollTopV, 0);
            $('.citroenid-block-popin').css({
                display: 'none'
            });
            $('.citroenid-block-popin .close').remove();
            $('.mask').remove();
        });
        return false;
    });
}

// SUBSCRIBE POPIN
if ($('.subscribe-block-popin').length > 0) {
    $('a.sb').bind('click, tapone', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $('<div class="mask"></div>').prependTo($('body'));
        $('.subscribe-block-popin').prependTo($('body'));
        $('.subscribe-block-popin').css({
            display: 'block',
            position: 'absolute',
            zIndex: 10000,
            left: '5%',
            top: parseInt(($(window).height() - $('.subscribe-block-popin').height()) / 2) + 'px',
            width: '90%'
        });

        scrollTopV = $(window).scrollTop();
        $('.content').css({
            top: -scrollTopV,
            left: 0
        });
        $('.container').addClass('popopen');
        $('.content').addClass('popopen');
        $('.container').css({
            height: $(window).height()
        });

        //$('body').css({ overflow:'hidden' });

        $('.mask').css({
            width: $(window).width(),
            height: $(window).height()
        });
        $('<div class="close"></div>').appendTo($('.subscribe-block-popin'));

        $('.subscribe-block-popin .close, .mask').bind('click, tapone', function(e) {
            //$('body').css({ overflow:'auto' });
            $('.container').removeClass('popopen');
            $('.content').removeClass('popopen');
            $('.content').css({
                top: 0,
                left: 0
            });
            $('.container').css({
                height: 'auto'
            });
            $(window).scrollTo(scrollTopV, 0);
            $('.subscribe-block-popin').css({
                display: 'none'
            });
            $('.subscribe-block-popin .close').remove();
            $('.mask').remove();
        });
        return false;
    });
}

// COOKIES READING
function readCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }
    // COOKIE INFORMATIONS
$('.closecookies').bind('click', function() {
    $('#block-cookies').hide();
});

function initLocatorPDV() {
    $('.locator').gLocator({
        onLoad: function() {}, // Initialisation
        onList: function() {
            $('#map-canvas').removeAttr('style');
        }, // When results displayed / refreshed
        onItemClick: function(storeId) { // When item (marker or result) is cliked, receives storeId as parameter
            try {
                getCarStock(storeId);
            } catch (e) {}
        },
        onDetails: function() {
            return false;
        }, // When details displayed if return false, default action is prevent
        onGeoloc: function() {},
        onGeolocError: function() {
            /* Display prompt, should use an HTML template */
            var mytext = $('.error-geoloc');
            var mytextClose = mytext.attr('data-close');
            $('.content').css({
                top: -scrollTopV,
                left: 0
            });

            $('.container').addClass('popopen');
            $('.content').addClass('popopen');

            $('.container').css({
                height: $(window).height()
            });
            $('<div class="mask mask-n" style="z-index:199"></div>').prependTo($('.container'));
            $('.mask').css({
                width: $(window).width(),
                height: $(window).height()
            });
            mytext.prependTo($('.container'));
            mytext.css({
                display: 'block',
                position: 'absolute',
                zIndex: 200,
                left: '5%',
                top: Math.floor((($(window).height() - mytext.outerHeight()) / 2)),
                width: '90%'
            });

            $('body').css({
                overflow: 'hidden'
            });

            $('<div class="fermer">' + mytextClose + '</div><div class="close"></div>').appendTo(mytext);

            $('.error-geoloc .fermer, .error-geoloc .close, .mask').bind('click', function(e) {
                $('.error-geoloc .close').remove();
                $('.error-geoloc .fermer').remove();
                $('.mask-n').remove();
                mytext.css({
                    display: 'none'
                });
                $('.container').removeClass('popopen');
                $('.content').removeClass('popopen');
                $('.content').css({
                    top: 0,
                    left: 0
                });
                $('.container').css({
                    height: 'auto'
                });
                $(window).scrollTo(scrollTopV, 0);
            });
            return false;
        }
    });
}

// STORE LOCATOR
if ($.fn.gLocator) {
    if ($('.locator') !== undefined) {
        if ($('.locator').length > 0) {
            // Lancement de l'API
            try {
                if (google) {
                    initLocatorPDV();
                }
            } catch (e) {
                // injecte le script que si celui-ci n'est d�j� pas charg�.
                var script = document.createElement('script');
                script.type = 'text/javascript';
                script.src = googlemapAPI + '&callback=initLocatorPDV';
                document.body.appendChild(script);
            }
        }
    }
};

(function($, win, doc, gtmCit) {
    /* GTM Functions */
    var $doc = $(doc),
        gtmModule = {};

    $doc.on('gtm', function(e) {
        // l'écoute des gtm se fait via la console Google Analytics.
/*
        var splitter = (e.dataGtm).split('|');
        var eventCategory = splitter[1];
        var eventAction = splitter[2];
        var eventLabel = '';

        for (var i = 3; i < splitter.length; i++){
            eventLabel += splitter[i] +' ';
        }
        try{
            console.log('ga(\'citroenTracker.send\', \'event\', ' + eventCategory + ', ' + eventAction + ', ' + eventLabel + ');');
        }
        catch (err) {}

        ga('citroenTracker.send', 'event', eventCategory, eventAction, eventLabel);
*/
    });

    var setTrigger = function(value) {
       // console.log("setTrigger : " + value);
        $doc.trigger({
            type: 'gtm',
            dataGtm: value
        });
    };

    gtmModule.tabs = function(data) {
        this.init(data)
    };
    gtmModule.tabs.prototype = {
        init: function(data) {
            var oThis = this;
            this.data = data[0];

            if (this.data == undefined) {
                return false;
            } else {
                this.sliced = this.data.split('|');
                this.$root = data.root;

                this.$root.find('li').each(function() {

                    $(this).on('click', function() {
                        var status = 'close';
                        if (!$(this).parent().hasClass('on')) {
                            status = 'open';
                        }
                        if (status == 'close') return;

                        oThis.sliced[3] = $(this).text();
                        oThis.data = oThis.sliced[0].concat('|', oThis.sliced[1], '|', oThis.sliced[2], '|', oThis.sliced[3], '|', oThis.sliced[4], '|', oThis.sliced[5]);
                        setTrigger(oThis.data);
                    });
                });
            }

        }
    }

    gtmModule.expandBar = function(data) {
        this.init(data)
    };
    gtmModule.expandBar.prototype = {
        init: function(data) {
            var oThis = this;
            this.data = data[0];

            this.sliced = data[0].split('|');
            //this.sliced[1] = "toggle";
            this.$root = data.root;

            this.$root.on('click', function() {
                var status = 'close';


                var eventLabel = oThis.sliced[3];
                if ($(this).hasClass('open')) {
                    status = 'open';
                    eventLabel = oThis.sliced[2];
                }
                if (oThis.sliced[3] == '' && status == 'close') return;


                oThis.data = oThis.sliced[0].concat('|', oThis.sliced[1], '|', eventLabel, '|', oThis.sliced[4], '|', oThis.sliced[5], '|', oThis.sliced[6]);
                setTrigger(oThis.data);
            });
        }
    }

    gtmModule.searchText = function(data) {
        this.init(data)
    };
    gtmModule.searchText.prototype = {
        init: function(data) {
            var oThis = this;

            this.data = data[0];

            this.sliced = data[0].split('|');
            //this.sliced[1] = "toggle";
            this.$root = data.root;
            var oText = this.$root.find('#searchText');

            this.$root.find('#searchSubmit').on('click', function() {
                var searchText =oText.val();
                oThis.data = oThis.sliced[0].concat('|', oThis.sliced[1], '|',  oThis.sliced[2], '|', searchText, '|', oThis.sliced[4], '|', oThis.sliced[5]);
                setTrigger(oThis.data);

            });
        }
    }

    gtmModule.toggle = function(data) {
        this.init(data)
    };
    gtmModule.toggle.prototype = {
        init: function(data) {
            var oThis = this;
            this.data = data[0];

            this.sliced = data[0].split('|');
            //this.sliced[1] = "toggle";
            this.$root = data.root;
            
            this.$root.on('click', function() { 
                var status = 'close';


                var eventLabel = oThis.sliced[3];
                if (!$(this).parent().hasClass('open') && !$(this).hasClass('on')) {
                    status = 'open';
                    eventLabel = oThis.sliced[2];
                }
                if (oThis.sliced[3] == '' && status == 'close') return;


                oThis.data = oThis.sliced[0].concat('|', oThis.sliced[1], '|', eventLabel, '|', oThis.sliced[4], '|', oThis.sliced[5], '|', oThis.sliced[6]);
                setTrigger(oThis.data);
            });
        }
    }


    gtmModule.searchText = function(data) {
        this.init(data)
    };
    gtmModule.searchText.prototype = {
        init: function(data) {
            var oThis = this;

            this.data = data[0];
            this.sliced = data[0].split('|');
            this.$root = data.root;
            var keywordInput = this.$root.find('.tt-query');
            this.$root.on('submit', function() {
                oThis.data = oThis.sliced[0].concat('|', oThis.sliced[1], '|', oThis.sliced[2], '|', oThis.sliced[3], '|', oThis.sliced[4], '|', $(keywordInput).val());
                setTrigger(oThis.data);

            });

        }
    }


    gtmModule.clickable = function(data) {
        this.init(data)
    };
    gtmModule.clickable.prototype = {
        init: function(data) {
            var oThis = this;

            this.data = data[0];
            this.$root = data.root;
            this.$root.on('click', function() {
                setTrigger(oThis.data);

            });

        }
    }
    
    gtmModule.clickableJS = function (data) {
        this.init(data)
    };

    gtmModule.clickableJS.prototype = {
        init: function (data) {
            var oThis = this;
            this.activeGTM = true;
            this.$root = data.root;
            this.data = $.parseJSON(this.$root.attr('data-gtm-js'))[0];
            this.$root.on('click', function () {
                if (oThis.activeGTM) {
                    var data = $.parseJSON(oThis.$root.attr('data-gtm-js'))[0];
                    setTrigger(data);
                }
            });
        }
    }

    gtmModule.jColors = function(data) {
        this.init(data)
    };
    gtmModule.jColors.prototype = {
        init: function(data) {
            var oThis = this;
            this.data = data[0];
            if (this.data == undefined) {
                return false;
            } else {
                this.sliced = data[0].split('|');
                this.$root = data.root;
                this.$next = this.$root.find('.bx-next');
                this.$prev = this.$root.find('.bx-prev');
                this.$pager = this.$root.find('.bx-pager');
                this.$bxSlider = data.bxSlider;
                this.currentSlide = 0;
                //this.sliced[1] = "slideShow";
                oThis.setHandlers();
            }
        },
        setHandlers: function() {
            var oThis = this;
            this.$next.on('click', function() {
                if(!$(this).hasClass('.disabled')){
                    oThis.gtmNextPrev('Next');
                }
            });

            this.$prev.on('click', function() {
                if(!$(this).hasClass('.disabled')){
                    oThis.gtmNextPrev('Previous');
                }
            });

            this.$pager.on('click', function() {
                if(!$(this).hasClass('.active')){
                    oThis.gtmPager();
                }
            });

        },
        getMainSlide: function() {
            this.currentSlide = this.$bxSlider.getCurrentSlide();
        },
        gtmNextPrev: function(dir) {
            var oThis = this;
           this.getMainSlide();

            //console.log(this.currentSlide);
            oThis.sliced[3] = dir;
            oThis.sliced[5] = this.currentSlide+1;
            oThis.data = oThis.sliced[0].concat('|', oThis.sliced[1], '|', oThis.sliced[2], '|', oThis.sliced[3], '|', oThis.sliced[4], '|', oThis.sliced[5]);
            setTrigger(oThis.data);

        },
        gtmPager: function() {
            var oThis = this;
            this.getMainSlide();
             oThis.sliced[3] = 'pager';
            oThis.sliced[5] = this.currentSlide+1;
            oThis.data = oThis.sliced[0].concat('|', oThis.sliced[1], '|', oThis.sliced[2], '|', oThis.sliced[3], '|', oThis.sliced[4], '|', oThis.sliced[5]);
            setTrigger(oThis.data);
        },

    }

    gtmModule.slider = function(data) {
        this.init(data)
    };
    gtmModule.slider.prototype = {
        init: function(data) {
            var oThis = this;
            this.data = data[0];
            if (this.data == undefined) {
                return false;
            } else {
                this.sliced = data[0].split('|');
                this.$root = data.root;
                this.$next = this.$root.find('.bx-next');
                this.$prev = this.$root.find('.bx-prev');
                this.$pager = this.$root.find('.bx-pager');
                this.$bxSlider = data.bxSlider;

                this.currentSlide = 0;
                //this.sliced[1] = "slideShow";
                oThis.setHandlers();
            }
        },
        setHandlers: function() {
            var oThis = this;
            this.$next.on('click', function() {
                if(!$(this).hasClass('.disabled')){
                    oThis.gtmNextPrev('Next');
                }
            });

            this.$prev.on('click', function() {
                if(!$(this).hasClass('.disabled')){
                    oThis.gtmNextPrev('Previous');
                }
            });

            this.$pager.on('click', function() {
                if(!$(this).hasClass('.active')){
                    oThis.gtmPager();
                }
            });

            this.$root.on('onTouchMove', function() {
                oThis.gtmTouch();
            });
        },
        getMainSlide: function() {
            //this.currentSlide = $(this).$bxSlider.getCurrentSlide();
            if (this.$root.find('.col').eq(this.currentSlide + 1).find('.title').attr('data-text') !== undefined) {
                this.contentText = this.$root.find('.col').eq(this.currentSlide + 1).find('.title').attr('data-text');
            } else {
                this.contentText = "";
            }
        },
        gtmNextPrev: function(dir) {
            var oThis = this;
            this.getMainSlide();
            //console.log(this.currentSlide);
            this.data.dir = dir;
            oThis.sliced[2] = dir;
            oThis.sliced[3] = this.contentText;
            oThis.sliced[5] = this.currentSlide+1;
            oThis.data = oThis.sliced[0].concat('|', oThis.sliced[1], '|', oThis.sliced[2], '|', oThis.sliced[3], '|', oThis.sliced[4], '|', oThis.sliced[5]);
            setTrigger(oThis.data);

        },
        gtmPager: function() {
            var oThis = this;
            this.getMainSlide();
            oThis.sliced[2] = "pager";
            oThis.sliced[3] = this.contentText;
            oThis.sliced[5] = this.currentSlide+1;
            oThis.data = oThis.sliced[0].concat('|', oThis.sliced[1], '|', oThis.sliced[2], '|', oThis.sliced[3], '|', oThis.sliced[4], '|', oThis.sliced[5]);
            setTrigger(oThis.data);
        },
        gtmTouch: function() {
            var oThis = this;
            this.getMainSlide();
            oThis.sliced[2] = "touch";
            oThis.sliced[3] = this.contentText;
            oThis.sliced[5] = this.currentSlide+1;
            oThis.data = oThis.sliced[0].concat('|', oThis.sliced[1], '|', oThis.sliced[2], '|', oThis.sliced[3], '|', oThis.sliced[4], '|', oThis.sliced[5]);
            setTrigger(oThis.data);
        }
    }

	gtmModule.video = function(data) {
        console.log(data);
        this.init(data)
    };
	
	gtmModule.video.prototype = {
        init: function(data) {
            var oThis = this;
            this.data = data[0];
            if (this.data == undefined) {
                return false;
            } else {
                this.sliced = data[0].split('|');
                this.$root = data.root;
                this.idVideo = this.$root.attr('id');
                this.$bigplay = this.$root.parents('.video-js').find('.vjs-big-play-button');
                this.$switchbtn = this.$root.parents('.video-js').find('.vjs-play-control');
              
                //this.sliced[1] = "slideShow";
                oThis.setHandlers();
            }
        },
        setHandlers: function() {
            var oThis = this;
            this.$bigplay.on('click', function() {
                oThis.gtmPlayStop('Play');
            });

            this.$switchbtn.on('click', function() {
                if(!$(this).hasClass('vjs-paused')){
                    oThis.gtmPlayStop('Pause');
                } else {
                    oThis.gtmPlayStop('Play');
                }
            });
        },
        gtmPlayStop: function(dir) {
            var oThis = this;
            this.data.dir = dir;
            oThis.sliced[2] = 'Video::'+dir;
			var videoName = oThis.sliced[3];
			var view = Math.round((oThis.$root.get(0).currentTime*100)/oThis.$root.get(0).duration);
			
            if(dir=="Pause"){
                oThis.sliced[3] = videoName + ':' + view.toString() + '%';
            } else {
                oThis.sliced[3] = videoName;
            }
			oThis.sliced[4] = '';
            oThis.sliced[5] = '';
            oThis.data = oThis.sliced[0].concat('|', oThis.sliced[1], '|', oThis.sliced[2], '|', oThis.sliced[3], '|', oThis.sliced[4], '|', oThis.sliced[5]);
            
            setTrigger(oThis.data);

        }
    }


    gtmModule.vue360 = function(data) {
        console.log(data);
        this.init(data)
    };

    gtmModule.vue360.prototype = {
        init: function(data) {
            var oThis = this;
            this.data = data[0];
            if (this.data == undefined) {
                return false;
            } else {
                this.sliced = data[0].split('|');
                this.$root = data.root;
              
                //this.sliced[1] = "slideShow";
                oThis.setHandlers();
            }
        },
        setHandlers: function() {
            var oThis = this;
            this.$root.on('click', function() {
                oThis.data = oThis.sliced[0].concat('|', oThis.sliced[1], '|', oThis.sliced[2], '|', oThis.sliced[3], '|', oThis.sliced[4], '|', oThis.sliced[5]);
                
                setTrigger(oThis.data);
            });

        }
    }

    /*gtmModule.dragnchange = function(data) {
        this.init(data)
    };
    gtmModule.dragnchange.prototype = {
        init: function(data) {
            var oThis = this;
            oThis.data = data[0];

            if (this.data == undefined) {
                return false;
            } else {
                oThis.sliced = data[0].split('|');

                oThis.$root = data.root;


                oThis.$root.find('.drag').on('mouseup', function() {
                    var leftPercent = parseInt(this.style.left.substr(0, (this.style.left.length-1) ) );

                    if (leftPercent < 50) {
                        oThis.eventName = oThis.sliced[2];
                        oThis.data = oThis.sliced[0].concat('|', oThis.sliced[1], '|', oThis.sliced[2], '|', oThis.sliced[4], '|', oThis.sliced[5], '|', oThis.sliced[6]);
                        setTrigger(oThis.data);

                    } else if ( leftPercent > 50 ) {
                        oThis.eventName = oThis.sliced[3]
                        oThis.data = oThis.sliced[0].concat('|', oThis.sliced[1], '|',oThis.sliced[2], '|', oThis.sliced[3], '|', oThis.sliced[5], '|', oThis.sliced[6]);
                        setTrigger(oThis.data);

                    }
                });
            }
        }
    }*/



    $(document).on('newgtmattr', '[data-gtm], [data-gtm-js]', function(e) {
        var $this = $(this);
    //    console.log("newgtmattr")
    if($this.attr('data-gtm-init')!='1'){
           if ($this.attr('data-gtm') != undefined  ) {
                 try {
                    var data = $this.attr('data-gtm').replace(/\'/g, '"');
                    $this.attr('data-gtm-init','1');
                    var oData ={
                       0: data,
                       'root' : $this
                   }

                        new gtmModule.clickable(oData);

                } catch (err) {}

               // console.log('data-gtm')


            }

            if ($this.attr('data-gtm-js') != undefined) {
              //  console.log('data-gtm-js')
                try {
                    var data = $this.attr('data-gtm-js');

                      oData = $.parseJSON(data);
                    oData.root = $this;



                    if (oData.type !== 'slider' && oData.type !== 'jColors' && gtmModule[oData.type] !== undefined) {


                        $this.attr('data-gtm-init','1');

                        new gtmModule[oData.type](oData);
                    }
                } catch (err) {}
            }
        }
    });

    gtmCit.initNewGTM = function(){
        $('[data-gtm], [data-gtm-js]').each(function() {
            $(this).trigger('newgtmattr');
        });
    };

    gtmCit.initjColors = function(obj) {
        var $root = $(obj.context),
            data = $root.attr('data-gtm-js');

        if (data !== undefined && $root.attr('data-gtm-init')!='1') {
            data = data.replace(/\'/g, '"'),
                oData = $.parseJSON(data);
            oData.root = $root;
            oData.bxSlider = obj;

            $root.attr('data-gtm-init','1');
            new gtmModule[oData.type](oData);
        }
    }


    gtmCit.initSlider = function(obj) {
        var $root = $(obj.context),
            data = $root.attr('data-gtm-js');

        if (data !== undefined && $root.attr('data-gtm-init')!='1') {
            data = data.replace(/\'/g, '"'),
                oData = $.parseJSON(data);
            oData.root = $root;
            oData.bxSlider = obj;

            $root.attr('data-gtm-init','1');
            new gtmModule[oData.type](oData);
        }
    }

    gtmCit.initVue360 = function(obj) {
        
        var $root = $(obj.context),
            data = $root.attr('data-gtm-js');

        if (data !== undefined && $root.attr('data-gtm-init')!='1') {
            data = data.replace(/\'/g, '"');
            console.log(data), 
            oData = $.parseJSON(data);
            console.log(oData);
            oData.root = $root;
           
            $root.attr('data-gtm-init','1');
            new gtmModule[oData.type](oData);
        }
        
    }

    window.addEventListener('load', function() {

        new gtm_listener.viewed(document.querySelector('#gtm-visibility-test'), function() {}, 100);

        new gtm_listener.dragged(document.querySelector('.dragnchange .drag'), function() {}, 100);

    }, false);


})(jQuery, window, document, window.gtmCit = window.gtmCit || {});


    /* iPad dummy events to activate :hover on all elements */
    // $('body').bind(STARTEVENT,function(){  });

    /* Google Tag Manager Functions  */
    var gtm_listener = {

        start: ('ontouchstart' in window) ? 'touchstart' : 'mousedown',
        move: ('ontouchstart' in window) ? 'touchmove' : 'mousemove',
        end: ('ontouchstart' in window) ? 'touchend' : 'mouseup',


        viewed: function(element, callback, degree) {

            if (!element) return;

            if ($) $(element).unbind('click').parents('[data-gtm]').unbind('click');

            /* Vars */
            var that = this,
                check = function(e) {

                    /* Get viewport and element offsets */
                    var st = window.pageYOffset || document.body.scrollTop,
                        vh = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight,
                        et = Math.round(gtm_listener.top(that._el)) - Math.round(vh * (100 - that._degree) / 100);

                    /* Callback if eligible and remove event listener */
                    if (st > et) {
                        that._callback.call(element);
                        window.removeEventListener('scroll', check, false)
                    };

                };

            /* Properties */
            that._el = element;
            that._degree = degree || 0;
            that._callback = callback || function() {};

            /* Events */
            window.addEventListener('scroll', check, false)

        },
        top: function(obj) {
            var top = 0;
            if (obj.offsetParent) {
                while (obj.offsetParent) {
                    top += obj.offsetTop;
                    obj = obj.offsetParent;
                };
            } else if (obj.y) {
                top += obj.y;
            };
            return top;
        },




        dragged: function(element, callback, x, y) {

            if (!element) return;

            if ($) $(element).unbind('click').parents('[data-gtm]').unbind('click');

            /* Vars */
            var that = this,
                set = function(e) {

                    /* Events */
                    document.addEventListener(gtm_listener.move, check, false);
                    document.addEventListener(gtm_listener.end, stop, false);

                },
                check = function(e) {

                    /* Current */
                    var dx = Math.abs(that._x - that._el.offsetLeft),
                        dy = Math.abs(that._y - that._el.offsetTop);

                    if ((dx > that._dx && 0 < that._dx) || (dy > that._dy && 0 < that._dy)) stop(null, true);

                },
                stop = function(e, kill) {

                    document.removeEventListener(gtm_listener.move, check, false);
                    document.removeEventListener(gtm_listener.end, stop, false);

                    if (!kill) return;

                    that._el.removeEventListener(gtm_listener.start, set, false);
                    that._callback.call(element);

                };

            /* Properties */
            that._el = element;
            that._dx = x || 0;
            that._dy = y || 0;
            that._callback = callback || function() {};

            /* Origin */
            that._x = that._el.offsetLeft;
            that._y = that._el.offsetTop;

            /* Events */
            that._el.addEventListener(gtm_listener.start, set, false)

        }

    };

// PAGE READY
(function($) {

    // Car selector init
    $('form[id*=carselector]').each(function() {
        new CarSelector(this)
    });

    //Setting Home Push Btns
    homepushCta(0);

    // add slider thumb ( page showroom )
    addSliderThumb();

    // V2.4 - CPW-3498 // Eligibilité Link My Car
    linkMyCar();

    // skin select page showroom
    selectSkin();

    // dropDown Menu
    dropDownMenu();

    // checkbox skin page showroom
    checkboxSkin();

    addThisScript();

}(jQuery))


//  Dynamisation CTA
var setCtaMobile = function() {
    $('.showroom-mobile .cta').each(function() {
        var dftStyles = $(this).attr('data-off');
        //console.log(dftStyles)
        var hvrStyles = $(this).attr('data-hover');

        $(this).find('a').each(function() {
            $(this).attr('style', dftStyles);
           
            $(this).on('mouseenter', function() {
                $(this).attr('style', hvrStyles);
               
            }).on('mouseleave', function() {
                $(this).attr('style', dftStyles);
            });

        });
    });
}
setCtaMobile();

var setTools = function() {

    $('.showroom-mobile .tools').each(function() {
        var dftStyles = $(this).attr('data-off');
        var hvrStyles = $(this).attr('data-hover');
        if($(this).attr('data-firstoff') && $(this).attr('data-firsthover')){
            var dftFirstStyles = $(this).attr('data-firstoff');
            var hvrFirstStyles = $(this).attr('data-firsthover');
        }

        $(this).find('a').each(function(index) {
            if(dftFirstStyles != undefined && index==0){
                $(this).attr('style', dftFirstStyles);
           
                $(this).on('mouseenter, touchstart', function(e) {
                    e.stopPropagation();
                    $(this).attr('style', hvrFirstStyles);
                   
                }).on('mouseleave, touchend', function(e) {
                    e.stopPropagation();
                    $(this).attr('style', dftFirstStyles);
                });

            } else {
                $(this).attr('style', dftStyles);
           
                $(this).on('mouseenter, touchstart', function(e) {
                    e.stopPropagation();
                    $(this).attr('style', hvrStyles);
                    //console.log('hover / '+hvrStyles)
                   
                }).on('mouseleave, touchend', function(e) {
                    e.stopPropagation();
                    $(this).attr('style', dftStyles);
                });
            }

        });
    });
}
setTools();

var setLinks = function() {
    $('.showroom-mobile .links').each(function() {
        var dftStyles = $(this).attr('data-off');
        var hvrStyles = $(this).attr('data-hover');

        $(this).find('a').each(function(index) {
            $(this).attr('style', dftStyles);
       
            $(this).on('touchstart', function(e) {
                e.stopPropagation();
                $(this).attr('style', hvrStyles);
                //console.log('hover / '+hvrStyles);
               
            }).on('touchend', function(e) {
                e.stopPropagation();
                $(this).attr('style', dftStyles);
            });
        });
    });
}
setLinks();


//  Dynamisation PagerShowroom
var setPagerShowroom = function() {
    
    $('.showroom-mobile.pagenav').each(function() {
        var dftStyles = $(this).attr('data-on');

        $(this).find('li a').each(function() {
            $(this).attr('style', dftStyles);

        });
    });
}
setPagerShowroom();


// GLOBAL REFRESH
var globalRefresh = function() {
    if ($('.cta').length > 0) {
        $('.cta').each(function() {
            new magicCta($(this), 'li');
        })
    }
    if ($('.media').length > 0) {
        $('.media').each(function() {
            new magicCta($(this), 'a', 'span');
        })
    }
    myScroll.refresh();

    // Reload bxslider by MISSIRIA / #4502
    if (mediaSlider) {
        /* VERSION DESTROY
		mediaSlider.destroySlider();
		mediaSlider.reloadSlider();
		*/
        var wH = $(window).height(),
            wW = $(window).width();


        // VERSION UPDATE
        $('.mediaPop .slideThis figure').each(function(index, el) {
            // $(el).attr('style', styles)

            $(el).css({
                position: 'relative',
                overflow: 'hidden',
                height: wH,
                width: wW
            });




            $(el).find('img, iframe, video').each(function() {
                var oThis = this,
                    iH = $(oThis).height(),
                    iW = $(oThis).width();

                //console.log("iH : " + iH + " / iW : " + iW)
                if (wH > wW) {
                    console.log("device : portrait");
                    var hValue = Math.ceil((iH * wW) / iW),
                        wValue = wW,
                        mLvalue = -(wValue / 2),
                        mTvalue = -(hValue / 2);

                        if(hValue>wH){
                            var hValue = wH,
                                wValue = Math.ceil((iW * wH) / iH),
                                mLvalue = -(wValue / 2),
                                mTvalue = -(hValue / 2);
                        }

                } else {
                    //console.log("device : landscape");
                    var hValue = wH,
                        wValue = Math.ceil((iW * wH) / iH),
                        mLvalue = -(wValue / 2),
                        mTvalue = -(hValue / 2);

                        if(wValue>wW){
                            var wValue = wW,
                                hValue = Math.ceil((iH * wW) / iW),
                                mLvalue = -(wValue / 2),
                                mTvalue = -(hValue / 2);
                        }
                }
                $(oThis).css({
                    position: 'absolute',
                    top: '50%',
                    left: '50%',
                    minHeight: hValue,
                    height: hValue,
                    maxHeight: hValue,
                    minWidth: wValue,
                    width: wValue,
                    maxWidth: wValue

                });
                setTimeout(function() {
                    $(oThis).css({
                        marginTop: mTvalue,
                        marginLeft: mLvalue
                    });
                }, 500);


            });

        });
        /*
                $('.mediaPop .slideThis').css({
                    width: mediaSlider.getSlideCount() * $(window).width(),
                    height: $(window).height()
                });
        */
    }

    //if( $( "#slider" ).length ){$('#amount').css({ left : $('#slider .ui-slider-handle').position().left + dragHalf });}
};
$(window).bind('resize orientation', globalRefresh);
// $('.content').click(menu.close);


$(document).on('ready', function(){

    /* AJOUT CPW-4097 */
    //  Dynamisation Comparateur
    var setPointsFortsLight = function(root,index) {
        var root = root;
        
        var $sliders = $(root).find('.slide').not('.bx-clone');
        var gtmArrow = function(currentIndex){
            var $left = $(root).find('a.bx-prev');
            var $right = $(root).find('a.bx-next');
            var step_number = parseInt(currentIndex)+1;
            var left_slide = $sliders.get((currentIndex-1+$sliders.length)%$sliders.length);
            var right_slide = $($sliders.get((currentIndex+1)%$sliders.length));
            $left.attr('data-gtm-js', '{"type":"clickableJS","0":"eventGTM|Showroom::' + page_vehicule_label + '::Strengths::' + step_number + '|Navigation::Arrow::left|' + $(left_slide).attr('data-label') + '||"}');
            $right.attr('data-gtm-js', '{"type":"clickableJS","0":"eventGTM|Showroom::' + page_vehicule_label + '::Strengths::' + step_number + '|Navigation::Arrow::right|' + $(right_slide).attr('data-label') + '||"}');
        };
        var gtmPager = function(){
            $(root).find('.bx-pager-link').each(function(){
                var $link = $(this);
                var index = $link.attr('data-slide-index');
                var step_number = parseInt(index)+1;
                var slide = $sliders.get(index);
                $link.attr('data-gtm','eventGTM|Showroom::'+page_vehicule_label+'::Strengths::'+step_number+'|Navigation::Click::'+step_number+'|'+$(slide).attr('data-label')+'||');
            });
        };
        $(root).find('.slides').bxSlider({
            infiniteLoop:true,
            auto: true,
            slideSelector:'div.slide',
            onSlideAfter: function($slider, oldIndex, newIndex) {
                gtmArrow(newIndex);
            },
            onSliderLoad: function(currentIndex) {
                
            }
        });
        gtmArrow(0);
        gtmPager();
    }
    $('.clspointsfortslight').each(function(index,el){
        new setPointsFortsLight(el,index);
    });
    gtmCit.initNewGTM();
});
;var IframeId; 
$(document).ready(function(){
	//Actualit�
	seeMoreNews();
	filterNews();
	//R�sultats de recherche
	seeMoreResults();
	autoCompleteSearch();
    // Gestion des onglets
    $('.data-onglet').each(function(){
        onglet = $(this).data('onglet');
        valeur = $(this).children().detach();
        valeur.appendTo('.onglet-'+onglet);
        $(this).remove();
    });
	//Finitions
	maskBtnComparateur();
	ajaxToggleFinitions();
	fctConnexion();
	//Iframe
	//loadIframe();
	$('footer .site-version').bind('click', function(e){
		e.preventDefault();
		_version = $(this).data('version');
		if (_version == 'mobile'){
			callAjax({
				url: "Layout_Citroen_Global_Footer/versionMobile"
			});

		} else {
			callAjax({
				url: "Layout_Citroen_Global_Footer/versionDesktop"
			});
		}
	});
    //Formulaires
    initFormulaire();
	focusVehicule();
	focusFinition();
	// Maj dataLayer
	$('form.search-lvl').submit(function(e){
		dataLayer[0].internalSearchKeyword = $(this).find("input[type='search']").val();
	});
	
	// Choix langue dans le footer
	$('footer .languageSelect select').change(function(e){
		var selectedOption = $(this).find('option:selected');
		var urlTo = selectedOption.data('href');
		window.location.href = urlTo;
	});		
	
	if($('#iframeContainer').attr('data-iframe') != ""){
            var loaderIframe = new Loader('.iframeClear');
            //on masque les iframes
            $('#iframeContainer').hide('0', function(){	
                    loaderIframe.show(LoadingKey, false);
            });

            //on affiche l'iframe avec filtre sur class et id
            $('#iframeContainer').load(function() {
                    var hideData = '';
                    if($('#iframeContainer').attr('data-iframe')){
                            var hideData = $('#iframeContainer').attr('data-iframe');			 
                    }
                    $('#iframeContainer').contents().find(hideData).hide("0", function(){
                            $('#iframeContainer').show();
                            loaderIframe.hide();
                    });

            });
	}
});
/**
* ACTUALITES
**/
//M�thode servant au binding le clic sur le bouton permettant d'afficher plus de news
function seeMoreNews(){
	$("#seeMoreNews a").on('click',function(e){
		e.preventDefault();
		displayMoreNews('more');
	});
}
//M�thode affichant les news suppl�mentaires via un appel ajax, on r�cup�re un compteur dans un champs cach� sur la page
function displayMoreNews(typeAff){
	var iMin = parseInt($('#iCount').val());
	var iPid = parseInt($('#pid').val());
    var loader = new Loader($('#seeMoreNews'));
    loader.show();
	callAjax({
		url: "Layout_Citroen_Actualites_Galerie/moreNews",
		async: false,
		data:	{
		   'iMin' : iMin,
		   'typeAff' : typeAff,
		   'iPid' : iPid
		},
        afterAction: function( jqXHR, textStatus){
            lazy.set($('#allActu img.lazy'));
            loader.hide();
        }
	});
}
//M�thode permettant de submitter le formulaire de filtre au changement d'un des filtres
function filterNews(){
	$('select#themeId').on('change',function(){
		var sFormName = $(this).parents("form").attr('id');
        var pid = $('input[name="pid"]').val();
        var iTheme = $(this).val();
        var loader = new Loader($('#allActu'));
        loader.show(LoadingKey,false);
		if(iTheme == '0'){
			/*var urlPage = document.URL;
			var urlNoParams = urlPage.split('?');
			window.location.href = urlNoParams[0];*/
                        callAjax({
                        url: "Layout_Citroen_Actualites_Galerie/filterNews",
                        async: false,
                        data:{
                           'iPid' : pid,
                           'iTheme' : iTheme,
                           'iMin' : 1
                        }
                    });
		}else{
                    //$('#'+sFormName).submit();

                    callAjax({
                        url: "Layout_Citroen_Actualites_Galerie/filterNews",
                        async: false,
                        data:{
                           'iPid' : pid,
                           'iTheme' : iTheme,
                           'iMin' : 1
                        }
                    });
		}
	});
}
/**
* IFRAME
**/

function loadIframe(){
    var loader = new Loader($('.iframeClear'));
    loader.show(LoadingKey,false);
    $('iframe.loadingIframe').load(function() {
        loader.hide();
    });
	 $('iframe#iframeContainer').load(function() {
        //verification de l'iframe
        try {
            document.getElementById('iframeContainer').contentWindow.document.body.innerHTML;
            if($("#iframeContainer").contents().find('body').html() == "PAGE NOT FOUND"){
                $('#iframeContainer').css('display', 'none');
                $('#alterFrame').css('display', 'block');
            }
            adjustMyFrameHeight(document.getElementById("iframeContainer"));
       } catch (ex) {
            $('#iframeContainer').css('display', 'none');
            $('#alterFrame').css('display', 'block');
       }

    });
}
/**
* RESULTATS DE RECHERCHE
**/
//Méthode gérant la fonction d'autocomplétion de la recherche
function autoCompleteSearch(){
	$("input[type=search]").not('.autocomplete-off').typeahead( {
		remote: '/_/Layout_Citroen_ResultatsRecherche/suggest',
		minLength: 3,
		name: 'rechercher'
	});
	$("input[type=search]").not('.autocomplete-off').on("typeahead:selected typeahead:autocompleted", function(e,datum) {
		var sFormName = $(this).parents("form").attr('id');
		$('#'+sFormName).submit();
	});
}
//M�thode servant au binding le clic sur le bouton permettant d'afficher plus de r�sultats de recherche
function seeMoreResults(){
	$("#seeMoreResults a").bind('click',function(e){
		e.preventDefault();
		displayMoreResults('more');
	});
}
//M�thode affichant les r�sultats suppl�mentaires via un appel ajax, on r�cup�re un compteur dans un champs cach� sur la page
function displayMoreResults(typeAff){
	var iStart = parseInt($('#iCount').val());
	var sSearch = $('#searchField').val();
	callAjax({
		url: "Layout_Citroen_ResultatsRecherche/moreResults",
		async: false,
		data:	{
		   'iStart' : iStart,
		   'search' : sSearch,
		   'typeAff' : typeAff
		},
	});
}
//Fonction permettant d'accepter les cookies sur le site, l'Ajax modifie des
//données de session indiquant que l'utilisateur à accepter les cookies et qu'il
//n'est plus nécessaires d'afficher le bandeau d'information
function acceptCookies(redirectUrl){
    $.ajax({
		url: '/_/Layout_Citroen_Global_Header/acceptcookies',
		async: true,
		data:	{
		},
               success: function(data) {

                    if(typeof redirectUrl !== 'undefined'){
                        document.location.href =  redirectUrl;
                    }

                }
	});
}

function ajaxToggleFinitions(){
	$('.btn.dynscript').each(function(index, el){
		$(el).bind('click', function(e){
			var infoRel = $(this).attr('rel');
			var params = infoRel.split('_');
			var finitionLabel = $(this).attr('data-finitionlabel');
            var loader = new Loader($('#car-details'));
            loader.show(LoadingKey,false);
			callAjax({
				url: "Layout_Citroen_Finitions/toggleFinitions",
				async: false,
				data: {
					'lcvd6' : params[0],
					'gamme' : params[1],
					'finition' : params[2],
					'skin' : params[3],
					'form_page_pid':params[4]
				},
				beforeAction: function( jqXHR, textStatus ) {
					scrollTopV = $(window).scrollTop();

					$('.content').css({
						top: - scrollTopV,
						left:0
					});

					$('.container').addClass('popopen');
					$('.content').addClass('popopen');

					$('.container').css({
						height:$(window).height()
					});

                    //$('<div class="loading"><div class="circ"></div></div>').appendTo($('body'));
                    $('.loading').css({
                        width:$(window).width(),
                        height:$(window).height()
                    });

				},
				afterAction: function( jqXHR, textStatus){
					$('.container').removeClass('popopen');
					$('.content').removeClass('popopen');
					$('.content').css({
						top: 0,
						left:0
					});
					$('.container').css({
						height:'auto'
					});
					$(window).scrollTo(scrollTopV, 0);
                    //$('.loading').remove();
                    loader.hide();

					// Marquage GTM
					dataLayer.push({
						vehicleFinition : params[2],
						vehicleFinitionLabel : finitionLabel,
						event : 'click'
					});
				},
				success: function(data){
					var	tpl = $('#itemTpl').html(),
					$placeholder = $('#car-details');
					//$placeholder.html(tpl).find('.accordion').each(accordion.build);
					//$placeholder.find('.car-details'+index).html(tpl).find('.accordion').each(accordion.build);
								
					$placeholder.find('.car-details'+index).html(tpl).find('.accordion').each(function(){
						new Accordion($(this));
					});
					ajaxCaracteristiquesFinitions();
					var $box_lvl2 = $placeholder.find('.box-lvl2');
					$box_lvl2.find('.accordion').show();
					var position = $("#sticky").offset().top;
					$('html, body').animate({scrollTop:position}, 0);
				}
			});
		});
	});
}

function ajaxCaracteristiquesFinitions(){
	$('select[name=equipe]').on('change',function(){
		var valueCarac = $(this).val();
		var params = valueCarac.split('_');
		var motorLabel = $(this).find('option:selected').text();
		displayCaracteristiquesFinitions(params[0], params[1], params[2], params[3]);

		// Marquage GTM
		dataLayer.push({
			vehicleMotor : params[0],
			vehicleMotorLabel : motorLabel,
			event : 'click'
		});
	});
}

function displayCaracteristiquesFinitions(engine_code, finition, lcvd6, gamme){
	$box_lvl2 = $('.box-lvl2');
    var loader = new Loader($('#caracteristiques'));
    loader.show(LoadingKey,false);
	callAjax({
		url: "Layout_Citroen_Finitions/caracteristiquesFinitions",
		async: false,
		data:	{
		   'engine_code' : engine_code,
		   'finition' : finition,
		   'lcvd6' : lcvd6,
		   'gamme' : gamme
		},
		 beforeAction : function( jqXHR, textStatus ) {

                    scrollTopV = $(window).scrollTop();
                    $('.content').css({
                        top: - scrollTopV,
                        left:0
                    });

                    $('.container').addClass('popopen');
                    $('.content').addClass('popopen');

                    $('.container').css({
                        height:$(window).height()
                    });

                    //$('<div class="loading"><div class="circ"></div></div>').appendTo($('body'));
                    $('.loading').css({
                        width:$(window).width(),
                        height:$(window).height()
                    });
                },
                afterAction : function( jqXHR, textStatus)
                {

                $('.container').removeClass('popopen');
                $('.content').removeClass('popopen');
                $('.content').css({
                    top: 0,
                    left:0
                });

                $('.container').css({
                    height:'auto'
                });
                 $(window).scrollTo(scrollTopV, 0);

                //$('.loading').remove();
                loader.hide();

                },
		success: function(){
			$box_lvl2.find('.accordion').show();
			$('.accordion', $box_lvl2).each(function(){
				new Accordion($(this));
			});
		}
	});
}
//Méthode servant au binding le clic sur le bouton permettant d'afficher plus de vehicules neufs
function maskBtnComparateur(){
	$(".compareBtn").each(function(e){
		if($("input[name=trancheComparateur]").length == 0){
			$(this).css('display','none');
		}
	});
}
function fctConnexion() {
	// Connexion reseaux sociaux
	$('.step .share-btns ul li a, .social-connect .btns span, .social-connect .btns-text span').bind('tapone', function(e) {
		e.preventDefault();
		var _url = $(this).data('url');
		window.open(_url,'login');
	});
}

$('a.connection-btn').bind('click, tapone', function(e){
	var _url = "/_/User/openid";
	window.open(_url,'login');
	//$('.citroenid-block-popin iframe').attr('src', _url);
});


/**Formulaires*/

function scrollForm(height) 
{   
        var arrFrames = document.getElementsByTagName("iframe");
        for(i = 0; i<arrFrames.length; i++){
            try{
                if(arrFrames[i].id == IframeId){
                        $('html, body').delay(100).stop().dequeue().animate({
                        scrollTop: $(arrFrames[i]).offset().top+height
                    }, 200);
                 }
              }
              catch(e){
              } 
        }
}


function initFormulaire()
{
    $('div.request-form').each(function(){
        var formActivation = $(this).find('input[name=formActivation]').val();
        var typeFormulaire = $(this).find('input[name=typeFormulaire]').val();
        var typeDevice = $(this).find('input[name=typeDevice]').val();
        var idDiv =  $(this).attr('id');
        var InceCode = $(this).find('input[name=InceCode]').val();
        var lcdvForm = $(this).find('input[name=lcdv6Form]').val();
        isDeployed = $(this).find('input[name=deployed]').val();
		var ippId = $(this).find('input[name=ppid]').val();
		var formTypeLabel = $(this).find('input[name=formTypeLabel]').val();

        var formEquipCode =  $(this).find('input[name=EQUIPEMENT_CODE]').val();
        var formIDType =  $(this).find('input[name=TYPE_ID]').val();
        var formUserCode =  $(this).find('input[name=USER_TYPE_CODE]').val();
        var contextForm = "";
        if(formActivation != 'CHOIX'){
          if(lcdvForm)
          {
            contextForm = "CAR";
        }
        if ($("#isPDV") && $("#isPDV").val() == 'RTO')
        {

            contextForm = "RTO";
            InceCode = getINCEForm(formIDType, formUserCode, formEquipCode, contextForm, InceCode);

        }

        getFormId(InceCode, formActivation,idDiv,lcdvForm, null, formTypeLabel, isDeployed, contextForm,ippId);
    }

    $('.nextStepForm'+idDiv).on('click', function(e){
        e.preventDefault();
        var div = $(this).attr('rel');
        var typeClient = $('div.'+div).find('input[name=typeClient]:checked').val();
        if(typeof(typeClient) != 'undefined'){
          if(lcdvForm)
          {
            contextForm = "CAR";
        }

        if ($("#isPDV") && $("#isPDV").val() == 'RTO')
        {
            contextForm = "RTO";
            InceCode = getINCEForm(formIDType, formUserCode, formEquipCode, contextForm, InceCode);
        }
		$('#'+idDiv+'').hide();
        callFormulaire(typeFormulaire,typeClient,typeDevice,idDiv,lcdvForm, isDeployed, contextForm,ippId);
    }
});
});
}

function getINCEForm(TYPE_ID, USER_TYPE_CODE, EQUIPEMENT_CODE, CONTEXT_CODE, InceCode) {
    $.ajax({
        url: '/_/Layout_Citroen_Formulaire/getINCECode',
        async: false,
        data: {
            'TYPE_ID': TYPE_ID,
            'USER_TYPE_CODE': USER_TYPE_CODE,
            'EQUIPEMENT_CODE': EQUIPEMENT_CODE,
            'CONTEXT_CODE': CONTEXT_CODE
        },
        success: function(data) {
        data = JSON.parse(data);
       InceCode = data['FORM_INCE_CODE'];
        }
    });
    return InceCode;
}

function ResizeIframeFromParent(id)
{
    try {
        if (jQuery('#' + id).length > 0) {
            var window = document.getElementById(id).contentWindow;
            var prevheight = jQuery('#' + id).attr('height');
            var newheight = window.document.getElementById('wf_form_content').clientHeight;

            //console.log("Adjusting iframe height for "+id+": " +prevheight+"px => "+newheight+"px");
            if (newheight != prevheight && newheight > 0) {
                jQuery('#' + id).attr('height', newheight + 10);
            }
        }
    }
    catch (e) {
    }
}
function ResizeIframeFromParent2(id)
{
    var isMSIE = /*@cc_on!@*/0; //test pour déterminé si IE
    if (!isMSIE)
    {
        var $myIframe = $('#' + id);
        var myIframe = $myIframe[0];
        var MutationObserver = window.MutationObserver || window.WebKitMutationObserver || window.MozMutationObserver;

        myIframe.addEventListener('load', function() {
            ResizeIframeFromParent(id);

            var target = myIframe.contentDocument.getElementById('wf_form_content');

            var observer = new MutationObserver(function(mutations) {
                ResizeIframeFromParent(id);
            });

            var config = {
                attributes: true,
                childList: true,
                characterData: true,
                subtree: true
            };
            observer.observe(target, config);
        });
    }
    else
    {
        setInterval(function() {
            ResizeIframeFromParent(id);
        }, 1000);
    }
}





function loadFormsParameters() {
				
	var lcdvForm = $('input[name=lcdv6Form]').val();
	var formTypeGTM = $('input[name=formTypeGTM]').val();
	var isGeocodeActive = $('input#isGeocodeActive').val();
	var country = $('input[name=CODE_PAYS]').val();
          	
	if(country == 'FR' || country == 'BE' || formTypeGTM == 'pre-lead' ){
		var sAutoFill = {'GIT_TRACKING_ID': getGITID(),'TESTDRIVE_CAR_LCDV': lcdvForm};
	}else{
		var sAutoFill = {'GIT_TRACKING_ID': getGITID()};
	}
	
	

	lcdvFormContext = Array();
	if(lcdvForm.length > 0){
		lcdvFormContext = [lcdvForm]; 
	}

	
	
	new citroen.webforms.WebFormsFacade({
		source: '/dcr/prm/getinstancebyid?instanceid='+formParams.instance+'&culture='+formParams.culture,
		returnURL: '',
		dealerLocatorFluxType: 'dealerdirectory2',
		target: 'wf_form_content',
		siteGeo : isGeocodeActive,
		autoFill: sAutoFill,
		carPickerPreselectedVehicles: lcdvFormContext,
		brochurePickerPreselectedVehiclesLcdv: lcdvFormContext,
		brochurePickerPreselectedVehicles: [],
		onPostAjaxSuccess: function(datas) {
			//$('div#wf_form_content').hide();
		   finalStepFunction(datas,formParams.idframe, formParams.instance,formParams.typeLabel);
		},
		onPostAjaxFailure: function() {
			alert("Erreur technique lors de l'enregistrement du formulaire");
		},
		onPostAjaxError: function(datas) {
			console.log(datas,'onPostAjaxError');
			alert("Certaines donness du formulaire sont invalides");
		}					
	});
	
	 form_load_html();
	// Contextualisation des parametres du moteur
	citroen.webforms.parameters.contextualize(formParams);
}	

 function form_load_html() {
         if (typeof $('li.wf_active').html() === 'undefined')
        {
            window.setTimeout(form_load_html, 100);	
        }
        else
        {
			$('li.wf_active a').trigger('click');
			$('div.wf_resume_img img').css("min-width", "0%");
        }
    }

function getFormId(InceCode, formActivation, idIframe, lcdvForm, email, formTypeLabel, isDeployed, contextForm,ippId){

  
	$('div#wf_form_content').remove();
	
    if (typeof (InceCode) == "string" && InceCode != '') {
        if (typeof formTypeLabel == 'undefined' || formTypeLabel == '') {
            formTypeLabel = '';
        }
		var country = $('input[name=CODE_PAYS]').val();
		var lang = $('input[name=LANGUE_CODE]').val();
		var typeDevice =  $('input[name=typeDevice]').val();
		 var iFormPageId = $('input[name=form_page_pid]').val();
		
		if(typeDevice == 'WEB'){
			contextdevice = 'desktop';
			brandconnector = 'pc';
		}else{
			contextdevice = 'mobile';
			brandconnector = 'mobile';
		}
		
		
		formParams = {
				brand:        'ac',               // Marque [ap, ac, ds] en minuscule
				lang:         lang,               // Code ISO de la Langue (en)
				country:      country,               // Code ISO du Pays (GB) 
				culture:      lang+'-'+country,            // Culture (en-GB, nl-BE pour le Neerlandais en Belgique)
				instance:     InceCode, // Numero d'nstance du formulaire (16 caracteres)
				context :     contextdevice,          // desktop ou mobile
				brandidConnector: brandconnector,       // pc ou mobile ou driveds
				otherCss:     [],                 // Liste de CSS additionnels
				GammeSource: 'CPP',                // Source de la Gamme des Vehicules et Brochures (CPP ou GDG)
				environment: '' ,// Environnement (DEV, RECETTE, PREPROD, PROD)
				idframe:        idIframe,  
				typeLabel:       formTypeLabel,  
				contextFormDeploy:   contextForm
			};
			
		  
			  $("<div>", {class: "wf_form_content",id:"wf_form_content"}).insertBefore('#'+idIframe+''); 
		  
            // Chargement du moteur
            $(window).load(loadFormsResources(formParams.context));
		 
        // var iFormPageId = $('input[name=form_page_pid]').val();
        // var styleForm = $('input[name=' + idIframe + 'styleForm]').val();
        // var formClass = $("body").attr('class');
        // var iPageId2 = $('input[name=form_page_pid]').val();
        // var url = "/forms/" + formTypeLabel + "/Layout_Citroen_Formulaire/iframe?version=2&idform=" + InceCode + "&typeform=" + formActivation + "&section=" + idIframe + "&lcdv=" + lcdvForm + "&email=" + email + "&formClass=" + formClass + "&styleForm=" + styleForm + "&isDeployed=" + isDeployed + "&form_page_id=" +iFormPageId+"&contextForm="+contextForm;
      

    }
}

// function getFormId(InceCode, formActivation, idIframe, lcdvForm, email, formTypeLabel, isDeployed, contextForm,ippId)
// {
    // var styleForm = $('input[name=' + idIframe + 'styleForm]').val();
    // var formClass = $("body").attr('class');
    // formClass = formClass.replace("script","");
    // formClass = $.trim(formClass);
	// var ippId = $('input[name=ppid]').val();
    // var url = "/_/Layout_Citroen_Formulaire/iframe?version=2&idform=" + InceCode+ "&typeform=" + formActivation+"&section="+idIframe+"&lcdv="+lcdvForm + "&formClass="+ formClass + "&styleForm=" + styleForm + "&isDeployed=" + isDeployed + "&ppid=" + ippId + "&contextForm="+contextForm;

   // $("#"+idIframe).html('<iframe id="iframe'+idIframe+'" src="'+url+'" style="min-height:100px; width: 100%; margin: 0px; padding: 0px; clear: both; display: block" scrolling="no" frameborder="no"></iframe>');
    // setInterval(function() {ResizeIframeFromParent('iframe'+idIframe);}, 1000);
    // var loader = new Loader($('#'+idIframe));
    // loader.show(LoadingKey,false);
    // IframeId = 'iframe' + idIframe;
    // ResizeIframeFromParent2('iframe' + idIframe);
    // $('#'+idIframe).slideDown( "slow" );
    // $('#iframe' + idIframe).load(function(){
        // loader.hide();
    // });
// }
function callFormulaire(typeFormulaire,typeClient,typeDevice,idSection,lcdvForm, isDeployed, contextForm,ippId)
{

    callAjax({
        url: '/_/Layout_Citroen_Formulaire/getContenu',
        async: false,
        data:	{
            'typeFormulaire' : typeFormulaire,
            'typeClient' : typeClient,
            'typeDevice' : typeDevice,
            'idSection' : idSection,
            'lcdvForm' : lcdvForm,
            'isDeployed' : isDeployed,
            'contextForm' : contextForm,
			'ppid' : ippId
        }
    });
}
function finalStepFunction(dataForm,idSection,idForm)
{
    var idPage = $('input[name='+idSection+'idPage]').val();
    var zoneOrder = $('input[name='+idSection+'zoneOrder]').val();
    var areaId = $('input[name='+idSection+'areaId]').val();
    var zoneTid = $('input[name='+idSection+'zoneTid]').val();
    var isDeployed = $('input[name='+idSection+'deployed]').val();
	var ippId = $('input[name='+idSection+'ppid]').val();
	
    var params = {};
    var arDatas = dataForm.message.split('&');
    var loader = new Loader($('#'+idSection));
    loader.show(LoadingKey,false);
    arDatas.forEach(function (part) {
        var pair = part.split('=');
        pair[0] = decodeURIComponent(pair[0]);
        pair[1] = decodeURIComponent(pair[1]);
        params[pair[0]] = (pair[1] !== 'undefined') ?
            pair[1] : true;
    });
	
    $.ajax({
        url: '/_/Layout_Citroen_Formulaire/finalStep',
        async: false,
        type : 'POST',
        data:	{
            'params' : params,
            'idPage' : idPage,
            'areaId' : areaId,
            'zoneTid' : zoneTid,
            'zoneOrder' : zoneOrder,
            'isDeployed' : isDeployed,
            'idForm' : idForm,
			'ppid':ippId
        },
        success: function(data){
            $('.'+idSection+'Chapo').hide();
            // $('#'+idSection).html(data);
			$('#wf_form_content').html(data);
        }

    });
}

function setVehiculeEdit(idx) {
	$.ajax({
		url: '/_/Layout_Citroen_MonProjet_SelectionVehicules/setVehiculeEdit',
		data: {
			'idx': idx
		}
	}).done(function(retour) {
		item = $('.selection-vehicules article:not(.bx-clone):nth-child('+(idx+1)+')');
		item.html(retour);
		new SelectFeeder(item.find('.selectfeeder'));
	});
}

function setVehiculeActif(idx) {
	callAjax({
		url: 'Layout_Citroen_MonProjet_SelectionVehicules/setVehiculeActif',
		data: {
			'idx': idx
		}
	});
}

function saveVehicule(idx) {
	order = idx-1;
	vehicule = '';
	for (i=1; i<=3; i++) {
		elem = $('article:not(.bx-clone) #mp-v-form_' + idx + ' select.select' + i + ' option:selected').val();
		if (elem) {
			if (vehicule != '') { vehicule += '|'; }
			vehicule += elem;
		}
	}
	callAjax({
		url: 'Layout_Citroen_MonProjet_SelectionVehicules/addToSelectionAjax',
		data: {
			'order': order,
			'vehicule': vehicule
		},
		type: 'post',
		success: function() {
			document.location.reload();
		}
	});
}
function focusFinition() {
	if ($('section.finitions').length >0) {
		idxFinition = null;
		$('section.finitions article:not(.bx-clone)').each(function(index){
			if ($(this).find('span.check-label').hasClass('checked')) {
				idxFinition = index;
			}
		});
		if (idxFinition) {
			$('section.finitions .bx-pager .bx-pager-item a[data-slide-index='+idxFinition+']').trigger('click');
		}
	}
}

function focusVehicule() {
	if ($('section.selection-vehicules').length >0) {
		idxVehicule = null;
		$('section.selection-vehicules article:not(.bx-clone)').each(function(index){
			if ($(this).data('actif') != '') {
				idxVehicule = index;
			}
		});

		if (idxVehicule) {
			$('section.selection-vehicules .bx-pager .bx-pager-item a[data-slide-index='+idxVehicule+']').trigger('click');
		}
	}
}

function hasATactileScreen(id){
	if(id==1){
		 $('.saisie_VIN').show();
		 $('.message_ineligibilite').hide();
		 $('.retour_ajax').hide();
	}
	else{
		$('.saisie_VIN').hide();
		$('.message_ineligibilite').show();
		$('.retour_ajax').hide();
	}
}
function checkEligibilityLinkMyCitroen(VIN,pid,pversion){
		
		
	$.ajax({
        url: '/_/Layout_Citroen_EligibiliteLinkMyCitroen/check',
        async: false,
   		dataType: 'json',
        data: {
            'VIN': VIN,
            'pid': pid,
            'pversion':pversion
        },
        success: function(e) {
        	if(e['invalide_size']){
        		
        		
        		$('#edge-modal').css({"position": "absolute", "top": 45, "right": 55});
				$('#edge-modal').fadeIn(300, function(){$(this).focus();});
				$closeModal	= $('#edge-modal').find(".close");
				
				if ($closeModal.length){
					$closeModal.on("click", function(e){
						var _this = $(this);
							_this.closest("#edge-modal").fadeOut("slow");
					});
				};
        	}
        	else{
        		$('.retour_ajax').show();
        		$('.retour_ajax').html(e['message']);
        	}
        }
       
    } );

	return false;
}

function resize_iframe(iframe) {
	var iframeid = iframe.id;
	//find the height of the internal page
	var the_height= document.getElementById(iframeid).contentWindow.document.body.scrollHeight;
	//change the height of the iframe
	document.getElementById(iframeid).height=the_height;
	$('div.loading').remove();
} 