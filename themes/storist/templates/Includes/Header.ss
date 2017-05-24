<header id="header" class="grey-dark">
    <h1 class="title is-3 has-text-centered grey-darker">NZ YOGO</h1>
    <div class="current-member">
        <h2 class="title is-4 has-text-centered is-normal">$CurrentMember.FirstName<% if $CurrentMember.Surname %> $CurrentMember.Surname<% end_if %></h2>
        <% if $CurrentMember.ClassName == 'Supplier' %>
        <p class="subtitle is-6 has-text-centered font-white-ter">$CurrentMember.TradingName</p>
        <% end_if %>
    </div>
    <% include Navigation %>
    <div class="contact-button-wrapper has-text-centered">
        <a class="button is-danger is-outlined" href="#">Contact</a>
    </div>
</header>
