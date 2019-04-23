<!-- BEGIN: main -->

<form id="nvtools_module" action="{NV_BASE_SITEURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<table class="tab1">
		<tbody>
			<tr>
				<td> Tên bảng </td>
				<td>
				<input type="text" name="tablename" style="width:250px;" value="{TABLENAME}" />
				</td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td> Chức năng </td>
				<td>
				<select name="func">
					<option value="pdo_insert">pdo_insert</option>
					<option value="pdo_update">pdo_update</option>
					<option value="html_admin">html_admin</option>
				</select></td>
			</tr>
		</tbody>
	</table>
	<div style="text-align: center">
		<input id="submitmod" type="submit" value="Thực hiện" />
	</div>
	<br>
	<textarea name="txtcode" style="width: 100%;height: 500px;">{TXTCODE}</textarea>
</form>

<!-- END: main -->
