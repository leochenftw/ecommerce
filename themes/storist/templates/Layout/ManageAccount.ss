<section class="section transparent account">
    <header class="columns vertical-center">
        <div class="column is-6"><h2 class="title is-2">Account</h2></div>
        <div class="column has-text-right account-tools is-paddingless">
            <a href="$Link('operators')" class="font-blue"><span class="icon"><i class="fa fa-users"></i></span>Manage Operators</a>
        </div>
    </header>
    <% with $SupplierEditForm %>
        <form $FormAttributes>
            <% if $Message %>
                <div class="message-wrapper has-text-centered $Message.MessageType">$Message</div>
            <% end_if %>
            <div class="business-info">
                <h3 class="title is-3">Business settings</h3>
                <div class="columns">
                    <label class="column is-4"><label>Logo</label></label>
                    <div class="column is-4">
                        $Fields.fieldByName('Logo').FieldHolder
                    </div>
                </div>
                <div class="columns">
                    <label class="column is-4"><label for="SupplierEditForm_SupplierEditForm_TradingName">Trading name</label></label>
                    <div class="column is-4">
                        $Fields.fieldByName('TradingName')
                    </div>
                </div>
                <div class="columns">
                    <label class="column is-4"><label for="SupplierEditForm_SupplierEditForm_NZLocation">Trading location</label></label>
                    <div class="column is-4">
                        $Fields.fieldByName('NZLocation')
                    </div>
                </div>
                <div class="columns">
                    <label class="column is-4"><label for="SupplierEditForm_SupplierEditForm_GST">GST number</label></label>
                    <div class="column is-4">
                        $Fields.fieldByName('GST')
                    </div>
                </div>
                <div class="columns">
                    <label class="column is-4"><label for="SupplierEditForm_SupplierEditForm_ContactNumber">Contact number</label></label>
                    <div class="column is-4">
                        $Fields.fieldByName('ContactNumber')
                    </div>
                </div>
            </div>
            <div class="peronsal-info">
                <h3 class="title is-3">Personal settings</h3>
                <div class="columns">
                    <label class="column is-4"><label for="SupplierEditForm_SupplierEditForm_FirstName">First name</label></label>
                    <div class="column is-4">
                        $Fields.fieldByName('FirstName')
                    </div>
                </div>
                <div class="columns">
                    <label class="column is-4"><label for="SupplierEditForm_SupplierEditForm_Surname">Last name</label></label>
                    <div class="column is-4">
                        $Fields.fieldByName('Surname')
                    </div>
                </div>
                <div class="columns">
                    <label class="column is-4"><label for="SupplierEditForm_SupplierEditForm_Email">Email address</label></label>
                    <div class="column is-4">
                        $Fields.fieldByName('Email')
                        <% if $Fields.fieldByName('Email').Message %>
                            <p class="subtitle is-6 font-red">$Fields.fieldByName('Email').Message</p>
                        <% end_if %>
                    </div>
                </div>
                <div class="columns">
                    <label class="column is-4"><label for="SupplierEditForm_SupplierEditForm_Email">Update password</label></label>
                    <div class="column is-4 $isBadPassword">
                        $Fields.fieldByName('Password')
                        <% if $Fields.fieldByName('Password').Message %>
                            <p class="subtitle is-6 font-red">$Fields.fieldByName('Password').Message</p>
                        <% else %>
                            <p class="subtitle is-6">Leave empty if don't wish to change</p>
                        <% end_if %>
                    </div>
                </div>
                <div class="columns">
                    <label class="column is-4"><label for="SupplierEditForm_SupplierEditForm_Phone">Contact number</label></label>
                    <div class="column is-4">
                        $Fields.fieldByName('Phone')
                    </div>
                </div>
            </div>
            $Fields.fieldByName('SecurityID').FieldHolder
            <div class="actions columns">
                <div class="column is-4 is-offset-4 has-text-centered">$Actions</div>
            </div>
        </form>
    <% end_with %>
</section>
