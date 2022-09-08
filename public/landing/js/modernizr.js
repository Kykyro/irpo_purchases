/*! modernizr 3.3.1 (Custom Build) | MIT *
 * https://modernizr.com/download/?-applicationcache-audio-backgroundsize-borderimage-borderradius-boxshadow-canvas-canvastext-cssanimations-csscolumns-cssgradients-cssreflections-csstransforms-csstransforms3d-csstransitions-flexbox-fontface-generatedcontent-geolocation-hashchange-history-hsla-indexeddb-inlinesvg-input-inputtypes-localstorage-multiplebgs-opacity-postmessage-rgba-sessionstorage-smil-svg-svgclippaths-textshadow-video-webgl-websockets-websqldatabase-webworkers-addtest-mq-setclasses-shiv !*/
!function(e,t,n){function r(e,t){return typeof e===t}function a(e){var t=x.className,n=b._config.classPrefix||"";if(T&&(t=t.baseVal),b._config.enableJSClass){var r=new RegExp("(^|\\s)"+n+"no-js(\\s|$)");t=t.replace(r,"$1"+n+"js$2")}b._config.enableClasses&&(t+=" "+n+e.join(" "+n),T?x.className.baseVal=t:x.className=t)}function o(e,t){if("object"==typeof e)for(var n in e)w(e,n)&&o(n,e[n]);else{var r=(e=e.toLowerCase()).split("."),i=b[r[0]];if(2==r.length&&(i=i[r[1]]),void 0!==i)return b;t="function"==typeof t?t():t,1==r.length?b[r[0]]=t:(!b[r[0]]||b[r[0]]instanceof Boolean||(b[r[0]]=new Boolean(b[r[0]])),b[r[0]][r[1]]=t),a([(t&&0!=t?"":"no-")+r.join("-")]),b._trigger(e,t)}return b}function i(){return"function"!=typeof t.createElement?t.createElement(arguments[0]):T?t.createElementNS.call(t,"http://www.w3.org/2000/svg",arguments[0]):t.createElement.apply(t,arguments)}function s(e,t){return!!~(""+e).indexOf(t)}function c(e,n,r,a){var o,s,c,l,d="modernizr",u=i("div"),f=function(){var e=t.body;return e||((e=i(T?"svg":"body")).fake=!0),e}();if(parseInt(r,10))for(;r--;)(c=i("div")).id=a?a[r]:d+(r+1),u.appendChild(c);return(o=i("style")).type="text/css",o.id="s"+d,(f.fake?f:u).appendChild(o),f.appendChild(u),o.styleSheet?o.styleSheet.cssText=e:o.appendChild(t.createTextNode(e)),u.id=d,f.fake&&(f.style.background="",f.style.overflow="hidden",l=x.style.overflow,x.style.overflow="hidden",x.appendChild(f)),s=n(u,e),f.fake?(f.parentNode.removeChild(f),x.style.overflow=l,x.offsetHeight):u.parentNode.removeChild(u),!!s}function l(e){return e.replace(/([a-z])-([a-z])/g,(function(e,t,n){return t+n.toUpperCase()})).replace(/^-/,"")}function d(e,t){return function(){return e.apply(t,arguments)}}function u(e){return e.replace(/([A-Z])/g,(function(e,t){return"-"+t.toLowerCase()})).replace(/^ms-/,"-ms-")}function f(t,r){var a=t.length;if("CSS"in e&&"supports"in e.CSS){for(;a--;)if(e.CSS.supports(u(t[a]),r))return!0;return!1}if("CSSSupportsRule"in e){for(var o=[];a--;)o.push("("+u(t[a])+":"+r+")");return c("@supports ("+(o=o.join(" or "))+") { #modernizr { position: absolute; } }",(function(e){return"absolute"==getComputedStyle(e,null).position}))}return n}function p(e,t,a,o){function c(){u&&(delete I.style,delete I.modElem)}if(o=!r(o,"undefined")&&o,!r(a,"undefined")){var d=f(e,a);if(!r(d,"undefined"))return d}for(var u,p,m,h,g,v=["modernizr","tspan","samp"];!I.style&&v.length;)u=!0,I.modElem=i(v.shift()),I.style=I.modElem.style;for(m=e.length,p=0;m>p;p++)if(h=e[p],g=I.style[h],s(h,"-")&&(h=l(h)),I.style[h]!==n){if(o||r(a,"undefined"))return c(),"pfx"!=t||h;try{I.style[h]=a}catch(e){}if(I.style[h]!=g)return c(),"pfx"!=t||h}return c(),!1}function m(e,t,n,a,o){var i=e.charAt(0).toUpperCase()+e.slice(1),s=(e+" "+j.join(i+" ")+i).split(" ");return r(t,"string")||r(t,"undefined")?p(s,t,a,o):function(e,t,n){var a;for(var o in e)if(e[o]in t)return!1===n?e[o]:r(a=t[e[o]],"function")?d(a,n||t):a;return!1}(s=(e+" "+B.join(i+" ")+i).split(" "),t,n)}function h(e,t,r){return m(e,n,n,t,r)}var g=[],v=[],y={_version:"3.3.1",_config:{classPrefix:"",enableClasses:!0,enableJSClass:!0,usePrefixes:!0},_q:[],on:function(e,t){var n=this;setTimeout((function(){t(n[e])}),0)},addTest:function(e,t,n){v.push({name:e,fn:t,options:n})},addAsyncTest:function(e){v.push({name:null,fn:e})}},b=function(){};b.prototype=y,b=new b;var x=t.documentElement,T="svg"===x.nodeName.toLowerCase();T||function(e,t){function n(){var e=h.elements;return"string"==typeof e?e.split(" "):e}function r(e){var t=m[e[f]];return t||(t={},p++,e[f]=p,m[p]=t),t}function a(e,n,a){return n||(n=t),c?n.createElement(e):(a||(a=r(n)),!(o=a.cache[e]?a.cache[e].cloneNode():u.test(e)?(a.cache[e]=a.createElem(e)).cloneNode():a.createElem(e)).canHaveChildren||d.test(e)||o.tagUrn?o:a.frag.appendChild(o));var o}function o(e,t){t.cache||(t.cache={},t.createElem=e.createElement,t.createFrag=e.createDocumentFragment,t.frag=t.createFrag()),e.createElement=function(n){return h.shivMethods?a(n,e,t):t.createElem(n)},e.createDocumentFragment=Function("h,f","return function(){var n=f.cloneNode(),c=n.createElement;h.shivMethods&&("+n().join().replace(/[\w\-:]+/g,(function(e){return t.createElem(e),t.frag.createElement(e),'c("'+e+'")'}))+");return n}")(h,t.frag)}function i(e){e||(e=t);var n=r(e);return!h.shivCSS||s||n.hasCSS||(n.hasCSS=!!function(e,t){var n=e.createElement("p"),r=e.getElementsByTagName("head")[0]||e.documentElement;return n.innerHTML="x<style>"+t+"</style>",r.insertBefore(n.lastChild,r.firstChild)}(e,"article,aside,dialog,figcaption,figure,footer,header,hgroup,main,nav,section{display:block}mark{background:#FF0;color:#000}template{display:none}")),c||o(e,n),e}var s,c,l=e.html5||{},d=/^<|^(?:button|map|select|textarea|object|iframe|option|optgroup)$/i,u=/^(?:a|b|code|div|fieldset|h1|h2|h3|h4|h5|h6|i|label|li|ol|p|q|span|strong|style|table|tbody|td|th|tr|ul)$/i,f="_html5shiv",p=0,m={};!function(){try{var e=t.createElement("a");e.innerHTML="<xyz></xyz>",s="hidden"in e,c=1==e.childNodes.length||function(){t.createElement("a");var e=t.createDocumentFragment();return void 0===e.cloneNode||void 0===e.createDocumentFragment||void 0===e.createElement}()}catch(e){s=!0,c=!0}}();var h={elements:l.elements||"abbr article aside audio bdi canvas data datalist details dialog figcaption figure footer header hgroup main mark meter nav output picture progress section summary template time video",version:"3.7.3",shivCSS:!1!==l.shivCSS,supportsUnknownElements:c,shivMethods:!1!==l.shivMethods,type:"default",shivDocument:i,createElement:a,createDocumentFragment:function(e,a){if(e||(e=t),c)return e.createDocumentFragment();for(var o=(a=a||r(e)).frag.cloneNode(),i=0,s=n(),l=s.length;l>i;i++)o.createElement(s[i]);return o},addElements:function(e,t){var n=h.elements;"string"!=typeof n&&(n=n.join(" ")),"string"!=typeof e&&(e=e.join(" ")),h.elements=n+" "+e,i(t)}};e.html5=h,i(t),"object"==typeof module&&module.exports&&(module.exports=h)}(void 0!==e?e:this,t),b.addTest("applicationcache","applicationCache"in e),b.addTest("geolocation","geolocation"in navigator),b.addTest("history",(function(){var t=navigator.userAgent;return(-1===t.indexOf("Android 2.")&&-1===t.indexOf("Android 4.0")||-1===t.indexOf("Mobile Safari")||-1!==t.indexOf("Chrome")||-1!==t.indexOf("Windows Phone"))&&(e.history&&"pushState"in e.history)})),b.addTest("postmessage","postMessage"in e),b.addTest("svg",!!t.createElementNS&&!!t.createElementNS("http://www.w3.org/2000/svg","svg").createSVGRect);var w,S=!1;try{S="WebSocket"in e&&2===e.WebSocket.CLOSING}catch(e){}b.addTest("websockets",S),b.addTest("localstorage",(function(){var e="modernizr";try{return localStorage.setItem(e,e),localStorage.removeItem(e),!0}catch(e){return!1}})),b.addTest("sessionstorage",(function(){var e="modernizr";try{return sessionStorage.setItem(e,e),sessionStorage.removeItem(e),!0}catch(e){return!1}})),b.addTest("websqldatabase","openDatabase"in e),b.addTest("webworkers","Worker"in e),function(){var e={}.hasOwnProperty;w=r(e,"undefined")||r(e.call,"undefined")?function(e,t){return t in e&&r(e.constructor.prototype[t],"undefined")}:function(t,n){return e.call(t,n)}}(),y._l={},y.on=function(e,t){this._l[e]||(this._l[e]=[]),this._l[e].push(t),b.hasOwnProperty(e)&&setTimeout((function(){b._trigger(e,b[e])}),0)},y._trigger=function(e,t){if(this._l[e]){var n=this._l[e];setTimeout((function(){var e;for(e=0;e<n.length;e++)(0,n[e])(t)}),0),delete this._l[e]}},b._q.push((function(){y.addTest=o})),b.addTest("audio",(function(){var e=i("audio"),t=!1;try{(t=!!e.canPlayType)&&((t=new Boolean(t)).ogg=e.canPlayType('audio/ogg; codecs="vorbis"').replace(/^no$/,""),t.mp3=e.canPlayType('audio/mpeg; codecs="mp3"').replace(/^no$/,""),t.opus=e.canPlayType('audio/ogg; codecs="opus"')||e.canPlayType('audio/webm; codecs="opus"').replace(/^no$/,""),t.wav=e.canPlayType('audio/wav; codecs="1"').replace(/^no$/,""),t.m4a=(e.canPlayType("audio/x-m4a;")||e.canPlayType("audio/aac;")).replace(/^no$/,""))}catch(e){}return t})),b.addTest("canvas",(function(){var e=i("canvas");return!(!e.getContext||!e.getContext("2d"))})),b.addTest("canvastext",(function(){return!1!==b.canvas&&"function"==typeof i("canvas").getContext("2d").fillText})),b.addTest("webgl",(function(){var t=i("canvas"),n="probablySupportsContext"in t?"probablySupportsContext":"supportsContext";return n in t?t[n]("webgl")||t[n]("experimental-webgl"):"WebGLRenderingContext"in e})),b.addTest("video",(function(){var e=i("video"),t=!1;try{(t=!!e.canPlayType)&&((t=new Boolean(t)).ogg=e.canPlayType('video/ogg; codecs="theora"').replace(/^no$/,""),t.h264=e.canPlayType('video/mp4; codecs="avc1.42E01E"').replace(/^no$/,""),t.webm=e.canPlayType('video/webm; codecs="vp8, vorbis"').replace(/^no$/,""),t.vp9=e.canPlayType('video/webm; codecs="vp9"').replace(/^no$/,""),t.hls=e.canPlayType('application/x-mpegURL; codecs="avc1.42E01E"').replace(/^no$/,""))}catch(e){}return t})),b.addTest("multiplebgs",(function(){var e=i("a").style;return e.cssText="background:url(https://),url(https://),red url(https://)",/(url\s*\(.*?){3}/.test(e.background)})),b.addTest("rgba",(function(){var e=i("a").style;return e.cssText="background-color:rgba(150,255,150,.5)",(""+e.backgroundColor).indexOf("rgba")>-1})),b.addTest("inlinesvg",(function(){var e=i("div");return e.innerHTML="<svg/>","http://www.w3.org/2000/svg"==("undefined"!=typeof SVGRect&&e.firstChild&&e.firstChild.namespaceURI)}));var C=function(){var e=!("onblur"in t.documentElement);return function(t,r){var a;return!!t&&(r&&"string"!=typeof r||(r=i(r||"div")),!(a=(t="on"+t)in r)&&e&&(r.setAttribute||(r=i("div")),r.setAttribute(t,""),a="function"==typeof r[t],r[t]!==n&&(r[t]=n),r.removeAttribute(t)),a)}}();y.hasEvent=C,b.addTest("hashchange",(function(){return!1!==C("hashchange",e)&&(t.documentMode===n||t.documentMode>7)}));var E=i("input"),k="autocomplete autofocus list placeholder max min multiple pattern required step".split(" "),P={};b.input=function(t){for(var n=0,r=t.length;r>n;n++)P[t[n]]=!!(t[n]in E);return P.list&&(P.list=!(!i("datalist")||!e.HTMLDataListElement)),P}(k);var _="search tel url email datetime date month week time datetime-local number range color".split(" "),N={};b.inputtypes=function(e){for(var r,a,o,i=e.length,s=0;i>s;s++)E.setAttribute("type",r=e[s]),(o="text"!==E.type&&"style"in E)&&(E.value="1)",E.style.cssText="position:absolute;visibility:hidden;",/^range$/.test(r)&&E.style.WebkitAppearance!==n?(x.appendChild(E),o=(a=t.defaultView).getComputedStyle&&"textfield"!==a.getComputedStyle(E,null).WebkitAppearance&&0!==E.offsetHeight,x.removeChild(E)):/^(search|tel)$/.test(r)||(o=/^(url|email)$/.test(r)?E.checkValidity&&!1===E.checkValidity():"1)"!=E.value)),N[e[s]]=!!o;return N}(_),b.addTest("hsla",(function(){var e=i("a").style;return e.cssText="background-color:hsla(120,40%,100%,.5)",s(e.backgroundColor,"rgba")||s(e.backgroundColor,"hsla")}));var z=y._config.usePrefixes?" -webkit- -moz- -o- -ms- ".split(" "):["",""];y._prefixes=z,b.addTest("cssgradients",(function(){for(var e,t="background-image:",n="",r=0,a=z.length-1;a>r;r++)e=0===r?"to ":"",n+=t+z[r]+"linear-gradient("+e+"left top, #9f9, white);";b._config.usePrefixes&&(n+=t+"-webkit-gradient(linear,left top,right bottom,from(#9f9),to(white));");var o=i("a").style;return o.cssText=n,(""+o.backgroundImage).indexOf("gradient")>-1})),b.addTest("opacity",(function(){var e=i("a").style;return e.cssText=z.join("opacity:.55;"),/^0.55$/.test(e.opacity)}));var R={}.toString;b.addTest("svgclippaths",(function(){return!!t.createElementNS&&/SVGClipPath/.test(R.call(t.createElementNS("http://www.w3.org/2000/svg","clipPath")))})),b.addTest("smil",(function(){return!!t.createElementNS&&/SVGAnimate/.test(R.call(t.createElementNS("http://www.w3.org/2000/svg","animate")))}));var $="CSS"in e&&"supports"in e.CSS,A="supportsCSS"in e;b.addTest("supports",$||A);var L=function(){var t=e.matchMedia||e.msMatchMedia;return t?function(e){var n=t(e);return n&&n.matches||!1}:function(t){var n=!1;return c("@media "+t+" { #modernizr { position: absolute; } }",(function(t){n="absolute"==(e.getComputedStyle?e.getComputedStyle(t,null):t.currentStyle).position})),n}}();y.mq=L;var M=y.testStyles=c;(function(){var e=navigator.userAgent,t=e.match(/applewebkit\/([0-9]+)/gi)&&parseFloat(RegExp.$1),n=e.match(/w(eb)?osbrowser/gi),r=e.match(/windows phone/gi)&&e.match(/iemobile\/([0-9])+/gi)&&parseFloat(RegExp.$1)>=9,a=533>t&&e.match(/android/gi);return n||a||r})()?b.addTest("fontface",!1):M('@font-face {font-family:"font";src:url("https://")}',(function(e,n){var r=t.getElementById("smodernizr"),a=r.sheet||r.styleSheet,o=a?a.cssRules&&a.cssRules[0]?a.cssRules[0].cssText:a.cssText||"":"",i=/src/i.test(o)&&0===o.indexOf(n.split(" ")[0]);b.addTest("fontface",i)})),M('#modernizr{font:0/0 a}#modernizr:after{content:":)";visibility:hidden;font:7px/1 a}',(function(e){b.addTest("generatedcontent",e.offsetHeight>=7)}));var O="Moz O ms Webkit",B=y._config.usePrefixes?O.toLowerCase().split(" "):[];y._domPrefixes=B;var j=y._config.usePrefixes?O.split(" "):[];y._cssomPrefixes=j;var F=function(t){var r,a=z.length,o=e.CSSRule;if(void 0===o)return n;if(!t)return!1;if((r=(t=t.replace(/^@/,"")).replace(/-/g,"_").toUpperCase()+"_RULE")in o)return"@"+t;for(var i=0;a>i;i++){var s=z[i];if(s.toUpperCase()+"_"+r in o)return"@-"+s.toLowerCase()+"-"+t}return!1};y.atRule=F;var D={elem:i("modernizr")};b._q.push((function(){delete D.elem}));var I={style:D.elem.style};b._q.unshift((function(){delete I.style}));var W=y.testProp=function(e,t,r){return p([e],n,t,r)};b.addTest("textshadow",W("textShadow","1px 1px")),y.testAllProps=m;var q,H=y.prefixed=function(e,t,n){return 0===e.indexOf("@")?F(e):(-1!=e.indexOf("-")&&(e=l(e)),t?m(e,t,n):m(e,"pfx"))};try{q=H("indexedDB",e)}catch(e){}b.addTest("indexeddb",!!q),q&&b.addTest("indexeddb.deletedatabase","deleteDatabase"in q),y.testAllProps=h,b.addTest("cssanimations",h("animationName","a",!0)),b.addTest("backgroundsize",h("backgroundSize","100%",!0)),b.addTest("borderimage",h("borderImage","url() 1",!0)),b.addTest("borderradius",h("borderRadius","0px",!0)),b.addTest("boxshadow",h("boxShadow","1px 1px",!0)),function(){b.addTest("csscolumns",(function(){var e=!1,t=h("columnCount");try{(e=!!t)&&(e=new Boolean(e))}catch(e){}return e}));for(var e,t,n=["Width","Span","Fill","Gap","Rule","RuleColor","RuleStyle","RuleWidth","BreakBefore","BreakAfter","BreakInside"],r=0;r<n.length;r++)e=n[r].toLowerCase(),t=h("column"+n[r]),("breakbefore"===e||"breakafter"===e||"breakinside"==e)&&(t=t||h(n[r])),b.addTest("csscolumns."+e,t)}(),b.addTest("flexbox",h("flexBasis","1px",!0)),b.addTest("cssreflections",h("boxReflect","above",!0)),b.addTest("csstransforms",(function(){return-1===navigator.userAgent.indexOf("Android 2.")&&h("transform","scale(1)",!0)})),b.addTest("csstransforms3d",(function(){var e=!!h("perspective","1px",!0),t=b._config.usePrefixes;if(e&&(!t||"webkitPerspective"in x.style)){var n;b.supports?n="@supports (perspective: 1px)":(n="@media (transform-3d)",t&&(n+=",(-webkit-transform-3d)")),M("#modernizr{width:0;height:0}"+(n+="{#modernizr{width:7px;height:18px;margin:0;padding:0;border:0}}"),(function(t){e=7===t.offsetWidth&&18===t.offsetHeight}))}return e})),b.addTest("csstransitions",h("transition","all",!0)),function(){var e,t,n,a,o,i;for(var s in v)if(v.hasOwnProperty(s)){if(e=[],(t=v[s]).name&&(e.push(t.name.toLowerCase()),t.options&&t.options.aliases&&t.options.aliases.length))for(n=0;n<t.options.aliases.length;n++)e.push(t.options.aliases[n].toLowerCase());for(a=r(t.fn,"function")?t.fn():t.fn,o=0;o<e.length;o++)1===(i=e[o].split(".")).length?b[i[0]]=a:(!b[i[0]]||b[i[0]]instanceof Boolean||(b[i[0]]=new Boolean(b[i[0]])),b[i[0]][i[1]]=a),g.push((a?"":"no-")+i.join("-"))}}(),a(g),delete y.addTest,delete y.addAsyncTest;for(var U=0;U<b._q.length;U++)b._q[U]();e.Modernizr=b}(window,document);