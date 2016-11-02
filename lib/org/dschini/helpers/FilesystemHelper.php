<?php
class FilesystemHelper
{
	function mkdirRecursive($pathname, $mode)
	{
	    is_dir(dirname($pathname)) || self::mkdirRecursive(dirname($pathname), $mode);
	    return is_dir($pathname) || @mkdir($pathname, $mode);
	}
}
