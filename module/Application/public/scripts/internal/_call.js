queue.jQuery(function(){


  // Open the call screen
  $('#callScreen_open').on('click', function() {
    
    // Adjust the design initially
    callScreenDesign();

    // Slide in the call screen
    $('#callScreen').addClass('-show');

  });

  
  // Close the call screen
  $('#callScreen_close').on('click', function() {
    $('#callScreen').removeClass('-show');
  });


  // Adjust the design on resize
  queue.globalResizeEvents.push(function(event){
    callScreenDesign();
  });


  // Adjust design function
  function callScreenDesign() {
    // Adjust the padding for the center title
    var totalHeight = ux.viewport.height;
    var headerHeight = 58;
    var dialHeight = $('#callPhone').height();
    var padding = (totalHeight - headerHeight * 2 - dialHeight) / 2;
    $('#callScreen').children('.callScreen_middle').css('paddingTop', padding);
  }


});