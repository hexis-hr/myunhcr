/*

  This is the main controller for all map scripts

*/
queue.pageLoadEvents.push(function (event) {
  
  // If there's a map on this page, resize it to fit
  if ($('#map')[0]) {
    var mapContainer = $('#map').first();
    resizeMap(mapContainer);

    // Also add the on.resize event for map resizing
    queue.globalResizeEvents.push(function (event) {
      resizeMap(mapContainer);
      google.maps.event.trigger(app.currentMapapp.currentMap, 'resize');
    });
  }

  // Get the device location if there's an autoLocate attribute on this page
  if ($('[data-autoLocate]')[0]) {
    deviceLocation.get();
  }

  // If there is a geocode form, watch the submit
  if ($('[data-geocodeForm]')[0]) {
    $(document).on('submit', '[data-geocodeForm]', function (e) {
      e.preventDefault();
      app.geocode.setAddress( $('#location').val() );
    });
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
}