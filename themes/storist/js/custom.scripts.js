Date.prototype.format = function()
{
    return this.getFullYear() + '-' + (this.getMonth() + 1).DoubleDigit() + '-' + this.getDate().DoubleDigit();
};

Handlebars.registerHelper('ifEqual', function(a, b, options) {
    if(a === b) {
        return options.fn(this);
    }
    return options.inverse(this);
});

$(document).ready(function(e)
{
    BulmaAlert();
    var from        =   QueryString && QueryString.from ? QueryString.from : null,
        to          =   QueryString && QueryString.to ? QueryString.to : null,
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

    if (from) {
        $('.date-from.date-picker').val(from);
    }

    if (to) {
        $('.date-to.date-picker').val(from);
    }

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

            if (!$i.hasClass('do-sweep')) {
                updateSalesList();
            } else {
                $('.product-list').html('');
                if ($('input[name="search-target"]:checked').val() == 'freeview') {
                    getRankingList();
                }
            }
        }
    });

    function getRankingList()
    {
        $('#search-with-freeview').addClass('hide');
        $('#spinner-loading').removeClass('hide');
        $.get(
            '/api/v/1/ranking/',
            {
                from    :   $.trim($('input.date-from').val()).length > 0 ? $.trim($('input.date-from').val()) : null,
                to      :   $.trim($('input.date-to').val()).length > 0 ? $.trim($('input.date-to').val()) : null,
                by      :   $('input[name="search-target"]:checked').val()
            },
            function(data)
            {
                $('#search-with-freeview').removeClass('hide');
                $('#spinner-loading').addClass('hide');

                if (!$.isArray(data)) {
                    var template        =   Handlebars.compile(TemplateRankingItem),
                        tmpRows         =   template(data);

                    tmpRows = $($.trim(tmpRows));

                    $('.product-list').prepend(tmpRows);
                } else {
                    var list            =   new RankingList(data);
                    $('.product-list').prepend(list);
                }
            }
        );
    }

    if ($('#search-ranking').length > 0) {
        getRankingList();
        $('input[name="search-target"]').change(function(e)
        {
            $('.product-list').html('');
            if ($(this).val() == 'freeview') {
                getRankingList();
            }
        });
    }

    $('#search-ranking').change(function(e)
    {
        var barcode = $(this).val().trim();
        if (barcode.length > 0) {
            if ($('.product-list .sales-record[data-barcode="' + barcode + '"]').length == 0) {
                $.get(
                    '/api/v/1/ranking/' + barcode,
                    {
                        from    :   $.trim($('input.date-from').val()).length > 0 ? $.trim($('input.date-from').val()) : null,
                        to      :   $.trim($('input.date-to').val()).length > 0 ? $.trim($('input.date-to').val()) : null,
                        by      :   $('input[name="search-target"]:checked').val()
                    },
                    function(data)
                    {
                        if (!$.isArray(data)) {
                            var template        =   Handlebars.compile(TemplateRankingItem),
                                tmpRows         =   template(data);

                            tmpRows = $($.trim(tmpRows));

                            $('.product-list').prepend(tmpRows);
                        } else {
                            var list            =   new RankingList(data);
                            $('.product-list').prepend(list);
                        }
                    }
                );
            } else {
                alert('You have already added this product');
                setTimeout(function () {
                    $('.bulification .delete').focus();
                }, 100);
            }
            $('#search-ranking').val('');
        }
    }).keydown(function(e)
    {
        if (e.keyCode == 13)
        {
            $(this).change();
        }
    });

    $('#btn-reset-ranking').click(function(e)
    {
        e.preventDefault();
        $('.product-list').html('');
        $('#search-ranking, .date-picker').val('');
        if ($('input[name="search-target"]:checked').val() == 'freeview') {
            getRankingList();
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

                $('.product-row.is-temp').remove();
                barcoding = $.get(
                    '/api/v/1/cloud-products',
                    {
                        barcode: search
                    },
                    function(data)
                    {
                        barcoding = null;
                        if ($.isArray(data)) {

                            data.forEach(function(o)
                            {
                                o.cost          =   o.cost.toDollar();
                                o.price         =   o.price.toDollar();
                            });

                            var template        =   Handlebars.compile(TemplateProductRows),
                                tmpRows         =   template(data);

                            tmpRows = $($.trim(tmpRows));

                            tmpRows.filter('.product-row').dblclick(function(e)
                            {
                                e.preventDefault();
                                $(this).editProduct();
                            });

                            $('.product-list').prepend(tmpRows);

                        } else {
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

    $('.sales-record').dblclick(function(e)
    {
        e.preventDefault();
        var thisRow     =   $(this),
            records     =   null,
            ajaxing     =   null,
            btnRefund   =   thisRow.find('.btn-refund'),
            btnClose    =   thisRow.find('.btn-close');

        thisRow.parent().addClass('filtering');
        thisRow.addClass('is-target inspecting');
        btnRefund.addClass('is-loading');
        btnClose.unbind('click').click(function(e)
        {
            e.preventDefault();
            if (ajaxing) {
                ajaxing.abort();
                ajaxing =   null;
            }
            thisRow.parent().removeClass('filtering');
            thisRow.removeClass('is-target inspecting');
            if (records) {
                records.remove();
                records =   null;
            }
        });

        btnRefund.unbind('click').click(function(e)
        {
            e.preventDefault();
            alert('还没做好');
        });

        ajaxing         =   $.get(
            'api/v/1/sales/' + thisRow.data('receipt'),
            function(data)
            {
                btnRefund.removeClass('is-loading');
                ajaxing =   null;
                records =   new SaleDetail(data);
                records.insertAfter(thisRow);
            }
        );
    });

    function updateSalesList()
    {
        $.get(
            location.pathname,
            data_maker(),
            function(data)
            {
                $('#main .total-items').html($(data).find('.total-items').html());
                $('#main .sales-records').html($(data).find('.sales-records').html());
                $('#main .sales-pagination').html($(data).find('.sales-pagination').html());
            }
        );
    }























    if (window.exchange_rates) {
        // D3.js
        // var margin = {top: 0, right: 15, bottom: 30, left: 30},
        var margin = {top: 5, right: 5, bottom: 0, left: 5},
        width = $('#exchange-trend-holder').width() - margin.left - margin.right,
        height = 190 - margin.top - margin.bottom;

        // parse the date / time
        var parseTime = d3.timeParse("%d/%m/%Y");
        var formatTime = d3.timeFormat("%e %B");
        // set the ranges
        var x = d3.scaleTime().range([0, width]);
        var y = d3.scaleLinear().range([height, 0]);

        var div = d3.select("#exchange-trend-holder").append("div")
            .attr("class", "tooltip")
            .style("opacity", 0);
        // define the area
        var area = d3.area()
            .curve(d3.curveCardinal)
            .x(function(d) { return x(d.date); })
            .y0(height)
            .y1(function(d) { return y(d.close); });

        // define the line
        var valueline = d3.line()
            .curve(d3.curveCardinal)
            .x(function(d) { return x(d.date); })
            .y(function(d) { return y(d.close); });

        // append the svg obgect to the body of the page
        // appends a 'group' element to 'svg'
        // moves the 'group' element to the top left margin
        var svg = d3.select("#exchange-trend-holder").append("svg")
            .attr("width", width + margin.left + margin.right)
            .attr("height", height + margin.top + margin.bottom)
            .append("g")
            .attr("transform",
                  "translate(" + margin.left + "," + margin.top + ")");

        // get the data

        var data = window.exchange_rates;
        data.forEach(function(d) {
            d.date = parseTime(d.date);
            // d.close = +((d.close - 5) *100);
            d.close = +d.close;
        });

        //scale the range of the data
        var minY = d3.min(data, function(d) { return d.close; }) - 0.1;
        minY = minY < 0 ? 0 : minY;
        x.domain(d3.extent(data, function(d) { return d.date; }));
        y.domain([minY, d3.max(data, function(d) { return d.close; })]);

        // add the area
        svg.append("path")
            .data([data])
            .attr("class", "area")
            .attr("d", area)
            .style('fill', 'lightsteelblue');

        // add the valueline path.
        svg.append("path")
            .data([data])
            .attr("class", "line")
            .attr("d", valueline)
            .style('fill', 'none')
            .style('stroke', 'steelblue');

        // add the X Axis
        // svg.append("g")
        //     .attr("transform", "translate(0," + height + ")")
        //     .call(d3.axisBottom(x).tickFormat(d3.timeFormat("%d")));

        svg.selectAll("dot")
              .data(data)
            .enter().append("circle")
            .attr("r", 5)
            .attr("cx", function(d) { return x(d.date); })
            .attr("cy", function(d) { return y(d.close); })
            .on("mouseover", function(d) {
                div.transition()
                    .duration(200)
                    .style("opacity", .9);

                var cx  =   $(d3.event.target).attr('cx'),
                    cy  =   $(d3.event.target).attr('cy');

                div.html('<span class="is-block tooltip-date">' + formatTime(d.date) + '</span><span class="is-block tooltip-rate">' + d.close + '</span>')
                    .style("left", (cx) + "px")
                    .style("top", (cy - 42) + "px");
            })
            .on("mouseout", function(d) {
                div.transition()
                    .duration(500)
                    .style("opacity", 0);
            });
        // add the Y Axis
        // svg.append("g")
        //     .call(d3.axisLeft(y));
    }
});
