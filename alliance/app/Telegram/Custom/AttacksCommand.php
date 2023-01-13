<?php
namespace App\Telegram\Custom;

use App\Telegram\Custom\BaseCommand;
use App\Attack;
use App;

class AttacksCommand extends BaseCommand
{
	protected $command = "!attacks";

	public function execute()
	{
		if(!$this->isChannelAllowed()) return "You can not use that command in this channel";

		// setup vars
		$retVal = '';

		// open attacks
		$attacks = Attack::where([
			'is_closed' => 0,
			'is_opened' => 1
		])->get();

		if(count($attacks)){
			$urls = [];

			foreach($attacks as $attack) {
				$urls[] = App::make('url')->to('/') . '/#/attacks/' . $attack->attack_id;
			}

			$retVal .= sprintf("Open attacks: %s", implode(", ", $urls));
		}
		
		// open attacks
		$attacks = Attack::where([
			'is_closed' => 0,
			'is_opened' => 0,
			'is_prereleased' => 1
		])->get();

		if(count($attacks)){
			$urls = [];

			foreach($attacks as $attack) {
				$urls[] = App::make('url')->to('/') . '/#/attacks/' . $attack->attack_id;
			}

			$retVal .= sprintf("Prereleased attacks: %s", implode(", ", $urls));
		}
		
		if (isset($retVal) && strlen($retVal) > 0)
			return $retVal;
		else
			return "There are no open attacks";
	}
}