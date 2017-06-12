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

    $('#search-product').change(function(e)
    {
        var search = $(this).val().trim();
        if (search.length > 0) {
            $('.product-pagination').addClass('disabled');
            $('.product-list').addClass('filtering');
            if ($('.product-row[data-barcode="' + search + '"]').length > 0) {
                $('.product-row[data-barcode="' + search + '"]').addClass('is-target');
            } else {
                if (barcoding) {
                    barcoding.abort();
                }

                barcoding = $.get(
                    '/api/v/1/cloud-products',
                    {
                        barcode: search
                    },
                    function(data)
                    {
                        barcoding = null;
                        if (data.id) {
                            var tmpRow  =   $('<div class="columns product-row is-marginless is-temp is-target">\
                                                <div class="column product-row__thumbnail is-1"></div>\
                                                <div class="column product-row__title"><span class="english"></span><br /><span class="chinese subtitle is-6"></span></div>\
                                                <div class="column product-row__stock-count is-1 has-text-centered"></div>\
                                                <div class="column product-row__cost has-text-centered is-2"></div>\
                                                <div class="column product-row__price has-text-centered is-2"></div>\
                                                <div class="column product-row__last-update is-2"></div>\
                                            </div>');
                            $('.product-list').prepend(tmpRow);
                            tmpRow.data('id', data.id);
                            tmpRow.data('title', data.title);
                            tmpRow.data('chinese', data.chinese_title);
                            tmpRow.data('cost', data.cost.toDollar());
                            tmpRow.data('price', data.price.toDollar());
                            tmpRow.data('stock-count', data.stock_count);
                            tmpRow.data('barcode', data.barcode);
                            tmpRow.data('width', data.width);
                            tmpRow.data('height', data.height);
                            tmpRow.data('depth', data.depth);
                            tmpRow.data('measurement', data.measurement);
                            tmpRow.data('weight', data.weight);
                            tmpRow.data('manufacturer', data.manufacturer);

                            tmpRow.find('.product-row__title .english').html(data.title);
                            tmpRow.find('.product-row__title .chinese').html(data.chinese_title);
                            tmpRow.find('.product-row__cost').html(data.cost.toDollar());
                            tmpRow.find('.product-row__price').html(data.price.toDollar());
                            tmpRow.find('.product-row__stock-count').html(data.stock_count);
                            tmpRow.find('.product-row__last-update').html(data.last_update);
                            tmpRow.dblclick(function(e)
                            {
                                e.preventDefault();
                                $(this).editProduct();
                            });
                        }

                        // if (data.find('.sales-record').length > 0) {
                        //     data.find('.sales-record').addClass('is-target is-temp').appendTo($('#main .sales-records'));
                        // }
                    }
                );
            }
        } else {
            $('.product-pagination').removeClass('disabled');
            $('.product-list').removeClass('filtering');
            $('.product-row').removeClass('is-target');
            $('.product-row.is-temp').remove();
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

            if ($('#search-product').length > 0 && $('#search-product').val().length > 0) {
                $('#search-product').val('');
                $('#search-product').change();
            }

            if ($('form.form-product').length > 0) {
                $('form.form-product').find('button.action.cancel').click();
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

    $('#btn-new-product').click(function(e)
    {
        e.preventDefault();
        if ($('.form-product').length == 0) {
            var productEditor = new FormProduct();
            $('.product-rows').addClass('editing');
            $('.product-list').prepend(productEditor);
            $('.product-list').scrollTo(productEditor, 500, {axis: 'y'});
        }
    });

    $('.product-row').dblclick(function(e)
    {
        e.preventDefault();
        $(this).editProduct();
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
