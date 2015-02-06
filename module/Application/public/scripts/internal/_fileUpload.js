queue.pageLoadEvents.push(function(event){
  if( $('[fileUpload]')[0] ){
    load('scripts/external/jquery.js')
    .then('scripts/external/jquery-ui-widget.js', 'scripts/external/jquery.iframe-transport.js')
    .then('scripts/external/jquery.fileupload.js')
    .thenRun(function(){
      
      

      console.log('File upload ready...');


      // In order to not conflict with Zepto, route the alias to $j
      var $j = jQuery.noConflict();


      $j(function(){
        $j('[fileUpload]').fileupload({
          dataType: 'json',
          done: function (e, data) {
            var t = $j(this);
            if (data.result.status == 'success') {
                $j.each(data.result.files, function (index, file) {
                    $j('<p/>').text(file.name).insertAfter(t);
                });
            } else if (data.result.status == 'error') {
                t.parents('.form_item:first').addClass('-error');
                $j('<div/>').text(data.result.message).addClass('form_error').insertAfter(t);
            }
          }
        });
      });



    });
  }
});
