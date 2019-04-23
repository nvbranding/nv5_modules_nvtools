<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Sat, 19 Mar 2011 16:50:45 GMT
 */

if ( ! defined( 'NV_IS_MOD_NVTOOLS' ) ) die( 'Stop!!!' );

$array_yes_no = array( 1 => $lang_global['yes'], 0 => $lang_global['no'] );
$array_css_reset = array( 'eric' => $lang_module['css_reset_eric'], 'yahoo' => $lang_module['css_reset_yahoo'], 'none' => $lang_module['css_reset_none'] );
$array_css_page = array( 'pagecenter' => $lang_module['pageTypeCenter'], 'pageleft' => $lang_module['pageTypeLeft'], 'pagefull' => $lang_module['pageTypeFull'] );
$array_doctype_page = array( 'htmlTrans' => $lang_module['htmlTrans'], 'htmlStrict' => $lang_module['htmlStrict'], 'xhtmlTrans' => $lang_module['xhtmlTrans'], 'xhtmlStrict' => $lang_module['xhtmlStrict'], 'html5' => $lang_module['html5'] );
$array_additional = array( '0' => $lang_global['no'], '2' => $lang_module['equalheightcolumns'], '1' => $lang_module['footerStick'] );

$array_layout = array();
$array_layout[] = array( 'value' => 'body', 'text' => 'Body' );
$array_layout[] = array( 'value' => 'body-right', 'text' => 'Body Right' );
$array_layout[] = array( 'value' => 'body-left-right', 'text' => ' Body Left Right' );
$array_layout[] = array( 'value' => 'left-body', 'text' => 'Left Body' );
$array_layout[] = array( 'value' => 'left-body-right', 'text' => 'Left Body Right' );
$array_layout[] = array( 'value' => 'left-right-body', 'text' => 'Left Right Body' );

$theme_info = array();
$data_positions = array();
$data_layout = array();

$theme_css = array();
$theme_css['min_width'] = 800;
$theme_css['max_width'] = 1600;

