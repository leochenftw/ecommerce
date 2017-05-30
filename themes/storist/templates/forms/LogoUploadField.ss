<div id="{$form.name.LowerCase}-{$name.LowerCase}-uploader" class="field upload ss-upload ss-uploadfield">
    <% if $canUpload || $canAttachExisting %>
        <div class="ss-uploadfield-item ss-uploadfield-addfile<% if $CustomisedItems %> borderTop<% end_if %>">
            <% if canUpload %>
            <div class="ss-uploadfield-item-preview simple-previewable-holder">
                <% if $CurrentMember.Logo %>
                    $CurrentMember.Logo
                <% else %>
                    <img class="default-avatar" src="/assets/Uploads/nzyogo.png" width="144" height="40" />
                <% end_if %>
            </div>
            <% end_if %>
            <div class="ss-uploadfield-item-info">

                <% if $canUpload %>
                    <label class="ss-uploadfield-fromcomputer ss-ui-button ui-corner-all" title="<% _t('UploadField.FROMCOMPUTERINFO', 'Upload from your computer') %>" data-icon="drive-upload">
                        <input data-destination="simple-previewable-holder" accept="image/*" id="$id" name="{$Name}[Uploads][]" class="$extraClass ss-uploadfield-fromcomputer-fileinput simple-previewable" data-config="$configString" type="file"<% if $multiple %> multiple="multiple"<% end_if %> />
                    </label>
                <% else %>
                    <input accept="image/*" id="$id" name="{$Name}[Uploads][]" class="$extraClass ss-uploadfield-fromcomputer-fileinput" data-config="$configString" type="hidden" />
                <% end_if %>

                <% if $canAttachExisting %>
                    <button class="ss-uploadfield-fromfiles ss-ui-button ui-corner-all" title="<% _t('UploadField.FROMCOMPUTERINFO', 'Select from files') %>" data-icon="network-cloud"><% _t('UploadField.FROMFILES', 'From files') %></button>
                <% end_if %>
                <% if $canUpload %>
                    <% if not $autoUpload %>
                        <button style="display:none;" class="ss-uploadfield-startall ss-ui-button ui-corner-all" data-icon="navigation"><% _t('UploadField.STARTALL', 'Start all') %></button>
                    <% end_if %>
                <% end_if %>
                <div class="clear"><!-- --></div>
            </div>
            <div class="clear"><!-- --></div>
        </div>
        <div class="has-text-left">
			<a href="#" class="button is-info is-outlined btn-file-browser">Choose image...</a>
		</div>
    <% end_if %>
</div>
