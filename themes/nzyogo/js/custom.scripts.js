document.addEventListener("touchstart", function(){}, true);
var google_api	=	'AIzaSyCd77ZeqfPhsCOV2aXj81lgSgp5ajw1LWU',
    transLock	=	false;
HijackAlert();
jQuery(document).ready(function($) {
    // undefined.x();
    if ($('.parallax-window').length > 0) {
        $('.parallax-window').parallax();
    }

    var owlOpt = { loop: true };
    owlOpt.nav = true;
    owlOpt.dots = true;
    owlOpt.smartSpeed = 1000;
    owlOpt.items = 1;
    owlOpt.navText = ["<i class='fa fa-chevron-left'></i>","<i class='fa fa-chevron-right'></i>"];
    owlOpt.autoplay = true;
    owlOpt.loop = true;
    owlOpt.autoPlay = true;
    owlOpt.autoplayTimeout = 5000;
    owlOpt.autoplayHoverPause = true;
    if ($('#promotional-wrapper').length == 1) {
        $('#promotional-wrapper').owlCarousel(owlOpt);
    }

    if ($('.product-carousel').length > 0) {
        var noramOpt = {
            nav: true,
            dots: false,
            smartSpeed: 1000,
            items: 3,
            loop: true,
            autoWidth: true,
            margin: 10,
            navText: ["<i class='fa fa-chevron-left'></i>","<i class='fa fa-chevron-right'></i>"]
        };

        var prodOwl = $('.product-images__pool').owlCarousel(noramOpt);

        $('.product-carousel').owlCarousel(noramOpt);

        prodOwl.on('dragged.owl.carousel', imageClicker)
                .on('next.owl.carousel', imageClicker)
                .on('prev.owl.carousel', imageClicker);
    }

    $(window).bind('load', function(e) {
        homeTweak();
    });

    homeTweak();

    $('.tag-tile__carousel').each(function(index, element) {
        var owl = $(this).owlCarousel({ nav: false, dots: false, smartSpeed: 1000, loop: true, items: 1 });
        if (!$(this).hasClass('interval-set')) {
            $(this).addClass('interval-set');
            setInterval(function() {
                owl.trigger('next.owl.carousel');
            }, 5000 + Math.random() * 2000);
        }
    });

    $('.up-coming__countdown .start-at').each(function(index, element) {
        var datetime		=	new Date($(this).attr('datetime')),
            now				=	Date.now(),
            remaining		=	datetime.getTime() - now,
            field_day		=	$(this).parent().find('.up-coming__countdown__days .value'),
            field_hour		=	$(this).parent().find('.up-coming__countdown__hours .value'),
            field_minute	=	$(this).parent().find('.up-coming__countdown__minutes .value'),
            field_second	=	$(this).parent().find('.up-coming__countdown__seconds .value');

        if (remaining > 0) {
            setInterval(function(){
                var now			=	Date.now(),
                    remaining	=	datetime.getTime() - now;
                if (remaining > 0) {
                    var d = msToTime(remaining, true);
                    field_day.html(d.days);
                    field_hour.html(d.hours);
                    field_minute.html(d.minutes);
                    field_second.html(d.seconds);
                    delete d.days;
                    delete d.hours;
                    delete d.minutes;
                    delete d.seconds;
                    d = null;
                } else {

                }
            }, 1000);
        }

    });


    $('.group-on .end-at').each(function(index, element) {
        var datetime	=	new Date($(this).attr('datetime')),
            lcThis		=	$(this),
            now			=	Date.now(),
            remaining	=	datetime.getTime() - now;
        /*trace(datetime);
        trace(datetime.getTime());
        trace(now);
        trace(datetime.getTime() - now);*/

        if (remaining > 0) {
            lcThis.html(msToTime(remaining));
            setInterval(function(){
                var now			=	Date.now(),
                    remaining	=	datetime.getTime() - now;
                if (remaining > 0) {
                    lcThis.html(msToTime(remaining));
                } else {
                    $(this).parent().html('<活动已结束>');
                    $(this).parent().parent().parent().find('.group-on__order-form').remove();
                }
            }, 1000);
        } else {
            $(this).parent().html('<活动已结束>');
            $(this).parent().parent().parent().find('.group-on__order-form').remove();
        }
    });
    $('.group-on__order-form').submit(function(e) {
        e.preventDefault();
        var lcThis		=	$(this),
            lcQuantity	=	$.trim($(this).find('input[name="quantity"]').val()),
            lcProdID	=	$(this).find('input[name="product-id"]').val(),
            lcGroupon	=	$(this).find('input[name="groupon-id"]').val();
        $.post(
            $(this).attr('action'),
            {
                quantity: lcQuantity,
                'product-id': lcProdID,
                'groupon-id': lcGroupon
            },
            function(response) {
                var data = JSON.parse(response);
                if (Boolean(data.success)) {
                    lcThis.parent().find('.groupon-on__mini-cart').html(purchaseCallback(data));
                } else {
                    alert(data.message, '出错啦');
                }
            }
        );
    });

    lnkPayment();

    $('#CartForm_CartForm_Freight input[name="Freight"]').change(function(e) {
        var id = $(this).val();
        calcFreight(id);
    });

    $('input.txt-quantity').change(function(e) {
        var lcThis = $(this),
            n = $(this).val().toFloat(),
            id = $(this).data('item-id'),
            subtotalContainer = $(this).parent().parent().find('.prod-subtotal');
        if (n > 0) {
            $.ajax({
                url: '/api/v/1/order-items/' + id + '?quantity=' + n,
                type: 'PUT',
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.success) {
                        var data = response.data,
                            id = $('#CartForm_CartForm_Freight input[name="Freight"]:checked').val();
                        subtotalContainer.html('$' + response.subtotal);
                        $('#CartForm_CartForm_Weight').html( data.weight + ' KG' );
                        $('#subtotal').html( data.total.toDollar() );
                        calcFreight(id);
                    } else {
                        alert(response.message, ':/');
                        lcThis.val(response.quantity);
                    }
                },
                error: function(response) {

                }
            });
        } else {
            alert('若要删除此条抢购, 请使用<span class="inline-mini-button">不要了</span>按钮', '出错啦');
        }
    });

    $('.btn-remove-order-item').click(function(e) {
        e.preventDefault();
        var id = $(this).data('item-id'),
            buttons = [
                {
                    Label: '再想想'
                },
                {
                    Label: '不要了'
                }
            ],
            tr = $(this).parent().parent(),
            lcThis = $(this);

        $(this).prop('disabled', true);

        var splayer = new simplayer('不要了?', '如果您确定要放弃这条抢购, 请点击<span class="inline-mini-button">不要了</span>以进行确认操作.', buttons, null, null, false);
        splayer.show();
        splayer.btnEvent(0, function() {
            lcThis.prop('disabled', false);
            splayer.close();
        })
        splayer.btnEvent(1, function() {
            $.ajax({
                url: '/api/v/1/order-items/' + id,
                type: 'DELETE',
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    tr.remove();
                },
                error: function(response) {

                }
            });
            splayer.close();
        });
    });

    $('#CartForm_CartForm_DeliveryAddress').change(function(e) {
        var addr_id = $(this).find('option:selected').val();

        if (!addr_id) {
            $('#CartForm_CartForm_NewDeliveryAddress_Holder').removeClass('hide');
            $('#CartForm_CartForm_NewDeliveryAddress').attr('aria-required', true).attr('required','required');
            $('#CartForm_CartForm_Surname, #CartForm_CartForm_FirstName, #CartForm_CartForm_Email, #CartForm_CartForm_Phone').val('');
        } else if(addr_id && addr_id.length > 0) {
            $('#CartForm_CartForm_NewDeliveryAddress_Holder').addClass('hide');
            $('#CartForm_CartForm_NewDeliveryAddress').removeAttr('aria-required').removeAttr('required');
            $.get('/api/v/1/addresses/' + addr_id, function(data) {
                if (data) {
                    $('#CartForm_CartForm_Surname').val(data.surname);
                    $('#CartForm_CartForm_FirstName').val(data.firstname);
                    $('#CartForm_CartForm_Email').val(data.email);
                    $('#CartForm_CartForm_Phone').val(data.phone);
                }
            });
        }
    });

    if ($('#CartForm_CartForm_NewDeliveryAddress').length > 0) {
        var addressCompleter = new autoAddress(google_api, function() {
            addressCompleter.gplacised('CartForm_CartForm_NewDeliveryAddress');
        });
    }
    imageClicker();

    if ($('body').hasClass('page-type-contact-page') && $('#map').length > 0) {
        var map = new gmap(google_api, 'map', [{lat: -41.206463, lng: 174.908977}]);
    }


    if ($('body').hasClass('page-dashboardcontroller')) {
        initAjaxRoute('.member-area__content');
        $('.member-area__sidebar a.ajax-routed').each(function(index, element) {
            var self = $(this);
            $(this).ajaxRoute({ container: $('.member-area__content') }, null, {
                onStart: function() {
                    $('.member-area__sidebar a').removeClass('active');
                    TweenMax.to($('.member-area__content'), 0.25, {opacity: 0});
                    self.addClass('active');
                    NProgress.start();
                },

                onEnd: function() {
                    TweenMax.to($('.member-area__content'), 0.25, {opacity: 1});
                    NProgress.done();
                    standardMemberCallback();
                    switch (location.pathname.replace('/member/action/', '')) {
                        case 'address':
                            initAddressCallback();
                            break;
                    }
                }
            });
        });
        standardMemberCallback();
        initAddressCallback();
    }

    window.popstateTransition.onEnd = function() {
        $('.member-area__sidebar a').removeClass('active');
        $('.member-area__sidebar a[href="' + location.pathname + '"]').addClass('active');
    };

    $('#SubscribeForm_SubscribeForm').ajaxSubmit({
        validator: function() {
            if ($.trim($('#SubscribeForm_SubscribeForm_Email').val()) == 0) {
                $('#SubscribeForm_SubscribeForm_Email_Holder').addClass('require-email');
                return false;
            }

            return true;
        },
        onstart: function() {
            $('#SubscribeForm_SubscribeForm_Email_Holder').removeClass('require-email');
            $('#SubscribeForm_SubscribeForm_action_doSubscribe').html('<span class="icon-loading animate-spin"></span>');
        },
        done: function(data) {
            if (data.success) {
                $('#SubscribeForm_SubscribeForm_action_doSubscribe').html('已关注');
                $('#SubscribeForm_SubscribeForm_action_doSubscribe').prop('disabled', true);
            } else {
                $('#SubscribeForm_SubscribeForm_Email_Holder').addClass('require-email');
                $('#SubscribeForm_SubscribeForm_action_doSubscribe').html('先关注');
                alert(data.message);
            }
        }
    });
});

