<?php
namespace App\Telegram\Custom;

use App\Telegram\Custom\BaseCommand;
use App;
use App\Tick;
use App\User;
use Carbon\Carbon;

class TickCommand extends BaseCommand
{
	protected $command = "!tick";

	public function execute()
	{
		$hasTz = false;
		$tick = $this->text;

		$currentTick = Tick::orderBy('tick', 'DESC')->first();

		if(!$currentTick) return "Ticks haven't started yet!";

		if(!$tick) {
			return sprintf("It is tick %d", $currentTick->tick);
		}

		if(!is_int(intval($tick))) return "Tick must be a number";

		$user = User::with('planet')->where('tg_username', $this->userId)->first();

		// If the user has linked their TG user to web, we can try to get their timezone settings and show the time of the tick requested
		if($user) {
			if(isset($user->timezone)) {
				$hasTz = true;
				$currentTime = Carbon::parse(Carbon::now($user->timezone));
			}
		}

		if(!isset($currentTime)) $currentTime = Carbon::now();

		$ticksUntil = $tick - $currentTick->tick;
		$timeUntil = $currentTime->addHours($ticksUntil)->startOfHour();

		if($currentTime->isPast()) {
			$response = sprintf("Tick %d happened %d ticks ago at %s", $tick, abs($ticksUntil), $timeUntil->format('ga'));
		} else {
			$response = sprintf("Tick %d will happen in %d ticks at %s", $tick, abs($ticksUntil), $timeUntil->format('ga'));
		}

		if($hasTz) $response = $response . " [" . $timeUntil->timezoneName . "]";

		return $response;
		
	}
}