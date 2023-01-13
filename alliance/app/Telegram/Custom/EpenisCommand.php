<?php
namespace App\Telegram\Custom;

use App\Telegram\Custom\BaseCommand;
use App;
use App\Planet;
use App\User;
use Carbon\Carbon;

class EpenisCommand extends BaseCommand
{
	protected $command = "!epenis";

	public function execute()
	{
		$string = explode(" ", $this->text);

		// find the user if provided
		if (isset($string[0])):
			$count = User::where('name', 'LIKE', '%' . addslashes($string[0]) . '%')->count();
			if ($count == 0)
				return 'Unable to find anyone by that name';
			$user = User::getUser(addslashes($string[0]));
		endif;
		
		// get telegram user if needed
		if (!isset($user)):
			$user = User::where('tg_username', $this->message->from['id']);
		endif;
		
		// check for development environment
		if (isset($this->tgTest))
			$user = User::getUserById(1);
		
		// if no user
		if (!isset($user)):
			if (isset($string[0]))
				return 'Could not find anyone by that name';
			return 'Please set your coords using !setplanet <x:y:z>';
		endif;
		
		// retrieve planet score change
		$planet = Planet::find($user['planet_id']);
		
		return sprintf('In the last 24 hours %s:%s:%s had a total score gain of %s', $planet->x, $planet->y, $planet->z,  number_format($planet->growth_score));
		
	}
}