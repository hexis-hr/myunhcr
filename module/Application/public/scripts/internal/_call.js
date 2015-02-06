queue.jQuery(function(){

  $(document).on('click','#callScreen_open', function() {
    var totalHeight = ux.viewport.height;
    var headerHeight = 58;
    var dialHeight = 200;
    var padding = (totalHeight - headerHeight * 2 - dialHeight) / 2;
    
    $('#callScreen').children('.callScreen_middle').css('paddingTop', padding);
    $('#callScreen').addClass('-show');
  });

  $(document).on('click','#callScreen_close', function() {
    $('#callScreen').removeClass('-show');
  });

});