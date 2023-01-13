<?php
namespace App\Telegram\Custom;

use App\Telegram\Custom\BaseCommand;
use App;
use App\Tick;
use App\User;
use App\Alliance;
use App\Planet;
use Carbon\Carbon;

class Top5Command extends BaseCommand
{
	protected $command = "!top5";

	public function execute()
	{
		// setup vars
		$string = explode(" ", $this->text);
		$reply = '';
		
		// find the alliance if provided
		if (isset($string[0])):
			$count = Alliance::where('name', 'LIKE', '%' . addslashes($string[0]) . '%')->count();
			if ($count > 0)
				$alliance = Alliance::where('name', 'LIKE',  '%' . $string[0] . '%')->first();
			else
				return 'Could not find alliance';
		endif;
		
		// grab users alliance if needed
		if (!isset($alliance))
			$alliance = Alliance::where('name', 'LIKE',  '%Unicorns%')->first();
		
		// retrieve and prepare
		$data = Planet::where('alliance_id', $alliance->id)->orderBy('score', 'desc')->take(5)->get();
		foreach ($data as $planet):
			$reply .= sprintf('%s:%s:%s Score: %s (%s) Size: %s (%s) Value: %s (%s) XP: %s (%s)' . PHP_EOL,
			$planet->x, $planet->y, $planet->z, 
				number_format($planet->score), $planet->rank_score, 
				number_format($planet->size), $planet->rank_size, 
				number_format($planet->value), $planet->rank_value, 
				number_format($planet->xp), $planet->rank_xp
			);
		endforeach;
		
		// setup vars
		
		return $reply;

		
	}
}