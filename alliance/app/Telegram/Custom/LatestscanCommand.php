<?php
namespace App\Telegram\Custom;

use App\Telegram\Custom\BaseCommand;
use App\Attack;
use App\Services\Misc\Eff;
use App;
use App\Planet;
use App\Scan;

class LatestscanCommand extends BaseCommand
{
	protected $command = "!latestscan";

	public function execute()
	{
		$string = explode(' ', $this->text);
		$a = 0;
		$reply = '';
		while (isset($string[1][$a]) && $string[1][$a] != NULL):
			
			// coords
			$coords = explode(':', $string[0]);
			
			// retrieve scan
			switch ($string[1][$a]):
				case 'p':
				case 'd':
				case 'u':
				case 'a':
				
					// fetch planet
					$planet = Planet::where('x', $coords[0])->where('y', $coords[1])->where('z', $coords[2])->first();
					
					// fetch scan id
					$method = 'latest_' . $string[1][$a];
					$planetScanId = $planet->$method;
					
					// fetch scan
					$scan = Scan::where('id', $planetScanId)->first();
					
					if (isset($scan) && !empty($scan))
						$reply .= sprintf('%s: https://game.planetarion.com/showscan.pl?scan_id=%s' . PHP_EOL, strtoupper($string[1][$a]), $scan->pa_id);
					else
						$reply .= sprintf('Unable to find %s scan for %d:%d:%d' . PHP_EOL, strtoupper($string[1][$a]), $coords[0], $coords[1], $coords[2]);
				break;
				default:
				return 'Usage: <x:y:z> <pdua>';
			endswitch;
			$a++;
		endwhile;
		
		return $reply;
	}
}