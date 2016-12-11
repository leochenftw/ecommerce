<div class="section hero">
	<div class="container">
		<h1>$Title</h1>
		<div class="breadcrumb">$Breadcrumbs</div>
	</div>
</div>

<div class="section">
	<div class="container">
		$Form
		<% if $Plugables %>
		<% loop $Plugables %>
			<% include PlugableContent %>
		<% end_loop %>
		<% else %>
		$Content
		<% end_if %>
	</div>
</div>