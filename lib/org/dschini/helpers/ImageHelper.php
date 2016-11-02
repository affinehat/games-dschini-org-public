<?php
class ImageHelper
{	
	public static function copyResized( $filename, $size )
    {
		$gis       = GetImageSize($filename);
		$type       = $gis[2];
		switch($type)
        {
        	case "1":
        		$imorig = imagecreatefromgif($filename);
        		break;
        	case "2":
        		$imorig = imagecreatefromjpeg($filename);
        		break;
        	case "3":
        		$imorig = imagecreatefrompng($filename);
        		break;
        	default: 
        		$imorig = imagecreatefromjpeg($filename);
        } 
        $x = imageSX($imorig);
        $y = imageSY($imorig);
        if($gis[0] <= $size){
        	$av = $x;
        	$ah = $y;
        } else {
            $yc = $y*1.3333333;
            $d = $x>$yc?$x:$yc;
            $c = $d>$size ? $size/$d : $size;
              $av = $x*$c; 
              $ah = $y*$c;
        }    
        $im = imagecreate($av, $ah);
        $im = imagecreatetruecolor($av,$ah);
	    if (imagecopyresampled($im,$imorig , 0,0,0,0,$av,$ah,$x,$y)){
    	    if (imagejpeg($im, $filename)){
    	        return true;
			} else {
            	return false;
			}
		}
	}
}