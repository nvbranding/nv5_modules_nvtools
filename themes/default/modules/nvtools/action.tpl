<!-- BEGIN: main -->
<!-- BEGIN: tablename -->
<form action="{NV_BASE_SITEURL}index.php" method="get">
	<input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}"/>
	<input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}"/>
	<h3>Chức năng tạo lại file action cho module dựa vào CSDL sử dụng cho NukeViet 4</h3>
	<table class="table table-striped table-bordered table-hover">
		<tbody>
			<tr>
				<td>Chọn module:</td>
				<td>
				<select name="modname" class="form-control">
					<option value=""> -- chọn module -- </option>
					<!-- BEGIN: modname -->
					<option value="{MODNAME.value}" {MODNAME.selected}>{MODNAME.value}</option>
					<!-- END: modname -->
				</select></td>
			</tr>
			<tr>
				<td colspan="2" class="text-center"><input class="btn btn-primary" type="submit" value="Thực hiện" /></td>
			</tr>
		</tbody>
	</table>
</form>
<!-- END: tablename -->

<!-- BEGIN: form -->
<form action="{NV_BASE_SITEURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}" method="post">
	<input type="hidden" name="modname" value="{MODNAME}"/>
	<table class="table table-striped table-bordered table-hover">
		<caption>
			Các bảng CSDL thuộc module: {MODNAME}
		</caption>
		<thead>
			<tr>
				<th>STT</th>
				<th>Bảng cơ sở dữ liệu</th>
				<th class="text-center"> Xuất cấu trúc
				<br />
				<input type="checkbox" name="check_all[]" value="yes" onclick="nv_checkAll(this.form, 'export_structure[]', 'check_all[]',this.checked);"/></th>
				<th class="text-center">Xuất dữ liệu
				<br />
				<input type="checkbox" name="check_all_data[]" value="yes"  onclick="nv_checkAll(this.form, 'export_data[]', 'check_all_data[]',this.checked);"/></th>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: item -->
			<tr id="column_{COLUMN.column_name}">
				<td>{ITEM.stt}</td>
				<td>{ITEM.name}</td>
				<td align="center"><input type="checkbox" name="export_structure[]" value="{ITEM.name}" {ITEM.checked} onclick="nv_UncheckAll(this.form, 'export_structure[]', 'check_all[]', this.checked);"/></td>
				<td align="center"><input type="checkbox" name="export_data[]" value="{ITEM.name}" {ITEM.checkeddata} onclick="nv_UncheckAll(this.form, 'export_data[]', 'check_all_data[]',this.checked);"/></td>
			</tr>
			<!-- END: item -->
		</tbody>
		<tfoot>
			<tr>
				<td colspan="2">Xuất dữ liệu cấu hình của module:</td>
				<td colspan="2"><input type="checkbox" name="data_config" value="1" {CHECKEDCONFIG} /></td>
			</tr>
			<tr>
				<td colspan="2">Xem bảng *_config là bảng cấu hình <small>(Dữ liệu sẽ ghi vào file action thay vì file dữ liệu mẫu)</small>:</td>
				<td colspan="2"><input type="checkbox" name="data_auto_config" value="1" {CHECKEDAUTOCONFIG} /></td>
			</tr>
			<tr>
				<td colspan="2">Chọn thư mục lưu file:</td>
				<td colspan="2">
					<!-- BEGIN: folder -->
					<input type="radio" name="folder" value="{OPTION.key}" {OPTION.checked} /> {OPTION.title}
					<br />
					<!-- END: folder -->
				</td>
			</tr>
			<tr>
				<td align="center" colspan="4">
                    <input class="btn btn-primary" type="submit" name="submit" value="Thực hiện" />
                    <a class="btn btn-default" href="{NV_BASE_SITEURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}"><span class="text-black">Quay lại chọn Module</span></a>
                </td>
			</tr>
		</tfoot>
	</table>
</form>
<!-- END: form -->
<!-- END: main -->