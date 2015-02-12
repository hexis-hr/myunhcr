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
    
    // Inject the html if it's not present...
    ux.func.callScreenInject();

    // Adjust the padding for the center title
    var totalHeight = ux.viewport.height;
    var headerHeight = 58;
    var dialHeight = $('#callPhone').height();
    var padding = (totalHeight - headerHeight * 2 - dialHeight) / 2;
    $('#callScreen').children('.callScreen_middle').css('paddingTop', padding);

  }


  // Inject call screen html
  ux.func.callScreenInject = function(){
    if(ux.preload.callScreen !== true){
      console.log('PRELOAD: Call screen');
      
      var callScreen = ""+
      "<div class='callScreen_top'>"+
        "<a id='callScreen_close' class='callScreen_back'>"+
          "<i class='callScreen_back_icon icon -arrowLeftWhite14'></i>"+
          "<strong class='callScreen_back_label'>Back</strong>"+
        "</a>"+
      "</div>"+
      "<div class='callScreen_middle'>Select call type</div>"+
      "<a id='callPhone' class='callScreen_dial -left' href='tel:+385916037830' data-ripple='white'>"+
        "<div class='callScreen_dial_inner'>"+
          "<span class='callScreen_dial_title'>Phone</span>"+
          "<span class='callScreen_dial_image'><i class='icon -phone'></i></span>"+
          "<span class='callScreen_dial_info'>+385 91 603 7830</span>"+
        "</div>"+
      "</a>"+
      "<a id='callSkype' class='callScreen_dial -right' href='skype:echo123?call' data-ripple='white'>"+
        "<div class='callScreen_dial_inner'>"+
          "<span class='callScreen_dial_title'>Skype</span>"+
          "<span class='callScreen_dial_image'><i class='icon -skype'></i></span>"+
          "<span class='callScreen_dial_info'>@unhcr_infoline</span>"+
        "</div>"+
      "</a>";

      $('#callScreen').html(callScreen);
      ux.preload.callScreen = true;
    }
  }


  // Activate call state
  function callState(){
    ux.state.isCalling = true;
    setTimeout(function(){
      ux.state.isCalling = false;
    }, 1000);
  }


});