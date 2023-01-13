<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Battlegroup;
use App\BattlegroupUser;
use App\BattlegroupTeamups;
use App\BattlegroupFleets;
use App\AttackBooking;
use App\AttackBookingBattlegroup;
use App\User;
use Auth;

class BattlegroupController extends ApiController
{
	public function index(Request $request)
	{
		$sort = $request->input('sort', '-avg_score');

		$bgs = Battlegroup::with('owner', 'members')->get();

		if($sort) {
			$sortDirection = substr($sort, 0, 1);
			$sort = substr($sort, 1);
		}

		if($sortDirection && $sortDirection == '+') {
			$sorted = $bgs->sortBy(function($bg) use($sort) {
				return $bg[$sort];
			});
		} else {
			$sorted = $bgs->sortByDesc(function($bg) use($sort) {
				return $bg[$sort];
			});
		}
		
		return $sorted->values();
	}


	public function store(Request $request)
	{
		if (strlen($request->input('name')) > 9)
			return 'Battlegroup name too long';
		
		$bg = Battlegroup::create([
			'name' => $request->input('name'),
			'creator_id' => Auth::user()->id
		]);

		$bg->members()->sync([Auth::user()->id => ['is_pending' => 0]], false);
	}

	public function show($id)
	{
		$bg = Battlegroup::with(['members', 'membersPending', 'members.planet','membersPending.planet'])->find($id);

		$members = $bg->members->keyBy('id')->toArray();
		$membersPending = $bg->membersPending->keyBy('id')->toArray();

		$users = User::whereNotNull('planet_id')->orderBy('name')->get()->keyBy('id')->toArray();

		$new = array_diff_key($users, $members);
		$new = array_diff_key($new, $membersPending);

		$bg->non_members = collect($new)->values();

		return $bg;
	}

	public function destroy($id)
	{
		
		Battlegroup::find($id)->delete();
		$users = BattlegroupUsers::where('battlegroup_id', $id)->get();
		$teamups = BattlegroupTeamups::where('battlegroup_id', $id)->get();
		$fleets = BattlegroupFleets::where('battlegroup_id', $id)->get();
		
		foreach ($users as $user):
			$user->delete();
		endforeach;
		foreach ($teamups as $teamup):
			$teamup->delete();
		endforeach;
		foreach ($fleets as $fleet):
			$fleet->delete();
		endforeach;
		
		return $this->index();
	}

	public function join($id)
	{
		$bg = Battlegroup::find($id);

		$bg->members()->sync([Auth::user()->id => ['is_pending' => 1]], false);
	}
	
	function fetchByUser($userId)
	{
		$battlegroupUsers = BattlegroupUser::where('user_id', $userId)->get();
		
		foreach ($battlegroupUsers as $user):
			$battlegroup = Battlegroup::where('id', $user->battlegroup_id)->first();
			$teamups = BattlegroupTeamups::where('battlegroup_id', $user->battlegroup_id)->get();
		
			foreach ($teamups as $teamup):
				$battlegroupTeamups[$battlegroup->id . '-' . $teamup->teamup_id] = array(
					'battlegroup_id' => $battlegroup->id,
					'name' => $teamup->name,
					'teamup_id' => $teamup->teamup_id
				);
			endforeach;
		endforeach;
		
		// store for later
		$battlegroup->teamups = $battlegroupTeamups;
		
		return $battlegroup;
	}
	

	
	function fetchByUserForBooking($userId, $bookingId)
	{
		// fetch by user
		$battlegroupsForUser = BattlegroupUser::where('user_id', $userId)->get();
		
		// setup data
		$battlegroupTeamups = array();
		$battlegroupClaims = array();
		
		// itterate battlegroups and grab booking data
		foreach ($battlegroupsForUser as $battlegroupForUser):
			$battlegroupByBattlegroupId = $this->fetchByBattlegroupIdForBooking($battlegroupForUser->battlegroup_id, $bookingId);
			if (!empty($battlegroupByBattlegroupId->teamups))
				$battlegroupTeamups = array_merge($battlegroupTeamups, $battlegroupByBattlegroupId->teamups);
			if (!empty($battlegroupByBattlegroupId->claims))
				$battlegroupClaims = array_merge($battlegroupClaims, $battlegroupByBattlegroupId->claims);
		endforeach;
		
		// store for return
		$battlegroup = $battlegroupsForUser[0];
		$battlegroup->teamups = $battlegroupTeamups;
		$battlegroup->claims = $battlegroupClaims;
		
		return $battlegroup;
	}
	
