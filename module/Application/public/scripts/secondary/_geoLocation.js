var deviceLocation = {

  get: function(){
    if(geoPosition.init() === true){
      dlog('GEOLOCATION Supported: Attempting a trace...');
      if($('#geoLocator.geoLocator')[0]){ $('#geoLocator').addClass('-active -searching'); }
      geoPosition.getCurrentPosition(this.success, this.error, {
        timeout: 10000,
        highAccuracy: false
      });
    }
  },

  success: function(data){
    if($('#geoLocator.geoLocator')[0]){
      $('#geoLocator').removeClass('-searching').addClass('-success').html(data.coords.latitude + ', ' + data.coords.longitude);
      setTimeout(function(){
        $('#geoLocator').removeClass('-active -success');
      }, 2000);
    }
  },

  error: function(data){
    if($('#geoLocator.geoLocator')[0]){
      $('#geoLocator').removeClass('-searching').addClass('-error').html('Could not determine your precise location');
      setTimeout(function(){
        $('#geoLocator').removeClass('-active -error');
      }, 5000);
    }
  }

};


queue.pageLoadEvents.push(function (event) {
  
  if($('#geoLocator')[0]){
    deviceLocation.get();
  }

});