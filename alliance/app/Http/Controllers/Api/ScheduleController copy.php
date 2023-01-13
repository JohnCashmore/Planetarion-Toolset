<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Schedule;
use Auth;

class ScheduleController extends ApiController
{
	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public static function fetch($userId)
	{
		return Schedule::fetch($userId);
	}

	public static function change($userId, $dateTime, $action)
	{
		return Schedule::change($userId, $dateTime, $action);
	}
}
