<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Battlegroup;
use App\BattlegroupUser;
use App\BattlegroupTeamups;
use App\BattlegroupFleets;

use App\User;
use Auth;

class BattlegroupUserController extends ApiController
{
	public function index($id)
	{
		$bg = Battlegroup::with(['members', 'membersPending', 'members.planet','membersPending.planet'])->find($id);

		$members = $bg->members->keyBy('id')->toArray();
		$membersPending = $bg->membersPending->keyBy('id')->toArray();
		
		$users = User::whereNotNull('planet_id')->orderBy('name')->get()->keyBy('id')->toArray();

		$new = array_diff_key($users, $members);
		$new = array_diff_key($new, $membersPending);
		
		$bg->non_members = collect($new)->values();

		return $bg;
	}

	public function store($id, Request $request)
	{
		$user = $request->input('user');

		$bg = Battlegroup::find($id);

		$bg->members()->sync([$user => ['is_pending' => 0]], false);

		return $this->index($id);
	}

	public function accept($bgId, $userId)
	{
		$bg = Battlegroup::find($bgId);

		$bg->members()->sync([$userId => ['is_pending' => 0]], false);

		return $this->index($bgId);
	}

	public function decline($bgId, $userId)
	{
		$bg = Battlegroup::find($bgId);

		$bg->members()->detach([$userId]);

		return $this->index($bgId);
	}

	public function destroy($bgId, $userId)
	{
		$bg = Battlegroup::find($bgId);

		$bg->members()->detach([$userId]);
		
		BattlegroupUser::where(['battlegroup_id' => $bgId, 'user_id' => $userId])->delete();
		
		$teamups = BattlegroupTeamups::where('battlegroup_id', $bgId)->where('user_id', $userId)->get();
		$fleets = BattlegroupFleets::where('battlegroup_id', $bgId)->where('user_id', $userId)->get();
		
		foreach ($teamups as $teamup):
			$teamup->delete();
		endforeach;
		foreach ($fleets as $fleet):
			$fleet->delete();
		endforeach;

		return $this->index($bgId);
	}
}
