<div class="section hero">
    <div class="hero-body">
    	<div class="container has-text-centered">
            <h1 class="title is-3 is-bold">
                <% if $ClassName == 'ProductLandingPage' %>
                    商店
                <% else %>
                    <% if $Link == '/DashboardController/' %>
                        <% if $CurrentUser.isEnglish %>$CurrentUser.FirstName $CurrentUser.Surname<% else %>$CurrentUser.Surname$CurrentUser.FirstName<% end_if %>
                    <% else %>
                        <% if $Top.Language == 'Chinese' %><% if $Chinese %>$Chinese<% else %>$Title<% end_if %><% else %>$Title<% end_if %>
                    <% end_if %>
                <% end_if %>
            </h1>
            <nav class="breadcrumb is-centered">
                <ul>
                <% loop $Crumbs %>
                    <% if $Link %>
                        <li><a href="$Link">$Title</a></li>
                    <% else %>
                        <li class="is-active"><a>$Title</a></li>
                    <% end_if %>
                <% end_loop %>
                </ul>
            </nav>
    	</div>
    </div>
</div>
