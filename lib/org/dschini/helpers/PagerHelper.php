<?php
class PagerHelper
{
    public static function getArray($amount,$current,$itemsPerPage,$resultCount){
	  $pager = array(
		'display'=>$itemsPerPage<$amount?true:false,
	    'itemsPerPage'=>$itemsPerPage,
	    'resultCount'=>$resultCount,
	    'current'=>$current,
		'previous'=>$current-1>0?$current-1:null,
		'next'=>$current*$itemsPerPage<$amount?($current+1):null,
		'amount'=>$amount,
	  );
	  for($i=$current-5; $i<$current; $i++){
		if($i>0){
			$i*$itemsPerPage<$amount-1?$pager['pages'][]=$i:null;
		}
	  }
	  $pager['pages'][] = $current;
	  for($i=$current+1; $i<$current+6; $i++){
	    (($i-1)*$itemsPerPage-($itemsPerPage-$resultCount))<$amount?$pager['pages'][]=$i:null;
	  }
	  return $pager;
	}
}