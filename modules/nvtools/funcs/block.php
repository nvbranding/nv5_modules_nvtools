<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Sat, 19 Mar 2011 16:50:45 GMT
 */

if (!defined('NV_IS_MOD_NVTOOLS')) die('Stop!!!');

define('NV_ADMIN', true);
define('AUTHOR_FILEHEAD', "/**\n * @Project NUKEVIET 4.x\n * @Author VINADES.,JSC <contact@vinades.vn>\n * @Copyright (C) " . gmdate("Y") . " VINADES.,JSC. All rights reserved\n * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/\n * @Createdate " . gmdate("D, d M Y H:i:s") . " GMT\n */");

$page_title = $lang_module['block'];
$key_words = $module_info['keywords'];

$blocktheme = $nv_Request->get_title('blocktheme', 'get,post', '');
$blockglobal = $nv_Request->get_int('blockglobal', 'get,post', 0);
$blocksetting = $nv_Request->get_int('blocksetting', 'get,post', 0);
$tablename = $nv_Request->get_title('tablename', 'get,post');
$modname = $nv_Request->get_title('modname', 'get,post');
$blockname = $nv_Request->get_title('blockname', 'get,post');
$theme_others = $nv_Request->get_typed_array('theme_others', 'get,post', 'title', []);

/*
 * Block giao diện thì không thể lưu vào giao diện khác
 * Bắt buộc phải là block global
 */
if (!empty($blocktheme)) {
    $blockglobal = 1;
    $theme_others = [];
}

