<!-- BEGIN: main -->
<!-- BEGIN: tablename -->
<form action="{FORM_ACTION}" method="get">
    <!-- BEGIN: no_rewrite -->
    <input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}" /> 
    <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" /> 
    <input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />
    <!-- END: no_rewrite -->
    <h3>Chức năng thêm mới fuction cho module dựa vào CSDL sử dụng cho NukeViet 4</h3>
    <table class="table table-striped table-bordered table-hover">
        <tbody>
            <tr>
                <td>Lưu vào module:</td>
                <td>
                    <select name="modname" class="form-control w300 d-inline-block">
                        <option value="">-- chọn module --</option>
                        <!-- BEGIN: modname -->
                        <option value="{MODNAME.value}"{MODNAME.selected}>{MODNAME.value}</option>
                        <!-- END: modname -->
                    </select>
                </td>
            </tr>
            <tr>
                <td>Bảng CSDL</td>
                <td>
                    <select name="tablename" class="form-control w300 d-inline-block">
                        <option value="">-- chọn bảng dữ liệu --</option>
                        <!-- BEGIN: loop -->
                        <option value="{MODNAME.value}"{MODNAME.selected}>{MODNAME.value}</option>
                        <!-- END: loop -->
                    </select>
                </td>
            </tr>
            <tr>
                <td>Tên function:</td>
                <td>
                    <input class="form-control w300 d-inline-block" required="required" oninvalid="setCustomValidity('Dữ liệu này là bắt buộc chỉ dùng các ký tự a-z 0-9 và gạch dưới')" oninput="setCustomValidity('')" pattern="^[a-z0-9_]+$" type="text" name="funname" value="{FUNNAME}" />
                </td>
            </tr>
            <tr>
                <td>Tiêu đề tiếng Anh</td>
                <td>
                    <label><input type="checkbox" name="setlangvi" value="1" checked="checked" /> Sử dụng tên cột và bỏ dấu "_"</label>
                </td>
            </tr>
            <tr>
                <td>Tiêu đê tiếng Việt</td>
                <td>
                    <label><input type="checkbox" name="setlangen" value="1" checked="checked" /> Sử dụng comment của cột</label>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="text-center">
                    <input class="btn btn-primary" type="submit" value="Thực hiện" />
                </td>
            </tr>
        </tbody>
    </table>
</form>
<script type="text/javascript">
    $('select[name=modname]').change(function() {
        $("select[name=tablename]").load("{NV_BASE_SITEURL}index.php?" + nv_name_variable + "="
            + nv_module_name + "&" + nv_fc_variable
            + "=addfun&loadmodname=" + $(this).val()
            + "&nocache=" + new Date().getTime(), function() {
            $("select[name=tablename]").change();
        });
    });
    $('select[name=tablename]').change(function() {
        var r_split = $(this).val().split('_');
        len = r_split.length - 1;
        $("input[name=funname]").val(r_split[len]);
    });
</script>
<!-- END: tablename -->

