queue.jQuery(function(){

  // Custom select
  $(document).on('change','.customSelect_select', function() {
    $(this).next('.customSelect_overlay').html( $(this).find('option:selected').first().text() );
  });

});