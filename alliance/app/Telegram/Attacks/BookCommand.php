<?php
namespace App\Telegram\Attacks;

use App\Telegram\Custom\BaseCommand;
use App;
use App\Attack;
use App\AttackBooking;
use App\AttackTarget;
use App\Planet;
use App\User;
use App\Galaxy;
use App\Alliance;
use Carbon\Carbon;

class BookCommand extends BaseCommand
{
	protected $command = "!book";

	public function execute()
	{
		
		if(!$this->isWebUser()) return "Error: you are not registered with the tools. Please do !setnick <your_username>";
		
		$string = explode(" ", $this->text);
		
		// validate
		if (!isset($string[0]) || !isset($string[1]))
			return 'Usage: !book <x:y:z> <land tick>';
		
		// setup vars
		if (isset($string[3])):
			$coords = array($string[0], $string[1], $string[2]);
			$landingTick = $string[3];
		else:
			$coords = explode(':', $string[0]);
			$landingTick = $string[1];
		endif;

		// retrieve planet
		$planet = Planet::where('x', $coords[0])->where('y', $coords[1])->where('z', $coords[2])->first();
		
		// check planet exists
		if (empty($planet))
			return 'Could not find coords';
		
		// check planet is available
		$attacks = Attack::where(['is_opened' => 1, 'is_closed' => 0])->get();
		foreach ($attacks as $attack):
			$bookings = AttackBooking::where('attack_id', $attack->id)->where('user_id', NULL)->get();
			foreach ($bookings as $booking):
				$attackTarget = AttackTarget::where('attack_id', $attack->id)->where('id', $booking->attack_target_id)->get();
				foreach ($attackTarget as $target):
					$planet = Planet::find($target->planet_id);
					if ($landingTick == $booking->land_tick && $planet->x == $coords[0] && $planet->y == $coords[1] && $planet->z == $coords[2]):
						if ($booking->user_id == NULL):
							$reply = sprintf('Target booked (%s:%s:%s LT%s)', $coords[0], $coords[1], $coords[2], $landingTick);
							$user = User::where('tg_username', (isset($this->message->from['id'])?$this->message->from['id']:1552608528))->first();
							$booking->user_id = $user['id'];
							$booking->save();
						endif;
					endif;
				endforeach;
			endforeach;
		endforeach;
		
		return (isset($reply)?$reply:'Target not available');
		
	}
}