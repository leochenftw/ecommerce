Date.prototype.format = function()
{
    return this.getFullYear() + '-' + (this.getMonth() + 1).DoubleDigit() + '-' + this.getDate().DoubleDigit();
};

$(document).ready(function(e)
{
    var from        =   null,
        to          =   null,
        enterframe  =   null,
        barcoding   =   null,
        data_maker  =   function()
                        {
                            var data = {};
                            if (from) {
                                data.from               =   from.format();
                            }

                            if (to) {
                                data.to                 =   to.format();
                            }

                            if (!$('#show-refunded').prop('checked')) {
                                data.exclude_refund     =   true;
                            }

                            if (!$('#show-success').prop('checked')) {
                                data.exclude_success    =   true;
                            }

                            return data;
                        };

    $('input.date-picker').datetimepicker(
    {
        timepicker: false,
        format: 'Y-m-d',
        scrollInput: false,
        scrollMonth: false,
        onSelectDate: function(ct,$i)
        {
            if ($i.is('.date-from')) {
                from    =   ct;
            } else {
                to      =   ct;
            }

            if (from && to) {
                if ($i.is('.date-from') && from > to) {
                    $('input.date-picker.date-to').val(from.format());
                } else if ($i.is('.date-to') && from > to) {
                    $('input.date-picker.date-from').val(to.format());
                }
            }

            updateSalesList();
        }
    });

    $('#search-receipt').change(function(e)
    {
        var receipt = $(this).val().trim();
        if (receipt.length > 0) {
            $('.sales-records').addClass('filtering');
            if ($('.sales-record[data-receipt="' + $(this).val().trim() + '"]').length > 0) {
                $('.sales-record[data-receipt="' + $(this).val().trim() + '"]').addClass('is-target');
            } else {
                if (barcoding) {
                    barcoding.abort();
                }

                barcoding = $.get(
                    location.pathname,
                    {
                        receipt: receipt
                    },
                    function(data)
                    {
                        barcoding = null;
                        data = $(data);
                        if (data.find('.sales-record').length > 0) {
                            data.find('.sales-record').addClass('is-target is-temp').appendTo($('#main .sales-records'));
                        }
                    }
                );
            }
        } else {
            $('.sales-records').removeClass('filtering');
            $('.sales-record').removeClass('is-target');
            $('.sales-record.is-temp').remove();
        }
    }).keydown(function(e)
    {
        if (e.keyCode == 13)
        {
            $(this).change();
        }
    });

    $('.to-exclude').change(function(e)
    {
        updateSalesList();
    });

    $('#btn-refresh').click(function(e)
    {
        e.preventDefault();
        updateSalesList();
    });

    $(window).keydown(function(e)
    {
        if (e.keyCode == 27) { // ESC
            if ($('#search-receipt').length > 0 && $('#search-receipt').val().length > 0) {
                $('#search-receipt').val('');
                $('#search-receipt').change();
            }
        }
    });

    $('.operators .operator').OperatorWork();

    $('#btn-new-operator').click(function(e)
    {
        e.preventDefault();
        if ($('.form-operator').length == 0) {
            var operatorEditor = new FormOperator();
            $(document.body).append(operatorEditor);
            $('.operator-list').prepend(operatorEditor);
        }
    });

    $('input.simple-previewable').previewable();
    $('.btn-file-browser').click(function(e)
    {
        e.preventDefault();
        $(this).parents('form:eq(0)').find('input.simple-previewable').click();
    });

    function updateSalesList()
    {
        $.get(
            location.pathname,
            data_maker(),
            function(data)
            {
                $('#main .sales-records').html($(data).find('.sales-records').html());
            }
        );
    }
});
