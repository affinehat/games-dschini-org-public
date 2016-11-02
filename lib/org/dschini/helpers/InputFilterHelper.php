<?php
class InputFilterHelper
{
	public static function getString($input)
	{
		return stripslashes(strip_tags($input));
	}
	
	public static function getArray($input)
	{
		/*
		if(!is_array($input)||!isset($input)){
			return array();
		}
		*/
		return $input;
	}
	
	public static function getInt($input)
	{
		return (int)$input;
	}
}
