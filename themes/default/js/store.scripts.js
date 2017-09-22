const _MANUAL_TRIGGER   =   10;
const _MANUAL_WITHIN    =   2000;

var _cumulatived        =   0,
    _timer              =   null,
    _isManualMode       =   false,
    _isRefunding        =   false,
    _discount           =   0,
    creating_order      =   false,
    btnStashTemplate    =   $('<button class="button icon-basket">\
                                <span>\
                                    <time class="as-inline-block"></time>\
                                    <i>ON HOLD</i>\
                                </span>\
                            </button>');
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
    readStashes();

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

            validator: function()
            {
                var barcode     =   $('#StoreLookupForm_StoreLookupForm_Lookup').val().trim();

                if (barcode == 'enterrefundmode') {
                    if (!_isRefunding) {
                        _isRefunding = true;
                        $('body').addClass('refunding');
                    } else {
                        _isRefunding = false;
                        $('body').removeClass('refunding');
                    }

                    $('#StoreLookupForm_StoreLookupForm_Lookup').val('');
                    return false;
                }

                return true;
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
        creating_order      =   true;
        var data            =   {},
            form            =   $('#StoreOrderForm_StoreOrderForm'),
            name            =   $(e.target).attr('name'),
            value           =   $(e.target).val();

        data.list           =   [];
        data.SecurityID     =   form.find('input[name="SecurityID"]').val();
        data[name]          =   value;
        data['isRefunding'] =   _isRefunding;

        form.find('tbody tr').each(function(i, el)
        {
            var id          =   $(this).find('input[name="OrderID[]"]').val(),
                qty         =   $(this).find('input[name="Quantity[]"]').val(),
                amount      =   $(this).find('.to-buy__price').html().toFloat();

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
        var me          =   $(this);
        if ($(this).data('target') == 'action_byCash' && !_isRefunding) {
            CalculateChanges(function()
            {
                me.removeClass('not-on-print');
                $('input[name="' + me.data('target') + '"]').click();
            });
        } else {
            me.removeClass('not-on-print');
            $('input[name="' + me.data('target') + '"]').click();
        }
    });

    $('#btn-stash').click(function(e)
    {
        e.preventDefault();
        var timestamp   =   'stash-' + Date.now(),
            stash       =   [];
        $('#to-buy tbody tr').each(function(i, el)
        {
            var me      =   $(this),
                data    =   {
                                id      :   me.find('input[name="OrderID[]"]').val(),
                                price   :   me.find('input[name="UnitPrice[]"]').val().toFloat(),
                                qty     :   me.find('input[name="Quantity[]"]').val(),
                                title   :   me.find('.to-buy__title').html()
                            };

            stash.push(data);
        });

        createStashButton(timestamp);

        window.StashList[timestamp]   =   stash;
        SaveStashes();
        flushQueue();
    });
});

$(window).load(function(e)
{
    var w = $('#supplier-logo').outerWidth();
    $('#stashes .stashes__stash').css('padding-right', w * 0.75);
});

function readStashes()
{
    if (typeof(Storage) !== "undefined") {
        window.StashList        =   [];
        if (window.localStorage.stashes) {
            var lst             =   JSON.parse(window.localStorage.stashes);
            lst.forEach(function(o)
            {
                window.StashList[o.timestamp]   =   o.stash;
                createStashButton(o.timestamp);
            });
        }
    }
}

function createStashButton(timestamp)
{
    var btnStash    =   btnStashTemplate.clone(),
        time        =   new Date(timestamp.replace(/stash-/gi, '').toFloat());

    btnStash.data('timestamp', timestamp);
    btnStash.find('time').html(time.getHours().DoubleDigit() + ':' + time.getMinutes().DoubleDigit());

    btnStash.click(function(e)
    {
        e.preventDefault();
        var timestampe      =   $(this).data('timestamp'),
            confirmation    =   true;

        if ($('#to-buy tbody tr').length > 0) {
            confirmation = confirm('Pop the stash will flush the current queue. Do you wish to continue?');
        }

        if (confirmation) {
            flushQueue();

            var lst = window.StashList[timestampe];
            lst.forEach(function(item)
            {
                addItem(item);
            });

            btnStash.remove();

            delete window.StashList[timestampe];
            SaveStashes();
        }
    });

    $('#stashes .stashes__stash').append(btnStash);
    $('body').addClass('has-stash');
}

