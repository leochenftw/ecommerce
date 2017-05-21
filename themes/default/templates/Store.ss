<!DOCTYPE html>
<!--[if lt IE 7]>      <html lang="$ContentLocale" class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html lang="$ContentLocale" class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html lang="$ContentLocale" class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="$ContentLocale" class="no-js locked"> <!--<![endif]-->
	<head>
		<link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
		<link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png">
		<link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png">
		<link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png">
		<link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png">
		<link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png">
		<link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png">
		<link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
		<link rel="icon" type="image/png" sizes="192x192"  href="/android-icon-192x192.png">
		<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
		<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
		<link rel="manifest" href="/manifest.json">
		<meta name="msapplication-TileColor" content="#ffffff">
		<meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
		<meta name="theme-color" content="#ffffff">
		<script src="https://use.typekit.net/bsg3kpw.js"></script>
		<script>try{Typekit.load({ async: true });}catch(e){}</script>
		<% base_tag %>
		$MetaTags(true)

		<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
		$getCSS
		<script src="$ThemeDir/js/lib/modernizr.min.js"></script>

	</head>
	<body class="page-$URLSegment.LowerCase<% if $isMobile %> mobile<% end_if %> page-type-$BodyClass.LowerCase">
        <header id="header"<% if $CurrentMember.Logo %> class="padding-top-bottom"<% end_if %>>
            <div class="container text-centred">
                <% if $CurrentMember.Logo %>
                    <div class="as-inline-block loading-indicator"><div class="icon-loading animate-spin"></div></div>
                    <a class="as-inline-block" id="supplier-logo">$CurrentMember.Logo.SetHeight(40)</a>
                    <div class="as-inline-block text-left print-only merchant-info">
                        $CurrentMember.TradingName<br />
                        $CurrentMember.GST
                    </div>
                <% else %>
                    <a href="/" id="logo" rel="start">NZ优购</a>
                    <div class="loading-indicator"><div class="icon-loading animate-spin"></div></div>
                <% end_if %>
            </div>
        </header>
		<main id="main">
            <div class="container as-flex">
                <% with $StoreOrderForm %>
                <form $FormAttributes>
                    <table id="to-buy" class="to-buy as-table full-width">
                        <thead>
                            <tr>
                                <th class="to-buy__action not-on-print"></th>
                                <th class="to-buy__title">Product</th>
                                <th class="to-buy__unit-price">Unit price</th>
                                <th class="to-buy__quantity">Qty</th>
                                <th class="to-buy__price">Sub total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr id="to-buy-item-template">
                                <td class="to-buy__action not-on-print"></td>
                                <td class="to-buy__title"></td>
                                <td class="to-buy__unit-price"></td>
                                <td class="to-buy__quantity"></td>
                                <td class="to-buy__price"></td>
                            </tr>
                        </tbody>
                    </table>
                    $fields
                    <div class="Actions hide">
                        $Actions
                    </div>
                </form>
                <% end_with %>
            </div>
            <div class="container print-only merchant-misc">
                <p><strong>NOTE: </strong>ALL PRICES ARE GST INCLUSIVE</p>
                <p><strong>Operator: </strong>$CurrentMember.FirstName $CurrentMember.Surname</p>
                <p><strong>Paid at: </strong><span id="paid-at"></span></p>
            </div>
        </main>
		<footer id="footer">
            <div class="container">
                $StoreLookupForm
            </div>
            <div class="container as-flex space-between vertical-centre">
                <div id="payment-methods" class="col">
                    <%-- <label for="do-print"><input id="do-print" type="checkbox" name="do_print" checked />print receipt</label> --%>
                    <button class="payment-trigger not-on-print" data-target="action_byCash">Cash</button>
                    <%-- <button class="payment-trigger" data-target="action_byCredit">Credit</button> --%>
                    <button class="payment-trigger not-on-print" data-target="action_byEFTPOS">EFTPOS</button>
                </div>
                <div id="receipt-barcode-col" class="col print-only">
                    <svg id="receipt-barcode"></svg>
                </div>
                <div class="col" id="txt-sum">
                    <span id="txt-sum-val" class="as-block">$0.00</span>
                    <span id="txt-gst" class="as-block">$0.00</span>
                    <%-- <input id="txt-sum" type="text" readonly value="$0.00" /> <label>Total</label> --%>
                </div>
            </div>
        </footer>
		<script type="text/javascript">window.security_id = '$SecurityID';</script>
	</body>
</html>
