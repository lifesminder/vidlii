(function(){/*

    Copyright The Closure Library Authors.
    SPDX-License-Identifier: Apache-2.0
   */
   var f,g="function"==typeof Object.create?Object.create:function(a){function b(){}
   b.prototype=a;return new b},h;
   if("function"==typeof Object.setPrototypeOf)h=Object.setPrototypeOf;else{var k;a:{var aa={K:!0},l={};try{l.__proto__=aa;k=l.K;break a}catch(a){}k=!1}h=k?function(a,b){a.__proto__=b;if(a.__proto__!==b)throw new TypeError(a+" is not extensible");return a}:null}var m=h,n=this||self;
   function p(a){a=a.split(".");for(var b=n,c=0;c<a.length;c++)if(b=b[a[c]],null==b)return null;return b}
   function ba(a,b,c){return a.call.apply(a.bind,arguments)}
   function ca(a,b,c){if(!a)throw Error();if(2<arguments.length){var e=Array.prototype.slice.call(arguments,2);return function(){var d=Array.prototype.slice.call(arguments);Array.prototype.unshift.apply(d,e);return a.apply(b,d)}}return function(){return a.apply(b,arguments)}}
   function q(a,b,c){Function.prototype.bind&&-1!=Function.prototype.bind.toString().indexOf("native code")?q=ba:q=ca;return q.apply(null,arguments)}
   function r(a,b){var c=a.split("."),e=n;c[0]in e||"undefined"==typeof e.execScript||e.execScript("var "+c[0]);for(var d;c.length&&(d=c.shift());)c.length||void 0===b?e[d]&&e[d]!==Object.prototype[d]?e=e[d]:e=e[d]={}:e[d]=b}
   ;function u(){this.j=this.j;this.o=this.o}
   u.prototype.j=!1;u.prototype.dispose=function(){this.j||(this.j=!0,this.v())};
   u.prototype.v=function(){if(this.o)for(;this.o.length;)this.o.shift()()};var v=window.yt&&window.yt.config_||window.ytcfg&&window.ytcfg.data_||{};r("yt.config_",v);function w(a,b){return a in v?v[a]:b}
   ;function x(a){var b=w("EXPERIMENTS_FORCED_FLAGS",{});return void 0!==b[a]?b[a]:w("EXPERIMENT_FLAGS",{})[a]}
   ;var y,z=x("web_emulated_idle_callback_delay");y=void 0===z?300:Number(z||0);var B=1E3/60-3;
   function C(a){a=void 0===a?{}:a;u.call(this);this.a=[];this.a[8]=[];this.a[4]=[];this.a[3]=[];this.a[2]=[];this.a[1]=[];this.a[0]=[];this.f=0;this.J=a.timeout||1;this.c={};this.i=B;this.A=this.b=this.h=0;this.B=this.g=!1;this.l=[];this.C=q(this.N,this);this.I=q(this.P,this);this.F=q(this.L,this);this.G=q(this.M,this);this.H=q(this.O,this);this.m=this.u=!1;var b;if(b=!!window.requestIdleCallback)b=x("disable_scheduler_requestIdleCallback"),b=!("string"===typeof b&&"false"===b?0:b);this.D=b;(this.s=
   !!a.useRaf&&!!window.requestAnimationFrame)&&document.addEventListener("visibilitychange",this.C)}
   C.prototype=g(u.prototype);C.prototype.constructor=C;if(m)m(C,u);else for(var D in u)if("prototype"!=D)if(Object.defineProperties){var E=Object.getOwnPropertyDescriptor(u,D);E&&Object.defineProperty(C,D,E)}else C[D]=u[D];function F(a,b){var c=Date.now();G(b);c=Date.now()-c;a.g||(a.i-=c)}
   function H(a,b,c,e){++a.A;if(10==c)return F(a,b),a.A;var d=a.A;a.c[d]=b;a.g&&!e?a.l.push({id:d,priority:c}):(a.a[c].push(d),a.B||a.g||(0!=a.b&&I(a)!=a.h&&J(a),a.start()));return d}
   function K(a){a.l.length=0;for(var b=4;0<=b;b--)a.a[b].length=0;a.a[8].length=0;a.c={};J(a)}
   function I(a){if(a.a[8].length){if(a.m)return 4;if(!document.hidden&&a.s)return 3}for(var b=4;b>=a.f;b--)if(0<a.a[b].length)return 0<b?!document.hidden&&a.s?3:2:1;return 0}
   function G(a){try{a()}catch(b){(a=p("yt.logging.errors.log"))&&a(b)}}
   function L(a){if(a.a[8].length)return!0;for(var b=3;0<=b;b--)if(a.a[b].length)return!0;return!1}
   f=C.prototype;f.M=function(a){var b=void 0;a&&(b=a.timeRemaining());this.u=!0;M(this,b);this.u=!1};
   f.P=function(){M(this)};
   f.L=function(){N(this)};
   f.O=function(){this.m=!0;var a=I(this);4==a&&a!=this.h&&(J(this),this.start());M(this);this.m=!1};
   f.N=function(){document.hidden||N(this);this.b&&(J(this),this.start())};
   function N(a){J(a);a.g=!0;for(var b=Date.now(),c=a.a[8];c.length;){var e=c.shift(),d=a.c[e];delete a.c[e];d&&G(d)}O(a);a.g=!1;L(a)&&a.start();a.i-=Date.now()-b}
   function O(a){for(var b=0,c=a.l.length;b<c;b++){var e=a.l[b];a.a[e.priority].push(e.id)}a.l.length=0}
   function M(a,b){a.m&&4==a.h&&a.b||J(a);a.g=!0;for(var c=Date.now()+(b||a.i),e=a.a[4];e.length;){var d=e.shift(),t=a.c[d];delete a.c[d];t&&G(t)}e=a.u?0:1;e=a.f>e?a.f:e;if(!(Date.now()>=c)){do{a:{d=a;t=e;for(var A=3;A>=t;A--)for(var S=d.a[A];S.length;){var T=S.shift(),U=d.c[T];delete d.c[T];if(U){d=U;break a}}d=null}d&&G(d)}while(d&&Date.now()<c)}a.g=!1;O(a);a.i=B;L(a)&&a.start()}
   f.start=function(){this.B=!1;if(0==this.b)switch(this.h=I(this),this.h){case 1:var a=this.G;this.b=this.D?window.requestIdleCallback(a,{timeout:3E3}):window.setTimeout(a,y);break;case 2:this.b=window.setTimeout(this.I,this.J);break;case 3:this.b=window.requestAnimationFrame(this.H);break;case 4:this.b=window.setTimeout(this.F,0)}};
   function J(a){if(a.b){switch(a.h){case 1:var b=a.b;a.D?window.cancelIdleCallback(b):window.clearTimeout(b);break;case 2:case 4:window.clearTimeout(a.b);break;case 3:window.cancelAnimationFrame(a.b)}a.b=0}}
   f.v=function(){K(this);J(this);this.s&&document.removeEventListener("visibilitychange",this.C);u.prototype.v.call(this)};var P=p("yt.scheduler.instance.timerIdMap_")||{},Q=0,R=0;function V(){var a=p("ytglobal.schedulerInstanceInstance_");if(!a||a.j)a=new C(w("scheduler",void 0)||{}),r("ytglobal.schedulerInstanceInstance_",a);return a}
   function da(){var a=p("ytglobal.schedulerInstanceInstance_");a&&(a&&"function"==typeof a.dispose&&a.dispose(),r("ytglobal.schedulerInstanceInstance_",null))}
   function ea(){K(V())}
   function fa(a,b,c){if(0==c||void 0===c)return c=void 0===c,-H(V(),a,b,c);var e=window.setTimeout(function(){var d=H(V(),a,b);P[e]=d},c);
   return e}
   function ha(a){var b=V();F(b,a)}
   function ia(a){var b=V();if(0>a)delete b.c[-a];else{var c=P[a];c?(delete b.c[c],delete P[a]):window.clearTimeout(a)}}
   function W(a){var b=p("ytcsi.tick");b&&b(a)}
   function ja(){W("jse");X()}
   function X(){window.clearTimeout(Q);V().start()}
   function ka(){var a=V();J(a);a.B=!0;window.clearTimeout(Q);Q=window.setTimeout(ja,800)}
   function Y(){window.clearTimeout(R);R=window.setTimeout(function(){W("jset");Z(0)},800)}
   function Z(a){Y();var b=V();b.f=a;b.start()}
   function la(a){Y();var b=V();b.f>a&&(b.f=a,b.start())}
   function ma(){window.clearTimeout(R);var a=V();a.f=0;a.start()}
   ;p("yt.scheduler.initialized")||(r("yt.scheduler.instance.dispose",da),r("yt.scheduler.instance.addJob",fa),r("yt.scheduler.instance.addImmediateJob",ha),r("yt.scheduler.instance.cancelJob",ia),r("yt.scheduler.instance.cancelAllJobs",ea),r("yt.scheduler.instance.start",X),r("yt.scheduler.instance.pause",ka),r("yt.scheduler.instance.setPriorityThreshold",Z),r("yt.scheduler.instance.enablePriorityThreshold",la),r("yt.scheduler.instance.clearPriorityThreshold",ma),r("yt.scheduler.initialized",!0));}).call(this);