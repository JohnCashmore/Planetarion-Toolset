<?php
namespace App\Telegram\Custom\Attacks;

use App\Telegram\Custom\BaseCommand;
use App\User;
use App\Planet;
use App\Alliance;
use App\DevelopmentScan;
use App\Tick;
use App;
use DB;

class StructureKillersCommand extends BaseCommand
{
    protected $command = "!sk";

    public function execute()
    {
        if(!$this->isWebUser()) return "User can not be authenticated with webby.";

        $alliance   = Alliance::where('name', 'like', '%' . $this->text . '%')->orWhere('nickname', $this->text)->first();
        if(!isset($alliance)) {
            return "There is no alliance with name or alias: " . $this->text;
        }

        $currentTick            = Tick::orderBy('tick', 'DESC')->first();
        $tick                   = $currentTick->tick;

        $planets                = Planet::where('alliance_id', $alliance->id)->orderBy('x', 'ASC')->orderBy('y', 'ASC')->orderBy('z', 'ASC')->get();

        $immune                 = collect([]);
        $idiots                 = collect([]);
        $noscan                 = collect([]);
        $oldscan                = collect([]);
        $shoulddelete           = collect([]);

        $result = "<u>Structure Killers information for " . $alliance->name . "</u>\n";

        ForEach($planets as $planet) {
            $dscan = DevelopmentScan::with('scan')->where('id', $planet->latest_d)->first();
            
            $nickname = "";
            if(isset($planet->nick)) {
                $nickname = $planet->nick;
            }
            
            if(!$dscan) {
                $info           = collect(["nickname" => $nickname, "x" => $planet->x, "y" => $planet->y, "z" => $planet->z]); 
                $noscan->push($info);
                continue; 
            }

            $scanage            = $tick - $dscan->scan->tick;
            if($scanage > 3) {
                $info           = collect(["nickname" => $nickname, "x" => $planet->x, "y" => $planet->y, "z" => $planet->z, "scanage" => $scanage]);    
                $oldscan->push($info);
            }

            $total_cons         = $dscan->light_factory + $dscan->medium_factory + $dscan->heavy_factory + $dscan->wave_amplifier + $dscan->wave_distorter + $dscan->metal_refinery + $dscan->crystal_refinery + $dscan->eonium_refinery + $dscan->research_lab + $dscan->finance_centre + $dscan->military_centre + $dscan->security_centre + $dscan->structure_defence;
            $total_sd           = $dscan->structure_defence;
            $required_sd        = ceil($total_cons / 10);
	    $missing_sd		= $required_sd - $total_sd;

            if($total_sd == 0) {
                $info               = collect(["nickname" => $nickname, "x" => $planet->x, "y" => $planet->y, "z" => $planet->z, "total_cons" => $total_cons, "total_sd" => $total_sd, "missing_sd" => $missing_sd]);
                $shoulddelete->push($info);
                continue;
            }

            if($total_sd >= $required_sd) {
                $info               = collect(["nickname" => $nickname, "x" => $planet->x, "y" => $planet->y, "z" => $planet->z, "total_cons" => $total_cons, "total_sd" => $total_sd]);
                $immune->push($info);
            }
            else {
                $info               = collect(["nickname" => $nickname, "x" => $planet->x, "y" => $planet->y, "z" => $planet->z, "total_cons" => $total_cons, "total_sd" => $total_sd, "missing_sd" => $missing_sd]);
                $idiots->push($info);
            }
        }

	$shoulddeleteORDER 	= $shoulddelete->sortByDesc('missing_sd');
	$idiotsORDER 		= $idiots->sortByDesc('missing_sd');

        $noscan->all();
        $oldscan->all();
        $immune->all();
        $idiotsORDER->all();
	$shoulddeleteORDER->all();

        $oldscan_result         = "<strong>Old Scans (+3 tick)</strong>: \n";
        ForEach($oldscan as $record) {
            $oldscan_result    .= $record->get('x') . ":" . $record->get('y') . ":" . $record->get('z') . ", ";
        }

        $noscan_result = "<strong>No Scans</strong>: \n";
        ForEach($noscan as $record) {
            $noscan_result     .= $record->get('x') . ":" . $record->get('y') . ":" . $record->get('z') . ", ";
        }

        $immune_result = "<strong>Immune</strong>: \n";
        ForEach($immune as $record) {
            $nickname = $record->get('nickname');
            if($nickname == "") {
                $immune_result .= $record->get('x') . ":" . $record->get('y') . ":" . $record->get('z') . "(T:" . $record->get('total_cons') . "/SD:" . $record->get('total_sd') . "), ";
            }
            else {
                $immune_result .= $record->get('nickname') . "(T:" . $record->get('total_cons') . "/SD:" . $record->get('total_sd') . "), ";
            }
        }

        $idiots_result = "<strong>Idiots</strong>: \n";
        ForEach($idiotsORDER as $record) {
            $nickname = $record->get('nickname');
            if($nickname == "") {
                $idiots_result .= $record->get('x') . ":" . $record->get('y') . ":" . $record->get('z') . "(T:" . $record->get('total_cons') . "/SD:" . $record->get('total_sd') . "), ";
            }
            else {
                $idiots_result .= $record->get('nickname') . "(T:" . $record->get('total_cons') . "/SD:" . $record->get('total_sd') . "), ";
            }
        }

        $shoulddelete_result = "<strong>Should Delete Planet</strong>: \n";
        ForEach($shoulddeleteORDER as $record) {
            $nickname = $record->get('nickname');
            if($nickname == "") {
                $shoulddelete_result .= $record->get('x') . ":" . $record->get('y') . ":" . $record->get('z') . "(T:" . $record->get('total_cons') . "/SD:" . $record->get('total_sd') . "), ";
            }
            else {
                $shoulddelete_result .= $record->get('nickname') . "(T:" . $record->get('total_cons') . "/SD:" . $record->get('total_sd') . "), ";
            }
        }

        return $result . "\n" . substr($oldscan_result, 0, -2) . "\n\n" . substr($noscan_result, 0, -2) . "\n\n" . substr($immune_result, 0, -2) . "\n\n" . substr($idiots_result, 0, -2) . "\n\n" . substr($shoulddelete_result, 0, -2);
    }
}