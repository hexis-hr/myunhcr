/*

  This is the main controller for all map scripts

*/
queue.pageLoadEventsSecondary.push(function(event){
  

  // Resize the mobile map to fit vertically
  if ($('#map')[0]) {
    var mapContainer = $('#map').first();
    resizeMap(mapContainer);

    // Resize the map again when the viewport resizes
    queue.globalResizeEvents.push(function (event) {
      resizeMap(mapContainer);
    });

    $('#page').on('click', '#map', function(){
      $('#map_address').blur();
    });
    $('#page').on('click', '#map_address', function(){
      $('#map_address').focus();
    });
  }


  // Get the device location
  if ($('[data-autoLocate]')[0]) {
    app.deviceLocation.get();
  }


  // Watch for location submits
  if ($('[data-geocodeForm]')[0]) {
    // Have to track submit button click here as well. Android issues...
    $(document).on('click', '#map_address_submit', function(e){ 
      e.preventDefault();
      submitGeoForm();
    });
    $(document).on('submit', 'form[data-geocodeForm]', function(e){ 
      e.preventDefault();
      submitGeoForm();
    });
    function submitGeoForm(){
      app.geocode.setAddress( $('#map_address').val() );
      $('#map_address').blur();
    }
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