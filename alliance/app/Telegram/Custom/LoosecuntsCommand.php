<?php
namespace App\Telegram\Custom;

use App\Telegram\Custom\BaseCommand;
use App;
use App\Tick;
use App\User;
use App\Alliance;
use App\Planet;
use Carbon\Carbon;

class LoosecuntsCommand extends BaseCommand
{
	protected $command = "!loosecunts";

	public function execute()
	{
		$string = explode(" ", $this->text);
		$reply = '';
		
		// retrieve data
		$alliance = Alliance::where('name', 'LIKE',  '%Unicorns%')->first();
		$loosecunts = Planet::where('alliance_id', $alliance->id)->orderBy('growth_score', 'asc')->take(5)->get();
		
		// prepare data
		foreach ($loosecunts as $planet):
			$user = User::where('planet_id', $planet->id)->first();
			if (empty($user))
				$username = 'unknown';
			else
				$username = $user->name;
			
			$reply .= sprintf('%s %s:%s:%s Score: %s (%s) Size: %s (%s) Value: %s (%s) XP: %s (%s)' . PHP_EOL,
				$username, 
				$planet->x, $planet->y, $planet->z, 
				number_format($planet->score), $planet->rank_score, 
				number_format($planet->size), $planet->rank_size, 
				number_format($planet->value), $planet->rank_value, 
				number_format($planet->xp), $planet->rank_xp
			);
		endforeach;
		
		return $reply;

		
	}
}