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
