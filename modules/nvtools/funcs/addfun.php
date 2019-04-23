<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Sat, 19 Mar 2011 16:50:45 GMT
 */

if (!defined('NV_IS_MOD_NVTOOLS')) {
    die('Stop!!!');
}

define('NV_ADMIN', true);

/**
 * nv_get_lang_mod_admin()
 *
 * @param mixed $mod
 * @param mixed $lang
 * @param mixed $setfunction
 * @return
 */
function nv_get_lang_mod_admin($mod, $lang, $setfunction)
{
    global $global_config;

    $lang_module = [];
    if ($setfunction == 1) {
        $file_name = '';
    } else {
        $file_name = 'admin_';
    }
    if (file_exists(NV_ROOTDIR . '/modules/' . $mod . '/language/' . $file_name . $lang . '.php')) {
        include NV_ROOTDIR . '/modules/' . $mod . '/language/' . $file_name . $lang . '.php';
    }
    return $lang_module;
}

/**
 * nv_write_lang_mod_admin()
 *
 * @param mixed $mod
 * @param mixed $lang
 * @param mixed $arr_new_lang
 * @param mixed $setfunction
 * @return void
 */
function nv_write_lang_mod_admin($mod, $lang, $arr_new_lang, $setfunction)
{
    global $funname, $sys_info, $language_array;
    if (!empty($arr_new_lang)) {
        if ($setfunction == 1) {
            $file_name = '';
        } else {
            $file_name = 'admin_';
        }
        if (file_exists(NV_ROOTDIR . '/modules/' . $mod . '/language/' . $file_name . $lang . '.php')) {
            $content_lang = file_get_contents(NV_ROOTDIR . '/modules/' . $mod . '/language/' . $file_name . $lang . '.php');
            $content_lang = trim($content_lang);
            $content_lang = rtrim($content_lang, '?>');
        } else {
            $content_lang = "<?php\n\n";
            $content_lang .= "/**\n";
            $content_lang .= "* @Project NUKEVIET 4.x\n";
            $content_lang .= "* @Author VINADES.,JSC <contact@vinades.vn>\n";
            $content_lang .= "* @Copyright (C) " . date("Y") . " VINADES.,JSC. All rights reserved\n";
            $content_lang .= "* @Language " . $language_array[$lang]['name'] . "\n";
            $content_lang .= "* @License CC BY-SA (http://creativecommons.org/licenses/by-sa/4.0/)\n";
            $content_lang .= "* @Createdate " . gmdate("M d, Y, h:i:s A", time()) . "\n";
            $content_lang .= "*/\n";
            if ($setfunction == 1) {
                $content_lang .= "\nif (!defined('NV_MAINFILE'))";
            } else {
                $content_lang .= "\nif (!defined('NV_ADMIN') or !defined('NV_MAINFILE'))";
            }
            $content_lang .= "\n\tdie('Stop!!!');\n\n";

            $array_translator['info'] = (isset($array_translator['info'])) ? $array_translator['info'] : "";

            $content_lang .= "\$lang_translator['author'] = 'VINADES.,JSC (contact@vinades.vn)';\n";
            $content_lang .= "\$lang_translator['createdate'] = '" . date('d/m/Y, H:i') . "';\n";
            $content_lang .= "\$lang_translator['copyright'] = 'Copyright (C) " . date('Y') . " VINADES.,JSC. All rights reserved';\n";
            $content_lang .= "\$lang_translator['info'] = '';\n";
            $content_lang .= "\$lang_translator['langtype'] = 'lang_module';\n";
            $content_lang .= "\n";
        }
        $content_lang .= "\n\n//Lang for function " . $funname . "\n";

        foreach ($arr_new_lang as $lang_key => $lang_value) {
            $lang_value = nv_unhtmlspecialchars($lang_value);
            $lang_value = str_replace("\'", "'", $lang_value);
            $lang_value = str_replace("'", "\'", $lang_value);
            $lang_value = nv_nl2br($lang_value);
            $lang_value = str_replace('<br />', '<br />', $lang_value);
            $content_lang .= "\$lang_module['" . $lang_key . "'] = '" . $lang_value . "';\n";
        }

        if (!is_writable(NV_ROOTDIR . '/modules/' . $mod . '/language/' . $file_name . $lang . '.php')) {
            if (substr($sys_info['os'], 0, 3) != 'WIN') {
                chmod(NV_ROOTDIR . '/modules/' . $mod . '/language/' . $file_name . $lang . '.php', 0777);
            }
        }

        file_put_contents(NV_ROOTDIR . '/modules/' . $mod . '/language/' . $file_name . $lang . '.php', str_replace("\t", "    ", $content_lang), LOCK_EX);
    }
}

// Load các bảng dữ liệu của module
if ($nv_Request->isset_request('loadmodname', 'get')) {
    $contents = '<option value=""> -- Chọn bảng dữ liệu -- </option>';
    $loadmodname = $nv_Request->get_title('loadmodname', 'get', '');
    if (preg_match('/^[a-zA-Z0-9\_\-]+$/', $loadmodname)) {
        $modname_data = $site_mods[$loadmodname]['module_data'];
        $result = $db->query('SHOW TABLE STATUS LIKE ' . $db->quote($db_config['prefix'] . '\_' . NV_LANG_DATA . '\_' . $modname_data . '%'));
        while ($item = $result->fetch()) {
            $contents .= '<option value="' . $item['name'] . '">' . $item['name'] . '</option>';
        }
        $result = $db->query('SHOW TABLE STATUS LIKE ' . $db->quote($db_config['prefix'] . '\_' . $modname_data . '%'));
        while ($item = $result->fetch()) {
            $contents .= '<option value="' . $item['name'] . '">' . $item['name'] . '</option>';
        }
    }
    nv_htmlOutput($contents);
}

