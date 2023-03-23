<?php

declare(strict_types=1);

return [
    /**
     * Bot configuration
     */
    'bot'      => [
        'name'    => env('PHP_TELEGRAM_BOT_NAME', ''),
        'api_key' => env('PHP_TELEGRAM_BOT_API_KEY', ''),
    ],

    /**
     * Database integration
     */
    'database' => [
        'enabled'    => true,
        'connection' => env('DB_CONNECTION', 'mysql'),
    ],

    'commands' => [
        'before'  => true,
        'paths'   => [
            base_path('/app/Telegram/Commands')
        ],
        'configs' => [
            
        ],
        'map' => [
            'attacks' => 'App\Telegram\Custom\AttacksCommand',
            'claimed' => 'App\Telegram\Attacks\ClaimedCommand',
			'book' => 'App\Telegram\Attacks\BookCommand',
			'drop' => 'App\Telegram\Attacks\DropCommand',
			'free' => 'App\Telegram\Attacks\FreeCommand',
			'launch' => 'App\Telegram\Attacks\LaunchCommand',
			'last24' => 'App\Telegram\Custom\Last24Command',
			'top5' => 'App\Telegram\Custom\Top5Command',
			'maxcap' => 'App\Telegram\Custom\MaxcapCommand',
			'parse' => 'App\Telegram\Custom\ParseCommand',
			'epenis' => 'App\Telegram\Custom\EpenisCommand',
			'galpenis' => 'App\Telegram\Custom\GalpenisCommand',
			'apenis' => 'App\Telegram\Custom\ApenisCommand',
			'bigdick' => 'App\Telegram\Custom\BigdickCommand',
			'winners' => 'App\Telegram\Custom\BigdickCommand',
			'loosecunts' => 'App\Telegram\Custom\LoosecuntsCommand',
			'loosers' => 'App\Telegram\Custom\LoosersCommand',
			'afford' => 'App\Telegram\Custom\AffordCommand',
			'latestscan' => 'App\Telegram\Custom\LatestscanCommand',
			'cookie' => 'App\Telegram\Custom\CookieCommand',
            'cost' => 'App\Telegram\Custom\CostCommand',
            'eff' => 'App\Telegram\Custom\EffCommand',
            'help' => 'App\Telegram\Custom\HelpCommand',
            'intel' => 'App\Telegram\Custom\IntelCommand',
            'lookup' => 'App\Telegram\Custom\LookupCommand',
            'req' => 'App\Telegram\Custom\ReqCommand',
            'reqs' => 'App\Telegram\Custom\ReqsCommand',
            'roidcost' => 'App\Telegram\Custom\RoidcostCommand',
            'setnick' => 'App\Telegram\Custom\SetnickCommand',
            'setplanet' => 'App\Telegram\Custom\SetplanetCommand',
            'ship' => 'App\Telegram\Custom\ShipCommand',
            'spam' => 'App\Telegram\Custom\SpamCommand',
            'stop' => 'App\Telegram\Custom\StopCommand',
            'tools' => 'App\Telegram\Custom\ToolsCommand',
		    'tick' => 'App\Telegram\Custom\TickCommand',
			'whodidthis' => 'App\Telegram\Custom\WhodidthisCommand',
			'afford' => 'App\Telegram\Custom\AffordCommand',
			'call' => 'App\Telegram\Custom\CallCommand',
			'sms' => 'App\Telegram\Custom\SmsCommand',
			'jpg' => 'App\Telegram\Custom\JpgCommand',
			'useless' => 'App\Telegram\Custom\UselessCommand',
			
		    /* ADMIN */
	            'activechannels'=> 'App\Telegram\Custom\Admin\ActiveChannelsCommand',
	            'leave'         => 'App\Telegram\Custom\Admin\LeaveChannelCommand',
	            'members'       => 'App\Telegram\Custom\Admin\MembersCommand',
	            'bream'         => 'App\Telegram\Custom\Admin\BreamIntelCommand',
	            'build'	        => 'App\Telegram\Custom\Admin\BuildCommand',
	            'review'	    => 'App\Telegram\Custom\Admin\ReviewCommand',

		    /* SCANS */
	            'scans'         => 'App\Telegram\Custom\Scans\ScansCommand',
	            'req'           => 'App\Telegram\Custom\Scans\ReqCommand',
	            'reqcount'      => 'App\Telegram\Custom\Scans\ReqcountCommand',
	            'reqs'          => 'App\Telegram\Custom\Scans\ReqsCommand',
	            'pscan'         => 'App\Telegram\Custom\Scans\PlanetCommand',
	            'dscan'         => 'App\Telegram\Custom\Scans\DevelopmentCommand',
	            'ascan'         => 'App\Telegram\Custom\Scans\AdvancedUnitCommand',
	            'uscan'         => 'App\Telegram\Custom\Scans\UnitCommand',
	            'jscan'         => 'App\Telegram\Custom\Scans\JumpgateCommand',
	            'nscan'         => 'App\Telegram\Custom\Scans\NewsCommand',
				'mscan'         => 'App\Telegram\Custom\Scans\MilitaryCommand',
	            'jan'           => 'App\Telegram\Custom\Scans\JanCommand',
	            'jmac'          => 'App\Telegram\Custom\Scans\JmacCommand',
	            'funddestroyer' => 'App\Telegram\Custom\Scans\FundDestroyerCommand',
	            'cancel' 	    => 'App\Telegram\Custom\Scans\CancelCommand',
	            'scanhistory'   => 'App\Telegram\Custom\Scans\ScanHistoryCommand',

		    /* OTHER */
	            'test'          => 'App\Telegram\Custom\TestCommand',
	            'base'          => 'App\Telegram\Custom\BaseCommand',
	            'mayday'        => 'App\Telegram\Custom\MaydayCommand',
	            'spam'          => 'App\Telegram\Custom\SpamCommand',
	            'tools'         => 'App\Telegram\Custom\ToolsCommand',
	            'bonus'         => 'App\Telegram\Custom\BonusCommand',
	            'help'          => 'App\Telegram\Custom\HelpCommand',
	            'stockpile'     => 'App\Telegram\Custom\StockpileCommand',
	            'topdists'      => 'App\Telegram\Custom\TopdistsCommand',
	            'localtime'     => 'App\Telegram\Custom\LocaltimeCommand',
	            'top10'        => 'App\Telegram\Custom\Top10Command',
	            'topamps'       => 'App\Telegram\Custom\TopAmpsCommand',
	            'history'       => 'App\Telegram\Custom\HistoryCommand',
	            'miningcap'     => 'App\Telegram\Custom\MiningcapCommand',
	            'tickplan'      => 'App\Telegram\Custom\TickPlanCommand',
	            'failures'      => 'App\Telegram\Custom\FailuresCommand',
	            'notifications' => 'App\Telegram\Custom\NotificationsCommand',
	            'bigdicks'	    => 'App\Telegram\Custom\BigDicksCommand',
	            'smalldicks'    => 'App\Telegram\Custom\SmallDicksCommand',
	            'aliases'	    => 'App\Telegram\Custom\AliasesCommand',
	            'summary'	    => 'App\Telegram\Custom\SummaryCommand',
				'adminhelp'	    => 'App\Telegram\Custom\AdminHelpCommand',

		    /* ATTACKS */
		        'attacks'       => 'App\Telegram\Custom\Attacks\AttacksCommand',
	            'claimed'       => 'App\Telegram\Custom\Attacks\ClaimedCommand',
		        'maxcap'        => 'App\Telegram\Custom\Attacks\MaxcapCommand',
	 	        'bcalc'         => 'App\Telegram\Custom\Attacks\BattleCalcCommand',
		        'bg'            => 'App\Telegram\Custom\Attacks\BattleGroupCommand',
		        'topbgs'        => 'App\Telegram\Custom\Attacks\TopBGSCommand',
	            'launch'        => 'App\Telegram\Custom\Attacks\LaunchCommand',
	            'bash'          => 'App\Telegram\Custom\Attacks\BashCommand',
	            'prodticks'     => 'App\Telegram\Custom\Attacks\ProdticksCommand',
	            'sk'            => 'App\Telegram\Custom\Attacks\StructureKillersCommand',

		    /* USELESS VGN COMMANDS */
	            'church'        => 'App\Telegram\Custom\Useless\ChurchCommand',
		        'mackem'        => 'App\Telegram\Custom\Useless\MackemCommand',
	    	    'titpics'       => 'App\Telegram\Custom\Useless\TitpicsCommand',
	    	    'whorules'      => 'App\Telegram\Custom\Useless\WhorulesCommand',
	            'rob'           => 'App\Telegram\Custom\Useless\RobCommand',
	            'whodidthis'    => 'App\Telegram\Custom\Useless\WhodidthisCommand',
	            'rick'          => 'App\Telegram\Custom\Useless\RickCommand',
	    	    'boobs'         => 'App\Telegram\Custom\Useless\BoobsCommand',
	            'butts'         => 'App\Telegram\Custom\Useless\ButtsCommand',
	            'doris'         => 'App\Telegram\Custom\Useless\DorisCommand',
	            'poster'        => 'App\Telegram\Custom\Useless\PosterCommand',
	            'joke'          => 'App\Telegram\Custom\Useless\JokeCommand',
	            'galla'         => 'App\Telegram\Custom\Useless\GallaCommand',
	            'lonney'        => 'App\Telegram\Custom\Useless\LonneyCommand',
	            'judo'          => 'App\Telegram\Custom\Useless\JudoCommand',
	            'tek'           => 'App\Telegram\Custom\Useless\TekCommand',
	            'goose'         => 'App\Telegram\Custom\Useless\GooseCommand',
	            'geriatric'     => 'App\Telegram\Custom\Useless\GeriatricCommand',
	            'pan'           => 'App\Telegram\Custom\Useless\PanCommand',
	            'skhy'          => 'App\Telegram\Custom\Useless\SkhyCommand',
	            'demort'        => 'App\Telegram\Custom\Useless\DemortCommand',
	            'bobzy'         => 'App\Telegram\Custom\Useless\BobzyCommand',
	            'iborg'         => 'App\Telegram\Custom\Useless\iBorgCommand',
	            'nodef'	        => 'App\Telegram\Custom\Useless\NoDefCommand',
				'tits'          => 'App\Telegram\Custom\Useless\TitsCommand',
				'chant'         => 'App\Telegram\Custom\Useless\ChantCommand',
				'dogg'          => 'App\Telegram\Custom\Useless\DoggCommand',
				'sven'          => 'App\Telegram\Custom\Useless\SvenCommand',
				'pies'          => 'App\Telegram\Custom\Useless\PiesCommand',
				'wesseh'        => 'App\Telegram\Custom\Useless\WessehCommand',
				'cba'          	=> 'App\Telegram\Custom\Useless\CbaCommand',
				'aywah'         => 'App\Telegram\Custom\Useless\AywahCommand',
	       

	        ],
	        'alias' => [
	            'links' 	    => 'tools',
	            'p' 	        => 'pscan',
	            'd' 	        => 'dscan',
	            'u' 	        => 'uscan',
	            'n' 	        => 'nscan',
	            'j' 	        => 'jscan',
	            'a' 	        => 'ascan',
				'm' 	        => 'mscan',
	            'winners' 	    => 'bigdicks',
				'losers' 	    => 'smalldicks',
				'scanners' 	    => 'scans',
				'amprapers' 	=> 'funddestroyer',
				'bukkake' 	    => 'spam',
				'distwhores' 	=> 'topdists',
	        ]
    ],

    'admins'  => [
        env('TG_ADMIN_ID', '')
    ],

    /**
     * Request limiter
     */
    'limiter' => [
        'enabled'  => false,
        'interval' => 1,
    ],

    'upload_path'   => '',
    'download_path' => '',
];
