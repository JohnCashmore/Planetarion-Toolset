<?php
namespace App\Telegram\Custom;

use App\Telegram\Custom\BaseCommand;
use App\Attack;
use App\Services\Misc\Eff;
use App;

class TestCommand extends BaseCommand
{
	protected $command = ".test";

	public function execute()
	{
		$string = explode(" ", $this->text);

		return 'hello world';
	}
}