<% if $CurrentGroupon %>
	<% include GrouponHeader this=$CurrentGroupon %>
<% else %>
    <% include PageHero %>
<% end_if %>
<div class="section product-details">
    
    <script>console.log($ID);</script>

    <article class="container content">
    	<div class="columns">
    		<div class="column product-images is-half">
    			<% include ProductImageViewer %>
    		</div>
    		<div class="column product-form is-half">
    			<<% if $CurrentGroupon %>h2<% else %>h1<% end_if %> class="title is-1 is-bold product-form__title">
                <% if $Language == 'Chinese' %>$Chinese<% else %>$Title<% end_if %>
                </<% if $CurrentGroupon %>h2<% else %>h1<% end_if %>>
    			<div class="product-form__miscs">
                    <span class="icon"><i class="fa fa-<% if $inStock %>check<% else %>times<% end_if %>"></i><% if $inStock %>有<% else %>没<% end_if %>货</span>
                    <span class="icon"><i class="fa fa-<% if $AcceptOrder %>check<% else %>times<% end_if %>"></i><% if $AcceptOrder %>接受订单<% else %>仅在抢购活动时接受订单<% end_if %></span>
    			</div>
    			<div class="product-form__price title is-3 is-bold">
    				${$Price}
    			</div>
    			<div class="product-form__content">
    				<<% if $CurrentGroupon %>h3<% else %>h2<% end_if %> class="title is-3 is-bold product-form__content__content-title">商品简介</<% if $CurrentGroupon %>h3<% else %>h2<% end_if %>>
    				$Content
    			</div>
    			<% with $ProductOrderForm %>
                    <form $FormAttributes>
                        <% loop $Fields %>
                            <% if $name != 'Quantity' %>$Field<% end_if %>
                        <% end_loop %>
                        <div class="columns align-vertical-center">
                            <label for="ProductOrderForm_ProductOrderForm_Quantity" class="column is-narrow">数量</label>
                            <div class="column field-quantity is-narrow">
                                $Fields.fieldByName('Quantity')
                            </div>
                            <div class="column icon is-paddingless-left">
                                $Actions
                            </div>
                        </div>
                    </form>
                <% end_with %>
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
</div>
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
