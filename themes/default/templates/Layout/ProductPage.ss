<% if $CurrentGroupon %>
	<% include GrouponHeader this=$CurrentGroupon %>
<% else %>
<div class="section hero">
	<div class="container">
		<h1>商店</h1>
		<div class="breadcrumb">$Breadcrumbs</div>
	</div>
</div>
<% end_if %>
<article class="product-details container">
	<div class="row">
		<div class="grid product-images">
			<% include ProductImageViewer %>
		</div>
		<div class="grid product-form">
			<<% if $CurrentGroupon %>h2<% else %>h1<% end_if %> class="product-form__title">$Title</<% if $CurrentGroupon %>h2<% else %>h1<% end_if %>>
			<div class="product-form__miscs">
				<span class="<% if $inStock %>icon-check<% else %>icon-x<% end_if %> font-inline"><% if $inStock %>有<% else %>没<% end_if %>货</span>
				<span class="<% if $AcceptOrder %>icon-check<% else %>icon-x<% end_if %> font-inline"><% if $AcceptOrder %>接受订单<% else %>仅在抢购活动时接受订单<% end_if %></span>
			</div>
			<div class="product-form__price">
				$Price
			</div>		
			<div class="product-form__content">
				<<% if $CurrentGroupon %>h3<% else %>h2<% end_if %> class="product-form__content__content-title">商品简介</<% if $CurrentGroupon %>h3<% else %>h2<% end_if %>>
				$Content
			</div>
			$ProductOrderForm
			<div class="product-form__social">
				<span class="label">分享</span> <div class="addthis_inline_share_toolbox"></div>
			</div>
			<div class="product-form__taggings">
				<div class="product-form__taggings__categories">
					<span>品牌: </span><a href="/products?category=$CategoryID">$Category.Title</a>
				</div>
				<div class="product-form__taggings__tags">
					<span>标签: </span>
					<% loop $tags %>
					<a href="/products?tag=$ID">$Title</a><% if not $Last %>, <% end_if %>
					<% end_loop %>
				</div>
			</div>
		</div>
	</div>
	<% if $ProductDescs %>
	<div class="row product-detailed-intro">
		<h2 class="text-center product-detailed-intro__title">商品详细介绍</h2>
		<div class="imagery">
		<% loop $ProductDescs.Sort('Title', 'ASC') %>
			<div class="imagery__item">$SetWidth(1200)</div>
		<% end_loop %>
		</div>
	</div>
	<% end_if %>
</article>
<section class="section product-carousel-section">
	<div class="container">
		<header class="text-center"><h2>品牌其他商品</h2></header>
		<div class="row product-carousel owl-carousel">
		<% loop $Category.Product.exclude('ID', $ID).limit(10) %>
		<% include ProductTile %>
		<% end_loop %>		
		</div>
	</div>
</section>
<section class="section product-carousel-section no-top-padding">
	<div class="container">
		<header class="text-center"><h2>同类商品</h2></header>
		<div class="row product-carousel owl-carousel">
		<% loop $Tags.First.Products.exclude('ID', $ID).limit(10) %>
		<% include ProductTile %>
		<% end_loop %>		
		</div>
	</div>
</section>

