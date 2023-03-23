<?php
namespace App\Telegram\Custom;

use App\Telegram\Custom\BaseCommand;
use App\User;
use App\Planet;
use App\Alliance;
use App\PlanetScan;
use App\Tick;
use App;
use DB;

class StockpileCommand extends BaseCommand
{
    protected $command = "!stockpile";

    public function execute()
    {
        if(!$this->isWebUser()) return "User can not be authenticated with webby.";
	if($this->chatId == "-1001385725905" || $this->chatId == "-349310499") {

        $params = explode(" ", strtolower($this->text));
        $ally = $params[0];
        $showsummary = true;

        if(count($params) > 1) {
            if ($params[1] == "details") {
                $ally = $params[0];
                $showsummary = false;
            }
            elseif (isset($params[2])) {
                if ($params[2] == "details") {
                    $ally = $params[0] . " " . $params[1];
                    $showsummary = false;
                } 
            }
            else {
                $ally = $params[0] . " " . $params[1];
                $showsummary = true;
            }
        }

        $alliance   = Alliance::where('name', 'like', '%' . $ally . '%')->orWhere('nickname', $ally)->first();
        if(!isset($alliance)) {
            return "There is no alliance with name or alias: " . $ally;
        }

        $currentTick            = Tick::orderBy('tick', 'DESC')->first();
        $tick                   = $currentTick->tick;

        $planets                = Planet::where('alliance_id', $alliance->id)->orderBy('x', 'ASC')->orderBy('y', 'ASC')->orderBy('z', 'ASC')->get();
        $metal                  = 0;
        $crystal                = 0;
        $eonium                 = 0;
        $prod                   = 0;

        $oldscan                = collect([]);
        $noscan                 = collect([]);
        $prodders               = collect([]);
        $stockpilers            = collect([]);

        $result = "<u>Stockpile information for " . $alliance->name . "</u>\n";

        ForEach($planets as $planet) {
            $pscan = PlanetScan::with('scan')->where('id', $planet->latest_p)->first();
            
            $nickname = "";
            if(isset($planet->nick)) {
                $nickname = $planet->nick;
            }
            
            if(!$pscan) {
                $info           = collect(["nickname" => $nickname, "x" => $planet->x, "y" => $planet->y, "z" => $planet->z]); 
                $noscan->push($info);
                continue; 
            }

            $scanage            = $tick - $pscan->scan->tick;
            if($scanage > 3) {
                $info           = collect(["nickname" => $nickname, "x" => $planet->x, "y" => $planet->y, "z" => $planet->z, "scanage" => $scanage]);    
                $oldscan->push($info);
            }

            $metal              = $metal + intval(str_replace(',', '', $pscan->res_metal));
            $crystal            = $crystal + intval(str_replace(',', '', $pscan->res_crystal));
            $eonium             = $eonium + intval(str_replace(',', '', $pscan->res_eonium));
            $resources          = $metal + $crystal + $eonium;
            $planet_res         = intval(str_replace(',', '', $pscan->res_metal)) + intval(str_replace(',', '', $pscan->res_crystal)) + intval(str_replace(',', '', $pscan->res_eonium));

            $info               = collect(["nickname" => $nickname, "x" => $planet->x, "y" => $planet->y, "z" => $planet->z, "stockpile" => number_shorten($planet_res, 0)]);
            $stockpilers->push($info);

            $prod               = $prod + intval(str_replace(',', '', $pscan->prod_res));
            $planet_prod        = intval(str_replace(',', '', $pscan->prod_res));
            if($planet_prod > 0) {
                $info           = collect(["nickname" => $nickname, "x" => $planet->x, "y" => $planet->y, "z" => $planet->z, "in_prod" => number_shorten($planet_prod, 0)]);    
                $prodders->push($info);
            }
        }

        $resources_vgain        = $resources / 300;
        $prod_vgain             = $prod / 300;

        $noscan->all();
        $oldscan->all();
        $prodders->all();
        $stockpilers->all();

        $result                .= "<strong>Resources</strong>: " . number_format($resources) . " (" . number_shorten($resources_vgain, 2) . " value gain)\n<strong>In Production</strong>: " . number_format($prod) . " (" . number_shorten($prod_vgain, 2) . " value gain)\n";

        if($showsummary) {
            return $result;
        }

        $oldscan_result         = "<strong>Old Scans (+3 tick)</strong>: \n";
        ForEach($oldscan as $record) {
            $oldscan_result    .= $record->get('x') . ":" . $record->get('y') . ":" . $record->get('z') . ", ";
        }

        $noscan_result = "<strong>No Scans</strong>: \n";
        ForEach($noscan as $record) {
            $noscan_result     .= $record->get('x') . ":" . $record->get('y') . ":" . $record->get('z') . ", ";
        }

        $prodder_result = "<strong>Prodders</strong>: \n";
        ForEach($prodders as $record) {
            $nickname = $record->get('nickname');
            if($nickname == "") {
                $prodder_result .= $record->get('x') . ":" . $record->get('y') . ":" . $record->get('z') . "(" . $record->get('in_prod') . "), ";
            }
            else {
                $prodder_result .= $record->get('nickname') . "(" . $record->get('in_prod') . "), ";
            }
        }

        $stockpiler_result = "<strong>Stockpilers</strong>: \n";
        ForEach($stockpilers as $record) {
            $nickname = $record->get('nickname');
            if($nickname == "") {
                $stockpiler_result .= $record->get('x') . ":" . $record->get('y') . ":" . $record->get('z') . "(" . $record->get('stockpile') . "), ";
            }
            else {
                $stockpiler_result .= $record->get('nickname') . "(" . $record->get('stockpile') . "), ";
            }
        }

        return $result . "\n" . substr($oldscan_result, 0, -2) . "\n\n" . substr($noscan_result, 0, -2) . "\n\n" . substr($prodder_result, 0, -2) . "\n\n" . substr($stockpiler_result, 0, -2);
	}
    }
}