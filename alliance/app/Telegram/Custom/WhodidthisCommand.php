<?php
namespace App\Telegram\Custom;

use App\Telegram\Custom\BaseCommand;
use App\Attack;
use App\Services\Misc\Eff;
use App;

class WhodidthisCommand extends BaseCommand
{
	protected $command = "!whodidthis";

	public function execute()
	{

		return "The 2022 version of the webby were brought to you by Venox, Nitin, Sven & Johnny Aywah and macen";

	}
}
