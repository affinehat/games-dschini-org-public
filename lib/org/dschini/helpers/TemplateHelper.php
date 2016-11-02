<?php
/*
 * TemplateHelper
 * @author	John Weber
 * @date	30/11/08
 * @see		http://dschini.googlecode.com/
 */
class TemplateHelper {
	
	/*
	 * renderToResponse
	 */
	public static function renderToResponse( $theme, $page, $data=array() ){
		foreach($data AS $var => $value){
			$$var = $value;
		}
		if(!@include_once( $theme.$page ) ) {
			echo "Error: [".$theme.$page."] -> TemplateHelper does not exist";
			return;
		}
	}

    public static function wrap($text, $nr=10) {
        $mytext=explode(" ",trim($text));
        $newtext=array();
        foreach($mytext as $k=>$txt)
        {
            if (strlen($txt)>$nr)
            {
                $txt=wordwrap($txt, $nr, "-", 1);
            }
            $newtext[]=$txt;
        }
        return implode(" ",$newtext);
    }

}