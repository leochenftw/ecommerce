(function($) {
	$.fn.OperatorWork = function() {
        $(this).each(function(i, el)
        {
            $(this).dblclick(function(e)
            {
                var operator = $(this);
                operator.addClass('hide');
                if ($('.form-operator').length == 0) {

                    var operatorEditor = new FormOperator(operator.data('id'), operator.data('first-name'), operator.data('surname'), operator.data('email'), function()
                    {
                        operator.removeClass('hide');
                    });
                    operatorEditor.insertAfter($(this));
                }
            });
        });
        return $(this);
	};
 })(jQuery);
