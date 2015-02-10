queue.jQuery(function(){

  // Re-run on load phase
  if ( ux.state.isLoading === false ){
    page.onLoad();  
  }

});