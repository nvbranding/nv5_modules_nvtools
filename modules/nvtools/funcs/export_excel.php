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

define('NV_ADMIN', true);

$page_title = $lang_module['Export_excel'];
$key_words = $module_info['keywords'];

if (!class_exists('PhpOffice\PhpSpreadsheet\IOFactory')) {
    $message_title = "Thiếu thư viện PhpSpreadsheet";
    $message_content = "Để sử dụng chức năng này bạn cần cài đặt thư viện PhpSpreadsheet: <pre><code>composer require phpoffice/phpspreadsheet</code></pre>";
    $contents = nv_theme_alert($message_title, $message_content);

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

$modname = $nv_Request->get_title('modname', 'get,post');
$array_table = array();

if ($nv_Request->isset_request('submit', 'post,get')) {
    $export_structure = $nv_Request->get_array('export_structure', 'post,get');
    foreach ($export_structure as $key => $value) {
        $result = $db->query("SHOW TABLE STATUS LIKE '" . $value . "'");
        while ($item = $result->fetch()) {
            $array_table[$item['name']] = array();
            $result = $db->query("select * from information_schema.columns where `TABLE_SCHEMA` = '" . $db_config['dbname'] . "' and `TABLE_NAME` = '" . $item['name'] . "'");
            while ($column = $result->fetch()) {
                $array_table[$item['name']][$column['column_name']] = $column;
            }
        }
    }
}

if (!empty($array_table)) {
    $Excel_Cell_Begin = 5;
    $objPHPExcel = \PhpOffice\PhpSpreadsheet\IOFactory::load(NV_ROOTDIR . '/modules/nvtools/export.xls');
    $objWorksheet = $objPHPExcel->getActiveSheet();

    // Setting a spreadsheet’s metadata
    $objPHPExcel->getProperties()->setCreator("NukeViet CMS");
    $objPHPExcel->getProperties()->setLastModifiedBy("NukeViet CMS");
    $objPHPExcel->getProperties()->setTitle($page_title . time());
    $objPHPExcel->getProperties()->setSubject($page_title . time());
    $objPHPExcel->getProperties()->setDescription($page_title);
    $objPHPExcel->getProperties()->setKeywords($page_title);
    $objPHPExcel->getProperties()->setCategory($module_name);

    // Set page orientation and size
    $objWorksheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
    $objWorksheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
    $objWorksheet->getPageSetup()->setHorizontalCentered(true);

    $style_bold = array(
        'font' => array(
            'color' => array(
                'argb' => \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED
            ),
            'bold' => true
        )
    );
    $style_fill = array(
        'fill' => array(
            'type' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startcolor' => array(
                'argb' => 'EEEEEE'
            )
        )
    );

    $i = 0;
    foreach ($array_table as $table => $values) {
        $r = $i + $Excel_Cell_Begin;
        $objWorksheet->mergeCells('A' . $r . ':C' . $r);
        $objWorksheet->setCellValue('A' . $r, "Bảng: " . $table);
        $objWorksheet->getStyle('A' . $r . ':B' . $r)->applyFromArray($style_bold);
        ++ $r;
        $k = $r;
        $objWorksheet->getStyle('A' . $r . ':D' . $r)->applyFromArray($style_fill);
        $objWorksheet->setCellValue('A' . $r, "STT");
        $objWorksheet->setCellValue('B' . $r, "Tên cột");
        $objWorksheet->setCellValue('C' . $r, "Kiểu dữ liệu");
        $objWorksheet->setCellValue('D' . $r, "Tham chiếu, mô tả");

        $j = 0;
        foreach ($values as $column => $value) {
            ++ $r;
            ++ $j;
            $objWorksheet->getRowDimension($r)->setRowHeight(- 1);
            $objWorksheet->setCellValue('A' . $r, $j);
            $objWorksheet->setCellValue('B' . $r, $value['column_name']);
            $objWorksheet->setCellValue('C' . $r, $value['column_type']);
            if ($value['column_key'] == 'PRI' and $value['extra'] == 'auto_increment') {
                $objWorksheet->setCellValue('D' . $r, $value['column_comment'] . ', Khóa chính tự tăng.');
            } else {
                $objWorksheet->setCellValue('D' . $r, $value['column_comment']);
            }
        }
        $Excel_Cell_Begin = $r + 2;
        $objWorksheet->getStyle('A' . $k . ':D' . ($r))
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    }

    $file_src = NV_ROOTDIR . "/" . NV_TEMP_DIR . "/" . $modname . '-' . session_id() . '.xls';
    if (file_exists($file_src)) {
        unlink($file_src);
    }
    $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xls');
    $objWriter->save($file_src);

    // Download file
    $download = new NukeViet\Files\Download($file_src, NV_ROOTDIR . "/" . NV_TEMP_DIR, $modname . ".xls");
    $download->download_file();
    exit();
}

$xtpl = new XTemplate($op . ".tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);

$xtpl->assign('MODNAME', $modname);

if (preg_match('/^[a-zA-Z0-9\_\-]+$/', $modname)) {

    $modname = preg_replace('#-#', '_', $modname);
    $export_structure = $nv_Request->get_array('export_structure', 'post,get');
    $array_structure = array();
    if (!empty($export_structure)) {
        foreach ($export_structure as $value) {
            $array_structure[$value] = $value;
        }
    }
    $i = 1;
    $result = $db->query('SHOW TABLE STATUS LIKE ' . $db->quote('%' . $db_config['prefix'] . '_' . NV_LANG_DATA . '_' . $modname . '%'));
    while ($item = $result->fetch()) {
        $item['stt'] = $i;
        ++ $i;
        $item['checked'] = (isset($array_structure[$item['name']])) ? 'checked=\"checked\"' : '';
        $xtpl->assign('ITEM', $item);
        $xtpl->parse('main.form.item');
    }
    $result = $db->query('SHOW TABLE STATUS LIKE ' . $db->quote('%' . $db_config['prefix'] . '_' . $modname . '%'));
    while ($item = $result->fetch()) {
        $item['stt'] = $i;
        ++ $i;
        $item['checked'] = (isset($array_structure[$item['name']])) ? 'checked=\"checked\"' : '';
        $xtpl->assign('ITEM', $item);
        $xtpl->parse('main.form.item');
    }
    $xtpl->parse('main.form');
} else {
    $modules_exit = nv_scandir(NV_ROOTDIR . '/modules', $global_config['check_module']);
    foreach ($modules_exit as $mod_i) {
        $xtpl->assign('MODNAME', array(
            'value' => $mod_i,
            'selected' => ($modname == $mod_i) ? ' selected="selected"' : ''
        ));
        $xtpl->parse('main.tablename.modname');
    }
    $xtpl->parse('main.tablename');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