function initAddressCallback() {
    $('input[name="isDefault"]').click(function(e) {
        var me = $(this);
        if (!$(this).prop('checked')) {
            e.preventDefault();
            e.stopPropagation();
            return false;
        } else {
            if (confirm('您确定要执行首选地址替换操作?')) {
                $('input[name="isDefault"]').not(me).prop('checked', false);
                $.post(
                    'api/v/1/addresses/' + $(this).data('addr-id'),
                    {
                        'isDefault': true
                    },
                    function(data) {
                        if (data) {
                            alert('<p>您已成功更换首选收货地址. 购物车将默认使用您新的首选地址.</p>', '首选地址已更换');
                        }
                    }
                );
            } else {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
        }
    });
}

function standardMemberCallback() {
    $('.member-area__content a.ajax-routed').each(function(index, element) {
        var self = $(this);
        $(this).ajaxRoute({ container: $('.member-area__content') }, null, {
            onStart: function() {
                $('.member-area__sidebar a').removeClass('active');
                self.addClass('active');
                TweenMax.to($('.member-area__content'), 0.25, {opacity: 0});
                NProgress.start();
            },

            onEnd: function() {
                TweenMax.to($('.member-area__content'), 0.25, {opacity: 1});
                NProgress.done();
            }
        });
    });

    $('.mini-watch-form').each(function(i, el) {
        var form = $(this);
        $(this).ajaxSubmit({
            success: function(data) {
                trace(data);
            }
        });

        $(this).find('input.checkbox').change(function(e){
            form.submit();
        });
    });
}

function imageClicker(e) {

    $('.product-images .product-images__pool a').unbind('click').click(function(e) {
        e.preventDefault();

        if ($(this).hasClass('active')) return false;
        $('.product-images .product-images__pool a.active').removeClass('active');
        $(this).addClass('active');

        if (transLock) return false;
        transLock = true;

        $('.product-images__stage a').attr('href', $(this).attr('href'));
        var relative	=	$('.product-images__stage a img.relative'),
            absolute	=	relative.clone();

        absolute.removeClass('relative').addClass('absolute').attr('src', $(this).data('src')).appendTo(relative.parent());
        TweenMax.to(relative, 0.25, {opacity: 0, onComplete: function() {
            relative.remove();
            absolute.removeClass('absolute').addClass('relative');
            transLock = false;
        }});
    });
}

function homeTweak() {
    $(window).resize(function(e) {
        if ($('section.group-on').length > 0) {
            $('section.group-on').css('min-height', $(window).height() * 0.6 - $('#header').outerHeight());
        }
    }).resize().resize();
}

function calcFreight(id) {
    $.get('/api/v/1/freights/' + id, function(data) {
        if (data) {
            $('#CartForm_CartForm_FreightCost').html(data.freight_total.toDollar());
            $('#CartForm_CartForm_Total').html(data.total.toDollar());
        }
    });
}

function lnkPayment() {

    var content, clipboard;

    $('#PaymentForm_PaymentForm input[name="PaymentMethod"]').change(function(e) {
        if (clipboard) {
            clipboard.destroy();
            clipboard = null;
        }

         var id = $(this).val();
         $('#PaymentForm_PaymentForm_action_ProcessPayment').prop('disabled', true);
         $.get('/api/v/1/payment-methods/' + id, { prerender: true }, function(data) {
            if (content) {
                content.remove();
                content = null;
            }
            content = $(data);
            content.appendTo('#payment-details');
            if (content.find('.btn-copy').length > 0) {
                clipboard = activateCopy();
            }

            if (content.find('.disable-button').length > 0) {
                if (content.find('.disable-button').val() == 1) {
                    $('#PaymentForm_PaymentForm_action_ProcessPayment').prop('disabled', true);
                } else {
                    $('#PaymentForm_PaymentForm_action_ProcessPayment').prop('disabled', false);
                }
            } else {
                $('#PaymentForm_PaymentForm_action_ProcessPayment').prop('disabled', false);
            }

        });
    });

}

function purchaseCallback(data) {
    var unpaid = data.num_unpaid,
        paid = data.num_paid,
        title = data.title,
        measurement = data.measurement;

    if (unpaid == 0 && paid == 0) {
        return '<p>您还什么都没买呢.</p>';
    } else {
        if (unpaid > 0 && paid == 0) {
            return '<p>您已经抢购了<span class="num-unpaid">' + unpaid + '</span>'+measurement+', 但是您还没有付款. 为确保此次抢购生效, 请您尽快<a class="lnk-pay" href="/cart">支付</a>.</p>';
        } else {
            if (unpaid == 0) {
                return '<p>您已经成功抢购了<span class="num-paid">'+paid+'</span>' + measurement + title + '</p>';
            } else {
                return '<p>您已经成功抢购了<span class="num-paid">'+paid+'</span>' + measurement + title + ', 另外还有<span class="num-unpaid">'+unpaid+'</span>' + measurement + '尚未付款. 为确保此次抢购生效, 请您尽快<a class="lnk-pay" href="/cart">支付</a>.</p>';
            }
        }
    }

    return '<p>成功抢购: ' + paid + '; 尚未付款: ' + unpaid + '</p>';
}

function activateCopy() {
    $('.btn-copy').click(function(e) {
        e.preventDefault();
    });
    var clipboard = new Clipboard('.btn-copy');
    clipboard.on('success', function(e) {
        /*console.info('Action:', e.action);
        console.info('Text:', e.text);
        console.info('Trigger:', e.trigger);
        e.clearSelection();
        var splayer = new simplayer('复制完毕');
        splayer.show();*/
        $(e.trigger).html('完毕');
        $(e.trigger).addClass('copied');
        e.clearSelection();
    });

    clipboard.on('error', function(e) {
        console.error('Action:', e.action);
        console.error('Trigger:', e.trigger);
    });

    return clipboard;
}

function msToTime(duration, as_data) {
    var milliseconds = parseInt((duration%1000)/100)
        , seconds = parseInt((duration/1000)%60)
        , minutes = parseInt((duration/(1000*60))%60)
        , hours = parseInt((duration/(1000*60*60)))
        , days = Math.floor((duration/(1000*60*60*24)));

    hours = hours.DoubleDigit();
    minutes = minutes.DoubleDigit();
    seconds = seconds.DoubleDigit();
    days = days.DoubleDigit();

    if (as_data && as_data === true) {
        return {
            days: days,
            hours: (hours % 24).DoubleDigit(),
            minutes: minutes,
            seconds: seconds
        };
    }

    return hours + ":" + minutes + ":" + seconds;
}
