/*------------------------------------------------------------------------------------
  Update user info on resize event
------------------------------------------------------------------------------------*/
var pageOffset = {
  save: function(){
    ux.scrollOffset[$('#relay').attr('bodyClass')] = ux.scroll.offset;
  },
  restore: function(){
    var currentPage = $('#relay').attr('bodyClass');
    var allowedPages = ['pg-homepage'];
    if( $.inArray(currentPage, allowedPages) > -1 ){
      $(window).scrollTop( ux.scrollOffset[currentPage] );
    }
  }
};



/*------------------------------------------------------------------------------------
  Get page function
------------------------------------------------------------------------------------*/
function getPage(url, method, data, timeout) {
  
  // Documentation: http://zeptojs.com/#ajax

  // Log page request
  dlog('GET: ' + url + ' started...');
  var start = new Date().getTime();
  
  // Store the scroll offset for current page
  pageOffset.save();

  // Parse config
  var method = method || 'GET';
  var data = data || [];

  // Hide the content of the page and show a loader
  $('#page').addClass('-loading');

  // Page unload events
  page.onUnload();
  
  // Start the request
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
      dlog('GET: ' + url + ' success! (' + execTime + 'ms)');

      // Push history state and toggle related switches
      History.pushState(null, title, url);
      ux.url.isLoading = false;

      // Scroll to top
      $("html, body").scrollTop(0);

      
      $('#page').removeClass('-loading');

      // Run all pageLoad events
      page.onLoad();

      // Apply the scroll offset if applicable
      pageOffset.restore();

    },
    error: function(xhr, type) {
      
      // Log error and waiting time
      var execTime = new Date().getTime() - start;
      dlog('GET: ' + url + ' error! Reason: ' + type + '. (' + execTime + 'ms)');

      // Show an error message
      $('#pageLoad').html("Sorry, this is taking long. We will try one more time...").addClass('-show');

      // After a small pause, initiate a redirect to the target url
      setTimeout(function(){
        window.location = url;
      }, 500);

    }
  });
}