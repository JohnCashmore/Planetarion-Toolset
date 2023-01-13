<?php
namespace App\Telegram\Custom;

use App\Telegram\Custom\BaseCommand;
use App\Attack;
use App\Services\ScanParser;
use App;
use App\Planet;
use App\Scan;

class ParseCommand extends BaseCommand
{
	protected $command = "!parse";

	public function execute()
	{
		// input
		$string = explode(' ', $this->text);
		
		// parse scans
		$scanParser = new ScanParser();
		$return = $scanParser->parse($this->text);
		
		// error handling
		if (isset($return->original['error']))
			return $return->original['error'];
			
		// output
		$reply = '';
		foreach ($return as $line):
			$reply .= $line . PHP_EOL;
		endforeach;
		
		// return reply
		return $reply;
	}
}