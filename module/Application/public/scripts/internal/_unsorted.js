queue.jQuery(function(){


  /*------------------------------------------------------------------------------------
    Accordion (Used on FAQ pages)
  ------------------------------------------------------------------------------------*/
  var accordion = {
    open: function(target, openItems){
      dlog_verbose('faq.open()');
      openItems.removeAttr('accordionOpen').find('[autoHeight]').first().height(0);
      target.attr('accordionOpen', '').find('[autoHeight]').first().animateAuto();
    },
    close: function(target){
      dlog_verbose('faq.close()');
      target.removeAttr('accordionOpen').find('[autoHeight]').first().height(0);
    }
  };

  // Accordion item click
  $(document).on('click, focus','[accordionItem]', function() {
    var openItems = $(this).parent().children('[accordionOpen]');
    accordion.open($(this), openItems);
  });

  // CLose on unfocus
  $(document).on('blur','[accordionItem]', function() {
    accordion.close($(this));
  });



  /*------------------------------------------------------------------------------------
    Finally, run everything
  ------------------------------------------------------------------------------------*/
  // Run onLoad events
  page.onLoad();

  // Attach FastClick
  FastClick.attach(document.body);


  // Attach global events
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
    exec(queue.globalUnloadEvents, 'globalUnloadEvents');
  });


});
jQueryExec();