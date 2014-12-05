/* Load.js - Copyright (c) 2010 Chris O'Hara <cohara87@gmail.com>. MIT Licensed */
function asyncLoadScript(a){return function(b,c){var d=document.createElement("script");d.type="text/javascript",d.src=a,d.onload=b,d.onerror=c,d.onreadystatechange=function(){var a=this.readyState;if(a==="loaded"||a==="complete")d.onreadystatechange=null,b()},head.insertBefore(d,head.firstChild)}}(function(a){a=a||{};var b={},c,d;c=function(a,d,e){var f=a.halt=!1;a.error=function(a){throw a},a.next=function(c){c&&(f=!1);if(!a.halt&&d&&d.length){var e=d.shift(),g=e.shift();f=!0;try{b[g].apply(a,[e,e.length,g])}catch(h){a.error(h)}}return a};for(var g in b){if(typeof a[g]=="function")continue;(function(e){a[e]=function(){var g=Array.prototype.slice.call(arguments);if(e==="onError"){if(d)return b.onError.apply(a,[g,g.length]),a;var h={};return b.onError.apply(h,[g,g.length]),c(h,null,"onError")}return g.unshift(e),d?(a.then=a[e],d.push(g),f?a:a.next()):c({},[g],e)}})(g)}return e&&(a.then=a[e]),a.call=function(b,c){c.unshift(b),d.unshift(c),a.next(!0)},a.next()},d=a.addMethod=function(d){var e=Array.prototype.slice.call(arguments),f=e.pop();for(var g=0,h=e.length;g<h;g++)typeof e[g]=="string"&&(b[e[g]]=f);--h||(b["then"+d.substr(0,1).toUpperCase()+d.substr(1)]=f),c(a)},d("chain",function(a){var b=this,c=function(){if(!b.halt){if(!a.length)return b.next(!0);try{null!=a.shift().call(b,c,b.error)&&c()}catch(d){b.error(d)}}};c()}),d("run",function(a,b){var c=this,d=function(){c.halt||--b||c.next(!0)},e=function(a){c.error(a)};for(var f=0,g=b;!c.halt&&f<g;f++)null!=a[f].call(c,d,e)&&d()}),d("defer",function(a){var b=this;setTimeout(function(){b.next(!0)},a.shift())}),d("onError",function(a,b){var c=this;this.error=function(d){c.halt=!0;for(var e=0;e<b;e++)a[e].call(c,d)}})})(this);var head=document.getElementsByTagName("head")[0]||document.documentElement;addMethod("load",function(a,b){for(var c=[],d=0;d<b;d++)(function(b){c.push(asyncLoadScript(a[b]))})(d);this.call("run",c)});

