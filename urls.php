<?php
/* urls */
$urls = array(

    /* scores */
    '^/trash/(?P<id>\d+)/' => array('right'=>RIGHT_MEMBER,'controller'=>'userController','action'=>'trashAction'),
    '^/read/(?P<id>\d+)/' => array('right'=>RIGHT_MEMBER,'controller'=>'userController','action'=>'readAction'),
    '^/inbox/unread/count/' => array('right'=>RIGHT_MEMBER,'controller'=>'userController','action'=>'inboxUnreadCountAction'),
    '^/inbox/' => array('right'=>RIGHT_MEMBER,'controller'=>'userController','action'=>'inboxAction'),
    '^/outbox/' => array('right'=>RIGHT_MEMBER,'controller'=>'userController','action'=>'outboxAction'),
    '^/trashbox/' => array('right'=>RIGHT_MEMBER,'controller'=>'userController','action'=>'trashboxAction'),
    '^/compose/(?P<username>\w+)/(?P<id>\d+)/' => array('right'=>RIGHT_MEMBER,'controller'=>'userController','action'=>'composeAction'),
    '^/compose/(?P<username>\w+)/' => array('right'=>RIGHT_MEMBER,'controller'=>'userController','action'=>'composeAction'),
    '^/player/(?P<username>\w+)/' => array('right'=>RIGHT_MEMBER,'controller'=>'userController','action'=>'profileAction'),

    /* scores */
    '^/score/submit/' => array('right'=>RIGHT_PUBLIC,'controller'=>'scoreController','action'=>'submitAction'),

    /* statistics */
    '^/statistics/' => array('right'=>RIGHT_PUBLIC,'controller'=>'statisticsController','action'=>'indexAction'),

    /* userdata */
	'^/userdata/put/' => array('right'=>RIGHT_PUBLIC,'controller'=>'userdataController','action'=>'putAction'),
	'^/userdata/get/' => array('right'=>RIGHT_PUBLIC,'controller'=>'userdataController','action'=>'getAction'),
	'^/userdata/friends/' => array('right'=>RIGHT_PUBLIC,'controller'=>'userdataController','action'=>'friendsAction'),

        /* shop */
	'^/shop/delitem/' => array('right'=>RIGHT_PUBLIC,'controller'=>'shopController','action'=>'delitemAction'),
	'^/shop/setitem/' => array('right'=>RIGHT_PUBLIC,'controller'=>'shopController','action'=>'setitemAction'),
        '^/shop/getitem/' => array('right'=>RIGHT_PUBLIC,'controller'=>'shopController','action'=>'getitemAction'),
	'^/shop/getallitemsofauthor/' => array('right'=>RIGHT_PUBLIC,'controller'=>'shopController','action'=>'getallitemsofauthorAction'),
	'^/shop/getallitemsofuser/' => array('right'=>RIGHT_PUBLIC,'controller'=>'shopController','action'=>'getallitemsofuserAction'),

	/* payments */
    '^/coins/approve/paypal/' => array('right'=>RIGHT_PUBLIC,'controller'=>'paymentController','action'=>'paypalIPNsActions'),
    '^/coins/step3/' => array('right'=>RIGHT_MEMBER,'controller'=>'paymentController','action'=>'coinsStep3Action'),
	'^/coins/step2/' => array('right'=>RIGHT_MEMBER,'controller'=>'paymentController','action'=>'coinsStep2Action'),
	'^/coins/step1/' => array('right'=>RIGHT_MEMBER,'controller'=>'paymentController','action'=>'coinsStep1Action'),
    '^/coins/error/' => array('right'=>RIGHT_MEMBER,'controller'=>'paymentController','action'=>'coinsErrorAction'),
    '^/coins/cancel/' => array('right'=>RIGHT_MEMBER,'controller'=>'paymentController','action'=>'coinsCancelAction'),
	'^/coins/' => array('right'=>RIGHT_MEMBER,'controller'=>'paymentController','action'=>'coinsStep1Action'),
	'^/buy/(?P<id>\d+)/' => array('right'=>RIGHT_MEMBER,'controller'=>'paymentController','action'=>'buyAction'),

	/* games */
	//'^/winners/(?P<game_id>\d+)/' => array('right'=>RIGHT_MEMBER,'controller'=>'gameController','action'=>'winnersByGameIdAction'),
    '^/scores/(?P<game_id>\d+)/(?P<page_no>\d+)/' => array('right'=>RIGHT_MEMBER,'controller'=>'gameController','action'=>'scoresByGameIdAction'),
	'^/logs/timestamp/(?P<timestamp>\d+)/(?P<amount>\d+)/' => array('right'=>RIGHT_PUBLIC,'controller'=>'gameController','action'=>'logsTimestampAction'),
	'^/logs/latest/(?P<amount>\d+)/' => array('right'=>RIGHT_PUBLIC,'controller'=>'gameController','action'=>'logsLatestAction'),
	'^/play/(?P<id>\d+)/logs/' => array('right'=>RIGHT_PUBLIC,'controller'=>'gameController','action'=>'logsByGameIdAction'),
	'^/play/(?P<id>\d+)/positions/' => array('right'=>RIGHT_PUBLIC,'controller'=>'gameController','action'=>'positionsByGameIdAction'),
	'^/play/(?P<id>\d+)/savescore/' => array('right'=>RIGHT_PLAYER,'controller'=>'gameController','action'=>'savescoreAction'),
	'^/play/(?P<id>\d+)/' => array('right'=>RIGHT_PLAYER,'controller'=>'gameController','action'=>'playByGameIdAction'),

	/* account */
	'^/activate/(?P<uid>\w+)/'	=> array('right'=>RIGHT_PUBLIC,'controller'=>'accountController','action'=>'activateAction'),
	'^/lostpassword/step2/' => array('right'=>RIGHT_PUBLIC,'controller'=>'accountController','action'=>'lostpasswordstep2Action'),
	'^/lostpassword/' => array('right'=>RIGHT_PUBLIC,'controller'=>'accountController','action'=>'lostpasswordstep1Action'),
	'^/register/step2/' => array('right'=>RIGHT_PUBLIC,'controller'=>'accountController','action'=>'registerstep2Action'),
	'^/register/' => array('right'=>RIGHT_PUBLIC,'controller'=>'accountController','action'=>'registerstep1Action'),
	'^/logout/' => array('right'=>RIGHT_PUBLIC,'controller'=>'accountController','action'=>'logoutAction'),
	'^/login/' => array('right'=>RIGHT_PUBLIC,'controller'=>'accountController','action'=>'loginAction'),
	'^/accounts/latest/' => array('right'=>RIGHT_PUBLIC,'controller'=>'accountController','action'=>'latestAccountsAction'),
	'^/username/' => array('right'=>RIGHT_PUBLIC,'controller'=>'accountController','action'=>'usernameAction'),

	/* pages */
	//'^/molecules/' => array('right'=>RIGHT_PUBLIC,'controller'=>'pageController','action'=>'moleculesAction'),
    //'^/globetrottermobile/' => array('right'=>RIGHT_PUBLIC,'controller'=>'pageController','action'=>'globetrottermobileAction'),
	'^/termsconditions/' => array('right'=>RIGHT_PUBLIC,'controller'=>'pageController','action'=>'termsconditionsAction'),
	'^/game/(?P<id>\d+)/' => array('right'=>RIGHT_PLAYER,'controller'=>'gameController','action'=>'playByGameIdAction'),
	'^/lobby/' => array('right'=>RIGHT_PLAYER,'controller'=>'pageController','action'=>'lobbyAction'),
	'^/chat/' => array('right'=>RIGHT_MEMBER,'controller'=>'pageController','action'=>'chatAction'),
    '^/about/' => array('right'=>RIGHT_PUBLIC,'controller'=>'pageController','action'=>'aboutAction'),
	//'^/donate/' => array('right'=>RIGHT_PUBLIC,'controller'=>'pageController','action'=>'donateAction'),
	'^/$' => array('right'=>RIGHT_PUBLIC,'controller'=>'pageController','action'=>'indexAction'),
);
