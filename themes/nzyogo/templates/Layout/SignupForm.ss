<div class="section hero">
	<div class="container">
		<h1>$Title</h1>
		<div class="breadcrumb">$Breadcrumbs</div>
	</div>
</div>

<div class="section">
	<div class="container">
		<% with $SignupForm %>
			<form $FormAttributes>
				<div class="fields">
					<% loop $Fields %>
						<% if $Name != 'Subscribe' %>
							$FieldHolder
						<% else %>
							<div class="as-flex">
								$FieldHolder
								<div class="g-recaptcha" data-sitekey="6LdGBgoUAAAAAEeRpUAKCZ9unXoyNcDbtgvdP5hE"></div>
							</div>
						<% end_if %>
					<% end_loop %>
				</div>
				<div class="Actions">
					$Actions.First
				</div>
				<div class="lnk-signup-wrapper margin-h-10-0-0 text-center"><a href="/signin?backURL=/member">我有账号, 放我进去!</a></div>
			</form>
		<% end_with %>
	</div>
</div>