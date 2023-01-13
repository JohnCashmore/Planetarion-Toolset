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
use App\Tick;
use Carbon\Carbon;

class LaunchCommand extends BaseCommand
{
	protected $command = "!launch";

	public function execute()
	{
		if(!$this->isWebUser()) return "Error: you are not registered with the tools. Please do !setnick <your_username>";
		
		$string = explode(" ", $this->text);
		
		// get user
		if (isset($string[0])):
			
			// find the user
			$count = User::where('name', 'LIKE', '%' . $string[0] . '%')->count();
			if ($count == 0)
				return 'Unable to find anyone by that name';
			$user = User::getUser($string[0]);			
		else:
			$user = User::where('tg_username',  (isset($this->message->from['id'])?$this->message->from['id']:'1552608528'))->first();
		endif;
		
		$currentTick = Tick::orderBy('tick', 'DESC')->first();

		// get bookings
		$reply = '';
		$attacks = Attack::where(['is_opened' => 1, 'is_closed' => 0])->get();
		foreach ($attacks as $attack):
			$bookings = AttackBooking::where('attack_id', $attack->id)->get();
			foreach ($bookings as $booking):
				$attackTarget = AttackTarget::where('attack_id', $attack->id)->where('id', $booking->attack_target_id)->get();
				foreach ($attackTarget as $target):
					$planet = Planet::find($target->planet_id);
					if (isset($booking->user_id) && $booking->user_id == $user['id']):
						$time = Carbon::parse(Carbon::now($user['timezone'])->startOfHour());
						$travelTicks = $booking->land_tick - $currentTick->tick;
						$landingTime = date('h:i', strtotime('+' . $travelTicks . ' hours', strtotime($time)));						
						$reply .= sprintf('Booking %s:%s:%s LT%s landing at %s' . PHP_EOL, $planet->x, $planet->y, $planet->z, $booking->land_tick, $landingTime);
					endif;
				endforeach;
			endforeach;
		endforeach;
		//$time = Carbon::parse(Carbon::now($user['timezone'])->startOfHour());
		
		return (strlen($reply) > 0?$reply:'No bookings found');
		
	}
}