// FastClick
(function(){"use strict";function e(t,r){function s(e,t){return function(){return e.apply(t,arguments)}}var i;r=r||{};this.trackingClick=false;this.trackingClickStart=0;this.targetElement=null;this.touchStartX=0;this.touchStartY=0;this.lastTouchIdentifier=0;this.touchBoundary=r.touchBoundary||10;this.layer=t;this.tapDelay=r.tapDelay||200;this.tapTimeout=r.tapTimeout||700;if(e.notNeeded(t)){return}var o=["onMouse","onClick","onTouchStart","onTouchMove","onTouchEnd","onTouchCancel"];var u=this;for(var a=0,f=o.length;a<f;a++){u[o[a]]=s(u[o[a]],u)}if(n){t.addEventListener("mouseover",this.onMouse,true);t.addEventListener("mousedown",this.onMouse,true);t.addEventListener("mouseup",this.onMouse,true)}t.addEventListener("click",this.onClick,true);t.addEventListener("touchstart",this.onTouchStart,false);t.addEventListener("touchmove",this.onTouchMove,false);t.addEventListener("touchend",this.onTouchEnd,false);t.addEventListener("touchcancel",this.onTouchCancel,false);if(!Event.prototype.stopImmediatePropagation){t.removeEventListener=function(e,n,r){var i=Node.prototype.removeEventListener;if(e==="click"){i.call(t,e,n.hijacked||n,r)}else{i.call(t,e,n,r)}};t.addEventListener=function(e,n,r){var i=Node.prototype.addEventListener;if(e==="click"){i.call(t,e,n.hijacked||(n.hijacked=function(e){if(!e.propagationStopped){n(e)}}),r)}else{i.call(t,e,n,r)}}}if(typeof t.onclick==="function"){i=t.onclick;t.addEventListener("click",function(e){i(e)},false);t.onclick=null}}var t=navigator.userAgent.indexOf("Windows Phone")>=0;var n=navigator.userAgent.indexOf("Android")>0&&!t;var r=/iP(ad|hone|od)/.test(navigator.userAgent)&&!t;var i=r&&/OS 4_\d(_\d)?/.test(navigator.userAgent);var s=r&&/OS ([6-9]|\d{2})_\d/.test(navigator.userAgent);var o=navigator.userAgent.indexOf("BB10")>0;e.prototype.needsClick=function(e){switch(e.nodeName.toLowerCase()){case"button":case"select":case"textarea":if(e.disabled){return true}break;case"input":if(r&&e.type==="file"||e.disabled){return true}break;case"label":case"iframe":case"video":return true}return/\bneedsclick\b/.test(e.className)};e.prototype.needsFocus=function(e){switch(e.nodeName.toLowerCase()){case"textarea":return true;case"select":return!n;case"input":switch(e.type){case"button":case"checkbox":case"file":case"image":case"radio":case"submit":return false}return!e.disabled&&!e.readOnly;default:return/\bneedsfocus\b/.test(e.className)}};e.prototype.sendClick=function(e,t){var n,r;if(document.activeElement&&document.activeElement!==e){document.activeElement.blur()}r=t.changedTouches[0];n=document.createEvent("MouseEvents");n.initMouseEvent(this.determineEventType(e),true,true,window,1,r.screenX,r.screenY,r.clientX,r.clientY,false,false,false,false,0,null);n.forwardedTouchEvent=true;e.dispatchEvent(n)};e.prototype.determineEventType=function(e){if(n&&e.tagName.toLowerCase()==="select"){return"mousedown"}return"click"};e.prototype.focus=function(e){var t;if(r&&e.setSelectionRange&&e.type.indexOf("date")!==0&&e.type!=="time"&&e.type!=="month"){t=e.value.length;e.setSelectionRange(t,t)}else{e.focus()}};e.prototype.updateScrollParent=function(e){var t,n;t=e.fastClickScrollParent;if(!t||!t.contains(e)){n=e;do{if(n.scrollHeight>n.offsetHeight){t=n;e.fastClickScrollParent=n;break}n=n.parentElement}while(n)}if(t){t.fastClickLastScrollTop=t.scrollTop}};e.prototype.getTargetElementFromEventTarget=function(e){if(e.nodeType===Node.TEXT_NODE){return e.parentNode}return e};e.prototype.onTouchStart=function(e){var t,n,s;if(e.targetTouches.length>1){return true}t=this.getTargetElementFromEventTarget(e.target);n=e.targetTouches[0];if(r){s=window.getSelection();if(s.rangeCount&&!s.isCollapsed){return true}if(!i){if(n.identifier&&n.identifier===this.lastTouchIdentifier){e.preventDefault();return false}this.lastTouchIdentifier=n.identifier;this.updateScrollParent(t)}}this.trackingClick=true;this.trackingClickStart=e.timeStamp;this.targetElement=t;this.touchStartX=n.pageX;this.touchStartY=n.pageY;if(e.timeStamp-this.lastClickTime<this.tapDelay){e.preventDefault()}return true};e.prototype.touchHasMoved=function(e){var t=e.changedTouches[0],n=this.touchBoundary;if(Math.abs(t.pageX-this.touchStartX)>n||Math.abs(t.pageY-this.touchStartY)>n){return true}return false};e.prototype.onTouchMove=function(e){if(!this.trackingClick){return true}if(this.targetElement!==this.getTargetElementFromEventTarget(e.target)||this.touchHasMoved(e)){this.trackingClick=false;this.targetElement=null}return true};e.prototype.findControl=function(e){if(e.control!==undefined){return e.control}if(e.htmlFor){return document.getElementById(e.htmlFor)}return e.querySelector("button, input:not([type=hidden]), keygen, meter, output, progress, select, textarea")};e.prototype.onTouchEnd=function(e){var t,o,u,a,f,l=this.targetElement;if(!this.trackingClick){return true}if(e.timeStamp-this.lastClickTime<this.tapDelay){this.cancelNextClick=true;return true}if(e.timeStamp-this.trackingClickStart>this.tapTimeout){return true}this.cancelNextClick=false;this.lastClickTime=e.timeStamp;o=this.trackingClickStart;this.trackingClick=false;this.trackingClickStart=0;if(s){f=e.changedTouches[0];l=document.elementFromPoint(f.pageX-window.pageXOffset,f.pageY-window.pageYOffset)||l;l.fastClickScrollParent=this.targetElement.fastClickScrollParent}u=l.tagName.toLowerCase();if(u==="label"){t=this.findControl(l);if(t){this.focus(l);if(n){return false}l=t}}else if(this.needsFocus(l)){if(e.timeStamp-o>100||r&&window.top!==window&&u==="input"){this.targetElement=null;return false}this.focus(l);this.sendClick(l,e);if(!r||u!=="select"){this.targetElement=null;e.preventDefault()}return false}if(r&&!i){a=l.fastClickScrollParent;if(a&&a.fastClickLastScrollTop!==a.scrollTop){return true}}if(!this.needsClick(l)){e.preventDefault();this.sendClick(l,e)}return false};e.prototype.onTouchCancel=function(){this.trackingClick=false;this.targetElement=null};e.prototype.onMouse=function(e){if(!this.targetElement){return true}if(e.forwardedTouchEvent){return true}if(!e.cancelable){return true}if(!this.needsClick(this.targetElement)||this.cancelNextClick){if(e.stopImmediatePropagation){e.stopImmediatePropagation()}else{e.propagationStopped=true}e.stopPropagation();e.preventDefault();return false}return true};e.prototype.onClick=function(e){var t;if(this.trackingClick){this.targetElement=null;this.trackingClick=false;return true}if(e.target.type==="submit"&&e.detail===0){return true}t=this.onMouse(e);if(!t){this.targetElement=null}return t};e.prototype.destroy=function(){var e=this.layer;if(n){e.removeEventListener("mouseover",this.onMouse,true);e.removeEventListener("mousedown",this.onMouse,true);e.removeEventListener("mouseup",this.onMouse,true)}e.removeEventListener("click",this.onClick,true);e.removeEventListener("touchstart",this.onTouchStart,false);e.removeEventListener("touchmove",this.onTouchMove,false);e.removeEventListener("touchend",this.onTouchEnd,false);e.removeEventListener("touchcancel",this.onTouchCancel,false)};e.notNeeded=function(e){var t;var r;var i;if(typeof window.ontouchstart==="undefined"){return true}r=+(/Chrome\/([0-9]+)/.exec(navigator.userAgent)||[,0])[1];if(r){if(n){t=document.querySelector("meta[name=viewport]");if(t){if(t.content.indexOf("user-scalable=no")!==-1){return true}if(r>31&&document.documentElement.scrollWidth<=window.outerWidth){return true}}}else{return true}}if(o){i=navigator.userAgent.match(/Version\/([0-9]*)\.([0-9]*)/);if(i[1]>=10&&i[2]>=3){t=document.querySelector("meta[name=viewport]");if(t){if(t.content.indexOf("user-scalable=no")!==-1){return true}if(document.documentElement.scrollWidth<=window.outerWidth){return true}}}}if(e.style.msTouchAction==="none"){return true}if(e.style.touchAction==="none"){return true}return false};e.attach=function(t,n){return new e(t,n)};if(typeof define=="function"&&typeof define.amd=="object"&&define.amd){define(function(){return e})}else if(typeof module!=="undefined"&&module.exports){module.exports=e.attach;module.exports.FastClick=e}else{window.FastClick=e}})();



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
    linkTrigger: false,
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
    else {
      window.queue.jQueryWaitlist.push(func);
    }

  },
  jQueryWaitlist: [],
  globalClickEvents: [],
  globalScrollEvents: [],
  globalResizeEvents: []
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
    dlog_verbose(name + ' exec: ' + index);
    value();
  });
}