$error = array();
$savedata = $nv_Request->get_int( 'savedata', 'post', 0 );
if ( $savedata )
{
    $theme_info['theme'] = $nv_Request->get_string( 'theme', 'post', '', 0 );
    $theme_info['theme'] = strtolower( change_alias( $theme_info['theme'] ) );

    $theme_info['info_name'] = $nv_Request->get_string( 'info_name', 'post', '' );
    $theme_info['info_author'] = $nv_Request->get_string( 'info_author', 'post', '' );
    $theme_info['info_website'] = $nv_Request->get_string( 'info_website', 'post', '' );
    $theme_info['info_description'] = $nv_Request->get_string( 'info_description', 'post', '' );
    $theme_info['layoutdefault'] = $nv_Request->get_string( 'layoutdefault', 'post', '' );

    $theme_css['doctype'] = $nv_Request->get_string( 'doctype', 'post', 'none', 0 );
    $theme_css['css_reset'] = $nv_Request->get_string( 'css_reset', 'post', 'none', 0 );
    $theme_css['pagetype'] = $nv_Request->get_string( 'pagetype', 'post', '', 0 );
    $theme_css['pageWidthValue'] = $nv_Request->get_int( 'pageWidthValue', 'post', 0 );
    $theme_css['headerHeight'] = $nv_Request->get_int( 'headerHeight', 'post', 0 );
    $theme_css['headerBgColor'] = $nv_Request->get_string( 'headerBgColor', 'post', '', 0 );
    $theme_css['footerHeight'] = $nv_Request->get_int( 'footerHeight', 'post', 0 );
    $theme_css['footerBgColor'] = $nv_Request->get_string( 'footerBgColor', 'post', '', 0 );
    $theme_css['additional'] = $nv_Request->get_int( 'additional', 'post', 0 );

    $theme_css['horizontalMenuHeight'] = $nv_Request->get_int( 'horizontalMenuHeight', 'post', 0 );
    $theme_css['horizontalMenuBgColor'] = $nv_Request->get_string( 'horizontalMenuBgColor', 'post', '', 0 );

    $theme_css['leftSidebarWidth'] = $nv_Request->get_int( 'leftSidebarWidth', 'post', 0 );
    $theme_css['leftSidebarBgColor'] = $nv_Request->get_string( 'leftSidebarBgColor', 'post', '', 0 );

    $theme_css['rightSidebarWidth'] = $nv_Request->get_int( 'rightSidebarWidth', 'post', 0 );
    $theme_css['rightSidebarBgColor'] = $nv_Request->get_string( 'rightSidebarBgColor', 'post', '', 0 );

    $theme_css['leftrightSidebarWidth'] = $theme_css['leftSidebarWidth'] + $theme_css['rightSidebarWidth'];

    $theme_css['bodyBgColor'] = $nv_Request->get_string( 'bodyBgColor', 'post', '', 0 );
    $theme_css['textColor'] = $nv_Request->get_string( 'textColor', 'post', '', 0 );

    $theme_css['aColor'] = $nv_Request->get_string( 'aColor', 'post', '', 0 );
    $theme_css['aHoverColor'] = $nv_Request->get_string( 'aHoverColor', 'post', '', 0 );

    $position_tag = $nv_Request->get_typed_array( 'position_tag', 'post', 'string' );
    $position_name = $nv_Request->get_typed_array( 'position_name', 'post', 'string' );
    $position_name_vi = $nv_Request->get_typed_array( 'position_name_vi', 'post', 'string' );

    $diff1 = array_diff( array_keys( $position_tag ), array_keys( $position_name ) );
    $diff2 = array_diff( array_keys( $position_name ), array_keys( $position_name_vi ) );
    if ( empty( $diff1 ) and empty( $diff2 ) )
    {
        foreach ( $position_tag as $key => $tag )
        {
            if ( ! empty( $tag ) )
            {
                $data_positions[] = array( 'name' => $position_name[$key], 'name_vi' => $position_name_vi[$key], 'tag' => $tag );
            }
        }
    }
    $layout = $nv_Request->get_typed_array( 'layout', 'post', 'string' );
    $data_layout[] = 'body';
    foreach ( $layout as $key => $value )
    {
        if ( $value )
        {
            $data_layout[] = $key;
        }
    }

    $data_layout = array_unique( $data_layout );
    if ( ! empty( $theme_info['theme'] ) and ! empty( $theme_info['info_name'] ) and ! empty( $theme_info['info_author'] ) and ! empty( $data_positions ) and ! empty( $data_layout ) )
    {
        $tempdir = 'theme_' . $theme_info['theme'] . '_' . md5( nv_genpass( 10 ) . session_id() );

        if ( is_dir( NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $tempdir ) )
        {
            nv_deletefile( NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $tempdir, true );
        }
        nv_mkdir_nvtools( NV_ROOTDIR . '/' . NV_TEMP_DIR, $tempdir );

        $mkdir = nv_mkdir_nvtools( NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $tempdir, $theme_info['theme'], 1 );
        if ( $mkdir[0] )
        {
            $theme_dir = NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $tempdir . '/' . $theme_info['theme'];
            $theme_default_dir = NV_ROOTDIR . '/modules/' . $module_file . '/theme';

            nv_mkdir_nvtools( $theme_dir, 'blocks', 1, 1 );
            nv_mkdir_nvtools( $theme_dir, 'css', 1 );
            nv_mkdir_nvtools( $theme_dir, 'images', 1 );
            nv_mkdir_nvtools( $theme_dir . '/images', 'admin', 1 );
            nv_mkdir_nvtools( $theme_dir . '/images', 'icons', 1 );
            nv_mkdir_nvtools( $theme_dir . '/images', 'arrows', 1 );
            nv_mkdir_nvtools( $theme_dir, 'js', 1 );

            nv_mkdir_nvtools( $theme_dir, 'layout', 1, 1 );
            nv_mkdir_nvtools( $theme_dir, 'modules', 1, 1 );
            nv_mkdir_nvtools( $theme_dir, 'system', 1, 1 );

            nv_copyfile( $theme_default_dir . '/default.jpg', $theme_dir . '/' . $theme_info['theme'] . '.jpg' );
            nv_copyfile( $theme_default_dir . '/favicon.ico', $theme_dir . '/favicon.ico' );

            nv_copyfile( $theme_default_dir . '/system/error_info.tpl', $theme_dir . '/system/error_info.tpl' );
            nv_copyfile( $theme_default_dir . '/system/flood_blocker.tpl', $theme_dir . '/system/flood_blocker.tpl' );
            nv_copyfile( $theme_default_dir . '/system/info_die.tpl', $theme_dir . '/system/info_die.tpl' );

            nv_copyfile( $theme_default_dir . '/layout/block.default.tpl', $theme_dir . '/layout/block.default.tpl' );
            nv_copyfile( $theme_default_dir . '/layout/block.no_title.tpl', $theme_dir . '/layout/block.no_title.tpl' );

            nv_copyfile( $theme_default_dir . '/blocks/global.banners.tpl', $theme_dir . '/blocks/global.banners.tpl' );
            nv_copyfile( $theme_default_dir . '/blocks/global.rss.tpl', $theme_dir . '/blocks/global.rss.tpl' );

            nv_copyfile( $theme_default_dir . '/css/ie6.css', $theme_dir . '/css/ie6.css' );
            nv_copyfile( $theme_default_dir . '/css/admin.css', $theme_dir . '/css/admin.css' );
            nv_copyfile( $theme_default_dir . '/css/tab_info.css', $theme_dir . '/css/tab_info.css' );

            $images_array = scandir( $theme_default_dir . '/images' );
            $images_array = array_diff( $images_array, array( '.', '..' ) );
            foreach ( $images_array as $file )
            {
                nv_copyfile( $theme_default_dir . '/images/admin/' . $file, $theme_dir . '/images/admin/' . $file );
            }

            $images_array = scandir( $theme_default_dir . '/images/admin' );
            $images_array = array_diff( $images_array, array( '.', '..' ) );
            foreach ( $images_array as $file )
            {
                nv_copyfile( $theme_default_dir . '/images/admin/' . $file, $theme_dir . '/images/admin/' . $file );
            }

            $images_array = scandir( $theme_default_dir . '/images/arrows' );
            $images_array = array_diff( $images_array, array( '.', '..' ) );
            foreach ( $images_array as $file )
            {
                nv_copyfile( $theme_default_dir . '/images/arrows/' . $file, $theme_dir . '/images/arrows/' . $file );
            }

            $images_array = scandir( $theme_default_dir . '/images/icons' );
            $images_array = array_diff( $images_array, array( '.', '..' ) );
            foreach ( $images_array as $file )
            {
                nv_copyfile( $theme_default_dir . '/images/icons/' . $file, $theme_dir . '/images/icons/' . $file );
            }

            //config.ini
            $xtpl = new XTemplate( 'config.tpl', NV_ROOTDIR . '/modules/' . $module_file . '/theme' );
            $xtpl->assign( 'THEME_INFO', $theme_info );
            foreach ( $data_positions as $data )
            {
                $xtpl->assign( 'DATA_POSITION', $data );
                $xtpl->parse( 'main.position' );
            }
            $xtpl->parse( 'main' );
            $config_ini = $xtpl->text( 'main' );
            file_put_contents( $theme_dir . '/config.ini', trim($config_ini), LOCK_EX );
            unset( $config_ini );

            //screen.css
            if ( $theme_css['additional'] == 1 )
            {
                $file_name_css = 'screen.stick.css';
            }
            elseif ( $theme_css['additional'] == 2 )
            {
                $file_name_css = 'screen.equal.css';
            }
            else
            {
                $file_name_css = 'screen.css';
            }
            $xtpl = new XTemplate( $file_name_css, NV_ROOTDIR . '/modules/' . $module_file . '/theme/css' );

            if ( $theme_css['pagetype'] == 'pageleft' )
            {
                $theme_css['pageAlign'] = '0';
            }
            else
            {
                $theme_css['pageAlign'] = 'auto';
            }

            if ( $theme_css['pagetype'] == 'pagefull' )
            {
                $theme_css['max_width'] = $theme_css['pageWidthValue'];
            }

            $xtpl->assign( 'THEME_CSS', $theme_css );
            if ( $theme_css['css_reset'] == 'none' )
            {
                $xtpl->parse( 'main.css_reset' );
            }

            if ( $theme_css['pagetype'] == 'pagefull' )
            {
                $xtpl->parse( 'main.pageFull_wrapper' );
                $xtpl->parse( 'main.pageFull_footer' );
            }
            else
            {
                $xtpl->parse( 'main.pageFixed_body' );
                $xtpl->parse( 'main.pageFixed_wrapper' );
                $xtpl->parse( 'main.pageFixed_footer' );
            }

            $prefix_layout = ( $theme_css['additional'] == 2 ) ? 'layout.equal.' : 'layout.';
            $array_check_positions = array( 'MENU_SITE', 'LEFT', 'RIGHT', 'FOOTER_SITE' );

            foreach ( $data_layout as $layout_i )
            {
                $xtpl->parse( 'main.' . str_replace( '-', '_', $layout_i ) );
                $content_layout = file_get_contents( $theme_default_dir . '/layout/' . $prefix_layout . $layout_i . '.tpl' );
                foreach ( $array_check_positions as $position )
                {
                    if ( ! in_array( $position, $position_tag ) )
                    {
                        $content_layout = str_replace( '[' . $position . ']', '', $content_layout );
                    }
                }
                file_put_contents( $theme_dir . '/layout/layout.' . $layout_i . '.tpl', $content_layout, LOCK_EX );
            }

            $xtpl->parse( 'main' );
            $config_css = $xtpl->text( 'main' );
            file_put_contents( $theme_dir . '/css/screen.css', $config_css, LOCK_EX );
            unset( $config_css );

            if ( $theme_css['horizontalMenuHeight'] > 0 and in_array( 'MENU_SITE', $position_tag ) )
            {
                $content_menu_site = "<div id=\"navigation\">[MENU_SITE]</div>";
            }
            elseif ( $theme_css['horizontalMenuHeight'] > 0 )
            {
                $content_menu_site = "<div id=\"navigation\"></div>";
            }
            else
            {
                $content_menu_site = "";
            }

            if ( in_array( $theme_css['css_reset'], array_keys( $array_css_reset ) ) and $theme_css['css_reset'] != "none" )
            {
                nv_copyfile( $theme_default_dir . "/css/reset_" . $theme_css['css_reset'] . ".css", $theme_dir . "/css/reset.css" );
                $stylesheetrese = "<link rel=\"stylesheet\" type=\"text/css\" href=\"{NV_BASE_SITEURL}themes/{TEMPLATE}/css/reset.css\" />";
            }
            else
            {
                $stylesheetrese = "";
            }

            //header.tpl
            if ( $theme_css['doctype'] == "html5" )
            {
                //HTML 5
                $config_header = "<!DOCTYPE HTML>\n<html>\n";
            }
            elseif ( $theme_css['doctype'] == "xhtmlStrict" )
            {
                //XHTML 1.0 Strict
                $config_header = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";
            }
            elseif ( $theme_css['doctype'] == "xhtmlTrans" )
            {
                //XHTML 1.0 Transitional
                $config_header = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";
            }
            elseif ( $theme_css['doctype'] == "htmlTrans" )
            {
                //HTML 4.01 Transitional
                $config_header = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">\n<html>\n";
            }
            else
            {
                //HTML 4.01 Strict
                $config_header = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">\n<html>\n";
            }

            $config_header .= "<head>
    {THEME_PAGE_TITLE} {THEME_META_TAGS}
    <link rel=\"icon\" href=\"{NV_BASE_SITEURL}favicon.ico\" type=\"image/vnd.microsoft.icon\" />
    <link rel=\"shortcut icon\" href=\"{NV_BASE_SITEURL}favicon.ico\" type=\"image/vnd.microsoft.icon\" />
    " . $stylesheetrese . "
    <link rel=\"stylesheet\" type=\"text/css\" href=\"{NV_BASE_SITEURL}themes/{TEMPLATE}/css/screen.css\" media=\"screen, projection\"/>
    {THEME_CSS}
    {THEME_SITE_RSS}
    {THEME_SITE_JS}
    <!--[if IE 6]>
        <link rel=\"stylesheet\" type=\"text/css\" href=\"{NV_BASE_SITEURL}themes/{TEMPLATE}/css/ie6.css\" />
        <script type=\"text/javascript\" src=\"{NV_BASE_SITEURL}js/fix-png-ie6.js\"></script>
        <script type=\"text/javascript\">
        DD_belatedPNG.fix('#');
        </script>
    <![endif]-->
    {THEME_MY_HEAD}
</head>
<body>
    <div id=\"wrapper\">
    	<div id=\"header\">
    		<strong>{SITE_NAME}</strong>
    	</div><!-- #header-->
    	" . $content_menu_site . "
    	{THEME_ERROR_INFO}";

            file_put_contents( $theme_dir . "/layout/header.tpl", $config_header, LOCK_EX );
            unset( $config_header );

            //footer.tpl
            $config_footer = "\n";
            if ( $theme_css['additional'] == 1 )
            {
                $config_footer .= "\t\t</div><!-- #wrapper -->
        <div id=\"footer\">
        	[FOOTER_SITE]
        </div><!-- #footer -->";
            }
            else
            {
                $config_footer .= "\t\t<div id=\"footer\">
        		[FOOTER_SITE]
        	</div><!-- #footer -->
        </div><!-- #wrapper -->";
            }
            $config_footer .= "
            <!-- BEGIN: for_admin -->
            <p class=\"show_query\">
                {CLICK_SHOW_QUERIES}
            </p>
            <div id=\"div_hide\" style=\"visibility: hidden; display: none;\">
                {SHOW_QUERIES_FOR_ADMIN}
            </div>
            <!-- END: for_admin -->
            <div id=\"run_cronjobs\" style=\"visibility: hidden; display: none;\">
                <img alt=\"cronjobs\" src=\"{THEME_IMG_CRONJOBS}\" width=\"1\" height=\"1\" />
            </div>
        {THEME_ADMIN_MENU}
        {THEME_MY_FOOTER}
        {THEME_FOOTER_JS}
    </body>
</html>";
            file_put_contents( $theme_dir . "/layout/footer.tpl", $config_footer, LOCK_EX );
            unset( $config_footer );

            $config_theme = "<?php\n\n";
            $config_theme .= NV_FILEHEAD . "\n\n";
            $config_theme .= file_get_contents( $theme_default_dir . "/theme.tpl" );
            $config_theme .= "\n";
            $config_theme .= "?>";

            file_put_contents( $theme_dir . "/theme.php", $config_theme, LOCK_EX );
            unset( $config_theme );

            $array_folder_theme = array();
            $array_folder_theme[] = NV_ROOTDIR . "/" . NV_TEMP_DIR . "/" . $tempdir;

            //Zip module
            $file_src = NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . NV_TEMPNAM_PREFIX . $tempdir . '.zip';
            require_once NV_ROOTDIR . '/includes/class/pclzip.class.php';
            $zip = new PclZip( $file_src );
            $zip->create( $theme_dir, PCLZIP_OPT_REMOVE_PATH, NV_ROOTDIR . "/" . NV_TEMP_DIR . "/" . $tempdir );

            nv_deletefile( NV_ROOTDIR . "/" . NV_TEMP_DIR . "/" . $tempdir, true );

            //Download file
            require_once ( NV_ROOTDIR . '/includes/class/download.class.php' );
            $download = new download( $file_src, NV_ROOTDIR . "/" . NV_TEMP_DIR, "nv4_theme_" . $theme_info['theme'] . ".zip" );
            $download->download_file();
            exit();
        }
        else
        {
            trigger_error( $mkdir[1], 256 );
        }
    }
}
else
{
    $theme_info['theme'] = 'sample';
    $theme_info['info_name'] = 'Theme sample';
    $theme_info['info_author'] = 'VinaDes.,Jsc';
    $theme_info['info_website'] = 'http://vinades.vn';
    $theme_info['info_description'] = 'Theme for NukeViet 4';

    $theme_css['doctype'] = 'xhtmlStrict';
    $theme_css['css_reset'] = 'eric';
    $theme_css['pagetype'] = 'pagecenter';
    $theme_css['pageWidthValue'] = 1000;
    $theme_css['headerHeight'] = 150;
    $theme_css['headerBgColor'] = 'FFE680';
    $theme_css['footerHeight'] = 120;
    $theme_css['footerBgColor'] = 'BFF08E';
    $theme_css['additional'] = 0;

    $theme_css['horizontalMenuHeight'] = 30;
    $theme_css['horizontalMenuBgColor'] = '8AA1B6';

    $theme_css['leftSidebarWidth'] = 190;
    $theme_css['leftSidebarBgColor'] = 'B5E3FF';

    $theme_css['rightSidebarWidth'] = 230;
    $theme_css['rightSidebarBgColor'] = 'FFACAA';

    $theme_css['bodyBgColor'] = 'FFFFFF';
    $theme_css['textColor'] = '000000';

    $theme_css['aColor'] = '000000';
    $theme_css['aHoverColor'] = 'FF0000';

    $theme_info['layoutdefault'] = 'left-body-right';
    $data_layout = array( 'body', 'left-body', 'body-right', 'left-body-right' );

    $data_positions[] = array( 'tag' => 'MENU_SITE', 'name' => 'Menu Site', 'name_vi' => $lang_module['positions_menu_site'] );
    $data_positions[] = array( 'tag' => 'LEFT', 'name' => 'Block Left', 'name_vi' => $lang_module['positions_left'] );
    $data_positions[] = array( 'tag' => 'RIGHT', 'name' => 'Block Right', 'name_vi' => $lang_module['positions_right'] );
    $data_positions[] = array( 'tag' => 'FOOTER_SITE', 'name' => 'Footer Site', 'name_vi' => $lang_module['positions_footer_site'] );
}

