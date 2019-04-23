<?php

//REPLACE

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

// Dưới đây là code mẫu. Xem hướng dẫn ở https://wiki.nukeviet.vn/programming4:module:comment
if (defined('NV_ADMIN')) {
    $id = $row['id'];
    $area = $row['area'];
}
 
/**
 * Sử dụng các biến sau để lấy số comment của đối tượng
 * $id, $area là hai biến unique để xác định ra bài viết (đối tượng)
 * $module tên module là module thực hoặc module ảo
 * $mod_info tương đương với biến $module_info khi lập trình module
 */
 
//>>
// Nếu là trong admin thì xác định biến $id, $area theo $row
// Xác định số comment của bài viết
$numf = $db->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_comment WHERE module= ' . $db->quote($module) . ' AND id=' . $id . ' AND area=' . $area . ' AND status=1')->fetchColumn();
 
// Thực hiện cập nhật lại chô bài viết (đối tượng)
// Ví dụ
$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $mod_info['module_data'] . '_rows SET hitscm=' . $numf . ' WHERE id=' . $id;
$db->query($sql);
//<<
