<template>
	 <main class="my-3">
		<div class="card card-default mb-3">
			<div class="card-header">{{ battlegroup.name }} Battlegroup Fleets For {{ battlegroup.username }}</div>
			
			<div class="latestA" style="text-align:center;padding-top:20px;">
				<a href="#" id="auscan" target="_blank">Latest AU Scan</a>
			</div>
			
		   	<div class="fleet" v-for="(fleet, index) in fleets" style="padding-top:18px;">
				<h4>Fleet {{ index }}</h4>
				<preloader :loading.sync="loading"></preloader>
				<table id="members" class="table table-striped table-bordered" style="width:100%" v-if="!loading">
					<thead>
						<tr>
							<th class="text-center">Ship</th>
							<th class="text-center">Amount</th>
							<th class="text-center">Race</th>
							<th class="text-center">Class</th>
							<th class="text-center">t1</th>
							<th class="text-center">t2</th>
							<th class="text-center">t3</th>
							<th class="text-center">Init</th>
							<th class="text-center">Guns</th>
							<th class="text-center">Armor</th>
							<th class="text-center">Damage</th>
							<th class="text-center">Empres</th>
							<th class="text-center">Total Cost</th>
							<th class="text-center"></th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="ship in fleet">
							<th class="text-center"><span class="race" v-bind:class="ship.ship.race">{{ ship.ship.name }}</span></th>
							<th class="text-center"><input type="text" name="amount" :value="ship.amount" :id="index + '_' + ship.ship.id" /> <a v-on:click="editShip(index, ship.ship.id)" class="btn btn-info btn-sm" title="change ship in fleet"><i class="fa fa-plus-circle"></i></a></th>
							<th class="text-center"><span class="race" v-bind:class="ship.ship.race">{{ ship.ship.race }}</span></th>
							<th class="text-center"><span class="race" v-bind:class="ship.ship.race">{{ ship.ship.class }}</span></th>
							<th class="text-center"><span class="race" v-bind:class="ship.ship.race">{{ ship.ship.t1 }}</span></th>
							<th class="text-center"><span class="race" v-bind:class="ship.ship.race">{{ ship.ship.t2 }}</span></th>
							<th class="text-center"><span class="race" v-bind:class="ship.ship.race">{{ ship.ship.t3 }}</span></th>
							<th class="text-center"><span class="race" v-bind:class="ship.ship.race">{{ ship.ship.init }}</span></th>
							<th class="text-center"><span class="race" v-bind:class="ship.ship.race">{{ ship.ship.guns }}</span></th>
							<th class="text-center"><span class="race" v-bind:class="ship.ship.race">{{ ship.ship.armor }}</span></th>
							<th class="text-center"><span class="race" v-bind:class="ship.ship.race">{{ ship.ship.damage }}</span></th>
							<th class="text-center"><span class="race" v-bind:class="ship.ship.race">{{ ship.ship.empres }}</span></th>
							<th class="text-center"><span class="race" v-bind:class="ship.ship.race">{{ ship.ship.total_cost }}</span></th>
							<th class="text-center"><a v-on:click="deleteShip(index, ship.ship.id)" class="btn btn-info btn-sm" title="remove ship from fleet"><i class="fa fa- fa-minus-circle"></i></a></th>
							
						</tr>
					   
					</tbody>
				</table>
				
			</div>	
			<form @submit.prevent="addShipToFleet">
				<div style="text-align:center;width:100%;padding-top:20px;">
					<h5>Add Fleets</h5>
					<select name="ship" id="ship">
						<option :value="ship.id" v-for="ship in ships">{{ ship.name }}</option>
					</select>
					<select name="fleet" id="fleet">
						<option value="1">Fleet 1</option>
						<option value="2">Fleet 2</option>
						<option value="3">Fleet 3</option>
					</select>
					<input type="text" name="amount" value="" id="amount" placeholder="Amount.." />
					<a v-on:click="addShipToFleet()" class="btn btn-info btn-sm" title="add ship to fleet"><i class="fa fa- fa-plus-circle"></i></a>
				</div>
			</form>
		</div>
	</main>
</template>

<script>
	export default {
		props: ['id', 'user_id'],
		data() {
			return {
				loading: true,
				battlegroup: {},
				auscan: {},
				fleets: {},
				ships: {
				  ships: {}
				}
			};
		},
		methods: {
			loadShips: function() {
				this.loading = true;
				axios.get('api/v1/battlegroup/' + this.id + '/fleets/' + this.user_id)
				.then((response) => {
					this.fleets = response.data.fleets;
					this.battlegroup = response.data;
					this.ships = response.data.ships;
					if (response.data.auscan && response.data.auscan.pa_id && response.data.auscan.pa_id.length > 0)
						document.getElementById('auscan').href = 'https://game.planetarion.com/showscan.pl?scan_id=' + response.data.auscan.pa_id;
					else 
						document.getElementById('auscan').style.display = 'none'
					this.loading = false;
				});
			},
			saveShips: function () {
				this.loadShips();
			},
			editShip: function (fleetId, shipId) {

				// get value
				this.shipAmount = document.getElementById(fleetId + '_' + shipId).value;
				
				// update appropriately 
				if (!isNaN(this.shipAmount)) {
					
					// add ship
					axios.get('api/v1/battlegroup/' + this.id + '/fleets/' + this.user_id + '/edit/' + fleetId + '/' + shipId + '/' + this.shipAmount).then((response) => {

						// load changes
						this.loadShips();
					});
				} else {
					alert('Please provide a number!');
				}
			},
			addShipToFleet: function () {
				
				// fetch ship and amount
				this.ship = document.getElementById('ship');
				this.shipId = this.ship.options[this.ship.selectedIndex].value
				this.fleet = document.getElementById('fleet');
				this.fleetId = this.fleet.options[this.fleet.selectedIndex].value;
				this.shipAmount = document.getElementById('amount').value
				
				if (!isNaN(this.shipAmount)) {
					
					// add ship
					axios.get('api/v1/battlegroup/' + this.id + '/fleets/' + this.user_id + '/add/' + this.fleetId + '/' + this.shipId + '/' + this.shipAmount).then((response) => {

						// load changes
						this.loadShips();
					});
				} else {
					alert('Please provide a number!');
				}
				
				
			},
			deleteShip: function (fleetId, shipId) {

				// delete ship
				axios.get('api/v1/battlegroup/' + this.id + '/fleets/' + this.user_id + '/delete/' + fleetId + '/' + shipId).then((response) => {

					// load changes
					this.loadShips();
				});
			},
			
		},
		watch: {
		   '$route': 'loadShips'
		},
		mounted() {
			this.loadShips();
		}
	}
</script>
