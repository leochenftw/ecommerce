<% include PageHero %>
<div class="section content">
	<div class="container">
		<div class="gmap">
			<div id="map-wrapper"><div id="map"></div></div>
		</div>
		<div class="contact columns">
			<div class="info column is-4">
				<h2 class="title is-5 is-bold">联系方式</h2>
				<p>我们Lower Hutt本店的营业时间为: <br />周一至周六早10点到晚6点, <br />周日早11点到下午4点</p>
				<p>惠灵顿其他地区取货网点营业时间分别为: </p>
                <ul>
					<li class="is-relative"><span class="icon"><i class="fa fa-home"></i></span>Shop 5, 374-380 High St, Lower Hutt, Wellington, New Zealand, 5010</li>
					<li class="is-relative"><span class="icon"><i class="fa fa-phone"></i></span><a href="tel:+64 04 569 2106">+64 04 569 2106</a></li>
					<li class="is-relative"><span class="icon"><i class="fa fa-envelope"></i></span><a href="mailto:info@nzyogo.co.nz">info@nzyogo.co.nz</a></li>
				</ul>
                <div class="social-icons columns wrap">
					<div class="column is-narrow"><a class="has-text-centered" href=""><span class="icon"><i class="fa fa-weixin"></i></span><span class="hide">微信</span></a></div>
					<div class="column is-narrow"><a class="has-text-centered" href=""><span class="icon"><i class="fa fa-qq"></i></span><span class="hide">QQ</span></a></div>
					<div class="column is-narrow"><a class="has-text-centered" href=""><span class="icon"><i class="fa fa-weibo"></i></span><span class="hide">微博</span></a></div>
					<div class="column is-narrow"><a class="has-text-centered" href=""><span class="icon"><i class="fa fa-facebook"></i></span><span class="hide">Facebook</span></a></div>
				</div>
			</div>

			<div class="form column">
				<h2 class="title is-5 is-bold">您缺啥? 咱唠唠?</h2>
				<% with $ContactForm %>
				<form $FormAttributes>
					$Fields
					<div class="actions columns align-vertical-center">
                        <div class="column">
                            <div class="g-recaptcha" data-sitekey="6LdGBgoUAAAAAEeRpUAKCZ9unXoyNcDbtgvdP5hE"></div>
                        </div>
                        <div class="column has-text-right">
                            $Actions
                        </div>
					</div>
				</form>
				<% end_with %>
			</div>
		</div>
	</div>
</div>
