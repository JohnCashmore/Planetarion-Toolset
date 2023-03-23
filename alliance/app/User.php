<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Config;
use App\Planet;

class User extends Authenticatable
{
	use Notifiable;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	public $fillable = [
		'name',
		'email',
		'password',
		'planet_id',
		'phone',
		'timezone',
		'notes',
		'distorters',
		'military_centres',
		'is_enabled',
		'is_new',
		'last_login',
		'role_id',
		'tg_username',
		'stealth',
		'battlegroup'
	];

	protected $appends = [
		'notification_email'
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token',
	];
	static public function getUser($user) {
		
		$user = User::where('name', 'LIKE', '%' . $user . '%')->first();

		$retVal = array(
			'id' => $user->id,
			'name' => $user->name,
			'email' => $user->email,
			'name' => $user->name,
			'planet_id' => $user->planet_id,
			'phone' => $user->phone,
			'timezone' => ($user->timezone?$user->timezone:'+0'),
			'notes' => $user->notes,
			'distorters' => $user->distorters,
			'military_centres' => $user->military_centres,
			'is_enabled' => $user->is_enabled,
			'is_new' => $user->is_new,
			'last_login' => $user->last_login,
			'role_id' => $user->role_id,
			'tg_username' => $user->tg_username,
			'allow_night' => $user->allow_night,
			'allow_notifications' => $user->allow_notifications,
			'notification_email_forward' => $user->notification_email_forward,
			'stealth' => $user->stealth
		);
		
		return $retVal;
	}
	
	static public function getUserById($id) {
		
		$user = User::where('id', 'LIKE', $id)->first();
		$retVal = array(
			'name' => $user->name,
			'email' => $user->email,
			'planet_id' => $user->planet_id,
			'phone' => $user->phone,
			'timezone' => $user->timezone,
			'notes' => $user->notes,
			'distorters' => $user->distorters,
			'military_centres' => $user->military_centres,
			'is_enabled' => $user->is_enabled,
			'is_new' => $user->is_new,
			'last_login' => $user->last_login,
			'role_id' => $user->role_id,
			'tg_username' => $user->tg_username,
			'allow_night' => $user->allow_night,
			'allow_notifications' => $user->allow_notifications,
			'notification_email_forward' => $user->notification_email_forward,
			'stealth' => $user->stealth
		);
		
		return $retVal;
	}
	
	static public function getUserByPlanetId($id) {
		
		$user = User::where('planet_id', $id)->first();
		
		if (isset($user->name)):
			$retVal = array(
				'name' => $user->name,
				'email' => $user->email,
				'planet_id' => $user->planet_id,
				'phone' => $user->phone,
				'timezone' => $user->timezone,
				'notes' => $user->notes,
				'distorters' => $user->distorters,
				'military_centres' => $user->military_centres,
				'is_enabled' => $user->is_enabled,
				'is_new' => $user->is_new,
				'last_login' => $user->last_login,
				'role_id' => $user->role_id,
				'tg_username' => $user->tg_username,
				'allow_night' => $user->allow_night,
				'allow_notifications' => $user->allow_notifications,
				'notification_email_forward' => $user->notification_email_forward,
				'stealth' => $user->stealth
			);
			
			return $retVal;
		else:
			return 'Could not find user';
		endif;
	}
	public function getnotificationEmailAttribute($value)
	{
		if(Config::get('notifications.email_notifications.enabled')) {
			$email = Config::get('notifications.email_notifications.email_address');
			$email = str_replace("@webby.domain.tld", "", $email);
			
			return $email . "+" . $this->id . "@webby.domain.tld";
		}

		return;
	}

	public function scopeAdmin($query)
	{
		return $query->whereHas('role', function($q) {
			$q->where('name', 'Admin');
		});
	}

	public function scopeMember($query)
	{
		return $query->whereHas('role', function($q) {
			$q->where('name', 'Member');
		});
	}

	public function scopeBc($query)
	{
		return $query->whereHas('role', function($q) {
			$q->where('name', 'BC');
		});
	}

	public function planet()
	{
		return $this->hasOne(Planet::class, 'id', 'planet_id');
	}

	public function activities()
	{
		return $this->hasMany(Activity::class, 'user_id', 'id');
	}

	public function battlegroups()
	{
		return $this->hasMany(Battlegroup::class, 'battlegroup_members', 'user_id', 'battlegroup_id');
	}

	public function role()
	{
		return $this->hasOne(Role::class, 'id', 'role_id');
	}

	public function bookings()
	{
		return $this->hasMany(AttackBooking::class);
	}

	public function sharedBookings()
	{
		return $this->belongsToMany(AttackBooking::class, 'attack_booking_user', 'user_id', 'booking_id');
	}
}
