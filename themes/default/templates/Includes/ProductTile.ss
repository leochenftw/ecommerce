<a class="<% if not $inList %>grid_3 <% end_if %>product-tile text-center" href="$Link">
	<div class="product-tile__poster">
		<% if not $inList %>
		$Poster.FillMax(240, 360).SetHeight(360)
		<% else %>
		$Poster.FillMax(270, 380).SetHeight(380)
		<% end_if %>
	</div>
	<% if not $inList %>
	<div class="product-tile__brand">$Category.Title</div>
	<% end_if %>
	<div class="product-tile__title">$Title</div>
	<div class="product-tile__price">${$Pricings.First.Price}</div>
</a>