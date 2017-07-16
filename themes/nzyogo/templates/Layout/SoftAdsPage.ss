<% include PageHero %>
<div class="section article-section">
	<div class="container">
        <div class="columns">
    		<article class="blog-content column">
    			<div class="cover is-relative">
    				<time class="created" datetime="$Created.Nice">$Date <span>$Created.ShortMonth</span></time>
    				$Cover.FillMax(870, 546)
    			</div>
    			<div class="content">
    				<h2 class="title is-5 is-bold">$Title</h2>
    				$Content
    				<div class="addthis_inline_share_toolbox_k6r6"></div>
    			</div>
    			<div id="latest-update" class="row">
    				<h3 class="title is-5 is-bold">最近更新</h3>
    				<div class="latest-updates columns wrap">
    				<% loop $LatestUpdates %>
    					<% include MiniBlogTile %>
    				<% end_loop %>
    				</div>
    			</div>
    		</article>
    		<aside class="column is-3">
                <div class="blog-misc">
        			<div class="category-filter filter">
        				<h2 class="filter-title title is-5 is-bold">相关商品</h2>
                        <% if $RelatedProdct %>
            				<ul class="category-filter-list">
            				<% loop $RelatedProdct.limit(4) %>
            					<li class="border-less">
            						<a href="$Link" class="columns">
            							<div class="product-thumbnail column is-narrow">$getSquared.FillMax(85, 85)</div>
            							<div class="product-details column">
            								<h3 class="title is-6 is-bold">$Title</h3>
            								<p class="price subtitle is-6">${$Pricings.First.Price}</p>
            							</div>
            						</a>
            					</li>
            				<% end_loop %>
            				</ul>
                        <% else %>
                            <p>这是硬植入. 没有应对的商品</p>
                        <% end_if %>
        			</div>

        			<div class="tag-filter filter">
        				<h2 class="filter-title title is-5 is-bold">同类资讯</h2>
                        <% if $SimilarBlogs %>
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
                        <% else %>
                            <p>不好意思, 没找到类似的资讯</p>
                        <% end_if %>
        			</div>
                    <% if $Promo %>
        			<div class="promo">
        				<a href="$Promo.Link">
        					$Promo.Vertical.FillMax(300, 410)
        				</a>
        			</div>
                    <% end_if %>
        		</aside>
            </div>
        </div>
	</div>
</div>
