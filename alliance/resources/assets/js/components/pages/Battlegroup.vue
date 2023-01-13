<template>
	<main class="my-3">
		<div class="row justify-content-center">
			<div class="col-md-12">

				<div class="card card-default mb-3 has-table" v-if="battlegroup">
					<div class="card-header">
						Battlegroup: {{ battlegroup.name }}
					</div>

					<div class="card-body">

					</div>
				</div>

				<div class="card card-default mb-3 has-table" v-if="battlegroup.members_pending.length">
					<div class="card-header">
						Pending members<br/>
						<small>Only the owner or admins can accept or decline pending members.</small>
					</div>

					<div class="card-body">
						<table id="members" class="table table-striped table-bordered" style="width:100%">
						  <thead>
							  <tr>
								  <th>Nick</th>
								  <th class="align-bottom text-center" rowspan="2">Race</th>
								  <th class="align-bottom text-center" rowspan="2">Size</th>
								  <th class="align-bottom text-center" rowspan="2">Value</th>
								  <th class="align-bottom text-center" rowspan="2">Score</th>
								  <th class="align-bottom text-center" rowspan="2">Xp</th>
								  <th class="text-center" colspan="3">Growth</th>
								  <th>Options</th>
							  </tr>
						  </thead>
						  <tbody>
							  <tr v-for="(member, index) in battlegroup.members_pending">
								  <td style="width: 100%">{{ member.name }}</td>
								  <td><span class="race" v-bind:class="member.planet.race">{{ member.planet.race }}</span></td>
								  <td class="text-right"><span v-tooltip:top="member.planet.size.toLocaleString()">{{ member.planet.size | shorten }}</span></td>
								  <td class="text-right" v-bind:class="{ basher: member.planet.basher }"><span v-tooltip:top="member.planet.value.toLocaleString()">{{ member.planet.value | shorten }}</span></td>
								  <td class="text-right" v-bind:class="{ basher: member.planet.basher }"><span v-tooltip:top="member.planet.score.toLocaleString()">{{ member.planet.score | shorten }}</span></td>
								  <td class="text-right"><span v-tooltip:top="member.planet.xp.toLocaleString()">{{ member.planet.xp | shorten }}</span></td>
								  <td class="options">
									  <span v-if="battlegroup.is_owner || settings.role == 'Admin'">
										  <span><button v-on:click="accept(member.id)" class="btn btn-sm btn-success">accept</button></span>
										  <span><button v-on:click="decline(member.id)" class="btn btn-sm btn-danger">decline</button></span>
									  </span>
								  </td>
							  </tr>
						  </tbody>
						</table>
					</div>
				</div>

				<div class="card card-default mb-3 has-table" v-if="battlegroup.members">
					<div class="card-header">
						Members<br/>
						<small>Welcome to {{ battlegroup.name }}</small>
					</div>

					<div class="card-body">
						<table id="members" class="table table-striped table-bordered" style="width:100%">
						  <thead>
							  <tr>
								<th rowspan="2">Nick</th>
								<th class="align-bottom text-center" rowspan="2">Race</th>
								<th class="align-bottom text-center" rowspan="2">Size</th>
								<th class="align-bottom text-center" rowspan="2">Value</th>
								<th class="align-bottom text-center" rowspan="2">Score</th>
								<th class="align-bottom text-center" rowspan="2">Xp</th>
								<th class="text-center" colspan="3">Growth</th>
								<th rowspan="2" class="align-bottom text-center">Fleets</th>
								<th rowspan="2">Options</th>
							  </tr>
							  <tr>
								  <th class="text-center">Size</th>
								  <th class="text-center">Value</th>
								  <th class="text-center">Score</th>
							  </tr>
						  </thead>
						  <tbody>
							  
							  <tr v-for="(member, index) in battlegroup.members">
								  <td style="width: 100%">{{ member.name }}</td>
								  <td><span class="race" v-bind:class="member.planet.race">{{ member.planet.race }}</span></td>
								  <td class="text-right"><span v-tooltip:top="member.planet.size.toLocaleString()">{{ member.planet.size | shorten }}</span></td>
								  <td class="text-right" v-bind:class="{ basher: member.planet.basher }"><span v-tooltip:top="member.planet.value.toLocaleString()">{{ member.planet.value | shorten }}</span></td>
								  <td class="text-right" v-bind:class="{ basher: member.planet.basher }"><span v-tooltip:top="member.planet.score.toLocaleString()">{{ member.planet.score | shorten }}</span></td>
								  <td class="text-right"><span v-tooltip:top="member.planet.xp.toLocaleString()">{{ member.planet.xp | shorten }}</span></td>
								  <td class="text-right" v-bind:class="{ yellow: member.planet.growth_percentage_size == 0.0, green: member.planet.growth_percentage_size > 0.0, red: member.planet.growth_percentage_size < 0.0 }"><span v-tooltip:top="member.planet.growth_size.toLocaleString()">{{ member.planet.growth_percentage_size | shorten }}%</span></td>
								  <td class="text-right" v-bind:class="{ yellow: member.planet.growth_percentage_value == 0.0, green: member.planet.growth_percentage_value > 0.0, red: member.planet.growth_percentage_value < 0.0 }"><span v-tooltip:top="member.planet.growth_value.toLocaleString()">{{ member.planet.growth_percentage_value | shorten }}%</span></td>
								  <td class="text-right" v-bind:class="{ yellow: member.planet.growth_percentage_score == 0.0, green: member.planet.growth_percentage_score > 0.0, red: member.planet.growth_percentage_score < 0.0 }"><span v-tooltip:top="member.planet.growth_score.toLocaleString()">{{ member.planet.growth_percentage_score | shorten }}%</span></td>
								  <td class="text-right"><a v-on:click="viewFleet(battlegroup.id, member.id)" class="btn btn-info btn-sm" title="view fleets"><i class="fa fa- fa-space-shuttle"></i></a></td>
								  <td class="options">
									  <span v-if="battlegroup.is_owner || settings.role == 'Admin' || settings.role == 'BC'">
										  <span><button v-on:click="remove(member.id)" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button></span>
									  </span>
								  </td>
							  </tr>
							 
						  </tbody>
						</table>
					</div>
				</div>
			  				
				<div class="card card-default mb-3">
				   	<div class="fleet" v-for="(teamup,index) in teamups">
						<div class="card-header">
							{{ teamup.name }} &nbsp; 
							<span style="display:none" :id="'teamupNameEdit' + index"> - 
								<input type="text" name="name" :value="teamup.name" :id="'team' + index" /> 
								<a v-on:click="teamupName(teamup.name, index)" class="btn btn-info btn-sm" title="change teamup name"><i class="fa fa- fa-edit"></i></a>
							</span>
							<a v-on:click="showTeamupNameEdit(teamup.name, index)" class="btn btn-info btn-sm" title="change teamup name" :id="'showTeamupNameEdit' + index"><i class="fa fa- fa-edit"></i></a>
						</div>
						<table id="teamups" class="table table-striped table-bordered" style="width:100%">
							<thead>
								<tr>
									<th class="text-center">Name</th>
									<th class="text-center">Co-ords</th>
									<th class="text-center">Fleet</th>
									<th class="text-center">Class</th>
									<th class="text-center">Score</th>
									<th class="text-center">Value</th>
									<th class="text-center"></th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="(fleet,id) in teamup.fleets">
									<th class="text-center"><span class="race" v-bind:class="fleet.planet.race">{{ fleet.user.name }}</span></th>
									<th class="text-center"><span class="race" v-bind:class="fleet.planet.race">{{ fleet.planet.x }}:{{ fleet.planet.y }}:{{ fleet.planet.z }}</span></th>
									<th class="text-center"><span class="race" v-bind:class="fleet.planet.race">{{ fleet.fleetLine }}</span></th>
									<th class="text-center"><span class="race" v-bind:class="fleet.planet.race">{{ fleet.planet.race }}</span></th>
									<th class="text-center"><span class="race" v-bind:class="fleet.planet.race">{{ fleet.planet.score }}</span></th>
									<th class="text-center"><span class="race" v-bind:class="fleet.planet.race">{{ fleet.planet.value }}</span></th>
									<th class="text-center"><a v-on:click="removeTeamupMember(fleet.teamup_id, fleet.user.name, fleet.fleet_id)" class="btn btn-info btn-sm" title="remove from teamup"><i class="fa fa- fa-minus-circle"></i></a></th>
					
								</tr>
			   
							</tbody>
						</table>
					</div>
				</div>
				
				<form @submit.prevent="addTeamup">
					<div style="color:white;width:100%;">
						<div class="card-header">
							Idle Fleets
						</div>
						<table id="teamups" class="table table-striped table-bordered" style="width:100%">
							<thead>
								<tr>
									<th class="text-center">Name</th>
									<th class="text-center">Co-ords</th>
									<th class="text-center">Fleet</th>
									<th class="text-center"></th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="(line,index) in fleets">
									<td class="text-center"><span class="race" v-bind:class="line.planet.race">{{ line.user.name }}</span></td>
									<td class="text-center"><span class="race" v-bind:class="line.planet.race">{{ line.planet.x }}:{{ line.planet.y }}:{{ line.planet.z }}</span></td>
									<td class="text-center"><span class="race" v-bind:class="line.planet.race">{{ line.fleet_line }}</span></td>
									<td class="text-center">
										
										<select name="addTeamupMember" :id="line.user.name + '-' + line.fleet_id">
											<option :value="team.teamup_id" v-for="team in teams">{{ team.name }}</option>
											<option value="new">New Team</option>
										</select>
										<a v-on:click="addTeamupMember(index, line.user.name, line.fleet_id)" class="btn btn-info btn-sm" title="add to teamup"><i class="fa fa- fa-plus-circle"></i></a></td>
					
								</tr>
			   
							</tbody>
						</table>
					</div>
				</form>
				
				
				
				<div class="card card-default mb-3" v-if="battlegroup.non_members.length && (battlegroup.is_owner || settings.role == 'Admin' || settings.role == 'BC')">
					<div class="card-header">
						Add members<br/>
						<small>You can only add members who have set their planet.</small>
					</div>

					<div class="card-body">
						<form @submit.prevent="addMember">
							<select v-model="user">
								<option v-for="member in battlegroup.non_members" v-bind:value="member.id">
								  {{ member.name }}
								</option>
							</select>
							<button type="submit" class="btn btn-primary">Add</button>
						</form>
					</div>
				</div>

			</div>
		</div>
	</main>
