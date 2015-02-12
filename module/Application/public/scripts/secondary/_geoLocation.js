// Plugin documentation: https://github.com/estebanav/javascript-mobile-desktop-geolocation
app.deviceLocation = {


  get: function(){
    if( ux.geo.accurate.lat === undefined ){
      dlog('GEOLOCATION: Fetching device location...');

      // First, check for a stored accurate location...
      if( ux.geo.accurate.lat !== undefined ){
        dlog('GEOLOCATION: Accurate location retrieved from session!');
        // app.deviceLocation.pushToMap();
        return true;
      }

      // Next, check for a stored rough location...
      else if( ux.geo.coarse.lat !== undefined ){
        dlog('GEOLOCATION: Coarse location retrieved from session!');

        // Try to get the accurate location this time...
        if(ux.state.isTracing===false && $('#map')[0]){
          if (ux.geo.accurate.failed < 4) {
            this.getRealtime('accurate');
            return true;
          } else {
            dlog('GEOLOCATION: Failed 3 times already. Skipping...');
          }
        }
      }

      // If no succes, try reading from cookie...
      this.checkCookie();

    } else {
      dlog('GEOLOCATION: Already have trace. Skipping...');
    }
  },


  checkCookie: function(){
    // Check if we have a stored location for this device...
    // Needs code here...

    // If no succes, try getting realtime from the device...
    if(ux.state.isTracing===false){ this.getRealtime('coarse'); }
  },


  getRealtime: function(type){
    ux.state.isTracing = true;
    clearTimeout(app.deviceLocation.timeout);

    if (geoPosition.init() === true) {
      dlog('GEOLOCATION: Attempting a ' + type + ' trace to device location...');

      // Set default parameters
      ux.geo.attemptedTrace = type;
      var params = {
        timeout: 10000,
        highAccuracy: false
      };

      // If we have already have a rough location, try a more accurate one...
      if( type === 'accurate' ){
        ux.geo.attemptedTrace = type;
        params = {
          timeout: 10000,
          highAccuracy: true
        };
      }
      
      // If there is a geolocator element, update it
      if($('#geoLocator')[0]){
        var gl = $('#geoLocator');
        gl.html(gl.attr('data-searchingMsg')).addClass('-active -searching');
      }
      
      geoPosition.getCurrentPosition(this.getRealtime_success, this.getRealtime_error, {
        timeout: 10000,
        highAccuracy: false
      });

    } else {
      ux.geo[type].failed++;
      dlog('GEOLOCATION: No stored locations or device-supported geolocation!');
      return false;
    }
  },


  getRealtime_success: function(data){
    ux.state.isTracing = false;

    // Store the location for this session
    var gs = ux.geo.attemptedTrace;
    ux.geo[gs].lat = data.coords.latitude;
    ux.geo[gs].lng = data.coords.longitude;
    dlog('GEOLOCATION: ' + gs + ' trace! ' + data.coords.latitude + '|' + data.coords.longitude);
    
    // If there is an autoLocate parameter on this page, send a push to google maps
    if ($('#map')[0]) {
      app.deviceLocation.pushToMap();
    }

    // If there is a geolocator element, update it
    if ($('#geoLocator')[0]) {
      var gl = $('#geoLocator');
      gl.removeClass('-searching').addClass('-success').html(gl.attr('data-successMsg'));
      app.deviceLocation.timeout = setTimeout(function(){
        $('#geoLocator').removeClass('-active -success');
      }, 2000);
    }

  },


  getRealtime_error: function(data){
    ux.state.isTracing = false;
    ux.geo[type].failed++;
    dlog('GEOLOCATION: Error! ' + data.message + ' for ' + ux.geo.attemptedTrace + ' trace.');
    
    // If there is a geolocator element, update it
    if ($('#geoLocator')[0]) {
      var gl = $('#geoLocator');
      gl.removeClass('-searching').addClass('-error').html(gl.attr('data-errorMsg'));
      app.deviceLocation.timeout = setTimeout(function(){
        $('#geoLocator').removeClass('-active -error');
      }, 5000);
    }
  },


  pushToMap: function(lat, lng){
    if( ux.geo.accurate.lat !== undefined ){
      dlog('MAPS: Pushing accurate location to google maps...');
      app.geocode.setLocation(ux.geo.accurate.lat, ux.geo.accurate.lng);
    }

    else if( ux.geo.coarse.lat !== undefined ){
      dlog('MAPS: Pushing coarse location to google maps...');
      app.geocode.setLocation(ux.geo.coarse.lat, ux.geo.coarse.lng);
    }

    else {
      dlog('MAPS: pushToMap error!');
    }
  }


};