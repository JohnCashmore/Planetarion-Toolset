<?php
namespace App\Telegram\Custom\Attacks;

use App\Telegram\Custom\BaseCommand;
use App\User;
use App\Ship;
use App\Planet;
use App;
use DB;

class TopBGSCommand extends BaseCommand
{
    protected $command = "!topbgs";

    public function execute()
    {
        if(!$this->isWebUser()) return "User can not be authenticated with webby.";

        $BGIDS                      = DB::table('battlegroups')->get()->pluck('id')->unique();
	if(!count($BGIDS)) {
	    return "There's no BG's, lazy cunts!";
	}

        $BGSUMMARY                  = collect([]);
        ForEach($BGIDS as $battlegroup) {
            $BGNAME                 = DB::table('battlegroups')->where('id', $battlegroup)->get()->pluck('name');
            $BGMEMBERCOUNT          = DB::table('battlegroup_users')->where('battlegroup_id', $battlegroup)->get()->count();
            $BGMEMBERS              = DB::table('battlegroup_users')->where('battlegroup_id', $battlegroup)->get()->pluck('user_id');
            $BGPLANETS              = DB::table('users')->whereIn('id', $BGMEMBERS)->get()->pluck('planet_id');

            $BGVALUE_TOTAL          = 0;
            $BGSCORE_TOTAL          = 0;
            $BGSIZE_TOTAL           = 0;
            $BGXP_TOTAL           = 0;
            ForEach($BGPLANETS as $PLANET_ID) {
                $PLANET             = DB::table('planets')->where('id', $PLANET_ID)->get();
                $BGVALUE_TOTAL      = $BGVALUE_TOTAL + $PLANET[0]->value;
                $BGSCORE_TOTAL      = $BGSCORE_TOTAL + $PLANET[0]->score;
                $BGSIZE_TOTAL       = $BGSIZE_TOTAL + $PLANET[0]->size;
                $BGXP_TOTAL         = $BGXP_TOTAL + $PLANET[0]->xp;
            }

            $BGVALUE_AVERAGE        = round($BGVALUE_TOTAL / $BGMEMBERCOUNT);
            $BGSCORE_AVERAGE        = round($BGSCORE_TOTAL / $BGMEMBERCOUNT);
            $BGSIZE_AVERAGE         = round($BGSIZE_TOTAL / $BGMEMBERCOUNT);
            $BGXP_AVERAGE           = round($BGXP_TOTAL / $BGMEMBERCOUNT);

            $BG                     = collect(["id" => $battlegroup, "name" => $BGNAME[0], "member_count" => $BGMEMBERCOUNT, "total_value" => $BGVALUE_TOTAL, "total_score" => $BGSCORE_TOTAL, "total_size" => $BGSIZE_TOTAL, "avg_value" => $BGVALUE_AVERAGE, "avg_score" => $BGSCORE_AVERAGE, "avg_size" => $BGSIZE_AVERAGE, "avg_xp" => $BGXP_AVERAGE/* "members" => $BGMEMBERS, "member_planets" => $BGPLANETS*/]);            
            $BGSUMMARY->push($BG);
        }

        if(!$this->text || strtolower($this->text) == "score") {
            $ORDER                  = $BGSUMMARY->sortByDesc('avg_score');
            $ORDER_TEXT             = "AVERAGE SCORE";
            $ORDER_VALUE            = "avg_score";
        }

        if(strtolower($this->text) == "value") {
            $ORDER                  = $BGSUMMARY->sortByDesc('avg_value');
            $ORDER_TEXT             = "AVERAGE VALUE";
            $ORDER_VALUE            = "avg_value";
        }

        if(strtolower($this->text) == "size") {
            $ORDER                  = $BGSUMMARY->sortByDesc('avg_size');
            $ORDER_TEXT             = "AVERAGE SIZE";
            $ORDER_VALUE            = "avg_size";
        }

        if(strtolower($this->text) == "xp") {
            $ORDER                  = $BGSUMMARY->sortByDesc('avg_xp');
            $ORDER_TEXT             = "AVERAGE XP";
            $ORDER_VALUE            = "avg_xp";
        }
        
        $ORDER->all();

        $RESULT                     = "BATTLEGROUPS SUMMARY: " . $ORDER_TEXT . "\n\n";
        $ORDER_NUMBER               = 1;
        ForEach($ORDER as $RECORD) {
            $RESULT                .= "#" . $ORDER_NUMBER . " - " . strtoupper($RECORD->get('name')) . " - " . number_format($RECORD->get($ORDER_VALUE)) . "\n";
            $ORDER_NUMBER           = $ORDER_NUMBER + 1;
        }

        return $RESULT;
    }
}