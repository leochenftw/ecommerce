var FormProduct             =   function(target)
{
    var id                  =   target ? target.data('id') : null;

    this.template           =   '<form class="form-product" method="POST" action="/api/v/1/cloud-products/' + (id == null ? '' : id) + '" enctype="multipart/form-data">\
                                    <div class="columns">\
                                        <div class="column is-4">Product images</div>\
                                        <div class="column is-4">\
                                            <div class="previewable-uploader columns">\
                                                <div class="previewable-uploader__previewable column">\
                                                    <div class="previewable-uploader__previewable__thumbnail is-relative">\
                                                        <div class="thumbnail-core show image-as-block"></div>\
                                                        <button class="btn-remove-thumbnail solid icon-close" data-id="210">remove</button>\
                                                    </div>\
                                                </div>\
                                                <div class="previewable-uploader__uploadable column">\
                                                    <input accept="image/*" name="Gallery[Uploads][]" class="upload viewable-gallery" type="file">\
                                                </div>\
                                            </div>\
                                        </div>\
                                    </div>\
                                    <div class="columns">\
                                        <div class="column is-4">Barcode</div>\
                                        <div class="column is-4">\
                                            <input id="product-barcode" class="text" type="text" name="barcode" />\
                                        </div>\
                                    </div>\
                                    <div class="columns">\
                                        <div class="column is-4">Product</div>\
                                        <div class="column is-4">\
                                            <div class="columns"><div class="column is-12"><input id="product-name" class="text" type="text" name="title" placeholder="English" required /></div></div>\
                                            <div class="columns"><div class="column is-12"><input id="product-cn-name" class="text" type="text" name="chinese_title" placeholder="Chinese" /></div></div>\
                                        </div>\
                                    </div>\
                                    <div class="columns">\
                                        <div class="column is-4">Stock count</div>\
                                        <div class="column is-4">\
                                            <div class="columns">\
                                                <div class="column"><input id="product-stock-count" class="text" type="text" name="stock_count" /></div>\
                                                <div class="column is-3"><input id="product-measurement" class="text" type="text" name="measurement" placeholder="Unit" required /></div>\
                                            </div>\
                                        </div>\
                                    </div>\
                                    <div class="columns">\
                                        <div class="column is-4">Pricing</div>\
                                        <div class="column is-4">\
                                            <div class="columns">\
                                                <div class="column"><input class="text" type="text" name="cost" placeholder="Cost" /></div>\
                                                <div class="column"><input class="text" type="text" name="price" placeholder="Price" /></div>\
                                            </div>\
                                        </div>\
                                    </div>\
                                    <div class="columns">\
                                        <div class="column is-4">Dimensions</div>\
                                        <div class="column is-4">\
                                            <div class="columns">\
                                                <div class="column"><input class="text" type="text" name="width" placeholder="Width" /></div>\
                                                <div class="column"><input class="text" type="text" name="height" placeholder="Height" /></div>\
                                                <div class="column"><input class="text" type="text" name="depth" placeholder="Depth" /></div>\
                                            </div>\
                                        </div>\
                                    </div>\
                                    <div class="columns">\
                                        <div class="column is-4">Weight</div>\
                                        <div class="column is-4">\
                                            <div class="columns vertical-center">\
                                                <div class="column"><input id="product-weight" class="text" type="text" name="weight" /></div>\
                                                <div class="column is-auto-width">KG</div>\
                                            </div>\
                                        </div>\
                                    </div>\
                                    <div class="columns">\
                                        <div class="column is-4">Manufacturer</div>\
                                        <div class="column is-4">\
                                            <div class="columns vertical-center">\
                                                <div class="column"><input id="product-manufacturer" class="text" type="text" name="manufacturer" /></div>\
                                            </div>\
                                        </div>\
                                    </div>\
                                    <div class="columns">\
                                        <div class="column is-4 is-offset-4">\
                                            <button class="button create action is-info" type="submit">' + ((id == null) ? 'Create' : 'Update') + '</button>\
                                            ' + ((id != null) ? '<button class="button btn-delete is-danger">Delete</button>' : '') + '\
                                            <button class="button cancel action is-warning">Cancel</button>\
                                        </div>\
                                    </div>\
                                </form>';
    this.html               =   $(this.template);
    var me                  =   this.html;
    this.html.find('button.cancel').click(function(e)
    {
        e.preventDefault();
        $('.product-rows').removeClass('editing');
        me.remove();
    });


    this.html.ajaxSubmit(
    {
        onstart: function()
        {
            me.find('.button:not(".create")').addClass('hide');
            me.find('.button.create').addClass('is-loading');
        },
        done: function(data)
        {
            console.log(data);
            if (!target) {
                target = $('<div class="columns product-row is-marginless">\
                                <div class="column product-row__thumbnail is-1"></div>\
                                <div class="column product-row__title"><span class="english"></span><br /><span class="chinese subtitle is-6"></span></div>\
                                <div class="column product-row__stock-count is-1 has-text-centered"></div>\
                                <div class="column product-row__cost has-text-centered is-2"></div>\
                                <div class="column product-row__price has-text-centered is-2"></div>\
                                <div class="column product-row__last-update is-2"></div>\
                            </div>');
                $('.product-list').prepend(target);
                target.data('id', data.id);
            }

            target.data('title', data.title);
            target.data('chinese', data.chinese_title);
            target.data('cost', data.cost.toDollar());
            target.data('price', data.price.toDollar());
            target.data('stock-count', data.stock_count);
            target.data('barcode', data.barcode);
            target.data('width', data.width);
            target.data('height', data.height);
            target.data('depth', data.depth);
            target.data('measurement', data.measurement);
            target.data('weight', data.weight);
            target.data('manufacturer', data.manufacturer);

            target.find('.product-row__title .english').html(data.title);
            target.find('.product-row__title .chinese').html(data.chinese_title);
            target.find('.product-row__cost').html(data.cost.toDollar());
            target.find('.product-row__price').html(data.price.toDollar());
            target.find('.product-row__stock-count').html(data.stock_count);
            target.find('.product-row__last-update').html(data.last_update);

            target.addClass('updated');
            setTimeout(function()
            {
                $('.product-list').scrollTo(target, 500, {axis: 'y'});
                target.removeClass('updated');
            }, 100);

            me.find('button.cancel').click();
        }
    });

    return this.html;
};



