<section class="section transparent sales">
    <header class="columns is-marginless align-vertical-center">
        <div class="column is-2"><h2 class="title is-2">Sales</h2></div>
        <div class="column has-text-right sales-tools is-paddingless">
            <div class="columns is-marginless align-vertical-center align-horizontal-right">
                <div class="sales-stats column is-2 is-relative is-paddingless-vertical">
                <% with $Sales %>
                    <strong class="total-items">$TotalItems</strong> records
                <% end_with %>
                </div>
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
            <div class="column sales-record__status is-auto-width">
                <span class="icon font-<% if $Refunded %>red<% else %>green<% end_if %>"><i class="fa fa-<% if $Refunded %>times-circle<% else %>check-circle-o<% end_if %>"></i></span>
            </div>
            <div class="column sales-record__action is-narrow">
                <button class="button btn-refund is-danger is-small">Refund</button>
                <button class="button btn-close is-small">Close</button>
            </div>
        </div>
    <% end_loop %>
    </div>

    <div class="sales-pagination">
    <% if $Sales.MoreThanOnePage %>
        <nav class="pagination">
            <a href="$Sales.PrevLink" class="pagination-previous"<% if not $Sales.NotFirstPage %> disabled<% end_if %>>Prev</a>
            <a href="$Sales.NextLink" class="pagination-next"<% if not $Sales.NotLastPage %> disabled<% end_if %>>Next</a>
            <ul class="pagination-list" style="list-style: none; margin: 0;">
            <% loop $Sales.PaginationSummary %>
                <% if $Link %>
                    <li style="margin-top: 0;"><a href="$Link" class="pagination-link<% if $CurrentBool %> is-current<% end_if %>">$PageNum</a></li>
                <% else %>
                    <li><span class="pagination-ellipsis">&hellip;</span></li>
                <% end_if %>
            <% end_loop %>
            </ul>
        </nav>
    <% end_if %>
    </div>
</section>
