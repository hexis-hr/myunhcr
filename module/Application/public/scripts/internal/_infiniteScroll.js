queue.jQuery(function(){

  // Watch page scroll and load more news
  queue.globalScrollEvents.push(function(event){
    if( $('#infiniteScrollTrigger')[0]){
      if(checkIfVisible('#infiniteScrollTrigger') === true) { 
        console.log('Send ajax request for more news...');

        // See /scripts/internal/_ajax.js
        // getPage(url, title, method, data, timeout)

        // Mozda ti zatreba i /scripts/internal/_historyState.js
      }
    }
  });

});