$page_title = $lang_module['SiteTitleTheme'];
$key_words = $module_info['keywords'];
$array_mod_title[] = array('catid' => 0, 'title' => $lang_module['SiteTitleTheme'], 'link' => $client_info['selfurl'] );

$xtpl = new XTemplate( $op . ".tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'THEME_INFO', $theme_info );
$xtpl->assign( 'THEME_CSS', $theme_css );

foreach ( $array_doctype_page as $value => $text )
{
    $data = array( 'value' => $value, 'text' => $text, 'selected' => ( $value == $theme_css['doctype'] ) ? 'selected="selected"' : '' );
    $xtpl->assign( 'DOCTYPE', $data );
    $xtpl->parse( 'main.doctype' );
}

foreach ( $array_css_reset as $value => $text )
{
    $data = array( 'value' => $value, 'text' => $text, 'selected' => ( $value == $theme_css['css_reset'] ) ? 'selected="selected"' : '' );
    $xtpl->assign( 'CSS_RESET', $data );
    $xtpl->parse( 'main.css_reset' );
}

foreach ( $array_css_page as $value => $text )
{
    $data = array( 'value' => $value, 'text' => $text, 'selected' => ( $value == $theme_css['pagetype'] ) ? 'selected="selected"' : '' );
    $xtpl->assign( 'PAGETYPE', $data );
    $xtpl->parse( 'main.pagetype' );
}

foreach ( $array_additional as $value => $text )
{
    $data = array( 'value' => $value, 'text' => $text, 'checked' => ( $value == $theme_css['additional'] ) ? 'checked="checked"' : '' );
    $xtpl->assign( 'ADDITIONAL', $data );
    $xtpl->parse( 'main.additional' );
}

$i = 0;
foreach ( $array_layout as $data )
{
    $i ++;
    $data['number'] = $i;
    $data['class'] = ( $i % 2 == 1 ) ? 'class="second"' : '';
    $data['layoutdefault'] = ( $data['value'] == $theme_info['layoutdefault'] ) ? 'checked="checked"' : '';
    $xtpl->assign( 'LAYOUT', $data );
    $ch = ( $data['value'] == $theme_info['layoutdefault'] or in_array( $data['value'], $data_layout ) ) ? 1 : 0;

    foreach ( $array_yes_no as $value => $text )
    {
        $data = array( 'value' => $value, 'text' => $text, 'checked' => ( $value == $ch ) ? 'checked="checked"' : '' );
        $xtpl->assign( 'LAYOUTCHECK', $data );
        $xtpl->parse( 'main.layout.layoutcheck' );
    }
    $xtpl->parse( 'main.layout' );

}

$limit = ( count( $data_positions ) > 2 ) ? count( $data_positions ) : 2;
$xtpl->assign( 'ITEMS_POSITIONS', $limit );

for ( $i = 0; $i < $limit; $i ++ )
{
    $data = ( isset( $data_positions[$i] ) ) ? $data_positions[$i] : array( 'tag' => '', 'name' => '', 'name_vi' => '' );
    $data['number'] = $i + 1;
    $data['class'] = ( $i % 2 == 1 ) ? 'class="second"' : '';
    $xtpl->assign( 'DATA_POSITION', $data );
    $xtpl->parse( 'main.positions' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );