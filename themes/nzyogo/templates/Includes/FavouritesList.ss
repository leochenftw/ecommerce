<h2 class="title">我的收藏</h2>
<ul id="favourite-list" class="neat-ul">
<% loop $Favourites %>
	<li class="favourited-item columns">
		<% include MiniProduct Language=$Top.Language %>
	</li>
<% end_loop %>
</ul>
