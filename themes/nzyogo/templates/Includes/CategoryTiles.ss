<div id="category-tiles">
    <div class="columns is-marginless">
        <div class="column is-half is-half-screen-height is-paddingless">
            <% loop $Categories.limit(2,0) %>
                <a class="hot-spot columns is-marginless<% if $Last %> reverse<% end_if %> is-half-height" href="/products?category=$ID">
                    <div class="column<% if $Last %> order-2<% end_if %> has-background is-half" style="background-image: url($HorizPoster.FillMax(480,360).URL);"></div>
                    <div class="column<% if $Last %> order-1<% end_if %> has-text is-half">
                        <p class="subtitle is-4">$Subtitle</p>
                        <h2 class="title is-2">$Title</h2>
                        <span class="link-alike"><% if $Last %>看看去<% else %>去看看<% end_if %></span>
                    </div>
                </a>
    		<% end_loop %>
        </div>
        <% loop $Categories.limit(1,2) %>
            <a class="hot-spot is-half big-tile has-text-centered column is-half-screen-height has-background" style="background-image: url($SquarePoster.FillMax(960,720).URL);" href="/products?category=$ID">
                <h2 class="title is-2">$Title</h2>
                <p class="subtitle is-4">$Subtitle</p>
                <span class="button is-danger">来逛逛</span>
            </a>
        <% end_loop %>
    </div>
    <div class="columns is-marginless">
        <% loop $Categories.limit(1,3) %>
            <a class="hot-spot column big-tile is-half is-half-screen-height has-background has-text-centered" style="background-image: url($SquarePoster.FillMax(960,720).URL);" href="/products?category=$ID">
                <% if $ShowTitle %>
                    <h2 class="title is-2">$Title</h2>
                    <p class="subtitle is-4">$Subtitle</p>
                <% end_if %>
                <% if $ShowButton %>
                    <span class="button is-danger">来逛逛</span>
                <% end_if %>
            </a>
        <% end_loop %>
        <% loop $Categories.limit(2,4) %>
            <a class="hot-spot big-tile has-text-centered column is-3 is-half-screen-height has-background" style="background-image: url($VertiPoster.FillMax(480,720).URL);" href="/products?category=$ID">
                <% if $ShowTitle %>
                    <h2 class="title is-2">$Title</h2>
                    <p class="subtitle is-4">$Subtitle</p>
                <% end_if %>
                <% if $ShowButton %>
                    <span class="button is-danger">来逛逛</span>
                <% end_if %>
            </a>
		<% end_loop %>
    </div>
</div>
