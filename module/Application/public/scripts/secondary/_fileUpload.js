/*

  Documentation: https://github.com/blueimp/jQuery-File-Upload

  NOTE: Upload scripts must always be (pre)loaded at the previous form step!
  TODO: Implement filetype and size check BEFORE uploading the files (critical for ux !!)
  TODO: Review upload security documentation: https://github.com/blueimp/jQuery-File-Upload/wiki/Security

*/
queue.pageLoadEvents.push(function(event) {


  // Triggers...
  if( $('[fileUpload]')[0] || $('[preload-fileUpload]')[0] ){
    
    // Log trigger fire
    dlog('UPLOAD: fileUpload trigger!');

    // Start this whole shit up
    getjQuery(function(){
      getFileUploadScripts(function(){
        handleFileUpload();
      });
    });

  }


  // Handle file uploading here...
  function handleFileUpload(){

    var fileUploadHTML = function(id, name, status, size, type){
      return "<li class='fileUI_item' data-id='"+id+"'>"+
               "<table class='fileUI_item_table'>"+
                 "<tr>"+
                   "<td class='fileUI_item_name' data-name><div>"+name+"</div></td>"+
                   "<td class='fileUI_item_status' data-status>"+status+"</td>"+
                   "<td class='fileUI_item_delete'><a data-delete>Delete?</a></td>"+
                 "</tr>"+
               "</table>"+
             "</li>";
    };

    var fileUpload = $j('[fileUpload]').fileupload({
      autoUpload: true,
      dataType: 'json',
      limitMultiFileUploads: 2,

      // ----------------------------------------------------------
      add: function (e, data) {
        dlog('UPLOAD: Files added...');
        
        // Store the upload UI element in this global variable
        // console.log($j(this));
        data.ui = $j(this).siblings('.fileUI').children('.fileUI_list').first();
        // console.log(data.ui);
        
        // Append each file added
        $(data.files).each(function(index){
          data.ui.append( fileUploadHTML(escape(this.name), this.name, data.ui.attr('data-ready'), this.size, this.type) );
        });

        // Automatically submit after adding (autoUpload does not seem to work)
        data.submit();

      },

      // ----------------------------------------------------------
      send: function (e, data) {
        dlog('UPLOAD: Files sending...');

        // Design stuff
        data.ui.parent().addClass('-uploading');
        var formItem = $j(this).parents('.form_item').first();
        formItem.removeClass('-error').removeClass('-success');

        // Update each file's status
        $(data.files).each(function(index){
          data.ui.find('[data-id="'+escape(this.name) + '"]').first().find('[data-status]').first().html(data.ui.attr('data-uploading'));
        });
      },

      // ----------------------------------------------------------
      progressall: function (e, data) {
        data.progressUI = $j(this).siblings('.fileUI').children('.fileUI_progress').first().children('.fileUI_progress_bar').first();
        var percent = parseInt(data.loaded / data.total * 100, 10);
        data.progressUI.css('width', percent + '%').html(percent + '%');
      },

      // ----------------------------------------------------------
      done: function (e, data) {
        
        // Reset design assets
        var progress = data.ui.siblings('.fileUI_progress').children('.fileUI_progress_bar');
        progress.css('width', 0).html('');
        data.ui.parent().removeClass('-uploading');

        // Success
        if (data.result.status == 'success') {
          dlog('UPLOAD: Success!');
          $(data.result.files).each(function(index, file){
            var item = data.ui.find('[data-id="'+escape(file.name) + '"]').first();
            item.addClass('-success');
            item.find('[data-status]').first().html(data.ui.attr('data-success'));
          });
        }

        // Error
        else {
          dlog('UPLOAD: Error! ' + data.result.message);

          // Add form_item error
          var formItem = $j(this).parents('.form_item').first();
          formItem.addClass('-error');
          formItem.children('.form_error').first().html(data.result.message);

          // Remove each file from the list
          $(data.files).each(function(index, file){
            data.ui.find('[data-id="'+escape(file.name) + '"]').remove();
          });
        }

      },

      // ----------------------------------------------------------
      fail: function (e, data) {
        dlog('UPLOAD: Ajax fail! ' + data.errorThrown);

        // Reset design assets
        var progress = data.ui.siblings('.fileUI_progress').children('.fileUI_progress_bar');
        progress.css('width', 0).html('');
        data.ui.parent().removeClass('-uploading');

        // Add form_item error
        var formItem = $j(this).parents('.form_item').first();
        formItem.addClass('-error');
        formItem.children('.form_error').first().html(data.errorThrown);

        // Remove each file from the list
        $(data.files).each(function(index, file){
          data.ui.find('[data-id="'+escape(file.name) + '"]').remove();
        });

      },

      // ----------------------------------------------------------
    });

    $(document).on('click', '.fileUI_item_delete', function(){
      alert('Delete not yet implemented!');
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
      dlog('GET: Skipping jQuery. Already loaded!');
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
        dlog('UPLOAD: Requesting fileUpload scripts...');

        // Run whatever callback script you define...
        callback();

      });
    } else {
      dlog('UPLOAD: Skipping fileUpload scripts. Scripts already loaded!');
      callback();
    }
  }


});