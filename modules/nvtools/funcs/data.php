<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Sat, 19 Mar 2011 16:50:45 GMT
 */

if ( !defined( 'NV_IS_MOD_NVTOOLS' ) )
	die( 'Stop!!!' );

$page_title = $lang_module['SiteTitleModule'];
$key_words = $module_info['keywords'];

$tablename = $nv_Request->get_title( 'tablename', 'post' );

$xtpl = new XTemplate( $op . ".tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'TABLENAME', $tablename );
$txtcode = '';
if ( preg_match( '/^[a-zA-Z0-9\_]+$/', $tablename ) )
{
	$item = $db->columns_array( $tablename );

	$func = $nv_Request->get_title( 'func', 'post' );
	if ( $func == 'pdo_insert' )
	{
		$_tmp_keyvalue = array();
		$txt_bindParam = '';
		$txt_post = "\$row = array();\n";
		foreach ( $item as $key => $_row )
		{
			$_tmp_keyvalue[] = ':' . $key;
			//[extra] => auto_increment
			//[type] => mediumint(8) unsigned
			//    [type] => varchar(255)
			//    [type] => text

			$txt_bindParam .= "\$stmt->bindParam( ':" . $key . "', \$row['" . $key . "'], PDO::PARAM_";
			if ( strpos( $_row['type'], 'text' ) !== false )
			{
				$txt_bindParam .= "STR, strlen(\$row['" . $key . "'])";
				$txt_post .= "\$row['" . $key . "'] = \$nv_Request->get_string( '" . $key . "', 'post', '' );\n";
			}
			elseif ( strpos( $_row['type'], 'int' ) !== false )
			{
				$txt_post .= "\$row['" . $key . "'] = \$nv_Request->get_int( '" . $key . "', 'post', 0 );\n";
				$txt_bindParam .= "INT";
			}
			else
			{
				$txt_post .= "\$row['" . $key . "'] = \$nv_Request->get_title( '" . $key . "', 'post', '' );\n";
				$txt_bindParam .= "STR";
			}
			$txt_bindParam .= " );\n";
		}

		$txtcode .= $txt_post;
		$txtcode .= "\n\n";
		$txtcode .= '$stmt = $db->prepare( "';
		$txtcode .= "INSERT INTO " . $tablename . " (" . implode( ', ', array_keys( $item ) ) . ") VALUES (" . implode( ', ', $_tmp_keyvalue ) . ")";
		$txtcode .= '" );';
		$txtcode .= "\n\n";

		$txtcode .= $txt_bindParam;
		$txtcode .= "\n";
		$txtcode .= '$ec = $stmt->execute();';
	}
	elseif ( $func == 'html_admin' )
	{
		$txtcode .= "<form action=\"{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}\" method=\"post\">\n";
		$txtcode .= "\t<table class=\"tab1\">\n";
		$txtcode .= "\t\t<tbody>\n";
		foreach ( $item as $key => $_row )
		{
			$txtcode .= "\t\t\t<tr>\n";
			$txtcode .= "\t\t\t\t<td> {LANG." . $key . "} </td>\n";
			$txtcode .= "\t\t\t\t<td><input type=\"text\" name=\"" . $key . "\" value=\"{DATA." . $key . "}\" /></td>\n";
			$txtcode .= "\t\t\t</tr>\n";
		}
		$txtcode .= "\t\t</tbody>\n";
		$txtcode .= "\t</table>\n";
		$txtcode .= "\t<div style=\"text-align: center\"><input name=\"submit\" type=\"submit\" value=\"{LANG.save}\" /></div>\n";
		$txtcode .= "</form>";
	}
}
$xtpl->assign( 'TXTCODE', $txtcode );

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include (NV_ROOTDIR . "/includes/header.php");
echo nv_site_theme( $contents );
include (NV_ROOTDIR . "/includes/footer.php");
