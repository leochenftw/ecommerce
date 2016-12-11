<div class="container">
	<div class="title clearfix">
		<h1 class="float-left">$Title</h1>
		<div class="float-right">Hello</div>
	</div>
	<% if $Cart %>
		<table class="cart-table full-width">
			<thead>
				<tr class="heading">
					<th class="prod-photo"></th>
					<th class="prod-info">商品名称</th>
					<th class="prod-price">单价</th>
					<th class="prod-quantity">数量</th>
					<th class="prod-subtotal">总计</th>
					<th class="prod-action"></th>
				</tr>
			</thead>
			<tbody>
			<% loop $Cart.OrderItems %>
				<tr>
					<td class="prod-photo">
						<a href="$Groupon.Product.Link">$Groupon.Product.Poster.FillMax(160, 80)</a>
					</td>
					<td class="prod-info">
						<a href="$Groupon.Product.Link">$Groupon.Product.Title</a>
					</td>
					<td class="prod-price">${$UsingPricing.Price}</td>
					<td class="prod-quantity"><input class="txt-quantity" data-item-id="$ID" type="number" value="$Quantity" /></td>
					<td class="prod-subtotal">${$FormattedSubtotal}</td>
					<td class="prod-action"><button class="btn-remove-order-item" data-item-id="$ID">不要了</button></td>
				</tr>
			<% end_loop %>
			</tbody>
		</table>
		<form $checkout.FormAttributes class="as-table">
			<div class="td-left as-cell">
				$checkout.Fields.fieldByName('DeliveryAddress').FieldHolder
				<div id="CartForm_CartForm_NewDeliveryAddress_Holder" class="field text<% if $CurrentUser %> hide<% end_if %>">
					<% if not $CurrentUser %>
						<label class="left" for="CartForm_CartForm_NewDeliveryAddress">收货地址</label>
					<% end_if %>
					<div class="middleColumn">
						$checkout.Fields.fieldByName('NewDeliveryAddress')
					</div>
				</div>
				<div class="clearfix">
					$checkout.Fields.fieldByName('Surname').FieldHolder
					$checkout.Fields.fieldByName('FirstName').FieldHolder
				</div>
				$checkout.Fields.fieldByName('Email').FieldHolder
				$checkout.Fields.fieldByName('Phone').FieldHolder
				$checkout.Fields.fieldByName('AlsoSignup').FieldHolder
			</div>
			<div class="td-right as-cell">
				$checkout.Fields.fieldByName('Weight').FieldHolder
				$checkout.Fields.fieldByName('Freight').FieldHolder
				$checkout.Fields.fieldByName('FreightCost').FieldHolder
				<div id="subtotal-wrapper" class="field readonly">
					<label class="left">商品总计</label>
					<div id="subtotal" class="middleColumn">${$Cart.Sum}</div>
				</div>
				$checkout.Fields.fieldByName('Total').FieldHolder
				<div class="actions">
					$checkout.Fields.fieldByName('SecurityID')
					$checkout.Actions
					<% if $checkout.Message && $checkout.MessageType = 'bad' %>
						<p>$checkout.Message</p>
					<% end_if %>
				</div>
			</div>
		</form>
		$resetForm
	<% else %>
		Buy something first!
	<% end_if %>
</div>
