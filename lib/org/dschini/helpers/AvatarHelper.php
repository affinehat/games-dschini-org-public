<?php
class AvatarHelper
{
	/*
	 * imgurl
	 * type can be 'monsterid','identicon','wavatar',null
	 */
	public static function imgurl($email,$type='wavatar',$size=30)
	{
		return "http://www.gravatar.com/avatar/".(md5($email)).($type ? "?s=".$size."&d=".$type : '');
	}

	/*
	 * imglevel
	 */
	public static function imglevel($points)
	{
		if($points<=10){
			$level = 1;
		}elseif($points<=50){
			$level = 2;
		}elseif($points<=100){
			$level = 3;
		}elseif($points<=150){
			$level = 4;	
		}elseif($points<=250){
			$level = 5;
		}elseif($points<=400){
			$level = 6;
		}elseif($points<=600){
			$level = 7;
		}elseif($points<=900){
			$level = 8;
		}elseif($points<=1400){
			$level = 9;
		}elseif($points<=2000){
			$level = 10;
		}elseif($points<=2600){
			$level = 11;
		}elseif($points<=3200){
			$level = 12;
		}elseif($points<=4000){
			$level = 13;
		}elseif($points<=5000){
			$level = 14;
		}elseif($points<=6000){
			$level = 15;
		}elseif($points<=7000){
			$level = 16;
		}elseif($points<=8000){
			$level = 17;
		}elseif($points<=9000){
			$level = 18;
        }elseif($points<=10000){
            $level = 19;
        }elseif($points<=12000){
            $level = 20;
        }elseif($points<=14000){
            $level = 21;
        }elseif($points<=16000){
            $level = 22;
        }elseif($points<=18000){
            $level = 23;
        }elseif($points<=20000){
            $level = 24;
        }elseif($points<=24000){
            $level = 25;
        }elseif($points<=28000){
            $level = 26;
        }elseif($points<=32000){
            $level = 27;
        }elseif($points<=36000){
            $level = 28;
        }elseif($points<=40000){
            $level = 29;
		}else{
			$level = 30;
		}
		return '<img src="/img/levels/'.$level.'.gif" title="Member Level '.$level.' ('.$points.' Points)" />';
	}
}
