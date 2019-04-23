<!-- BEGIN: main -->
<!-- BEGIN: tablename -->
<form action="{NV_BASE_SITEURL}index.php" method="get">
    <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}"/>
    <input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}"/>
    <h3>Chức năng thêm mới block sử dụng cho NukeViet 4</h3>
    <table class="table table-striped table-bordered table-hover">
        <tbody>
            <tr>
                <td style="width: 200px;"> Kiểu Block</td>
                <td>
                    <select name="blocktheme" class="form-control d-inline-block w300">
                        <option value=""> Block của module </option>
                        <!-- BEGIN: theme_list -->
                        <option value="{THEME_LIST.value}" {THEME_LIST.selected}>Giao diện: {THEME_LIST.value}</option>
                        <!-- END: theme_list -->
                    </select> Lựa chọn xem block được lưu vào module hay lưu vào giao diện
                </td>
            </tr>
            <tr>
                <td>Chọn module:</td>
                <td>
                    <select name="modname" class="form-control d-inline-block w300">
                        <option value=""> -- Chọn module -- </option>
                        <!-- BEGIN: modname -->
                        <option value="{MODNAME.value}" {MODNAME.selected}>{MODNAME.value}</option>
                        <!-- END: modname -->
                    </select> Nếu block module thì chọn module sẽ lưu. Nếu block giao diện thì chọn module sẽ xử lý thông tin, bỏ trống để chỉ tạo block mà không kết nối với module nào.
                </td>
            </tr>
            <tr>
                <td> Bảng CSDL</td>
                <td>
                    <select name="tablename" class="form-control d-inline-block w300">
                        <option value=""> -- Chọn bảng dữ liệu -- </option>
                        <!-- BEGIN: loop -->
                        <option value="{MODNAME.value}" {MODNAME.selected}>{MODNAME.value}</option>
                        <!-- END: loop -->
                    </select> Chọn bảng dữ liệu sẽ lấy dữ liệu. Bỏ trống để tạo block không truy vấn CSDL
                </td>
            </tr>
            <tr>
                <td> Block Global:</td>
                <td>
                    <label><input type="checkbox" name="blockglobal" value="1" {BLOCKGLOBALCHECK} data-value="0"/> Block global hay module</label>
                </td>
            </tr>
            <tr>
                <td> Block Setting:</td>
                <td>
                    <label><input type="checkbox" name="blocksetting" value="1" {BLOCKSETTINGCHECK}/> Block có cấu hình riêng</label>
                </td>
            </tr>
            <!-- BEGIN: othertheme -->
            <tr id="area-other-theme">
                <td> Lưu thêm giao diện vào:</td>
                <td>
                    <span class="help-block">Giao diện mặc định luôn luôn được lưu. Hệ thống chỉ lưu thêm ở các giao diện chọn dưới đây nếu giao diện đó có tùy biến module.</span>
                    <div class="row">
                        <!-- BEGIN: loop -->
                        <div class="col-xs-12 col-sm-8 col-md-8">
                            <label><input type="checkbox" name="theme_others[]" value="{THEME_OTHER.value}"{THEME_OTHER.checked}> {THEME_OTHER.value}</label>
                        </div>
                        <!-- END: loop -->
                    </div>
                </td>
            </tr>
            <!-- END: othertheme -->
            <tr>
                <td>Tên Block:</td>
                <td><input class="form-control d-inline-block w300" required="required" oninvalid="setCustomValidity('Dữ liệu này là bắt buộc chỉ dùng các ký tự a-z 0-9 và gạch dưới')" oninput="setCustomValidity('')" pattern="^[a-z0-9_]*$"  type="text" name="blockname" style="width:150px;" value="{BLOCKNAME}" /></td>
            </tr>
            <tr>
                <td colspan="2" class="text-center"><input class="btn btn-primary" type="submit" value="Thực hiện" /></td>
            </tr>
        </tbody>
    </table>
