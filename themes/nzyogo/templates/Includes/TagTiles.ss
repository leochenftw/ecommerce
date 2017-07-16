<section id="all-tags" class="section" style="min-height: 400px;">
	<div class="container">
		<header class="has-text-centered">
            <h2 class="title is-1">标签汇</h2>
            <div class="max-width-580 section-intro">
                <p>瞧一瞧, 看一看 -- 有你想要找东西么?</p>
            </div>
        </header>
		<div class="all-tags columns wrap align-horizontal-center">
			<% loop $Tags %>
				$Me
			<% end_loop %>
		</div>
	</div>
</section>
