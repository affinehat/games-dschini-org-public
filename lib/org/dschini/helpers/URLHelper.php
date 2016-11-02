<?php
/*
 * URLHelper
 * @author	John Weber
 * @date	30/11/08
 * @see		http://dschini.googlecode.com/
 */
class URLHelper {
	
	/*
	 * clean
	 */
	public static function clean($url)
	{
		$search = array('_','.',' ','ä','ö','ü','Ä','Ö','Ü','ß');
		$replace = array('-','-','-','ae','oe','ue','Ae','Oe','Ue','ss');
		return strtolower(trim(preg_replace('=-{2,}=','-',str_replace($search,$replace,preg_replace('=[^ _a-z0-9äöüß.-]+=iuU','',$url))),'-'));
	}
}
