var geocoder;


// Geocode object for pushing locations to google maps
app.geocode = {
  params: {},

  setAddress: function(address){
    dlog('MAPS: Pushing ' + address + ' location to map...');
    geocoder.geocode({ 'address':address }, this.callback);
  },

  setLocation: function(latitude,longitude){
    app.currentMap.setCenter({ 'lat':latitude, 'lng':longitude });
  },

  callback: function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
      app.currentMap.fitBounds(results[0].geometry.viewport);
    } else {
      alert("Geocode was not successful for the following reason: " + status);
    }
  }
};


// if ($('[data-openStreetMap]')[0]) {
app.handleMap = function() {

    // Load map scripts
    getMaps();

    // This is the callback function which will execute upon script load
    window.parseMap = function () {

        var element = document.getElementById('map');

        var mapOptions = {
            center: new google.maps.LatLng(51.503454, -0.119562),
            zoom: 8,
            mapTypeId: 'OSM',
            mapTypeControl: false,
            streetViewControl: false
        };

        window.app.currentMap = new google.maps.Map(element, mapOptions);
        geocoder = new google.maps.Geocoder();

        app.currentMap.mapTypes.set('OSM', new google.maps.ImageMapType({
            getTileUrl: function (coord, zoom) {
                return 'http://tile.openstreetmap.org/' + zoom + '/' + coord.x + '/' + coord.y + '.png';
            },
            tileSize: new google.maps.Size(256, 256),
            name: 'OpenStreetMap',
            maxZoom: 18
        }));

        var markers = [];
        var oldMarkers = [];

        google.maps.event.addListener(app.currentMap, 'tilesloaded', function () {
            getMarkers();
        });
        google.maps.event.addListener(app.currentMap, 'dragend', function () {
            getMarkers();
        });

        function getMarkers() {
            var bounds = app.currentMap.getBounds();
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

                    clearMarkers();

                    // Multiple Markers
                    markers = response.data;

                    $.each(markers, function (key, marker) {

                        var content = '<h5>' + marker[0] + '</h5><p>' + marker[3] + '</p><p><a href="' + marker[4] +
                            '">' + marker[0] + '</a></p><h6>Activity</h6><p>' + marker[5] + '</p><p>' + marker[6] + '</p>' +
                            '<p>' + marker[7] + '</p><p>' + marker[8] + '</p><h6>Organization</h6><p>' + marker[9] + '</p>' +
                            '<h6>Sector</h6><p>' + marker[10] + '</p>';

                        var infowindow = new google.maps.InfoWindow({
                            content: content
                        });

                        marker = new google.maps.Marker({
                            position: new google.maps.LatLng(marker[1], marker[2]),
                            map: app.currentMap,
                            title: marker[0]
                        });

                        google.maps.event.addListener(marker, 'click', function() {
                            infowindow.open(app.currentMap, marker);
                        });

                        marker.setMap(app.currentMap);
                        oldMarkers.push(marker);
                    });

                },
                error: function (xhr, type) {
                    console.log('Error on service map handling');
                }
            });
        }

        // Sets the map on all markers in the array.
        function setAllMap() {
            for (var i = 0; i < oldMarkers.length; i++) {
                oldMarkers[i].setMap(app.currentMap);
            }
            oldMarkers = [];
        }

        // Removes the markers from the map, but keeps them in the array.
        function clearMarkers() {
            setAllMap(null);
        }

        // If the map was already loaded once, we probably have the location...
        if( ux.geo.coarse.lat !== undefined ){
          app.deviceLocation.pushToMap();
        }

        // Log that maps have been loaded and parsed for future reference.
        ux.state.mapsLoaded = true;

    }
}


// Get file upload scripts
function getMaps() {
    if (ux.preload.getMaps !== true) {
        load('http://maps.googleapis.com/maps/api/js?v=3.exp&callback=parseMap')
            .thenRun(function () {
                dlog('MAPS: Loaded main maps.google script...');
                ux.preload.getMaps = true;
            });
    } else {
        dlog('MAPS: Skipping main script download... already loaded.');
        parseMap();
    }
}
