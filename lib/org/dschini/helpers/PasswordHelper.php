<?php
class PasswordHelper
{
	public static function create($length=8)
	{
		$string = md5((string)mt_rand() . $_SERVER['REMOTE_ADDR'] . time());
		$start = rand(0,strlen($string)-$length);
		return substr($string, $start, $length);
	}
}
