<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Battlegroup;
use App\BattlegroupFleets;
use App\User;
use App\Tick;
use App\Fleets;
use App\Ship;
use Auth;
use Carbon\Carbon;

class BattlegroupFleetsController extends ApiController
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

	public function store($id, Request $request)
	{
		$user = $request->input('user');

		$bg = Battlegroup::find($id);

		$bg->members()->sync([$user => ['is_pending' => 0]], false);

		return $this->index($id);
	}

	public function accept($bgId, $userId)
	{
		$bg = Battlegroup::find($bgId);

		$bg->members()->sync([$userId => ['is_pending' => 0]], false);

		return $this->index($bgId);
	}

	public function decline($bgId, $userId)
	{
		$bg = Battlegroup::find($bgId);

		$bg->members()->detach([$userId]);

		return $this->index($bgId);
	}

	public function destroy($bgId, $userId)
	{
		$bg = Battlegroup::find($bgId);

		$bg->members()->detach([$userId]);

		return $this->index($bgId);
	}
	
	/**
	 * Get Fleets For User
	 *
	 * @return void
	 * @author Craig Fairhurst
	 */
	public function fleets($battlegroupId, $userId)
	{
		return BattlegroupFleets::fleets($battlegroupId, $userId);
	}
	
	/**
	 * Add a ship to fleet
	 *
	 * @return boolean
	 * @author Craig Fairhurst
	 */
	function addShip ($battlegroupId, $userId, $fleetId, $shipId, $shipAmount)
	{
		
		return BattlegroupFleets::addShip($battlegroupId, $userId, $fleetId, $shipId, $shipAmount);;
		
	}
	
	/**
	 * Edit a ship in fleet
	 *
	 * @return boolean
	 * @author Craig Fairhurst
	 */
	function editShip ($battlegroupId, $userId, $fleetId, $shipId, $shipAmount)
	{
		
		return BattlegroupFleets::editShip($battlegroupId, $userId, $fleetId, $shipId, $shipAmount);
		
	}
	
	/**
	 * Edit a ship in fleet
	 *
	 * @return boolean
	 * @author Craig Fairhurst
	 */
	function deleteShip ($battlegroupId, $userId, $fleetId, $shipId)
	{
		return BattleGroupFleets::deleteShip($battlegroupId, $userId, $fleetId, $shipId);
		
	}
	

}
?>