function SaveStashes()
{
    var arr = [];
    for (var key in window.StashList)
    {
        var stash   =   window.StashList[key];
        if (typeof(stash) != 'function') {
            var data    =   {
                                timestamp: key,
                                stash: stash
                            }
            arr.push(data);
        }
    }

    window.localStorage.stashes =   JSON.stringify(arr);
}

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
    $('body').removeClass('has-queue refunding');
    _suspendfocus = false;
    _isRefunding = false;
    $('#StoreLookupForm_StoreLookupForm_Lookup').focus();
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
        qty.val(data.qty != undefined ? data.qty : 1);
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

function CalculateChanges(callback)
{
    _suspendfocus   =   true;
    var buttons     =   [
        {
            Label: 'Calculate'
        }
    ];

    var input       =   $('<input id="cash-received" placeholder="Amount received" type="number" class="text" style="margin-top: 20px; outline: none; font-size: 32px; display: block; width: 100%; text-align: center; border-radius: 10px; border: 1px solid #999; padding: 0.25em 1em;" />'),
        result      =   $('<p />'),
        proceed     =   false,
        clsHanlder  =   function()
                        {
                            if (ticker) {
                                clearInterval(ticker);
                                ticker = null;
                            }
                            $(window).unbind('keydown', kdHandler);
                            _suspendfocus   =   false;
                            splayer.close();
                            $('#StoreLookupForm_StoreLookupForm_Lookup').focus();
                        },
        kdHandler   =   function(e)
                        {
                            if (e.keyCode == 27) {
                                if (input.val().length > 0) {
                                    input.val('');
                                    result.html('Change: <strong style="color: #4CAF50;">$0.00</strong>');
                                } else {
                                    clsHanlder();
                                }
                            } else if (e.keyCode == 13) {
                                if (!proceed) {
                                    proceed = calc();
                                    if (proceed) {
                                        $('#simplayer-wrapper .simplayer-button').html('Complete');
                                    }
                                } else {
                                    if (callback) {
                                        callback();
                                    }
                                    clsHanlder();
                                }

                                return false;
                            }

                            $('#simplayer-wrapper .simplayer-button').html('Calculate');

                            proceed = false;
                        },
        calc        =   function()
                        {
                            var total   =   $('#txt-sum-val').html().toFloat(),
                                change  =   input.val().toFloat() - total;

                            if (change < 0) {
                                result.html('<strong style="color: #ed1c24;">' + Math.abs(change).toDollar() + '</strong> more please!');
                                return false;
                            } else {
                                result.html('Change: <strong style="color: #4CAF50;">' + change.toDollar() + '</strong>');
                            }

                            return true;
                        };

    result.css({'font-weight': 'lighter', 'text-align': 'center', 'margin': '14px 0 0', 'font-size': '24px'}).html('Change: <strong style="color: #4CAF50;">$0.00</strong>');

    var splayer = new simplayer('Cash payment', [input, result], buttons, null, null, false);
    splayer.show();

    var ticker = setInterval(function()
    {
        input.focus();
    }, 100);

    splayer.btnEvent(0, function() {
        calc();
    });

    $(window).keydown(kdHandler);
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
        flushQueue();
        splayer.close();
    });
}

function flushQueue()
{
    $('#to-buy tbody tr').remove();
    $('.payment-trigger').addClass('not-on-print');
    $('#receipt-barcode').html('');
    updateSum();
    resetScreen();
    creating_order = false;
}
