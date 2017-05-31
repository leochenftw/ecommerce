<section class="section transparent products">
    <header class="columns vertical-center is-marginless">
        <div class="column is-6"><h2 class="title is-2">Products</h2></div>
        <div class="product-search column is-relative">
            <input id="search-product" type="text" class="text" /><span class="icon is-absolute"><i class="fa fa-search"></i></span>
        </div>
        <div class="column has-text-right product-tools is-paddingless is-auto-width">
            <a id="btn-new-product" href="#" class="font-blue">
                <span class="icon"><i class="fa fa-plus-circle"></i></span>
                New product
            </a>
        </div>
    </header>
    <div class="product-rows is-relative">
        <div class="columns product-row is-marginless headers white-ter">
            <div class="column is-1"></div>
            <div class="column">Product</div>
            <div class="column is-1 has-text-centered">Stock</div>
            <div class="column has-text-centered is-2">Cost</div>
            <div class="column has-text-centered is-2">Price</div>
            <div class="column is-2">Updated</div>
        </div>
        <div class="product-list">
        <% loop $Products %>
            <div class="columns product-row is-marginless" data-id="$ID" data-title="$Title" data-barcode="$Barcode" data-cost="$Cost" data-price="$Price" data-thumbnail="$Thumbnail">
                <div class="column product-row__thumbnail is-1"></div>
                <div class="column product-row__title">$Title</div>
                <div class="column product-row__stock-count is-1 has-text-centered">$StockCount</div>
                <div class="column product-row__cost has-text-centered is-2">$Cost</div>
                <div class="column product-row__price has-text-centered is-2">$Price</div>
                <div class="column product-row__last-update is-2">$LastUpdate</div>
            </div>
        <% end_loop %>
        </div>
    </div>
</section>
