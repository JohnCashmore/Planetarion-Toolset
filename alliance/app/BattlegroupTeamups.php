<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Battlegroup;
use App\BattlegroupFleets;
use App\User;
use App\Tick;
use App\Fleets;
use App\Ship;
use Auth;
use Carbon\Carbon;

class BattlegroupTeamups extends Model {
	

	/**
	 * Get Teamups For Battlegroup
	 *
	 * @return void
	 * @author Craig Fairhurst
	 */
	static public function lookup($battlegroupId)
	{
		// get teamups
		$teamups = BattlegroupTeamups::where('battlegroup_id', $battlegroupId)->get();
		
		// get fleets
		$fleets = BattlegroupFleets::where('battlegroup_id', $battlegroupId)->get();
		
		// order fleets
		$fleetInfo[][] = array();
		$fleetLine[][] = array();
		foreach ($fleets as $fleet):
			$ship = Ship::where('id', $fleet['ship_id'])->first();
			$fleetInfo[$fleet->user_id . '-' . $fleet->fleet_id][] = array(
				'fleet' => $fleet, 
				'ship' => $ship
			);
			if (!isset($fleetLine[$fleet->user_id][$fleet->fleet_id]))
				$fleetLine[$fleet->user_id][$fleet->fleet_id] = '';
			$fleetLine[$fleet->user_id][$fleet->fleet_id] .= sprintf('%s %s, ', $ship['name'], $fleet['amount']);
		endforeach;
		
		// order teamups
		$retVal = array();
		foreach ($teamups as $teamup):
			
			// get user
			$user = User::where('id', $teamup->user_id)->first();
			
			// get planet
			$planet = Planet::where('id', $user->planet_id)->first();
			
			// fleet info
			if (isset($fleetLine[$user->id][$teamup->fleet_id]))
				$fleetLineString = substr($fleetLine[$user->id][$teamup->fleet_id], 0, strlen($fleetLine[$user->id][$teamup->fleet_id]) - 2);
			else
				$fleetLineString = '';
			
			// save for processing
			$retVal[$teamup->teamup_id]['fleets'][] = array(
				'user' => $user,
				'planet' => $planet,
				'battlegroup' => Battlegroup::where('id', $teamup->battlegroup_id)->first(),
				'teamup_id' => $teamup->teamup_id,
				'fleet_id' => $teamup->fleet_id,
				'fleets' => (isset($fleetInfo[$user->id][$teamup->fleet_id])?$fleetInfo[$user->id][$teamup->fleet_id]:null),
				'fleetLine' => $fleetLineString,
				'name' => $teamup->name
			);
			$retVal[$teamup->teamup_id]['name'] = $teamup->name;
		endforeach;
		
		
		return json_encode($retVal);
	}
	
	/**
	 * Get Teams For Battlegroup
	 *
	 * @return json
	 * @author Craig Fairhurst
	 */
	static public function teams($battlegroupId)
	{
		// get teams
		$teams = BattlegroupTeamups::where('battlegroup_id', $battlegroupId)->get();

		// loop over teams
		$retVal = array();
		foreach ($teams as $team):
			$retVal[$team->teamup_id] = $team;
		endforeach;
		
		// return data
		return json_encode($retVal);
	}
	
	
	/**
	 * Get Available Fleets For Battlegroup
	 *
	 * @return void
	 * @author Craig Fairhurst
	 */
	static public function availableFleets ($battlegroupId)
	{
		// get fleets
		$fleets = BattlegroupFleets::where('battlegroup_id', $battlegroupId)->get();
		
		// get fleet line
		// order fleets
		foreach ($fleets as $fleet):
			$ship = Ship::where('id', $fleet['ship_id'])->first();
			
			if (!isset($fleetLine[$fleet->user_id][$fleet->fleet_id]))
				$fleetLine[$fleet->user_id][$fleet->fleet_id] = '';
			$fleetLine[$fleet->user_id][$fleet->fleet_id] .= sprintf('%s %s, ', $ship['name'], $fleet['amount']);
		endforeach;
		
		// process fleets
		$retVal = array();
		foreach ($fleets as $fleet):
			
			// get user
			$user = User::where('id', $fleet->user_id)->first();
			
			// get planet
			$planet = Planet::where('id', $user->planet_id)->first();
			
			// check for fleet
			$fleetCheck = BattlegroupTeamups::where('battlegroup_id', $battlegroupId)->where('fleet_id', $fleet->fleet_id)->where('user_id', $user->id)->first();
			
			// save fleet if not found
			if (!isset($fleetCheck->fleet_id)):
				$retVal[$fleet->user_id . '-' . $fleet->fleet_id] = array(
					'user' => $user,
					'fleet_id' => $fleet->fleet_id,
					'fleet_line' => substr($fleetLine[$fleet->user_id][$fleet->fleet_id], 0, strlen($fleetLine[$fleet->user_id][$fleet->fleet_id]) - 2),
					'planet' => $planet
				);
				unset($fleetCheck);
			endif;
		endforeach;
		
				
		return json_encode($retVal);
	}
	
