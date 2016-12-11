<a class="grid latest-updates__item" href="$Link">
	<time class="latest-updates__item__created" datetime="$Created.Nice">$Date <span>$Created.ShortMonth</span></time>
	<% with $Cover.FillMax(300, 190) %>
		<div class="image-holder"><img src="$URL" width="$Width" height="$Height" alt="$Up.Up.Title 配图" /></div>
	<% end_with %>
	<h4>$Title</h4>
</a>