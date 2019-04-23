$lang_siteinfo = nv_get_lang_module($mod);

/*
// Tổng số bài viết
$number = $db->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $mod_data . '_rows where status= 1 AND publtime < ' . NV_CURRENTTIME . ' AND (exptime=0 OR exptime>' . NV_CURRENTTIME . ')')->fetchColumn();
if ($number > 0) {
    $siteinfo[] = [
        'key' => $lang_siteinfo['siteinfo_publtime'],
        'value' => $number
    ];
}
*/
