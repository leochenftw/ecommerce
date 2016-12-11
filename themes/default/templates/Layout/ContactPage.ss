<div class="section hero">
	<div class="container">
		<h1>$Title</h1>
		<div class="breadcrumb">$Breadcrumbs</div>
	</div>
</div>

<div class="section content-area">
	<div class="container">
		<div class="row gmap">
			<div id="map-wrapper"><div id="map"></div></div>
		</div>
		<div class="row contact">
			<div class="info">
				<h2 class="title">联系方式</h2>
				<p>我们Lower Hutt本店的营业时间为: <br />周一至周六早10点到晚6点, <br />周日早11点到下午4点</p>
				<p>惠灵顿其他地区取货网点营业时间分别为: </p>
				<ul class="contact-ul">
					<li class="icon-home">Shop 5, 374-380 High St, Lower Hutt, <br />Wellington, New Zealand, 5010</li>
					<li class="icon-phone"><a href="tel:+64 04 569 2106">+64 04 569 2106</a></li>
					<li class="icon-mail"><a href="mailto:info@nzyogo.co.nz">info@nzyogo.co.nz</a></li>
				</ul>
				<div class="social-icons as-flex wrap">
					<a class="icon-wechat" href="">微信</a> 
					<a class="icon-qq" href="">QQ</a> 
					<a class="icon-weibo" href="">微博</a> 
					<a class="icon-facebook" href="">Facebook</a>
				</div>
			</div>
			
			<div class="form">
				<h2 class="title">您缺啥? 咱唠唠?</h2>
				<% with $ContactForm %>
				<form $FormAttributes>
					$Fields
					<div class="actions">
						<div class="g-recaptcha" data-sitekey="6LdGBgoUAAAAAEeRpUAKCZ9unXoyNcDbtgvdP5hE"></div>
						$Actions
					</div>
				</form>
				<% end_with %>
			</div>
		</div>
	</div>
</div>