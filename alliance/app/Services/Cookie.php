<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use App\Telegram\Custom\BaseCommand;
use App\Planet;
use App\User;
use DB;
use Carbon\Carbon;

class Cookie extends Model
{
	
	/**
	 * Give user a cookie
	 *
	 * @return string message
	 * @author Craig Fairhurst
	 */
	public function give($user, $cookie)
	{
		$insert = array(
			'user_id' => $user,
			'message' => $cookie
		);
		$this->insert($insert);
		$this->save();
	
		return $cookie;
	
	}
	
}

?>