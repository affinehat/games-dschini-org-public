<?php
class StatisticsProxy
{
    public static function monthlyGameplays(){
        $sql = "SELECT game_id, COUNT( * ) AS cg, YEAR( created ) AS y, MONTH( created ) AS m
            FROM  highscores
            GROUP BY y, m, game_id
            ORDER BY game_id DESC , y DESC , m DESC";
        return DBConnectionHelper::getInstance()->query($sql);
    }

    public static function overallGameplays(){
        $sql = "SELECT game_id, COUNT( * ) AS cg FROM  highscores
            WHERE created > NOW( ) - INTERVAL 12 MONTH
            GROUP BY game_id";
        return DBConnectionHelper::getInstance()->query($sql);
    }

    public static function monthlyNewAccounts($approved=false){
        $sql_approved = $approved ? 'AND approved = 1' : '';
        $sql = "SELECT COUNT( * ) AS c, YEAR( created ) AS y, MONTH( created ) AS m
            FROM  accounts
            WHERE created > NOW( ) - INTERVAL 12 MONTH
            $sql_approved
            GROUP BY m
            ORDER BY y DESC , m DESC";
        return DBConnectionHelper::getInstance()->query($sql);
    }

}
