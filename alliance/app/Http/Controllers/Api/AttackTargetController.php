<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\AttackRequest;

use App\Http\Controllers\Controller;
use App\AttackBooking;

class AttackTargetController extends ApiController
{
	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function bookings($attackId, $targetId)
	{
		$retVal = AttackBooking::with(['user' => function($q) {
			$q->select(['id', 'name']);
		}])->where([
			'attack_target_id' => $targetId,
			'attack_id' => $attackId
		])->get();
			
		foreach ($retVal as $key => $value):
			$retVal[$key]['closed'] = true;
		endforeach;		
			
		//echo "<pre>";var_dump($retVal);
			
		return $retVal;
		
	}
}
