<template>
	 <main class="my-3">
		<div class="card card-default mb-3" id="schedule">
			<div class="card"><h2>{{ user.name }} Schedule</h2></div>
			
			<table class="table table-striped table-bordered">
				<thead>
					<tr>
						<th scope="col"></th>
						<th scope="col">00:00</th>
						<th scope="col">01:00</th>
						<th scope="col">02:00</th>
						<th scope="col">03:00</th>
						<th scope="col">04:00</th>
						<th scope="col">05:00</th>
						<th scope="col">06:00</th>
						<th scope="col">07:00</th>
						<th scope="col">08:00</th>
						<th scope="col">09:00</th>
						<th scope="col">10:00</th>
						<th scope="col">11:00</th>
						<th scope="col">12:00</th>
						<th scope="col">13:00</th>
						<th scope="col">14:00</th>
						<th scope="col">15:00</th>
						<th scope="col">16:00</th>
						<th scope="col">17:00</th>
						<th scope="col">18:00</th>
						<th scope="col">19:00</th>
						<th scope="col">20:00</th>
						<th scope="col">21:00</th>
						<th scope="col">22:00</th>
						<th scope="col">23:00</th>
					</tr>
				</thead>
				<tbody>
					<th scope="row">Monday</th>
						<td class="allow" v-on:click="save('monday_00')"  id="monday_00"> </td>
						<td class="allow" v-on:click="save('monday_01')" id="monday_01" > </td>
						<td class="allow" v-on:click="save('monday_02')" id="monday_02" > </td>
						<td class="allow" v-on:click="save('monday_03')" id="monday_03" > </td>
						<td class="allow" v-on:click="save('monday_04')" id="monday_04" > </td>
						<td class="allow" v-on:click="save('monday_05')" id="monday_05" > </td>
						<td class="allow" v-on:click="save('monday_06')" id="monday_06" > </td>
						<td class="allow" v-on:click="save('monday_07')" id="monday_07" > </td>
						<td class="allow" v-on:click="save('monday_08')" id="monday_08" > </td>
						<td class="allow" v-on:click="save('monday_09')" id="monday_09" > </td>
						<td class="allow" v-on:click="save('monday_10')" id="monday_10" > </td>
						<td class="allow" v-on:click="save('monday_11')" id="monday_11"> </td>
						<td class="allow" v-on:click="save('monday_12')" id="monday_12" > </td>
						<td class="allow" v-on:click="save('monday_13')" id="monday_13" > </td>
						<td class="allow" v-on:click="save('monday_14')" id="monday_14" > </td>
						<td class="allow" v-on:click="save('monday_15')" id="monday_15" > </td>
						<td class="allow" v-on:click="save('monday_16')" id="monday_16" > </td>
						<td class="allow" v-on:click="save('monday_17')" id="monday_17" > </td>
						<td class="allow" v-on:click="save('monday_18')" id="monday_18" > </td>
						<td class="allow" v-on:click="save('monday_19')" id="monday_19" > </td>
						<td class="allow" v-on:click="save('monday_20')" id="monday_20" > </td>
						<td class="allow" v-on:click="save('monday_21')" id="monday_21" > </td>
						<td class="allow" v-on:click="save('monday_22')" id="monday_22" > </td>
						<td class="allow" v-on:click="save('monday_23')" id="monday_23" > </td>
					</tr>
					<tr>
						<th scope="row">Tuesday</th>
						<td class="allow" v-on:click="save('tuesday_00')" id="tuesday_00"> </td>
						<td class="allow" v-on:click="save('tuesday_01')" id="tuesday_01"> </td>
						<td class="allow" v-on:click="save('tuesday_02')" id="tuesday_02"> </td>
						<td class="allow" v-on:click="save('tuesday_03')" id="tuesday_03"> </td>
						<td class="allow" v-on:click="save('tuesday_04')" id="tuesday_04"> </td>
						<td class="allow" v-on:click="save('tuesday_05')" id="tuesday_05"> </td>
						<td class="allow" v-on:click="save('tuesday_06')" id="tuesday_06"> </td>
						<td class="allow" v-on:click="save('tuesday_07')" id="tuesday_07"> </td>
						<td class="allow" v-on:click="save('tuesday_08')" id="tuesday_08"> </td>
						<td class="allow" v-on:click="save('tuesday_09')" id="tuesday_09"> </td>
						<td class="allow" v-on:click="save('tuesday_10')" id="tuesday_10"> </td>
						<td class="allow"v-on:click="save('tuesday_11')" id="tuesday_11"> </td>
						<td class="allow" v-on:click="save('tuesday_12')" id="tuesday_12"> </td>
						<td class="allow" v-on:click="save('tuesday_13')" id="tuesday_13"> </td>
						<td class="allow" v-on:click="save('tuesday_14')" id="tuesday_14"> </td>
						<td class="allow" v-on:click="save('tuesday_15')" id="tuesday_15"> </td>
						<td class="allow" v-on:click="save('tuesday_16')" id="tuesday_16"> </td>
						<td class="allow" v-on:click="save('tuesday_17')" id="tuesday_17"> </td>
						<td class="allow" v-on:click="save('tuesday_18')" id="tuesday_18"> </td>
						<td class="allow" v-on:click="save('tuesday_19')" id="tuesday_19"> </td>
						<td class="allow" v-on:click="save('tuesday_20')" id="tuesday_20"> </td>
						<td class="allow" v-on:click="save('tuesday_21')" id="tuesday_21"> </td>
						<td class="allow" v-on:click="save('tuesday_22')" id="tuesday_22"> </td>
						<td class="allow" v-on:click="save('tuesday_23')" id="tuesday_23"> </td>
					</tr>
	
					<tr>
						<th scope="row">Wednesday</th>
						<td class="allow" v-on:click="save('wednesday_00')" id="wednesday_00"> </td>
						<td class="allow" v-on:click="save('wednesday_01')" id="wednesday_01"> </td>
						<td class="allow" v-on:click="save('wednesday_02')" id="wednesday_02"> </td>
						<td class="allow" v-on:click="save('wednesday_03')" id="wednesday_03"> </td>
						<td class="allow" v-on:click="save('wednesday_04')" id="wednesday_04"> </td>
						<td class="allow" v-on:click="save('wednesday_05')" id="wednesday_05"> </td>
						<td class="allow" v-on:click="save('wednesday_06')" id="wednesday_06"> </td>
						<td class="allow" v-on:click="save('wednesday_07')" id="wednesday_07"> </td>
						<td class="allow" v-on:click="save('wednesday_08')" id="wednesday_08"> </td>
						<td class="allow" v-on:click="save('wednesday_09')" id="wednesday_09"> </td>
						<td class="allow" v-on:click="save('wednesday_10')" id="wednesday_10"> </td>
						<td class="allow" v-on:click="save('wednesday_11')" id="wednesday_11"> </td>
						<td class="allow" v-on:click="save('wednesday_12')" id="wednesday_12"> </td>
						<td class="allow" v-on:click="save('wednesday_13')" id="wednesday_13"> </td>
						<td class="allow" v-on:click="save('wednesday_14')" id="wednesday_14"> </td>
						<td class="allow" v-on:click="save('wednesday_15')" id="wednesday_15"> </td>
						<td class="allow" v-on:click="save('wednesday_16')" id="wednesday_16"> </td>
						<td class="allow" v-on:click="save('wednesday_17')" id="wednesday_17"> </td>
						<td class="allow" v-on:click="save('wednesday_18')" id="wednesday_18"> </td>
						<td class="allow" v-on:click="save('wednesday_19')" id="wednesday_19"> </td>
						<td class="allow" v-on:click="save('wednesday_20')" id="wednesday_20"> </td>
						<td class="allow" v-on:click="save('wednesday_21')" id="wednesday_21"> </td>
						<td class="allow" v-on:click="save('wednesday_22')" id="wednesday_22"> </td>
						<td class="allow" v-on:click="save('wednesday_23')" id="wednesday_23"> </td>
					</tr>
	
					<tr>
						<th scope="row">Thursday</th>
						<td class="allow" v-on:click="save('thursday_00')" id="thursday_00"> </td>
						<td class="allow" v-on:click="save('thursday_01')" id="thursday_01"> </td>
						<td class="allow" v-on:click="save('thursday_02')" id="thursday_02"> </td>
						<td class="allow" v-on:click="save('thursday_03')" id="thursday_03"> </td>
						<td class="allow" v-on:click="save('thursday_04')" id="thursday_04"> </td>
						<td class="allow" v-on:click="save('thursday_05')" id="thursday_05"> </td>
						<td class="allow" v-on:click="save('thursday_06')" id="thursday_06"> </td>
						<td class="allow" v-on:click="save('thursday_07')" id="thursday_07"> </td>
						<td class="allow" v-on:click="save('thursday_08')" id="thursday_08"> </td>
						<td class="allow" v-on:click="save('thursday_09')" id="thursday_09"> </td>
						<td class="allow" v-on:click="save('thursday_10')" id="thursday_10"> </td>
						<td class="allow" v-on:click="save('thursday_11')" id="thursday_11"> </td>
						<td class="allow" v-on:click="save('thursday_12')" id="thursday_12"> </td>
						<td class="allow" v-on:click="save('thursday_13')" id="thursday_13"> </td>
						<td class="allow" v-on:click="save('thursday_14')" id="thursday_14"> </td>
						<td class="allow" v-on:click="save('thursday_15')" id="thursday_15"> </td>
						<td class="allow" v-on:click="save('thursday_16')" id="thursday_16"> </td>
						<td class="allow" v-on:click="save('thursday_17')" id="thursday_17"> </td>
						<td class="allow" v-on:click="save('thursday_18')" id="thursday_18"> </td>
						<td class="allow" v-on:click="save('thursday_19')" id="thursday_19"> </td>
						<td class="allow" v-on:click="save('thursday_20')" id="thursday_20"> </td>
						<td class="allow" v-on:click="save('thursday_21')" id="thursday_21"> </td>
						<td class="allow" v-on:click="save('thursday_22')" id="thursday_22"> </td>
						<td class="allow" v-on:click="save('thursday_23')" id="thursday_23"> </td>
					</tr>
	
					<tr>
						<th scope="row">Friday</th>
						<td class="allow" v-on:click="save('friday_00')" id="friday_00"> </td>
						<td class="allow" v-on:click="save('friday_01')" id="friday_01"> </td>
						<td class="allow" v-on:click="save('friday_02')" id="friday_02"> </td>
						<td class="allow" v-on:click="save('friday_03')" id="friday_03"> </td>
						<td class="allow" v-on:click="save('friday_04')" id="friday_04"> </td>
						<td class="allow" v-on:click="save('friday_05')" id="friday_05"> </td>
						<td class="allow" v-on:click="save('friday_06')" id="friday_06"> </td>
						<td class="allow" v-on:click="save('friday_07')" id="friday_07"> </td>
						<td class="allow" v-on:click="save('friday_08')" id="friday_08"> </td>
						<td class="allow" v-on:click="save('friday_09')" id="friday_09"> </td>
						<td class="allow" v-on:click="save('friday_10')" id="friday_10"> </td>
						<td class="allow" v-on:click="save('friday_11')" id="friday_11"> </td>
						<td class="allow" v-on:click="save('friday_12')" id="friday_12"> </td>
						<td class="allow" v-on:click="save('friday_13')" id="friday_13"> </td>
						<td class="allow" v-on:click="save('friday_14')" id="friday_14"> </td>
						<td class="allow" v-on:click="save('friday_15')" id="friday_15"> </td>
						<td class="allow" v-on:click="save('friday_16')" id="friday_16"> </td>
						<td class="allow" v-on:click="save('friday_17')" id="friday_17"> </td>
						<td class="allow" v-on:click="save('friday_18')" id="friday_18"> </td>
						<td class="allow" v-on:click="save('friday_19')" id="friday_19"> </td>
						<td class="allow" v-on:click="save('friday_20')" id="friday_20"> </td>
						<td class="allow" v-on:click="save('friday_21')" id="friday_21"> </td>
						<td class="allow" v-on:click="save('friday_22')" id="friday_22"> </td>
						<td class="allow" v-on:click="save('friday_23')" id="friday_23"> </td>
					</tr>
	
					<tr>
						<th scope="row">Saturday</th>
						<td class="allow" v-on:click="save('saturday_00')" id="saturday_00"> </td>
						<td class="allow" v-on:click="save('saturday_01')" id="saturday_01"> </td>
						<td class="allow" v-on:click="save('saturday_02')" id="saturday_02"> </td>
						<td class="allow" v-on:click="save('saturday_03')" id="saturday_03"> </td>
						<td class="allow" v-on:click="save('saturday_04')" id="saturday_04"> </td>
						<td class="allow" v-on:click="save('saturday_05')" id="saturday_05"> </td>
						<td class="allow" v-on:click="save('saturday_06')" id="saturday_06"> </td>
						<td class="allow" v-on:click="save('saturday_07')" id="saturday_07"> </td>
						<td class="allow" v-on:click="save('saturday_08')" id="saturday_08"> </td>
						<td class="allow" v-on:click="save('saturday_09')" id="saturday_09"> </td>
						<td class="allow" v-on:click="save('saturday_10')" id="saturday_10"> </td>
						<td class="allow" v-on:click="save('saturday_11')" id="saturday_11"> </td>
						<td class="allow" v-on:click="save('saturday_12')" id="saturday_12"> </td>
						<td class="allow" v-on:click="save('saturday_13')" id="saturday_13"> </td>
						<td class="allow" v-on:click="save('saturday_14')" id="saturday_14"> </td>
						<td class="allow" v-on:click="save('saturday_15')" id="saturday_15"> </td>
						<td class="allow" v-on:click="save('saturday_16')" id="saturday_16"> </td>
						<td class="allow" v-on:click="save('saturday_17')" id="saturday_17"> </td>
						<td class="allow" v-on:click="save('saturday_18')" id="saturday_18"> </td>
						<td class="allow" v-on:click="save('saturday_19')" id="saturday_19"> </td>
						<td class="allow" v-on:click="save('saturday_20')" id="saturday_20"> </td>
						<td class="allow" v-on:click="save('saturday_21')" id="saturday_21"> </td>
						<td class="allow" v-on:click="save('saturday_22')" id="saturday_22"> </td>
						<td class="allow" v-on:click="save('saturday_23')" id="saturday_23"> </td>
					</tr>
	
					<tr>
						<th scope="row">Sunday</th>
						<td class="allow"  v-on:click="save('sunday_00')" id="sunday_00"> </td>
						<td class="allow"  v-on:click="save('sunday_01')" id="sunday_01"> </td>
						<td class="allow"  v-on:click="save('sunday_02')" id="sunday_02"> </td>
						<td class="allow"  v-on:click="save('sunday_03')" id="sunday_03"> </td>
						<td class="allow"  v-on:click="save('sunday_04')" id="sunday_04"> </td>
						<td class="allow"  v-on:click="save('sunday_05')" id="sunday_05"> </td>
						<td class="allow"  v-on:click="save('sunday_06')" id="sunday_06"> </td>
						<td class="allow"  v-on:click="save('sunday_07')" id="sunday_07"> </td>
						<td class="allow"  v-on:click="save('sunday_08')" id="sunday_08"> </td>
						<td class="allow"  v-on:click="save('sunday_09')" id="sunday_09"> </td>
						<td class="allow"  v-on:click="save('sunday_10')" id="sunday_10"> </td>
						<td class="allow"  v-on:click="save('sunday_11')" id="sunday_11"> </td>
						<td class="allow"  v-on:click="save('sunday_12')" id="sunday_12"> </td>
						<td class="allow"  v-on:click="save('sunday_13')" id="sunday_13"> </td>
						<td class="allow"  v-on:click="save('sunday_14')" id="sunday_14"> </td>
						<td class="allow"  v-on:click="save('sunday_15')" id="sunday_15"> </td>
						<td class="allow"  v-on:click="save('sunday_16')" id="sunday_16"> </td>
						<td class="allow"  v-on:click="save('sunday_17')" id="sunday_17"> </td>
						<td class="allow"  v-on:click="save('sunday_18')" id="sunday_18"> </td>
						<td class="allow"  v-on:click="save('sunday_19')" id="sunday_19"> </td>
						<td class="allow"  v-on:click="save('sunday_20')" id="sunday_20"> </td>
						<td class="allow"  v-on:click="save('sunday_21')" id="sunday_21"> </td>
						<td class="allow"  v-on:click="save('sunday_22')" id="sunday_22"> </td>
						<td class="allow"  v-on:click="save('sunday_23')" id="sunday_23"> </td>
					</tr>
				</tbody>
