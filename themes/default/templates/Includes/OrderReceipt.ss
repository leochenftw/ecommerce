<% with $Order %>
    <table class="cart-table full-width">
        <thead>
            <tr class="heading">
                <th class="prod-photo"></th>
                <th class="prod-info">商品名称</th>
                <th class="prod-price">单价</th>
                <th class="prod-quantity">数量</th>
                <th class="prod-subtotal">小计</th>
            </tr>
        </thead>
        <tbody>
        <% loop $OrderItems %>
            <tr>
                <td class="prod-photo">
                    <a href="$ItemLink">$Poster.FillMax(160, 80)</a>
                </td>
                <td class="prod-info">
                    <a href="$ItemLink">$ProductTitle</a>
                </td>
                <td class="prod-price">${$UsingPricing.Price}</td>
                <td class="prod-quantity">$Quantity</td>
                <td class="prod-subtotal">${$FormattedSubtotal}</td>
            </tr>
        <% end_loop %>
            <tr>
                <td class="prod-photo"></td>
                <td class="prod-info text-right">已选快递: <strong>$UsingFreight.Title</strong></td>
                <td class="prod-price">${$UsingFreight.Price} / kg</td>
                <td class="prod-quantity">{$Weight} kg</td>
                <td class="prod-subtotal">${$FrieghtCost}</td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td class="prod-photo"></td>
                <td class="prod-info"></td>
                <td class="prod-price"></td>
                <td class="prod-quantity">总计</td>
                <td class="prod-subtotal">${$getTotal(true)}</td>
            </tr>
        </tfoot>
    </table>
    <% include OrderReceiptFooter %>
<% end_with %>
