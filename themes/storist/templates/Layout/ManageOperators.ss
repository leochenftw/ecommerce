<section class="section transparent operators">
    <header class="columns vertical-center">
        <div class="column is-6"><h2 class="title is-2">Operators</h2></div>
        <div class="column has-text-right account-tools is-paddingless">
            <a href="$Link('account')" class="font-blue"><span class="icon"><i class="fa fa-cog"></i></span>Manage account</a>
            <a id="btn-new-operator" href="#" class="font-blue"><span class="icon"><i class="fa fa-plus-circle"></i></span>New operator</a>
        </div>
    </header>

    <div class="operator-list">
        <% loop $Operators %>
            <div class="columns operator is-marginless" data-id="$ID" data-first-name="$FirstName" data-surname="$Surname" data-email="$Email">
                <div class="column is-auto-width"><span class="icon"><i class="fa fa-user"></i></span></div>
                <div class="column">$FirstName $Surname</div>
                <div class="column">$Email</div>
            </div>
        <% end_loop %>
    </div>
</section>