	/**
	 * Add fleet to teamup
	 *
	 * @return json
	 * @author Craig Fairhurst
	 */
	static public function addTeamupMember ($battlegroupId, $teamupId, $username, $fleetId, $name) {
		
		// get user
		$user = User::where('name', $username)->first();
		
		// get team count
		$teamCount = count((array) json_decode(BattlegroupTeamups::teams($battlegroupId)));
		
		// set name
		if ($name == 'New Team')
			$name = 'Team ' . ($teamCount + 1);
		
		$insertTeamup = array(
			'battlegroup_id' => $battlegroupId,
			'user_id' => $user->id,
			'teamup_id' => (is_numeric($teamupId)?$teamupId:$teamCount + 1),
			'fleet_id' => $fleetId,
			'name' => $name
		);
		$retVal = BattlegroupTeamups::insert($insertTeamup);
		return json_encode($retVal);
	}
	
	/**
	 * Remove teamup member
	 *
	 * @return json
	 * @author Craig Fairhurst
	 */
	static public function removeTeamupMember ($battlegroupId, $teamupId, $username, $fleetId) {
		
		$user = User::where('name', $username)->first();
		
		$teamup = BattlegroupTeamups::where('battlegroup_id', $battlegroupId)->where('teamup_id', $teamupId)->where('user_id', $user->id)->where('fleet_id', $fleetId)->first();
		$teamup->delete();
		return json_encode(true);
	}
	
	static public function teamupName ($battlegroupId, $teamupId, $name) {
		
		// sanitise data
		$name = addslashes($name);
		
		// get teamups
		$teamups = BattlegroupTeamups::where('battlegroup_id', $battlegroupId)->where('teamup_id', $teamupId)->get();
		
		// change name
		foreach ($teamups as $teamup):
			$teamup->name = $name;
			$teamup->save();
		endforeach;
		
	}
	
	/**
	 * Add teamup to booking
	 *
	 * @return json
	 * @author Craig Fairhurst
	 */
	static public function addTeamup ($bookingId, $battlegroupId, $teamupId) {
		
	
		// get battlegroup
		$battlegroup = Battlegroup::where('id', $battlegroupId)->first();
		
		// get booking
		$booking = AttackBooking::where('id', $bookingId)->first();
		
		// teamup booking
		$insertBookingTeamup = array(
			'booking_id' => $bookingId,
			'battlegroup_id' => $battlegroupId,
			'teamup_id' => $teamupId,
			'attack_id' => $booking->attack_id
		);

		// add to table
		$retVal = AttackBookingBattlegroup::insert($insertBookingTeamup);

		
		return json_encode($retVal);
	}
	
	/**
	 * Remove teamup from booking
	 *
	 * @return json
	 * @author Craig Fairhurst
	 */
	static public function removeTeamup ($bookingId, $battlegroupId, $teamupId) {
		
	
		$bookings = AttackBookingBattlegroup::where('battlegroup_id', $battlegroupId)->where('teamup_id', $teamupId)->get();
		
		if ($bookings):
			foreach ($bookings as $booking):
				$booking->delete();
			endforeach;
		endif;
		return json_encode(true);
	}
	
	/**
	 * Retrieve teamups for a booking
	 *
	 * @return void
	 * @author Craig Fairhurst
	 */
	static public function getTeamupsBookings ($attackId, $userId)
	{

		// fetch data
		$teamups = BattlegroupTeamups::where('user_id', $userId)->get();
		$user = User::where('id', $userId)->first();
		$planet = Planet::where('id', $user->planet_id)->first();
		
		$attackBooking = array();
		foreach ($teamups as $teamup):
			
			// more data based on teamup
			$battlegroupBooking = AttackBookingBattlegroup::where('attack_id', $attackId)->where('battlegroup_id', $teamup['battlegroup_id'])->where('teamup_id', $teamup['teamup_id'])->first();
			
			if (isset($battlegroupBooking->booking_id)):
				$booking = AttackBooking::where('id', $battlegroupBooking->booking_id)->first();
				$target = AttackTarget::where('id', $attackId)->first();
				$target->planet = Planet::where('id', $target->planet_id)->first();
				$target->attack = Attack::where('id', $attackId);
				$target->attack->id = $attackId;
				$target->planet_id = $booking->attack_target_id;

				// save for return
				$attackBooking[] = array(
					'attack_target_id' => $booking->attack_target_id,
					'battle_calc' => $booking->battle_calc,
					'id' => $booking->id,
					'land_tick' => $booking->land_tick,
					'notes' => $booking->notes,
					'battlegroup' => $battlegroupBooking,
					'booking' => $booking,
					'user' => $user,
					'users' => array($user),
					'planet' => $planet,
					'target' => $target
				);
			endif;
		endforeach;
		
		return json_encode($attackBooking);
	}
}