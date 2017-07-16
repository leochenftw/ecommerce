<%-- <% if Translations %>
<ul class="translations children">
<% loop Translations.Sort('Locale', 'ASC') %>
    <li class="$Locale.RFC1766">
        <a<% if $Locale.RFC1766 == $Top.ContentLocale %> class="current"<% end_if %> href="$Link" hreflang="$Locale.RFC1766" title="$Title"><% if $Locale.NativeName == 'English (NZ)' %>English<% else %>$Locale.NativeName<% end_if %></a>
    </li>
<% end_loop %>
</ul>
<% end_if %> --%>

<nav id="main_nav" class="clearfix">
	<ul class="site-tree">
		<% loop Menu(1) %>
			<li>
				<a href="$Link" class="<% if LinkOrCurrent = current || $LinkOrSection = section %>current<% end_if %>">$MenuTitle.XML</a>
				<% if Children %>
				<ul class="children">
					<% loop Children %>
						<li><a href="$Link" class="<% if LinkOrCurrent = current %>current<% end_if %>">$MenuTitle.XML</a></li>
					<% end_loop %>
				</ul>
				<% end_if %>
			</li>
		<% end_loop %>
	</ul>
	<ul class="site-feature">
		  <li><a class="icon-cart<% if $Link == 'cart' %> current<% end_if %>" href="/cart"><span>购物车</span></a></li>
		<li><a class="icon-member<% if $Link == '/DashboardController/' %> current<% end_if %>" href="/member"><span>会员中心</span></a></li>
	</ul>
</nav>
