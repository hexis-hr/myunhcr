queue.jQuery(function(){


  /*------------------------------------------------------------------------------------
    Custom select
  ------------------------------------------------------------------------------------*/
  $(document).on('change','.customSelect_select', function() {
    $(this).siblings('.customSelect_overlay').html( $(this).find('option:selected').first().text() );
  });


  /*------------------------------------------------------------------------------------
    Clear query button
  ------------------------------------------------------------------------------------*/
  // Add the clear button if there's an input supporting it
  queue.pageLoadEvents.push(function(){
    if( $('[data-clearQuery]')[0] ){
      
      // Attach the clearing element
      $('[data-clearQuery]').each(function(){
        $(this).parent().prepend("<a class='clearQuery'><i class='icon -crossBlack20'></i></a>");
        if( $(this).val() !== '' ){ $(this).siblings('.clearQuery').addClass('-visible'); }
      });
      

      // Watch the input element for changes in real-time
      $('#page').on('change keyup input paste click', '[data-clearQuery]', function() {
        if( $(this).val() === '' || $(this).val() === ' ' ){
          $(this).siblings('.clearQuery').removeClass('-visible');
        } else {
          $(this).siblings('.clearQuery').addClass('-visible');
        }
      });

    }
  });

  

  // Watch clearing clicks
  $('#page').on('click','.clearQuery', function() {
    $(this).next('input').val('').focus();
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