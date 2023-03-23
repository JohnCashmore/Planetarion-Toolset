<?php
namespace App\Telegram\Custom\Attacks;

use App\Telegram\Custom\BaseCommand;
use App\User;
use App\AttackBooking;
use App\Attack;
use App\Planet;
use App\Tick;
use App;

class ClaimedCommand extends BaseCommand
{
    protected $command = "!claimed";

    public function execute()
    {
    //if(!$this->isChannelAllowed()) return "You can only use this command in the channel linked to webby.";

    $currentTick = Tick::orderBy('tick', 'DESC')->first();
    $tick = $currentTick->tick;

    $user = User::with('bookings')->where('tg_username', $this->message->from['id'])->first();
    $bookings = $user->bookings;

    if(count($bookings)) {
        $targets = "";
	$total = 0;
        foreach($bookings as $booking) {
            $claim = AttackBooking::with('target')->where('attack_target_id', $booking->attack_target_id)->first();
            $planet = Planet::where('id', $claim->target->planet_id)->first();
	    $status = Attack::where('id', $booking->attack_id)->first();

	    if($status->is_closed == 1) { continue; }
	    ++$total;

	    if(!is_null($booking->notes)) {
                $targets .= sprintf("Target: %s:%s:%s (%s) - LT: %s - Notes: %s\n", $planet->x, $planet->y, $planet->z, $planet->size, $booking->land_tick, $booking->notes);
            }
	    else {
		$targets .= sprintf("Target: %s:%s:%s (%s) - LT: %s\n", $planet->x, $planet->y, $planet->z, $planet->size, $booking->land_tick);
	    }
	}
	if($total == 0) { return "No targets claimed?"; }
    }
    else {
        return "No targets claimed?";
    }

    return $targets;
    }
}