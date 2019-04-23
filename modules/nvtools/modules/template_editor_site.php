<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Sat, 19 Mar 2011 16:50:45 GMT
 */

// Viết đoạn code trong //>> và //<<, khi lấy dữ liệu hệ thống chỉ lấy trong đó

//>>
/**
 * nv_module_aleditor()
 *
 * @param mixed $textareaname
 * @param string $width
 * @param string $height
 * @param string $val
 * @return
 */
function nv_module_aleditor($textareaname, $width = '100%', $height = '450px', $val = '')
{
    global $global_config, $module_data;

    $return = '';
    if (!defined('CKEDITOR')) {
        define('CKEDITOR', true);
        $return .= '<script type="text/javascript" src="' . NV_BASE_SITEURL . NV_EDITORSDIR . '/ckeditor/ckeditor.js?t=' . $global_config['timestamp'] . '"></script>';
        $return .= '<script type="text/javascript">CKEDITOR.timestamp=CKEDITOR.timestamp+' . $global_config['timestamp'] . ';</script>';
    }

    $return .= '<textarea class="form-control" style="width: ' . $width . '; height:' . $height . ';" id="' . $module_data . '_' . $textareaname . '" name="' . $textareaname . '">' . $val . '</textarea>';
    $return .= "<script type=\"text/javascript\">CKEDITOR.replace('" . $module_data . "_" . $textareaname . "', {
    removePlugins: 'uploadfile,uploadimage,autosave',
    contentsCss: '" . NV_BASE_SITEURL . NV_EDITORSDIR . "/ckeditor/nv.css?t=" . $global_config['timestamp'] . "'
    });</script>";

    return $return;
}
//<<
