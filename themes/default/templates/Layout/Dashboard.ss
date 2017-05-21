<div class="section hero">
	<div class="container">
		<h1><% if $CurrentUser.isEnglish %>$CurrentUser.FirstName $CurrentUser.Surname<% else %>$CurrentUser.Surname$CurrentUser.FirstName<% end_if %></h1>
		<div class="breadcrumb">$Breadcrumbs</div>
	</div>
</div>

<div class="section">
	<div class="container member-area">
		<aside class="member-area__sidebar">
			<ul class="neat-ul">
				<li><a data-title="会员中心 | 会员资料" href="/member/action/profile" class="ajax-routed<% if $tab == 'profile' || not $tab || $tab == 'email-update' %> active<% end_if %>">会员资料</a></li>
				<li><a data-title="会员中心 | 修改密码" href="/member/action/password" class="ajax-routed<% if $tab == 'password' %> active<% end_if %>">修改密码</a></li>
				<li><a data-title="会员中心 | 收货地址" href="/member/action/address" class="ajax-routed<% if $tab == 'address' %> active<% end_if %>">收货地址</a></li>
				<li><a data-title="会员中心 | 优Gold" href="/member/action/yo-gold" class="ajax-routed<% if $tab == 'yo-gold' %> active<% end_if %>">优Gold</a></li>
				<li><a data-title="会员中心 | 购买历史" href="/member/action/orders" class="ajax-routed<% if $tab == 'orders' %> active<% end_if %>">购买历史</a></li>
				<li><a data-title="会员中心 | 我的收藏" href="/member/action/favourites" class="ajax-routed<% if $tab == 'favourites' %> active<% end_if %>">我的收藏</a></li>
				<li><a data-title="会员中心 | 我的关注" href="/member/action/watch" class="ajax-routed<% if $tab == 'watch' %> active<% end_if %>">我的关注</a></li>
				<li><a href="/member/signout">退出登录</a></li>
			</ul>
		</aside>

		<div class="member-area__content">
			<% if $tab == 'profile' || not $tab %>
				<h2 class="title">修改个人资料</h2>
				$MemberProfileForm
			<% end_if %>

			<% if $tab == 'password' %>
				<h2 class="title">修改登录密码</h2>
				$UpdatePasswordForm
			<% end_if %>

			<% if $tab == 'email-update' %>
				<h2 class="title">修改邮箱地址</h2>
				$UpdateEmailForm
			<% end_if %>

			<% if $tab == 'address' %>
				<% include MyAddresses %>
			<% end_if %>

			<% if $tab == 'yo-gold' %>
				<h2 class="title">优Gold充值</h2>
				$YoGoldPurchaseForm
			<% end_if %>

			<% if $tab == 'orders' %>
                <% if $Order %>
                <% include OrderReceipt %>
                <% else %>
				<% include OrderHistory %>
                <% end_if %>
			<% end_if %>

			<% if $tab == 'watch' %>
				<% include MyWatch %>
			<% end_if %>

			<% if $tab == 'favourites' %>
				<% include FavouritesList %>
			<% end_if %>
		</div>
	</div>
</div>
