<a class="tag-tile grid_3 as-block" title="$Title" href="/products?tag=$ID">
	<div class="tag-tile__caro-count-wrapper">
		<div class="tag-tile__carousel owl-carousel">
			<% loop $Products %>
			<% with $getSquared.FillMax(220, 220) %>
			<div class="tag-tile__carousel-item">
				<img src="$URL" width="$Width" height="$Height" class="tag-tile__carousel-item__image" />
			</div>
			<% end_with %>
			<% end_loop %>
		</div>
		<div class="tag-tile__count">{$Products.Count}<span class="mini">件</span><span class="own-line">$Title</span></div>
	</div>
	<%-- <div class="tag-tile__texts text-center">$Title <span>{$Products.Count}件</span></div> --%>
</a>