</table>
		</div>
	</main>
</template>
<style>
	div#schedule td{margin:6px;border:1px solid #353535;}
	div#schedule td.allow { background:green;}
	div#schedule td.deny { background:red;}
</style>
<script>
	export default {
		props: ['id', 'user_id'],
		data() {
			return {
				loading: true,
				user: {}
			};
		},
		methods: {
			loadAccount: function() {
				axios.get('api/v1/account')
				.then((response) => {
					this.user = response.data;
					this.loadSchedule();
				});
			},
			loadSchedule: function() {
				this.loading = true;
				axios.get('api/v1/schedule/' + this.user.id)
				.then((response) => {
					this.schedule = response.data;
					for (this.i=0;this.schedule.length >= this.i;this.i++){
						document.getElementById(this.schedule[this.i]['datetime']).className = 'deny'
					}
					this.loading = false;
				});
			},
			flip: function(id) {
			},
			save: function(id) {
				this.flipElement = document.getElementById(id);
				if (this.flipElement.className.indexOf('allow') != -1){
					
					// allow to deny
					this.flipElement.className = 'deny'
					axios.get('api/v1/schedule/' + this.user.id + '/' + id + '/deny')
					.then((response) => {
						console.log('changed to deny');
					});
				} else {
					
					// deny to allow
					this.flipElement.className = 'allow'
					axios.get('api/v1/schedule/' + this.user.id + '/' + id + '/allow')
					.then((response) => {
						console.log('changed to allow');
					});
								
				}
					
			},
			
		},
		watch: {
		   '$route': 'loadSchedule'
		},
		mounted() {
			this.loadAccount();
			
		}
	}
</script>
