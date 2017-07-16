<a class="column blog-tile is-4" href="$Link">
	<time class="latest-updates__item__created" datetime="$Created.Nice">$Date <span>$Created.ShortMonth</span></time>
	<% with $Cover.FillMax(456, 289) %>
		<div class="image-holder"><img src="$URL" width="$Width" height="$Height" alt="$Up.Up.Title 配图" /></div>
	<% end_with %>
	<h4 class="title is-6 is-bold">$Title</h4>
</a>