(function($)
{
    $.fn.editProduct = function()
    {
        var self            =   $(this),
            id              =   $(this).data('id'),
            title           =   $(this).data('title'),
            chinese         =   $(this).data('chinese'),
            cost            =   $(this).data('cost'),
            price           =   $(this).data('price'),
            stock           =   $(this).data('stock-count'),
            barcode         =   $(this).data('barcode'),
            width           =   $(this).data('width'),
            height          =   $(this).data('height'),
            depth           =   $(this).data('depth'),
            measurement     =   $(this).data('measurement'),
            thumbnail       =   $(this).data('thumbnail'),
            weight          =   $(this).data('weight'),
            manufacturer    =   $(this).data('manufacturer'),
            form            =   new FormProduct(self);

        form.find('#product-barcode').val(barcode);
        form.find('#product-name').val(title);
        form.find('#product-cn-name').val(chinese);
        form.find('#product-stock-count').val(stock);
        form.find('#product-measurement').val(measurement);
        form.find('input[name="cost"]').val(cost);
        form.find('input[name="price"]').val(price);
        form.find('input[name="width"]').val(width);
        form.find('input[name="height"]').val(height);
        form.find('input[name="depth"]').val(depth);
        form.find('#product-weight').val(weight);
        form.find('#product-manufacturer').val(manufacturer);


        $('.product-rows').addClass('editing');
        $('.product-list').prepend(form);
        $('.product-list').scrollTo(form, 500, {axis: 'y'});
    };
})(jQuery);
