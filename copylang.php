<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 09 Jan 2014 10:18:48 GMT
 */

/*Hướng dẫn sử dụng:
 * 1) Cài đặt ngôn ngữ tiếng việt trước tiên, (Ngôn ngữ mặc định cua hệ thống)
 * 2) Bật chức năng đa ngôn ngữ.
 * 3) Cài đặt site tiếng anh.
 * 4) Chạy tool để copy dữ liệu http://domain.com/copylang.php?lang=en
 * 2) Khi copy  hệ thống sẽ copy từ tiếng việt ra các ngôn ngữ
 * 3) Khi cần cài đặt ngôn ngữ nào, chỉ cần cài ngôn ngữ đó lên trước, sau đó chạy tool, hệ thông sẽ copy tất cả CSDL sang
 * 4) Chưa kiểm tra với Các module không phải đa ngôn ngữ CSDL như Shop, Ví tiền
 */

define('NV_ADMIN', true);

// Xac dinh thu muc goc cua site
define('NV_ROOTDIR', pathinfo(str_replace(DIRECTORY_SEPARATOR, '/', __file__), PATHINFO_DIRNAME));

require NV_ROOTDIR . '/includes/mainfile.php';

if (!((NV_CLIENT_IP == '123.25.21.13' or NV_CLIENT_IP == '::1') and defined('NV_IS_GODADMIN'))) {
    $redirect = '<meta http-equiv="Refresh" content="3;URL=' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA, true) . '" />';
    nv_info_die($lang_module['error_title'], $lang_module['error_title'], $lang_module['error_content'] . $redirect);
}

$lang = $nv_Request->get_string('lang', 'post,get', 'en');
$lang_source = 'vi';
if ($lang != $lang_source) {
    try {
        $result = $db->query("SHOW TABLE STATUS LIKE '" . $db_config['prefix'] . "\_" . $lang_source . "\_%'");
        while ($item = $result->fetch()) {
            if (strpos($item['name'], $db_config['prefix'] . '_' . $lang_source . '_') !== false) {
                echo $item['name'] . '<br>' . strpos($db_config['prefix'] . '_', $item['name']) . '<br>';

                $table_lang = str_replace($db_config['prefix'] . '_' . $lang_source . '_', $db_config['prefix'] . '_' . $lang . '_', $item['name']);

                $db->query('DROP TABLE IF EXISTS ' . $table_lang);// Xóa của bản tiếng anh.
                $db->query('CREATE TABLE ' . $table_lang . ' LIKE ' . $item['name']);

                $db->query('INSERT INTO ' . $table_lang . ' SELECT * FROM ' . $item['name']);
            }
        }

        //config
        echo $db_config['prefix'] . '_config<br>';
        $db->query("DELETE FROM " . $db_config['prefix'] . "_config WHERE lang='" . $lang . "'");

        $_sql = "SELECT * FROM " . $db_config['prefix'] . "_config WHERE lang='" . $lang_source . "'";
        $_query = $db->query($_sql);
        while ($row = $_query->fetch()) {
            $db->query("INSERT INTO " . $db_config['prefix'] . "_config
                    (lang, module, config_name, config_value) VALUES
                    ('" . $lang . "'," . $db->quote($row['module']) . "," . $db->quote($row['config_name']) . "," . $db->quote($row['config_value']) . ")");
        }

        //Xóa các dữ liệu thừa Module contact
        $mquery = $db->query("SELECT title, module_data FROM " . $db_config['prefix'] . "_" . $lang . "_modules WHERE module_file = 'contact'");
        while (list ($mod, $mod_data) = $mquery->fetch(3)) {
            $db->query("TRUNCATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_send");
            $db->query("TRUNCATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_reply");
        }

        //Xóa các dữ liệu thừa Module comment
        $db->query("TRUNCATE TABLE " . $db_config['prefix'] . "_" . $lang . "_comment");

        //Sửa lại link menu
        $mquery = $db->query("SELECT `id`, `link`  FROM " . $db_config['prefix'] . "_" . $lang . "_menu_rows");
        while (list ($id, $link) = $mquery->fetch(3)) {
            $link = str_replace('index.php?language=' . $lang_source, 'index.php?language=' . $lang, $link);
            $link = str_replace('/' . $lang_source . '/', '/' . $lang . '/', $link);
            $db->query("UPDATE " . $db_config['prefix'] . "_" . $lang . "_menu_rows SET `link` = " . $db->quote($link) . " WHERE `id` = " . $id);
        }

        //Sửa lại tên module
        $mquery = $db->query("SELECT `title`, `custom_title`   FROM " . $db_config['prefix'] . "_" . $lang . "_modules");
        while (list ($title, $custom_title) = $mquery->fetch(3)) {
            if (nv_EncString($custom_title) != $custom_title) {
                $custom_title = str_replace('-', ' ', $title);
                $custom_title = str_replace('_', ' ', $custom_title);
                $custom_title = ucwords($custom_title);
                $db->query("UPDATE " . $db_config['prefix'] . "_" . $lang . "_modules SET `custom_title` = " . $db->quote($custom_title) . " WHERE `title` = " . $db->quote($title));
            }
        }
        $db->query("UPDATE `" . $db_config['prefix'] . "_counter` SET `" . $lang . "_count` = '0'");

        //Xóa Cache
        include_once NV_ROOTDIR . '/includes/core/admin_functions.php';
        $nv_Cache->delAll();

        echo 'Thực hiện xong<br>';
    } catch (PDOException $e) {
        echo '<pre>';
        print_r($e);
        echo '</pre>';
    }
} else {
    echo 'Ngôn ngữ copy cần khác ngôn ngữ: ' . $lang_source;
}
die();