// Plugin documentation: https://github.com/estebanav/javascript-mobile-desktop-geolocation


var deviceLocation = {

  get: function(){
    dlog('GEOLOCATION: Fetching device location...');
    this.getCookie();
    this.getLocation();
  },

  getCookie: function(){
    // Check if we have a stored location for this device...
    dlog('GEOLOCATION: No previous location stored in cookie...');
  },

  getLocation: function(){
    if (geoPosition.init() === true) {
      dlog('GEOLOCATION: Device supports it. Attempting a live trace...');
      
      // If there is a geolocator element, update it
      if($('#geoLocator.geoLocator')[0]){
        var gl = $('#geoLocator');
        gl.html(gl.attr('data-searchingMsg')).addClass('-active -searching');
      }
      
      geoPosition.getCurrentPosition(this.success, this.error, {
        timeout: 10000,
        highAccuracy: false
      });
    }
  },

  success: function(data){
    var lat = data.coords.latitude;
    var lon = data.coords.longitude;
    dlog('GEOLOCATION: Success! ' + lat + '|' + lon);
    
    // If there is an autoLocate parameter on this page, send a push to google maps
    if ($('[data-autoLocate]')[0]) {
      app.geocode.setLocation(lat, lon);
    }

    // If there is a geolocator element, update it
    if ($('#geoLocator')[0]) {
      var gl = $('#geoLocator');
      gl.removeClass('-searching').addClass('-success').html(gl.attr('data-successMsg'));
      setTimeout(function(){
        $('#geoLocator').removeClass('-active -success');
      }, 2000);
    }
  },

  error: function(data){
    dlog('GEOLOCATION: Error! ' + data);
    
    // If there is a geolocator element, update it
    if ($('#geoLocator')[0]) {
      var gl = $('#geoLocator');
      gl.removeClass('-searching').addClass('-error').html(gl.attr('data-errorMsg'));
      setTimeout(function(){
        $('#geoLocator').removeClass('-active -error');
      }, 5000);
    }
  }

};


queue.pageLoadEvents.push(function (event) {
  
  // Get the device location if there's an autoLocate attribute on this page
  if ($('[data-autoLocate]')[0]) {
    deviceLocation.get();
  }

});