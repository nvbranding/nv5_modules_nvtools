<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Thu, 16 Jan 2014 01:06:59 GMT
 */

if( ! defined( 'NV_IS_MOD_NVTOOLS' ) ) die( 'Stop!!!' );

$page_title = $lang_module['SiteTitleModule'];
$key_words = $module_info['keywords'];

$list_no = 'add, all, alter, analyze, and, as, asc, before, between, bigint, binary, both, by, call, cascade, case, change, char, character, check, collate, column, comment, condition, constraint, continue, convert, create, cross, current_user, cursor, database, databases, date, day_hour, day_minute, day_second, dec, decimal, declare, default, delayed, delete, desc, describe, distinct, distinctrow, drop, dual, else, elseif, enclosed, escaped, exists, exit, explain, false, fetch, file, float4, float8, for, force, foreign, from, fulltext, get, grant, group, having, high_priority, hour_minute, hour_second, identified, if, ignore, ignore_server_ids, in, index, infile, inner, insert, int1, int2, int3, int4, int8, integer, interval, into, is, iterate, join, key, keys, kill, leading, leave, left, level, like, limit, lines, load, lock, long, loop, low_priority, master_bind, master_heartbeat_period, master_ssl_verify_server_cert, match, middleint, minute_second, mod, mode, modify, natural, no_write_to_binlog, not, null, number, numeric, on, optimize, option, optionally, or, order, outer, outfile, partition, precision, primary, privileges, procedure, public, purge, read, real, references, release, rename, repeat, replace, require, resignal, restrict, return, revoke, right, rlike, rows, schema, schemas, select, separator, session, set, share, show, signal, spatial, sql_after_gtids, sql_before_gtids, sql_big_result, sql_calc_found_rows, sql_small_result, sqlstate, ssl, start, starting, straight_join, table, terminated, then, to, trailing, trigger, true, undo, union, unique, unlock, unsigned, update, usage, use, user, using, values, varcharacter, varying, view, when, where, while, with, write, year_month, zerofill';
$array_no = explode( ',', $list_no );
$array_no = array_map( 'trim', $array_no );
$array_no = array_unique( $array_no );
if( $db->dbtype == 'mysql' )
{
	$result = $db->query( "SHOW TABLE STATUS LIKE '" . $db_config['prefix'] . "\_%'" );
	while( $item = $result->fetch( ) )
	{
		$resulttab = $db->query( 'SHOW COLUMNS FROM ' . $item['name'] );
		$array_no_i = array( );
		while( $row = $resulttab->fetch( ) )
		{
			if( in_array( $row['field'], $array_no ) )
			{
				$array_no_i[] = $row['field'];
			}
		}
		if( ! empty( $array_no_i ) )
		{
			$contents .= '<br>' . $item['name'] . ': ' . implode( ', ', $array_no_i );
		}
	}
}
elseif( $db->dbtype == 'oci' )
{
	$query = $db->query( "select table_name from all_tables WHERE table_name LIKE '" . strtoupper( $db_config['prefix'] . "_%" ) . "'" );
	while( $item = $query->fetch( ) )
	{
		$resulttab = $this->query( "SELECT column_name FROM all_tab_columns WHERE table_name = '" . strtoupper( $item['table_name'] ) . "' ORDER BY column_id" );
		$array_no_i = array( );
		while( $row = $resulttab->fetch( ) )
		{
			if( in_array( $row['column_name'], $array_no ) )
			{
				$array_no_i[] = $row['column_name'];
			}
		}
		if( ! empty( $array_no_i ) )
		{
			$contents .= '<br>' . $item['name'] . ': ' . implode( ', ', $array_no_i );
		}
	}
}
if( ! empty( $contents ) )
{
	$contents = "Không nên sử dụng các cột trong các bảng sau: <br>" . $contents;
}
include (NV_ROOTDIR . "/includes/header.php");
echo nv_site_theme( $contents );
include (NV_ROOTDIR . "/includes/footer.php");