<nav class="nav">
    <div class="nav-left">
        <div class="nav-item"><a href="/" id="logo" rel="start">$SiteConfig.Title.RAW</a></div>
    </div>
    <span class="nav-toggle">
        <span></span>
        <span></span>
        <span></span>
    </span>

    <div class="nav-right nav-menu">
        <% loop Menu(1) %>
		<a href="$Link" class="nav-item <% if LinkOrCurrent = current || $LinkOrSection = section %>is-active<% end_if %>">$MenuTitle.XML</a>
		<% end_loop %>
        <a class="nav-item icon<% if $Link == 'cart' %> is-active<% end_if %>" href="/cart"><i class="fa fa-shopping-cart"></i><span class="hide">购物车</span></a>
        <a class="nav-item icon<% if $Link == '/DashboardController/' %> is-active<% end_if %>" href="/member"><i class="fa fa-user"></i><span class="hide">会员中心</span></a>
    </div>
</nav>
