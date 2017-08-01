<div class="container checkout">
	<div class="title columns">
		<h1 class="column title">$Title</h1>
		<div class="column">Hello</div>
	</div>
	<div id="payment-handler-wrapper" class="columns full-width">
		<div class="amount-col column is-4">
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
		<div class="payment-detail-col column">
			<h2 class="title is-2 is-bold">支付方式</h2>
			<% with $PaymentHandler %>
			<form $FormAttributes>
				$Fields.fieldByName('PaymentMethod').FieldHolder
				<div id="payment-details"></div>
				<div class="actions">
					$Fields.fieldByName('SecurityID')
					$Actions.First.addExtraClass('is-danger')
				</div>
			</form>
			<% end_with %>
		</div>
	</div>
</div>
