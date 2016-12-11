<div class="section hero">
	<div class="container">
		<h1>商店</h1>
		<div class="breadcrumb">$Breadcrumbs</div>
	</div>
</div>
<div class="section">
	<div class="container">
		<div class="product-list">
			<div class="product-options">
				<div class="view-modes">
					<button class="view-mode as-grid active icon-grids">栅格</button>
					<button class="view-mode as-list icon-menu">列表</button>
				</div>
				<div class="sortings">
					<div class="select icon-angle-down">
						<select>
							<option selected value="Created">上架日期</option>
							<option value="Title">商品名称</option>
							<option value="Price">价格</option>
						</select>
					</div>
					<% if $isASC %>
						<a href="$LinkThis('sort', 'DESC')" class="btn-sort icon-down" title="使用倒序">倒序</a>						
					<% else %>
						<a href="$LinkThis('sort', 'ASC')" class="btn-sort icon-up" title="使用顺序">顺序</a>
					<% end_if %>
				</div>
				<div class="groupon-only">
					<% if $grouponOnly %>
					<a href="$LinkThis('groupon-only')" class="icon-check">只看抢购活动</a>
					<% else %>
					<a href="$LinkThis('groupon-only', 1)" class="icon-uncheck">只看抢购活动</a>
					<% end_if %>
				</div>
			</div>
			<div class="product-tiles">
			<% loop $Products %>
				<% include ProductTile inList=true %>
			<% end_loop %>
			</div>
			<div class="container pagination be-lazy text-center">
				<% if $Products.MoreThanOnePage %>
					<% if $Products.NotLastPage %>
						<a class="next button black text-white text-centered extra-small" id="next" href="$Products.NextLink">Next</a>
					<% end_if %>
				<% end_if %>
			</div>
		</div>
		<aside class="product-filters">
			<div class="category-filter">
				<h2 class="filter-title">品牌分类</h2>
				<ul class="category-filter-list">
				<% loop $Categories %>
					<li><a href="/products?category=$ID">$Title</a> <span>$Product.Count</span></li>
				<% end_loop %>
				</ul>
			</div>
			<div class="promo">
				<a href="$Promo.Link">
					$Promo.Vertical.FillMax(300, 410)
				</a>
			</div>
			<div class="tag-filter">
				<h2 class="filter-title">标签分类</h2>
				<ul class="tag-filter-list">
				<% loop $Tags %>
					<li><a href="/products?tag=$ID">$Title</a> <span>($Products.Count)</span></li>
				<% end_loop %>
				</ul>
			</div>
		</aside>
	</div>
</div>