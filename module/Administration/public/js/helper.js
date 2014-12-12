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