</template>

<script>
	export default {
		props: ['settings'],
		data() {
			return {
				loading: true,
				'battlegroup': {
					'members': {},
					'members_pending': {},
					'non_members': {}
				},
				'user': '',
				'teamups': {},
				'fleets': {},
				'teams': {}
			};
		},
		methods: {
			loadBattlegroup: function() {
				axios.get('api/v1/battlegroup/' + this.$route.params.id)
				.then((response) => {
					this.battlegroup = response.data;
				});
			},
			loadTeamups: function() {
				axios.get('api/v1/battlegroup/' + this.$route.params.id + '/teamups')
				.then((response) => {
					this.teamups = response.data;
				});
			},
			loadFleets: function() {
				axios.get('api/v1/battlegroup/' + this.$route.params.id + '/teamups/available-fleets')
				.then((response) => {
					this.fleets = response.data;
				});
			},
			loadTeams: function() {
				axios.get('api/v1/battlegroup/' + this.$route.params.id + '/teams')
				.then((response) => {
					this.teams = response.data;
				});
			},
			accept: function(id) {
				axios.get('api/v1/battlegroup/' + this.$route.params.id + '/user/' + id + '/accept')
				.then((response) => {
					this.battlegroup = response.data;
				});
			},
			decline: function(id) {
				axios.get('api/v1/battlegroup/' + this.$route.params.id + '/user/' + id + '/decline')
				.then((response) => {
					this.battlegroup = response.data;
				});
			},
			remove: function(id) {
				axios.get('api/v1/battlegroup/' + this.$route.params.id + '/user/' + id + '/remove')
				.then((response) => {
					this.battlegroup = response.data;
					this.loadBattlegroup();
					this.loadFleets();
					this.loadTeamups();
					this.loadTeams();
				});
			},
			addMember() {
				axios.post('api/v1/battlegroup/' + this.$route.params.id + '/user', {
					user: this.user
				}).then((response) => {
					this.battlegroup = response.data;
					this.loadTeamups();
					this.loadFleets();
					this.loadTeams();
					this.$notify({
						group: 'foo',
						title: 'Success',
						text: 'User added',
						type: 'success'
					});
				});
			},
			addTeamupMember(teamup, user, fleet) {
				
				this.teamupElement = document.getElementById(user + '-' + fleet);
				if (this.teamupElement) {
					this.teamupName = this.teamupElement.options[this.teamupElement.selectedIndex].innerHTML;
					this.teamupId = this.teamupElement.options[this.teamupElement.selectedIndex].value;
				} else {
					this.teamupId = 'new';
					this.teamupName = 'new';
				}
				axios.get('api/v1/battlegroup/' + this.$route.params.id + '/teamups/' + this.teamupId + '/' + user + '/add/' + fleet + '/' + this.teamupName)
				.then((response) => {
					this.loadTeamups();
					this.loadFleets();
					this.loadTeams();
					this.$notify({
						group: 'foo',
						title: 'Success',
						text: 'User added',
						type: 'success'
					});
				});
			},
			removeTeamupMember: function(teamupId, username, fleetId) {
				axios.get('api/v1/battlegroup/' + this.$route.params.id + '/teamups/' + teamupId + '/' + username + '/remove/' + fleetId)
				.then((response) => {
					this.loadTeamups();
					this.loadFleets();
					this.loadTeams();
					this.$notify({
						group: 'foo',
						title: 'Success',
						text: 'User removed',
						type: 'success'
					});
				});
			},
		  	teamupName: function (teamName, teamup_id) {
				this.teamup_name = document.getElementById('team' + teamup_id).value;
				axios.get('api/v1/battlegroup/' + this.$route.params.id + '/teamups/' + teamup_id + '/name/' + this.teamup_name)
				.then((response) => {
					this.loadTeamups();
					this.loadFleets();
					this.loadTeams();
					document.getElementById('showTeamupNameEdit' + teamup_id).style.display = 'inline';
					document.getElementById('teamupNameEdit' + teamup_id).style.display = 'none';
					this.$notify({
						group: 'foo',
						title: 'Success',
						text: 'Change successful',
						type: 'success'
					});
				});
		  	},
			showTeamupNameEdit: function (index, teamup_id) {
				document.getElementById('showTeamupNameEdit' + teamup_id).style.display = 'none';
				document.getElementById('teamupNameEdit' + teamup_id).style.display = 'inline';
			},
			viewFleet(battlegroupId, userId) {
				window.location =  '#/battlegroups/' + battlegroupId + '/fleets/' + userId
			}
		},
		mounted() {
			this.loadBattlegroup();
			this.loadFleets();
			this.loadTeamups();
			this.loadTeams();
		},
	}
</script>
