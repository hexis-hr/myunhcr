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

    //sector search
    $(document).ready(function() {
        $('#searchInput').on('keyup', function() {
            var searchKeyword = $(this).val();
            if (searchKeyword.length >= 2) {
                $.ajax({
                    type: "POST",
                    url: $(this).data('url'),
                    data: { keyword: searchKeyword },
                    success: function (response) {
                        if (response.status == 'success') {
                            $('.sectorSearch').html('<ul></ul>');
                            $.each(response.sectors, function (index, value) {
                                $('.sectorSearch ul').append('<li>' + value + '</li>');
                            });
                        } else  {
                            $('.sectorSearch').html('No sector data found');
                        }
                    }
                });
            }
        });
    });

});
