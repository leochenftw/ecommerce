<div class="section hero">
	<div class="container">
		<h1>$Title</h1>
		<div class="breadcrumb">$Breadcrumbs</div>
	</div>
</div>
<div id="latest-update" class="section">
	<div class="container latest-updates">
		<% loop $Blogs %>
			<% include BlogTile GridClass=xgrid_3 %>
		<% end_loop %>
	</div>
</div>