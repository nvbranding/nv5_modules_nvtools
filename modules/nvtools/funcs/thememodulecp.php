<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Sat, 19 Mar 2011 16:50:45 GMT
 */

if( !defined( 'NV_IS_MOD_NVTOOLS' ) ) die( 'Stop!!!' );

$page_title = $lang_module['thememdcp'];
$key_words = $module_info['keywords'];

$array_mod_title[] = array(
	'catid' => 0,
	'title' => $lang_module['thememdcp'],
	'link' => $client_info['selfurl']
);

$xtpl = new XTemplate( "thememodulecp.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );

// Get theme config
$ini = file( NV_ROOTDIR . '/modules/' . $module_file . '/ini/theme.ini' );
$theme_syscfg = array();
$section = '';

foreach( $ini as $line )
{
	$line = trim( $line );
	
	if( ! empty( $line ) )
	{
		if( preg_match( '/^\[(.*?)\]$/', $line, $match ) )
		{
			$section = $match[1];
			continue;
		}
		
		if( ! empty( $section ) )
		{
			$theme_syscfg[$section][] = $line;
		}		
	}
}

unset( $ini, $line, $section );

// Scan theme
$array_themes_desktop = nv_scandir( NV_ROOTDIR . '/themes', $global_config['check_theme'] );
$array_themes_mobile = nv_scandir( NV_ROOTDIR . '/themes', $global_config['check_theme_mobile'] );
$array_themes = array_merge_recursive( $array_themes_desktop, $array_themes_mobile );

if( $nv_Request->isset_request( 'fromtheme', 'post' ) )
{
	$fromtheme = trim( $nv_Request->get_title( 'fromtheme', 'post', '' ) );
	$cpmodule = trim( $nv_Request->get_title( 'cpmodule', 'post', '' ) );
	$totheme = trim( $nv_Request->get_title( 'totheme', 'post', '' ) );
	
	if( ! empty( $fromtheme ) and ! empty( $cpmodule ) and ! empty( $totheme ) and file_exists( NV_ROOTDIR . '/themes/' . $fromtheme ) and file_exists( NV_ROOTDIR . '/themes/' . $totheme ) )
	{
		if( ! file_exists( NV_ROOTDIR . '/themes/' . $totheme . '/modules' ) )
		{
			nv_mkdir( NV_ROOTDIR . '/themes/' . $totheme, 'modules' );
		}
		if( ! file_exists( NV_ROOTDIR . '/themes/' . $totheme . '/images' ) )
		{
			nv_mkdir( NV_ROOTDIR . '/themes/' . $totheme, 'images' );
		}
		if( file_exists( NV_ROOTDIR . '/themes/' . $totheme . '/modules/' . $cpmodule ) )
		{
			nv_deletefile( NV_ROOTDIR . '/themes/' . $totheme . '/modules/' . $cpmodule, true );
		}
		if( file_exists( NV_ROOTDIR . '/themes/' . $totheme . '/images/' . $cpmodule ) )
		{
			nv_deletefile( NV_ROOTDIR . '/themes/' . $totheme . '/images/' . $cpmodule, true );
		}
		if( file_exists( NV_ROOTDIR . '/themes/' . $totheme . '/css/' . $cpmodule . '.css' ) )
		{
			nv_deletefile( NV_ROOTDIR . '/themes/' . $totheme . '/css/' . $cpmodule . '.css' );
		}
		
		nv_mkdir( NV_ROOTDIR . '/themes/' . $totheme . '/images', $cpmodule );
		nv_mkdir( NV_ROOTDIR . '/themes/' . $totheme . '/modules', $cpmodule );
		
		// Copy images file
		$images = nv_list_all_files( NV_ROOTDIR . '/themes/' . $fromtheme . '/images/' . $cpmodule );
		
		foreach( $images as $image )
		{
			nv_copyfile( NV_ROOTDIR . '/themes/' . $fromtheme . '/images/' . $cpmodule . '/' . $image, NV_ROOTDIR . '/themes/' . $totheme . '/images/' . $cpmodule . '/' . $image );
		}
		
		// Copy tpl file
		$tpls = nv_list_all_files( NV_ROOTDIR . '/themes/' . $fromtheme . '/modules/' . $cpmodule );
		
		foreach( $tpls as $tpl )
		{
			nv_copyfile( NV_ROOTDIR . '/themes/' . $fromtheme . '/modules/' . $cpmodule . '/' . $tpl, NV_ROOTDIR . '/themes/' . $totheme . '/modules/' . $cpmodule . '/' . $tpl );
		}
		
		// Copy css file
		if( file_exists( NV_ROOTDIR . '/themes/' . $fromtheme . '/css/' . $cpmodule . '.css' ) )
		{
			nv_copyfile( NV_ROOTDIR . '/themes/' . $fromtheme . '/css/' . $cpmodule . '.css', NV_ROOTDIR . '/themes/' . $totheme . '/css/' . $cpmodule . '.css' );
		}
		
		die('OK|OK');
	}
	
	die('ERR|' . $lang_module['thememdcp_error_postdata']);
}

if( $nv_Request->isset_request( 'fromtheme', 'get' ) )
{
	$fromtheme = trim( $nv_Request->get_title( 'fromtheme', 'get', '' ) );
	
	$array_modules = nv_scandir( NV_ROOTDIR . '/themes/' . $fromtheme . '/modules', $global_config['check_module'] );
	
	// Write module
	foreach( $array_modules as $module )
	{
		$xtpl->assign( 'MODULE', $module );		
		$xtpl->parse( 'cpmodule.module' );
	}
	
	// Write theme
	foreach( $array_themes as $theme )
	{
		if( $theme != $fromtheme )
		{
			$xtpl->assign( 'THEME', $theme );		
			$xtpl->parse( 'cpmodule.theme' );			
		}
	}
	
	$xtpl->parse( 'cpmodule' );
	die( $xtpl->text( 'cpmodule' ) );
}

foreach( $array_themes as $theme )
{
	$xtpl->assign( 'THEME', $theme );
	$xtpl->parse( 'main.theme' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include (NV_ROOTDIR . "/includes/header.php");
echo nv_site_theme( $contents );
include (NV_ROOTDIR . "/includes/footer.php");