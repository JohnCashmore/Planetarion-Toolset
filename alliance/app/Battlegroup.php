<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Auth;

class Battlegroup extends Model
{
	protected $table = 'battlegroups';

	protected $appends = [
	  'is_bg',
	  'is_pending',
	  'is_owner',
	  'member_count',
	  'avg_score',
	  'avg_value',
	  'avg_size',
	  'avg_xp',
	];

	protected $fillable = [
		'name',
		'creator_id',
		'value',
		'score',
		'size',
		'xp',
		'day_value',
		'day_score',
		'day_size',
		'day_xp',
	];

	public function getMemberCountAttribute()
	{
		return $this->members->count();
	}

	public function getIsBgAttribute()
	{
		$members = $this->members->keyBy('id');

		if(isset($members[Auth::user()->id])) return true;

		return false;
	}

	public function getIsPendingAttribute()
	{
		$members = $this->membersPending->keyBy('id');

		if(isset($members[Auth::user()->id])) return true;

		return false;
	}

	public function getIsOwnerAttribute()
	{
		if(Auth::user()->id == $this->owner->id) return true;

		return false;
	}

	public function getAvgSizeAttribute()
	{
		if (isset($this->size) && $this->size > 0 && isset($this->member_count) && $this->member_count > 0)
			return $this->size / $this->member_count;
	}

	public function getAvgValueAttribute()
	{
		if (isset($this->value) && $this->value > 0 && isset($this->member_count) && $this->member_count > 0)
			return $this->value / $this->member_count;
	}

	public function getAvgScoreAttribute()
	{
		if (isset($this->score) && $this->score > 0 && isset($this->member_count) && $this->member_count > 0)
			return $this->score / $this->member_count;
		
	}

	public function getAvgXpAttribute()
	{
		if (isset($this->xp) && $this->xp > 0 && isset($this->member_count) && $this->member_count > 0)
			return $this->xp / $this->member_count;
	}

	public function owner()
	{
		return $this->belongsTo(User::class, 'creator_id');
	}

	public function members()
	{
		return $this->belongsToMany(User::class, 'battlegroup_users', 'battlegroup_id', 'user_id')->where('is_pending', 0);
	}

	public function membersPending()
	{
		return $this->belongsToMany(User::class, 'battlegroup_users', 'battlegroup_id', 'user_id')->where('is_pending', 1);
	}
}
