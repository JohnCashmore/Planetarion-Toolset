<?php
namespace App\Telegram\Custom;

use App\Telegram\Custom\BaseCommand;
use App;
use App\Planet;
use App\User;
use App\Galaxy;
use App\Alliance;
use Carbon\Carbon;

class ApenisCommand extends BaseCommand
{
	protected $command = "!apenis";

	public function execute()
	{
		$string = explode(" ", $this->text);
		
		// retrieve planet score change
		$alliance = Alliance::where('name', 'Unicorns')->first();
		
		return sprintf('In the last 24 hours %s had a total score gain of %s', $alliance->name,  number_format($alliance->growth_score));
		
	}
}