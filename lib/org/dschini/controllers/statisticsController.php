<?php
include_once('../config.php');

class statisticsController {

	public static $THEME = THEME_DEFAULT;

    public static function indexAction(){

        $_monthlyNewAccounts = json_decode(file_get_contents(BASEDIR.'cron/monthlyNewAccounts.json'),true);

        $_overallGameplays = json_decode(file_get_contents(BASEDIR.'cron/monthlyOverallGameplays.json'),true);

        // monthly gameplays (latest 12)
        $_monthlyGameplays = json_decode(file_get_contents(BASEDIR.'cron/monthlyGameplays.json'),true);
        $monthlyGameplays = array();
        foreach($_monthlyGameplays as $monthlyGameplay){
            $game_id = $monthlyGameplay['game_id'];
            $y = $monthlyGameplay['y'];
            $m = $monthlyGameplay['m'];
            if( empty($monthlyGameplays[$game_id]) || count($monthlyGameplays[$game_id]) < 12 ){
                $monthlyGameplays[$game_id][] = array($y,$m,$monthlyGameplay['cg']);
            }
        }
        return TemplateHelper::renderToResponse(self::$THEME,"html/statistics/index.phtml",
            array(
                'monthlyNewAccounts' => $_monthlyNewAccounts,
                'overallGameplays' => $_overallGameplays,
                'monthlyGameplays' => $monthlyGameplays,
                'games' => $GLOBALS['games']
            )
        );
    }

}
