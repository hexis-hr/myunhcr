queue.jQuery(function(){

  var sectionInfo = {
    enabled: true,
    selector: '#sectionInfo',
    toggle: function(){
      if( this.enabled === true ){
        dlog_verbose('sectionInfo.toggle()');
        if ( $(this.selector).height() === 0 ){ sectionInfo.open(); }
        else { sectionInfo.close(); }
      }
    },
    open: function(){
      if( this.enabled === true ){
        dlog_verbose('sectionInfo.open()');
        $(this.selector).animateAuto().addClass('-show');
      }
    },
    close: function(){
      if( this.enabled === true ){
        dlog_verbose('sectionInfo.close()');
        $(this.selector).removeClass('-show').height(0);
      }
    },
    enable: function(){
      dlog_verbose('sectionInfo.enable()');
      this.enabled = true;
    },
    disable: function(){
      dlog_verbose('sectionInfo.disable()');
      $(this.selector).removeClass('-show').height('auto');
      this.enabled = false;
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

  // Detect resize and enable or disable based on media query
  // queue.globalResizeEvents.push(function(event){
  //   if(checkMediaQuery('small') === true){ sectionInfo.enable(); }
  //   else { sectionInfo.disable(); }
  // });
  
  // But, stop propagation when clicked within the info element (to prevent it from closing)
  $('#page').on('click','#sectionInfo', function(event) {
    event.stopPropagation();
  });

});