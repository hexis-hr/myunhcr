queue.jQuery(function(){


  /*------------------------------------------------------------------------------------
    Accordion (Used on FAQ pages)
  ------------------------------------------------------------------------------------*/
  var accordion = {
    open: function(target, openItems){
      dlog_verbose('faq.open()');
      openItems.removeAttr('accordionOpen').find('[autoHeight]').first().height(0);
      target.attr('accordionOpen', '').find('[autoHeight]').first().animateAuto();
    },
    close: function(target){
      dlog_verbose('faq.close()');
      target.removeAttr('accordionOpen').find('[autoHeight]').first().height(0);
    }
  };

  // Accordion item click
  $(document).on('click, focus','[accordionItem]', function() {
    var openItems = $(this).parent().children('[accordionOpen]');
    accordion.open($(this), openItems);
  });

  // CLose on unfocus
  $(document).on('blur','[accordionItem]', function() {
    accordion.close($(this));
  });


  /*------------------------------------------------------------------------------------
    Something for country?
  ------------------------------------------------------------------------------------*/
  $(document).on('change', '.settings #country', function(){
        $.ajax({
            type: 'POST',
            url: $(this).attr('data-ajax-href'),
            data: {
                countryId : $(this).val()
            },
            success: function(response){
                $('#location').html('');
                $('.location_overlay').html('');
                if (response.locations) {
                    $.each(response.locations, function (index, item) {
                        $('#location').append($('<option>').attr('value', index).html(item));
                    });
                    $('.location_overlay').html($('#location option').first().html());
                }
            },
            error: function() {
                console.log('Error on country activation');
            }
        });
    });


});