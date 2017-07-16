<div id="category-tiles">
    <div class="columns is-marginless">
        <div class="column is-half-screen-height is-paddingless">
            <% loop $Categories.limit(2,0) %>
                <a class="columns is-marginless<% if $Last %> reverse<% end_if %> is-half-height" href="/products?category=$ID">
                    <div class="column<% if $Last %> order-2<% end_if %> has-background" style="background-image: url($HorizPoster.FillMax(480,360).URL);"></div>
                    <div class="column<% if $Last %> order-1<% end_if %>">
                        <p class="subtitle is-4">$Subtitle</p>
                        <h2 class="title is-2">$Title</h2>
                        <% if $Intro %><p class="category-tile__text__content">$Intro</p><% end_if %>
                    </div>
                </a>
    		<% end_loop %>
        </div>
        <% loop $Categories.limit(1,2) %>
            <a class="column is-half-screen-height is-paddingless has-background" style="background-image: url($SquarePoster.FillMax(960,720).URL);" href="/products?category=$ID">
                <h2 class="title is-2">$Title</h2>
                <p class="subtitle is-4">$Subtitle</p>
                <% if $Intro %><p class="category-tile__text__content">$Intro</p><% end_if %>
            </a>
        <% end_loop %>
    </div>
    <div class="columns is-marginless">
        <% loop $Categories.limit(1,3) %>
            <a class="column is-half is-half-screen-height is-paddingless has-background" style="background-image: url($SquarePoster.FillMax(960,720).URL);" href="/products?category=$ID">
                <h2 class="title is-2">$Title</h2>
                <p class="subtitle is-4">$Subtitle</p>
                <% if $Intro %><p class="category-tile__text__content">$Intro</p><% end_if %>
            </a>
        <% end_loop %>
        <% loop $Categories.limit(2,4) %>
            <a class="column is-3 is-half-screen-height is-paddingless has-background" style="background-image: url($VertiPoster.FillMax(480,720).URL);" href="/products?category=$ID">
                <h2 class="title is-2">$Title</h2>
                <p class="subtitle is-4">$Subtitle</p>
                <% if $Intro %><p class="category-tile__text__content">$Intro</p><% end_if %>
            </a>
		<% end_loop %>
    </div>
</div>
