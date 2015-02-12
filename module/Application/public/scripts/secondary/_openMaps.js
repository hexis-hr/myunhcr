queue.pageLoadEvents.push(function (event) {

    var geocoder;

    if ($('[data-openStreetMap]')[0]) {
        var mapContainer = '';


        // Resize the map container based on available screen estate
        if ($('#map')[0]) {
            var mapContainer = $('#map').first();
            resizeMap(mapContainer);
        }

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

            var map = new google.maps.Map(element, mapOptions);
            geocoder = new google.maps.Geocoder();

            map.mapTypes.set('OSM', new google.maps.ImageMapType({
                getTileUrl: function (coord, zoom) {
                    return 'http://tile.openstreetmap.org/' + zoom + '/' + coord.x + '/' + coord.y + '.png';
                },
                tileSize: new google.maps.Size(256, 256),
                name: 'OpenStreetMap',
                maxZoom: 18
            }));

            var markers = [];
            var oldMarkers = [];

            google.maps.event.addListener(map, 'tilesloaded', function () {
                getMarkers();
            });
            google.maps.event.addListener(map, 'dragend', function () {
                getMarkers();
            });

            // Add the on.resize event
            queue.globalResizeEvents.push(function (event) {
                resizeMap(mapContainer);
                google.maps.event.trigger(map, 'resize');
            });

            function getMarkers() {
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

                        clearMarkers();

                        // Multiple Markers
                        markers = response.data;

                        $.each(markers, function (key, marker) {
                            marker = new google.maps.Marker({
                                position: new google.maps.LatLng(marker[1], marker[2]),
                                map: map,
                                title: marker[0]
                            });
                            marker.setMap(map);
                            oldMarkers.push(marker);
                        });

                    },
                    error: function (xhr, type) {
                        console.log('Error on service map handling');
                    }
                });
            }

            // Sets the map on all markers in the array.
            function setAllMap(map) {
                for (var i = 0; i < oldMarkers.length; i++) {
                    oldMarkers[i].setMap(map);
                }
                oldMarkers = [];
            }

            // Removes the markers from the map, but keeps them in the array.
            function clearMarkers() {
                setAllMap(null);
            }

            function geocode() {
                var address = document.getElementById('location').value;

                geocoder.geocode({'address': address}, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        map.fitBounds(results[0].geometry.viewport);
                    } else {
                        alert("Geocode was not successful for the following reason: " + status);
                    }
                });
            }

            $(document).on('click', '[data-geocode]', function (e) {
                geocode();
            });

            $(document).on('submit', '[data-geocodeForm]', function (e) {
                e.preventDefault();
                geocode();
            });
        }
    }

    // Resize the map to fit the screen nicely
    function resizeMap(mapContainer) {
        var height = ux.viewport.height - mapContainer.offset().top;
        if (height < 250) {
            height = 250;
        }
        mapContainer.height(height);
    }


    // Get file upload scripts
    function getMaps() {
        if (ux.preload.getMaps !== true) {
            load('http://maps.googleapis.com/maps/api/js?v=3.exp&callback=parseMap')
                .thenRun(function () {
                    ux.preload.getMaps = true;
                    dlog('GET: Google Maps API');
                });
        } else {
            dlog('SKIP: Google Maps API. Already loaded!');
            parseMap();
        }
    }


});
