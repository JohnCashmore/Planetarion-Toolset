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

class DropCommand extends BaseCommand
{
	protected $command = "!drop";

	public function execute()
	{
		if(!$this->isWebUser()) return "Error: you are not registered with the tools. Please do !setnick <your_username>";
		
		$string = explode(" ", $this->text);
		
		// validate
		if (!isset($string[0]) || !isset($string[1]))
			return 'Usage: !drop <x:y:z> <tick>';
		
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
		$attacks = Attack::where('is_closed', 0)->get();
		foreach ($attacks as $attack):
			$bookings = AttackBooking::where('attack_id', $attack->id)->get();
			foreach ($bookings as $booking):
				$attackTarget = AttackTarget::where('attack_id', $attack->id)->where('id', $booking->attack_target_id)->get();
				foreach ($attackTarget as $target):
					$planet = Planet::find($target->planet_id);
					if ($landingTick == $booking->land_tick && $planet->x == $coords[0] && $planet->y == $coords[1] && $planet->z == $coords[2]):
						$user = User::where('tg_username',  (isset($this->message->from['id'])?$this->message->from['id']:'1552608528'))->first();
						if (isset($booking->user_id) && $booking->user_id == $user->id):
							$reply = sprintf('Target dropped (%s:%s:%s LT%s)', $coords[0], $coords[1], $coords[2], $landingTick);
							if(!count($booking->users)) {
								$booking->user_id = null;
								$booking->save();
							} else {
								
								// Check if user dropping is one of the shared users
								$attackBooking = AttackBooking::whereHas('users', function($q) {
									$q->where('user_id', Auth::user()->id);
								})->first();

								if($attackBooking) {
									$attackBooking->users()->detach(Auth::user()->id);
								}
							}
						endif;
					endif;
				endforeach;
			endforeach;
		endforeach;
		
		return (isset($reply)?$reply:'Could not find that booking');
		
	}
}