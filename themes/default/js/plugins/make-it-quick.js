(function($) {
	$.fn.makeitQuick = function(cbf) {

        $(this).each(function(i, el)
        {
            var me              =   $(this),
                endpoint        =   me.attr('href'),
                method          =   me.data('method'),
                csrf            =   me.data('csrf');

            me.click(function(e)
            {
                e.preventDefault();
                me.addClass('is-loading');
                $[method](endpoint, {csrf: csrf}, function(data)
                {
                    if (data.class) {
                        me.find('.fa').removeAttr('class').addClass('fa fa-' + data.class);
                    }

                    if (data.toggle_class) {
                        me.toggleClass(data.toggle_class);
                    }

                    if (data.method) {
                        me.data('method', data.method);
                    }

                    if (cbf) {
                        cbf();
                    }

                    me.removeClass('is-loading');
                });
            });
        });

        return $(this);
    };
})(jQuery);
