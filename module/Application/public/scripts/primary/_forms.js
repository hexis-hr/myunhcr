queue.jQuery(function(){


  /*------------------------------------------------------------------------------------
    Custom select
  ------------------------------------------------------------------------------------*/
  $(document).on('change','.customSelect_select', function() {
    $(this).siblings('.customSelect_overlay').html( $(this).find('option:selected').first().text() );
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