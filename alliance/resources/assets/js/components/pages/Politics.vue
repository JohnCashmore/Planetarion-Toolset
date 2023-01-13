<template>
	<main class="my-3">
		<div class="row justify-content-center">
			<div class="col-md-12">

				<div class="card card-default mb-3 has-table" v-if="politics">
					<div class="card-header">
						Politics<br/>
						<small>An overview of the political stance of Pink Fluffy Unicorns for other alliances</small>
					</div>

					<div class="card-body">
						<preloader :loading.sync="loading"></preloader>
						<table id="politics" class="table table-striped table-bordered" style="width:100%" v-if="!loading">
							<thead>
								<tr>
									<th rowspan="5">Alliance</th>
									<th rowspan="3">Status</th>
									<th rowspan="2">Max Planets</th>
									<th rowspan="2">Max Waves</th>
									<th rowspan="2">Max Fleets</th>
									<th rowspan="2"></th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="(politics, index) in politics">
									<td>{{ politics.allianceName }}</td>
									<td>{{ politics.status }}</td>
									<td>{{ politics.maxPlanets }}</td>
									<td>{{ politics.maxWaves }}</td>	
									<td>{{ politics.maxFleets }}</td>
									<td><a v-on:click="removeAgreement(politics.id)" class="btn btn-sm btn-danger" title="Remove Agreement"><i class="fa fa-error fa-minus-circle"></i></a></td>									
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="row justify-content-center my-3">
			<div class="col-md-12">
				<div class="card card-default mb-3 has-table" v-if="politics && (settings.role == 'Admin' || settings.role == 'BC')">
					<div class="card-header">
						Manage Politics<br/>
						<small>Set alliance relational status for any other alliance in the game</small>
					</div>
					<div class="card-body" style="padding:12px 17px 14px;">
						<preloader :loading.sync="loadingSettings"></preloader>
						<div v-if="!loadingSettings">
							<form @submit.prevent="allianceRelation">
								<div class="form-group">
									<label for="name">Alliance</label><br>
									<select v-model="settings.alliance" id="allianceRelation">
										<option v-for="option in alliances.data" v-bind:value="option.id">
										{{ option.name }}
										</option>
									</select>
									<select name="allianceStatus" id="allianceStatus" v-on:change="allianceStatus(this.value)">
										<option value="none">No Deal</option>
										<option value="nap">Full NAP</option>
										<option value="deal">Deal</option>
									</select>
									<select name="maxPlanets" id="maxPlanets">
										<option value="1">Max 1 Planets</option>
										<option value="2">Max 2 Planets</option>
										<option value="3">Max 3 Planets</option>
										<option value="4">Max 4 Planets</option>
										<option value="5">Max 5 Planets</option>
										<option value="6">Max 6 Planets</option>
										<option value="7">Max 7 Planets</option>
									</select>
									<select name="maxWaves" id="maxWaves">
										<option value="1">Max 1 Waves</option>
										<option value="2">Max 2 Waves</option>
										<option value="3">Max 3 Waves</option>
										<option value="4">Max 4 Waves</option>
										<option value="5">Max 5 Waves</option>
										<option value="6">Max 6 Waves</option>
										<option value="7">Max 7 Waves</option>
									</select>
									<select name="maxFleets" id="maxFleets">
										<option value="1">Max 1 Fleets</option>
										<option value="2">Max 2 Fleets</option>
										<option value="3">Max 3 Fleets</option>
										<option value="4">Max 4 Fleets</option>
										<option value="5">Max 5 Fleets</option>
										<option value="6">Max 6 Fleets</option>
										<option value="7">Max 7 Fleets</option>
									</select>
									<br />
									<button type="submit" class="btn btn-primary" style="margin-top:12px">Save</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>
</template>
<style>
	select#maxPlanets, select#maxWaves, select#maxFleets {display:none;}
</style>
<script>
	export default {
		props: ['settings'],
		data() {
			return {
				'politics': {},
				'name': '',
				 'alliances': {},
				'loading': true,
				loadingAlliances: true,
				loadingSettings: true
			};
		},
		methods: {
			loadPolitics: function() {
				this.loading = true;
				axios.get('api/v1/politics', {
					params: {
						
					}
				}).then((response) => {
					this.politics = response.data;
					this.loading = false;
				});
			},
			handleSubmit() {
				axios.post('api/v1/politics', {
					name: this.name
				}).then((response) => {
					this.loadPolitics();
					this.$notify({
						group: 'foo',
						title: 'Success',
						text: 'Successfully updated political status',
						type: 'success'
					});
					this.name = '';
				});
			},
			removeAgreement: function(id) {
				if(confirm("Are you sure?")) {
					axios.get('api/v1/politics/remove/' + id)
					.then((response) => {
						this.politics = response.data;
						this.$notify({
						  group: 'foo',
						  title: 'Success',
						  text: 'Political status removed',
						  type: 'success'
						});
						this.loadPolitics();
						
					});
				}

			},
			allianceStatus: function(allianceStatus) {
				console.log(this, allianceStatus);
				if (document.getElementById('allianceStatus').value == 'deal') {
					document.getElementById('maxPlanets').style.display = 'inline';
					document.getElementById('maxWaves').style.display = 'inline';
					document.getElementById('maxFleets').style.display = 'inline';
				} else {
					document.getElementById('maxPlanets').style.display = 'none';
					document.getElementById('maxWaves').style.display = 'none';
					document.getElementById('maxFleets').style.display = 'none';
				}
			},
			allianceRelation: function(allianceRelation) {
				allianceStatus = document.getElementById('allianceStatus').value;
				maxPlanets = document.getElementById('maxPlanets').value;
				maxWaves = document.getElementById('maxWaves').value;
				maxFleets = document.getElementById('maxFleets').value;
				allianceRelation = document.getElementById('allianceRelation').value;
				axios.get('api/v1/politics/' + allianceRelation + '/' + allianceStatus + '/' + maxPlanets + '/' + maxWaves + '/' + maxFleets, {
					name: this.name
				}).then((response) => {
					this.$notify({
						group: 'foo',
						title: 'Success',
						text: 'Relation saved!',
						type: 'success'
					});
					this.name = '';
					this.loadPolitics();
				});
			},
			loadAlliances: function() {
				axios.get('api/v1/alliances', {
					params: {
						sort: "+name",
						perPage: 999
					}
				})
				.then((response) => {
					this.alliances = response.data;
					this.loadingAlliances = false;
				});
			},
			loadSettings: function() {
				axios.get('api/v1/admin')
				.then((response) => {
					this.settings = response.data;
					this.loadingSettings = false;
				});
			}
		},
		watch: {
		   'sort': 'loadPolitics',
		},
		mounted() {
			this.loadSettings();
			this.loadPolitics();
			this.loadAlliances();
		},
	}
</script>
