<div class="yo-gold">
	<div class="yo-gold__balance"><span>${$Credit}</span> 余额</div>
	
	<div class="yo-gold__message">
		<% if $MessageType != 0 %>
			<input type="hidden" class="disable-button" value="1" />
		<% end_if %>
		<% if $MessageType == -1 %>
			<a href="/member">登录</a>后使用优Gold余额支付. 如果您还没有账号, 请先<a href="/signup">注册</a>.
		<% end_if %>
		<% if $MessageType == 1 %>
			您的优Gold余额不足. 请先<a id="btn-top-up" href="#">充值</a>.
		<% end_if %>
		<% if $MessageType == 0 %>
			此次交易后, 您的余额为${$Balance}
		<% end_if %>
	</div>
</div>