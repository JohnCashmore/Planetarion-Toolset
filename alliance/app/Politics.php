<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Politics extends Model
{
	protected $table = 'politics';

	public static function fetch ()
	{
		$relations = Politics::get();
		$retVal = array();
		foreach ($relations as $relation):
			$alliance = Alliance::where('id', $relation->allianceId)->first();
			$retVal[$relation->id] = $relation;
			$retVal[$relation->id]->allianceName = $alliance->name;
		endforeach;
		return $retVal;
	}
	
	public static function change ($allianceId, $status, $maxPlanets = 0, $maxWaves = 0, $maxFleets = 0)
	{
		$currentRelations = Politics::where('allianceId', $allianceId)->count();
		if ($currentRelations == 0):
			$politics = new Politics();
		else:
			$politics = Politics::where('allianceId', $allianceId)->first();
		endif;
		
		$politics->allianceId = $allianceId;
		$politics->status = $status;
		if ($status == 'deal'):
			$politics->maxPlanets = $maxPlanets;
			$politics->maxWaves = $maxWaves;
			$politics->maxFleets = $maxFleets;
		else:
			$politics->maxPlanets = NULL;
			$politics->maxWaves = NULL;
			$politics->maxFleets = NULL;
		endif;
		$politics->save();
		
		
		return 'success';
	}
	
	public static function remove ($politicsId)
	{
		$retVal = Politics::where('id', $politicsId)->delete();

		return $retVal;
	}
}
