<section class="group-on section parallax-window" data-parallax="scroll" data-image-src="$Poster.SetWidth(1920).URL">
	<div class="container">
		<header class="text-center">
			<h2 class="group-on__title">$Title</h2>
		</header>
		<div class="group-on__content group-on__widget text-center">
			<div class="group-on__content__info">
				<span>$this.Type: </span> 
				<span class="group-on__widget__remaining">
					还剩
					<% if $this.Type == '限量抢购' %>
						<i class="stock-remaining">$this.Remaining</i> $Measurement
					<% end_if %>
					<% if $this.Type == '限时抢购' %>
						<time class="end-at" datetime="$this.End.Format('D M d Y H:i:s O')">$this.Remaining</time>
					<% end_if %>
					<% if $this.Type == '限时限量' %>
						<i class="stock-remaining">$this.Remaining.stock</i> $Measurement, <time class="end-at" datetime="$this.End.Format('D M d Y H:i:s O')">$this.Remaining.time</time>
					<% end_if %>
				</span>
			</div>
		<% if $CurrentUser.inGroup('administrators') %>
			<p>管理员不能参与</p>
		<% else %>
			<form class="group-on__order-form" action="/groupon-action/buy" method="post" target="_blank">
				<label for="quantity">来</label>
				<input id="quantity" name="quantity" type="number" class="text" value="1" />
				<span class="measurement-unit">$Measurement</span>
				<input type="hidden" name="product-id" value="$ID" />
				<input type="hidden" name="groupon-id" value="$this.ID" />
				<div class="actions">
					<input class="action button" type="submit" value="抢购" />
				</div>
			</form>
			<div class="groupon-on__mini-cart">
				<% if not $NumUnpaid && not $NumPaid %>
					<p>您还什么都没买呢.</p>
				<% else %>
					<% if not $NumUnpaid && $NumPaid > 0 %>
						<p>您已经成功抢购了<span class="num-paid">$NumPaid</span>{$Measurement}$Title.</p>
					<% end_if %>
					<% if $NumUnpaid > 0 && $NumPaid == 0 %>
						<p>您已经抢购了<span class="num-unpaid">$NumUnpaid</span>{$Measurement}, 但是您还没有付款. 为确保此次抢购生效, 请您尽快<a class="lnk-pay inline-mini-button" href="/cart">支付</a></p>
					<% end_if %>
					<% if $NumUnpaid == 0 && $NumPaid > 0 %>
						<p>您已经成功抢购了<span class="num-paid">$NumPaid</span>{$Measurement}$Title</p>
					<% end_if %>
					<% if $NumUnpaid > 0 && $NumPaid > 0 %>
						<p>您已经成功抢购了<span class="num-paid">$NumPaid</span>{$Measurement}$Title, 另外还有<span class="num-unpaid">$NumUnpaid</span>{$Measurement}尚未付款. 为确保此次抢购生效, 请您尽快<a class="lnk-pay inline-mini-button" href="/cart">支付</a></p>
					<% end_if %>
				<% end_if %>
			</div>
		<% end_if %>
	</div>
</section>
<!-- $SessionID -->