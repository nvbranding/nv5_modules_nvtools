<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Sat, 19 Mar 2011 16:50:45 GMT
 */

if (!defined('NV_SYSTEM'))
    die('Stop!!!');

define('NV_IS_MOD_NVTOOLS', true);

// Thu muc compiler
define('NV_SOURCE', NV_ROOTDIR . '/uploads/' . $module_upload . '/compiler');

if (!((NV_CLIENT_IP == '127.0.0.1' or NV_CLIENT_IP == '::1') and defined('NV_IS_GODADMIN'))) {
    $redirect = '<meta http-equiv="Refresh" content="3;URL=' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA, true) . '" />';
    nv_info_die($lang_module['error_title'], $lang_module['error_title'], $lang_module['error_content'] . $redirect);
}

/**
 * nv_mkdir_nvtools()
 *
 * @param mixed $path
 * @param mixed $dir_name
 * @param integer $index_file
 * @param integer $htaccess
 * @return
 */
function nv_mkdir_nvtools($path, $dir_name, $index_file = 0, $htaccess = 0)
{
    global $lang_global, $global_config, $sys_info;
    $dir_name = nv_string_to_filename(trim(basename($dir_name)));
    if (!preg_match("/^[a-zA-Z0-9-_.]+$/", $dir_name))
        return array(0, sprintf($lang_global['error_create_directories_name_invalid'], $dir_name));
    $path = @realpath($path);
    if (!preg_match('/\/$/', $path))
        $path = $path . "/";

    if (file_exists($path . $dir_name))
        return array(
            2,
            sprintf($lang_global['error_create_directories_name_used'], $dir_name),
            $path . $dir_name);

    if (!is_dir($path))
        return array(0, sprintf($lang_global['error_directory_does_not_exist'], $path));

    $ftp_check_login = 0;
    if ($sys_info['ftp_support'] and intval($global_config['ftp_check_login']) == 1) {
        $ftp_server = nv_unhtmlspecialchars($global_config['ftp_server']);
        $ftp_port = intval($global_config['ftp_port']);
        $ftp_user_name = nv_unhtmlspecialchars($global_config['ftp_user_name']);
        $ftp_user_pass = nv_unhtmlspecialchars($global_config['ftp_user_pass']);
        $ftp_path = nv_unhtmlspecialchars($global_config['ftp_path']);
        // set up basic connection
        $conn_id = ftp_connect($ftp_server, $ftp_port);
        // login with username and password
        $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
        if ((!$conn_id) || (!$login_result)) {
            $ftp_check_login = 3;
        } elseif (ftp_chdir($conn_id, $ftp_path)) {
            $ftp_check_login = 1;
        } else {
            $ftp_check_login = 2;
        }
    }
    if ($ftp_check_login == 1) {
        $dir = str_replace(NV_ROOTDIR . "/", "", str_replace('\\', '/', $path . $dir_name));
        $res = ftp_mkdir($conn_id, $dir);
        ftp_chmod($conn_id, 0777, $dir);
        ftp_close($conn_id);
    }
    if (!is_dir($path . $dir_name)) {
        if (!is_writable($path)) {
            @chmod($path, 0777);
        }
        if (!is_writable($path))
            return array(0, sprintf($lang_global['error_directory_can_not_write'], $path));

        $oldumask = umask(0);
        $res = @mkdir($path . $dir_name);
        umask($oldumask);
    }
    if (!$res)
        return array(0, sprintf($lang_global['error_create_directories_failed'], $dir_name));
    if ($index_file) {
        file_put_contents($path . $dir_name . '/index.html', '');
    }
    if ($htaccess) {
        file_put_contents($path . $dir_name . '/.htaccess', 'deny from all');
    }
    return array(
        1,
        sprintf($lang_global['directory_was_created'], $dir_name),
        $path . $dir_name);
}

/**
 * nv_list_all_files()
 *
 * @param mixed $dir
 * @param string $base_dir
 * @return
 */
function nv_list_all_files($dir, $base_dir = '')
{
    $file_list = array();

    if (is_dir($dir)) {
        $array_filedir = scandir($dir);

        foreach ($array_filedir as $v) {
            if ($v == '.' or $v == '..')
                continue;

            if (is_dir($dir . '/' . $v)) {
                foreach (nv_list_all_files($dir . '/' . $v, $base_dir . '/' . $v) as $file) {
                    $file_list[] = $file;
                }
            } else {
                if ($v == 'index.html' or $v == 'index.htm')
                    continue; // Khong copy index.htm[l]
                $file_list[] = preg_replace('/^\//', '', $base_dir . '/' . $v);
            }
        }
    }

    return $file_list;
}