</form>
<script type="text/javascript">
    $('select[name=modname]').change(function() {
        $("select[name=tablename]").load("{NV_BASE_SITEURL}index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=addfun&loadmodname=" + $(this).val() + "&nocache=" + new Date().getTime(), function() {
        	$("select[name=tablename]").change();
        });
    });
    $('select[name=tablename]').change(function() {
        var r_split = $(this).val().split('_');
        len = r_split.length - 1;
        $("input[name=blockname]").val(r_split[len]);
    });
    $('select[name=blocktheme]').change(function() {
        var blocktype = $(this).val();
        if (blocktype == '') {
        	if ($('#area-other-theme').length) {
        		$('#area-other-theme').show();
        	}
        	$('[name="blockglobal"]').prop('disabled', false);
        	$('[name="blockglobal"]').prop('checked', $('[name="blockglobal"]').data('value'));
        } else {
            if ($('#area-other-theme').length) {
                $('#area-other-theme').hide();
            }
        	$('[name="blockglobal"]').prop('disabled', true);
        	$('[name="blockglobal"]').prop('checked', true);
        }
    });
    $('[name="blockglobal"]').change(function() {
    	if ($(this).is(':checked')) {
    		$(this).data('value', 1);
    	} else {
    		$(this).data('value', 0);
    	}
    });
</script>
<!-- END: tablename -->

<!-- BEGIN: form -->
<form action="{NV_BASE_SITEURL}index.php" method="post">
    <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}"/>
    <input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}"/>
    <input type="hidden" name="blocktheme" value="{BLOCKTHEME}"/>
    <input type="hidden" name="modname" value="{MODNAME}"/>
    <input type="hidden" name="tablename" value="{TABLENAME}"/>
    <!-- BEGIN: theme_other -->
    <input type="hidden" name="theme_other[]" value="{THEME_OTHER}">
    <!-- END: theme_other -->
    <table class="table table-striped table-bordered table-hover">
        <caption>
            Tạo block cho module {MODNAME} từ bảng: {TABLENAME}
        </caption>
        <thead>
            <tr>
                <th>Tên cột</th>
                <th>Loại dữ liệu</th>
                <th>Chọn các trường trong câu truy vấn SQL</th>
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: column -->
            <tr id="column_{COLUMN.column_name}">
                <td>{COLUMN.column_name}</td>
                <td>{COLUMN.data_type}</td>
                <td>
                    <select name="views[{COLUMN.column_name}]}" class="form-control d-inline-block w300">
                        <option value=""> ---- </option>
                        <!-- BEGIN: field_type -->
                        <option value="{FIELD_TYPE.key}" {FIELD_TYPE.selected}>{FIELD_TYPE.value}</option>
                        <!-- END: field_type -->
                    </select>
                </td>
            </tr>
            <!-- END: column -->
        </tbody>
    </table>
    <table class="table table-striped table-bordered table-hover">
        <caption>
            Các chức năng của block
        </caption>
        <colgroup>
            <col style="width: 30%" />
        </colgroup>
        <tbody>
            <tr>
                <td>Tên Block:</td>
                <td><input class="form-control" required="required" oninvalid="setCustomValidity('Dữ liệu này là bắt buộc chỉ dùng các ký tự a-z 0-9 và gạch dưới')" oninput="setCustomValidity('')"  pattern="^[a-z0-9_]*$"  type="text" name="blockname" value="{BLOCKNAME}" /></td>
            </tr>
            <tr>
                <td> Block Global:</td>
                <td><input type="checkbox" name="blockglobal" value="1"  {BLOCKGLOBALCHECK}{BLOCK_GLOBAL_DISABLED}/></td>
            </tr>
            <tr>
                <td> Block Setting:</td>
                <td><input type="checkbox" name="blocksetting" value="1"  {BLOCKSETTINGCHECK}/></td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td align="center" colspan="2"><input class="btn btn-primary" type="submit" value="Thực hiện" /></td>
            </tr>
        </tfoot>
    </table>
</form>
<!-- END: form -->
<!-- END: main -->