<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Politics;
use Auth;

class PoliticsController extends ApiController
{
	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public static function fetch()
	{
		return Politics::fetch();
	}

	public static function change($allianceId, $status, $maxPlanets = 0, $maxWaves = 0, $maxFleets = 0, $expire = NULL)
	{
		return Politics::change($allianceId, $status, $maxPlanets, $maxWaves, $maxFleets, $expire);
	}
	
	public static function remove($politicsId)
	{
		return Politics::remove($politicsId);
	}
}
