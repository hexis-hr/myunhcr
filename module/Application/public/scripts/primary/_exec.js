queue.jQuery(function(){


  /*------------------------------------------------------------------------------------
    Attach global events
  ------------------------------------------------------------------------------------*/
  $('html').on('click', function(event) {
    exec(queue.globalClickEvents, 'globalClickEvents');
  });

  lightResize(function(){
    exec(queue.globalResizeEvents, 'globalResizeEvents');
  }, 300);

  lightScroll(function(){
    exec(queue.globalScrollEvents, 'globalScrollEvents');
  }, 300);

  $(window).on('beforeunload', function(event) {
    
    // Ignore call triggers since they almost never actually navigate the page
    if ( ux.state.isCalling !== true ) {
      exec(queue.globalUnloadEvents, 'globalUnloadEvents');
    }

  });


  /*------------------------------------------------------------------------------------
    Finally, run everything
  ------------------------------------------------------------------------------------*/
  // Attach FastClick
  FastClick.attach(document.body);

  // Run onLoad events
  page.onLoad();


});
jQueryExec();