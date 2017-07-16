<% with $UpcomingGroupon %>
<section id="up-coming" class="section is-relative parallax-window" data-parallax="scroll" data-image-src="$Product.Poster.URL">
	<div class="container">
        <div class="columns">
            <div class="column is-8 is-offset-4">
        		<header class="has-text-centered">
        			<h2 class="title is-1">$Product.Title</h2>
        			<p class="subtitle is-2">距离抢购活动开始还有</p>
        		</header>
        		<div class="up-coming__countdown has-text-centered">
        			<time class="start-at hide" datetime="$Start.Format('D M d Y H:i:s O')">$TilStart</time>
        			<div class="row">
        				<% with $getTilStart(1) %>
        				<div class="up-coming__countdown__days time-cell">
        					<span class="value">$Days</span>
        					<span class="sub">Day<% if $Days > 1 %>s<% end_if %></span>
        				</div>
        				<div class="up-coming__countdown__hours time-cell">
        					<span class="value">$Hours</span>
        					<span class="sub">Hour<% if $Hours > 1 %>s<% end_if %></span>
        				</div>
        				<div class="up-coming__countdown__minutes time-cell">
        					<span class="value">$Minutes</span>
        					<span class="sub">Min<% if $Minutes > 1 %>s<% end_if %></span>
        				</div>
        				<div class="up-coming__countdown__seconds time-cell">
        					<span class="value">$Seconds</span>
        					<span class="sub">Sec<% if $Seconds > 1 %>s<% end_if %></span>
        				</div>
        				<% end_with %>
        			</div>
        		</div>
        		<div class="up-coming__call-to-action call-to-action-wrapper has-text-centered">
                    $Product.SubscribeForm('先关注')
        		</div>
            </div>
        </div>
	</div>
</section>
<% end_with %>
