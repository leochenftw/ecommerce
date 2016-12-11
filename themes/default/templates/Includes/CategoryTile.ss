<% if $vertical || $bigTile %>
<div class="category-tile<% if $reverse %> reversed<% end_if %><% if $vertical %> vertical<% end_if %>"<% if $bigTile %> style="background-image: url($SquarePoster.FillMax(960,720).URL);"<% end_if %><% if $vertical %> style="background-image: url($VertiPoster.FillMax(480,720).URL);"<% end_if %>>
<% else %>
<a class="category-tile<% if $reverse %> reversed<% end_if %>" href="/products?category=$ID">
<% end_if %>

	<% if $bigTile || $vertical %>
		<div class="category-tile__text">
			<h2 class="category-tile__text__title">$Title</h2>
			<div class="category-tile__text__contents">
				<div class="category-tile__text__subtitle">$Subtitle</div>
				<% if $Intro %><div class="category-tile__text__content">$Intro</div><% end_if %>
			</div>
		</div>
		<a href="#" class="call-to-action red horizontal-center">看看去</a>
	<% else %>
		<% if $reverse %>
			<div class="category-tile__text">
				<div class="category-tile__text__subtitle">$Subtitle</div>
				<div class="category-tile__text__contents">
					<div class="category-tile__text__content">
						<h2 class="category-tile__text__title">$Title</h2>
						<span class="link-alike">去看看</span>
					</div>
				</div>
			</div>
			<div class="category-tile__carousel">
				<div class="category-tile__carousel_item">
					<% if $vertical %>
						$VertiPoster.FillMax(480,720)
					<% else %>
						$HorizPoster.FillMax(480,360)
					<% end_if %>
				</div>
			</div>
		<% else %>
			<div class="category-tile__carousel">
				<div class="category-tile__carousel_item">
					<% if $vertical %>
						$VertiPoster.FillMax(480,720)
					<% else %>
						$HorizPoster.FillMax(480,360)
					<% end_if %>
				</div>
			</div>
			<div class="category-tile__text">
				<div class="category-tile__text__subtitle">$Subtitle</div>
				<div class="category-tile__text__contents">
					<div class="category-tile__text__content">
						<h2 class="category-tile__text__title">$Title</h2>
						<span class="link-alike">去看看</span>
					</div>
				</div>
			</div>
		<% end_if %>
	<% end_if %>
<% if $vertical || $bigTile %>
</div>
<% else %>
</a>
<% end_if %>