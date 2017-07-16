<h2 class="title">购买历史</h2>
<table class="orders as-table full-width">
	<tr>
		<th class="orders__id">订单号</th>
		<th class="orders__date">日期</th>
		<th class="orders__price">价格</th>
		<th class="orders__freight">运费</th>
		<th class="orders__freight">运单号</th>
		<th class="orders__status">状态</th>
		<th class="orders__actions">操作</th>
	</tr>
<% loop $Orders %>
	<tr>
		<td class="orders__id">$Title</td>
		<td class="orders__date">$Created.Date</td>
		<td class="orders__price">${$Sum}</td>
		<td class="orders__freight">${$FrieghtCost}</td>
		<td class="orders__freight">
			<% if $Shipments %>

			<% else %>
				---
			<% end_if %>
		</td>
		<td class="orders__status">$NiceProgress</td>
		<td><a data-title="会员中心 | 查看账单" class="ajax-routed orders__actions" href="/member/action/orders/$ID">查看</a></td>
	</tr>
<% end_loop %>
</table>
