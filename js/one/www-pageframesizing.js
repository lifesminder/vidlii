(function(){/*

    Copyright The Closure Library Authors.
    SPDX-License-Identifier: Apache-2.0
   */
   var a={a:"content-snap-width-1",b:"content-snap-width-2",f:"content-snap-width-3"};function f(){var c=[],b;for(b in a)c.push(a[b]);return c}
   function h(c){var b=f().concat(["guide-pinned","show-guide"]),d=b.length,g=[];c.replace(/\S+/g,function(e){for(var k=0;k<d;k++)if(e==b[k])return;g.push(e)});
   return g}
   ;function l(c,b,d){var g=document.getElementsByTagName("html")[0],e=h(g.className);c&&1251<=(window.innerWidth||document.documentElement.clientWidth)&&(e.push("guide-pinned"),b&&e.push("show-guide"));d&&(d=(window.innerWidth||document.documentElement.clientWidth)-21-50,1251<=(window.innerWidth||document.documentElement.clientWidth)&&c&&b&&(d-=230),e.push(1262<=d?"content-snap-width-3":1056<=d?"content-snap-width-2":"content-snap-width-1"));g.className=e.join(" ")}
   var m=["yt","www","masthead","sizing","runBeforeBodyIsReady"],n=this||self;m[0]in n||"undefined"==typeof n.execScript||n.execScript("var "+m[0]);for(var p;m.length&&(p=m.shift());)m.length||void 0===l?n[p]&&n[p]!==Object.prototype[p]?n=n[p]:n=n[p]={}:n[p]=l;}).call(this);