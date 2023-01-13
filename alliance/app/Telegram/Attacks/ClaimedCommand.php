<?php
namespace App\Telegram\Attacks;

use App\Telegram\Custom\BaseCommand;
use App;
use App\Attack;
use App\AttackBooking;
use App\AttackBookingBattlegroup;
use App\AttackTarget;
use App\Planet;
use App\User;
use App\Galaxy;
use App\Alliance;
use Carbon\Carbon;

class ClaimedCommand extends BaseCommand
{
	protected $command = "!claimed";

	public function execute()
	{
		
		if(!$this->isWebUser()) return "Error: you are not registered with the tools. Please do !setnick <your_username>";
		
		$string = explode(" ", $this->text);
		
		// setup vars
		$target = array();
		$landingTick = null;
		
		// process free planets
		$attacks = Attack::where(['is_opened' => 1, 'is_closed' => 0])->get();
		if (count($attacks) > 0)
			$reply = 'Claimed targets: ';
		else
			return 'No open attacks';
		
		foreach ($attacks as $attack):
			$bookings = AttackBooking::where('attack_id', $attack->id)->get();
			foreach ($bookings as $booking):
				if (isset($booking->user_id) && $booking->user_id != NULL):
					
					// get planet
					$attackTarget = AttackTarget::where('attack_id', $attack->id)->where('id', $booking->attack_target_id)->first();

					if ($attackTarget):
						
						// store for later
						$available[$attackTarget->planet_id] = true;
					endif;
				endif;
			endforeach;
		endforeach;
		
		// process available fleets
		foreach ($available as $planetId => $list):
			$planet = Planet::where('id', $planetId)->first();
			$reply .= sprintf('%s:%s:%s ', $planet->x, $planet->y, $planet->z);
		endforeach;
		
		return $reply . PHP_EOL;
;
		
	}
}