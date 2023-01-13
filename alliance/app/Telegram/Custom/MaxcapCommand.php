<?php
namespace App\Telegram\Custom;

use App\Telegram\Custom\BaseCommand;
use App;
use App\Tick;
use App\User;
use App\Alliance;
use App\Planet;
use Carbon\Carbon;

class MaxcapCommand extends BaseCommand
{
	protected $command = "!maxcap";

	public function execute()
	{
		$string = explode(" ", $this->text);
		
		// validate
		if (!isset($string[0]))
			return 'Usage: !maxcap <coords> [ticks]';
		
		// get coords
		if (isset($string[3]))
			$coords = array($string[0], $string[1], $string[2]);
		else
			$coords = explode(':', $string[0]);
		
		// number of ticks
		if (isset($string[3]))
			$ticks = $string[3];
		elseif (isset($string[1]))
			$ticks = $string[1];
		else
			$ticks = 1;
		
		// get planet
		$planet = Planet::where('x', $coords[0])->where('y', $coords[1])->where('z', $coords[2])->first();
		
		// check for no data
		if (empty($planet))
			return 'Could not find planet';
		
		// maxcap
		$i = 0;
		$size = $planet->size;
		$retVal = '';
		$total = 0;
		while ($ticks > $i) {
			$i++;
			$total += number_format($size * 0.25, 0); 
			if ($i == 1)
				$retVal .= number_format(($size * 0.25), 0) . ' ';
			else
				$retVal .= number_format(($size * 0.25), 0) . ' (' . $total .')' . ' ';
			$size = $size * 0.75;
		}
		
		if ($ticks == 1)
			return sprintf('Max cap of %s:%s:%s over %s wave is %s', $coords[0], $coords[1], $coords[2], $ticks, $retVal);
		else
			return sprintf('Max cap of %s:%s:%s over %s waves is %s', $coords[0], $coords[1], $coords[2], $ticks, $retVal);

		
	}
}