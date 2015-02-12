/*

  This is the main controller for all map scripts

*/
queue.pageLoadEvents.push(function(event){
  
  // Resize the mobile map to fit vertically
  if ($('#map')[0]) {
    var mapContainer = $('#map').first();
    resizeMap(mapContainer);

    // Resize the map again when the viewport resizes
    queue.globalResizeEvents.push(function (event) {
      resizeMap(mapContainer);
    });
  }

  // Get the device location
  if ($('[data-autoLocate]')[0]) {
    app.deviceLocation.get();
  }

  // Watch for location submits
  if ($('[data-geocodeForm]')[0]) {
    $(document).on('submit', '[data-geocodeForm]', function (e) {
      e.preventDefault();
      app.geocode.setAddress( $('#location').val() );
    });
  }

  // Call scripts, configure map, etc...
  if ($('[data-openStreetMap]')[0]) {
    dlog('MAPS: Calling map handler...');
    app.handleMap();
  }

  // Not used?
  // if ($('[data-geocodeForm]')[0]) {
  //   $(document).on('click', '[data-geocode]', function (e) {
  //     app.geocode.setAddress( $('#location').val() );
  //   });
  // } else {
  //   $(document).off('click', '[data-geocode]');
  // }      

});


// Resize the map to fit the screen nicely
function resizeMap(mapContainer) {
  var height = ux.viewport.height - mapContainer.offset().top;
  if (height < 250) { height = 250; }
  mapContainer.height(height);
  if(ux.state.mapsLoaded === true){
    dlog('MAPS: Triggering resize event...');
    google.maps.event.trigger(app.currentMap, 'resize');
  }
}