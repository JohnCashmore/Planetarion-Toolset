<?php
namespace App\Telegram\Custom;

use App\Telegram\Custom\BaseCommand;
use App;
use App\Tick;
use App\User;
use App\Alliance;
use App\Planet;
use Carbon\Carbon;

class Last24Command extends BaseCommand
{
	protected $command = "!last24";

	public function execute()
	{
		$string = explode(" ", $this->text);
		
		// find the user if provided
		if (isset($string[0])):
			$count = User::where('name', 'LIKE', '%' . addslashes($string[0]) . '%')->count();
			if ($count > 0):
				$user = User::getUser(addslashes($string[0]));
			else:
				$count = Alliance::where('name', 'LIKE', '%' . addslashes($string[0]) . '%')->count();
				if ($count > 0):
					$alliance = Alliance::where('name', 'LIKE',  '%' . $string[0] . '%')->first();
				else:
					
					// find by planet
					preg_match("/^(\d+)[.: ](\d+)[.: ](\d+)$/", $this->text, $planet);

					$psearch = ($planet) ? $planet : false;

					if($psearch) {
						$x = $psearch[1];
						$y = $psearch[2];
						$z = $psearch[3];

						$planet = Planet::with('alliance')->where([
						  'x' => $x,
						  'y' => $y,
						  'z' => $z
						])->first();

						if(!$planet) {
							return sprintf("No planet found at %d:%d:%d", $x, $y, $z);
						}
					} else {
						return 'Could not find user/alliance';
					}
				endif;	
			endif;
		else:
			$user = User::where('tg_username', $this->message->from['id'])->first();
			
			// if no user
			if (!isset($user)):
				return 'Please set your coords using !setplanet <x:y:z>';
			endif;
			
		endif;
		
		
		// get data and set output 
		if (isset($user))
			$planet = Planet::find($user['planet_id']);

		$reply = '';
		if (isset($alliance)):
			$reply .= sprintf(
				'Score: %s (%s%s)' . PHP_EOL, 
					number_format($alliance->growth_score), 
					($alliance->growth_rank_score > 0?'+':''), 
					$alliance->growth_rank_score
				);
			$reply .= sprintf(
				'Roids: %s (%s%s)' . PHP_EOL, 
					number_format($alliance->growth_size), 
					($alliance->growth_rank_size > 0?'+':''), 
					$alliance->growth_rank_size
				);
			$reply .= sprintf(
				'Value: %s (%s%s)' . PHP_EOL, 
					number_format($alliance->growth_value), 
					($alliance->growth_rank_value > 0?'+':''), 
					$alliance->growth_rank_value
				);
			$reply .= sprintf(
				'XP: %s (%s%s)' . PHP_EOL, 
				number_format($alliance->growth_xpe), 
				($alliance->growth_rank_xp > 0?'+':''), 
				$alliance->growth_rank_xp
			);
		else:
			$reply .= sprintf(
				'Score: %s (%s%s)' . PHP_EOL, 
					number_format($planet->growth_score), 
					($planet->growth_rank_score > 0?'+':''), 
					$planet->growth_rank_score
				);
			$reply .= sprintf(
				'Roids: %s (%s%s)' . PHP_EOL, 
					number_format($planet->growth_size), 
					($planet->growth_rank_size > 0?'+':''), 
					$planet->growth_rank_size
				);
			$reply .= sprintf(
				'Value: %s (%s%s)' . PHP_EOL, 
					number_format($planet->growth_value), 
					($planet->growth_rank_value > 0?'+':''), 
					$planet->growth_rank_value
				);
			$reply .= sprintf(
				'XP: %s (%s%s)' . PHP_EOL, 
				number_format($planet->growth_xp), 
				($planet->growth_rank_xp > 0?'+':''), 
				$planet->growth_rank_xp
			);
		endif;
		
		return $reply;

		
	}
}