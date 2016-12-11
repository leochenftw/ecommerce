<h2 class="title">我的关注</h2>
<div class="member-area__content__group">
    <label for="cb-subscrib-site"><input class="hide" type="checkbox" id="cb-subscrib-site"<% if $Subscribe %> checked<% end_if %> /> <span>加入订阅计划</span> <a class="sub-link" href="/about/subscription" target="_blank">这啥?</a></label>
</div>
<div class="member-area__content__group">
    <% loop $Watchlist %>
        <% with $Top.MiniSubscribeForm($ID) %>
            <form $FormAttributes>
				<div class="fields">
					<% loop $Fields %>
						$FieldHolder
					<% end_loop %>
				</div>
				<div class="Actions">
					$Actions
				</div>
			</form>
        <% end_with %>
    <% end_loop %>
</div>
