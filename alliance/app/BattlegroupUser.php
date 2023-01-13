<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class BattlegroupUser extends Model {
	
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
}