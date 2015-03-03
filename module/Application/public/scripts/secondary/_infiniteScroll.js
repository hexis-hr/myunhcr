queue.jQuery(function(){

  // Watch page scroll and load more news
  queue.globalScrollEvents.push(function(event){
    if( $('#infiniteScrollTrigger')[0]){
      if(checkIfVisible('#infiniteScrollTrigger') === true) { 
        console.log('Send ajax request for more news...');

          if ( ux.state.isLoading === false ){
            ux.state.isLoading = true;
            var link = $('.articleList_item').last().attr('data-link');
            getPartialPage(link);
          }
      }
    }
  });

});
