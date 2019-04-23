<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 09 Jan 2014 10:18:48 GMT
 */

if (!defined('NV_IS_MOD_NVTOOLS'))
    die('Stop!!!');

$page_title = $lang_module['copylang'];

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);

$array_langs = [];
$re = $db->query('SELECT lang FROM ' . $db_config['prefix'] . '_setup_language WHERE setup=1 AND lang!=\'vi\' ');
while (list ($lang_i) = $re->fetch(3)) {
    $array_langs[] = $lang_i;
}

if ($nv_Request->isset_request('submit_copy', 'post,get') and $nv_Request->isset_request('lang', 'post,get')) {
    $lang = $nv_Request->get_string('lang', 'post,get', '');
    if (in_array($lang, $array_langs)) {
        $array_tables = [];
        $result = $db->query("SHOW TABLE STATUS LIKE '" . $db_config['prefix'] . "\_%'");

        while ($item = $result->fetch()) {
            if (strpos($item['name'], $db_config['prefix'] . '_vi_') !== false) {
                $table_lang = str_replace($db_config['prefix'] . '_vi_', $db_config['prefix'] . '_' . $lang . '_', $item['name']);
                $array_tables[] = $item['name'] . " => " . $table_lang;

                $db->query('DROP TABLE IF EXISTS ' . $table_lang);
                $db->query('CREATE TABLE ' . $table_lang . ' LIKE ' . $item['name']);
                $db->query('INSERT INTO ' . $table_lang . ' SELECT * FROM ' . $item['name']);
            }
        }

        // Xóa các cấu hình của ngôn ngữ khác
        $db->query("DELETE FROM " . $db_config['prefix'] . "_config WHERE lang='" . $lang . "'");

        // Sao chép cấu hình ngôn ngữ tiếng Việt sang
        $_sql = "SELECT * FROM " . $db_config['prefix'] . "_config WHERE lang='vi'";
        $_query = $db->query($_sql);
        while ($row = $_query->fetch()) {
            $db->query("INSERT INTO " . $db_config['prefix'] . "_config(
                lang, module, config_name, config_value
            ) VALUES (
                '" . $lang . "', " . $db->quote($row['module']) . ",
                " . $db->quote($row['config_name']) . ", " . $db->quote($row['config_value']) . "
            )");
        }
        $array_tables[] = $db_config['prefix'] . "_config";

        $nv_Cache->delAll(true);

        $xtpl->assign('TABLES', implode('<br>', array_map("htmlspecialchars", $array_tables)));
        $xtpl->parse('main.tables');
    }
}

foreach ($array_langs as $lang_i) {
    $xtpl->assign('LANG_I', $lang_i);
    $xtpl->parse('main.option');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
