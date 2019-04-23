<!-- BEGIN: main -->
<!-- BEGIN: tablename -->
<form action="{NV_BASE_SITEURL}index.php" method="get">
    <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}"/>
    <input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}"/>
    <h3>Chức năng export cấu trúc dữ liệu ra bảng mô tả</h3>
    <table class="table table-striped table-bordered table-hover">
        <tbody>
            <tr>
                <td style="width: 200px;">Chọn module:</td>
                <td>
                    <select name="modname" class="d-inline-block form-control w300">
                        <option value=""> -- chọn module -- </option>
                        <!-- BEGIN: modname -->
                        <option value="{MODNAME.value}" {MODNAME.selected}>{MODNAME.value}</option>
                        <!-- END: modname -->
                    </select>
                </td>
            </tr>
            <tr>
                <td></td>
                <td><input class="btn btn-primary" type="submit" value="Thực hiện" /></td>
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
                <th style="width: 100px;" class="text-center">STT</th>
                <th>Bảng cơ sở dữ liệu</th>
                <th style="width: 100px;" class="text-center">
                    <input type="checkbox" name="check_all[]" value="yes" onclick="nv_checkAll(this.form, 'export_structure[]', 'check_all[]',this.checked);"/>
                </th>
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: item -->
            <tr id="column_{COLUMN.column_name}">
                <td class="text-center">{ITEM.stt}</td>
                <td>{ITEM.name}</td>
                <td class="text-center"><input type="checkbox" name="export_structure[]" value="{ITEM.name}" {ITEM.checked} onclick="nv_UncheckAll(this.form, 'export_structure[]', 'check_all[]', this.checked);"/></td>
            </tr>
            <!-- END: column -->
        </tbody>
        <tfoot>
            <tr>
                <td align="center" colspan="3"><input class="btn btn-primary" type="submit" name="submit" value="Thực hiện" /></td>
            </tr>
        </tfoot>
    </table>
</form>
<!-- END: form -->
<!-- END: main -->