queue.jQuery(function(){

  
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