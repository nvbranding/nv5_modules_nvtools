<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Sat, 19 Mar 2011 16:50:45 GMT
 */

if (!defined('NV_IS_MOD_NVTOOLS'))
    die('Stop!!!');

$page_title = $lang_module['SiteTitleModule'];
$key_words = $module_info['keywords'];

function dir_tree_pack($dir)
{
    $path = array();
    $res = array(
        'dir' => array(),
        'file' => array()
    );
    $stack[] = $dir;
    while ($stack) {
        $thisdir = array_pop($stack);
        if ($dircont = scandir($thisdir)) {
            $i = 0;
            while (isset($dircont[$i])) {
                if ($dircont[$i] !== '.' && $dircont[$i] !== '..') {
                    $current_file = $thisdir . "/" . $dircont[$i];
                    if (is_file($current_file)) {
                        if (preg_match('/\.php$/', $dircont[$i])) {
                            $path[] = $thisdir . "/" . $dircont[$i];
                            $res['filephp'][] = str_replace($dir, "", $thisdir . "/" . $dircont[$i]);
                        } elseif (preg_match('/\.js$/', $dircont[$i])) {
                            $path[] = $thisdir . "/" . $dircont[$i];
                            $res['filejs'][] = str_replace($dir, "", $thisdir . "/" . $dircont[$i]);
                        } elseif (preg_match('/\.css/', $dircont[$i])) {
                            $path[] = $thisdir . "/" . $dircont[$i];
                            $res['filecss'][] = str_replace($dir, "", $thisdir . "/" . $dircont[$i]);
                        }
                    } elseif (is_dir($current_file) and $current_file != NV_SOURCE . '/editors/ckeditor') {
                        $path[] = $thisdir . "/" . $dircont[$i];
                        $stack[] = $current_file;
                        $res['dir'][] = str_replace($dir, "", $thisdir . "/" . $dircont[$i]);
                    }
                }

                $i ++;
            }
        }
    }
    return $res;
}

$array_js = array();

if ($nv_Request->isset_request('submit', 'post')) {
    $files = dir_tree_pack(NV_SOURCE);

    require (NV_ROOTDIR . "/modules/" . $module_file . "/Minify_CSS_Compressor.php");
    $Csszip = new CSSzip();
    foreach ($files['filecss'] as $file) {
        $Csszip->processFile(NV_SOURCE . $file);
    }

    $array_js = $files['filejs'];
}

$xtpl = new XTemplate($op . ".tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);

$number = 0;
if (!empty($array_js)) {
    foreach ($array_js as $file) {
        if (!preg_match('/\.pack\.js$/', $file) and !preg_match('/\.min\.js$/', $file) and !preg_match('/\.minified\.js$/', $file)) {
            $xtpl->assign('JSF', array(
                'number' => $number,
                'name' => nv_base64_encode($file)
            ));
            $xtpl->parse('main.jsfile');

            $xtpl->assign('DATAJS', $file);
            $xtpl->parse('main.js.loop');

            $number ++;
        }
    }
    $xtpl->parse('main.js');
}
$xtpl->parse('main');
$contents = $xtpl->text('main');

include (NV_ROOTDIR . "/includes/header.php");
echo nv_site_theme($contents);
include (NV_ROOTDIR . "/includes/footer.php");
