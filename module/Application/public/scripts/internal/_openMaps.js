queue.pageLoadEvents.push(function(event){
  if( $('[data-openStreetMap]')[0] ){
    var mapContainer = '';


    // Resize the map container based on available screen estate
    if ($('.mapContainer.-fill')[0]){
      var mapContainer = $('.mapContainer.-fill').first();
      resizeMap(mapContainer);
    };


    // Load map scripts
    getMaps();


    // This is the callback function which will execute upon script load
    window.gmap = function(){
      
      var element = document.getElementById('map');

      var mapOptions = {
        center: new google.maps.LatLng(51.503454, -0.119562),
        zoom: 8,
        mapTypeId: 'OSM',
        mapTypeControl: false,
        streetViewControl: false
      };

      var map = new google.maps.Map(element, mapOptions);

      map.mapTypes.set('OSM', new google.maps.ImageMapType({
        getTileUrl: function(coord, zoom) {
          return 'http://tile.openstreetmap.org/' + zoom + '/' + coord.x + '/' + coord.y + '.png';
        },
        tileSize: new google.maps.Size(256, 256),
        name: 'OpenStreetMap',
        maxZoom: 18
      }));

      var markers = [];

      google.maps.event.addListener(map, 'bounds_changed', function(){
        var bounds = map.getBounds();
        var northEast = bounds.getNorthEast(); // LatLng of the north-east corner
        var southWest = bounds.getSouthWest();
        var nelat = northEast.lat();
        var nelng = northEast.lng();
        var swlat = southWest.lat();
        var swlng = southWest.lng();

        $.ajax({
          type: 'POST',
          url: $('#map').data('href'),
          data: {
            nelat: nelat,
            nelng: nelng,
            swlat: swlat,
            swlng: swlng
          },
          timeout: 10000, // Wait for 10 seconds max
          success: function (response) {

            // Multiple Markers
            markers = response.data;

            $.each(markers, function (key, marker) {
              marker = new google.maps.Marker({
                position: new google.maps.LatLng(marker[1], marker[2]),
                map: map,
                title: marker[0]
              });
              marker.setMap(map);
            });

          },
          error: function (xhr, type) {
            console.log('Error on service map handling');
          }
        });
      });


      
      // Add the on.resize event
      queue.globalResizeEvents.push(function(event){
        resizeMap(mapContainer);
        google.maps.event.trigger(map, 'resize');
      });


    }
  }


  // Resize the map to fit the screen nicely
  function resizeMap(mapContainer){
    var height = ux.viewport.height - mapContainer.offset().top;
    if ( height < 250 ){ height = 250; }
    mapContainer.height(height);
  }


  // Get file upload scripts
  function getMaps(callback){
    callback = callback || function(){};
    if(ux.preload.getMaps !== true){
      load('http://maps.googleapis.com/maps/api/js?v=3.exp&callback=gmap')
      .thenRun(function(){
        ux.preload.getMaps = true;
        dlog('GET: Google Maps API');
        setTimeout(function(){
          callback();
        }, 50);
        
      });
    } else {
      dlog('SKIP: Google Maps API. Already loaded!');
      callback();
    }
  }


});