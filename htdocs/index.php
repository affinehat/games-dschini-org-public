<?php
/* 
 * The Front controller
 * This is developed to my best knowledge! You are warned ;)
 *
 * @author	John Weber
 * @date	16/12/08
 * @see		http://John.dschini.org/
 */
include("../config.php");
include("../urls.php");

/* Loop through the urls! */
foreach($urls as $pattern => $definition){
	
	/* Replace backslashes with slashes */
	$pattern = '/'.str_replace('/','\\/',substr($pattern,0,strlen($pattern))).'/';
	$params = array();
	
	/* Find the matching regex */
	if(preg_match($pattern, $_SERVER['REQUEST_URI'],$params_match)){
		
		/* Before Request Execution !!!
		 * 
		 * Put stuff here that you want to get done BEFORE calling the
		 * matching controller action! 
		 */
		RequestHelper::getInstance()->setController($definition['controller']);
		RequestHelper::getInstance()->setAction($definition['action']);
		RequestHelper::getInstance()->setRequiredRight($definition['right']);
		RequestHelper::getInstance()->setParams($params_match);

		/* check user rights */
		if( RequestHelper::getInstance()->userHasRequiredRight() ) { 
			
			/* Execution the matching controller action.
			 */
			$ok = call_user_func_array(
				array(
					RequestHelper::getInstance()->controller,
					RequestHelper::getInstance()->action
				),
				RequestHelper::getInstance()->params
			);
			
			if(!$ok){
				
				/* After Request Execution !!!
				 * 
				 * Put stuff here that you want to get done AFTER calling the
				 * matching controller action! 
				 */
				
				// do something
			}
			exit();
		}


        /*
         * Execution failed for Ajax and this will not respond with an error page but
         * with 403 because this is less restrictive then 402.
         */
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
        {
            http_response_code(403);
            exit;
        }

        /* Execution failed function.
         *
         * Put stuff here that you want to get done AFTER calling the
         * matching controller action!
         */
		if(RequestHelper::getInstance()->requiredRight == 4){
			RequestHelper::redirect('/login/?next='.$_SERVER['REQUEST_URI']);
		} else if(RequestHelper::getInstance()->requiredRight == 2){
			RequestHelper::redirect('/username/?next='.$_SERVER['REQUEST_URI']);
		} else {
			RequestHelper::redirect('/');
		}
	}
}
/* No controller or action found */
RequestHelper::redirect('/');

