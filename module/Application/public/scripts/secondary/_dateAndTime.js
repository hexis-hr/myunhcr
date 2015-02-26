queue.jQuery(function(){

  queue.pageLoadEventsSecondary.push(function(event){
    if( $('[data-inputDate]')[0] || $('[data-inputTime]')[0] ){ // Check if there are date & time fields
      
      // Avoid replacing date & time in favor of native device UI
      if((app.userDevice === 'Android' && app.userDeviceVersion > 4.3) || (app.userDevice === 'iOS' && app.userDeviceVersion > 5)){
        /* Do nothing for these devices since they have their own date & time UI */
      }
      else {
        load('scripts/external/picker.js').thenRun(function(){
          console.log('GET: Picker.js loaded!');

          // Change type to basic inputs
          $('[data-inputDate], [data-inputTime]').attr('type', 'input');

          // Assign a placeholder to date input
          $('[data-inputDate]').each(function () {
            $(this).attr('placeholder', $(this).attr('data-placeholder'));
          });

          // Assign a placeholder to date input
          $('[data-inputTime]').each(function () {
            $(this).attr('placeholder', $(this).attr('data-placeholder'));
          });

          // After validation assign the required format to date input
          $('[data-inputDate]').each(function () {
            if ($(this).val()) {
              var date = $(this).val().split('-');
              $(this).val(date[2] + '/' + date[1] + '/' + date[0]);
            }
          });



          // Activate custom date picker
          $('[data-inputDate]').pickadate({
            container: 'body',
            // onOpen: function(){
            //   checkIfPickerFits();
            // },
            // onClose: function(){ page.demote(); },
            format: 'dd/mm/yyyy',
            formatSubmit: 'yyyy-mm-dd',
            hiddenSuffix: '',

            // Disable these dates in the calendar (0 = January)
            disable: [
              [2015,0,14],
              [2015,0,15],
              [2015,0,16]
            ]
          });

          // Activate custom time picker
          $('[data-inputTime]').pickatime({
            container: 'body',
            // onOpen: function(){
            //   checkIfPickerFits();
            // },
            // onClose: function(){ page.demote(); },
            format: 'HH:i',
            min: [8, parseInt('00')], // Seems we have to use parseInt here to avoid strict errors
            max: [16, parseInt('00')]
          });

        });
      }

      // picker__button--close
      $(document).on('click','.picker__button--close', function() {
        dlog('picker.close()');
        // picker.close();
      });
    }
  });

  queue.pageUnloadEvents.push(function(event){
    // Remove leftover date & times
    $('.picker').remove();
  });

});
