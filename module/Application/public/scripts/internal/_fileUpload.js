/*

  Documentation: https://github.com/blueimp/jQuery-File-Upload

*/
queue.pageLoadEvents.push(function(event) {


  // Trigger this script if fileUpload is detected on the page
  if( $('[fileUpload]')[0] ){
    
    // Log trigger
    dlog('File upload detected. Loading scripts...');

    // Start this whole shit up
    getjQuery(function(){
      getFileUploadScripts(function(){
        handleFileUpload();
      });
    });

  }


  // Handle file uploading here...
  function handleFileUpload(){
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
  }


  // Get jQuery function
  function getjQuery(callback){
    if(ux.preload.jQuery !== true){
      load('scripts/external/jquery.js').thenRun(function(){
        
        // Log that the scripts are loaded so they won't be called again
        ux.preload.jQuery = true;
        dlog('GET: jQuery');

        // To avoid conflicts with Zepto, route the jQuery alias to $j
        window.$j = jQuery.noConflict();

        // Run whatever callback script you define...
        callback();

      });
    } else {
      dlog('SKIP: jQuery. Already loaded!');
      callback();
    }
  }


  // Get file upload scripts
  function getFileUploadScripts(callback){
    if(ux.preload.fileUploadScripts !== true){
      load('scripts/external/jquery-ui-widget.js', 'scripts/external/jquery.iframe-transport.js')
      .then('scripts/external/jquery.fileupload.js')
      .thenRun(function(){
        
        // Log that the scripts are loaded so they won't be called again
        ux.preload.fileUploadScripts = true;
        dlog('GET: File upload scripts');

        // Run whatever callback script you define...
        callback();

      });
    } else {
      dlog('SKIP: File upload scripts. Already loaded!');
      callback();
    }
  }


});