/*
$db->sqlreset()->select('COUNT(*)')->from(NV_PREFIXLANG . '_' . $m_values['module_data'] . '_rows r');
$db->join('INNER JOIN ' . NV_PREFIXLANG . '_' . $m_values['module_data'] . '_bodytext c ON (r.id=c.id)');
$db->where('(' . nv_like_logic('r.title', $dbkeyword, $logic) . ' OR ' . nv_like_logic('r.hometext', $dbkeyword, $logic) . ') OR ' . nv_like_logic('c.bodytext', $dbkeyword, $logic) . '	AND r.status= 1');

$all_page = $db->query($db->sql())->fetchColumn();
if ($all_page) {
    $link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $m_values['module_name'] . '&amp;' . NV_OP_VARIABLE . '=';

    $db->select('r.id, r.title, r.alias, r.catid, r.hometext, c.bodytext')->limit($limit)->offset($pages);
    $result = $db->query($db->sql());
    while (list($id, $tilterow, $alias, $content) = $result->fetch(3)) {
        $url = $link . $alias . '-' . $id;

        $result_array[] = [
            'link' => $url,
            'title' => BoldKeywordInStr($tilterow, $key, $logic),
            'content' => BoldKeywordInStr($content, $key, $logic)
        ];
    }
}
*/