/*------------------------------------------------------------------------------------
  Helper: Update user info on resize event
------------------------------------------------------------------------------------*/
function updateOnResize(){
  dlog('Resize updates executed...');
  ux.viewport.width = $(window).width();
  ux.viewport.height = $(window).height();
}



/*------------------------------------------------------------------------------------
  Helper: Update user info on scroll even
------------------------------------------------------------------------------------*/
function updateOnScroll(){
  dlog('Scroll updates executed...');
  var treshold = 5;
  ux.scroll.offsetPrevious = ux.scroll.offset;
  ux.scroll.offset = $(window).scrollTop();
  
  // We compare distance traveled with a defined treshold and report scroll direction
  if( ux.scroll.offset - ux.scroll.offsetPrevious > treshold ){
    ux.scroll.direction = 'down';
  } else if( ux.scroll.offsetPrevious - ux.scroll.offset > treshold ){
    ux.scroll.direction = 'up';
  }
  
  ux.viewport.visibleTop = ux.scroll.offset
  ux.viewport.visibleBottom = ux.viewport.height + ux.scroll.offset;
}



/*------------------------------------------------------------------------------------
  Helper: Light scroll (which triggers less often and not in real-time)
------------------------------------------------------------------------------------*/
function lightScroll(callback, delay){
  var timer,
      delay = delay || 80,
      scrollCounter = 0;

  $(window).on('scroll', function(e) {
    
    // Count scroll ticks and force callback execution on a specific count
    scrollCounter++;
    if( scrollCounter > 8 ){
      dlog_verbose('Scroll tick');
      callback();
      scrollCounter = 0;
      timer = false;
      return false;
    }

    // If scroll counters are low, proceed with regular Timeout
    clearTimeout(timer);
    timer = setTimeout(function(){
      scrollCounter = 0;
      dlog_verbose('Scroll tick');
      callback();
    }, delay);

  });
};



