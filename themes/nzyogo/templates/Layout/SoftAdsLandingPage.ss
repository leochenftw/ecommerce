<div class="section hero">
    <div class="hero-body">
    	<div class="container has-text-centered">
    		<h1 class="title is-3 is-bold">$Title</h1>
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
<div id="latest-update" class="section">
	<div class="container latest-updates">
        <div class="columns wrap">
    		<% loop $Blogs %>
    			<% include BlogTile inList=true %>
    		<% end_loop %>
        </div>
	</div>
</div>
