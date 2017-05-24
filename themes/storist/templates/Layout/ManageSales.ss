<section class="section transparent sales">
    <header class="columns is-marginless">
        <div class="column is-auto-width"><h2 class="title is-2">Sales</h2></div>
        <div class="column has-text-right sales-tools">
            <div class="columns is-marginless">
                <div class="sales-search column is-relative is-paddingless">
                    <input type="text" class="text" /><span class="icon is-absolute"><i class="fa fa-search"></i></span>
                </div>
                <div class="sales-filters column is-relative is-paddingless">
                    <input type="text" class="text" /><span class="icon is-absolute"><i class="fa fa-calendar"></i></span>
                    <label for="show-refunded"><input id="show-refunded" type="checkbox" checked /> Refunded</label>
                    <label for="show-success"><input id="show-success" type="checkbox" checked /> Success</label>
                </div>
                <div class="sales-refresh column is-auto-width"><a id="btn-refresh" href="#" class="icon"><i class="fa fa-refresh"></i></a></div>
            </div>
        </div>
    </header>
    <div class="sales-records">
    <% loop $Sales %>
        <div class="columns sales-record is-marginless">
            <div class="column sales-record__receipt-id">$Title</div>
            <div class="column sales-record__at font-grey-light">$Created</div>
            <div class="column sales-record__amount font-blue"><span class="icon payment-method"><i class="fa fa-<% if $PaymentMethod == 'Cash' %>money<% else %>credit-card<% end_if %>"></i></span>$Amount</div>
            <div class="column sales-record__operator font-orange">$Operator</div>
            <div class="column sales-record__status is-auto-width"><span class="icon font-<% if $Refunded %>red<% else %>green<% end_if %>"><i class="fa fa-<% if $Refunded %>times-circle<% else %>check-circle-o<% end_if %>"></i></span></div>
        </div>
    <% end_loop %>
    </div>
</section>
