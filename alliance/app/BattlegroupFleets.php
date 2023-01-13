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
use App\Scan;
use App\Planet;
use Auth;
use Carbon\Carbon;

class BattlegroupFleets extends Model {
	
	/**
	 * Retrieve fleets by user ID
	 *
	 * @return array of objects
	 * @author Craig Fairhurst
	 */
	function getByUserId($userId)
	{
		return $this->hasMany(User::class, 'id', 'user_id');
	}
	
	/**
	 * Get Fleets For User
	 *
	 * @return void
	 * @author Craig Fairhurst
	 */
	static public function fleets($battlegroupId, $userId)
	{
		// get fleets
		$fleets = BattlegroupFleets::where('battlegroup_id', $battlegroupId)->where('user_id', $userId)->get();
		
		// fetch battlegroup
		$battlegroup = Battlegroup::find($battlegroupId);
		
		// fetch user
		$user = User::find($userId);
		
		// planet 
		$planet = Planet::find($user->planet_id);
		
		switch ($planet->race):
			case 'Ter':
				$race = 'Terran';
				break;
			case 'Cat':
				$race = 'Cathaar';
				break;
			case 'Xan':
				$race = 'Xandathrii';
				break;
			case 'Zik':
				$race = 'Zikonian';
				break;
			case 'Etd':
				$race = 'Eitraides';
				break;
			default:
			return 'Unknown race ' . $planet->race;
		endswitch;
		
		// set generic information
		$retVal['name'] = $battlegroup->name;
		$retVal['username'] = $user->name;
		
		// get latest a scan
		$retVal['auscan'] = Scan::where('id', $planet->latest_au)->first();
		
		// ship stats
		if ($race != 'Zikonian' && $race != 'Eitraides')
			$retVal['ships'] = Ship::where('race', $race)->get();
		else
			$retVal['ships'] =  Ship::get();
		
		// process fleets
		$retVal['mtime'] = 0;
		foreach ($fleets as $fleet):
			
			// append fleet
			$retVal['fleets'][$fleet['fleet_id']][] = array(
				'fleet_ship_id' => $fleet['fleet_id'] . '_' . $fleet['ship_id'],
				'ship' => Ship::find($fleet['ship_id']),
				'amount' => $fleet['amount'],
			);
			
			// retrieve current time
			$mtime = strtotime($fleet->mtime);
			if ($mtime > $retVal['mtime'])
				$retVal['mtime'] = $mtime;
		endforeach;
		
		// mtime
		$retVal['mtime'] = date('H:i', $retVal['mtime']);
		
		
		return json_encode($retVal);
	}
	
	/**
	 * Add a ship to fleet
	 *
	 * @return boolean
	 * @author Craig Fairhurst
	 */
	static public function addShip ($battlegroupId, $userId, $fleetId, $shipId, $shipAmount)
	{
		
		// get ships in fleet
		$fleets = BattlegroupFleets::where('battlegroup_id', $battlegroupId)->where('user_id', $userId)->where('fleet_id', $fleetId)->get();
	
		// check for duplicate
		$duplicate = $fleets->where('ship_id', $shipId)->first();
		if (isset($duplicate->ship_id)):
			$duplicate->amount = $shipAmount;
			$duplicate->save();
		else:
			$insertFleet = array(
				'battlegroup_id' => $battlegroupId,
				'user_id' => $userId,
				'fleet_id' => $fleetId,
				'ship_id' => $shipId,
				'amount' => $shipAmount
			);
			BattlegroupFleets::insert($insertFleet);
		endif;

		
		return 'Success';
		
	}
	
	/**
	 * Edit a ship in fleet
	 *
	 * @return boolean
	 * @author Craig Fairhurst
	 */
	static public function editShip ($battlegroupId, $userId, $fleetId, $shipId, $shipAmount)
	{
		
		// get ships in fleet
		$fleets = BattlegroupFleets::where('battlegroup_id', $battlegroupId)->where('user_id', $userId)->where('fleet_id', $fleetId)->get();
	
		// get and edit ship
		$ship = $fleets->where('ship_id', $shipId)->first();
		$ship->amount = $shipAmount;
		$ship->save();
		
		return 'Success';
		
	}
	
	/**
	 * Edit a ship in fleet
	 *
	 * @return boolean
	 * @author Craig Fairhurst
	 */
	static public function deleteShip ($battlegroupId, $userId, $fleetId, $shipId)
	{
		// get ship
		$ship = BattlegroupFleets::where('battlegroup_id', $battlegroupId)->where('user_id', $userId)->where('fleet_id', $fleetId)->where('ship_id', $shipId)->first();
	
		// delete ship
		$ship->delete();
		
		return 'Success';
		
	}
}