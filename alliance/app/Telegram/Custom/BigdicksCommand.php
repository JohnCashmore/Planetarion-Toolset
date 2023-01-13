<?php
namespace App\Telegram\Custom;

use App\Telegram\Custom\BaseCommand;
use App;
use App\Tick;
use App\User;
use App\Alliance;
use App\Planet;
use Carbon\Carbon;

class BigdicksCommand extends BaseCommand
{
	protected $command = "!bigdicks";

	public function execute()
	{
		$string = explode(" ", $this->text);
		$reply = '';
		
		// retrieve data
		$alliance = Alliance::where('name', 'LIKE',  '%Unicorns%')->first();
		$bigdicks = Planet::where('alliance_id', $alliance->id)->orderBy('growth_score', 'desc')->take(5)->get();
		
		// prepare data
		foreach ($bigdicks as $planet):
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