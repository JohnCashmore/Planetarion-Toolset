<?php

namespace App\Services;

use App\AttackBooking;
use App;
use App\Services\Misc\MakeBattleCalc;
use App\BattlegroupTeamups;
use App\BattlegroupFleets;
use App\Attack;
use App\AttackTarget;
use App\User;
use App\Planet;
use App\AttackBookingBattlegroup;
use App\BattlegroupUser;
class MyBookings
{
	protected $id;
	protected $userId;
	protected $attackId;

	public function setId($id)
	{
		$this->id = $id;
		return $this;
	}

	public function setUserId($id)
	{
		$this->userId = $id;
		return $this;
	}

	public function setAttackId($id)
	{
		$this->attackId = $id;
		return $this;
	}
	public function execute()
	{
		
		// process claims and claimants
		$owner = AttackBooking::where('user_id', $this->userId)->get()->pluck('id');
		$sharedUser = AttackBooking::whereHas('users', function($q) {
			$q->where('user_id', $this->userId);
		})->get()->pluck('id');
		
		$battlegroupBookings = array();
		$teamups = BattlegroupTeamups::where('user_id', $this->userId)->get();
		foreach ($teamups as $teamup):
			if ($this->attackId):
				$attack = Attack::where('attack_id', $this->attackId)->first();
				$battlegroupBooking = AttackBookingBattlegroup::where('attack_id', $attack->id)->where('battlegroup_id', $teamup->battlegroup_id)->where('teamup_id', $teamup->teamup_id)->first();
				if ($battlegroupBooking):
					if (isset($battlegroupBooking->booking_id))
						$booking = AttackBooking::where('id', $battlegroupBooking->booking_id)->first();
					if (isset($booking->id))
						$battlegroupBookings[] = $booking->id;
				endif;
			endif;
		endforeach;

		$userBookings = array_merge($owner->toArray(), $sharedUser->toArray(), $battlegroupBookings);

		$bookings = AttackBooking::with([
		  'target',
		  'target.planet',
		  'target.attack',
		  'target.planet',
		  'target.planet.alliance',
		  'target.planet.latestP',
		  'target.planet.latestP.scan',
		  'target.planet.latestD',
		  'target.planet.latestD.scan',
		  'target.planet.latestJ',
		  'target.planet.latestJ.scan',
		  'target.planet.latestU',
		  'target.planet.latestU.u',
		  'target.planet.latestU.u.ship',
		  'target.planet.latestA',
		  'target.planet.latestA.au',
		  'user',
		  'users'
		])->orderBy('land_tick', 'ASC')->whereIn('id', $userBookings);

		$bookings = $bookings->whereHas('target', function($q1) {
			$q1->whereHas('attack', function($q2) {
				$q2->where('is_closed', 0);
				if($this->attackId) {
					$q2->where('attack_id', $this->attackId);
				}
			});
		});
		
		if($this->id) {
			$booking = $bookings->find($this->id);
			$calc = App::make(MakeBattleCalc::class);
			$booking->target->calc = $calc->setX($booking->target->planet->x)
			  ->setY($booking->target->planet->y)
			  ->setZ($booking->target->planet->z)
			  ->execute();
			return $booking;
		} else {
			$bookings = $bookings->get();
			$bookingsUser = $this->calcBookings($bookings);
			
			return $bookings;
		}
	}
	
	function calcBookings ($bookings) {
		
		foreach($bookings as $key => $booking) {
			$calc = App::make(MakeBattleCalc::class);
			$calc->teamups = array();
			$teamups = AttackBookingBattlegroup::where('booking_id', $booking->id)->get();
			
			foreach ($teamups as $teamup):
				$battlegroupTeamups = BattlegroupTeamups::where('battlegroup_id', $teamup->battlegroup_id)->where('teamup_id', $teamup->teamup_id)->get();
				foreach ($battlegroupTeamups as $battlegroupTeamup):
					$fleets = BattlegroupFleets::where('user_id', $battlegroupTeamup->user_id)->where('battlegroup_id', $battlegroupTeamup->battlegroup_id)->where('fleet_id', $battlegroupTeamup->fleet_id)->get();
					$teamupFleets = array();
					foreach ($fleets as $fleet):
						$user = User::where('id', $fleet->user_id)->first();
						$userPlanet = Planet::where('id', $user->planet_id)->first();
						$teamupFleets[] = array('ship_id' => $fleet['ship_id'], 'amount' => $fleet->amount);
					endforeach;
					$calc->teamups[] = array('x' => $userPlanet->x, 'y' => $userPlanet->y, 'z' => $userPlanet->z, 'type' => 'att', 'fleets' => $teamupFleets);
					
	   			
				endforeach;
			 endforeach;
			 
	
		 
			 
			 if (isset($booking->target->planet))
				 $booking->target->calc = $calc->setX($booking->target->planet->x)
					->setY($booking->target->planet->y)
					->setZ($booking->target->planet->z)
					->execute();
			
		}
		return $bookings;
	}
}
