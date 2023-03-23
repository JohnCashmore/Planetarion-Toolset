<?php
namespace App\Telegram\Custom;

use App\Telegram\Custom\BaseCommand;
use App\User;
use App;
use DB;

class SmallDicksCommand extends BaseCommand
{
    protected $command = "!smalldicks";

    public function execute()
    {
        if(!$this->isWebUser()) return "User can not be authenticated with webby.";
        if(!$this->isChannelAllowed()) return "You can not use that command in this channel";
   
        $members = User::orderBy('name', 'ASC')->where(['is_enabled' => 1])->whereNotNull('planet_id')->get();
    
        $MEMBERSSUMMARY = collect([]);
        ForEach($members as $member) {
            $PLANET             = DB::table('planets')->where('id', $member->planet_id)->first();
            $GROWTH_VALUE       = ($PLANET->growth_value) ?? 0;
            $GROWTH_SCORE       = ($PLANET->growth_score) ?? 0;
            $GROWTH_SIZE        = ($PLANET->growth_size) ?? 0;
            $GROWTH_XP          = ($PLANET->growth_xp) ?? 0;
            $MEMBERSTATS = collect(["name" => $member->name, "growth_value" => $GROWTH_VALUE, "growth_score" => $GROWTH_SCORE, "growth_size" => $GROWTH_SIZE, "growth_xp" => $GROWTH_XP]);            
            $MEMBERSSUMMARY->push($MEMBERSTATS);
        }
    
            if(!$this->text || strtolower($this->text) == "score") {
                $ORDER                  = $MEMBERSSUMMARY->sortBy('growth_score');
                $ORDER_TEXT             = "DAY GROWTH SCORE";
                $ORDER_VALUE            = "growth_score";
            }
    
            if(strtolower($this->text) == "value") {
                $ORDER                  = $MEMBERSSUMMARY->sortBy('growth_value');
                $ORDER_TEXT             = "DAY GROWTH VALUE";
                $ORDER_VALUE            = "growth_value";
            }
    
            if(strtolower($this->text) == "size") {
                $ORDER                  = $MEMBERSSUMMARY->sortBy('growth_size');
                $ORDER_TEXT             = "DAY GROWTH SIZE";
                $ORDER_VALUE            = "growth_size";
            }
    
            if(strtolower($this->text) == "xp") {
                $ORDER                  = $MEMBERSSUMMARY->sortBy('growth_xp');
                $ORDER_TEXT             = "DAY GROWTH XP";
                $ORDER_VALUE            = "growth_xp";
            }
            
            $RESULTS = $ORDER->take(10);
            $RESULTS->all();
    
            $RESULT                     = "SMALL DICKS: " . $ORDER_TEXT . "\n\n";
            $ORDER_NUMBER               = 1;
            ForEach($RESULTS as $RECORD) {
                $RESULT                .= "#" . $ORDER_NUMBER . " " . $RECORD->get('name') . " (" . number_format($RECORD->get($ORDER_VALUE)) . "), ";
                $ORDER_NUMBER           = $ORDER_NUMBER + 1;
            }
    
            return substr($RESULT, 0, -2);
    }
}