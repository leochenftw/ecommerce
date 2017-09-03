<section class="section transparent sales products ranking">
    <header class="columns is-marginless align-vertical-center">
        <div class="column is-2"><h2 class="title is-2">Ranking</h2></div>
        <div class="column has-text-right sales-tools is-paddingless">
            <div class="columns is-marginless align-vertical-center align-horizontal-right">
                <div class="sales-search-filter column is-narrow">
                    <label class="is-inline-block" for="search-with-freeview"><span id="spinner-loading" class="button is-loading"></span><input class="hide" value="freeview" type="radio" checked name="search-target" id="search-with-freeview" /> Freeview</label>
                    <label class="is-inline-block" for="search-with-barcode"><input value="barcode" type="radio" name="search-target" id="search-with-barcode" /> Barcode</label>
                    <label class="is-inline-block" for="search-with-provider"><input value="manufacturer" type="radio" name="search-target" id="search-with-provider" /> Provider</label>
                </div>
                <div class="sales-search column is-4 is-relative is-paddingless">
                    <input id="search-ranking" type="text" class="text" /><span class="icon is-absolute"><i class="fa fa-search"></i></span>
                </div>
                <div class="column date-picker-holder is-relative is-auto-width">
                    <input placeholder="$AWeekago" name="from" type="text" class="text date-from date-picker do-sweep" /><span class="icon is-absolute left-more"><i class="fa fa-calendar"></i></span>
                </div>
                <div class="column date-picker-holder is-relative is-paddingless is-auto-width">
                    <input placeholder="$Today" name="to" type="text" class="text date-to date-picker do-sweep" /><span class="icon is-absolute"><i class="fa fa-calendar"></i></span>
                </div>
                <div class="list-refresh column is-narrow">
                    <a id="btn-reset-ranking" href="#" class="icon"><i class="fa fa-refresh"></i></a>
                </div>
            </div>
        </div>
    </header>

    <div class="product-rows is-relative">
        <div class="columns sales-record is-marginless headers white-ter">
            <div class="column is-3">Barcode</div>
            <div class="column">Product</div>
            <div class="column has-text-centered is-2">Provider</div>
            <div class="column has-text-centered is-1">Sold</div>
            <div class="column has-text-centered is-1">Quantity</div>
        </div>
        <div class="product-list"></div>
    </div>
</section>
