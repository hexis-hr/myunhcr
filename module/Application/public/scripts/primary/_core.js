'use strict';


/*------------------------------------------------------------------------------------
  Define global ux variable
------------------------------------------------------------------------------------*/
window.ux = {
  func: {},
  state: {
    isLoading: false,
    isCalling: false,
    pageNotFound: false
  },
  viewport: {},
  scroll: {
    direction: false
  },
  page: {},
  config: {},
  preload: {}
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
    if(window.jQuery || window.Zepto || window.$) { func(); }

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
    dlog('Zepto loaded...');
    $.each(queue.jQueryWaitlist, function(index, value) {
      value();
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
  dlog(name + '();');
  $.each(funcQueue, function(index, value) {
    value();
  });
}



/*------------------------------------------------------------------------------------
  Events when swapping pages
------------------------------------------------------------------------------------*/
window.page = {
  onUnload: function(){
    exec(queue.pageUnloadEvents, 'pageUnloadEvents');
  },
  onLoad: function(){
    
    // Populate some basic page data
    ux.page.current = $('#relay').attr('bodyClass');

    // Open a new object for this page, if one does not exist
    ux.page[$('#relay').attr('bodyClass')] = ux.page[$('#relay').attr('bodyClass')] || {};

    exec(queue.pageLoadEvents, 'pageLoadEvents');

  }
};



/*------------------------------------------------------------------------------------
  Call secondary.js
------------------------------------------------------------------------------------*/
dlog('GET: Secondary.js...');
load('scripts/secondary.js').thenRun(function(){
  dlog('GET: Secondary.js OK!');
});