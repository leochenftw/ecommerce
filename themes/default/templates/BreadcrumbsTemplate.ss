<% if $Pages %>
	<a href="/">首页</a><i>/</i><% loop $Pages %><% if $Last %>$MenuTitle.XML<% else %><% if not Up.Unlinked %><a href="$Link" class="breadcrumb-$Pos"><% end_if %>$MenuTitle.XML<% if not Up.Unlinked %></a><% end_if %><i>/</i><% end_if %><% end_loop %>
<% end_if %>
