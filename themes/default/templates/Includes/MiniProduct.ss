<div class="product-image">
	<a href="$Product.Link">$Product.Poster.Cropped.FillMax(300, 400).SetHeight(360)</a>
</div>
<div class="product-form-wrapper">
	<div class="miscs">
		<span class="<% if $Product.inStock %>icon-check<% else %>icon-x<% end_if %> font-inline"><% if $Product.inStock %>有<% else %>没<% end_if %>货</span>
		<span class="<% if $Product.AcceptOrder %>icon-check<% else %>icon-x<% end_if %> font-inline"><% if $Product.AcceptOrder %>接受订单<% else %>仅在抢购活动时接受订单<% end_if %></span>
	</div>
	<h3 class="title"><a href="$Product.Link">$Product.Title</a></h3>
	<div class="price">${$Product.Pricings.First.Price}</div>
	<div class="content">$Product.Content</div>
	$Product.MiniOrderForm
</div>