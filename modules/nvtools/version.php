<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Sat, 19 Mar 2011 16:50:45 GMT
 */

if (!defined('NV_MAINFILE'))
    die('Stop!!!');

$module_version = array(
    'name' => 'nvtools',
    'modfuncs' => 'main,theme,data,addfun,action,export_excel,block,themecopy,thememodulecp,compiler,copylang',
    'submenu' => 'main,theme,data,addfun,action,export_excel,block,themecopy,thememodulecp,compiler,copylang',
    'is_sysmod' => 0,
    'virtual' => 0,
    'version' => '4.0.24',
    'date' => 'Wed, 1 Nov 2015 4:50:45 GMT',
    'author' => 'VINADES (contact@vinades.vn)',
    'uploads_dir' => array(
        $module_upload,
        $module_upload . '/compiler'
    ),
    'note' => 'Công cụ xây dựng site',
    'layoutdefault' => 'body:main,theme,data,addfun,themecopy,thememodulecp'
);