if ($nv_Request->isset_request('choicesql', 'post')) {
    if (!defined('NV_IS_AJAX')) {
        die('Wrong URL');
    }

    $array_choicesql = ['module' => 'table', 'table' => 'column'];
    $choice = $nv_Request->get_string('choice', 'post', '');
    $choice_seltected = $nv_Request->get_string('choice_seltected', 'post', '');
    $column = $nv_Request->get_string('column', 'post', '');

    $xtpl = new XTemplate('addfun.tpl', NV_ROOTDIR . '/themes/default/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('COLUMN', $column);
    if ($choice == 'module') {
        $xtpl->assign('choicesql_name', 'choicesql_' . $choice);
        $xtpl->assign('choicesql_next', $array_choicesql[$choice]);
        $xtpl->parse('choicesql.loop');
        foreach ($site_mods as $module) {
            $_temp_choice['sl'] = ($choice_seltected == $module['module_data']) ? ' selected="selected"' : '';
            $_temp_choice['key'] = $module['module_data'];
            $_temp_choice['val'] = $module['custom_title'];
            $xtpl->assign('SQL', $_temp_choice);
            $xtpl->parse('choicesql.loop');
            unset($_temp_choice);
        }
        $xtpl->parse('choicesql');
        $contents = $xtpl->text('choicesql');
    } elseif ($choice == 'table') {
        $module = $nv_Request->get_string('module', 'post', '');
        if ($module == '')
            exit();
        $_items = $db->query("SHOW TABLE STATUS LIKE '%\_" . $module . "%'")->fetchAll();
        $num_table = sizeof($_items);

        $array_table_module = [];
        $xtpl->assign('choicesql_name', 'choicesql_' . $choice);
        $xtpl->assign('choicesql_next', $array_choicesql[$choice]);

        if ($num_table > 0) {
            $xtpl->parse('choicesql.loop');
            foreach ($_items as $item) {
                $_temp_choice['sl'] = ($choice_seltected == $item['name']) ? ' selected="selected"' : '';
                $_temp_choice['key'] = $item['name'];
                $_temp_choice['val'] = $item['name'];
                $xtpl->assign('SQL', $_temp_choice);
                $xtpl->parse('choicesql.loop');
                unset($_temp_choice);
            }
        }
        $xtpl->parse('choicesql');
        $contents = $xtpl->text('choicesql');
    } elseif ($choice == 'column') {
        $table = $nv_Request->get_string('table', 'post', '');
        if ($table == '')
            exit();

        $_items = $db->columns_array($table);
        $num_table = sizeof($_items);

        $array_table_module = [];
        $xtpl->assign('choicesql_name', 'choicesql_' . $choice);
        $xtpl->assign('choicesql_next', $array_choicesql[$choice]);
        if ($num_table > 0) {
            $choice_seltected = explode('|', $choice_seltected);
            foreach ($_items as $item) {
                $_temp_choice['sl_key'] = ($choice_seltected[0] == $item['field']) ? ' selected="selected"' : '';
                $_temp_choice['sl_val'] = ($choice_seltected[1] == $item['field']) ? ' selected="selected"' : '';
                $_temp_choice['key'] = $item['field'];
                $_temp_choice['val'] = $item['field'];
                $xtpl->assign('SQL', $_temp_choice);
                $xtpl->parse('column.loop1');
                $xtpl->parse('column.loop2');
                unset($_temp_choice);
            }
        }
        $xtpl->parse('column');
        $contents = $xtpl->text('column');
    }

    include NV_ROOTDIR . '/includes/header.php';
    echo $contents;
    include NV_ROOTDIR . '/includes/footer.php';
}

$page_title = $lang_module['addfun'];
$key_words = $module_info['keywords'];

$modname = $nv_Request->get_title('modname', 'get,post');
$tablename = $nv_Request->get_title('tablename', 'get,post');
$funname = $nv_Request->get_title('funname', 'get,post');
$setlangvi = $nv_Request->get_int('setlangvi', 'get', 0);
$setlangen = $nv_Request->get_int('setlangen', 'get', 0);

$array_choice_type = [
    'field_choicetypes_sql' => $lang_module['field_choicetypes_sql'],
    'field_choicetypes_text' => $lang_module['field_choicetypes_text']
];

$xtpl = new XTemplate($op . ".tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
$xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);

$xtpl->assign('MODNAME', $modname);
$xtpl->assign('TABLENAME', $tablename);
$xtpl->assign('FUNNAME', $funname);

$nb = 0;
$error = [];
if (preg_match('/^[a-zA-Z0-9\_\-]+$/', $modname) and preg_match('/^[a-zA-Z0-9\_\-]+$/', $tablename)) {
    $alias_title = $nv_Request->get_title('alias_title', 'post', 'title');
    $array_views = $nv_Request->get_typed_array('views', 'post', 'string');
    $array_requireds = $nv_Request->get_typed_array('requireds', 'post', 'int');
    $array_hiddens = $nv_Request->get_typed_array('hiddens', 'post', 'int');
    $array_listviews = $nv_Request->get_typed_array('listviews', 'post', 'int');

    $array_title_vi = $nv_Request->get_typed_array('title_vi', 'post', 'string');
    $array_title_en = $nv_Request->get_typed_array('title_en', 'post', 'string');

    $type_addfun = $nv_Request->get_int('type_addfun', 'post', 0);
    $setfunction = $nv_Request->get_int('setfunction', 'post', 0);

    if ($nv_Request->isset_request('views', 'post')) {
        $generate_page = $nv_Request->get_int('generate_page', 'post', 0);
        $search_page = $nv_Request->get_int('search_page', 'post', 0);
        $weight_page = $nv_Request->get_title('weight_page', 'post', '');
        $active_page = $nv_Request->get_title('active_page', 'post', '');

        $choicetypes_ = $nv_Request->get_array('choicetypes_', 'post');
        $choicesql_module = $nv_Request->get_array('choicesql_module', 'post');
        $choicesql_table = $nv_Request->get_array('choicesql_table', 'post');
        $choicesql_column_key = $nv_Request->get_array('choicesql_column_key', 'post');
        $choicesql_column_val = $nv_Request->get_array('choicesql_column_val', 'post');

        $field_choice = $nv_Request->get_array('field_choice', 'post');
        $field_choice_text = $nv_Request->get_array('field_choice_text', 'post');
        $default_value_choice = $nv_Request->get_array('default_value_choice', 'post');

        if (!empty($weight_page)) {
            unset($array_listviews[$weight_page]);
            $array_hiddens[$weight_page] = 1;
        }

        if (empty($array_listviews)) {
            $generate_page = $search_page = 0;
            $weight_page = '';
            $active_page = '';
        }
    } else {
        $generate_page = $search_page = 1;
        $weight_page = 'weight';
        $active_page = 'active';
    }

    $content_default = '';
    try {
        $primary = '';
        $array_columns = [];
        $array_field_js = [];

        $list_no_us = 'add, all, alter, analyze, and, as, asc, before, between, bigint, binary, both, by, call, cascade, case, change, char, character, check, collate, column, comment, condition, constraint, continue, convert, create, cross, current_user, cursor, database, databases, date, day_hour, day_minute, day_second, dec, decimal, declare, default, delayed, delete, desc, describe, distinct, distinctrow, drop, dual, else, elseif, enclosed, escaped, exists, exit, explain, false, fetch, file, float4, float8, for, force, foreign, from, fulltext, get, grant, group, having, high_priority, hour_minute, hour_second, identified, if, ignore, ignore_server_ids, in, index, infile, inner, insert, int1, int2, int3, int4, int8, integer, interval, into, is, iterate, join, key, keys, kill, leading, leave, left, level, like, limit, lines, load, lock, long, loop, low_priority, master_bind, master_heartbeat_period, master_ssl_verify_server_cert, match, middleint, minute_second, mod, mode, modify, natural, no_write_to_binlog, not, null, number, numeric, on, optimize, option, optionally, or, order, outer, outfile, partition, precision, primary, privileges, procedure, public, purge, read, real, references, release, rename, repeat, replace, require, resignal, restrict, return, revoke, right, rlike, rows, schema, schemas, select, separator, session, set, share, show, signal, spatial, sql_after_gtids, sql_before_gtids, sql_big_result, sql_calc_found_rows, sql_small_result, sqlstate, ssl, start, starting, straight_join, table, terminated, then, to, trailing, trigger, true, undo, union, unique, unlock, unsigned, update, usage, use, user, using, values, varcharacter, varying, view, when, where, while, with, write, year_month, zerofill';
        $array_no_us = explode(',', $list_no_us);
        $array_no_us = array_map('trim', $array_no_us);
        $array_no_us = array_unique($array_no_us);

        $result = $db->query("select * from information_schema.columns where `TABLE_SCHEMA` = '" . $db_config['dbname'] . "' and `TABLE_NAME` = '" . $tablename . "'");
        while ($column = $result->fetch()) {
            $array_columns[$column['column_name']] = $column;
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
            $nb++;

            if (isset($array_title_en[$column['column_name']])) {
                $column['title_en'] = trim($array_title_en[$column['column_name']]);
            } elseif ($setlangen) {
                $column['title_en'] = ucfirst(str_replace('_', ' ', $column['column_name']));
            } else {
                $column['title_en'] = '';
            }

            if (isset($array_title_vi[$column['column_name']])) {
                $column['title_vi'] = trim($array_title_vi[$column['column_name']]);
            } elseif ($setlangvi) {
                $column['title_vi'] = (!empty($column['column_comment'])) ? $column['column_comment'] : ucfirst(str_replace('_', ' ', $column['column_name']));
            } else {
                $column['title_vi'] = '';
            }

            $column['required_checked'] = isset($array_requireds[$column['column_name']]) ? ' checked="checked"' : '';
            $column['hidden_checked'] = isset($array_hiddens[$column['column_name']]) ? ' checked="checked"' : '';
            $column['listview_checked'] = isset($array_listviews[$column['column_name']]) ? ' checked="checked"' : '';

            if (strpos($column['data_type'], 'text') !== false) {
                $field_type = (strpos($column['data_type'], 'mediumtext') !== false or strpos($column['data_type'], 'longtext') !== false) ? 'editor' : 'textarea';
                $array_field_type_i = [
                    'textarea' => $lang_module['field_type_textarea'],
                    'editor' => $lang_module['field_type_editor']
                ];
            } elseif (strpos($column['data_type'], 'int') !== false) {
                $field_type = 'number';
                $array_field_type_i = [
                    'number_int' => $lang_module['field_type_int'],
                    'number_float' => $lang_module['field_type_float'],
                    'date' => $lang_module['field_type_date'],
                    'time' => $lang_module['field_type_time'],
                    'textbox' => $lang_module['field_type_textbox'],
                    'select' => $lang_module['field_type_select'],
                    'radio' => $lang_module['field_type_radio']
                    // 'checkbox' => $lang_module['field_type_checkbox']
                ];
                if (strpos($column['data_type'], 'int') !== 0) {
                    unset($array_field_type_i['date']);
                    unset($array_field_type_i['time']);
                }
            } else {
                if ($column['column_name'] == 'alias') {
                    $field_type = 'textalias';
                } else {
                    $field_type = 'textbox';
                }
                $array_field_type_i = [
                    'email' => $lang_module['field_type_email'],
                    'url' => $lang_module['field_type_url'],
                    'textbox' => $lang_module['field_type_textbox'],
                    'textfile' => $lang_module['field_type_textfile'],
                    'textalias' => $lang_module['field_type_textalias'],
                    'password' => $lang_module['field_type_password'],
                    'select' => $lang_module['field_type_select'],
                    'radio' => $lang_module['field_type_radio'],
                    'checkbox' => $lang_module['field_type_checkbox']
                ];
                if (strpos($column['column_name'], 'groups_') !== false) {
                    $field_type = 'checkbox_groups';
                    $array_field_type_i['checkbox_groups'] = $lang_module['field_type_checkbox_groups'];
                }
            }

            if (isset($array_views[$column['column_name']])) {
                $field_type = $array_views[$column['column_name']];
            }

            foreach ($array_field_type_i as $key => $value) {
                $xtpl->assign('FIELD_TYPE', [
                    'key' => $key,
                    'value' => $value,
                    'selected' => ($field_type == $key) ? ' selected="selected"' : ''
                ]);
                $xtpl->parse('main.form.column.field_type');
            }
            $xtpl->assign('COLUMN', $column);
            foreach ($array_choice_type as $key => $value) {
                $xtpl->assign('CHOICE_TYPES', ['key' => $key, 'value' => $value]);
                $xtpl->parse('main.form.column.choicetypes_add.choicetypes');
            }
            $xtpl->parse('main.form.column.choicetypes_add');

            $number = 1;
            $xtpl->assign('FIELD_CHOICES', [
                'number' => $number,
                'key' => '',
                'value' => ''
            ]);
            $xtpl->parse('main.form.column.loop_field_choice');
            $xtpl->assign('FIELD_CHOICES_NUMBER', $number);

            $xtpl->parse('main.form.column');

            if (strpos($column['data_type'], 'int') !== false and $column['column_name'] != $primary) {
                $xtpl->assign('FIELD_TYPE', [
                    'key' => $column['column_name'],
                    'value' => $column['column_name'],
                    'selected' => ($column['column_name'] == $active_page) ? ' selected="selected"' : ''
                ]);
                $xtpl->parse('main.form.active_page');
            }

            if (strpos($column['data_type'], 'int') !== false and $column['column_name'] != $primary) {
                $xtpl->assign('FIELD_TYPE', [
                    'key' => $column['column_name'],
                    'value' => $column['column_name'],
                    'selected' => ($column['column_name'] == $weight_page) ? ' selected="selected"' : ''
                ]);
                $xtpl->parse('main.form.weight_page');
            }
            if (strpos($column['data_type'], 'varchar') !== false and $column['column_name'] != $primary) {
                $xtpl->assign('FIELD_TYPE', [
                    'key' => $column['column_name'],
                    'value' => $column['column_name'],
                    'selected' => ($column['column_name'] == $alias_title) ? ' selected="selected"' : ''
                ]);
                $xtpl->parse('main.form.alias_title');
            }
        }

        if (empty($primary)) {
            $error[] = $lang_module['addfun_err_prikey'];
        }

        $array_type_addfun = [
            0 => 'Cả form và list',
            1 => 'List',
            2 => 'Form'
        ];
        foreach ($array_type_addfun as $key => $value) {
            $xtpl->assign('TYPE_ADDFUN', [
                'key' => $key,
                'value' => $value,
                'selected' => ($key == $type_addfun) ? ' selected="selected"' : ''
            ]);
            $xtpl->parse('main.form.type_addfun');
        }

        $xtpl->assign('GENERATE_PAGE_CHECKED', ($generate_page) ? ' checked="checked"' : '');
        $xtpl->assign('SEARCH_PAGE_CHECKED', ($search_page) ? ' checked="checked"' : '');
        $xtpl->assign('SETFUNCTION_CHECKED', ($setfunction) ? ' checked="checked"' : '');

        if (empty($modname)) {
            if (preg_match('/^' . $db_config['prefix'] . '\_([a-z]+)\_([a-z0-9]+)\_/', $tablename, $m)) {
                $modname = $m[2];
            }
        }

        if (!empty($array_views)) {
            // wite file php
            $_tmp_key_insert = [];
            $_tmp_key_update = [];
            $_tmp_key_editor = [];
            $_tmp_key_textarea = [];
            $_tmp_key_file = [];
            $txt_bindParam = '';
            $txt_bindParam_default = '';
            $txt_post = '';
            $txt_date_view = '';

            $lang_mod_admin_vi = nv_get_lang_mod_admin($modname, 'vi', $setfunction);
            $lang_mod_admin_en = nv_get_lang_mod_admin($modname, 'en', $setfunction);

            $lang_mod_admin_vi_new = [];
            $lang_mod_admin_en_new = [];

            if (!isset($lang_mod_admin_vi[$funname]) and !isset($lang_mod_admin_vi_new[$funname])) {
                $lang_mod_admin_vi_new[$funname] = $funname;
            }

            if (!isset($lang_mod_admin_en[$funname]) and !isset($lang_mod_admin_en_new[$funname])) {
                $lang_mod_admin_en_new[$funname] = $funname;
            }

            if (!isset($lang_mod_admin_vi['add']) and !isset($lang_mod_admin_vi_new['add'])) {
                $lang_mod_admin_vi_new['add'] = 'Thêm mới';
            }
            if (!isset($lang_mod_admin_vi['edit']) and !isset($lang_mod_admin_vi_new['edit'])) {
                $lang_mod_admin_vi_new['edit'] = 'Sửa';
            }

            if (!isset($lang_mod_admin_en['edit']) and !isset($lang_mod_admin_en_new['edit'])) {
                $lang_mod_admin_en_new['edit'] = 'edit';
            }

            if (!isset($lang_mod_admin_vi['delete']) and !isset($lang_mod_admin_vi_new['delete'])) {
                $lang_mod_admin_vi_new['delete'] = 'Xóa';
            }

            if (!isset($lang_mod_admin_en['delete']) and !isset($lang_mod_admin_en_new['delete'])) {
                $lang_mod_admin_en_new['delete'] = 'Delete';
            }

            if (!isset($lang_mod_admin_vi['number']) and !isset($lang_mod_admin_vi_new['number'])) {
                $lang_mod_admin_vi_new['number'] = 'STT';
            }

            if (!isset($lang_mod_admin_en['number']) and !isset($lang_mod_admin_en_new['number'])) {
                $lang_mod_admin_en_new['number'] = 'Number';
            }
            if (!isset($lang_mod_admin_en['active']) and !isset($lang_mod_admin_en_new['active'])) {
                $lang_mod_admin_en_new['active'] = 'Trạng thái';
            }
            if (!isset($lang_mod_admin_vi['active']) and !isset($lang_mod_admin_vi_new['active'])) {
                $lang_mod_admin_vi_new['active'] = 'Trạng thái';
            }
            if ($search_page) {
                if (!isset($lang_mod_admin_vi['search_title']) and !isset($lang_mod_admin_vi_new['search_title'])) {
                    $lang_mod_admin_vi_new['search_title'] = 'Nhập từ khóa tìm kiếm';
                }

                if (!isset($lang_mod_admin_en['search_title']) and !isset($lang_mod_admin_en_new['search_title'])) {
                    $lang_mod_admin_en_new['search_title'] = 'Enter keywords searching';
                }

                if (!isset($lang_mod_admin_vi['search_submit']) and !isset($lang_mod_admin_vi_new['search_submit'])) {
                    $lang_mod_admin_vi_new['search_submit'] = 'Tìm kiếm';
                }

                if (!isset($lang_mod_admin_en['search_submit']) and !isset($lang_mod_admin_en_new['search_submit'])) {
                    $lang_mod_admin_en_new['search_submit'] = 'Search';
                }
            }

            // Lấy ngôn ngữ
            foreach ($array_views as $key => $_view_type) {
                if (!isset($array_hiddens[$key]) or isset($array_requireds[$key])) {
                    if (!isset($lang_mod_admin_vi[$key]) and !isset($lang_mod_admin_vi_new[$key])) {
                        $lang_mod_admin_vi_new[$key] = $array_title_vi[$key];
                    }
                    if (!isset($lang_mod_admin_en[$key]) and !isset($lang_mod_admin_en_new[$key])) {
                        $lang_mod_admin_en_new[$key] = $array_title_en[$key];
                    }
                }
            }

            $array_check_error = [];
            // Kiểm tra các biến bắt buộc phải nhập
            foreach ($array_requireds as $key => $value) {
                $array_check_error[] = "if (empty(\$row['" . $key . "'])) {\n\t\t\$error[] = \$lang_module['error_required_" . $key . "'];\n\t}";

                if (!isset($lang_mod_admin_vi['error_required_' . $key]) and !isset($lang_mod_admin_vi_new['error_required_' . $key])) {
                    $required_vi = isset($lang_mod_admin_vi_new[$key]) ? $lang_mod_admin_vi_new[$key] : $lang_mod_admin_vi[$key];
                    $lang_mod_admin_vi_new['error_required_' . $key] = "Lỗi: bạn cần nhập dữ liệu cho " . $required_vi;
                }

                if (!isset($lang_mod_admin_en['error_required_' . $key]) and !isset($lang_mod_admin_en_new['error_required_' . $key])) {
                    $required_en = isset($lang_mod_admin_en_new[$key]) ? $lang_mod_admin_en_new[$key] : $lang_mod_admin_en[$key];
                    $lang_mod_admin_en_new['error_required_' . $key] = "Error: Required fields enter the " . $required_en;
                }
            }
            $nv_url = "";
            $content = "<?php\n\n";
            $content .= NV_FILEHEAD . "\n\n";
            if ($setfunction == 1) {
                $modname_up = strtoupper($modname);
                $content .= "if (!defined('NV_IS_MOD_" . $modname_up . "')) {\n\tdie('Stop!!!');\n}\n\n";
                $nv_url = "NV_BASE_SITEURL";
            } else {
                $content .= "if (!defined('NV_IS_FILE_ADMIN')) {\n\tdie('Stop!!!');\n}\n\n";
                $nv_url = "NV_BASE_ADMINURL";
            }

            if (($type_addfun == 0 or $type_addfun == 2) and in_array('textalias', $array_views)) {
                $content .= "if (\$nv_Request->isset_request('get_alias_title', 'post')) {\n";
                $content .= "\t\$alias = \$nv_Request->get_title('get_alias_title', 'post', '');\n";
                $content .= "\t\$alias = change_alias(\$alias);\n";
                $content .= "\tnv_htmlOutput(\$alias);\n";
                $content .= "}\n\n";
            }

            if (in_array('checkbox_groups', $array_views)) {
                $content .= "\$groups_list = nv_groups_list();\n\n";
            }
            $modname_data = $site_mods[$modname]['module_data'];

            if (preg_match('/^' . $db_config['prefix'] . '\_([a-z]{2}+)\_(' . $modname_data . ')\_([a-z0-9\_]+)$/', $tablename, $m)) {
                $tablename_save = "' . NV_PREFIXLANG . '_' . \$module_data . '_" . $m[3];
            } elseif (preg_match('/^' . $db_config['prefix'] . '\_(' . $modname_data . ')\_([a-z0-9\_]+)$/', $tablename, $m)) {
                $tablename_save = "' . \$db_config['prefix'] . '_' . \$module_data . '_" . $m[2];
            } elseif (preg_match('/^' . $db_config['prefix'] . '\_(' . $modname_data . ')$/', $tablename, $m)) {
                $tablename_save = "' . \$db_config['prefix'] . '_' . \$module_data . '";
            } elseif (preg_match('/^' . $db_config['prefix'] . '\_([a-z]{2}+)\_(' . $modname_data . ')$/', $tablename, $m)) {
                $tablename_save = "' . NV_PREFIXLANG . '_' . \$module_data . '";
            } else {
                $tablename_save = $tablename;
            }

            if ($type_addfun == 0 or $type_addfun == 1) {
                if (!empty($active_page)) {
                    // neu co cot active
                    $content .= "// Change status\n";
                    $content .= "if (\$nv_Request->isset_request('change_status', 'post, get')) ";
                    $content .= "{\n";
                    $content .= "\t\$" . $primary . " = \$nv_Request->get_int('" . $primary . "', 'post, get', 0);\n";
                    $content .= "\t\$content = 'NO_' . \$" . $primary . ";\n\n";
                    $content .= "\t\$query = 'SELECT " . $active_page . " FROM " . $tablename_save . " WHERE " . $primary . "=' . \$" . $primary . ";\n";
                    $content .= "\t\$row = \$db->query(\$query)->fetch();\n";
                    $content .= "\tif (isset(\$row['" . $active_page . "'])) {\n";
                    $content .= "\t\t\$" . $active_page . " = (\$row['" . $active_page . "']) ? 0 : 1;\n";
                    $content .= "\t\t\$query = 'UPDATE " . $tablename_save . " SET " . $active_page . "=' . intval(\$" . $active_page . ") . ' WHERE " . $primary . "=' . \$" . $primary . ";\n";
                    $content .= "\t\t\$db->query(\$query);\n";
                    $content .= "\t\t\$content = 'OK_' . \$" . $primary . ";\n";
                    $content .= "\t}\n";
                    $content .= "\t\$nv_Cache->delMod(\$module_name);\n";
                    $content .= "\tinclude NV_ROOTDIR . '/includes/header.php';\n";
                    $content .= "\techo \$content;\n";
                    $content .= "\tinclude NV_ROOTDIR . '/includes/footer.php';\n";
                    $content .= "}\n\n";
                }

                if (!empty($weight_page)) {
                    // neu co cot weight
                    $content .= "if (\$nv_Request->isset_request('ajax_action', 'post')) ";
                    $content .= "{\n";
                    $content .= "\t\$" . $primary . " = \$nv_Request->get_int('" . $primary . "', 'post', 0);\n";
                    $content .= "\t\$new_vid = \$nv_Request->get_int('new_vid', 'post', 0);\n";
                    $content .= "\t\$content = 'NO_' . \$" . $primary . ";\n";
                    $content .= "\tif (\$new_vid > 0) {\n";
                    $content .= "\t\t\$sql = 'SELECT " . $primary . " FROM " . $tablename_save . " WHERE " . $primary . "!=' . \$" . $primary . " . ' ORDER BY " . $weight_page . " ASC';\n";
                    $content .= "\t\t\$result = \$db->query(\$sql);\n";
                    $content .= "\t\t\$" . $weight_page . " = 0;\n";
                    $content .= "\t\twhile (\$row = \$result->fetch()) {\n";
                    $content .= "\t\t\t++\$" . $weight_page . ";\n";
                    $content .= "\t\t\tif (\$" . $weight_page . " == \$new_vid) {\n";
                    $content .= "\t\t\t\t++\$" . $weight_page . ";\n";
                    $content .= "\t\t\t}\n";
                    $content .= "\t\t\t\$sql = 'UPDATE " . $tablename_save . " SET " . $weight_page . "=' . \$" . $weight_page . " . ' WHERE " . $primary . "=' . \$row['" . $primary . "'];\n";
                    $content .= "\t\t\t\$db->query(\$sql);\n";
                    $content .= "\t\t}\n";
                    $content .= "\t\t\$sql = 'UPDATE " . $tablename_save . " SET " . $weight_page . "=' . \$new_vid . ' WHERE " . $primary . "=' . \$" . $primary . ";\n";
                    $content .= "\t\t\$db->query(\$sql);\n";
                    $content .= "\t\t\$content = 'OK_' . \$" . $primary . ";\n";
                    $content .= "\t}\n";
                    $content .= "\t\$nv_Cache->delMod(\$module_name);\n";
                    $content .= "\tinclude NV_ROOTDIR . '/includes/header.php';\n";
                    $content .= "\techo \$content;\n";
                    $content .= "\tinclude NV_ROOTDIR . '/includes/footer.php';\n";
                    $content .= "}\n\n";
                }
            }

            if ($type_addfun == 0 or $type_addfun == 1) {
                $content .= "if (\$nv_Request->isset_request('delete_" . $primary . "', 'get') and \$nv_Request->isset_request('delete_checkss', 'get')) {\n";
                $content .= "\t\$" . $primary . " = \$nv_Request->get_int('delete_" . $primary . "', 'get');\n";
                $content .= "\t\$delete_checkss = \$nv_Request->get_string('delete_checkss', 'get');\n";
                $content .= "\tif (\$" . $primary . " > 0 and \$delete_checkss == md5(\$" . $primary . " . NV_CACHE_PREFIX . \$client_info['session_id'])) {\n";
                if (!empty($weight_page)) {
                    $content .= "\t\t\$" . $weight_page . " = 0;\n";
                    $content .= "\t\t\$sql = 'SELECT " . $weight_page . " FROM " . $tablename_save . " WHERE " . $primary . " =' . \$db->quote($" . $primary . ");\n";
                    $content .= "\t\t\$result = \$db->query(\$sql);\n";
                    $content .= "\t\tlist(\$" . $weight_page . ") = \$result->fetch(3);\n";
                    $content .= "\t\t\n";
                }

                $content .= "\t\t\$db->query('DELETE FROM " . $tablename_save . "  WHERE " . $primary . " = ' . \$db->quote($" . $primary . "));\n";
                if (!empty($weight_page)) {
                    $content .= "\t\tif (\$" . $weight_page . " > 0) ";
                    $content .= "\t\t{\n";
                    $content .= "\t\t\t\$sql = 'SELECT " . $primary . ", " . $weight_page . " FROM " . $tablename_save . " WHERE " . $weight_page . " >' . \$" . $weight_page . ";\n";
                    $content .= "\t\t\t\$result = \$db->query(\$sql);\n";
                    $content .= "\t\t\twhile (list(\$" . $primary . ", \$" . $weight_page . ") = \$result->fetch(3)) {\n";
                    $content .= "\t\t\t\t\$" . $weight_page . "--;\n";
                    $content .= "\t\t\t\t\$db->query('UPDATE " . $tablename_save . " SET " . $weight_page . "=' . \$" . $weight_page . " . ' WHERE " . $primary . "=' . intval(\$" . $primary . "));\n";
                    $content .= "\t\t\t}\n";
                    $content .= "\t\t}\n";
                }
                $content .= "\t\t\$nv_Cache->delMod(\$module_name);\n";
                $content .= "\t\tnv_insert_logs(NV_LANG_DATA, \$module_name, 'Delete " . nv_ucfirst($funname) . "', 'ID: ' . \$" . $primary . ", \$admin_info['userid']);\n";
                $content .= "\t\tnv_redirect_location(" . $nv_url . " . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . \$module_name . '&' . NV_OP_VARIABLE . '=' . \$op);\n";
                $content .= "\t}\n";
                $content .= "}\n\n";
            }

            $content .= "\$row = [];\n";
            $content .= "\$error = [];\n";
            if ($type_addfun == 0 or $type_addfun == 2) {
                if (!empty($primary)) {
                    $content .= "\$row['" . $primary . "'] = \$nv_Request->get_int('" . $primary . "', 'post,get', 0);\n";
                }

                $content .= "if (\$nv_Request->isset_request('submit', 'post')) {\n";

                foreach ($array_columns as $key => $column) {
                    if ($key == $primary) {
                        continue;
                    }
                    $_tmp_key_insert[] = ':' . $key;

                    if (!isset($array_hiddens[$key]) or isset($array_requireds[$key])) {
                        $_tmp_key_update[] = $key . ' = :' . $key;
                        $_view_type = $array_views[$key];

                        $txt_bindParam .= "\t\t\t\$stmt->bindParam(':" . $key . "', \$row['" . $key . "'], PDO::PARAM_";

                        // Từ kiểu dữ liệu sẽ bắt biến theo cách đó dù cho form chọn kiểu ghì
                        if (strpos($column['data_type'], 'text') !== false) {
                            $txt_bindParam .= "STR, strlen(\$row['" . $key . "'])";

                            if ($_view_type == 'editor') {
                                $_tmp_key_editor[] = $key;
                                $txt_post .= "\t\$row['" . $key . "'] = \$nv_Request->get_editor('" . $key . "', '', NV_ALLOWED_HTML_TAGS);\n";
                            } else {
                                $_tmp_key_textarea[] = $key;
                                $txt_post .= "\t\$row['" . $key . "'] = \$nv_Request->get_textarea('" . $key . "', '', NV_ALLOWED_HTML_TAGS);\n";
                            }

                        } elseif (strpos($column['data_type'], 'int') !== false) {
                            if ($_view_type == 'date' or $_view_type == 'time') {
                                $txt_date_view .= "\nif (empty(\$row['" . $key . "'])) ";
                                $txt_date_view .= "{\n";
                                $txt_date_view .= "\t\$row['" . $key . "'] = '';\n";
                                $txt_date_view .= "}\n";
                                $txt_date_view .= "else\n";
                                $txt_date_view .= "{\n";
                                $txt_date_view .= "\t\$row['" . $key . "'] = date('d/m/Y', \$row['" . $key . "']);\n";
                                $txt_date_view .= "}\n";

                                $txt_post .= "\tif (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})\$/', \$nv_Request->get_string('" . $key . "', 'post'), \$m)) ";
                                $txt_post .= "\t{\n";
                                if ($_view_type == 'time') {
                                    $txt_post .= "\t\t\$_hour = \$nv_Request->get_int('" . $key . "_hour', 'post');\n";
                                    $txt_post .= "\t\t\$_min = \$nv_Request->get_int('" . $key . "_min', 'post');\n";
                                } else {
                                    $txt_post .= "\t\t\$_hour = 0;\n";
                                    $txt_post .= "\t\t\$_min = 0;\n";
                                }
                                $txt_post .= "\t\t\$row['" . $key . "'] = mktime(\$_hour, \$_min, 0, \$m[2], \$m[1], \$m[3]);\n";
                                $txt_post .= "\t}\n";
                                $txt_post .= "\telse\n";
                                $txt_post .= "\t{\n";
                                $txt_post .= "\t\t\$row['" . $key . "'] = 0;\n";
                                $txt_post .= "\t}\n";
                            } else {
                                $txt_post .= "\t\$row['" . $key . "'] = \$nv_Request->get_int('" . $key . "', 'post', 0);\n";
                            }
                            $txt_bindParam .= "INT";
                        } elseif ($_view_type == 'checkbox_groups') {
                            $txt_post .= "\n\t\$_groups_post = \$nv_Request->get_array('" . $key . "', 'post', []);\n";
                            $txt_post .= "\t\$row['" . $key . "'] = !empty(\$_groups_post) ? implode(',', nv_groups_post(array_intersect(\$_groups_post, array_keys(\$groups_list)))) : '';\n";
                            $txt_bindParam .= "STR";
                        } elseif ($_view_type == 'checkbox') {
                            $txt_post .= "\n\t\$_" . $key . " = \$nv_Request->get_array('" . $key . "', 'post');\n";
                            $txt_post .= "\t\$row['" . $key . "'] = !empty(\$_" . $key . ") ? implode(',', \$_" . $key . ") : '';\n";
                            $txt_bindParam .= "STR";
                        } else {
                            $txt_post .= "\t\$row['" . $key . "'] = \$nv_Request->get_title('" . $key . "', 'post', '');\n";
                            if ($_view_type == 'textfile') {
                                $_tmp_key_file[] = $key;
                                $txt_post .= "\tif (is_file(NV_DOCUMENT_ROOT . \$row['" . $key . "'])) ";
                                $txt_post .= "\t{\n";
                                $txt_post .= "\t\t\$row['" . $key . "'] = substr(\$row['" . $key . "'], strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . \$module_upload . '/'));\n";
                                $txt_post .= "\t} else {\n";
                                $txt_post .= "\t\t\$row['" . $key . "'] = '';\n";
                                $txt_post .= "\t}\n";
                            } elseif ($_view_type == 'textalias' and !isset($array_field_js['textalias'])) {
                                $txt_post .= "\t\$row['" . $key . "'] = (empty(\$row['" . $key . "'])) ? change_alias(\$row['title']) : change_alias(\$row['" . $key . "']);\n";
                                $array_field_js['textalias'] = $key;
                            }

                            $txt_bindParam .= "STR";
                        }
                        $txt_bindParam .= ");\n";

                        if ($_view_type == 'email') {
                            // Kiểm tra các biến nếu là email
                            $array_check_error[] = "if (!empty(\$row['" . $key . "']) and (\$error_email = nv_check_valid_email(\$row['" . $key . "'])) != '') {\n\t\t\$error[] = \$error_email;\n\t}";
                        } elseif ($_view_type == 'url') {
                            // Kiểm tra các biến nếu là url
                            $array_check_error[] = "if (!empty(\$row['" . $key . "']) and !nv_is_url(\$row['" . $key . "'])) {\n\t\t\$error[] = \$lang_module['error_url_" . $key . "'];\n\t}";

                            if (!isset($lang_mod_admin_vi['error_url_' . $key]) and !isset($lang_mod_admin_vi_new['error_url_' . $key])) {
                                $lang_mod_admin_vi_new['error_url_' . $key] = "Lỗi: url ' . \$lang_module['" . $key . "'] không đúng";
                            }

                            if (!isset($lang_mod_admin_en['error_url_' . $key]) and !isset($lang_mod_admin_en_new['error_url_' . $key])) {
                                $lang_mod_admin_en_new['error_url_' . $key] = "Error: Url ' . \$lang_module['" . $key . "']";
                            }
                        }
                    } else {
                        if (strpos($column['data_type'], 'int') !== false) {
                            if ($column['column_name'] == $weight_page) {
                                $txt_bindParam_default .= "\t\t\t\t\$weight = \$db->query('SELECT max(" . $weight_page . ") FROM " . $tablename_save . "')->fetchColumn();\n";
                                $txt_bindParam_default .= "\t\t\t\t\$weight = intval(\$weight) + 1;\n";
                                $txt_bindParam_default .= "\t\t\t\t\$stmt->bindParam(':" . $key . "', \$weight, PDO::PARAM_INT);\n\n";
                            } elseif ($column['column_name'] == $active_page) {
                                $txt_bindParam_default .= "\t\t\t\t\$stmt->bindValue(':" . $key . "', 1, PDO::PARAM_INT);\n\n";
                            } else {
                                $content_default .= "\t\t\t\t\$row['" . $key . "'] = " . intval($column['column_default']) . ";\n";
                                $txt_bindParam_default .= "\t\t\t\t\$stmt->bindParam(':" . $key . "', \$row['" . $key . "'], PDO::PARAM_INT);\n";
                            }
                        } else {
                            if ($_view_type == 'checkbox_groups') {
                                $content_default .= "\t\t\t\t\$row['" . $key . "'] = '6';\n";
                            } else {
                                $content_default .= "\t\t\t\t\$row['" . $key . "'] = '" . $column['column_default'] . "';\n";
                            }
                            $txt_bindParam_default .= "\t\t\t\t\$stmt->bindParam(':" . $key . "', \$row['" . $key . "'], PDO::PARAM_STR);\n";
                        }
                    }
                }

                $content .= $txt_post;

                if (!empty($array_check_error)) {
                    $content .= "\n\t" . implode(" else", $array_check_error) . "\n";
                }

                // Begin try catch
                $content .= "\n\tif (empty(\$error)) {\n";
                $content .= "\t\ttry {\n";
                $content .= "\t\t\tif (empty(\$row['" . $primary . "'])) {\n";
                if (!empty($content_default)) {
                    $content .= $content_default . "\n";
                }
                $content .= "\t\t\t\t\$stmt = \$db->prepare('";
                $content .= 'INSERT INTO ' . $tablename_save . ' (' . implode(', ', array_keys($array_views)) . ') VALUES (' . implode(', ', $_tmp_key_insert) . ')';
                $content .= "');\n";
                if (!empty($txt_bindParam_default)) {
                    $content .= "\n" . $txt_bindParam_default . "\n";
                }
                $content .= "\t\t\t}";
                if (!empty($primary)) {
                    $content .= " else {\n";
                    $content .= "\t\t\t\t\$stmt = \$db->prepare('";
                    $content .= 'UPDATE ' . $tablename_save . ' SET ' . implode(', ', $_tmp_key_update) . ' WHERE ' . $primary . '=\' . $row[\'' . $primary . '\']';
                    $content .= ");\n";
                    $content .= "\t\t\t}";
                }
                $content .= "\n";

                $content .= $txt_bindParam;
                $content .= "\n";
                $content .= "\t\t\t\$exc = \$stmt->execute();";
                $content .= "\n\t\t\tif (\$exc) {\n";
                $content .= "\t\t\t\t\$nv_Cache->delMod(\$module_name);\n";

                $content .= "\t\t\t\tif (empty(\$row['" . $primary . "'])) {\n";
                $content .= "\t\t\t\t\tnv_insert_logs(NV_LANG_DATA, \$module_name, 'Add " . nv_ucfirst($funname) . "', ' ', \$admin_info['userid']);\n";
                $content .= "\t\t\t\t} else {\n";
                $content .= "\t\t\t\t\tnv_insert_logs(NV_LANG_DATA, \$module_name, 'Edit " . nv_ucfirst($funname) . "', 'ID: ' . \$row['" . $primary . "'], \$admin_info['userid']);\n";
                $content .= "\t\t\t\t}\n";
                $content .= "\t\t\t\tnv_redirect_location(" . $nv_url . " . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . \$module_name . '&' . NV_OP_VARIABLE . '=' . \$op);\n";
                $content .= "\t\t\t}\n";
                // end try catch
                $content .= "\t\t} catch(PDOException \$e) {\n";
                $content .= "\t\t\ttrigger_error(\$e->getMessage());\n";
                $content .= "\t\t\tdie(\$e->getMessage()); //Remove this line after checks finished\n";
                $content .= "\t\t}\n";
                $content .= "\t}\n";

                $content .= "} ";
                if (!empty($primary)) {
                    $content .= "elseif (\$row['" . $primary . "'] > 0) {\n";
                    $content .= "\t\$row = \$db->query('SELECT * FROM " . $tablename_save . " WHERE " . $primary . "=' . \$row['" . $primary . "'])->fetch();";
                    $content .= "\n\tif (empty(\$row)) {\n";
                    $content .= "\t\tnv_redirect_location(" . $nv_url . " . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . \$module_name . '&' . NV_OP_VARIABLE . '=' . \$op);\n";
                    $content .= "\t}\n";
                    $content .= "} ";
                }
                $content .= "else {\n";

                foreach ($array_columns as $key => $_row) {
                    if (!isset($array_hiddens[$key]) or isset($array_requireds[$key])) {
                        if (strpos($_row['data_type'], 'int') !== false) {
                            $content .= "\t\$row['" . $key . "'] = " . intval($_row['column_default']) . ";\n";
                        } elseif ($array_views[$key] == 'checkbox_groups') {
                            $content .= "\t\$row['" . $key . "'] = '6';\n";
                        } else {
                            $content .= "\t\$row['" . $key . "'] = '" . $_row['column_default'] . "';\n";
                        }
                    }
                }
                $content .= "}\n";

                $content .= $txt_date_view;

                // Gán lại giá trị cho chọn file
                foreach ($_tmp_key_file as $key) {
                    $content .= "if (!empty(\$row['" . $key . "']) and is_file(NV_UPLOADS_REAL_DIR . '/' . \$module_upload . '/' . \$row['" . $key . "'])) {\n";
                    $content .= "\t\$row['" . $key . "'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . \$module_upload . '/' . \$row['" . $key . "'];\n";
                    $content .= "}\n";
                }

                // Gán lại giá trị cho textarea
                if (!empty($_tmp_key_textarea)) {
                    $content .= "\n";
                    foreach ($_tmp_key_textarea as $key) {
                        $content .= "\$row['" . $key . "'] = nv_htmlspecialchars(nv_br2nl(\$row['" . $key . "']));\n";
                    }
                    $content .= "\n";
                }

                // Gán lại giá trị cho trình soạn thảo
                if (!empty($_tmp_key_editor)) {
                    if ($setfunction == 1) {
                        // Trình soạn thảo ngoài site
                        $content_function_editor = file_get_contents(NV_ROOTDIR . '/modules/' . $module_file . '/modules/template_editor_site.php');
                        if (preg_match('/\/\/\>\>[\r\n]+(.*?)[\r\n]+\/\/\<\</isu', $content_function_editor, $m)) {
                            $content .= $m[1] . "\n\n";
                        }
                        foreach ($_tmp_key_editor as $key) {
                            $content .= "\$row['" . $key . "'] = nv_htmlspecialchars(nv_editor_br2nl(\$row['" . $key . "']));\n";
                            $content .= "\$row['" . $key . "'] = nv_module_aleditor('" . $key . "', '100%', '300px', \$row['" . $key . "']);\n";
                        }
                    } else {
                        // Trình soạn thảo trong admin
                        $content .= "if (defined('NV_EDITOR')) {\n\trequire_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';\n}\n\n";
                        foreach ($_tmp_key_editor as $key) {
                            $content .= "\$row['" . $key . "'] = nv_htmlspecialchars(nv_editor_br2nl(\$row['" . $key . "']));\n";
                            $content .= "if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {\n";
                            $content .= "\t\$row['" . $key . "'] = nv_aleditor('" . $key . "', '100%', '300px', \$row['" . $key . "']);\n";
                            $content .= "} else {\n";
                            $content .= "\t\$row['" . $key . "'] = '<textarea style=\"width:100%;height:300px\" name=\"" . $key . "\">' . \$row['" . $key . "'] . '</textarea>';\n";
                            $content .= "}\n\n";
                        }
                    }
                }
            }

            // lay ve mang doi voi truong co select dữ liệu
            if (!empty($choicesql_module) && !empty($choicesql_table) && !empty($choicesql_column_key) && !empty($choicesql_column_val)) {
                foreach ($choicesql_module as $key => $value) {
                    if (!empty($value)) {
                        if (preg_match('/^' . nv_preg_quote(NV_PREFIXLANG) . '\_' . nv_preg_quote($value) . '(.*)$/i', $choicesql_table[$key], $m)) {
                            $table_sql = "' . NV_PREFIXLANG . '_" . $value . $m[1];
                        } elseif (preg_match('/^' . nv_preg_quote($db_config['prefix']) . '\_' . nv_preg_quote($value) . '(.*)$/i', $choicesql_table[$key], $m)) {
                            $table_sql = "' . \$db_config['prefix'] . '_" . $value . $m[1];
                        } else {
                            $table_sql = $choicesql_table[$key];
                        }
                        $content .= "\$array_" . $key . "_" . $value . " = [];\n";
                        $content .= "\$_sql = 'SELECT " . $choicesql_column_key[$key] . ", " . $choicesql_column_val[$key] . " FROM " . $table_sql . "';\n";
                        $content .= "\$_query = \$db->query(\$_sql);\n";
                        $content .= "while (\$_row = \$_query->fetch()) {\n";
                        $content .= "\t\$array_" . $key . "_" . $value . "[\$_row['" . $choicesql_column_key[$key] . "']] = \$_row;\n";
                        $content .= "}\n\n";
                    }
                }
            }

            // lay ve mang doi voi truong co select bang tay
            if (!empty($field_choice) && !empty($field_choice_text)) {
                foreach ($field_choice as $key_column => $values) {
                    if (!empty($field_choice[$key_column][1])) {
                        $content .= "\n\$array_" . $key_column . " = [];\n";
                    }
                    foreach ($values as $key => $value) {
                        if (!empty($value)) {
                            $content .= "\$array_" . $key_column . "[" . $value . "] = '" . $field_choice_text[$key_column][$key] . "';\n";
                        }
                    }
                }
            }

            if (($type_addfun == 0 or $type_addfun == 1) and !empty($array_listviews)) {
                $search_column = [];
                if ($search_page) {
                    $content .= "\n\$q = \$nv_Request->get_title('q', 'post,get');\n";
                    foreach ($array_listviews as $key => $_tmp) {
                        $search_column[] = $key . ' LIKE :q_' . $key;
                    }
                }
                $content .= "\n// Fetch Limit\n";
                $content .= "\$show_view = false;\n";
                $content .= "if (!\$nv_Request->isset_request('id', 'post,get')) {\n";
                $content .= "\t\$show_view = true;\n";
                if ($generate_page or !empty($weight_page)) {
                    $content .= "\t\$per_page = 20;\n";
                    $content .= "\t\$page = \$nv_Request->get_int('page', 'post,get', 1);\n";
                    $content .= "\t\$db->sqlreset()\n";
                    $content .= "\t\t->select('COUNT(*)')\n";
                    $content .= "\t\t->from('" . $tablename_save . "');\n";
                    if ($search_page) {
                        $content .= "\n\tif (!empty(\$q)) {\n";
                        $content .= "\t\t\$db->where('" . implode(' OR ', $search_column) . "');\n";
                        $content .= "\t}\n";
                    }
                    // Query Prepare
                    $content .= "\t\$sth = \$db->prepare(\$db->sql());\n";
                    if ($search_page) {
                        $content .= "\n\tif (!empty(\$q)) {\n";

                        foreach ($array_listviews as $key => $_tmp) {
                            $content .= "\t\t\$sth->bindValue(':q_" . $key . "', '%' . \$q . '%');\n";
                        }
                        $content .= "\t}\n";
                    }
                    $content .= "\t\$sth->execute();\n";

                    $content .= "\t\$num_items = \$sth->fetchColumn();\n\n";
                    $content .= "\t\$db->select('*')\n";
                    if (!empty($weight_page)) {
                        $content .= "\t\t->order('" . $weight_page . " ASC')\n";
                    } else {
                        $content .= "\t\t->order('" . $primary . " DESC')\n";
                    }
                    $content .= "\t\t->limit(\$per_page)\n";
                    $content .= "\t\t->offset((\$page - 1) * \$per_page);\n";
                    $content .= "\t\$sth = \$db->prepare(\$db->sql());\n";
                    if ($search_page) {
                        $content .= "\n\tif (!empty(\$q)) {\n";
                        foreach ($array_listviews as $key => $_tmp) {
                            $content .= "\t\t\$sth->bindValue(':q_" . $key . "', '%' . \$q . '%');\n";
                        }
                        $content .= "\t}\n";
                    }
                } else {
                    $content .= "\t\$db->sqlreset()\n";
                    $content .= "\t\t->select('*')\n";
                    $content .= "\t\t->from('" . $tablename_save . "')\n";
                    if (!empty($weight_page)) {
                        $content .= "\t\t->order('" . $weight_page . " ASC');\n";
                    } else {
                        $content .= "\t\t->order('" . $primary . " DESC');\n";
                    }
                    if ($search_page) {
                        $content .= "\n\tif (!empty(\$q)) {\n";
                        $content .= "\t\t\$db->where('" . implode(' OR ', $search_column) . "');\n";
                        $content .= "\t}\n";
                    }
                    $content .= "\t\$sth = \$db->prepare(\$db->sql());\n";
                    if ($search_page) {
                        $content .= "\n\tif (!empty(\$q)) {\n";
                        foreach ($array_listviews as $key => $_tmp) {
                            $content .= "\t\t\$sth->bindValue(':q_" . $key . "', '%' . \$q . '%');\n";
                        }
                        $content .= "\t}\n";
                    }
                }

                $content .= "\t\$sth->execute();\n";
                $content .= "}\n\n";
            }

            if ($setfunction) {
                $content .= "\$xtpl = new XTemplate('" . $funname . ".tpl', NV_ROOTDIR . '/themes/' . \$module_info['template'] . '/modules/' . \$module_file);\n";
            } else {
                $content .= "\$xtpl = new XTemplate('" . $funname . ".tpl', NV_ROOTDIR . '/themes/' . \$global_config['module_theme'] . '/modules/' . \$module_file);\n";
            }
            $content .= "\$xtpl->assign('LANG', \$lang_module);\n";
            $content .= "\$xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);\n";
            $content .= "\$xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);\n";
            $content .= "\$xtpl->assign('" . $nv_url . "', " . $nv_url . ");\n";
            $content .= "\$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);\n";
            $content .= "\$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);\n";
            $content .= "\$xtpl->assign('MODULE_NAME', \$module_name);\n";
            $content .= "\$xtpl->assign('MODULE_UPLOAD', \$module_upload);\n";
            $content .= "\$xtpl->assign('NV_ASSETS_DIR', NV_ASSETS_DIR);\n";
            $content .= "\$xtpl->assign('OP', \$op);\n";
            $content .= "\$xtpl->assign('ROW', \$row);\n\n";

            // Xử lý dữ liệu select CSDL
            if ($type_addfun == 0 or $type_addfun == 2) {
                if (!empty($choicesql_module) && !empty($choicesql_table) && !empty($choicesql_column_key) && !empty($choicesql_column_val)) {
                    foreach ($choicesql_module as $key => $value) {
                        if (!empty($value)) {
                            if ($array_views[$key] == 'select') {
                                $content .= "foreach (\$array_" . $key . "_" . $value . " as \$value) {\n";
                                $content .= "\t\$xtpl->assign('OPTION', [\n";
                                $content .= "\t\t'key' => \$value['" . $choicesql_column_key[$key] . "'],\n";
                                $content .= "\t\t'title' => \$value['" . $choicesql_column_val[$key] . "'],\n";
                                $content .= "\t\t'selected' => (\$value['" . $choicesql_column_key[$key] . "'] == \$row['" . $key . "']) ? ' selected=\"selected\"' : ''\n";
                                $content .= "\t]);\n";
                                $content .= "\t\$xtpl->parse('main.select_" . $key . "');\n";
                                $content .= "}\n";
                            } elseif ($array_views[$key] == 'radio' or $array_views[$key] == 'checkbox') {
                                $content .= "foreach (\$array_" . $key . "_" . $value . " as \$key => \$value) {\n";
                                $content .= "\t\$xtpl->assign('OPTION', [\n";
                                $content .= "\t\t'key' => \$value['" . $choicesql_column_key[$key] . "'],\n";
                                $content .= "\t\t'title' => \$value['" . $choicesql_column_val[$key] . "'],\n";
                                $content .= "\t\t'checked' => (\$value['" . $choicesql_column_key[$key] . "'] == \$row['" . $key . "']) ? ' checked=\"checked\"' : ''\n";
                                $content .= "\t]);\n";
                                $content .= "\t\$xtpl->parse('main." . $array_views[$key] . "_" . $key . "');\n";
                                $content .= "}\n";
                            } elseif ($array_views[$key] == 'checkbox_groups') {
                                $content .= "\n\$" . $key . " = explode(',', \$row['" . $key . "']);\n";
                                $content .= "foreach (\$groups_list as \$key => \$title) {\n";
                                $content .= "\t\$xtpl->assign('OPTION', [\n";
                                $content .= "\t\t'key' => \$key,\n";
                                $content .= "\t\t'title' => \$title,\n";
                                $content .= "\t\t'checked' => in_array(\$key, \$" . $key . ") ? ' checked=\"checked\"' : ''\n";
                                $content .= "\t]);\n";
                                $content .= "\t\$xtpl->parse('main." . $key . "');\n";
                                $content .= "}\n";
                            }
                        }
                    }
                }

                // Lay tu du lieu nhap bang tay
                if (!empty($field_choice) && !empty($field_choice_text)) {
                    foreach ($field_choice as $key_column => $values) {
                        if (!empty($field_choice[$key_column][1])) {
                            if ($array_views[$key_column] == 'select') {
                                $content .= "\nforeach (\$array_" . $key_column . " as \$key => \$title) {\n";
                                $content .= "\t\$xtpl->assign('OPTION', [\n";
                                $content .= "\t\t'key' => \$key,\n";
                                $content .= "\t\t'title' => \$title,\n";
                                $content .= "\t\t'selected' => (\$key == \$row['" . $key_column . "']) ? ' selected=\"selected\"' : ''\n";
                                $content .= "\t]);\n";
                                $content .= "\t\$xtpl->parse('main." . $array_views[$key_column] . "_" . $key_column . "');\n";
                                $content .= "}\n";
                            } elseif ($array_views[$key_column] == 'radio' or $array_views[$key_column] == 'checkbox') {
                                $content .= "\nforeach (\$array_" . $key_column . " as \$key => \$title) {\n";
                                $content .= "\t\$xtpl->assign('OPTION', [\n";
                                $content .= "\t\t'key' => \$key,\n";
                                $content .= "\t\t'title' => \$title,\n";
                                $content .= "\t\t'checked' => (\$key == \$row['" . $key_column . "']) ? ' checked=\"checked\"' : ''\n";
                                $content .= "\t]);\n";
                                $content .= "\t\$xtpl->parse('main." . $array_views[$key_column] . "_" . $key_column . "');\n";
                                $content .= "}\n";
                            } elseif ($array_views[$key_column] == 'checkbox_groups') {
                                $content .= "\n\$" . $key_column . " = explode(',', \$row['" . $key_column . "']);\n";
                                $content .= "foreach (\$groups_list as \$key => \$title) {\n";
                                $content .= "\t\$xtpl->assign('OPTION', [\n";
                                $content .= "\t\t'key' => \$key,\n";
                                $content .= "\t\t'title' => \$title,\n";
                                $content .= "\t\t'checked' => in_array(\$key, \$" . $key_column . ") ? ' checked=\"checked\"' : ''\n";
                                $content .= "\t]);\n";
                                $content .= "\t\$xtpl->parse('main." . $key_column . "');\n";
                                $content .= "}\n";
                            }
                        }
                    }
                }
            }

            if (($type_addfun == 0 or $type_addfun == 1) and !empty($array_listviews)) {
                if ($search_page) {
                    $content .= "\$xtpl->assign('Q', \$q);\n";
                }

                $content .= "\nif (\$show_view) {\n";
                if ($generate_page) {
                    $content .= "\t\$base_url = " . $nv_url . " . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . \$module_name . '&amp;' . NV_OP_VARIABLE . '=' . \$op;\n";
                    if ($search_page) {
                        $content .= "\tif (!empty(\$q)) {\n";
                        $content .= "\t\t\$base_url .= '&q=' . \$q;\n";
                        $content .= "\t}\n";
                    }
                    $content .= "\t\$generate_page = nv_generate_page(\$base_url, \$num_items, \$per_page, \$page);\n";
                    $content .= "\tif (!empty(\$generate_page)) {\n";
                    $content .= "\t\t\$xtpl->assign('NV_GENERATE_PAGE', \$generate_page);\n";
                    $content .= "\t\t\$xtpl->parse('main.view.generate_page');\n";
                    $content .= "\t}\n";
                }
                if ($generate_page) {
                    $content .= "\t\$number = \$page > 1 ? (\$per_page * (\$page - 1)) + 1 : 1;\n";
                } elseif (empty($weight_page)) {
                    $content .= "\t\$number = 0;\n";
                }
                $content .= "\twhile (\$view = \$sth->fetch())";
                $content .= " {\n";
                if (empty($weight_page)) {
                    $content .= "\t\t\$view['number'] = \$number++;\n";
                } else {
                    $content .= "\t\tfor(\$i = 1; \$i <= \$num_items; ++\$i)";
                    $content .= " {\n";
                    $content .= "\t\t\t\$xtpl->assign('WEIGHT', [\n";
                    $content .= "\t\t\t\t'key' => \$i,\n";
                    $content .= "\t\t\t\t'title' => \$i,\n";
                    $content .= "\t\t\t\t'selected' => (\$i == \$view['" . $weight_page . "']) ? ' selected=\"selected\"' : ''\n";
                    $content .= "\t\t\t]);\n";
                    $content .= "\t\t\t\$xtpl->parse('main.view.loop." . $weight_page . "_loop');\n";
                    $content .= "\t\t}\n";
                }
                if (!empty($active_page)) {
                    $content .= "\t\t\$xtpl->assign('CHECK', \$view['" . $active_page . "'] == 1 ? 'checked' : '');\n";
                }
                foreach ($array_views as $key => $input_type_i) {
                    if (!isset($array_hiddens[$key]) or isset($array_requireds[$key])) {
                        if ($input_type_i == 'date') {
                            $content .= "\t\t\$view['" . $key . "'] = (empty(\$view['" . $key . "'])) ? '' : nv_date('d/m/Y', \$view['" . $key . "']);\n";
                        } elseif ($input_type_i == 'time') {
                            $content .= "\t\t\$view['" . $key . "'] = (empty(\$view['" . $key . "'])) ? '' : nv_date('H:i d/m/Y', \$view['" . $key . "']);\n";
                        }
                    }
                }

                // /select
                if (!empty($choicesql_module) && !empty($choicesql_table) && !empty($choicesql_column_key) && !empty($choicesql_column_val)) {
                    foreach ($choicesql_module as $key => $value) {
                        if (!empty($value)) {
                            if ($array_views[$key] != 'checkbox') {
                                $content .= "\t\t\$view['" . $key . "'] = \$array_" . $key . "_" . $value . "[\$view['" . $key . "']]['" . $choicesql_column_val[$key] . "'];\n";
                            }
                        }
                    }
                }

                if (!empty($field_choice) && !empty($field_choice_text)) {
                    foreach ($field_choice as $key_column => $values) {
                        if (!empty($field_choice[$key_column][1])) {
                            if ($array_views[$key_column] != 'checkbox') {
                                $content .= "\t\t\$view['" . $key_column . "'] = \$array_" . $key_column . "[\$view['" . $key_column . "']];\n";
                            }
                        }
                    }
                }
                $content .= "\t\t\$view['link_edit'] = " . $nv_url . " . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . \$module_name . '&amp;' . NV_OP_VARIABLE . '=' . \$op . '&amp;" . $primary . "=' . \$view['" . $primary . "'];\n";
                $content .= "\t\t\$view['link_delete'] = " . $nv_url . " . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . \$module_name . '&amp;' . NV_OP_VARIABLE . '=' . \$op . '&amp;delete_" . $primary . "=' . \$view['" . $primary . "'] . '&amp;delete_checkss=' . md5(\$view['" . $primary . "'] . NV_CACHE_PREFIX . \$client_info['session_id']);\n";
                $content .= "\t\t\$xtpl->assign('VIEW', \$view);\n";
                $content .= "\t\t\$xtpl->parse('main.view.loop');\n";
                $content .= "\t}\n";
                $content .= "\t\$xtpl->parse('main.view');\n";
                $content .= "}\n\n";
            }

            $content .= "\nif (!empty(\$error)) {\n";
            $content .= "\t\$xtpl->assign('ERROR', implode('<br />', \$error));\n";
            $content .= "\t\$xtpl->parse('main.error');\n";
            $content .= "}\n";
            if (isset($array_field_js['textalias'])) {
                $content .= "if (empty(\$row['" . $primary . "'])) {\n";
                $content .= "\t\$xtpl->parse('main.auto_get_alias');\n";
                $content .= "}\n";
            }
            $content .= "\n";
            $content .= "\$xtpl->parse('main');\n";
            $content .= "\$contents = \$xtpl->text('main');\n\n";

            $content .= "\$page_title = \$lang_module['" . $funname . "'];\n\n";
            $content .= "include NV_ROOTDIR . '/includes/header.php';\n";
            if ($setfunction == 1) {
                $content .= "echo nv_site_theme(\$contents);\n";
            } else {
                $content .= "echo nv_admin_theme(\$contents);\n";
            }

            $content .= "include NV_ROOTDIR . '/includes/footer.php';";
            if ($setfunction == 1) {
                $folder = "funcs";
            } else {
                $folder = "admin";
            }

            file_put_contents(NV_ROOTDIR . "/modules/" . $modname . "/" . $folder . "/" . $funname . ".php", str_replace("\t", "    ", $content) . "\n", LOCK_EX);
            if (substr($sys_info['os'], 0, 3) != 'WIN')
                chmod(NV_ROOTDIR . "/modules/" . $modname . "/" . $folder . "/" . $funname . ".php", 0777);

            // write file tpl
            $content_1 = "<!-- BEGIN: main -->\n";
            if (($type_addfun == 0 or $type_addfun == 1) and !empty($array_listviews)) {
                // listviews
                $content_1 .= "<!-- BEGIN: view -->\n";
                if ($search_page) {
                    $content_1 .= "<div class=\"well\">\n";
                    $content_1 .= "<form action=\"{" . $nv_url . "}index.php\" method=\"get\">\n";
                    $content_1 .= "\t<input type=\"hidden\" name=\"{NV_LANG_VARIABLE}\"  value=\"{NV_LANG_DATA}\" />\n";
                    $content_1 .= "\t<input type=\"hidden\" name=\"{NV_NAME_VARIABLE}\"  value=\"{MODULE_NAME}\" />\n";
                    $content_1 .= "\t<input type=\"hidden\" name=\"{NV_OP_VARIABLE}\"  value=\"{OP}\" />\n";
                    $content_1 .= "\t<div class=\"row\">\n";
                    $content_1 .= "\t\t<div class=\"col-xs-24 col-md-6\">\n";
                    $content_1 .= "\t\t\t<div class=\"form-group\">\n";
                    $content_1 .= "\t\t\t\t<input class=\"form-control\" type=\"text\" value=\"{Q}\" name=\"q\" maxlength=\"255\" placeholder=\"{LANG.search_title}\" />\n";
                    $content_1 .= "\t\t\t</div>\n";
                    $content_1 .= "\t\t</div>\n";
                    $content_1 .= "\t\t<div class=\"col-xs-12 col-md-3\">\n";
                    $content_1 .= "\t\t\t<div class=\"form-group\">\n";
                    $content_1 .= "\t\t\t\t<input class=\"btn btn-primary\" type=\"submit\" value=\"{LANG.search_submit}\" />\n";
                    $content_1 .= "\t\t\t</div>\n";
                    $content_1 .= "\t\t</div>\n";
                    $content_1 .= "\t</div>\n";
                    $content_1 .= "</form>\n";
                    $content_1 .= "</div>\n";
                }

                $content_1 .= "<form action=\"{" . $nv_url . "}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}\" method=\"post\">\n";
                $content_1 .= "\t<div class=\"table-responsive\">\n\t\t<table class=\"table table-striped table-bordered table-hover\">\n";
                $content_1 .= "\t\t\t<thead>\n";
                $content_1 .= "\t\t\t\t<tr>\n";
                if (empty($weight_page)) {
                    $content_1 .= "\t\t\t\t\t<th class=\"w100\">{LANG.number}</th>\n";
                } else {
                    $content_1 .= "\t\t\t\t\t<th class=\"w100\">{LANG." . $weight_page . "}</th>\n";
                }
                foreach ($array_listviews as $key => $_tmp) {
                    $content_1 .= "\t\t\t\t\t<th>{LANG." . $key . "}</th>\n";
                }
                if (!empty($active_page)) {
                    $content_1 .= "\t\t\t\t\t<th class=\"w100 text-center\">{LANG.active}</th>\n";
                }
                $content_1 .= "\t\t\t\t\t<th class=\"w150\">&nbsp;</th>\n";
                $content_1 .= "\t\t\t\t</tr>\n";
                $content_1 .= "\t\t\t</thead>\n";
                if ($generate_page) {
                    $content_1 .= "\t\t\t<!-- BEGIN: generate_page -->\n";
                    $content_1 .= "\t\t\t<tfoot>\n";
                    $content_1 .= "\t\t\t\t<tr>\n";
                    if (!empty($active_page)) {
                        $content_1 .= "\t\t\t\t\t<td class=\"text-center\" colspan=\"" . (sizeof($array_listviews) + 3) . "\">{NV_GENERATE_PAGE}</td>\n";
                    } else {
                        $content_1 .= "\t\t\t\t\t<td class=\"text-center\" colspan=\"" . (sizeof($array_listviews) + 2) . "\">{NV_GENERATE_PAGE}</td>\n";
                    }
                    $content_1 .= "\t\t\t\t</tr>\n";
                    $content_1 .= "\t\t\t</tfoot>\n";
                    $content_1 .= "\t\t\t<!-- END: generate_page -->\n";
                }
                $content_1 .= "\t\t\t<tbody>\n";
                $content_1 .= "\t\t\t\t<!-- BEGIN: loop -->\n";
                $content_1 .= "\t\t\t\t<tr>\n";

                if (empty($weight_page)) {
                    $content_1 .= "\t\t\t\t\t<td> {VIEW.number} </td>\n";
                } else {
                    $content_1 .= "\t\t\t\t\t<td>\n\t\t\t\t\t\t<select class=\"form-control\" id=\"id_weight_{VIEW." . $primary . "}\" onchange=\"nv_change_weight('{VIEW." . $primary . "}');\">\n\t\t\t\t\t\t<!-- BEGIN: " . $weight_page . "_loop -->\n\t\t\t\t\t\t\t<option value=\"{WEIGHT.key}\"{WEIGHT.selected}>{WEIGHT.title}</option>\n\t\t\t\t\t\t<!-- END: " . $weight_page . "_loop -->\n\t\t\t\t\t</select>\n\t\t\t\t</td>\n";
                    $js_change_weight = "\tfunction nv_change_weight(id) {\n";
                    $js_change_weight .= "\t\tvar nv_timer = nv_settimeout_disable('id_weight_' + id, 5000);\n";
                    $js_change_weight .= "\t\tvar new_vid = $('#id_weight_' + id).val();\n";
                    $js_change_weight .= "\t\t$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=" . $funname . "&nocache=' + new Date().getTime(), 'ajax_action=1&" . $primary . "=' + id + '&new_vid=' + new_vid, function(res) {\n";
                    $js_change_weight .= "\t\t\tvar r_split = res.split('_');\n";
                    $js_change_weight .= "\t\t\tif (r_split[0] != 'OK') {\n";
                    $js_change_weight .= "\t\t\t\talert(nv_is_change_act_confirm[2]);\n";
                    $js_change_weight .= "\t\t\t}\n";
                    $js_change_weight .= "\t\t\twindow.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=" . $funname . "';\n";
                    $js_change_weight .= "\t\t\treturn;\n";
                    $js_change_weight .= "\t\t});\n";
                    $js_change_weight .= "\t\treturn;\n";
                    $js_change_weight .= "\t}\n";
                    $array_field_js['change_weight'] = $js_change_weight;
                }
                foreach ($array_listviews as $key => $_tmp) {
                    $content_1 .= "\t\t\t\t\t<td> {VIEW." . $key . "} </td>\n";
                }
                if (!empty($active_page)) {
                    $content_1 .= "\t\t\t\t\t<td class=\"text-center\"><input type=\"checkbox\" name=\"" . $active_page . "\" id=\"change_status_{VIEW." . $primary . "}\" value=\"{VIEW." . $primary . "}\" {CHECK} onclick=\"nv_change_status({VIEW." . $primary . "});\" /></td>\n";
                    $js_change_status = "\tfunction nv_change_status(id) {\n";
                    $js_change_status .= "\t\tvar new_status = $('#change_status_' + id).is(':checked') ? true : false;\n";
                    $js_change_status .= "\t\tif (confirm(nv_is_change_act_confirm[0])) {\n";
                    $js_change_status .= "\t\t\tvar nv_timer = nv_settimeout_disable('change_status_' + id, 5000);\n";
                    $js_change_status .= "\t\t\t$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=" . $funname . "&nocache=' + new Date().getTime(), 'change_status=1&" . $primary . "='+id, function(res) {\n";
                    $js_change_status .= "\t\t\t\tvar r_split = res.split('_');\n";
                    $js_change_status .= "\t\t\t\tif (r_split[0] != 'OK') {\n";
                    $js_change_status .= "\t\t\t\t\talert(nv_is_change_act_confirm[2]);\n";
                    $js_change_status .= "\t\t\t\t}\n";
                    $js_change_status .= "\t\t\t});\n";
                    $js_change_status .= "\t\t}\n";
                    $js_change_status .= "\t\telse{\n";
                    $js_change_status .= "\t\t\t$('#change_status_' + id).prop('checked', new_status ? false : true);\n";
                    $js_change_status .= "\t\t}\n";
                    $js_change_status .= "\t\treturn;\n";
                    $js_change_status .= "\t}\n";
                    $array_field_js['change_status'] = $js_change_status;
                }

                $content_1 .= "\t\t\t\t\t<td class=\"text-center\"><i class=\"fa fa-edit fa-lg\">&nbsp;</i> <a href=\"{VIEW.link_edit}#edit\">{LANG.edit}</a> - <em class=\"fa fa-trash-o fa-lg\">&nbsp;</em> <a href=\"{VIEW.link_delete}\" onclick=\"return confirm(nv_is_del_confirm[0]);\">{LANG.delete}</a></td>\n";
                $content_1 .= "\t\t\t\t</tr>\n";
                $content_1 .= "\t\t\t\t<!-- END: loop -->\n";

                $content_1 .= "\t\t\t</tbody>\n";

                $content_1 .= "\t\t</table>\n\t</div>\n";
                $content_1 .= "</form>\n";
                $content_1 .= "<!-- END: view -->\n\n";
            }
            // end listviews

            $content_2 = '';
            if ($type_addfun == 0 or $type_addfun == 2) {
                $content_2 .= "<!-- BEGIN: error -->\n";
                $content_2 .= "<div class=\"alert alert-warning\">{ERROR}</div>\n";
                $content_2 .= "<!-- END: error -->\n";
                $content_2 .= "<div class=\"panel panel-default\">\n";
                $content_2 .= "<div class=\"panel-body\">\n";
                $content_2 .= "<form class=\"form-horizontal\" action=\"{" . $nv_url . "}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}\" method=\"post\">\n";
                if (!empty($primary)) {
                    $content_2 .= "\t<input type=\"hidden\" name=\"" . $primary . "\" value=\"{ROW." . $primary . "}\" />\n";
                }
                foreach ($array_views as $key => $input_type_i) {
                    if (!isset($array_hiddens[$key]) or isset($array_requireds[$key])) {
                        $content_2 .= "\t<div class=\"form-group\">\n";
                        $content_2 .= "\t\t<label class=\"col-sm-5 col-md-4 control-label\"><strong>{LANG." . $key . "}</strong>";
                        if (isset($array_requireds[$key])) {
                            $content_2 .= " <span class=\"red\">(*)</span>";
                        }
                        $content_2 .= "</label>\n";
                        if ($input_type_i == 'textalias' and $array_field_js['textalias'] == $key) {
                            $content_2 .= "\t\t<div class=\"col-sm-19 col-md-18\">\n";
                        } else {
                            $content_2 .= "\t\t<div class=\"col-sm-19 col-md-20\">\n";
                        }

                        if ($input_type_i == 'time') {
                            $content_2 .= "\t\t\t<input class=\"form-control m-bottom\" type=\"text\" pattern=\"^[0-9]{2,2}\$\" name=\"" . $key . "_hour\" value=\"{ROW." . $key . "_hour}\" placeholder=\"" . $lang_module['type_hour'] . "\">\n";
                            $content_2 .= "\t\t\t<input class=\"form-control m-bottom\" type=\"text\" pattern=\"^[0-9]{2,2}\$\" name=\"" . $key . "_min\" value=\"{ROW." . $key . "_min}\" placeholder=\"" . $lang_module['type_min'] . "\">\n";
                        }

                        if ($input_type_i == 'textarea') {
                            // Nếu là textarea
                            $content_2 .= "\t\t\t<textarea class=\"form-control\" style=\"height:100px;\" cols=\"75\" rows=\"5\" name=\"" . $key . "\">{ROW." . $key . "}</textarea>\n";
                        } elseif ($input_type_i == 'editor') {
                            // Nếu là trình soạn thảo
                            $content_2 .= "{ROW." . $key . "}";
                        } elseif ($input_type_i == 'select') {
                            $content_2 .= "\t\t\t<select class=\"form-control\" name=\"" . $key . "\">\n";
                            $content_2 .= "\t\t\t\t<option value=\"\"> --- </option>\n";
                            $content_2 .= "\t\t\t\t<!-- BEGIN: select_" . $key . " -->\n";
                            $content_2 .= "\t\t\t\t<option value=\"{OPTION.key}\" {OPTION.selected}>{OPTION.title}</option>\n";
                            $content_2 .= "\t\t\t\t<!-- END: select_" . $key . " -->\n";
                            $content_2 .= "\t\t\t</select>\n";
                        } elseif ($input_type_i == 'radio') {
                            $type_html = ($input_type_i == 'radio') ? 'radio' : 'checkbox';
                            $content_2 .= "\n\t\t\t<!-- BEGIN: " . $type_html . "_" . $key . " -->\n";
                            $content_2 .= "\t\t\t\t<label><input class=\"form-control\" type=\"" . $type_html . "\" name=\"" . $key . "\" value=\"{OPTION.key}\" {OPTION.checked}";

                            if (isset($array_requireds[$key])) {
                                $content_2 .= 'required="required" ';
                                if (!empty($oninvalid)) {
                                    $content_2 .= "oninvalid=\"setCustomValidity(nv_required)\" oninput=\"setCustomValidity('')\" ";
                                }
                            }
                            $content_2 .= ">{OPTION.title} &nbsp;</label> \n";
                            $content_2 .= "\t\t\t<!-- END: " . $type_html . "_" . $key . " -->\n";
                        } elseif ($input_type_i == 'checkbox') {
                            $content_2 .= "\n\t\t\t\t\t<!-- BEGIN: " . $input_type_i . "_" . $key . " -->\n";
                            $content_2 .= "\t\t\t\t\t\t<label><input class=\"form-control\" type=\"checkbox\" name=\"" . $key . "[]\" value=\"{OPTION.key}\" {OPTION.checked}>{OPTION.title}</label>\n";
                            $content_2 .= "\t\t\t\t\t<!-- END: " . $input_type_i . "_" . $key . " -->\n";
                            $content_2 .= "\t\t\t\t";
                        } else {
                            // Nếu là các loại input khác
                            switch ($input_type_i) {
                                case 'email':
                                    $type_html = 'email';
                                    break;
                                case 'url':
                                    $type_html = 'url';
                                    break;
                                case 'password':
                                    $type_html = 'password';
                                    break;
                                default:
                                    $type_html = 'text';
                            }

                            $oninvalid = true;
                            if ($input_type_i == 'date' or $input_type_i == 'time' or $input_type_i == 'textfile') {
                                $content_2 .= "\t\t\t<div class=\"input-group\">\n";
                            }
                            $content_2 .= "\t\t\t<input class=\"form-control\" type=\"" . $type_html . "\" name=\"" . $key . "\" value=\"{ROW." . $key . "}\" ";
                            if ($input_type_i == 'date' or $input_type_i == 'time') {
                                $content_2 .= 'id="' . $key . '" pattern="^[0-9]{2,2}\/[0-9]{2,2}\/[0-9]{1,4}$" ';
                                $array_field_js['date'][] = '#' . $key;
                            } elseif ($input_type_i == 'textfile') {
                                $content_2 .= 'id="id_' . $key . '" ';
                                $array_field_js['file'][] = $key;
                            } elseif ($input_type_i == 'textalias') {
                                $content_2 .= 'id="id_' . $key . '" ';
                            } elseif ($input_type_i == 'email') {
                                $content_2 .= "oninvalid=\"setCustomValidity(nv_email)\" oninput=\"setCustomValidity('')\" ";
                                $oninvalid = false;
                            } elseif ($input_type_i == 'url') {
                                $content_2 .= "oninvalid=\"setCustomValidity(nv_url)\" oninput=\"setCustomValidity('')\" ";
                                $oninvalid = false;
                            } elseif ($input_type_i == 'number_int') {
                                $content_2 .= "pattern=\"^[0-9]*$\"  oninvalid=\"setCustomValidity(nv_digits)\" oninput=\"setCustomValidity('')\" ";
                                $oninvalid = false;
                            } elseif ($input_type_i == 'number_float') {
                                $content_2 .= "pattern=\"^([0-9]*)(\.*)([0-9]+)$\" oninvalid=\"setCustomValidity(nv_number)\" oninput=\"setCustomValidity('')\" ";
                                $oninvalid = false;
                            }

                            if (isset($array_requireds[$key])) {
                                $content_2 .= 'required="required" ';
                                if ($oninvalid) {
                                    $content_2 .= "oninvalid=\"setCustomValidity(nv_required)\" oninput=\"setCustomValidity('')\" ";
                                }
                            }

                            $content_2 .= "/>\n";
                            if ($input_type_i == 'date' or $input_type_i == 'time') {
                                $content_2 .= "\t\t\t\t<span class=\"input-group-btn\">\n";
                                $content_2 .= "\t\t\t\t\t<button class=\"btn btn-default\" type=\"button\" id=\"" . $key . "-btn\">\n";
                                $content_2 .= "\t\t\t\t\t\t<em class=\"fa fa-calendar fa-fix\"> </em>\n";
                                $content_2 .= "\t\t\t\t\t</button> </span>\n";
                                $content_2 .= "\t\t\t\t</div>\n";
                            }
                            if ($input_type_i == 'textfile') {
                                $content_2 .= "\t\t\t<span class=\"input-group-btn\">\n";
                                $content_2 .= "\t\t\t\t<button class=\"btn btn-default selectfile\" type=\"button\" >\n";
                                $content_2 .= "\t\t\t\t<em class=\"fa fa-folder-open-o fa-fix\">&nbsp;</em>\n";
                                $content_2 .= "\t\t\t</button>\n";
                                $content_2 .= "\t\t\t</span>\n";
                                $content_2 .= "\t\t</div>\n";
                            }
                            if ($input_type_i == 'textalias' and $array_field_js['textalias'] == $key) {
                                $content_2 .= "\t\t</div>\n";
                                $content_2 .= "\t\t<div class=\"col-sm-4 col-md-2\">\n";
                                $content_2 .= "\t\t\t<i class=\"fa fa-refresh fa-lg icon-pointer\" onclick=\"nv_get_alias('id_" . $key . "');\">&nbsp;</i>\n";
                            }
                        }
                        $content_2 .= "\t\t</div>\n";
                        $content_2 .= "\t</div>\n";
                    }
                }
                $content_2 .= "	<div class=\"form-group\" style=\"text-align: center\"><input class=\"btn btn-primary\" name=\"submit\" type=\"submit\" value=\"{LANG.save}\" /></div>\n";
                $content_2 .= "</form>\n";
                $content_2 .= "</div></div>\n";

                $content_3 = '';

                if (!isset($lang_mod_admin_vi['save']) and !isset($lang_mod_admin_vi_new['save'])) {
                    $lang_mod_admin_vi_new['save'] = 'Lưu thay đổi';
                }

                if (!isset($lang_mod_admin_en['save']) and !isset($lang_mod_admin_en_new['save'])) {
                    $lang_mod_admin_en_new['save'] = 'Save';
                }

                if (!empty($array_field_js)) {
                    if (isset($array_field_js['date'])) {
                        $content_1 .= "<link type=\"text/css\" href=\"{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css\" rel=\"stylesheet\" />\n";
                        $content_1 .= "\n";

                        $content_2 .= "\n";
                        $content_2 .= "<script type=\"text/javascript\" src=\"{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js\"></script>\n";
                        $content_2 .= "<script type=\"text/javascript\" src=\"{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js\"></script>\n";
                    }

                    $content_2 .= "\n<script type=\"text/javascript\">\n";
                    $content_2 .= "//<![CDATA[\n";

                    if (isset($array_field_js['textalias'])) {
                        $content_2 .= "\tfunction nv_get_alias(id) {\n";
                        $content_2 .= "\t	var title = strip_tags($(\"[name='" . $alias_title . "']\").val());\n";
                        $content_2 .= "\t	if (title != '') {\n";
                        $content_2 .= "\t		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=" . $funname . "&nocache=' + new Date().getTime(), 'get_alias_title=' + encodeURIComponent(title), function(res) {\n";
                        $content_2 .= "\t			$(\"#\"+id).val(strip_tags(res));\n";
                        $content_2 .= "\t		});\n";
                        $content_2 .= "\t	}\n";
                        $content_2 .= "\t	return false;\n";
                        $content_2 .= "\t}\n";

                        $content_3 .= "\n<!-- BEGIN: auto_get_alias -->\n";
                        $content_3 .= "<script type=\"text/javascript\">\n";
                        $content_3 .= "//<![CDATA[\n";
                        $content_3 .= "\t\$(\"[name='" . $alias_title . "']\").change(function() {\n";
                        $content_3 .= "\t\tnv_get_alias('id_" . $array_field_js['textalias'] . "');\n";
                        $content_3 .= "\t});\n";
                        $content_3 .= "//]]>\n";
                        $content_3 .= "</script>\n";
                        $content_3 .= "<!-- END: auto_get_alias -->\n";
                    }
                    if (isset($array_field_js['date'])) {
                        $content_2 .= "\t\$(\"" . implode(',', $array_field_js['date']) . "\").datepicker({\n";
                        $content_2 .= "\t\tdateFormat : \"dd/mm/yy\",\n";
                        $content_2 .= "\t\tchangeMonth : true,\n";
                        $content_2 .= "\t\tchangeYear : true,\n";
                        $content_2 .= "\t\tshowOtherMonths : true,\n";
                        $content_2 .= "\t});\n\n";
                    }
                    if (isset($array_field_js['file'])) {
                        foreach ($array_field_js['file'] as $key) {
                            $content_2 .= "\t\$(\".selectfile\").click(function() {\n";
                            $content_2 .= "\t\tvar area = \"id_" . $key . "\";\n";
                            $content_2 .= "\t\tvar path = \"{NV_UPLOADS_DIR}/{MODULE_UPLOAD}\";\n";
                            $content_2 .= "\t\tvar currentpath = \"{NV_UPLOADS_DIR}/{MODULE_UPLOAD}\";\n";
                            $content_2 .= "\t\tvar type = \"image\";\n";
                            $content_2 .= "\t\tnv_open_browse(script_name + \"?\" + nv_name_variable + \"=upload&popup=1&area=\" + area + \"&path=\" + path + \"&type=\" + type + \"&currentpath=\" + currentpath, \"NVImg\", 850, 420, \"resizable=no,scrollbars=no,toolbar=no,location=no,status=no\");\n";
                            $content_2 .= "\t\treturn false;\n";
                            $content_2 .= "\t});\n\n";
                        }
                    }
                    if (isset($array_field_js['change_weight'])) {
                        $content_2 .= $array_field_js['change_weight'] . "\n\n";
                    }
                    if (isset($array_field_js['change_status'])) {
                        $content_2 .= $array_field_js['change_status'] . "\n\n";
                    }
                    $content_2 .= "//]]>\n";
                    $content_2 .= "</script>\n";
                }
                $content_2 .= $content_3;
            }

            $content_2 .= "<!-- END: main -->";
            if ($setfunction == 1) {
                $folder = "default";
            } else {
                $folder = "admin_default";
            }
            file_put_contents(NV_ROOTDIR . "/themes/" . $folder . "/modules/" . $modname . "/" . $funname . ".tpl", str_replace("\t", "    ", $content_1 . $content_2), LOCK_EX);
            if (substr($sys_info['os'], 0, 3) != 'WIN')
                chmod(NV_ROOTDIR . "/themes/" . $folder . "/modules/" . $modname . "/" . $funname . ".tpl", 0777);

            nv_write_lang_mod_admin($modname, 'en', $lang_mod_admin_en_new, $setfunction);
            nv_write_lang_mod_admin($modname, 'vi', $lang_mod_admin_vi_new, $setfunction);
        }
    } catch (PDOException $e) {
        trigger_error($e->getMessage());
    }
}

if (empty($nb)) {
    if (!$global_config['rewrite_enable']) {
        $xtpl->assign('FORM_ACTION', NV_BASE_SITEURL . 'index.php');
        $xtpl->parse('main.tablename.no_rewrite');
    } else {
        $xtpl->assign('FORM_ACTION', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
    }

    $modules_exit = nv_scandir(NV_ROOTDIR . '/modules', $global_config['check_module']);
    foreach ($modules_exit as $mod_i) {
        $xtpl->assign('MODNAME', ['value' => $mod_i, 'selected' => ($modname == $mod_i) ? ' selected="selected"' : '']);
        $xtpl->parse('main.tablename.modname');
    }

    if (!empty($modname)) {
        $result = $db->query('SHOW TABLE STATUS LIKE ' . $db->quote($db_config['prefix'] . '\_' . NV_LANG_DATA . '\_' . $modname . '%'));
        while ($item = $result->fetch()) {
            $xtpl->assign('MODNAME', ['value' => $item['name'], 'selected' => ($tablename == $item['name']) ? ' selected="selected"' : '']);
            $xtpl->parse('main.tablename.loop');
        }

        $result = $db->query('SHOW TABLE STATUS LIKE ' . $db->quote($db_config['prefix'] . '\_' . $modname . '%'));
        while ($item = $result->fetch()) {
            $xtpl->assign('MODNAME', ['value' => $item['name'], 'selected' => ($tablename == $item['name']) ? ' selected="selected"' : '']);
            $xtpl->parse('main.tablename.loop');
        }
    }

    $xtpl->parse('main.tablename');
} else {
    $form_action = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
    $form_action .= '&amp;modname=' . $modname . '&amp;tablename=' . $tablename . '&amp;funname=' . $funname . '&amp;setlangvi=' . $setlangvi . '&amp;setlangen=' . $setlangen . '&amp;setfunction=' . $setfunction;
    $xtpl->assign('FORM_ACTION', $form_action);

    if (!empty($error)) {
        $xtpl->assign('ERROR', implode('<br />', $error));
        $xtpl->parse('main.form.error');
    }

    $xtpl->parse('main.form');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
