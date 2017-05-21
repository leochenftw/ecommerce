<div class="section hero">
	<div class="container">
		<h1>$Title</h1>
		<div class="breadcrumb">$Breadcrumbs</div>
	</div>
</div>

<div class="section">
	<div class="container">
		<% with $SigninForm %>
			<form $FormAttributes>
				<% if $Message %>
				<div class="message-wrapper error">$Message</div>
				<% end_if %>
				<div class="fields">
					$Fields
					$Actions.Last
				</div>
				<div class="Actions">
					$Actions.First
				</div>
				<div class="lnk-signup-wrapper margin-h-10-0-0 text-center"><a href="/signup">没有账号? 马上注册</a></div>
			</form>
		<% end_with %>
	</div>
</div>
