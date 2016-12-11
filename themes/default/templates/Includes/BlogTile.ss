<a class="grid <% if $GridClass %>$GridClass<% else %>grid_5<% end_if %> latest-updates__item" href="$Link">
	<time class="latest-updates__item__created" datetime="$Created.Nice">$Date <span>$Created.ShortMonth</span></time>
	<% if $GridClass == 'xgrid_3' %>
	<% with $Cover.FillMax(300, 190) %>
		<img src="$URL" width="$Width" height="$Height" alt="$Up.Up.Title 配图" />
	<% end_with %>
	<% else %>
	<% with $Cover.FillMax(380, 245) %>
		<img src="$URL" width="$Width" height="$Height" alt="$Up.Up.Title 配图" />
	<% end_with %>
	<% end_if %>
	<h3>$Title</h3>
	<p>$Abstract</p>
	<% if $GridClass == 'xgrid_3' %>
	<div class="link"><span>阅读全文</span></div>
	<% end_if %>
</a>