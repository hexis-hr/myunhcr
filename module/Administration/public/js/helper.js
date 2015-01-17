/**
 * Created by toni on 12/12/14.
 *
 * Helper functions for handling actions over entities
 */

handle('[data-delete]', function () {
    $(this).on('click', function (e) {
        e.preventDefault();

        if (confirm('You will remove chosen item and all items related to this entity, do you want to continue?')) {
            $.ajax({
                type: 'POST',
                url: $(this).data('href'),
                success: function(response){
                    $('[data-row="row_' + response.id + '"]').fadeOut(500);
                },
                error: function() {
                    console.log('Error on entity delete');
                }
            });
        }
    })
});

handle('[data-activeCheck]', function () {
    $(this).on('change', function (e) {
        e.preventDefault();

        var status = false;

        if($(this).attr('checked'))
            status = true;

        $.ajax({
            type: 'POST',
            url: $(this).data('href'),
            data: {
                'status' : status
            },
            success: function(response){
                if (response.status == 0) {
                    $(this).removeAttr('checked');
                } else {
                    $(this).attr('checked');
                }
            },
            error: function() {
                console.log('Error on entity action handle');
            }
        });
    })
});

handle('[data-countrySelection]', function () {

    $(this).on('click', function (e) {
        e.preventDefault();

        $.ajax({
            type: 'POST',
            url: $(this).data('href'),
            success: function(response){

                var active = $('[data-activeCountry]');
                active.html(response.country);

                var activeId = active.data('activeId');
                $('li #'+ activeId).removeClass('active');
                $('li #'+ response.countryId).addClass('active');
                active.attr('id', response.countryId);
            },
            error: function() {
                console.log('Error on entity delete');
            }
        });
    })
});

handle('[data-activateCountry]', function () {

    $(this).on('click', function (e) {
        e.preventDefault();

        var select = $('#country option:selected');
        $.ajax({
            type: 'POST',
            url: $(this).data('href'),
            data: {
                countryId : select.val()
            },
            success: function(response){

                $('.activeCountries').prepend('<div class="col-md-3" id="activeCountry_' + response.countryId +
                    '" style="padding: 0"><div class="widget-stats"><div class="col-md-8">' +
                    '<strong>' + response.countryName + '</strong></div><div class="col-md-4">' +
                    '<a class="btn btn-sm btn-primary" href="' + response.editUrl + '"><strong>Edit</strong></a>' +
                    '<a class="btn btn-sm btn-danger" href="#" data-deleteActiveCountry ' +
                    'data-href="' + response.deleteUrl + '" data-id="' + response.countryId + '">' +
                    '<strong>Delete</strong></a></div><div class="clearfix"></div></div></div>');

                $('ul .activeCountriesNav').prepend('<li id="' + response.countryId + '"><a href="#" ' +
                    'data-href="' + response.countrySelectionUrl + '"' +
                    'title="' + response.countryName + '" data-countrySelection>' + response.countryName + '</a></li>');

                select.remove();

                var activeMessage = $('.activeCountryMessage');
                if (activeMessage)
                    activeMessage.remove();

                //refresh handle
                $(handleElements);
            },
            error: function() {
                console.log('Error on country activation');
            }
        });
    })
});

handle('[data-editCountryLanguage]', function () {

    $(this).on('click', function (e) {
        e.preventDefault();

        var select = $('#language option:selected');

        $.ajax({
            type: 'POST',
            url: $(this).data('href'),
            data: {
                language : select.val()
            },
            success: function(response){

                console.log(response);

                $('.activeLanguages').prepend('<div class="col-md-2" id="activeLanguage_' + response.countryId +
                    '_' + response.language + '" style="padding: 0"><div class="widget-stats"><div class="col-md-7">' +
                    '<h5>' + response.language + '</h5></div><div class="col-md-5"><div class="separator"></div>' +
                    '<a class="btn btn-sm btn-danger" href="#" data-deleteActiveLanguage ' +
                    'data-href="' + response.deleteUrl + '" data-id="' + response.countryId + '">' +
                    '<strong>Delete</strong></a></div><div class="clearfix"></div></div>');

                select.remove();

                var activeMessage = $('.activeLanguageMessage');
                if (activeMessage)
                    activeMessage.remove();

                //refresh handle
                $(handleElements);
            },
            error: function() {
                console.log('Error on country language managing');
            }
        });
    })
});

handle('[data-deleteActiveCountry]', function () {

    $(this).on('click', function (e) {
        e.preventDefault();

        if ($('[data-activeCountry]').attr('id') == $(this).data('id')) {
            alert('Cannot remove currently active country.');
            return;
        }

        $.ajax({
            type: 'POST',
            url: $(this).data('href'),
            success: function(response){

                $('#activeCountry_' + response.countryId).remove();
                $('#country').prepend($('<option></option>').attr("value", response.countryId).text(response.countryName));
                $('.activeCountriesNav li#' + response.countryId).remove();
            },
            error: function() {
                console.log('Error on country deletion');
            }
        });
    })
});

handle('[data-deleteActiveLanguage]', function () {

    $(this).on('click', function (e) {
        e.preventDefault();

        $.ajax({
            type: 'POST',
            url: $(this).data('href'),
            success: function(response){

                $('#activeLanguage_' + response.countryId + '_' + response.language).remove();
                $('#language').prepend($('<option></option>').attr("value", response.language).text(response.language));
            },
            error: function() {
                console.log('Error on country language deletion');
            }
        });
    })
});

handle('[data-checkActiveCountry]', function () {

    if ($('[data-activeCountry]').attr('id') == '') {

        var navLi = $('.activeCountriesNav li');

        if (navLi.length >= 1) {

            navLi.each(function() {
                $('#activateCountrySelect').append($('<option></option>').attr("value", $(this).attr('id')).text($(this).text()));
            });

            var modal = $('#activateCountryModal');
            modal.modal({
                backdrop: 'static',
                keyboard: false
            });

            $('[data-activateCountrySubmit]').on('click', function (e) {
                e.preventDefault();

                $.ajax({
                    type: 'POST',
                    url: $(this).data('href'),
                    data: {
                        id: $('#activateCountrySelect option:selected').val()
                    },
                    success: function(response){

                        var active = $('[data-activeCountry]');
                        active.html(response.country);

                        var  activeId = active.attr('id');
                        $('li [data-activeCountry]').removeClass('active');
                        $('li #'+ response.countryId).addClass('active');

                        active.attr('id', response.countryId);
                        modal.modal('toggle');
                    },
                    error: function() {
                        console.log('Error on country activation');
                    }
                });
            })
        } else {

            $( "#dialogActivateCountry" ).dialog({
                modal: true,
                closeOnEscape: false,
                buttons: {
                    Ok: function() {
                        $( this ).dialog( "close" );
                        window.location.replace('/settings');
                    }
                },
                beforeClose: function () {
                    return false;
                }
            });

        }
    }
});
