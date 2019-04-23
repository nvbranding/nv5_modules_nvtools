<?php

/**
 * @Project NUKEVIET 4.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 23/11/2010, 20:46
 */

/**
 * CJzip
 *
 * @package
 * @author NUKEVIET 3.0
 * @copyright VINADES.,JSC
 * @version 2010
 * @access public
 */
class CSSzip
{

	public function __construct()
	{

	}

	/**
	 * CJzip::commentCB()
	 *
	 * @param mixed $m
	 * @return
	 */
	private function commentCB( $m )
	{
		$hasSurroundingWs = ( trim( $m[0] ) !== $m[1] );
		$m = $m[1];
		if( $m === 'keep' )
		{
			return '/**/';
		}
		if( $m === '" "' )
		{
			return '/*" "*/';
		}
		if( preg_match( '@";\\}\\s*\\}/\\*\\s+@', $m ) )
		{
			return '/*";}}/* */';
		}
		if( preg_match( '@^/\\s*(\\S[\\s\\S]+?)\\s*/\\*@x', $m, $n ) )
		{
			return "/*/" . $n[1] . "/**/";
		}
		if( substr( $m, -1 ) === '\\' )
		{
			return '/*\\*/';
		}
		if( $m !== '' && $m[0] === '/' )
		{
			return '/*/*/';
		}
		return $hasSurroundingWs ? ' ' : '';
	}

	/**
	 * CJzip::selectorsCB()
	 *
	 * @param mixed $m
	 * @return
	 */
	private function selectorsCB( $m )
	{
		return preg_replace( '/\\s*([,>+~])\\s*/', '$1', $m[0] );
	}

	/**
	 * CJzip::fontFamilyCB()
	 *
	 * @param mixed $m
	 * @return
	 */
	private function fontFamilyCB( $m )
	{
		$m[1] = preg_replace( '/\\s*("[^"]+"|\'[^\']+\'|[\\w\\-]+)\\s*/x', '$1', $m[1] );
		return 'font-family:' . $m[1] . $m[2];
	}

	/**
	 * CJzip::compress_css()
	 *
	 * @param mixed $cssContent
	 * @return
	 */
	private function compress_css( $cssContent )
	{
		$cssContent = preg_replace( "/url[\s]*\([\s]*[\'|\"](.*)?[\'|\"][\s]*\)/", "url($1)", $cssContent );

		$cssContent = preg_replace( '@>/\\*\\s*\\*/@', '>/*keep*/', $cssContent );
		$cssContent = preg_replace( '@/\\*\\s*\\*/\\s*:@', '/*keep*/:', $cssContent );
		$cssContent = preg_replace( '@:\\s*/\\*\\s*\\*/@', ':/*keep*/', $cssContent );
		$cssContent = preg_replace_callback( '@\\s*/\\*([\\s\\S]*?)\\*/\\s*@', array( $this, 'commentCB' ), $cssContent );

		$cssContent = preg_replace( '/[\s\t\r\n]+/', ' ', $cssContent );
		$cssContent = preg_replace( '/[\s]*(\:|\,|\;|\{|\})[\s]*/', "$1", $cssContent );
		$cssContent = preg_replace( "/[\#]+/", "#", $cssContent );
		$cssContent = str_replace( array(
			' 0px',
			':0px',
			';}',
			':0 0 0 0',
			':0.',
			' 0.' ), array(
			' 0',
			':0',
			'}',
			':0',
			':.',
			' .' ), $cssContent );
		$cssContent = preg_replace( '/\\s*([{;])\\s*([\\*_]?[\\w\\-]+)\\s*:\\s*(\\b|[#\'"-])/x', '$1$2:$3', $cssContent );

		$cssContent = preg_replace_callback( '/(?:\\s*[^~>+,\\s]+\\s*[,>+~])+\\s*[^~>+,\\s]+{/x', array( $this, 'selectorsCB' ), $cssContent );
		$cssContent = preg_replace( '/([^=])#([a-f\\d])\\2([a-f\\d])\\3([a-f\\d])\\4([\\s;\\}])/i', '$1#$2$3$4$5', $cssContent );
		$cssContent = preg_replace_callback( '/font-family:([^;}]+)([;}])/', array( $this, 'fontFamilyCB' ), $cssContent );
		$cssContent = preg_replace( '/@import\\s+url/', '@import url', $cssContent );
		$cssContent = preg_replace( '/:first-l(etter|ine)\\{/', ':first-l$1 {', $cssContent );
		$cssContent = preg_replace( "/[^\}]+\{[\s|\;]*\}[\s]*/", "", $cssContent );
		$cssContent = trim( str_replace( '!', ' !', $cssContent ) );
		$cssContent = preg_replace( "/[ ]+/", " ", $cssContent );
		return $cssContent;
	}

	/**
	 * CJzip::loadFile()
	 *
	 * @return
	 */
	public function processFile( $filename )
	{
		$css = $this->compress_css( file_get_contents( $filename ) );
		file_put_contents( $filename, $css, LOCK_EX );
	}

}