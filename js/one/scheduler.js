(function(){var f="function"==typeof Object.create?Object.create:function(a){function b(){}
b.prototype=a;return new b},g;
if("function"==typeof Object.setPrototypeOf)g=Object.setPrototypeOf;else{var h;a:{var k={L:!0},l={};try{l.__proto__=k;h=l.L;break a}catch(a){}h=!1}g=h?function(a,b){a.__proto__=b;if(a.__proto__!==b)throw new TypeError(a+" is not extensible");return a}:null}var m=g,n=this;
function p(a){a=a.split(".");for(var b=n,c=0;c<a.length;c++)if(b=b[a[c]],null==b)return null;return b}
function q(a,b,c){return a.call.apply(a.bind,arguments)}
function t(a,b,c){if(!a)throw Error();if(2<arguments.length){var d=Array.prototype.slice.call(arguments,2);return function(){var c=Array.prototype.slice.call(arguments);Array.prototype.unshift.apply(c,d);return a.apply(b,c)}}return function(){return a.apply(b,arguments)}}
function u(a,b,c){Function.prototype.bind&&-1!=Function.prototype.bind.toString().indexOf("native code")?u=q:u=t;return u.apply(null,arguments)}
var v=Date.now||function(){return+new Date};
function w(a,b){var c=a.split("."),d=n;c[0]in d||!d.execScript||d.execScript("var "+c[0]);for(var e;c.length&&(e=c.shift());)c.length||void 0===b?d[e]&&d[e]!==Object.prototype[e]?d=d[e]:d=d[e]={}:d[e]=b}
;function x(){this.h=this.h;this.m=this.m}
x.prototype.h=!1;x.prototype.dispose=function(){this.h||(this.h=!0,this.o())};
x.prototype.o=function(){if(this.m)for(;this.m.length;)this.m.shift()()};var y=window.yt&&window.yt.config_||window.ytcfg&&window.ytcfg.data_||{};w("yt.config_",y);function A(a,b){return a in y?y[a]:b}
function B(){return A("SCHEDULER_SOFT_STATE_TIMER",800)}
;var C=1E3/60-3;
function D(a){a=void 0===a?{}:a;x.call(this);this.a=[];this.a[4]=[];this.a[3]=[];this.a[2]=[];this.a[1]=[];this.a[0]=[];this.f=0;this.G=a.timeout||1;this.c={};this.l=C;this.s=this.b=this.j=0;this.u=this.i=!1;this.g=[];this.A=u(this.I,this);this.F=u(this.K,this);this.C=u(this.H,this);this.D=u(this.J,this);this.v=!1;var b;if(b=!!window.requestIdleCallback)b=!A("EXPERIMENT_FLAGS",{}).disable_scheduler_requestIdleCallback;this.B=b;(this.w=!!a.useRaf&&!!window.requestAnimationFrame)&&document.addEventListener("visibilitychange",this.A)}
D.prototype=f(x.prototype);D.prototype.constructor=D;if(m)m(D,x);else for(var E in x)if("prototype"!=E)if(Object.defineProperties){var F=Object.getOwnPropertyDescriptor(x,E);F&&Object.defineProperty(D,E,F)}else D[E]=x[E];D.a=x.prototype;function G(a,b){var c=v();H(b);c=v()-c;a.i||(a.l-=c)}
function I(a,b,c,d){++a.s;if(10==c)return G(a,b),a.s;var e=a.s;a.c[e]=b;a.i&&!d?a.g.push({id:e,priority:c}):(a.a[c].push(e),a.u||a.i||(0!=a.b&&J(a)!=a.j&&K(a),a.start()));return e}
function L(a){a.g.length=0;for(var b=4;0<=b;b--)a.a[b].length=0;a.c={};K(a)}
function J(a){for(var b=4;b>=a.f;b--)if(0<a.a[b].length)return 0<b?!document.hidden&&a.w?3:2:1;return 0}
function H(a){try{a()}catch(b){(a=p("yt.logging.errors.log"))&&a(b)}}
D.prototype.H=function(a){var b=void 0;a&&(b=a.timeRemaining());this.v=!0;M(this,b);this.v=!1};
D.prototype.K=function(){M(this)};
D.prototype.J=function(){M(this)};
D.prototype.I=function(){this.b&&(K(this),this.start())};
function M(a,b){K(a);a.i=!0;for(var c=v()+(b||a.l),d=a.a[4];d.length;){var e=d.shift(),r=a.c[e];delete a.c[e];r&&H(r)}d=a.v?0:1;d=a.f>d?a.f:d;if(!(v()>=c)){do{a:{e=a;r=d;for(var z=3;z>=r;z--)for(var N=e.a[z];N.length;){var O=N.shift(),P=e.c[O];delete e.c[O];if(P){e=P;break a}}e=null}e&&H(e)}while(e&&v()<c)}a.i=!1;c=0;for(d=a.g.length;c<d;c++)e=a.g[c],a.a[e.priority].push(e.id);a.l=C;a:{for(c=3;0<=c;c--)if(a.a[c].length){c=!0;break a}c=!1}(c||a.g.length)&&a.start();a.g.length=0}
D.prototype.start=function(){this.u=!1;if(0==this.b)switch(this.j=J(this),this.j){case 1:var a=this.C;this.b=this.B?window.requestIdleCallback(a,{timeout:3E3}):window.setTimeout(a,300);break;case 2:this.b=window.setTimeout(this.F,this.G);break;case 3:this.b=window.requestAnimationFrame(this.D)}};
function K(a){if(a.b){switch(a.j){case 1:var b=a.b;a.B?window.cancelIdleCallback(b):window.clearTimeout(b);break;case 2:window.clearTimeout(a.b);break;case 3:window.cancelAnimationFrame(a.b)}a.b=0}}
D.prototype.o=function(){L(this);K(this);this.w&&document.removeEventListener("visibilitychange",this.A);x.prototype.o.call(this)};var Q=p("yt.scheduler.instance.timerIdMap_")||{},R=0,S=0;function T(){var a=p("ytglobal.schedulerInstanceInstance_");if(!a||a.h)a=new D(A("scheduler",void 0)||{}),w("ytglobal.schedulerInstanceInstance_",a);return a}
function U(){var a=p("ytglobal.schedulerInstanceInstance_");a&&(a&&"function"==typeof a.dispose&&a.dispose(),w("ytglobal.schedulerInstanceInstance_",null))}
function V(){L(T())}
function aa(a,b,c){if(0==c||void 0===c)return c=void 0===c,-I(T(),a,b,c);var d=window.setTimeout(function(){var c=I(T(),a,b);Q[d]=c},c);
return d}
function ba(a){G(T(),a)}
function ca(a){var b=T();if(0>a)delete b.c[-a];else{var c=Q[a];c?(delete b.c[c],delete Q[a]):window.clearTimeout(a)}}
function W(a){var b=p("ytcsi.tick");b&&b(a)}
function da(){W("jse");X()}
function X(){window.clearTimeout(R);T().start()}
function ea(){var a=T();K(a);a.u=!0;window.clearTimeout(R);R=window.setTimeout(da,B())}
function Y(){window.clearTimeout(S);S=window.setTimeout(function(){W("jset");Z(0)},B())}
function Z(a){Y();var b=T();b.f=a;b.start()}
function fa(a){Y();var b=T();b.f>a&&(b.f=a,b.start())}
function ha(){window.clearTimeout(S);var a=T();a.f=0;a.start()}
;p("yt.scheduler.initialized")||(w("yt.scheduler.instance.dispose",U),w("yt.scheduler.instance.addJob",aa),w("yt.scheduler.instance.addImmediateJob",ba),w("yt.scheduler.instance.cancelJob",ca),w("yt.scheduler.instance.cancelAllJobs",V),w("yt.scheduler.instance.start",X),w("yt.scheduler.instance.pause",ea),w("yt.scheduler.instance.setPriorityThreshold",Z),w("yt.scheduler.instance.enablePriorityThreshold",fa),w("yt.scheduler.instance.clearPriorityThreshold",ha),w("yt.scheduler.initialized",!0));}).call(this);
