<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
	protected $table = 'schedule';

	public static function fetch ($userId)
	{
		return Schedule::where('user_id', $userId)->get();
	}
	
	public static function change ($userId, $dateTime, $action)
	{
		if ($action == 'allow'):
			
			// delete any entries
			Schedule::where(['user_id' => $userId, 'datetime' => $dateTime])->delete();
		else:
			
			// create entry
			$insert = array(
				'user_id' => $userId,
				'datetime' => $dateTime
			);
			Schedule::insert($insert);
		endif;
		
		return 'success';
	}
}
