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

class AffordCommand extends BaseCommand
{
	protected $command = "!afford";

	public function execute()
	{
		$afford = App::make(Afford::class);

		$string = explode(" ", $this->text);
		
		if(!isset($string[0]) && !isset($string[1])) return "usage: !afford <coords> <ship>";

		if(!$this->isWebUser()) return "Error: you are not registered with the tools. Please do !setnick <your_username>";
		
		if (!isset($this->tgTest))
			$user = User::where('tg_username', $this->userId)->first();
		else
			$user = User::where('id', 1) ->first();
		
		preg_match("/^(\d+)[.: ](\d+)[.: ](\d+)$/", $string[0], $planet);

		$afford->setX($planet[1]);
		$afford->setY($planet[2]);
		$afford->setZ($planet[3]);
		$afford->setShip($string[1]);
		$afford->tg = true;
		
		return $afford->execute();
	}
}