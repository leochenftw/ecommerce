<% include PageHero %>
<div class="section cart">
    <div class="container">
    <% if $Cart %>
        <div class="columns cart-heading">
            <div class="column prod-photo is-1"></div>
            <div class="column prod-info">商品名称</div>
            <div class="column prod-price is-2">单价</div>
            <div class="column prod-quantity is-1">数量</div>
            <div class="column prod-subtotal is-2">小计</div>
            <div class="column prod-action is-1"></div>
        </div>
        <div class="cart-body">
            <% loop $Cart.OrderItems %>
            <div class="columns cart-body-row align-vertical-center">
                <div class="column prod-photo is-1"><a href="$ItemLink">$Poster.SetHeight(80)</a></div>
                <div class="column prod-info"><a href="$ItemLink">$ProductTitle</a></div>
                <div class="column prod-price is-2">${$UnitPrice}</div>
                <div class="column prod-quantity is-1"><input class="txt-quantity text" data-item-id="$ID" type="number" value="$Quantity" /></div>
                <div class="column prod-subtotal is-2">${$FormattedSubtotal}</div>
                <div class="column prod-action is-1"><button class="btn-remove-order-item button" data-item-id="$ID">不要了</button></div>
            </div>
            <% end_loop %>
        </div>
        <form $CartForm.FormAttributes class="columns details">
            <div class="column is-7 details-customer">
                $CartForm.Fields.fieldByName('DeliveryAddress').FieldHolder
                <div id="CartForm_CartForm_NewDeliveryAddress_Holder" class="field text<% if $CurrentUser %> hide<% end_if %>">
                    <% if not $CurrentUser %>
                        <label class="left" for="CartForm_CartForm_NewDeliveryAddress">收货地址</label>
                    <% end_if %>
                    <div class="middleColumn">
                        $CartForm.Fields.fieldByName('NewDeliveryAddress')
                    </div>
                </div>
                <div class="columns">
                    <div class="column">$CartForm.Fields.fieldByName('Surname').FieldHolder</div>
                    <div class="column">$CartForm.Fields.fieldByName('FirstName').FieldHolder</div>
                </div>
                $CartForm.Fields.fieldByName('Email').FieldHolder
                $CartForm.Fields.fieldByName('Phone').FieldHolder
                $CartForm.Fields.fieldByName('AlsoSignup').FieldHolder
            </div>
            <div class="column details-freight">
                $CartForm.Fields.fieldByName('Weight').FieldHolder
                $CartForm.Fields.fieldByName('Freight').FieldHolder
                $CartForm.Fields.fieldByName('FreightCost').FieldHolder
                <div id="subtotal-wrapper" class="field readonly">
                    <label class="left">商品总计</label>
                    <div id="subtotal" class="middleColumn">${$Cart.Sum}</div>
                </div>
                $CartForm.Fields.fieldByName('Total').FieldHolder
                <div class="actions">
                    $CartForm.Fields.fieldByName('SecurityID')
                    $CartForm.Actions.First.addExtraClass('is-danger')
                    <% if $CartForm.Message && $CartForm.MessageType = 'bad' %>
                        <p>$CartForm.Message</p>
                    <% end_if %>
                </div>
            </div>
        </form>
        $resetForm
    <% else %>
        <p class="title is-1">老板要不您先逛逛?</p>
    <% end_if %>
    </div>
</div>
