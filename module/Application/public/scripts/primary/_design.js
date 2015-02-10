queue.jQuery(function(){


  /*------------------------------------------------------------------------------------
    Add state classes for css
  ------------------------------------------------------------------------------------*/
  $(window).on('load', function(){
    $('html').addClass('pageLoaded');
  });

  
  /*------------------------------------------------------------------------------------
    Preload resources after everything else loads
  ------------------------------------------------------------------------------------*/
  $(window).on('load', function(){
    setTimeout(function(){

      // Spinner gif for page transitions
      if(ux.preload.spinner !== true){
        console.log('PRELOAD: Spinner animation...');
        $('#pageLoad').addClass('-preload');
        setTimeout(function(){ $('#pageLoad').removeClass('-preload'); }, 50);
        ux.preload.spinner = true;
      }

      // Load these icons only if there are no other page loads in progress
      if( ux.state.isLoading === false ){

        
        /*------------------------------------------------------------------------------------
          Inject call screen
        ------------------------------------------------------------------------------------*/
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

    }, 500);
  });


  /*------------------------------------------------------------------------------------
    Article photo centering
  ------------------------------------------------------------------------------------*/
  function articlePhotoCenter(el){
    var img = el.find('.articlePhoto_img').first();
    var margin = -((img.height() - el.height()) / 2);
    img.css('marginTop', margin);
    console.log('margin set to ' + margin);
  };

  // Add the on.load event
  queue.pageLoadEvents.push(function(event){
    if( $('.articlePhoto')[0]){
      $('.articlePhoto').each(function(i, el){ articlePhotoCenter($(el)); });
    }
  });

  // Add the on.resize event
  queue.globalResizeEvents.push(function(event){
    if( $('.articlePhoto')[0]){
      $('.articlePhoto').each(function(i, el){ articlePhotoCenter($(el)); });
    }
  });


  /*------------------------------------------------------------------------------------
    Ripple click effect
  ------------------------------------------------------------------------------------*/
  $(document).on('click', '[data-ripple], a.button, .sectionHeader_info, .bigRadio', function(e){
    ripple($(this), event);
  });

  function ripple(target, event) {
    // Create a wave only if one does not exist
    if(target.find('.rippleWave').length == 0) {
      target.prepend("<span class='rippleWave'></span>");
    }

    // Find the child wave
    var wave = target.find('.rippleWave');

    // Reset wave to starting position
    wave.removeClass('animate');

    // Set the size of .rippleWave
    var cWidth = target.width();
    var cHeight = target.height();
    if ( cWidth > (cHeight*5) || cHeight > (cWidth*5) ){ var size = Math.max(cWidth, cHeight) / 1.5; }
    else { var size = Math.max(cWidth, cHeight); }
    wave.css({ width:size, height:size });

    // Get click coordinates
    x = event.pageX - target.offset().left - size/2;
    y = event.pageY - target.offset().top - size/2;

    // Position the wave and apply animation
    wave.css({top: y+'px', left: x+'px'}).addClass('animate');
  }


});