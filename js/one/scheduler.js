var _____WB$wombat$assign$function_____ = function(name) {return (self._wb_wombat && self._wb_wombat.local_init && self._wb_wombat.local_init(name)) || self[name]; };
if (!self.__WB_pmw) { self.__WB_pmw = function(obj) { this.__WB_source = obj; return this; } }
{
  let window = _____WB$wombat$assign$function_____("window");
  let self = _____WB$wombat$assign$function_____("self");
  let document = _____WB$wombat$assign$function_____("document");
  let location = _____WB$wombat$assign$function_____("location");
  let top = _____WB$wombat$assign$function_____("top");
  let parent = _____WB$wombat$assign$function_____("parent");
  let frames = _____WB$wombat$assign$function_____("frames");
  let opener = _____WB$wombat$assign$function_____("opener");

(function(){/*

 Copyright The Closure Library Authors.
 SPDX-License-Identifier: Apache-2.0
*/
var f,aa="function"==typeof Object.create?Object.create:function(a){function b(){}
b.prototype=a;return new b},g;
if("function"==typeof Object.setPrototypeOf)g=Object.setPrototypeOf;else{var h;a:{var ba={K:!0},k={};try{k.__proto__=ba;h=k.K;break a}catch(a){}h=!1}g=h?function(a,b){a.__proto__=b;if(a.__proto__!==b)throw new TypeError(a+" is not extensible");return a}:null}var l=g,m=this||self;
function n(a){a=a.split(".");for(var b=m,c=0;c<a.length;c++)if(b=b[a[c]],null==b)return null;return b}
function ca(a,b,c){return a.call.apply(a.bind,arguments)}
function da(a,b,c){if(!a)throw Error();if(2<arguments.length){var e=Array.prototype.slice.call(arguments,2);return function(){var d=Array.prototype.slice.call(arguments);Array.prototype.unshift.apply(d,e);return a.apply(b,d)}}return function(){return a.apply(b,arguments)}}
function p(a,b,c){Function.prototype.bind&&-1!=Function.prototype.bind.toString().indexOf("native code")?p=ca:p=da;return p.apply(null,arguments)}
var q=Date.now;function r(a,b){var c=a.split("."),e=m;c[0]in e||"undefined"==typeof e.execScript||e.execScript("var "+c[0]);for(var d;c.length&&(d=c.shift());)c.length||void 0===b?e[d]&&e[d]!==Object.prototype[d]?e=e[d]:e=e[d]={}:e[d]=b}
;function t(){this.j=this.j;this.o=this.o}
t.prototype.j=!1;t.prototype.dispose=function(){this.j||(this.j=!0,this.v())};
t.prototype.v=function(){if(this.o)for(;this.o.length;)this.o.shift()()};var v=window.yt&&window.yt.config_||window.ytcfg&&window.ytcfg.data_||{};r("yt.config_",v);function w(a,b){return a in v?v[a]:b}
;function x(a){var b=w("EXPERIMENTS_FORCED_FLAGS",{});return void 0!==b[a]?b[a]:w("EXPERIMENT_FLAGS",{})[a]}
;var y,z=x("web_emulated_idle_callback_delay");y=void 0===z?300:Number(z||0);var A=1E3/60-3;
function C(a){a=void 0===a?{}:a;t.call(this);this.a=[];this.a[8]=[];this.a[4]=[];this.a[3]=[];this.a[2]=[];this.a[1]=[];this.a[0]=[];this.f=0;this.J=a.timeout||1;this.c={};this.i=A;this.A=this.b=this.h=0;this.B=this.g=!1;this.l=[];this.C=p(this.N,this);this.I=p(this.P,this);this.F=p(this.L,this);this.G=p(this.M,this);this.H=p(this.O,this);this.m=this.u=!1;var b;if(b=!!window.requestIdleCallback)b=x("disable_scheduler_requestIdleCallback"),b=!("string"===typeof b&&"false"===b?0:b);this.D=b;(this.s=
!!a.useRaf&&!!window.requestAnimationFrame)&&document.addEventListener("visibilitychange",this.C)}
C.prototype=aa(t.prototype);C.prototype.constructor=C;if(l)l(C,t);else for(var D in t)if("prototype"!=D)if(Object.defineProperties){var E=Object.getOwnPropertyDescriptor(t,D);E&&Object.defineProperty(C,D,E)}else C[D]=t[D];function F(a,b){var c=q();G(b);c=q()-c;a.g||(a.i-=c)}
function H(a,b,c,e){++a.A;if(10==c)return F(a,b),a.A;var d=a.A;a.c[d]=b;a.g&&!e?a.l.push({id:d,priority:c}):(a.a[c].push(d),a.B||a.g||(0!=a.b&&I(a)!=a.h&&J(a),a.start()));return d}
function K(a){a.l.length=0;for(var b=4;0<=b;b--)a.a[b].length=0;a.a[8].length=0;a.c={};J(a)}
function I(a){if(a.a[8].length){if(a.m)return 4;if(!document.hidden&&a.s)return 3}for(var b=4;b>=a.f;b--)if(0<a.a[b].length)return 0<b?!document.hidden&&a.s?3:2:1;return 0}
function G(a){try{a()}catch(b){(a=n("yt.logging.errors.log"))&&a(b)}}
function L(a){if(a.a[8].length)return!0;for(var b=3;0<=b;b--)if(a.a[b].length)return!0;return!1}
f=C.prototype;f.M=function(a){var b=void 0;a&&(b=a.timeRemaining());this.u=!0;M(this,b);this.u=!1};
f.P=function(){M(this)};
f.L=function(){N(this)};
f.O=function(){this.m=!0;var a=I(this);4==a&&a!=this.h&&(J(this),this.start());M(this);this.m=!1};
f.N=function(){document.hidden||N(this);this.b&&(J(this),this.start())};
function N(a){J(a);a.g=!0;for(var b=q(),c=a.a[8];c.length;){var e=c.shift(),d=a.c[e];delete a.c[e];d&&G(d)}O(a);a.g=!1;L(a)&&a.start();b=q()-b;a.i-=b}
function O(a){for(var b=0,c=a.l.length;b<c;b++){var e=a.l[b];a.a[e.priority].push(e.id)}a.l.length=0}
function M(a,b){a.m&&4==a.h&&a.b||J(a);a.g=!0;for(var c=q()+(b||a.i),e=a.a[4];e.length;){var d=e.shift(),u=a.c[d];delete a.c[d];u&&G(u)}e=a.u?0:1;e=a.f>e?a.f:e;if(!(q()>=c)){do{a:{d=a;u=e;for(var B=3;B>=u;B--)for(var T=d.a[B];T.length;){var U=T.shift(),V=d.c[U];delete d.c[U];if(V){d=V;break a}}d=null}d&&G(d)}while(d&&q()<c)}a.g=!1;O(a);a.i=A;L(a)&&a.start()}
f.start=function(){this.B=!1;if(0==this.b)switch(this.h=I(this),this.h){case 1:var a=this.G;this.b=this.D?window.requestIdleCallback(a,{timeout:3E3}):window.setTimeout(a,y);break;case 2:this.b=window.setTimeout(this.I,this.J);break;case 3:this.b=window.requestAnimationFrame(this.H);break;case 4:this.b=window.setTimeout(this.F,0)}};
function J(a){if(a.b){switch(a.h){case 1:var b=a.b;a.D?window.cancelIdleCallback(b):window.clearTimeout(b);break;case 2:case 4:window.clearTimeout(a.b);break;case 3:window.cancelAnimationFrame(a.b)}a.b=0}}
f.v=function(){K(this);J(this);this.s&&document.removeEventListener("visibilitychange",this.C);t.prototype.v.call(this)};var P=n("yt.scheduler.instance.timerIdMap_")||{},Q=0,R=0;function S(){var a=n("ytglobal.schedulerInstanceInstance_");if(!a||a.j)a=new C(w("scheduler",void 0)||{}),r("ytglobal.schedulerInstanceInstance_",a);return a}
function ea(){var a=n("ytglobal.schedulerInstanceInstance_");a&&(a&&"function"==typeof a.dispose&&a.dispose(),r("ytglobal.schedulerInstanceInstance_",null))}
function fa(){K(S())}
function ha(a,b,c){if(0==c||void 0===c)return c=void 0===c,-H(S(),a,b,c);var e=window.setTimeout(function(){var d=H(S(),a,b);P[e]=d},c);
return e}
function ia(a){var b=S();F(b,a)}
function ja(a){var b=S();if(0>a)delete b.c[-a];else{var c=P[a];c?(delete b.c[c],delete P[a]):window.clearTimeout(a)}}
function W(a){var b=n("ytcsi.tick");b&&b(a)}
function ka(){W("jse");X()}
function X(){window.clearTimeout(Q);S().start()}
function la(){var a=S();J(a);a.B=!0;window.clearTimeout(Q);Q=window.setTimeout(ka,800)}
function Y(){window.clearTimeout(R);R=window.setTimeout(function(){W("jset");Z(0)},800)}
function Z(a){Y();var b=S();b.f=a;b.start()}
function ma(a){Y();var b=S();b.f>a&&(b.f=a,b.start())}
function na(){window.clearTimeout(R);var a=S();a.f=0;a.start()}
;n("yt.scheduler.initialized")||(r("yt.scheduler.instance.dispose",ea),r("yt.scheduler.instance.addJob",ha),r("yt.scheduler.instance.addImmediateJob",ia),r("yt.scheduler.instance.cancelJob",ja),r("yt.scheduler.instance.cancelAllJobs",fa),r("yt.scheduler.instance.start",X),r("yt.scheduler.instance.pause",la),r("yt.scheduler.instance.setPriorityThreshold",Z),r("yt.scheduler.instance.enablePriorityThreshold",ma),r("yt.scheduler.instance.clearPriorityThreshold",na),r("yt.scheduler.initialized",!0));}).call(this);


}
/*
     FILE ARCHIVED ON 00:40:21 Jul 29, 2020 AND RETRIEVED FROM THE
     INTERNET ARCHIVE ON 13:07:41 Feb 06, 2025.
     JAVASCRIPT APPENDED BY WAYBACK MACHINE, COPYRIGHT INTERNET ARCHIVE.

     ALL OTHER CONTENT MAY ALSO BE PROTECTED BY COPYRIGHT (17 U.S.C.
     SECTION 108(a)(3)).
*/
/*
playback timings (ms):
  captures_list: 0.539
  exclusion.robots: 0.023
  exclusion.robots.policy: 0.014
  esindex: 0.01
  cdx.remote: 9.216
  LoadShardBlock: 529.575 (6)
  PetaboxLoader3.datanode: 397.889 (8)
  load_resource: 214.862 (2)
  PetaboxLoader3.resolve: 151.669 (2)
*/