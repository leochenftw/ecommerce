<div class="<% if $isOwlItem %>owl-item-core<% else %>column<% end_if %><% if $inList %> is-3<% end_if %> product-tile has-text-centered">
    <div class="wired is-relative">
        <a class="is-block" href="$Link">
            <div class="product-tile__poster">
                <% if not $inList %>
                <% with $Poster.FillMax(224, 336).SetHeight(336) %>
                <img class="owl-lazy" data-src="$URL" />
                <% end_with %>
                <% else %>
                $Poster.FillMax(270, 380).SetHeight(380)
                <% end_if %>
            </div>
            <% if not $inList %>
            <div class="product-tile__brand">$Category.Title</div>
            <% end_if %>
            <div class="product-tile__title"><% if $Language == 'Chinese' %>$Chinese<% else %>$Title<% end_if %></div>
            <div class="product-tile__price">${$Price}</div>
        </a>
        <div class="product-tile__quick-actions is-absolute">
            <a href="/api/v/1/quick/$ID/cart" data-method="post" class="btn-quick button is-block" data-csrf="$SecurityID">
                <span class="icon">
                    <i class="fa fa-shopping-cart"></i>
                </span>
            </a>
            <a href="/api/v/1/quick/$ID/like" data-method="post" class="btn-quick button is-block<% if $isFav %> is-active<% end_if %>" data-csrf="$SecurityID">
                <span class="icon">
                    <i class="fa fa-heart"></i>
                </span>
            </a>
            <a href="/api/v/1/quick/$ID/compare" data-method="post" class="btn-quick button is-block" data-csrf="$SecurityID">
                <span class="icon">
                    <i class="fa fa-exchange"></i>
                </span>
            </a>
            <a href="/api/v/1/quick/$ID/inspect" data-method="get" class="btn-quick button is-block" data-csrf="$SecurityID">
                <span class="icon">
                    <i class="fa fa-search"></i>
                </span>
            </a>
        </div>
    </div>
</div>
