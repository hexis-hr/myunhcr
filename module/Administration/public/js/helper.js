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
