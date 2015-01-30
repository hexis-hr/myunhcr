/* Load.js - Copyright (c) 2010 Chris O'Hara <cohara87@gmail.com>. MIT Licensed. https://github.com/chriso/load.js */
function asyncLoadScript(a){return function(b,c){var d=document.createElement("script");d.type="text/javascript",d.src=a,d.onload=b,d.onerror=c,d.onreadystatechange=function(){var a=this.readyState;if(a==="loaded"||a==="complete")d.onreadystatechange=null,b()},head.insertBefore(d,head.firstChild)}}(function(a){a=a||{};var b={},c,d;c=function(a,d,e){var f=a.halt=!1;a.error=function(a){throw a},a.next=function(c){c&&(f=!1);if(!a.halt&&d&&d.length){var e=d.shift(),g=e.shift();f=!0;try{b[g].apply(a,[e,e.length,g])}catch(h){a.error(h)}}return a};for(var g in b){if(typeof a[g]=="function")continue;(function(e){a[e]=function(){var g=Array.prototype.slice.call(arguments);if(e==="onError"){if(d)return b.onError.apply(a,[g,g.length]),a;var h={};return b.onError.apply(h,[g,g.length]),c(h,null,"onError")}return g.unshift(e),d?(a.then=a[e],d.push(g),f?a:a.next()):c({},[g],e)}})(g)}return e&&(a.then=a[e]),a.call=function(b,c){c.unshift(b),d.unshift(c),a.next(!0)},a.next()},d=a.addMethod=function(d){var e=Array.prototype.slice.call(arguments),f=e.pop();for(var g=0,h=e.length;g<h;g++)typeof e[g]=="string"&&(b[e[g]]=f);--h||(b["then"+d.substr(0,1).toUpperCase()+d.substr(1)]=f),c(a)},d("chain",function(a){var b=this,c=function(){if(!b.halt){if(!a.length)return b.next(!0);try{null!=a.shift().call(b,c,b.error)&&c()}catch(d){b.error(d)}}};c()}),d("run",function(a,b){var c=this,d=function(){c.halt||--b||c.next(!0)},e=function(a){c.error(a)};for(var f=0,g=b;!c.halt&&f<g;f++)null!=a[f].call(c,d,e)&&d()}),d("defer",function(a){var b=this;setTimeout(function(){b.next(!0)},a.shift())}),d("onError",function(a,b){var c=this;this.error=function(d){c.halt=!0;for(var e=0;e<b;e++)a[e].call(c,d)}})})(this);var head=document.getElementsByTagName("head")[0]||document.documentElement;addMethod("load",function(a,b){for(var c=[],d=0;d<b;d++)(function(b){c.push(asyncLoadScript(a[b]))})(d);this.call("run",c)});


/*------------------------------------------------------------------------------------
  Define global ux variable
------------------------------------------------------------------------------------*/
window.ux = {
  viewport: {},
  scroll: {
    direction: false
  },
  config: {},
  url: {
    isLoading: false
  }
};



/*------------------------------------------------------------------------------------
  Dev log function (use instead of console.log)
------------------------------------------------------------------------------------*/
window.dlog = function(val) { if ( app.dlog === true ) console.log(val); }
window.dlog_verbose = function(val) { if ( app.dlog === true && app.verbose === true ) console.log(val); }



/*------------------------------------------------------------------------------------
  Queueing function
------------------------------------------------------------------------------------*/
window.queue = {
 
  // We use this to store function which will get executed once jQuery loads
  jQuery: function(func){
    // If there is jQuery / Zepto library present, we simply execute the passed function. Nothing different there...
    if(window.jQuery || window.Zepto || window.$) {
      dlog('Forwarding function...');
      func();
    }

    // However, if there is no library present at the moment (loading asynchronously) we store the function/s in an array for later use
    else { window.queue.jQueryWaitlist.push(func); }

  },
  jQueryWaitlist: [],
  pageUnloadEvents: [],
  pageLoadEvents: [],
  globalClickEvents: [],
  globalScrollEvents: [],
  globalResizeEvents: [],
  globalUnloadEvents: []

};



/*
 * This function only needs to be executed once at any point!
 * It's main purpose is to check for jQuery / Zepto library at small timeout intervals
 * If it finds a library present, it then starts to execute all the queued jQuery functions from queue.jQuery
 */
function jQueryExec(){
  if((window.jQuery || window.Zepto || window.$) && window.app.DOMContentLoaded === true) {
    dlog('Queue ready...');
    $.each(queue.jQueryWaitlist, function(index, value) {
      value();
      dlog('Queue exec: ' + index);
    });
    dlog('Queue complete!');
  } else {
    dlog('Queueing...');
    setTimeout(jQueryExec, 50);
  }
}



/*------------------------------------------------------------------------------------
  Helper: Call global events only once
------------------------------------------------------------------------------------*/
var exec = function(funcQueue, name) {
  $.each(funcQueue, function(index, value) {
    dlog_verbose(name + ' fired: ' + index);
    value();
  });
}



/*------------------------------------------------------------------------------------
  Events when swapping pages
------------------------------------------------------------------------------------*/
window.page = {
  onUnload: function(){
    dlog_verbose('page.onUnload()');
    exec(queue.pageUnloadEvents, 'pageUnloadEvents');
  },
  onLoad: function(){
    dlog_verbose('page.onLoad()');
    exec(queue.pageLoadEvents, 'pageLoadEvents');
  }
};