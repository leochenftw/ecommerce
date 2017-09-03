<script>
window.exchange_rates = [
<% loop $SiteConfig.Rates.Limit(14) %>
    {
        date: '$Created.Date',
        close: $Price
    },
<% end_loop %>
];
</script>

<div class="section transparent main-body">
    <div class="tile is-ancestor">
      <div class="tile is-parent">
        <article class="tile is-child box">
            <a class="columns align-vertical-center" href="/storist/v1/manage/account">
                <div class="is-2 column has-text-centered">
                    <span class="icon"><i class="fa fa-user"></i></span>
                </div>
                <div class="column">
                    <p class="title">Hello $CurrentMember.FirstName</p>
                    <p class="subtitle">What is up?</p>
                </div>
            </a>
        </article>
      </div>
      <div class="tile is-parent">
        <article class="tile is-child box">
            <a class="columns align-vertical-center" href="/storist/v1/manage/products">
                <div class="is-2 column has-text-centered">
                    <span class="icon"><i class="fa fa-shopping-cart"></i></span>
                </div>
                <div class="column">
                    <p class="title">Products</p>
                    <p class="subtitle">$Products.Count items</p>
                </div>
            </a>
        </article>
      </div>
      <div class="tile is-parent">
        <article class="tile is-child box">
            <a class="columns align-vertical-center" href="/storist/v1/manage/sales">
                <div class="is-2 column has-text-centered">
                    <span class="icon"><i class="fa fa-line-chart"></i></span>
                </div>
                <% with $SalesToday %>
                <div class="column">
                    <p class="title">Today's Sales</p>
                    <p class="subtitle"><span class="icon font-blue is-bold" style="margin-right: 0.5em;"><i style="margin-top: 2px;" class="fa fa-money"></i> ${$Cash}</span> <span class="icon font-blue is-bold"><i style="margin-top: 2px;" class="fa fa-credit-card"></i> ${$EFTPOS}</span></p>
                </div>
                <div class="column is-narrow">
                    <p class="title is-1"><span class="font-blue is-bold" title="${$TotalRaw}">${$Total}</span></p>
                </div>
                <% end_with %>
            </a>
        </article>
      </div>
    </div>
    <div class="tile is-ancestor">
      <div class="tile is-vertical is-8">
        <div class="tile">
          <div class="tile is-parent is-vertical">
            <article class="tile is-child box">
              <p class="title">Operators</p>
              <p class="subtitle">Top box</p>
            </article>
            <article class="tile is-child box">
              <p class="title">Vertical tiles</p>
              <p class="subtitle">Bottom box</p>
            </article>
          </div>
          <div class="tile is-parent">
            <article class="tile is-child box">
                <div class="columns">
                    <div class="column">
                        <p class="title">Exchange rate</p>
                        <p class="subtitle">NZD vs. CNY</p>
                    </div>
                    <div class="column is-auto-width">
                        <span class="font-blue is-bold">$SiteConfig.Rate.Price</span>
                    </div>
                </div>
                <div id="exchange-trend-holder" class="is-relative"></div>
            </article>
          </div>
        </div>
        <div class="tile is-parent">
          <article class="tile is-child box">
            <p class="title">Wide column</p>
            <p class="subtitle">Aligned with the right column</p>
            <div class="content">
              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin ornare magna eros, eu pellentesque tortor vestibulum ut. Maecenas non massa sem. Etiam finibus odio quis feugiat facilisis.</p>
            </div>
          </article>
        </div>
      </div>
      <div class="tile is-parent">
        <article class="tile is-child box">
          <div class="content">
            <p class="title">Tall column</p>
            <p class="subtitle">With even more content</p>
            <div class="content">
              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam semper diam at erat pulvinar, at pulvinar felis blandit. Vestibulum volutpat tellus diam, consequat gravida libero rhoncus ut. Morbi maximus, leo sit amet vehicula eleifend, nunc dui porta orci, quis semper odio felis ut quam.</p>
              <p>Suspendisse varius ligula in molestie lacinia. Maecenas varius eget ligula a sagittis. Pellentesque interdum, nisl nec interdum maximus, augue diam porttitor lorem, et sollicitudin felis neque sit amet erat. Maecenas imperdiet felis nisi, fringilla luctus felis hendrerit sit amet. Aenean vitae gravida diam, finibus dignissim turpis. Sed eget varius ligula, at volutpat tortor.</p>
              <p>Integer sollicitudin, tortor a mattis commodo, velit urna rhoncus erat, vitae congue lectus dolor consequat libero. Donec leo ligula, maximus et pellentesque sed, gravida a metus. Cras ullamcorper a nunc ac porta. Aliquam ut aliquet lacus, quis faucibus libero. Quisque non semper leo.</p>
            </div>
          </div>
        </article>
      </div>
    </div>

</div>
