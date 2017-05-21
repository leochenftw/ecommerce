<div id="product-tiles">
	<div class="row">
		<div class="col col-left">
		<% loop $Categories.limit(2,0) %>
			<% if $Last %>
				<% include CategoryTile reverse=1,class=half %>
			<% else %>
				<% include CategoryTile class=half %>
			<% end_if %>
		<% end_loop %>
		</div>
		<div class="col col-right big-tile">
		<% loop $Categories.limit(1,2) %>
			<% include CategoryTile bigTile=1 %>
		<% end_loop %>
		</div>
	</div>
	<div class="row">
		<div class="col col-left big-tile">
		<% loop $Categories.limit(1,3) %>
			<% include CategoryTile bigTile=1 %>
		<% end_loop %>
		</div>
		<div class="col col-right as-flex">
		<% loop $Categories.limit(2,4) %>
			<% if $First %>
				<% include CategoryTile vertical=1 %>
			<% else %>
				<% include CategoryTile vertical=1 %>
			<% end_if %>
		<% end_loop %>
		</div>
	</div>
</div>
