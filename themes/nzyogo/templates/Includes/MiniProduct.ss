<div class="product-image column is-3">
	<a href="$Product.Link">$Product.Poster.Cropped.FillMax(300, 400).SetHeight(360)</a>
</div>
<div class="product-form-wrapper column">
	<div class="miscs">
		<span class="font-inline icon"><% if $Product.inStock %><i class="fa fa-check-square-o"></i>有<% else %><i class="fa fa-window-close"></i>没<% end_if %>货</span>
		<span class="font-inline icon"><% if $Product.AcceptOrder %><i class="fa fa-check-square-o"></i>接受订单<% else %><i class="fa fa-window-close"></i>仅在抢购活动时接受订单<% end_if %></span>
	</div>
	<h3 class="title is-3 is-normal"><a href="$Product.Link"><% if $Language == 'Chinese' %><% if $Product.Chinese %>$Product.Chinese<% else %>$Product.Title<% end_if %><% else %>$Product.Title<% end_if %></a></h3>
	<div class="price">${$Product.Price}</div>
    <% with $Product.MiniOrderForm %>
        <form $FormAttributes>
            <% loop $Fields %>
                <% if $name != 'Quantity' %>$Field<% end_if %>
            <% end_loop %>
            <div class="columns align-vertical-center">
                <label for="ProductOrderForm_ProductOrderForm_Quantity" class="column is-narrow">数量</label>
                <div class="column field-quantity is-narrow">
                    $Fields.fieldByName('Quantity')
                </div>
                <div class="column icon is-paddingless-left">
                    $Actions
                </div>
            </div>
        </form>
    <% end_with %>
</div>
