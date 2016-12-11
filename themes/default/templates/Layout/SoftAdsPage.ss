<div class="section hero">
	<div class="container">
		<h1>$Title</h1>
		<div class="breadcrumb">$Breadcrumbs</div>
	</div>
</div>
<div class="section article-section">
	<div class="container">
		<article class="blog-content">
			<div class="cover">
				<time class="created" datetime="$Created.Nice">$Date <span>$Created.ShortMonth</span></time>
				$Cover.FillMax(870, 546)
			</div>
			<div class="content">
				<h2 class="title">$Title</h2>
				$Content
				<div class="addthis_inline_share_toolbox_k6r6"></div>
			</div>
			<div class="row">
				<h3>最近更新</h3>
				<div class="latest-updates">
				<% loop $LatestUpdates %>
					<% include MiniBlogTile %>
				<% end_loop %>
				</div>
			</div>
		</article>
		<aside class="blog-misc">
			<div class="category-filter">
				<h2 class="filter-title">相关商品</h2>
				<ul class="category-filter-list">
				<% loop $RelatedProdct.limit(4) %>
					<li class="border-less">
						<a href="$Link">
							<div class="product-thumbnail">$getSquared.FillMax(85, 85)</div>
							<div class="product-details">
								<span class="title">$Title</span>
								<span class="price">${$Pricings.First.Price}</span>
							</div>
						</a>
					</li>
				<% end_loop %>
				</ul>
			</div>
			
			<div class="tag-filter">
				<h2 class="filter-title">同类资讯</h2>
				<ul class="tag-filter-list">
				<% loop $SimilarBlogs %>
					<li class="border-less">
						<a href="$Link">
							<div class="product-thumbnail">$Cover.Cropped.FillMax(85, 85)</div>
							<div class="product-details">
								<span class="title blog-title">$Title</span>
								<span class="created">$Created.Nice</span>
							</div>
						</a>
					</li>
				<% end_loop %>
				</ul>
			</div>
			
			<div class="promo">
				<a href="$Promo.Link">
					$Promo.Vertical.FillMax(300, 410)
				</a>
			</div>
			
		</aside>
	</div>
</div>