/*------------------------------------------------------------------------------------
  Helper: Light resize (which triggers less often and not in real-time)
------------------------------------------------------------------------------------*/
function lightResize(callback, delay){
  var timer,
      delay = delay || 100;
  $(window).on('resize', function(e) {
    clearTimeout(timer);
    timer = setTimeout(function(){
      dlog_verbose('Resize tick'); // Show only for verbose
      callback();
    }, delay);
  });
};



/*------------------------------------------------------------------------------------
  Get page
------------------------------------------------------------------------------------*/
function getPage(url, urlTitle) {
  var title = urlTitle || app.name;
  // console.log('TITLE: ' + title);

  // Log page request
  dlog('GET: ' + url + ' started...');
  var start = new Date().getTime();
    if (typeof method === 'undefined') { method = 'GET'; }
    if (typeof data === 'undefined') { data = []; }

    // Hide the content of the page and show a loader
  $('#page').addClass('-loading');
  
  // Start the request
  $.ajax({
    type: method,
    url: url,
      data: data,
    timeout: 10000, // Wait for 10 seconds max
    success: function(data) {
      
      // Log success and waiting time
      var execTime = new Date().getTime() - start;
      dlog('GET: ' + url + ' success! (' + execTime + 'ms)');

      // Push history state and toggle related switches
      History.pushState(null, title, url);
      ux.url.linkTrigger = false;
      ux.url.isLoading = false;

       // Scroll to top
      $("html, body").scrollTop(0);

      // Swap data and show content
      $('#page').html(data);
      $('#page').removeClass('-loading');

    },
    error: function(xhr, type) {
      
      // Log error and waiting time
      var execTime = new Date().getTime() - start;
      dlog('GET: ' + url + ' error! Reason: ' + type + '. (' + execTime + 'ms)');

      // Show an error message
      $('#pageLoad').html("Sorry, this is taking long. We will try one more time...").addClass('-show');

      // After a small pause, initiate a redirect to the target url
      setTimeout(function(){
        window.location = url;
      }, 500);

    }
  });
}



