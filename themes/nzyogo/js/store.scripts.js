const _MANUAL_TRIGGER   =   10;
const _MANUAL_WITHIN    =   2000;

var _cumulatived    =   0,
    _timer          =   null,
    _isManualMode   =   false,
    creating_order  =   false;
$(window).click(function(e)
{
    if (!_isManualMode) {
        if (!_timer) {
            _timer = setTimeout(resetManualTrigger, _MANUAL_WITHIN);
        }

        _cumulatived++;
        if (_cumulatived == _MANUAL_TRIGGER) {
            clearTimeout(_timer);
            _timer = null;
            _cumulatived = 0;
            _isManualMode = true;
            $('body').addClass('manual-mode');
        }
    }
});

function resetManualTrigger()
{
    _cumulatived    =   0;
    _timer          =   null;
    _isManualMode   =   false;
    console.log('time out');
}

var _focusToLookup  =   null,
    _suspendfocus   =   false;

var Notification    =   function(title, message)
{
    var template    =   '<div class="notification">\
                            <div class="notification-heading"></div>\
                            <div class="notification-body"></div>\
                        </div>',
        self        =   this,
        added       =   false;

    this.html       =   $(template);

    if (title && title.length > 0) {
        this.html.find('.notification-heading').html(title);
    } else {
        this.html.find('.notification-heading').addClass('hide');
    }
    this.html.find('.notification-body').html(message);

    this.suicide    =   function()
                        {
                            if (timeout) {
                                clearTimeout(timeout);
                                timeout = null;
                            }

                            TweenMax.to(self.html, 0.25, {opacity: 0, onComplete: function()
                            {
                                self.html.remove();
                            }})
                        };
    var timeout     =   null,
        interval    =   setInterval(function ()
                        {
                            if (self.html.parent().length > 0) {
                                if (self.html.parent().is('body')) {
                                    clearInterval(interval);
                                    timeout = setTimeout(function()
                                    {
                                        self.suicide();
                                    }, 3000);
                                }
                            }
                        }, 10);

    return this.html;
};

$(document).ready(function(e)
{
    $('#StoreLookupForm_StoreLookupForm_Lookup').blur(function(e)
    {
        if (!_suspendfocus) {
            $('#StoreLookupForm_StoreLookupForm_Lookup').focus();
        }
    }).focus();

    window.tr = $('#to-buy-item-template').remove();
    window.tr.removeAttr('id');

    $('#StoreLookupForm_StoreLookupForm').ajaxSubmit(
        {
            onstart: function()
            {
                _cumulatived    =   0;
                _timer          =   null;
                _isManualMode   =   false;
                $('body').removeClass('manual-mode');
                $('#StoreLookupForm_StoreLookupForm_Lookup').val('');
                console.log('ajax submit initialised');
                $('body').addClass('pulling-product');
            },

            success: function(data)
            {
                data = JSON.parse(data);
                console.log(data);
                if ($.isArray(data)) {
                    if (data.length > 0) {
                        addItem(data[0]);
                    } else {
                        var msg = new Notification(null, 'Product not found');
                        $('body').append(msg.addClass('is-danger'));
                    }
                } else if (typeof(data) === 'object' && data.title) {
                    addItem(data);
                }
            },

            done: function(data)
            {
                $('body').removeClass('pulling-product');
            }
        }
    );

    $('#StoreOrderForm_StoreOrderForm .Actions input.action').click(function(e)
    {
        e.preventDefault();
        creating_order  =   true;
        var data        =   {},
            form        =   $('#StoreOrderForm_StoreOrderForm'),
            name        =   $(e.target).attr('name'),
            value       =   $(e.target).val();

        data.list       =   [];
        data.SecurityID =   form.find('input[name="SecurityID"]').val();
        data[name]      =   value;
        form.find('tbody tr').each(function(i, el)
        {
            var id      =   $(this).find('input[name="OrderID[]"]').val(),
                qty     =   $(this).find('input[name="Quantity[]"]').val(),
                amount  =   $(this).find('.to-buy__price').html().toFloat();
            data.list.push({
                MCProdID: id,
                Quantity: qty,
                AltAmount: amount
            });
        });

        $.post(form.attr('action'), data, function(response)
        {
            try {
                response = JSON.parse(response);
                if (response.result && response.result === true) {
                    $('#paid-at').html(response.when);
                    JsBarcode(
                        "#receipt-barcode",
                        response.receipt,
                        {
                            height: 30,
                            width: 1,
                            margin: 0
                        }
                    );
                    window.print();
                    EndofTrade();
                } else {
                    alert('ERROR');
                }
            } catch (err) {
                alert('ERROR');
            }
        });
    });

    $('.payment-trigger').click(function(e)
    {
        e.preventDefault();
        if (creating_order) return;
        $(this).removeClass('not-on-print');
        $('input[name="' + $(this).data('target') + '"]').click();
    });
});

function updateSum()
{
    var sum = 0;
    $('#to-buy tbody .to-buy__price').each(function(i, el)
    {
        var n = $(this).data('price').toFloat();
        sum += n;
    });

    $('#txt-sum-val').html(sum.toDollar());
    $('#txt-gst').html((sum.toFloat() * 0.15 / 1.15).toDollar());
}

