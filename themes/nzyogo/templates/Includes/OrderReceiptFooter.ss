<% with $SuccessPayment %>
<div class="as-flex space-between">
    <div class="payment-details">
        <p>支付方式: $PaymentMethod</p>
        <% if $CardNumber %>
        <p>信用卡: $CardNumber</p>
        <% else %>
            <% if $PaymentMethod == 'POLi' %>
                <p>银行卡: $MerchantAcctSortCode $MerchantAcctNumber $MerchantAcctSuffix</p>
            <% end_if %>
        <% end_if %>
        <p>支付时间: $ProcessedAt</p>
    </div>
    <div class="freight-details text-right">
        <% if $Shipments.Count > 0 %>

        <% else %>
        <p>您的订单还未装箱, 请等待. 装箱后我们会通过邮件通知您.</p>
        <% end_if %>
        <p><a class="inline-mini-button" href="/products">继续买买买</a></p>
        <p><a class="inline-mini-button" href="/member">返回会员中心</a></p>
    </div>
</div>
<% end_with %>
