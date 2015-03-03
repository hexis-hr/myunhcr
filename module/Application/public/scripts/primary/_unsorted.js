queue.jQuery(function () {


    /*------------------------------------------------------------------------------------
     Accordion (Used on FAQ pages)
     ------------------------------------------------------------------------------------*/
    var accordion = {
        open: function (target, openItems) {
            dlog_verbose('faq.open()');
            openItems.removeAttr('accordionOpen').find('[autoHeight]').first().height(0);
            target.attr('accordionOpen', '').find('[autoHeight]').first().animateAuto();
        },
        close: function (target) {
            dlog_verbose('faq.close()');
            target.removeAttr('accordionOpen').find('[autoHeight]').first().height(0);
        }
    };

    // Accordion item click
    $(document).on('click, focus', '[accordionItem]', function () {
        var openItems = $(this).parent().children('[accordionOpen]');
        accordion.open($(this), openItems);
    });

    // CLose on unfocus
    $(document).on('blur', '[accordionItem]', function () {
        accordion.close($(this));
    });

    //sector search
    $(document).ready(function () {
        $('#sectorFilter').on('keyup', function () {
            var searchKeyword = $('#searchField').val();

            if (searchKeyword.length >= 1) {
                $.ajax({
                    type: "POST",
                    url: $(this).data('url'),
                    data: { keyword: searchKeyword },
                    success: function (response) {
                        if (response.status == 'success') {
                            $('.sectorList').html('');
                            $.each(response.sectors, function (index, value) {
                                $('.sectorList').append('<li class="orgList_item">' +
                                    '<div class="orgList_link"><span class="orgList_item_acronym">' +
                                    value.sectorAcronym + '</span>' + value.sectorName + '</a></li>');
                            });
                        } else {
                            $('.sectorList').html('<div class="feedback -unknown gapTopSmall">' +
                                '<div class="feedback_description preformat">' +
                                '<h3>No sector data found.</h3></div></div>');
                        }
                    }
                });
            } else {
                $.ajax({
                    type: "POST",
                    url: $(this).attr('action'),
                    data: { keyword: 'all' },
                    success: function (response) {
                        if (response.status == 'success') {
                            $('.sectorList').html('');
                            $.each(response.sectors, function (index, value) {
                                $('.sectorList').append('<li class="orgList_item">' +
                                    '<div class="orgList_link"><span class="orgList_item_acronym">' +
                                    value.sectorAcronym + '</span>' + value.sectorName + '</a></li>');
                            });
                        } else {
                            $('.sectorList').html('<div class="feedback -unknown gapTopSmall">' +
                                '<div class="feedback_description preformat">' +
                                '<h3>No sector data found.</h3></div></div>');
                        }
                    }
                });
            }
        });
    });

    //sector search
    $(document).ready(function () {
        $('#organizationFilter').on('keyup', function () {
            var filterKeyword = $('#filterField').val();

            if (filterKeyword.length >= 1) {
                $.ajax({
                    type: "POST",
                    url: $(this).attr('action'),
                    data: { keyword: filterKeyword },
                    success: function (response) {
                        if (response.status == 'success') {
                            $('.orgList').html('');
                            $.each(response.orgs, function (index, value) {
                                $('.orgList').append('<li class="orgList_item">' +
                                    '<a class="orgList_link" data-ripple href="' + value.orgUrl + '" ajaxNav tabindex="0">' +
                                    '<span class="orgList_item_acronym">' + value.orgAcronym + '</span>' + value.orgName +
                                    '</a></li>');
                            });
                        } else {
                            $('.orgList').html('<div class="feedback -unknown gapTopSmall">' +
                                '<div class="feedback_description preformat">' +
                                '<h3>No organization data found.</h3></div></div>');
                        }
                    }
                });
            } else {
                $.ajax({
                    type: "POST",
                    url: $(this).attr('action'),
                    data: { keyword: 'all' },
                    success: function (response) {
                        if (response.status == 'success') {
                            $('.orgList').html('');
                            $.each(response.orgs, function (index, value) {
                                $('.orgList').append('<li class="orgList_item">' +
                                    '<a class="orgList_link" data-ripple href="' + value.orgUrl + '" ajaxNav tabindex="0">' +
                                    '<span class="orgList_item_acronym">' + value.orgAcronym + '</span>' + value.orgName +
                                    '</a></li>');
                            });
                        } else {
                            $('.orgList').html('<div class="feedback -unknown gapTopSmall">' +
                                '<div class="feedback_description preformat">' +
                                '<h3>No organization data found.</h3></div></div>');
                        }
                    }
                });
            }
        });
    });

});
