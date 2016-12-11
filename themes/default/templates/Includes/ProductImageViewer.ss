<div class="product-images__stage">
	<% with $ProductPhotos.First %>
	<a href="$URL" class="as-block relative" data-lightbox="大图">
		<% with $FillMax(570, 854) %>
			<img class="relative" src="$URL" width="$Width" height="$Height" />
		<% end_with %>
	</a>
	<% end_with %>
</div>
<div class="product-images__pool owl-carousel">
	<% loop $ProductPhotos %>
		<a class="as-block<% if $First %> relative<% end_if %>" href="$URL" data-src="$FillMax(570, 854).URL">
			$FillMax(170, 256)
		</a>
	<% end_loop %>
</div>