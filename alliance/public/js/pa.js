(function(w) {
	'use strict';
	var doc = w.document;
	if (!doc) return;

	var $j;
	var $pa;
	var skin = '';
	var page = '';
	var view = '';
	var tick = 0;
	var globalDependencies = ['jQuery', 'get_cookie', 'get_ships_cookie', 'PA'];

	var ships = { "Harpy": 0, "Centaur": 1, "Chimera": 2, "Pegasus": 3, "Drake": 4, "Syren": 5, "Titan": 6, "Wyvern": 7, "Medusa": 8, "Demeter": 9, "Griffin": 10, "Behemoth": 11,
		"Moth": 12, "Spider": 13, "Beetle": 14, "Scarab": 15, "Black Widow": 16, "Roach": 17, "Tarantula": 18, "Scorpion": 19, "Wasp": 20, "Ant": 21, "Hornet": 22, "Termite": 23,
		"Phantom": 24, "Banshee": 25, "Apparition": 26, "Shadow": 27, "Reaper": 28, "Spirit": 29, "Nightmare": 30, "Wraith": 31, "Illusion": 32, "Vampyre": 33, "Poltergeist": 34, "Haunt": 35,
		"Interceptor": 36, "Cutlass": 37, "Cutter": 38, "Thief": 39, "Clipper": 40, "Marauder": 41, "Brigand": 42, "Pirate": 43, "Privateer": 44, "Rover": 45, "Pillager": 46, "Galleon": 47,
		"Firefly": 48, "Candle": 49, "Torch": 50, "Blacklight": 51, "Laser": 52, "Moonlight": 53, "Red dwarf": 54, "Gas giant": 55, "Ember": 56, "Spark": 57, "Pulsar": 58, "Supernova": 59,
		"Mace": 60, "Spear": 61, "Pike": 62, "Arbalest": 63, "Broadsword": 64, "Lance": 65, "Halberd": 66, "Trebuchet": 67, "Warhammer": 68, "Ram": 69, "Glaive": 70, "Siegetower": 71 };
	
	var races = { "Ter": 1, "Cat": 2, "Xan": 3, "Zik": 4, "Kin": 5, "Sly": 6 };

	if(doc.readyState == 'complete') {
		checkDeps(true);
	} else {
		var _ev = w.addEventListener ? {add: 'addEventListener', rem: 'removeEventListener', pfx: ''} : w.attachEvent ? {add: 'attachEvent', rem: 'detachEvent', pfx: 'on'} : null;
		if(_ev) {
			doc[_ev.add](_ev.pfx + 'DOMContentLoaded', waitLoad, false);
			doc[_ev.add](_ev.pfx + 'readystatechange', waitLoad, false);
			w[_ev.add](_ev.pfx + 'load', waitLoad, false);
		} else {
			checkDeps();
		}
	}

	function waitLoad(ev) {
		ev = ev || w.event;
		if(ev.type === 'readystatechange' && doc.readyState && doc.readyState !== 'complete' && doc.readyState !== 'loaded') return;
		if(_ev) {
			doc[_ev.rem](_ev.pfx + 'DOMContentLoaded', waitLoad);
			doc[_ev.rem](_ev.pfx + 'readystatechange', waitLoad);
			w[_ev.rem](_ev.pfx + 'load', waitLoad);
			_ev = null;
			checkDeps(true);
		}
	}

	function checkDeps(loaded) {
		var remainingDeps = globalDependencies.filter(function(dep) {
			return !w[dep];
		});
		if(!remainingDeps.length) init();
		else if (loaded) console.error(remainingDeps.length+' missing userscript dependenc'+(remainingDeps.length==1?'y':'ies')+': '+remainingDeps.join(', '));
	}

	function init() {
		if($j) return;
		$j = w.jQuery;
		$pa = w.PA;

		$j('li#menu_help').after('<li id="menu_vengeance" class="menu_grp"><span class="textlabel">Vengeance</span><ul><li id="menu_vgnscans"><a target="_blank" href="https://vgnpa.uk/#/scans"><span class="textlabel">Scans</span></a></li><li id="menu_vgncovops"><a target="_blank" href="https://vgnpa.uk/#/covops"><span class="textlabel">CovOps</span></a></li><li id="menu_vgnatt"><a target="_blank" href="https://vgnpa.uk/#/attacks"><span class="textlabel">Attacks</span></a></li><li id="menu_vgndef"><a target="_blank" href="https://vgnpa.uk/#/defence"><span class="textlabel">Defence</span></a></li><li id="menu_vgnbgs"><a target="_blank" href="https://vgnpa.uk/#/battlegroups"><span class="textlabel">Battlegroups</span></a></li></ul></li><li id="menu_tools" class="menu_grp"><span class="textlabel">Tools</span><ul><li id="menu_vgn"><a target="_blank" href="https://vgnpa.uk/#/"><span class="textlabel">Vengeance</span></a></li><li id="menu_bream"><a target="_blank" href="http://breampatools.ddns.net/PA/index.php"><span class="textlabel">Bream</span></a></li><li id="menu_kia"><a target="_blank" href="https://kia.cthq.net/"><span class="textlabel">CT KIA</span></a></li></ul></li>');
		$j('ul#menu').append('<div style="background:pink; color: black; text-align: center;padding: 5px; margin-top: 10px">Salaam Robleikum!</div>');

		var pageHtml = $j('html').html();

		parseScanLinks(pageHtml);

		if(typeof w.PA != 'undefined' && 'page' in w.PA) { page = w.PA.page; }
		if(typeof w.PA != 'undefined' && 'last_tick' in w.PA) { tick = w.PA.last_tick; }

		if(page == 'galaxy_status') {
				parseGalStatus(pageHtml);
		}
		else if(page == 'alliance_defence') {
			initAutoBattleCalcLink();
		}
		else if(page == 'bcalc') {
            initBattleCalcData();
        }
	}

	function initAutoBattleCalcLink() {
		$j('.ally_def_incs_wrapper').each(function() {
			var that = this;
			var linkDiv = $j(this).find('.ally_def_target_links');
			var bcLink = document.createElement("div");
			bcLink.innerHTML = 'AC';
			bcLink.onclick = function() {
				generateBattleCalcData(that);
			};

			$j(linkDiv).after(bcLink);
		});
	}

	function getBaseFleet(userId) {
		var baseShips = [];
		$j.ajax({
			url: "https://game.planetarion.com/alliance_fleets.pl?view=free&member=" + userId,
			type: 'get',
			dataType: 'html',
			async: false,
			success: function(data) {
				$j(data).find('.maintext table:nth-of-type(2) td.left').each(function() {
					var $shipPANumber = $j(this).find('a').attr('class').match(/(\d+)/g)[0],
						$shipCount = $j(this).next().html();
					baseShips.push({
						type: $shipPANumber,
						count: $shipCount.replace(/,/g, '')
					});
				});
			}
		});
		return baseShips;
	}
	function getDefenceFleet(id) {
		var defShips = [];
		var fleet = w.PA_dships[id];
		$j.each(fleet, function(ind, ship) {
			defShips.push({
					type: ships[ship.name],
					count: ship.count
			});
		});
		return defShips;
	}
	function getAttackFleet(url) {
		var attShips = [];
		$j.ajax({
			url: "https://game.planetarion.com/" + url,
			type: 'get',
			dataType: 'html',
			async: false,
			success: function(data) {
				$j(data).find('table tr').each(function(dt) {
					var $attShipName = $j(this).find('td:nth-of-type(1)').html();
					if($attShipName !== undefined) {
						var $attShipCount = $j(this).find('td:nth-of-type(2)').html().replace(',', ''),
								$attShipNumber = ships[$attShipName];
						attShips.push({
								type: $attShipNumber,
								count: $attShipCount.replace(',', '')
						});
					}
				});
			}
		});
		return attShips;
	}

	function initBattleCalcData() {
        var defFleetCounter = 1;

        var autobcalc = localStorage.getItem('autobcalc');
        if(autobcalc !== null && autobcalc !== undefined) {
            autobcalc = JSON.parse(autobcalc);
            $j('#comment').val('Calc auto generated. Remember to trim attack fleets!');
            $j.each(autobcalc.def_fleets, function(ind, fleet){
                addFleet('def', fleet, defFleetCounter);
                defFleetCounter++;
            });
            var attFleetCounter = 1;
            $j.each(autobcalc.att_fleets, function(ind, fleet){
                addFleet('att', fleet, attFleetCounter);
                attFleetCounter++;
            });
        }
        else {
            console.log('nothing to calc');
        }
        localStorage.removeItem("autobcalc");
	}

	function generateBattleCalcData(obj) {
		var autobcalc = {
				def_fleets: [],
				att_fleets: [],
		};
		var eta = $j(obj).closest('.ally_def_eta_wrapper').data('eta');

		//Get base fleet
		var userDetail = $j(obj).find('div.ally_def_target_coords').find('a').attr('href');
		var userId = $j(obj).data('inc').split('_')[0];
		var userCoords = $j(obj).find('div.ally_def_target_coords').text().split(' ')[0];
		var roids = $j(obj).find('.ally_def_target_mdata_roids').text();
		var planetX = userCoords.match(/(\d+)/g)[0],
		planetY = userCoords.match(/(\d+)/g)[1],
		planetZ = userCoords.match(/(\d+)/g)[2];
		autobcalc.def_fleets.push({
			x:planetX,
			y:planetY,
			z:planetZ,
			roids: roids,
			ships:getBaseFleet(userId)
		});
		//Get defence fleets
		$j(obj).find('.ally_def_defender.mission_defend').each(function(defind, defobj) {
			var fleetId = $j(defobj).find('div.ally_def_defender_fleetname a').attr('id').split('_')[1];
			var defCoords = $j(defobj).find('div.ally_def_defender_coords a').text();
			var defX = defCoords.match(/(\d+)/g)[0],
				defY = defCoords.match(/(\d+)/g)[1],
				defZ = defCoords.match(/(\d+)/g)[2];
			var race = races[$j(defobj).find('.ally_def_defender_race').text()];
			autobcalc.def_fleets.push({
				x:defX,
				y:defY,
				z:defZ,
				roids: 0,
				race: race,
				ships:getDefenceFleet(fleetId)
			});
		});
		//Get attacker fleets
		$j(obj).find('.ally_def_attacker.mission_attack').each(function(attind, attobj) {
			var attCoords = $j(attobj).find('div.ally_def_attacker_coords a').text();
			var attX = attCoords.match(/(\d+)/g)[0],
				attY = attCoords.match(/(\d+)/g)[1],
				attZ = attCoords.match(/(\d+)/g)[2];
			var race = races[$j(attobj).find('.ally_def_attacker_race').text()];
			var scanId = $j(attobj).find('div.ally_def_attacker_links a:contains("I")').attr('href');
			if(scanId === undefined) {
				console.log('undefined');
				scanId = $j(attobj).find('div.ally_def_attacker_links a:contains("A")').attr('href');
			}
			if(scanId === undefined) {
				scanId = $j(attobj).find('div.ally_def_attacker_links a:contains("U")').attr('href');
			}
			if(scanId !== undefined) {
				autobcalc.att_fleets.push({
					x:attX,
					y:attY,
					z:attZ,
					roids: 0,
					race: race,
					ships:getAttackFleet(scanId)
				});
			}
		});
		localStorage.setItem("autobcalc", JSON.stringify(autobcalc));
		window.open('bcalc.pl?rn=' + Math.random(),'_blank');
	}
	function addFleet(type, fleet, fleetCounter) {
		if(fleetCounter !== 1) {
				w.add_fleet(type);
		}
		else {
				if(type === 'def') {
						$j('#def_asteroids').val(fleet.roids)
				}
		}
		$j('#' + type + '_coords_x_' + fleetCounter).val(fleet.x)
		$j('#' + type + '_coords_y_' + fleetCounter).val(fleet.y)
		$j('#' + type + '_coords_z_' + fleetCounter).val(fleet.z)
		$j('#' + type + '_planet_value_' + fleetCounter).val(fleet.value);
		$j('#' + type + '_planet_score_' + fleetCounter).val(fleet.score);
		$j('#' + type + '_' + fleetCounter + '_race').val(fleet.race);
		$j.each(fleet.ships, function(shipind, ship) {
				$j('#' + type + '_' + fleetCounter + '_' + ship.type).val(ship.count);
				$j('#' + type + '_row_' + ship.type).css("display", "");
		});
	}

	function parseScanLinks(page)
	{
		var regex = new RegExp(/showscan.pl[?]scan_id=([a-zA-Z0-9]*)/g);
		var mtc = [];
		var match = [];
		while( (match = regex.exec( page )) != null ) {
				mtc.push(match[1]);
		}

		var xhr = new XMLHttpRequest();
		xhr.open("POST", toolsUrl + '/api/v1/collector/scans', true);
		xhr.setRequestHeader('Content-Type', 'application/json');
		xhr.send(JSON.stringify({
				ids: mtc
		}));
	}

	function parseGalStatus(page)
	{
		var fleets = [];

		$j('#galaxy_status_incoming .mission_attack').each(function() {
			var fleet = $(this).html().replace(/(\r\n|\n|\r|\t)/gm, "");
			var regex = new RegExp(/<td[^>]*><b>(\d+)\:(\d+)\:(\d+).*<\/b>[*]?<\/td><td[^>]*><a[^>]*>(\d+)\:(\d+)\:(\d+)<\/a><\/td><td[^>]*>A<\/td><td[^>]*>([\w ]+)<\/td><td[^>]*>[a-zA-Z]*<\/td><td[^>]*>(\d+)<\/td><td[^>]*>(\d+)<\/td>/g);
			var match = regex.exec( fleet );
			if(match) {
				fleets.push({
					to_x: match[1],
					to_y: match[2],
					to_z: match[3],
					from_x: match[4],
					from_y: match[5],
					from_z: match[6],
					fleet: match[7],
					ships: match[8],
					eta: match[9],
					tick: tick,
					type: 'Attack'
				});
			}
		});

		$j('#galaxy_status_incoming .mission_defend').each(function() {
			var fleet = $(this).html().replace(/(\r\n|\n|\r|\t)/gm, "");
			var regex = new RegExp(/<td[^>]*><b>(\d+)\:(\d+)\:(\d+).*<\/b>[*]?<\/td><td[^>]*><a[^>]*>(\d+)\:(\d+)\:(\d+)<\/a><\/td><td[^>]*>D<\/td><td[^>]*>([\w ]+)<\/td><td[^>]*>[a-zA-Z]*<\/td><td[^>]*>(\d+)<\/td><td[^>]*>(\d+)<\/td>/g);
			var match = regex.exec( fleet );
			if(match) {
				fleets.push({
					to_x: match[1],
					to_y: match[2],
					to_z: match[3],
					from_x: match[4],
					from_y: match[5],
					from_z: match[6],
					fleet: match[7],
					ships: match[8],
					eta: match[9],
					tick: tick,
					type: 'Defend'
				});
			}
		});

		$j('#galaxy_status_outgoing .mission_attack').each(function() {
			var fleet = $(this).html().replace(/(\r\n|\n|\r|\t)/gm, "");
			var regex = new RegExp(/<td[^>]*><a[^>]*>(\d+)\:(\d+)\:(\d+)<\/a><\/td><td[^>]*>(\d+)\:(\d+)\:(\d+).*<\/td><td[^>]*>A<\/td><td[^>]*>([\w ]+)<\/td><td[^>]*>(\d+)<\/td><td[^>]*>(\d+)<\/td>/g);
			var match = regex.exec( fleet );
			if(match) {
				fleets.push({
					to_x: match[1],
					to_y: match[2],
					to_z: match[3],
					from_x: match[4],
					from_y: match[5],
					from_z: match[6],
					fleet: match[7],
					ships: match[8],
					eta: match[9],
					tick: tick,
					type: 'Attack'
				});
			}
		});

		$j('#galaxy_status_outgoing .mission_defend').each(function() {
			var fleet = $(this).html().replace(/(\r\n|\n|\r|\t)/gm, "");
			var regex = new RegExp(/<td[^>]*><a[^>]*>(\d+)\:(\d+)\:(\d+)<\/a><\/td><td[^>]*>(\d+)\:(\d+)\:(\d+).*<\/td><td[^>]*>D<\/td><td[^>]*>([\w ]+)<\/td><td[^>]*>(\d+)<\/td><td[^>]*>(\d+)<\/td>/g);
			var match = regex.exec( fleet );
			if(match) {
				fleets.push({
					to_x: match[1],
					to_y: match[2],
					to_z: match[3],
					from_x: match[4],
					from_y: match[5],
					from_z: match[6],
					fleet: match[7],
					ships: match[8],
					eta: match[9],
					tick: tick,
					type: 'Defend'
				});
			}
		});

		if(fleets.length) {
			var xhr = new XMLHttpRequest();
			xhr.open("POST", toolsUrl + '/api/v1/collector/fleets', true);
			xhr.setRequestHeader('Content-Type', 'application/json');
			xhr.send(JSON.stringify(fleets));
		}	
	}

})(window);

function getUrlParameter(name) {
	name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
	var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
	var results = regex.exec(location.search);
	return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
}