function resetScreen()
{
    $('body').removeClass('has-queue');
}

function addItem(data)
{
    $('body').addClass('has-queue');

    var id          =   data.id,
        item        =   null,
        unitprice   =   null,
        qty         =   null,
        sum         =   0;
    if ($('input[name="OrderID[]"][value="' + id + '"]').length > 0) {
        item        =   $('input[name="OrderID[]"][value="' + id + '"]').parents('tr:eq(0)');
        qty         =   item.find('input[name="Quantity[]"]');
        unitprice   =   item.find('input[name="UnitPrice[]"]');
        qty.val(qty.val().toFloat() + 1);
    } else {
        item        =   window.tr.clone();
        unitprice   =   $('<input name="UnitPrice[]" type="text" readonly />');
        qty         =   $('<input name="Quantity[]" type="text" readonly />');

        var btn     =   $('<button />').addClass('button'),
            prodid  =   $('<input name="OrderID[]" type="hidden" />');

        prodid.val(data.id);
        unitprice.val(data.price.toDollar());
        unitprice.data('origin-price', data.price);
        qty.val(1);
        btn.html('remove');
        btn.click(function(e)
        {
            e.preventDefault();
            var i = qty.val().toFloat();
            i--;
            qty.val(i);

            sum = qty.val().toFloat() * unitprice.val().toFloat();
            item.find('.to-buy__price').html(sum.toDollar());
            item.find('.to-buy__price').data('price', sum);

            if (i <= 0) {
                item.remove();
            }

            updateSum();
            if ($('#to-buy tbody tr').length == 0) {
                resetScreen();
            }
        });

        qty.dblclick(function(e)
        {
            e.preventDefault();
            _suspendfocus = true;
            $(this).prop('readonly', false);
            $(this).focus();
        }).focus(function(e)
        {
            var currenntQty = $(this).val().toFloat();
            $(this).val(currenntQty);
            $(this).data('current-qty', currenntQty);
            $(this).select();
        }).blur(function(e)
        {
            _suspendfocus = false;
            var newqty = $(this).val().toFloat();
            if (newqty < 0) {
                newqty = $(this).data('current-qty').toFloat() + newqty;
            }

            if (newqty > 0) {
                $(this).val(newqty);

                $(this).prop('readonly', true);
                sum = qty.val().toFloat() * unitprice.val().toFloat();
                item.find('.to-buy__price').html(sum.toDollar());
                item.find('.to-buy__price').data('price', sum);
                updateSum();
                $('#StoreLookupForm_StoreLookupForm_Lookup').focus();
            } else {
                btn.click();
            }

        }).keydown(function(e)
        {
            if (e.keyCode == 13) {
                e.preventDefault();
                $(this).blur();
            }
        });

        unitprice.dblclick(function(e)
        {
            e.preventDefault();
            _suspendfocus = true;
            $(this).prop('readonly', false);
            $(this).focus();
        }).focus(function(e)
        {
            var currentPrice = $(this).val().toFloat();
            $(this).val(currentPrice);
            $(this).data('current-price', currentPrice);
            $(this).select();
        }).blur(function(e)
        {
            _suspendfocus = false;
            var newprice = $(this).val().toFloat();

            if (newprice < 0) {
                newprice = $(this).data('current-price').toFloat() + newprice > 0 ? $(this).data('current-price').toFloat() + newprice : 0;
            }

            $(this).val(newprice.toDollar());

            if (newprice != $(this).data('origin-price')) {
                $(this).addClass('red');
            } else {
                $(this).removeClass('red');
            }

            $(this).prop('readonly', true);
            sum = qty.val().toFloat() * $(this).val().toFloat();
            item.find('.to-buy__price').html(sum.toDollar());
            item.find('.to-buy__price').data('price', sum);
            updateSum();
            $('#StoreLookupForm_StoreLookupForm_Lookup').focus();
        }).keydown(function(e)
        {
            if (e.keyCode == 13) {
                e.preventDefault();
                $(this).blur();
            }
        });

        item.find('.to-buy__action').append(btn, prodid);
        item.find('.to-buy__title').append(data.title);
        item.find('.to-buy__unit-price').append(unitprice);
        item.find('.to-buy__quantity').append(qty);
        $('#to-buy tbody').append(item);
    }

    $('#StoreOrderForm_StoreOrderForm').scrollTo(item, 500, {axis: 'y', offset: -70});

    sum = qty.val().toFloat() * unitprice.val().toFloat();
    item.find('.to-buy__price').html(sum.toDollar());
    item.find('.to-buy__price').data('price', sum);

    updateSum();
}

function EndofTrade()
{
    var buttons     =   [
        {
            Label: 'Print receipt'
        },
        {
            Label: 'Done'
        }
    ];

    var splayer = new simplayer('Thanks!', null, buttons, null, null, false);
    splayer.show();
    splayer.btnEvent(0, function() {
        window.print();
    })
    splayer.btnEvent(1, function() {
        $('#to-buy tbody tr').remove();
        $('.payment-trigger').addClass('not-on-print');
        $('#receipt-barcode').html('');
        updateSum();
        resetScreen();
        creating_order = false;
        splayer.close();
    });
}
