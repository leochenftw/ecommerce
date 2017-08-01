<h2 class="title">管理收货地址</h2>
<table class="addresses as-table full-width">
	<tr>
		<th class="addresses__is-default">首选</th>
		<th class="addresses__details">地址信息</th>
		<th class="addresses__actions">操作</th>
	</tr>
<% loop $Addresses %>
	<tr>
		<td class="addresses__is-default"><input data-addr-id="$ID" name="isDefault" id="is-default-{$ID}" type="checkbox" <% if $isDefault %>checked<% end_if %> /><label for="is-default-{$ID}" class="icon"><% if $isDefault %><i class="fa fa-check-square-o"></i><% else %><i class="fa fa-square-o"></i><% end_if %></label></td>
		<td class="addresses__details">
			<strong>收件人</strong>: $FirstName $Surname<br />
			<strong>地址</strong>: $Title<br />
			<% if $PostCode %><strong>邮编</strong>: $PostCode<br /><% end_if %>
			<strong>电话/手机</strong>: $Phone<br />
			<strong>电子邮件</strong>: $Email
		</td>
		<td class="addresses__actions"><a href="#" class="btn-edit">修改</a> | <a href="#" class="btn-delete">删除</a></td>
	</tr>
<% end_loop %>
</table>