<!-- BEGIN: form -->
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->
<form action="{FORM_ACTION}" method="post">
    <table class="table table-striped table-bordered table-hover">
        <caption>
            Tạo form module {MODNAME} <i class="fa fa-angle-double-right" aria-hidden="true"></i> {FUNNAME} từ bảng: {TABLENAME}
        </caption>
        <thead>
            <tr>
                <th>Tên cột</th>
                <th>Loại dữ liệu</th>
                <th>Kiểu hiển thị trong Form</th>
                <th>Bắt buộc</th>
                <th>Hidden</th>
                <th>List</th>
                <th>Tiêu đề tiếng Việt</th>
                <th>Tiêu đề tiếng Anh</th>
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: column -->
            <tr id="column_{COLUMN.column_name}">
                <td>{COLUMN.column_name}</td>
                <td>{COLUMN.data_type}</td>
                <td>
                    <select name="views[{COLUMN.column_name}]" onchange="nv_change('{COLUMN.column_name}');" class="form-control">
                        <!-- BEGIN: field_type -->
                        <option value="{FIELD_TYPE.key}" name="{FIELD_TYPE.value}"{FIELD_TYPE.selected}>{FIELD_TYPE.value}</option>
                        <!-- END: field_type -->
                    </select>
                </td>
                <td align="center">
                    <input type="checkbox" name="requireds[{COLUMN.column_name}]" value="1" {COLUMN.required_checked}/>
                </td>
                <td align="center">
                    <input type="checkbox" name="hiddens[{COLUMN.column_name}]" value="1" {COLUMN.hidden_checked}/>
                </td>
                <td align="center">
                    <input type="checkbox" name="listviews[{COLUMN.column_name}]" value="1" {COLUMN.listview_checked}/>
                </td>
                <td>
                    <input required="required" type="text" name="title_vi[{COLUMN.column_name}]" value="{COLUMN.title_vi}" class="form-control"/>
                </td>
                <td>
                    <input required="required" type="text" name="title_en[{COLUMN.column_name}]" value="{COLUMN.title_en}" class="form-control"/>
                </td>
            </tr>
            <tr id="{COLUMN.column_name}">
                <td colspan="8">
                    <table class="table table-striped table-bordered table-hover" id="choicetypes_{COLUMN.column_name}">
                        <tr>
                            <td>{LANG.field_choicetypes_title}</td>
                            <td>
                                <!-- BEGIN: choicetypes_add -->
                                <select class="form-control" name="choicetypes_[{COLUMN.column_name}]">
                                    <!-- BEGIN: choicetypes -->
                                    <option {CHOICE_TYPES.selected} value="{CHOICE_TYPES.key}">{CHOICE_TYPES.value}</option>
                                    <!-- END: choicetypes -->
                                </select>
                                <!-- END: choicetypes_add -->
                                <!-- BEGIN: choicetypes_add_hidden -->
                                {FIELD_TYPE_SQL} <input type="hidden" name="choicetypes" value="{choicetypes_add_hidden}" />
                                <!-- END: choicetypes_add_hidden -->
                            </td>
                        </tr>
                    </table>
                    <table class="table table-striped table-bordered table-hover" id="choicesql_{COLUMN.column_name}">
                        <caption>
                            <em class="fa fa-file-text-o">&nbsp;</em>{LANG.field_options_choicesql}
                        </caption>
                        <thead>
                            <tr>
                                <th>{LANG.field_options_choicesql_module}</th>
                                <th>{LANG.field_options_choicesql_table}</th>
                                <th>{LANG.field_options_choicesql_column}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <span id="choicesql_module_{COLUMN.column_name}">&nbsp;</span>
                                </td>
                                <td>
                                    <span id="choicesql_table_{COLUMN.column_name}">&nbsp;</span>
                                </td>
                                <td>
                                    <span id="choicesql_column_{COLUMN.column_name}">&nbsp;</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table table-striped table-bordered table-hover" id="choiceitems_{COLUMN.column_name}">
                        <caption>
                            <em class="fa fa-file-text-o">&nbsp;</em>{LANG.field_options_choice}
                        </caption>
                        <colgroup>
                            <col class="w250" />
                            <col span="3" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th class="text-center">{LANG.field_number}</th>
                                <th class="text-center">{LANG.field_value}</th>
                                <th class="text-center">{LANG.field_text}</th>
                                <th class="text-center">{LANG.field_default_value}</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <td colspan="4">
                                    <input style="margin-left: 50px;" type="button" value="{LANG.field_add_choice}" onclick="nv_choice_fields_additem('{COLUMN.column_name}');" />
                                </td>
                            </tr>
                        </tfoot>
                        <tbody>
                            <!-- BEGIN: loop_field_choice -->
                            <tr class="text-center">
                                <td>{FIELD_CHOICES.number}</td>
                                <td>
                                    <input class="form-control w100 validalphanumeric" type="text" value="{FIELD_CHOICES.key}" name="field_choice[{COLUMN.column_name}][{FIELD_CHOICES.number}]" />
                                </td>
                                <td>
                                    <input class="form-control w350" type="text" value="{FIELD_CHOICES.value}" name="field_choice_text[{COLUMN.column_name}][{FIELD_CHOICES.number}]" />
                                </td>
                                <td>
                                    <input type="radio" {FIELD_CHOICES.checked} value="{FIELD_CHOICES.number}" name="default_value_choice[{COLUMN.column_name}]">
                                </td>
                            </tr>
                            <!-- END: loop_field_choice -->
                        </tbody>
                    </table>
                </td>
            </tr>
            <script type="text/javascript">
            $(window).on('load', function() {
                $("#choicetypes_{COLUMN.column_name}").hide();
                $("#choicesql_{COLUMN.column_name}").hide();
                $("#choiceitems_{COLUMN.column_name}").hide();
            });
    
            $("select[name='choicetypes_[{COLUMN.column_name}]']").change(function() {
                nv_users_check_choicetypes(this, '{COLUMN.column_name}');
            });

            var items = '{FIELD_CHOICES_NUMBER}';
            function nv_choice_fields_additem(column) {
                items++;
                var newitem = '<tr class="text-center">';
                newitem += '    <td>' + items + '</td>';
                newitem += '    <td><input class="form-control w100 validalphanumeric" type="text" value="" name="field_choice[' + column + '][' + items + ']"></td>';
                newitem += '    <td><input class="form-control"     clas="w350" type="text" value="" name="field_choice_text[' + column + '][' + items + ']"></td>';
                newitem += '    <td><input type="radio" value="' + items + '" name="default_value_choice[' + column + ']"></td>';
                newitem += '    </tr>';
                $("#choiceitems_" + column).append(newitem);
            }

            function nv_change(column) {
                var field_type = $("select[name='views[" + column + "]'] option:selected").val();
                $("#choicetypes_" + column).hide();
                $("#choicesql_" + column).hide();
                $("#choiceitems_" + column).hide();
                if (field_type == 'select' || field_type == 'radio' || field_type == 'checkbox') {
                    $("#choicetypes_" + column).show();
                    nv_users_check_choicetypes("select[name='choicetypes_[" + column + "]']", column);
                }
            }

            function nv_users_check_choicetypes(elemnet, column) {
                var choicetypes_val = $(elemnet).val();
                if (choicetypes_val == "field_choicetypes_text") {
                    $("#choiceitems_" + column).show();
                    $("#choicesql_" + column).hide();
                } else {
                    $("#choiceitems_" + column).hide();
                    $("#choicesql_" + column).show();
                    nv_load_sqlchoice('module', '', column);
                }
            }

            function nv_load_sqlchoice(choice_name_select, choice_seltected, column) {
                var getval = "";

                if (choice_name_select == "table") {
                    var choicesql_module = $("select[name='choicesql_module[" + column + "]']").val();
                    var module_selected = (choicesql_module == "" || choicesql_module == undefined) ? '{SQL_DATA_CHOICE.0}' : choicesql_module;
                    getval = "&module=" + module_selected;
                    $("#choicesql_column_" + column).html("");
                } else if (choice_name_select == "column") {
                    var choicesql_module = $("select[name='choicesql_module[" + column + "]']").val();
                    var module_selected = (choicesql_module == "" || choicesql_module == undefined) ? '{SQL_DATA_CHOICE.0}' : choicesql_module;
                    var choicesql_table = $("select[name='choicesql_table[" + column + "]']").val();
                    var table_selected = (choicesql_table == "" || choicesql_table == undefined) ? '{SQL_DATA_CHOICE.1}' : choicesql_table;
                    getval = "&module=" + module_selected + "&table=" + table_selected;
                }
                $.post(
            		script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=addfun&nocache=' + new Date().getTime(),
                    'choicesql=1&choice=' + choice_name_select + getval + '&choice_seltected=' + choice_seltected + '&column=' + column,
                    function(res) {
                        $('#choicesql_' + choice_name_select + '_' + column).html(res);
                    }
            	);
            }
            </script>
            <!-- END: column -->
        </tbody>
    </table>
    <table class="table table-striped table-bordered table-hover">
        <caption>
            Các chức năng của function {MODNAME} <i class="fa fa-angle-double-right" aria-hidden="true"></i> {FUNNAME}
        </caption>
        <tbody>
            <tr>
                <td>Tên function:</td>
                <td>
                    <input class="form-control" type="text" name="funname" value="{FUNNAME}" />
                </td>
                <td>Tạo function:</td>
                <td>
                    <select class="form-control" name="type_addfun">
                        <!-- BEGIN: type_addfun -->
                        <option value="{TYPE_ADDFUN.key}"{TYPE_ADDFUN.selected}>{TYPE_ADDFUN.value}</option>
                        <!-- END: type_addfun -->
                    </select>
                </td>
            </tr>
            <tr>
                <td>Tạo function ngoài site:</td>
                <td>
                    <input type="checkbox" name="setfunction" value="1" {SETFUNCTION_CHECKED}/> (Mặc định là admin)
                </td>
                <td>Chức năng kích hoạt</td>
                <td>
                    <select class="form-control" name="active_page">
                        <option value="">-- Chọn trường kích hoạt --</option>
                        <!-- BEGIN: active_page -->
                        <option value="{FIELD_TYPE.key}"{FIELD_TYPE.selected}>{FIELD_TYPE.value}</option>
                        <!-- END: active_page -->
                    </select>
                </td>
            </tr>
            <tr>
                <td>Chức năng phân trang</td>
                <td>
                    <input type="checkbox" name="generate_page" value="1" {GENERATE_PAGE_CHECKED}/>
                </td>
                <td>Chức năng sắp xếp thứ tự</td>
                <td>
                    <select class="form-control" name="weight_page">
                        <option value="">-- Chọn trường sắp xếp --</option>
                        <!-- BEGIN: weight_page -->
                        <option value="{FIELD_TYPE.key}"{FIELD_TYPE.selected}>{FIELD_TYPE.value}</option>
                        <!-- END: weight_page -->
                    </select>
                </td>
            </tr>
            <tr>
                <td>Chức năng tìm kiếm</td>
                <td>
                    <input type="checkbox" name="search_page" value="1" {SEARCH_PAGE_CHECKED}/>
                </td>
                <td>Chọn cột tiêu đề để lấy làm alias</td>
                <td>
                    <select class="form-control" name="alias_title">
                        <option value="">-- Chọn trường --</option>
                        <!-- BEGIN: alias_title -->
                        <option value="{FIELD_TYPE.key}"{FIELD_TYPE.selected}>{FIELD_TYPE.value}</option>
                        <!-- END: alias_title -->
                    </select>
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td align="center" colspan="4">
                    <input class="btn btn-primary" type="submit" value="Thực hiện" />
                </td>
            </tr>
        </tfoot>
    </table>
</form>
<!-- END: form -->
<!-- END: main -->
<!-- BEGIN: choicesql -->
<select class="form-control" onchange="nv_load_sqlchoice('{choicesql_next}', '','{COLUMN}')" name="{choicesql_name}[{COLUMN}]">
    <!-- BEGIN: loop -->
    <option {SQL.sl} value="{SQL.key}">{SQL.val}</option>
    <!-- END: loop -->
</select>
<!-- END: choicesql -->
<!-- BEGIN: column -->
{LANG.field_options_choicesql_key}:
<select class="form-control" name="choicesql_column_key[{COLUMN}]" id="choicesql_column_key">
    <!-- BEGIN: loop1 -->
    <option {SQL.sl_key} value="{SQL.key}">{SQL.val}</option>
    <!-- END: loop1 -->
</select>
{LANG.field_options_choicesql_val}:
<select class="form-control" name="choicesql_column_val[{COLUMN}]" id="choicesql_column_val">
    <!-- BEGIN: loop2 -->
    <option {SQL.sl_val} value="{SQL.key}">{SQL.val}</option>
    <!-- END: loop2 -->
</select>
<!-- END: column -->