<section class="section transparent sales">
    <header class="columns is-marginless vertical-center">
        <div class="column is-2"><h2 class="title is-2">Sales</h2></div>
        <div class="column has-text-right sales-tools is-paddingless">
            <div class="columns is-marginless vertical-center horizontal-right">
                <div class="sales-search column is-4 is-relative is-paddingless">
                    <input id="search-receipt" type="text" class="text" /><span class="icon is-absolute"><i class="fa fa-search"></i></span>
                </div>
                <div class="column date-picker-holder is-relative is-auto-width">
                    <input placeholder="$Today" name="from" type="text" class="text date-from date-picker" /><span class="icon is-absolute left-more"><i class="fa fa-calendar"></i></span>
                </div>
                <div class="column date-picker-holder is-relative is-paddingless is-auto-width">
                    <input placeholder="$Today" name="to" type="text" class="text date-to date-picker" /><span class="icon is-absolute"><i class="fa fa-calendar"></i></span>
                </div>
                <div class="column is-auto-width">
                    <label title="Toggle refunded items" for="show-refunded"><input class="to-exclude" id="show-refunded" type="checkbox" checked /> <span class="icon font-red"><i class="fa fa-times-circle"></i></span></label>
                </div>
                <div class="column is-auto-width is-paddingless">
                    <label title="Toggle successful items" for="show-success"><input class="to-exclude" id="show-success" type="checkbox" checked /> <span class="icon font-green"><i class="fa fa-check-circle-o"></i></span></label>
                </div>
                <div class="sales-refresh column is-auto-width">
                    <a id="btn-refresh" href="#" class="icon"><i class="fa fa-refresh"></i></a>
                </div>
            </div>
        </div>
    </header>
    <div class="sales-records">
    <% loop $Sales %>
        <div class="columns sales-record is-marginless" data-receipt="$Title">
            <div class="column sales-record__receipt-id">$Title</div>
            <div class="column sales-record__at font-grey-light">$Created</div>
            <div class="column sales-record__amount font-blue"><span class="icon payment-method"><i class="fa fa-<% if $PaymentMethod == 'Cash' %>money<% else %>credit-card<% end_if %>"></i></span>$Amount</div>
            <div class="column sales-record__operator font-orange">$Operator</div>
            <div class="column sales-record__status is-auto-width"><span class="icon font-<% if $Refunded %>red<% else %>green<% end_if %>"><i class="fa fa-<% if $Refunded %>times-circle<% else %>check-circle-o<% end_if %>"></i></span></div>
        </div>
    <% end_loop %>
    </div>
</section>
