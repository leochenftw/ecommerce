<div class="container">
	<div class="title clearfix">
		<h1 class="float-left">$Title</h1>
		<div class="float-right">Hello</div>
	</div>
	
	<div id="payment-handler-wrapper" class="as-table full-width">
		<div class="amount-col as-cell">
			<% if $Cart %>
			<div class="amount vertical-centered text-centred">
				<span>结算金额</span>
				${$Cart.getTotal}
			</div>
			<% end_if %>
			<div class="stay-bottom side-action">
				<a href="/cart" class="button">先等等...</a>
			</div>
		</div>
		<div class="payment-detail-col as-cell">
			<h2 class="absolute">支付方式</h2>
			<% with $PaymentHandler %>
			<form $FormAttributes>
				$Fields.fieldByName('PaymentMethod').FieldHolder
				<div id="payment-details"></div>
				<div class="actions">
					$Fields.fieldByName('SecurityID')
					$Actions
				</div>
			</form>
			<% end_with %>
		</div>
	</div>
</div>