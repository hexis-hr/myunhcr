queue.pageLoadEvents.push(function(event){
  if( $('[fileUpload]')[0] ){
    load('scripts/external/jquery.js')
    .then('scripts/external/jquery-ui-widget.js', 'scripts/external/jquery.iframe-transport.js')
    .then('scripts/external/jquery.fileupload.js')
    .thenRun(function(){
      
      

      console.log('File upload ready...');
      
      $(function(){
        $('#fileupload').fileupload({
          dataType: 'json',
          done: function (e, data) {
            $.each(data.result.files, function (index, file) {
              $('<p/>').text(file.name).appendTo(document.body);
            });
          }
        });
      });



    });
  }
});