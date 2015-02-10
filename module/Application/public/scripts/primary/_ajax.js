// Documentation: http://zeptojs.com/#ajax



/*------------------------------------------------------------------------------------
  Save and restore scroll positions for certain pages
------------------------------------------------------------------------------------*/
var pageOffset = {
  save: function(){
    ux.page[ux.page.current].scroll = ux.scroll.offset;
  },
  restore: function(){
    var whitelist = ['pg-homepage'];
    if( $.inArray(ux.page.current, whitelist) > -1 ){
      $(window).scrollTop( ux.page[ux.page.current].scroll );
    }
  }
};



/*------------------------------------------------------------------------------------
  Get page function
------------------------------------------------------------------------------------*/
function getPage(url, method, data) {

  // Log page request
  dlog('--------------------- PAGE ---------------------');
  dlog('GET: ' + url);
  var start = new Date().getTime();
  
  // Store the scroll offset for current page
  pageOffset.save();

  // Parse config
  var method = method || 'GET';
  var data = data || [];

  // Hide the page, show the spinner
  $('#page, #pageLoad').removeClass('-problem').addClass('-loading');

  // Page unload events
  page.onUnload();

  // The ajax call wrapped in an internal function
  $.ajax({
    type: method,
    url: url,
    data: data,
    timeout: 10000, // Wait for 10 seconds max
    success: function(data) {
      var title = $('#relay').attr('data-pageTitle') || app.name;

      // Swap data
      $('#page').empty().html(data);
      $('body').removeClass().addClass($('#relay').attr('bodyClass'));

      // Log success and waiting time
      var execTime = new Date().getTime() - start;
      dlog('GOT: ' + url + '! (' + execTime + 'ms)');

      // Push history state and toggle related switches
      History.pushState(null, title, url);
      ux.state.isLoading = false;

      // Scroll to top
      $("html, body").scrollTop(0);

      // Show the page, hide the spinner
      $('#page, #pageLoad').removeClass('-loading');

      // Run all pageLoad events
      page.onLoad();

      // Apply the scroll offset if applicable
      pageOffset.restore();

    },
    error: function(xhr) {
      var execTime = new Date().getTime() - start;
      dlog('GET: ' + url + ' ERROR!' + xhr.status + ': ' + xhr.statusText + ' (' + execTime + 'ms)');
      $('#pageLoad').addClass('-problem');
      
      switch(xhr.status) {
        
        case 404:
          $('#pageLoad').html("Page not found!<br><br>Redirecting to homepage...");
          setTimeout(function(){
            window.location = '/';
          }, 1000);
          break;
        
        default:
          // Assuming network drop, try once more...
          if ( ux.state.pageNotFound === false ) {
            dlog('GET: Assuming network drop. Trying one more time...');
            $('#pageLoad').html("Sorry this is taking long. We will try one more time...");
            ux.state.pageNotFound = true;
            setTimeout(function(){
              ajaxCall(url, method, data);
            }, 1000);
          }
          // If this is the second time, hard redirect to homepage. Some shit ain't right.
          else {
            dlog('Something went wrong. Redirecting to homepage...');
            $('#pageLoad').html("Something went wrong. Redirecting to homepage...");
            setTimeout(function(){
              window.location = '/';
            }, 1000);
          }

      }
    }
  });
  
}