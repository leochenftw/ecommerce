<div class="plugable-content content $EvenOdd">
    <div class="columns">
    	<div class="column image<% if $EvenOdd == 'even' %> order-2<% end_if %>">$Image.SetWidth(575)</div>
    	<div class="column text<% if $EvenOdd == 'even' %> order-1<% end_if %>">
    		<h2 class="title is-4 is-bold">$Title</h2>
    		<div class="content">$Content</div>
    	</div>
    </div>
</div>
