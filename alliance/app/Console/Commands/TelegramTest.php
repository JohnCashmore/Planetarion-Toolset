<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use Carbon\Carbon;
use App\Planet;
use App\PlanetHistory;
use App\User;
use Twilio;
use App\Setting;
use DB;
use App\Telegram\Custom\WhodidthisCommand;
use App\Telegram\Custom\SmsCommand;
use App\Telegram\Custom\Scans\MilitaryCommand;
use App\Telegram\Commands\DisplaychannelsCommand;
use App\Services\Misc\Transit;
use App\Services\Misc\Stats;
use App\Services\Misc\Stop;
use App\Telegram\Attacks\LaunchCommand;
use App\Telegram\Attacks\BookCommand;
use App\Telegram\Attacks\ClaimedCommand;
use App\Telegram\Attacks\DropCommand;
use App\Telegram\Attacks\FreeCommand;
use App\Services\ScanParser;

class TelegramTest extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'pa:telegramTest';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Run Telegram Code Outside of Telegram';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$cmd = new MilitaryCommand();
		$cmd->text = '1:1:1 ';
		echo $cmd->execute();
//		var_dump($cmd->parse('https://game.planetarion.com/showscan.pl?scan_grp=jinfiptb4g3jsif'));
		
	}
}
