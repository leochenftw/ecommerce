(function($){
	$.entwine('ss', function($) {
		$('#Form_EditForm_Barcode').entwine({
			onmatch: function(e) {
				$('#Form_EditForm_Barcode, #Form_ItemEditForm_Barcode').unbind('keydown').keydown(function(e)
                {
                    if (e.keyCode == 13) {
                        e.preventDefault();
                        e.stopPropagation();
                        e.stopImmediatePropagation();
                        return false;
                    }
                });
			}
		});
	});

}(jQuery));
