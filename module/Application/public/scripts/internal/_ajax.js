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
function getPage(url, method, data, timeout) {
  
  // Documentation: http://zeptojs.com/#ajax

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
  $('#page, #pageLoad').addClass('-loading');

  // Page unload events
  page.onUnload();


  // Generate random delay number
  // var randomDelay = randomWithinRange(200, 2000);
  
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
      dlog('GOT: ' + url + '! (' + execTime + 'ms)');

      // Push history state and toggle related switches
      History.pushState(null, title, url);
      ux.state.isLoading = false;

      // Scroll to top
      $("html, body").scrollTop(0);

      // Show the page, hide the spinner
      // setTimeout(function(){
      $('#page, #pageLoad').removeClass('-loading');
      // }, randomWithinRange(200, 2000));

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
      $('#pageLoad').html("Sorry, this is taking long. We will try one more time...");

      // After a small pause, initiate a redirect to the target url
      setTimeout(function(){
        window.location = url;
      }, 500);

    }
  });
}