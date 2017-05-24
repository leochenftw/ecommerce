<nav id="main_nav">
	<ul>
        <li><a href="$Link" <% if not $Feature %> class="is-active"<% end_if %>><span class="icon"><i class="fa fa-tachometer"></i></span> Dashboard</a></li>
        <li><a href="$Link('products')" <% if $Feature == 'products' %> class="is-active"<% end_if %>><span class="icon"><i class="fa fa-cube"></i></span> Products</a></li>
        <li><a href="$Link('account')" <% if $Feature == 'account' %> class="is-active"<% end_if %>><span class="icon"><i class="fa fa-cog"></i></span> My account</a></li>
        <li><a href="$Link('sales')" <% if $Feature == 'sales' %> class="is-active"<% end_if %>><span class="icon"><i class="fa fa-usd"></i></span> Sales</a></li>
        <li><a><span class="icon"><i class="fa fa-sign-out"></i></span> Signout</a></li>
	</ul>
</nav>
