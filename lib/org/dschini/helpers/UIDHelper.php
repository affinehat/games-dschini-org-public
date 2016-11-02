<?php
class UIDHelper
{
	public static function RFC4122()
	{
		return sprintf( '%04x%04x%04x%04x%04x%04x%04x%04x',
			mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
			mt_rand( 0, 0x0fff ) | 0x4000,
			mt_rand( 0, 0x3fff ) | 0x8000,
			mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ) );
	}
	
	public static function MD5()
	{
		return md5(uniqid(rand(), true));
	}
	
}
