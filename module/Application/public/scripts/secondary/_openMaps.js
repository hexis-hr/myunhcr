var geocoder, autocomplete;


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


// UX helpers for marker and infoWindow
window.app.mapPopups = {};
window.app.lastOpenPopup = '';


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

        // Autocomplete and center using google maps
        autocomplete = new google.maps.places.Autocomplete(document.getElementById('map_address'));
        google.maps.event.addListener(autocomplete, 'place_changed', function() {
          var place = autocomplete.getPlace();
          if (place.geometry.viewport) {
            app.currentMap.fitBounds(place.geometry.viewport);
          } else {
            app.currentMap.setCenter(place.geometry.location);
            app.currentMap.setZoom(17);  // Why 17? Because it looks good.
          }
        });

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

                        // Parse the marker data semantically
                        var data = {
                          id: 'popup-' + randomWithinRange(1000, 10000),
                          service: {
                            name: marker[0],
                            lat: marker[1],
                            lng: marker[2],
                            description: marker[3],
                            url: marker[4],
                          },
                          activity: {
                            name: marker[5],
                            category: marker[6],
                            dateStart: marker[7],
                            dateEnd: marker[8]
                          },
                          organization: {
                            name: marker[9]
                          },
                          sector: {
                            name: marker[10]
                          }
                        };

                        // Template for marker windows
                        // var descrLength = data.service.description.split(" ").join("").length;
                        // if(descrLength > ){  }
                        var mapPopup = ""+
                          "<div class='mapPopup'>"+
                            "<h1 class='mapPopup_title'>"+data.service.name+"</h1>"+
                            "<a class='mapPopup_website link' href='"+data.service.url+"'>"+url('hostname', data.service.url)+"</a>"+
                            "<div class='mapPopup_description'>"+
                              "<p>"+data.service.description+"</p>"+
                            "</div>"+
                          "</div>";

                        app.mapPopups[data.id] = new google.maps.InfoWindow({
                            content: mapPopup
                        });

                        marker = new google.maps.Marker({
                            position: new google.maps.LatLng(data.service.lat, data.service.lng),
                            map: app.currentMap,
                            title: data.service.name
                        });

                        // Bind a click event to this marker
                        google.maps.event.addListener(marker, 'click', function() {
                          closeExistingPopup();
                          showNewPopup(data, marker);
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

        function showNewPopup(data, marker) {
          app.mapPopups[data.id].open(app.currentMap, marker);
          app.lastOpenPopup = data.id;
        }

        function closeExistingPopup() {
          if(app.lastOpenPopup !== ''){ app.mapPopups[app.lastOpenPopup].close(); }
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
        load('http://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&callback=parseMap')
            .thenRun(function () {
                dlog('MAPS: Loaded main maps.google script...');
                ux.preload.getMaps = true;
            });
    } else {
        dlog('MAPS: Skipping main script download... already loaded.');
        parseMap();
    }
}
