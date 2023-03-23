<?php
namespace App\Telegram\Custom;

use App\Telegram\Custom\BaseCommand;
use App\Attack;
use App\Services\Misc\Stop;
use App;

class StopCommand extends BaseCommand
{
	protected $command = "!stop";

	public function execute()
	{
		$stop = App::make(Stop::class);

		$string = explode(" ", $this->text);

		if(!isset($string[0]) || !isset($string[1])) return "usage: !stop <amount> <ship>";
		
		return $stop->setName($string[1])
				->setAmount($string[0])
				->execute();
	}
}