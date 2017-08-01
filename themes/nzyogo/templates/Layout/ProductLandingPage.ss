<% include PageHero %>
<div class="section products">
	<div class="container">
        <div class="columns">
    		<div class="product-list column">
    			<div class="product-options navbar">
                    <div class="navbar-menu">
        				<div class="view-modes navbar-start">
        					<button class="view-mode as-grid active icon-grids">栅格</button>
        					<button class="view-mode as-list icon-menu">列表</button>
        				</div>
        				<div class="sortings navbar-center">
        					<div class="select">
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
        				<div class="groupon-only navbar-end">
        					<% if $grouponOnly %>
        					<a href="$LinkThis('groupon-only')" class="icon-check">只看抢购活动</a>
        					<% else %>
        					<a href="$LinkThis('groupon-only', 1)" class="icon-uncheck">只看抢购活动</a>
        					<% end_if %>
        				</div>
                    </div>
    			</div>
    			<div class="product-tiles columns wrap">
    			<% loop $Products %>
    				<% include ProductTile inList=true,Language=$Top.Language %>
    			<% end_loop %>
    			</div>
                <% if $Products.MoreThanOnePage %>
                <nav class="pagination">
                    <a href="$Products.PrevLink" class="pagination-previous"<% if not $Products.NotFirstPage %> disabled<% end_if %>>Prev</a>
                    <a href="$Products.NextLink" class="pagination-next"<% if not $Products.NotLastPage %> disabled<% end_if %>>Next</a>
                    <ul class="pagination-list" style="list-style: none; margin: 0;">
                    <% loop $Products.PaginationSummary %>
                        <% if $Link %>
                            <li style="margin-top: 0;"><a href="$Link" class="pagination-link<% if $CurrentBool %> is-current<% end_if %>">$PageNum</a></li>
                        <% else %>
                            <li><span class="pagination-ellipsis">&hellip;</span></li>
                        <% end_if %>
                    <% end_loop %>
                    </ul>
                </nav>
                <% end_if %>
    		</div>
    		<aside class="product-filters column is-3">
    			<div class="category-filter filter">
    				<h2 class="title is-5 is-bold filter-title">品牌分类</h2>
    				<ul class="category-filter-list">
    				<% loop $Categories %>
    					<li><a href="/products?category=$ID">$Title</a> <span>$Product.Count</span></li>
    				<% end_loop %>
    				</ul>
    			</div>
    			<div class="promo filter">
    				<a href="$Promo.Link">
    					$Promo.Vertical.FillMax(300, 410)
    				</a>
    			</div>
    			<div class="tag-filter filter">
    				<h2 class="title is-5 is-bold filter-title">标签分类</h2>
    				<ul class="tag-filter-list">
    				<% loop $Tags %>
    					<li><a href="/products?tag=$ID">$Title</a> <span>($Products.Count)</span></li>
    				<% end_loop %>
    				</ul>
    			</div>
    		</aside>
        </div>
	</div>
</div>
