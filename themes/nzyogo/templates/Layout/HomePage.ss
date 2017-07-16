<div class="container">
	<h1 class="hide">$Title</h1>
</div>

<% if $Promotionals %>
<div id="promotional-wrapper" class="owl-carousel single-item owl-theme">
	<% loop $Promotionals %>
		$Me
	<% end_loop %>
</div>
<% end_if %>

<% include CategoryTiles %>

<section id="best-sales" class="section">
	<div class="container">
		<header class="has-text-centered">
			<h2 class="title is-1 is-bold">最热卖</h2>
			<div class="max-width-580 section-intro">
                <p>以下上榜商品, 要么卖的最快, 要么卖的最多. 但不管是什么原因, 事实的背后总是有一个让人不能拒绝的理由 -- 还等什么? 一起加入抢购的行列吧!</p>
            </div>
		</header>
		<div class="columns wrap">
		<% loop $HotProducts %>
			<% include ProductTile %>
		<% end_loop %>
		</div>
	</div>
</section>

<% if $UpcomingGroupon %>
	<% include UpcomingGroupon %>
<% end_if %>

<% include TagTiles %>

<section id="top-up-promitoin" class="section parallax-window is-relative" data-parallax="scroll" data-image-src="/themes/default/images/yogold-bg.jpg">
	<div class="container has-text-centered">
		<header><h2 class="title is-1 is-bold">优Gold会员</h2></header>
		<div class="columns">
			<div class="column">
				<h3 class="title is-3 is-normal">充值优惠</h3>
				<p>网站开业大酬宾 - 所有充值加送10%, 让你扫货抢购底气足! <br />优Gold在手, 天下我有!</p>
			</div>
			<div class="column">
				<h3 class="title is-3 is-normal">优购秒抢</h3>
				<p>手残? 网慢? 边上人强行打断? 充值优Gold, 瞬间完成抢购 -- 自从我充值了优Gold, 妈妈再也不为我抢不到商品顿足捶胸了!</p>
			</div>
			<div class="column">
				<h3 class="title is-3 is-normal">VIP会员</h3>
				<p>冲级VIP会员, 获得更多折扣优惠!</p>
			</div>
		</div>
		<div class="row call-to-action-wrapper">
			<a class="button is-outlined is-large">充值优Gold</a>
		</div>
	</div>
</section>

<div id="latest-update" class="section">
	<div class="container has-text-centered">
		<h2 class="title is-1 is-bold">最新资讯</h2>
		<div class="max-width-580 section-intro">
			<p>资系多京马斗也则思电，状要会次电写然级指，用前陕能串展断传。 队层阶么积存干其，战实较军流反采是，易两孝速局正。</p>
		</div>
		<div class="latest-updates columns wrap align-horizontal-center">
		<% loop $LatestUpdates %>
			<% include BlogTile %>
		<% end_loop %>
		</div>
		<div class="row call-to-action-wrapper">
			<a class="button is-danger is-large" href="/news">去瞧瞧</a>
		</div>
	</div>
</div>
