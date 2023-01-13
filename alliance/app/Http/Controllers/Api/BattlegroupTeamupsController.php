<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Battlegroup;
use App\BattlegroupFleets;
use App\BattlegroupTeamups;
use App\User;
use App\Tick;
use App\Fleets;
use App\Ship;
use Auth;
use Carbon\Carbon;

class BattlegroupTeamupsController extends ApiController
{
	public function index($id)
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

	public function lookup ($battlegroupId) {
		
		return BattlegroupTeamups::lookup($battlegroupId);
		
	}
	
	static public function availableFleets ($battlegroupId)
	{
		return BattlegroupTeamups::availableFleets($battlegroupId);
		
	}
	
	static public function teams ($battlegroupId)
	{
		return BattlegroupTeamups::teams($battlegroupId);
		
	}
	
	static public function addTeamupMember ($battlegroupId, $teamupId, $username, $fleetId, $name)
	{
		return BattlegroupTeamups::addTeamupMember($battlegroupId, $teamupId, $username, $fleetId, $name);
		
	}
	
	static public function removeTeamupMember ($battlegroupId, $teamupId, $username, $fleetId)
	{
		return BattlegroupTeamups::removeTeamupMember($battlegroupId, $teamupId, $username, $fleetId);
		
	}
	
	static public function teamupName ($battlegroupId, $teamupId, $name)
	{
		return BattlegroupTeamups::teamupName($battlegroupId, $teamupId, $name);
		
	}
	
	static public function addTeamup ($bookingId, $battlegroupId, $teamupId)
	{
		return BattlegroupTeamups::addTeamup($bookingId, $battlegroupId, $teamupId);
		
	}
	static public function removeTeamup ($bookingId, $battlegroupId, $teamupId)
	{
		return BattlegroupTeamups::removeTeamup($bookingId, $battlegroupId, $teamupId);
		
	}
	
	static public function getTeamupsBookings ($attackId, $userId)
	{
		return BattlegroupTeamups::getTeamupsBookings($attackId, $userId);
		
	}
		
}
?>