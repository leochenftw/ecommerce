<% include PageHero %>
<div class="section content">
	<div class="container">
		$Form
		<% if $Plugables %>
		<% loop $Plugables %>
			<% include PlugableContent EvenOdd=$EvenOdd %>
		<% end_loop %>
		<% else %>
		$Content
		<% end_if %>
	</div>
</div>
