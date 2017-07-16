<a class="column blog-tile <% if $inList %>is-3<% else %>is-4<% end_if %>" href="$Link">
	<time class="latest-updates__item__created" datetime="$Created.Nice">$Date <span>$Created.ShortMonth</span></time>
	<% with $Cover.FillMax(456, 289) %>
		<div class="image-holder"><img src="$URL" width="$Width" height="$Height" alt="$Up.Up.Title 配图" /></div>
	<% end_with %>
	<h3 class="title is-6 is-bold">$Title</h3>
	<p>$Abstract</p>
	<% if $inList %>
	<div class="link">阅读全文 <span class="icon"><i class="fa fa-angle-right"></i></span></div>
	<% end_if %>
</a>
