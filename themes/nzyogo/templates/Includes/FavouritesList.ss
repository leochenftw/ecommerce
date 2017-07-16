<h2 class="title">我的收藏</h2>
<ul id="favourite-list" class="neat-ul">
<% loop $Favourites %>
	<li class="favourited-item">
		<% include MiniProduct %>
	</li>
<% end_loop %>
</ul>