	function fetchByBattlegroupIdForBooking($battlegroupId, $bookingId)
	{
		$battlegroup = Battlegroup::where('id', $battlegroupId)->first();
		$teamups = BattlegroupTeamups::where('battlegroup_id', $battlegroupId)->get();
	
		if (empty($battlegroup)):
			return sprintf('Could not find battlegroup %s', $battlegroupId);
		endif;
	
		$battlegroupTeamups = array();
		$battlegroupClaims = array();
	
		foreach ($teamups as $teamup):
			
			$teamupClaims = AttackBookingBattlegroup::where('battlegroup_id', $battlegroupId)->where('teamup_id', $teamup->teamup_id)->where('booking_id', $bookingId)->first();
		
			if ($teamupClaims == NULL):
				$battlegroupTeamups[$teamup->teamup_id] = array(
					'battlegroup_id' => $battlegroup->id,
					'name' => $teamup->name,
					'teamup_id' => $teamup->teamup_id
				);
			else:
				$battlegroupClaims[$teamup->teamup_id] = array(
					'battlegroup_id' => $battlegroupId,
					'name' => $teamup->name,
					'teamup_id' => $teamup->teamup_id,
					'battlegroup_name' => $battlegroup->name
				);
			endif;
		endforeach;

		$battlegroup->teamups = $battlegroupTeamups;
	
		$battlegroup->claims = $battlegroupClaims;
	
		return $battlegroup;
	}
	
	function fetchByUserForAttack($userId, $attackId)
	{
		// fetch by user
		$battlegroupUser = BattlegroupUser::where('user_id', $userId)->first();
		
		// handle no battlegroup
		if (empty($battlegroupUser)):
			return 'no battlegroup';
		endif;

		$battlegroup = Battlegroup::where('id', $battlegroupUser->battlegroup_id)->first();
		
		// itterate battlegroups and grab booking data
		// get bookings
		$bookings = $this->fetchByBattlegroupIdForAttack($battlegroupUser->battlegroup_id, $attackId);
		
		// store for return
		$battlegroup->bookings = $bookings;
		
		return $battlegroup;

	}
	
	function fetchByBattlegroupIdForAttack($battlegroupId, $attackId)
	{
		
		// fetch battlegroup
		$battlegroup = Battlegroup::where('id', $battlegroupId)->first();	
		
		// check battlegroup exists
		if (empty($battlegroup)):
			return sprintf('Could not find battlegroup %s', $battlegroupId);
		endif;
	
		// setup vars
		$battlegroupBookings = array();
		
		// now fetch bookings
		$bookings = AttackBooking::where('attack_id', $attackId)->get();
		
		// loop bookings
		foreach ($bookings as $booking):
		
			// get teamups
			$teamups = BattlegroupTeamups::where('battlegroup_id', $battlegroupId)->get();
			
			// loop teamups
			foreach ($teamups as $teamup):
				
				// claimed or not
				$teamupClaims = AttackBookingBattlegroup::where('battlegroup_id', $battlegroupId)->where('teamup_id', $teamup->teamup_id)->where('booking_id', $booking->id)->first();
				
				// store data
				$battlegroupBookings[$booking->id][$teamup->teamup_id] = array(
					'name' => $teamup->name,
					'teamup_id' => $teamup->teamup_id,
					'attack_target_id' => $booking->attack_target_id,
					'booking_id' => $booking->id,
					'claimed' => (!empty($teamupClaims)?true:false)
				);
				if (!empty($teamupClaims))
					$battlegroupBookings['booking_' . $booking->id] = true;
			endforeach;
		endforeach;
		

		return $battlegroupBookings;
	}

	
}
