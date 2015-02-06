'use strict';


/*------------------------------------------------------------------------------------
  Define global ux variable
------------------------------------------------------------------------------------*/
window.ux = {
  viewport: {},
  scroll: {
    direction: false
  },
  scrollOffset: {},
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