$xtpl = new XTemplate($op . ".tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);

$xtpl->assign('MODNAME', $modname);
$xtpl->assign('TABLENAME', $tablename);
$xtpl->assign('BLOCKNAME', $blockname);
$xtpl->assign('BLOCKTHEME', $blocktheme);

$xtpl->assign('BLOCKGLOBALCHECK', ($blockglobal) ? ' checked="checked"' : '');
$xtpl->assign('BLOCKSETTINGCHECK', ($blocksetting) ? ' checked="checked"' : '');

$submited = false;
if ((!empty($blocktheme) or preg_match('/^[a-zA-Z0-9\_\-]+$/', $modname)) and preg_match('/^[a-zA-Z0-9\_\-]+$/', $blockname)) {
    $submited = true;

    // Các tên bị cấm đặt tên field
    $list_no_us = 'add, all, alter, analyze, and, as, asc, before, between, bigint, binary, both, by, call, cascade, case, change, char, character, check, collate, column, comment, condition, constraint, continue, convert, create, cross, current_user, cursor, database, databases, date, day_hour, day_minute, day_second, dec, decimal, declare, default, delayed, delete, desc, describe, distinct, distinctrow, drop, dual, else, elseif, enclosed, escaped, exists, exit, explain, false, fetch, file, float4, float8, for, force, foreign, from, fulltext, get, grant, group, having, high_priority, hour_minute, hour_second, identified, if, ignore, ignore_server_ids, in, index, infile, inner, insert, int1, int2, int3, int4, int8, integer, interval, into, is, iterate, join, key, keys, kill, leading, leave, left, level, like, limit, lines, load, lock, long, loop, low_priority, master_bind, master_heartbeat_period, master_ssl_verify_server_cert, match, middleint, minute_second, mod, mode, modify, natural, no_write_to_binlog, not, null, number, numeric, on, optimize, option, optionally, or, order, outer, outfile, partition, precision, primary, privileges, procedure, public, purge, read, real, references, release, rename, repeat, replace, require, resignal, restrict, return, revoke, right, rlike, rows, schema, schemas, select, separator, session, set, share, show, signal, spatial, sql_after_gtids, sql_before_gtids, sql_big_result, sql_calc_found_rows, sql_small_result, sqlstate, ssl, start, starting, straight_join, table, terminated, then, to, trailing, trigger, true, undo, union, unique, unlock, unsigned, update, usage, use, user, using, values, varcharacter, varying, view, when, where, while, with, write, year_month, zerofill';
    $array_no_us = explode(',', $list_no_us);
    $array_no_us = array_map('trim', $array_no_us);
    $array_no_us = array_unique($array_no_us);

    // Lấy dữ liệu loại kiểu dữ liệu của các trường
    $array_views = $nv_Request->get_typed_array('views', 'post', 'string');
    $array_select_sql = array();

    if (preg_match('/^[a-zA-Z0-9\_\-]+$/', $tablename)) {
        $result = $db->query("select * from information_schema.columns where `TABLE_SCHEMA` = '" . $db_config['dbname'] . "' and `TABLE_NAME` = '" . $tablename . "'");
        while ($column = $result->fetch()) {
            $array_columns[$column['column_name']] = $column;
            $field_type = '';
            if (isset($array_views[$column['column_name']])) {
                $field_type = $array_views[$column['column_name']];
                if (!empty($field_type)) {
                    $array_select_sql[$column['column_name']] = $field_type;
                }
            }
            if ($column['column_key'] == 'PRI') {
                if (in_array($column['column_name'], $array_no_us)) {
                    $contents = '<div class="alert alert-danger">' . sprintf($lang_module['field_no_us'], $column['column_name']) . '</div>';
                    include NV_ROOTDIR . '/includes/header.php';
                    echo nv_site_theme($contents);
                    include NV_ROOTDIR . '/includes/footer.php';
                    die();
                }
                $primary = $column['column_name'];
                if ($column['extra'] == 'auto_increment') {
                    continue;
                }
            }

            if (strpos($column['data_type'], 'text') !== false) {
                $array_field_type_i = array(
                    'string' => 'Chuối ký tự'
                );
            } elseif (strpos($column['data_type'], 'int') !== false) {
                $array_field_type_i = array(
                    'string' => 'Chuối ký tự',
                    'date' => "Ngày",
                    'time' => "Thời gian"
                );
            } else {
                $array_field_type_i = array(
                    'string' => 'Chuối ký tự',
                    'imagefile' => "Hình ảnh",
                    'urlalias' => "Link liên kết",
                    'titlealias' => "Tiêu đề liên kết"
                );
            }

            foreach ($array_field_type_i as $key => $value) {
                $xtpl->assign('FIELD_TYPE', array(
                    'key' => $key,
                    'value' => $value,
                    'selected' => ($field_type == $key) ? ' selected="selected"' : ''
                ));
                $xtpl->parse('main.form.column.field_type');
            }

            $xtpl->assign('COLUMN', $column);
            $xtpl->parse('main.form.column');
        }
    }

    if (empty($blocktheme)) {
        // Block của module
        $path_dir_php = NV_ROOTDIR . '/modules/' . $modname . '/blocks';
        $path_dir_tpl = NV_ROOTDIR . '/themes/default/modules/' . $modname;

        // Tạo thư mục block nếu chưa có
        if (!file_exists(NV_ROOTDIR . '/modules/' . $modname . '/blocks')) {
            nv_mkdir(NV_ROOTDIR . '/modules/' . $modname, 'blocks');
        }
    } else {
        // Block của giao diện thì cả php, ini, tpl lưu vào
        $path_dir_php = NV_ROOTDIR . '/themes/' . $blocktheme . '/blocks';
        $path_dir_tpl = NV_ROOTDIR . '/themes/' . $blocktheme . '/blocks';

        // Tạo thư mục block nếu chưa có
        if (!file_exists(NV_ROOTDIR . '/themes/' . $blocktheme . '/blocks')) {
            nv_mkdir(NV_ROOTDIR . '/themes/' . $blocktheme, 'blocks');
        }
    }
    $blocktype = ($blockglobal) ? 'global' : 'module';
    $moddata = [];
    if (!empty($blocktheme)) {
        $moddata[] = str_replace('-', '_', $blocktheme);
    }
    if (!empty($modname)) {
        $moddata[] = str_replace('-', '_', $modname);
    }
    $moddata = implode('_', $moddata);

    $php_block_i = "<?php" . "\n\n";
    $php_block_i .= AUTHOR_FILEHEAD . "\n\n";
    $php_block_i .= "if (!defined('NV_MAINFILE')) {\n    die('Stop!!!');\n}\n\n";

    $php_block_i .= "if (!nv_function_exists('nvb_" . $moddata . "_" . $blocktype . "_" . $blockname . "')) {\n";

    $php_block_config_i = '';

    // Ghi INI và phần cấu hình nếu block có setting
    if ($blocksetting) {
        $ini_block_i = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
        $ini_block_i .= "<block>\n";
        $ini_block_i .= "\t<info>\n";
        $ini_block_i .= "\t\t<name>Block " . $blockname . " for module " . $modname . "</name>\n";
        $ini_block_i .= "\t\t<author>VinaDes.,Jsc</author>\n";
        $ini_block_i .= "\t\t<website>http://vinades.vn</website>\n";
        $ini_block_i .= "\t\t<description></description>\n";
        $ini_block_i .= "\t</info>\n";
        $ini_block_i .= "\t<config>\n";

        if (!empty($blocktheme) and !empty($modname)) {
            $ini_block_i .= "\t\t<selectmod></selectmod>\n";
        }

        $ini_block_i .= "\t\t<number_item>10</number_item>\n";
        $ini_block_i .= "\t\t<title_length>0</title_length>\n";
        $ini_block_i .= "\t</config>\n";
        $ini_block_i .= "\t<datafunction>nvb_config_" . $moddata . "_" . $blocktype . "_" . $blockname . "</datafunction>\n";
        $ini_block_i .= "\t<submitfunction>nvb_config_" . $moddata . "_" . $blocktype . "_" . $blockname . "_submit</submitfunction>\n";
        $ini_block_i .= "</block>\n";
        file_put_contents($path_dir_php . '/' . $blocktype . '.' . $blockname . '.ini', str_replace("\t", '    ', $ini_block_i));

        $php_block_config_i .= "\t/**\n";
        $php_block_config_i .= "\t * @param string \$module\n";
        $php_block_config_i .= "\t * @param array \$data_block\n";
        $php_block_config_i .= "\t * @param array \$lang_block\n";
        $php_block_config_i .= "\t * @return string\n";
        $php_block_config_i .= "\t */\n";
        $php_block_config_i .= "\tfunction nvb_config_" . $moddata . "_" . $blocktype . "_" . $blockname . "(\$module, \$data_block, \$lang_block)\n";
        $php_block_config_i .= "\t{\n";
        $php_block_config_i .= "\t\tglobal \$nv_Cache, \$site_mods, \$nv_Request;\n\n";

        $indent_space = '';
        if (!empty($blocktheme) and !empty($modname)) {
            $php_block_config_i .= "\t\t// Xuất nội dung khi có chọn module\n";
            $php_block_config_i .= "\t\tif (\$nv_Request->isset_request('loadajaxdata', 'get')) {\n";
            $php_block_config_i .= "\t\t\t// Sử dụng biến \$module và \$site_mods để thao tác với CSDL của module được chọn\n";
            $php_block_config_i .= "\t\t\t\$module = \$nv_Request->get_title('loadajaxdata', 'get', '');\n\n";
            $indent_space = "\t";
        }

        $php_block_config_i .= $indent_space . "\t\t\$html = '<div class=\"form-group\">';\n";
        $php_block_config_i .= $indent_space . "\t\t\$html .= '<label class=\"control-label col-sm-6\">' . \$lang_block['number_item'] . ':</label>';\n";
        $php_block_config_i .= $indent_space . "\t\t\$html .= '<div class=\"col-sm-9\"><select name=\"config_number_item\" class=\"form-control\">';\n";
        $php_block_config_i .= $indent_space . "\t\tfor (\$i = 0; \$i < 20; ++\$i) {\n";
        $php_block_config_i .= $indent_space . "\t\t\t\$html .= '<option value=\"' . \$i . '\"' . (\$data_block['number_item'] == \$i ? ' selected=\"selected\"' : '') . '> ' . \$i . ' </option>';\n";
        $php_block_config_i .= $indent_space . "\t\t}\n";
        $php_block_config_i .= $indent_space . "\t\t\$html .= \"</select></div>\";\n";
        $php_block_config_i .= $indent_space . "\t\t\$html .= '</div>';\n";
        $php_block_config_i .= $indent_space . "\t\t\$html . '<div class=\"form-group\">';\n";
        $php_block_config_i .= $indent_space . "\t\t\$html .= '<label class=\"control-label col-sm-6\">' . \$lang_block['title_length'] . ':</label>';\n";
        $php_block_config_i .= $indent_space . "\t\t\$html .= '<div class=\"col-sm-9\"><input type=\"text\" class=\"form-control\" name=\"config_title_length\" value=\"' . \$data_block['title_length'] . '\"/></div>';\n";
        $php_block_config_i .= $indent_space . "\t\t\$html .= '</div>';\n\n";

        if (!empty($blocktheme) and !empty($modname)) {
            $php_block_config_i .= $indent_space . "\t\tnv_htmlOutput(\$html);\n";
        } else {
            $php_block_config_i .= $indent_space . "\t\treturn \$html;\n";
        }

        /*
         * Nếu block của giao diện và có chọn module
         * Thì xuất ra cấu hình chọn module
         */
        if (!empty($blocktheme) and !empty($modname)) {
            $php_block_config_i .= "\t\t}\n\n";
            $php_block_config_i .= "\t\t\$html = '';\n";
            $php_block_config_i .= "\t\t\$html .= '<div class=\"form-group\">';\n";
            $php_block_config_i .= "\t\t\$html .= '<label class=\"control-label col-sm-6\">' . \$lang_block['selectmod'] . ':</label>';\n";
            $php_block_config_i .= "\t\t\$html .= '<div class=\"col-sm-9\">';\n";
            $php_block_config_i .= "\t\t\$html .= '<select name=\"config_selectmod\" class=\"form-control\">';\n";
            $php_block_config_i .= "\t\t\$html .= '<option value=\"\">--</option>';\n\n";
            $php_block_config_i .= "\t\tforeach (\$site_mods as \$title => \$mod) {\n";
            $php_block_config_i .= "\t\t\tif (\$mod['module_file'] == 'news') {\n";
            $php_block_config_i .= "\t\t\t\t\$html .= '<option value=\"' . \$title . '\"' . (\$title == \$data_block['selectmod'] ? ' selected=\"selected\"' : '') . '>' . \$mod['custom_title'] . '</option>';\n";
            $php_block_config_i .= "\t\t\t}\n";
            $php_block_config_i .= "\t\t}\n\n";
            $php_block_config_i .= "\t\t\$html .= '</select>';\n\n";
            $php_block_config_i .= "\t\t\$html .= '\n";
            $php_block_config_i .= "\t\t<script type=\"text/javascript\">\n";
            $php_block_config_i .= "\t\t$(\'[name=\"config_selectmod\"]\').change(function() {\n";
            $php_block_config_i .= "\t\t\tvar mod = $(this).val();\n";
            $php_block_config_i .= "\t\t\tvar file_name = $(\"select[name=file_name]\").val();\n";
            $php_block_config_i .= "\t\t\tvar module_type = $(\"select[name=module_type]\").val();\n";
            $php_block_config_i .= "\t\t\tvar blok_file_name = \"\";\n";
            $php_block_config_i .= "\t\t\tif (file_name != \"\") {\n";
            $php_block_config_i .= "\t\t\t\tvar arr_file = file_name.split(\"|\");\n";
            $php_block_config_i .= "\t\t\t\tif (parseInt(arr_file[1]) == 1) {\n";
            $php_block_config_i .= "\t\t\t\t\tblok_file_name = arr_file[0];\n";
            $php_block_config_i .= "\t\t\t\t}\n";
            $php_block_config_i .= "\t\t\t}\n";
            $php_block_config_i .= "\t\t\tif (mod != \"\") {\n";
            $php_block_config_i .= "\t\t\t\t$.get(script_name + \"?\" + nv_name_variable + \"=\" + nv_module_name + \'&\' + nv_lang_variable + \"=\" + nv_lang_data + \"&\" + nv_fc_variable + \"=block_config&bid=\" + bid + \"&module=\" + module_type + \"&selectthemes=\" + selectthemes + \"&file_name=\" + blok_file_name + \"&loadajaxdata=\" + mod + \"&nocache=\" + new Date().getTime(), function(theResponse) {\n";
            $php_block_config_i .= "\t\t			$(\"#block_config\").append(theResponse);\n";
            $php_block_config_i .= "\t\t		});\n";
            $php_block_config_i .= "\t\t\t}\n";
            $php_block_config_i .= "\t\t});\n";
            $php_block_config_i .= "\t\t$(function() {\n";
            $php_block_config_i .= "\t\t\t$(\'[name=\"config_selectmod\"]\').change();\n";
            $php_block_config_i .= "\t\t});\n";
            $php_block_config_i .= "\t\t</script>\n";
            $php_block_config_i .= "\t\t';\n\n";
            $php_block_config_i .= "\t\t\$html .= '</div>';\n";
            $php_block_config_i .= "\t\t\$html .= '</div>';\n\n";
            $php_block_config_i .= "\t\treturn \$html;\n";
        }

        $php_block_config_i .= "\t}\n\n";

        $php_block_config_i .= "\t/**\n";
        $php_block_config_i .= "\t * @param string \$module\n";
        $php_block_config_i .= "\t * @param array \$lang_block\n";
        $php_block_config_i .= "\t * @return number\n";
        $php_block_config_i .= "\t */\n";
        $php_block_config_i .= "\tfunction nvb_config_" . $moddata . "_" . $blocktype . "_" . $blockname . "_submit(\$module, \$lang_block)\n";
        $php_block_config_i .= "\t{\n";
        $php_block_config_i .= "\t\tglobal \$nv_Request;\n\n";
        $php_block_config_i .= "\t\t\$return = array();\n";
        $php_block_config_i .= "\t\t\$return['error'] = array();\n";
        $php_block_config_i .= "\t\t\$return['config'] = array();\n";
        $php_block_config_i .= "\t\t\$return['config']['number_item'] = \$nv_Request->get_int('config_number_item', 'post', 0);\n";
        $php_block_config_i .= "\t\t\$return['config']['title_length'] = \$nv_Request->get_int('config_title_length', 'post', 0);\n";
        $php_block_config_i .= "\t\treturn \$return;\n";
        $php_block_config_i .= "\t}\n\n";
    }

    $php_block_i .= $php_block_config_i;
    $php_block_i .= "\t/**\n";
    $php_block_i .= "\t * @param array \$block_config\n";
    $php_block_i .= "\t * @return string\n";
    $php_block_i .= "\t */\n";
    $php_block_i .= "\tfunction nvb_" . $moddata . "_" . $blocktype . "_" . $blockname . "(\$block_config)\n";
    $php_block_i .= "\t{\n";
    $php_block_i .= "\t\tglobal \$global_config, \$db, \$site_mods, \$nv_Cache;\n\n";

    if (empty($blocktheme)) {
        $php_block_i .= "\t\t\$mod_name = \$block_config['module'];\n";
    } else {
        $php_block_i .= "\t\t\$mod_name = '" . $modname . "';\n";
    }
    $php_block_i .= "\t\tif (isset(\$site_mods[\$mod_name])) {\n";
    $php_block_i .= "\t\t\t\$mod_file = \$site_mods[\$mod_name]['module_file'];\n";
    $php_block_i .= "\t\t\t\$mod_upload = \$site_mods[\$mod_name]['module_upload'];\n";
    $php_block_i .= "\t\t\t\$mod_data = \$site_mods[\$mod_name]['module_data'];\n\n";

    $modname_data = $site_mods[$modname]['module_data'];
    if (preg_match('/^' . $db_config['prefix'] . '\_([a-z]{2}+)\_(' . $modname_data . ')\_([a-z0-9\_]+)$/', $tablename, $m)) {
        $tablename_save = "' . NV_PREFIXLANG . '_' . \$mod_data . '_" . $m[3];
    } elseif (preg_match('/^' . $db_config['prefix'] . '\_(' . $modname_data . ')\_([a-z0-9\_]+)$/', $tablename, $m)) {
        $tablename_save = "' . \$db_config['prefix'] . '_' . \$mod_data . '_" . $m[2];
    } elseif (preg_match('/^' . $db_config['prefix'] . '\_(' . $modname_data . ')$/', $tablename, $m)) {
        $tablename_save = "' . \$db_config['prefix'] . '_' . \$mod_data . '";
    } elseif (preg_match('/^' . $db_config['prefix'] . '\_([a-z]{2}+)\_(' . $modname_data . ')$/', $tablename, $m)) {
        $tablename_save = "' . NV_PREFIXLANG . '_' . \$mod_data . '";
    } else {
        $tablename_save = $tablename;
    }

    if (!empty($array_select_sql)) {
        $php_block_i .= "\t\t\t\$sql = 'SELECT " . implode(', ', array_keys($array_select_sql)) . " FROM " . $tablename_save . "';\n";
        $php_block_i .= "\t\t\t\$list = \$nv_Cache->db(\$sql, '', \$mod_name);\n\n";
    } else {
        $php_block_i .= "\t\t\t\$list = array();\n\n";
    }

    // Xác định thư mục chứa tpl
    if (empty($blocktheme)) {
        $dir_tpl_check = "modules/' . \$mod_file . '";
        $dir_tpl = "modules/' . \$mod_file";
    } else {
        $dir_tpl_check = "blocks";
        $dir_tpl = "blocks'";
    }

    $php_block_i .= "\t\t\tif (file_exists(NV_ROOTDIR . '/themes/' . \$global_config['module_theme'] . '/" . $dir_tpl_check . "/" . $blocktype . "." . $blockname . ".tpl')) {\n";
    $php_block_i .= "\t\t\t\t\$block_theme = \$global_config['module_theme'];\n";
    $php_block_i .= "\t\t\t} elseif (file_exists(NV_ROOTDIR . '/themes/' . \$global_config['site_theme'] . '/" . $dir_tpl_check . "/" . $blocktype . "." . $blockname . ".tpl')) {\n";
    $php_block_i .= "\t\t\t\t\$block_theme = \$global_config['site_theme'];\n";
    $php_block_i .= "\t\t\t} else {\n";
    $php_block_i .= "\t\t\t\t\$block_theme = 'default';\n";
    $php_block_i .= "\t\t\t}\n\n";

    $php_block_i .= "\t\t\t\$xtpl = new XTemplate('" . $blocktype . "." . $blockname . ".tpl', NV_ROOTDIR . '/themes/' . \$block_theme . '/" . $dir_tpl . ");\n";
    $php_block_i .= "\t\t\t\$xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);\n";
    $php_block_i .= "\t\t\t\$xtpl->assign('TEMPLATE', \$block_theme);\n";

    $php_block_i .= "\t\t\tforeach (\$list as \$row) {\n";
    $titlealias = 'title';
    foreach ($array_select_sql as $_field => $field_type) {
        if ($field_type == 'urlalias') {
            $php_block_i .= "\t\t\t\t\$row['" . $_field . "'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . \$mod_name . '&amp;' . NV_OP_VARIABLE . '=' . \$row['" . $_field . "'];\n";
        } elseif ($field_type == 'date') {
            $php_block_i .= "\t\t\t\t\$row['" . $_field . "'] = !(empty(\$row['" . $_field . "'])) ? date('d/m/Y', \$row['" . $_field . "']) : '';\n";
        } elseif ($field_type == 'time') {
            $php_block_i .= "\t\t\t\t\$row['" . $_field . "'] = !(empty(\$row['" . $_field . "'])) ? date('H:i, d/m/Y', \$row['" . $_field . "']) : '';\n";
        } elseif ($field_type == 'imagefile') {
            $php_block_i .= "\t\t\t\t\$row['" . $_field . "'] = !(empty(\$row['" . $_field . "'])) ? NV_BASE_SITEURL . NV_FILES_DIR . '/' . \$mod_upload . '/' . \$row['" . $_field . "'] : '';\n";
        } elseif ($field_type == 'titlealias') {
            $titlealias = $_field;
            $php_block_i .= "\t\t\t\t\$row['trim_" . $_field . "'] = nv_clean60(\$row['" . $_field . "'], \$block_config['title_length']);\n";
        }
    }
    $php_block_i .= "\t\t\t\t\$xtpl->assign('ROW', \$row);\n";
    foreach ($array_select_sql as $_field => $field_type) {
        if ($field_type == 'imagefile') {
            $php_block_i .= "\t\t\t\tif (!empty(\$row['" . $_field . "'])) {\n\t\t\t\t\t\$xtpl->parse('main.loop." . $_field . "');;\n\t\t\t\t}\n";
        }
    }
    $php_block_i .= "\t\t\t\t\$xtpl->parse('main.loop');\n";
    $php_block_i .= "\t\t\t}\n";
    $php_block_i .= "\t\t\t\$xtpl->parse('main');\n";
    $php_block_i .= "\t\t\treturn \$xtpl->text('main');\n";
    $php_block_i .= "\t\t}\n";
    $php_block_i .= "\t}\n";
    $php_block_i .= "}\n\n";

    $php_block_i .= "if (defined('NV_SYSTEM')) {\n";
    $php_block_i .= "\t\$content = nvb_" . $moddata . "_" . $blocktype . "_" . $blockname . "(\$block_config);\n";
    $php_block_i .= "}";

    $html_block_i = "<!-- BEGIN: main -->\n";
    $html_block_i .= "<ul>\n";
    $html_block_i .= "\t<!-- BEGIN: loop -->\n";
    $html_block_i .= "\t<li class=\"clearfix\">\n";
    foreach ($array_select_sql as $_field => $field_type) {
        if ($field_type == 'imagefile') {
            $html_block_i .= "\t\t<img src=\"{ROW." . $_field . "}\" alt=\"{ROW." . $titlealias . "}\"  width=\"100\" class=\"img-thumbnail pull-left\"/>\n";
        } elseif ($field_type == 'urlalias') {
            $html_block_i .= "\t\t<a href=\"{ROW." . $_field . "}\" title=\"{ROW." . $titlealias . "}\"> {ROW.trim_" . $titlealias . "}</a><br />\n";
        } elseif ($titlealias != $_field) {
            $html_block_i .= "\t\t{ROW." . $_field . "}<br />\n";
        }
    }
    $html_block_i .= "\t</li>\n";
    $html_block_i .= "\t<!-- END: loop -->\n";
    $html_block_i .= "</ul>\n";
    $html_block_i .= "<!-- END: main -->";
    $html_block_i = trim(preg_replace('/\n([\t\n]+)\n/', "\n\n", $html_block_i));
    $html_block_i = preg_replace('/\t/', "    ", $html_block_i);
    $php_block_i = trim(preg_replace('/\t/', "    ", $php_block_i)) . "\n";

    file_put_contents($path_dir_php . '/' . $blocktype . '.' . $blockname . '.php', $php_block_i);
    file_put_contents($path_dir_tpl . '/' . $blocktype . '.' . $blockname . '.tpl', $html_block_i);

    // Copy TPL của block sang giao diện khác
    if (!empty($theme_others) and !empty($modname)) {
        foreach ($theme_others as $theme_other) {
            if (file_exists(NV_ROOTDIR . '/themes/' . $theme_other . '/modules/' . $modname)) {
                copy($path_dir_tpl . '/' . $blocktype . '.' . $blockname . '.tpl', NV_ROOTDIR . '/themes/' . $theme_other . '/modules/' . $modname . '/' . $blocktype . '.' . $blockname . '.tpl');
            }
        }
    }
}

if (!$submited) {
    $modules_exit = nv_scandir(NV_ROOTDIR . '/modules', $global_config['check_module']);
    foreach ($modules_exit as $mod_i) {
        $xtpl->assign('MODNAME', array(
            'value' => $mod_i,
            'selected' => ($modname == $mod_i) ? ' selected="selected"' : ''
        ));
        $xtpl->parse('main.tablename.modname');
    }

    if (!empty($modname)) {
        $result = $db->query('SHOW TABLE STATUS LIKE ' . $db->quote($db_config['prefix'] . '\_' . NV_LANG_DATA . '\_' . $modname . '%'));
        while ($item = $result->fetch()) {
            $xtpl->assign('MODNAME', array(
                'value' => $item['name'],
                'selected' => ($tablename == $item['name']) ? ' selected="selected"' : ''
            ));
            $xtpl->parse('main.tablename.loop');
        }

        $result = $db->query('SHOW TABLE STATUS LIKE ' . $db->quote($db_config['prefix'] . '\_' . $modname . '%'));
        while ($item = $result->fetch()) {
            $xtpl->assign('MODNAME', array(
                'value' => $item['name'],
                'selected' => ($tablename == $item['name']) ? ' selected="selected"' : ''
            ));
            $xtpl->parse('main.tablename.loop');
        }
    }

    $theme_list = nv_scandir(NV_ROOTDIR . '/themes/', $global_config['check_theme']);
    $theme_mobile_list = nv_scandir(NV_ROOTDIR . '/themes/', $global_config['check_theme_mobile']);
    $theme_list = array_merge($theme_list, $theme_mobile_list);
    $theme_other_list = array_diff($theme_list, ['default', 'mobile_default']);

    foreach ($theme_list as $_theme_i) {
        $xtpl->assign('THEME_LIST', array(
            'value' => $_theme_i,
            'selected' => ($blocktheme == $_theme_i) ? ' selected="selected"' : ''
        ));
        $xtpl->parse('main.tablename.theme_list');
    }

    if (!empty($theme_other_list)) {
        foreach ($theme_other_list as $_theme_i) {
            $xtpl->assign('THEME_OTHER', array(
                'value' => $_theme_i,
                'checked' => in_array($_theme_i, $theme_others) ? ' checked="checked"' : ''
            ));
            $xtpl->parse('main.tablename.othertheme.loop');
        }
        $xtpl->parse('main.tablename.othertheme');
    }

    $xtpl->parse('main.tablename');
} else {
    $xtpl->assign('BLOCK_GLOBAL_DISABLED', empty($blocktheme) ? '' : ' disabled="disabled"');

    foreach ($theme_others as $theme_other) {
        $xtpl->assign('THEME_OTHER', $theme_other);
        $xtpl->parse('main.form.theme_other');
    }

    $xtpl->parse('main.form');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
