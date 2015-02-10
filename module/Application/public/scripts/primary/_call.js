queue.jQuery(function(){


  // Open the call screen
  $(document).on('click', '#callScreen_open', function(){
    
    // Adjust the design initially
    callScreenDesign();

    // Slide in the call screen
    $('#callScreen').addClass('-show');

  });

  
  // Close the call screen
  $(document).on('click', '#callScreen_close', function(){
    $('#callScreen').removeClass('-show');
  });


  // Phone call init...
  $(document).on('click', '#callPhone', function(){
    dlog('CALL: Phone');
    callState();
    event.stopPropagation();
  });


  // Skype call init...
  $(document).on('click', '#callSkype', function(){
    dlog('CALL: Skype');
    callState();
    event.stopPropagation();
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


  // Activate call state
  function callState(){
    ux.state.isCalling = true;
    setTimeout(function(){
      ux.state.isCalling = false;
    }, 1000);
  }


});