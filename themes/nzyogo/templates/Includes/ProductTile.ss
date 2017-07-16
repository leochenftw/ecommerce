<a class="column<% if $inList %> is-3<% end_if %> product-tile has-text-centered" href="$Link">
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
	<div class="product-tile__title">$Chinese</div>
	<div class="product-tile__price">${$Price}</div>
</a>
