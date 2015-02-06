queue.pageLoadEvents.push(function(event){

    if( $('[data-openStreetMap]')[0] ){

        //Google maps API initialisation
        var element = document.getElementById("map");

        var map = new google.maps.Map(element, {
            center: new google.maps.LatLng(51.503454, -0.119562),
            zoom: 8,
            mapTypeId: "OSM",
            mapTypeControl: false,
            streetViewControl: false
        });

        //Define OSM map type pointing at the OpenStreetMap tile server
        map.mapTypes.set("OSM", new google.maps.ImageMapType({
            getTileUrl: function(coord, zoom) {
                return "http://tile.openstreetmap.org/" + zoom + "/" + coord.x + "/" + coord.y + ".png";
            },
            tileSize: new google.maps.Size(256, 256),
            name: "OpenStreetMap",
            maxZoom: 18
        }));

        var markers = [];

        google.maps.event.addListener(map, 'bounds_changed', function() {

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

    }
});
