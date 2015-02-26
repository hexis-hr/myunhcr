queue.jQuery(function(){


  /*------------------------------------------------------------------------------------
    Check for date and time inputs, and load libraries if required
  ------------------------------------------------------------------------------------*/
  // queue.pageLoadEventsSecondary.push(function(event){
  //   if( $('[inputDate]')[0] || $('[inputTime]')[0] ){ // Check if there are date & time fields
  //     // Avoid replacing date & time in favor of native device UI
  //     if((app.userDevice === 'Android' && app.userDeviceVersion > 4.3) || (app.userDevice === 'iOS' && app.userDeviceVersion > 5)){
  //       // Do nothing for these devices since they have their own date & time UI
  //     } else {
  //       load('scripts/external/picker.js').thenRun(function(){
  //         console.log('GET: Picker.js loaded!');

  //         // Change type to basic inputs
  //         $('[inputDate], [inputTime]').attr('type', 'input');

  //         // Activate custom date picker
  //         $('[inputDate]').pickadate({
  //           format: 'dd/mm/yyyy',
            
  //           // Disable these dates in the calendar (0 = January)
  //           disable: [
  //             [2015,0,14],
  //             [2015,0,15],
  //             [2015,0,16]
  //           ]
  //         });

  //         // Activate custom time picker
  //         $('[inputTime]').pickatime({
  //           format: 'HH:i',

  //           // Available hours to pick from
  //           min: [8,00],
  //           max: [16,0]
  //         });
  //       });
  //     }
  //   }
  // });


  /*------------------------------------------------------------------------------------
    Add repeatable subform element
  ------------------------------------------------------------------------------------*/
  $(document).on('click', '.addSubform', function(e){
    e.preventDefault();

    console.log($(this));
    var subform = $('.surveyRepeat_' + $(this).data('subform'));

    var currentCount =  subform.length;
    var newCount = currentCount + 1;
    var lastRepeatingGroup = subform.last();

    var newSection = lastRepeatingGroup.clone();
    newSection.insertAfter(lastRepeatingGroup);

    newSection.find('input').each(function (index, input) {
      input.name = input.name.replace('[' + currentCount + ']', '[' + newCount + ']');
    });

    $(this).hide();
  });


});