/*------------------------------------------------------------------------------------
  Execute when jQuery loads...
------------------------------------------------------------------------------------*/
queue.jQuery(function(){
  

  // Get basic user info
  updateOnResize();
  updateOnScroll();


  // Attach FastClick
  FastClick.attach(document.body);


  /*------------------------------------------------------------------------------------
    Load and exec History.js for ajax supported browsers and devices
  ------------------------------------------------------------------------------------*/
  if ( $('html').hasClass('ajax') === true ) {
    load('scripts/external/zepto.history.js').thenRun(function () {
      // Set a click handler for any ajaxNav link
        $(document).on("submit", "form[ajaxForm]", function (event) {
            var data = $(this).serialize();
            console.log(data);
            event.preventDefault();

            if ( ux.url.isLoading === false ){
                ux.url.isLoading = true;
                ux.url.linkTrigger = true;
                var state = History.getState();
                var link = $(this).attr('action');
                getPage(link, $(this).attr("method"), data);
            }
        })

      $(document).on('click','[ajaxNav]', function(event) {
        event.preventDefault();
        if ( ux.url.isLoading === false ){
          ux.url.isLoading = true;
          ux.url.linkTrigger = true;
          var link = $(this).attr('href');
          var title;
          if ( $(this).attr('ajaxNav') !== '' ){ title = $(this).attr('ajaxNav') + ' | ' + app.name }
          else { title = ''; }
          getPage(link, title);
        }
      });

      // Watch for all sorts of state changes (e.g. back button)
      History.Adapter.bind(window,'statechange',function(){
        var state = History.getState();
        dlog('History statechange to: ' + state.hash);
        
        // We check for an active linkTrigger to avoid double ajax calls (ongoing issue 96: https://github.com/browserstate/history.js/issues/96)
        if ( ux.url.linkTrigger === false && ux.url.isLoading === false ) {
          getPage(state.hash);
        }

      });
    });
  }



  /*------------------------------------------------------------------------------------
    Section Tooltip
  ------------------------------------------------------------------------------------*/
  var sectionInfo = {
    target: $('#sectionInfo'),
    toggle: function(){
      dlog_verbose('sectionInfo.toggle()');
      if ( this.target.height() === 0 ){ sectionInfo.open(); }
      else { sectionInfo.close(); }
    },
    open: function(){
      dlog_verbose('sectionInfo.open()');
      this.target.animateAuto().addClass('-show');
    },
    close: function(){
      dlog_verbose('sectionInfo.close()');
      this.target.removeClass('-show').height(0);
    }
  };

  // Open tooltip on click / tap
  $(document).on('click','#sectionInfo_trigger', function() {
    sectionInfo.toggle();
  });

  // Show the tooltip on hover
  $(document).on('mouseenter','#sectionInfo_trigger', function() {
    sectionInfo.open();
  });

  // Hide the tooltip when mouse leaves
  $(document).on('mouseleave','#sectionInfo_trigger', function() {
    sectionInfo.close();
  });

  // Close the tooltip on global click
  queue.globalClickEvents.push(function(event){
    sectionInfo.close();
  });

  // But, stop propagation when clicked within the info element (to prevent it from closing)
  $('#page').on('click','#sectionInfo', function(event) {
    event.stopPropagation();
  });



  /*------------------------------------------------------------------------------------
    Forms
  ------------------------------------------------------------------------------------*/
  $('.customSelect_select').on('change', function(event) {
    $(this).next('.customSelect_overlay').html( $(this).val() );
  });



  /*------------------------------------------------------------------------------------
    Helper: Animate height:auto;
  ------------------------------------------------------------------------------------*/
  (function($){
    $.fn.animateAuto = function(){
      this.each(function(i, el){
        var clone = $(el).clone().css({ 'height':'auto', 'width':$(el).width(), 'position':'absolute', 'left':'-3000px' }).appendTo("body");
        var height = clone.height();
        clone.remove();
        $(el).css({'height':height});
      });
      return this;
    }
  })(window.jQuery || window.Zepto || window.$);
  

});



/*------------------------------------------------------------------------------------
  Execute queues
------------------------------------------------------------------------------------*/
queue.jQuery(function(){
  $('html').on('click', function(event) {
    exec(queue.globalClickEvents, 'GlobalClick');
  });

  lightResize(function(){
    exec(queue.globalResizeEvents, 'GlobalResize');
  }, 300);

  lightScroll(function(){
    exec(queue.globalResizeEvents, 'GlobalScroll');
  }, 300);
});
jQueryExec();