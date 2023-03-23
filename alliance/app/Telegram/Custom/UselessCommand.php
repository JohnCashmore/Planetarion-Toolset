<?php
namespace App\Telegram\Custom;

use App\Telegram\Custom\BaseCommand;
use App\Attack;
use App\Services\Misc\Afford;
use App;
use App\Tick;
use App\User;
use App\Planet;
use App\Services\CreateScanRequest;

class UselessCommand extends BaseCommand
{
	protected $command = "!useless";

	public function execute()
	{
		return 'useless';
	}
}