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


});